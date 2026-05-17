<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallSlot;
use App\Models\Project;
use App\Models\ProjectCall;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;

class CallController extends Controller
{
    // ── Dashboard de calls ─────────────────────────────────────────

    public function index()
    {
        $upcoming = ProjectCall::with(['project.client'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('scheduled_at')
            ->get();

        $active = ProjectCall::with(['project.client'])
            ->where('status', 'active')
            ->first();

        return view('admin.calls.index', compact('upcoming', 'active'));
    }

    // ── Confirmação/Rejeição ────────────────────────────────────────

    public function confirm(ProjectCall $call)
    {
        if ($call->status !== 'pending') abort(409);

        $call->update(['status' => 'confirmed']);

        return back()->with('success', "Call confirmada para {$call->scheduled_at->format('d/m H:i')}.");
    }

    public function reject(Request $request, ProjectCall $call)
    {
        $data = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!in_array($call->status, ['pending', 'confirmed'])) abort(409);

        // Libera o slot para ser reutilizado
        if ($call->call_slot_id) {
            CallSlot::find($call->call_slot_id)?->update(['is_booked' => false]);
        }

        $call->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('success', 'Call rejeitada e cliente será notificado.');
    }

    public function start(ProjectCall $call)
    {
        if ($call->status !== 'confirmed') abort(409);
        $call->update(['status' => 'active']);
        return redirect()->route('admin.calls.active', $call);
    }

    public function active(ProjectCall $call)
    {
        return view('admin.calls.active', compact('call'));
    }

    public function complete(Request $request, ProjectCall $call)
    {
        $data = $request->validate([
            'admin_notes'       => 'nullable|string|max:5000',
            'duration_minutes'  => 'nullable|integer|min:1',
            'checklist'         => 'nullable|array',
            'checklist.*.text'  => 'required|string',
            'checklist.*.done'  => 'boolean',
        ]);

        if ($call->status !== 'active') abort(409);

        $call->update([
            'status'           => 'completed',
            'admin_notes'      => $data['admin_notes'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'checklist'        => $data['checklist'] ?? null,
        ]);

        return redirect()->route('admin.calls.index')->with('success', 'Call encerrada e ata salva.');
    }

    // ── Gestão de Slots ─────────────────────────────────────────────

    public function slots()
    {
        $slots = CallSlot::with('project')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        $projects = Project::whereIn('status', ['in_progress', 'testing'])
            ->with('client')
            ->get();

        return view('admin.calls.slots', compact('slots', 'projects'));
    }

    public function storeSlot(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'start_time' => 'required|date|after:+24 hours',
        ]);

        CallSlot::create([
            'project_id' => $data['project_id'] ?? null,
            'start_time' => $data['start_time'],
            'is_booked'  => false,
        ]);

        return back()->with('success', 'Slot criado com sucesso.');
    }

    public function destroySlot(CallSlot $slot)
    {
        if ($slot->is_booked) {
            return back()->withErrors(['slot' => 'Este slot já foi reservado por um cliente.']);
        }
        $slot->delete();
        return back()->with('success', 'Slot removido.');
    }

    // ── Chat Admin ──────────────────────────────────────────────────

    public function chat(Project $project)
    {
        $messages = ProjectMessage::where('project_id', $project->id)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        // Marca mensagens do cliente como lidas
        ProjectMessage::where('project_id', $project->id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $projects = Project::whereIn('status', ['in_progress', 'testing'])->with('client')->get();

        return view('admin.calls.chat', compact('project', 'messages', 'projects'));
    }

    public function sendMessage(Request $request, Project $project)
    {
        $request->validate(['content' => 'required|string|max:2000']);

        ProjectMessage::create([
            'project_id' => $project->id,
            'user_id'    => auth()->id(),
            'content'    => $request->input('content'),
        ]);

        return back();
    }
}
