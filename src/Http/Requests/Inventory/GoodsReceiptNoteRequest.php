<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepass;
use Illuminate\Foundation\Http\FormRequest;

class GoodsReceiptNoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'date' => 'required|date',
            'remarks' => 'nullable|string',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'inward_gatepass_id' => 'required|exists:' . InwardGatepass::class . ',id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'inward_gatepass_id' => 'inward gatepass',
        ];
    }
}
