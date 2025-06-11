<?php

namespace XelentAbrar\HospitalInventory\Http\Requests\Inventory;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'landline' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|max:255',
            'city_id' => 'nullable|exists:' . City::class . ',id',
            'state_id' => 'nullable|exists:' . State::class . ',id',
            'country_id' => 'nullable|exists:' . Country::class . ',id',
            'logo' => 'nullable|string|max:255',
            'opening_balance' => 'nullable|numeric',
            'opening_type' => 'nullable|in:payable,receiveable',
            'tax_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'country_id' => 'country',
            'state_id' => 'state',
            'city_id' => 'city',
        ];
    }
}
