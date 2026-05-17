<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CallSlot;
use App\Models\Project;
use App\Models\ProjectCall;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CallController extends Controller
{
    public function create(Project $project)
    {
        // Autorização básica (já coberta por Policy no mundo ideal, mas vamos checar a ownership e status)
        if ($project->client_id !== auth()->id()) {
            abort(403);
        }

        // Apenas projetos em progresso ou testes permitem call agendada
        if (!in_array($project->status, ['in_progress', 'testing'])) {
            return redirect()->route('projects.show', $project)->withErrors(['calls' => 'Apenas projetos ativos podem ter agendamentos.']);
        }

        // Buscar slots disponíveis globais ou específicos do projeto a partir de 24h no futuro
        $minDate = now()->addHours(24);
        $slots = CallSlot::where('is_booked', false)
            ->where(function ($q) use ($project) {
                $q->whereNull('project_id')->orWhere('project_id', $project->id);
            })
            ->where('start_time', '>=', $minDate)
            ->orderBy('start_time')
            ->get();

        return view('client.calls.create', compact('project', 'slots'));
    }

    public function store(Request $request, Project $project)
    {
        if ($project->client_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'call_slot_id' => 'required|exists:call_slots,id',
            'type'         => 'required|in:status_alignment,delivery_review,urgency',
            'agenda'       => 'required|string|max:1000',
        ]);

        $slot = CallSlot::findOrFail($data['call_slot_id']);

        if ($slot->is_booked || $slot->start_time < now()->addHours(24)) {
            throw ValidationException::withMessages(['call_slot_id' => 'Este horário não está mais disponível ou não atende a antecedência mínima de 24h.']);
        }

        // Bloqueia e cria
        $slot->update(['is_booked' => true]);

        ProjectCall::create([
            'project_id'   => $project->id,
            'call_slot_id' => $slot->id,
            'type'         => $data['type'],
            'status'       => 'pending',
            'scheduled_at' => $slot->start_time,
            'agenda'       => $data['agenda'],
        ]);

        return redirect()->route('projects.calls.index', $project)->with('success', 'Call agendada com sucesso!');
    }

    public function index(Project $project)
    {
        if ($project->client_id !== auth()->id()) abort(403);

        $calls = ProjectCall::where('project_id', $project->id)
            ->with('slot')
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('client.calls.index', compact('project', 'calls'));
    }
}
