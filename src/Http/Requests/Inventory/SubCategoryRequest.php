<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use XelentAbrar\HospitalInventory\Models\Inventory\Category;
use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'category_id' => 'required|exists:' . Category::class . ',id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
        ];
    }
}
