<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('clientProfile');

        // Carrega projetos em uma única query otimizada
        $projects = $user
            ->clientProjects()
            ->active()
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
