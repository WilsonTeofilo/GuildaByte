<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the client's projects.
     */
    public function index(): View
    {
        // Pega os projetos do cliente autenticado
        // 'with' para evitar N+1
        $projects = Auth::user()->clientProjects()
            ->with(['packageVersion', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.projects.index', compact('projects'));
    }

    /**
     * Display the specific project details.
     */
    public function show(string $id): View
    {
        $project = Auth::user()->clientProjects()
            ->with(['packageVersion', 'status', 'addendums', 'messages'])
            ->findOrFail($id);

        return view('client.projects.show', compact('project'));
    }
}
