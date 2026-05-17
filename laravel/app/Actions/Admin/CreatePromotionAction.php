<?php

namespace App\Actions\Admin;

use App\Models\Promotion;

class CreatePromotionAction
{
    public function execute(array $data): Promotion
    {
        return Promotion::create([
            'code' => $data['code'],
            'description' => $data['description'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'is_active' => true,
        ]);
    }
}
