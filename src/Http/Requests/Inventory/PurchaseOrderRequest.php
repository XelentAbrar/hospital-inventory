<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use XelentAbrar\HospitalInventory\Models\Inventory\Supplier;
use XelentAbrar\HospitalInventory\Models\Inventory\Tax;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
            'supplier_id' => 'required|exists:' . Supplier::class . ',id',
            'detail' => 'array|min:0|not_in:0',
            'detail.*.product_id' => 'required|exists:' . Product::class . ',id',
            'detail.*.tax_id' => 'nullable|exists:' . Tax::class . ',id',
            'detail.*.qty' => 'required|min:1',
            'detail.*.price' => 'required|regex:/^\d+(\.\d{1,6})?$/',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'supplier_id' => 'supplier',
            'detail.*.tax_id' => 'tax',
            'detail.*.product_id' => 'product',
            'detail.*.qty' => 'qty',
            'detail.*.price' => 'price',
        ];
    }
}
