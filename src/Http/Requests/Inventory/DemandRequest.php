<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use App\Models\City;
use App\Models\Country;
use App\Models\HRMS\Department;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;

class DemandRequest extends FormRequest
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
            'department_id' => 'nullable|exists:' . Department::class . ',id',
            'detail.*.product_id' => 'required|exists:' . Product::class . ',id',
            'detail.*.qty' => 'required|min:1',
            'detail' => 'array|min:0|not_in:0',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'department_id' => 'department',
            'detail.*.product_id' => 'product',
        ];
    }
}
