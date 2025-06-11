<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use XelentAbrar\HospitalInventory\Models\Inventory\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\CustomerRequest;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Customer/Index', [
            'customers' => $customers,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $countries = Country::all();

        return Inertia::render('Inventory/Customer/Create', [
            'csrf_token' => csrf_token(),
            'countries' => $countries,
        ]);
    }

    public function store(CustomerRequest $request)
    {

        DB::beginTransaction();

        $customer = new Customer();
        $customerData = $request->only($customer->getFillable());

        $customer = Customer::create($customerData);

        DB::commit();
        return redirect()->route('customers.index');
    }


    public function edit(Customer $customer)
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $states = [];
        $cities = [];

        if ($customer->country_id) {
            $states = State::where('country_id', $customer->country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        if ($customer->state_id) {
            $cities = City::where('state_id', $customer->state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }
        return Inertia::render('Inventory/Customer/Create', [
            'customer' => $customer,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        DB::beginTransaction();

        $customerData = $request->only($customer->getFillable());

        $customer->update($customerData);

        DB::commit();


        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return Redirect::route('customers.index');
    }
}
