<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'abrv' => 'nullable|string',
            'type' => 'required|in:length,weight,volume,time,speed,area,other',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }
}
