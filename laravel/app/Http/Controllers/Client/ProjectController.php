<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Actions\Client\AcceptAddendumAction;
use Illuminate\View\View;

/**
 * Controller limpo de projetos do cliente.
 * Usa Route Model Binding com scope de ownership automático.
 */
class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = $request->user()
            ->clientProjects()
            ->active()                        // scope: exclui cancelled/archived
            ->latest('updated_at')
            ->get();

        return view('client.projects.index', compact('projects'));
    }

    public function show(Request $request, Project $project): View
    {
        // Garante que o projeto pertence ao cliente (autorização no servidor)
        abort_unless($project->client_id === $request->user()->id, 403);

        $project->load(['contractAcceptances']);

        return view('client.projects.show', compact('project'));
    }

    public function acceptAddendum(Request $request, \App\Models\ScopeAddendum $addendum, AcceptAddendumAction $action)
    {
        $action->execute($addendum, $request->ip(), $request->userAgent());

        return redirect()->back()->with('success', 'Aditivo de escopo aceito digitalmente.');
    }
}
