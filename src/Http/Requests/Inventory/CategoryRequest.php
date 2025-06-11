<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use App\Models\Accounts\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'coa_id' => 'required|exists:'. ChartOfAccount::class .',id',
        ];

        return $rules;
    }
}
