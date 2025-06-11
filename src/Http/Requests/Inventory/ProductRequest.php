<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use XelentAbrar\HospitalInventory\Models\Inventory\ItemCategory;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemType;
use XelentAbrar\HospitalInventory\Models\Inventory\Salt;
use XelentAbrar\HospitalInventory\Models\Inventory\Uom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'sku' => 'nullable|unique:products,sku,' . $this->id,
            'name' => 'required|string',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'slug' => 'required|unique:products,slug,' . $this->id,
            'product_code' => 'nullable|unique:products,product_code,' . $this->id,
            'featured_image' => 'nullable|string',
            'uom_id' => 'nullable|exists:' . Uom::class . ',id',
            'item_salt_id' => 'required|exists:' . Salt::class . ',id',
            'item_type_id' => 'required|exists:' . ItemType::class . ',id',
            'item_category_id' => 'required|exists:' . ItemCategory::class . ',id',
            'opening_stock' => 'required|integer',
            'max_qty' => 'nullable',
            'min_qty' => 'nullable',
            'short_description' => 'nullable|string',
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'uom_id' => 'uom',
        ];
    }
}
