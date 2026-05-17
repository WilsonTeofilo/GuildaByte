<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Project $project)
    {
        if ($project->client_id !== auth()->id()) abort(403);

        $messages = ProjectMessage::where('project_id', $project->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar todas como lidas (se fossem enviadas pelo admin)
        ProjectMessage::where('project_id', $project->id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('client.chat.index', compact('project', 'messages'));
    }

    public function store(Request $request, Project $project)
    {
        if ($project->client_id !== auth()->id()) abort(403);

        $request->validate([
            'content' => 'required_without:attachment|string|max:2000',
            // File upload logic easily added here
        ]);

        ProjectMessage::create([
            'project_id' => $project->id,
            'user_id'    => auth()->id(),
            'content'    => $request->input('content'),
        ]);

        // Retorno apropriado dependendo de requisição web ou axios
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
