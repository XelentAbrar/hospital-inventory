<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class SaltRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'salt_name' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }
}
