<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateProposalAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreAddendumRequest;
use App\Actions\Admin\CreateAddendumAction;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /** Fila de pedidos FIFO (mais antigo primeiro = mais urgente) */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Project::with('client')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%{$search}%"));
            }))
            ->orderBy('created_at', 'asc'); // FIFO — mais antigo primeiro

        $projects  = $query->paginate(20)->appends($request->query());
        $statusCounts = [
            'received'          => Project::where('status', 'received')->count(),
            'in_analysis'       => Project::where('status', 'in_analysis')->count(),
            'proposal_sent'     => Project::where('status', 'proposal_sent')->count(),
            'proposal_accepted' => Project::where('status', 'proposal_accepted')->count(),
            'in_progress'       => Project::where('status', 'in_progress')->count(),
            'delivered'         => Project::where('status', 'delivered')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'statusCounts', 'status', 'search'));
    }

    /** Detalhe/Briefing do projeto — layout duas colunas */
    public function show(Project $project)
    {
        $project->load(['client', 'client.clientProfile']);
        $proposal  = Proposal::where('project_id', $project->id)->where('status', '!=', 'rejected')->latest()->first();
        $acceptance = $project->contractAcceptances()->latest()->first();

        return view('admin.projects.show', compact('project', 'proposal', 'acceptance'));
    }

    /** Muda status com validação de cadeia de estados */
    public function updateStatus(Request $request, Project $project)
    {
        $newStatus = $request->validate([
            'status' => ['required', 'string', 'in:in_analysis,proposal_sent,awaiting_payment,in_progress,testing,delivered,maintenance,cancelled'],
        ])['status'];

        $this->guardTransition($project, $newStatus);

        $extra = [];
        if ($newStatus === 'in_progress') {
            $extra['started_at'] = now();
        } elseif ($newStatus === 'delivered') {
            $extra['delivered_at'] = now();
        }

        $project->update(array_merge(['status' => $newStatus], $extra));

        return back()->with('success', "Status atualizado para: {$newStatus}");
    }

    /** Cria proposta formal (Sprint 2.3) */
    public function storeProposal(Request $request, Project $project)
    {
        // Impede segunda proposta ativa
        if (Proposal::where('project_id', $project->id)->where('status', 'pending')->exists()) {
            return back()->withErrors(['proposal' => 'Já existe uma proposta ativa para este projeto.']);
        }

        $data = $request->validate([
            'scope'                  => ['required', 'string', 'max:5000'],
            'proposed_value'         => ['required', 'numeric', 'min:0'],
            'proposed_deadline_days' => ['required', 'integer', 'min:1', 'max:730'],
            'includes'               => ['nullable', 'array'],
            'includes.*'             => ['string', 'max:200'],
            'excludes'               => ['nullable', 'array'],
            'excludes.*'             => ['string', 'max:200'],
        ]);

        try {
            CreateProposalAction::run($project, $data);
        } catch (\Throwable $e) {
            return back()->withErrors(['proposal' => $e->getMessage()]);
        }

        return back()->with('success', 'Proposta criada e projeto marcado como "Proposta Enviada".');
    }

    public function storeAddendum(StoreAddendumRequest $request, Project $project, CreateAddendumAction $action)
    {
        $action->execute($project, $request->validated());

        return redirect()->back()->with('success', 'Aditivo de escopo gerado e enviado para o cliente.');
    }

    // ── Validação de Cadeia de Transição ─────────────────────────
    private function guardTransition(Project $project, string $newStatus): void
    {
        $current = $project->status;

        // proposal_accepted só via aceite do cliente — nunca via admin
        if ($newStatus === 'proposal_accepted') {
            throw ValidationException::withMessages([
                'status' => 'Este status só pode ser definido pelo cliente ao aceitar a proposta.',
            ]);
        }

        // in_progress exige proposal_accepted registrado
        if ($newStatus === 'in_progress') {
            $hasAcceptance = $project->contractAcceptances()->exists();
            if (! $hasAcceptance) {
                throw ValidationException::withMessages([
                    'status' => 'Não é possível iniciar o desenvolvimento sem aceite formal da proposta pelo cliente.',
                ]);
            }
        }

        // delivered exige que esteja in_progress ou testing
        if ($newStatus === 'delivered' && ! in_array($current, ['in_progress', 'testing'])) {
            throw ValidationException::withMessages([
                'status' => 'O projeto deve estar em desenvolvimento ou testes para ser marcado como entregue.',
            ]);
        }
    }
}
