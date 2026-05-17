<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('clientProfile');

        // Carrega projetos, faturas e tickets em uma única query (anti N+1)
        $projects = $user
            ->clientProjects()
            ->active()
            ->with('packageVersion')
            ->latest('updated_at')
            ->get();

        $pendingPayments = $user->payments()
            ->where('status', 'pending')
            ->count();

        $openTickets = $user->supportTickets()
            ->whereIn('status', ['open', 'in_progress', 'triage'])
            ->count();

        return view('client.dashboard', compact(
            'projects',
            'pendingPayments',
            'openTickets',
        ));
    }
}
