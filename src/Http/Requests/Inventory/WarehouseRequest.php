<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        return $rules;
    }
}
