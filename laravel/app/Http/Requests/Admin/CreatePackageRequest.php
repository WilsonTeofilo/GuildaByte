<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatePackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'slug' => 'required|string|unique:packages',
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'features' => 'required|array'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('features') && is_array($this->features)) {
            // Se o primeiro item do array contiver quebras de linha, divide em array real.
            $featRaw = $this->features[0];
            if (is_string($featRaw) && strpos($featRaw, "\n") !== false) {
                $feats = array_filter(array_map('trim', explode("\n", $featRaw)));
                $this->merge(['features' => $feats]);
            }
        }
    }
}
