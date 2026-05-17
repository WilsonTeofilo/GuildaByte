<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreatePromotionRequest;
use App\Actions\Admin\CreatePromotionAction;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function store(CreatePromotionRequest $request, CreatePromotionAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.promotions.index')->with('success', 'Promoção criada com sucesso.');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return redirect()->route('admin.promotions.index')->with('success', 'Status da promoção alterado.');
    }
}
