<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialSnapshot;
use App\Models\Project;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $totalClients = User::where('user_type', 'client')->count();
        $totalRevenue = FinancialSnapshot::sum('agreed_price'); // Summing all snapshots as a simple metric

        return view('admin.dashboard', compact('totalProjects', 'totalClients', 'totalRevenue'));
    }
}
