<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use XelentAbrar\HospitalInventory\Models\Inventory\Supplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\SupplierContact;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\SupplierRequest;

class SupplierController extends Controller
{


    public function __construct()
    {
        $this->middleware('checkPermission:suppliers.index')->only('index');
        $this->middleware('checkPermission:suppliers.create')->only('create', 'store');
        $this->middleware('checkPermission:suppliers.show')->only('show');
        $this->middleware('checkPermission:suppliers.edit')->only('edit', 'update');
        $this->middleware('checkPermission:suppliers.destroy')->only('destroy');
    }

    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Supplier/Index', [
            'suppliers' => $suppliers,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $countries = Country::all();

        return Inertia::render('Inventory/Supplier/Create', [
            'csrf_token' => csrf_token(),
            'countries' => $countries,
        ]);
    }

    public function store(SupplierRequest $request)
    {

        DB::beginTransaction();

        $uniqueFilename = $request->logo;

        $supplier = new Supplier();
        $supplierData = $request->only($supplier->getFillable());

        $supplier = Supplier::create($supplierData);

        if (!file_exists(public_path() . '/uploads/Inventory/Suppliers')) {
            mkdir(public_path() . '/uploads/Inventory/Suppliers');
        }
        if (!empty($uniqueFilename)) {

            $source = public_path('storage/uploads/Inventory/' . $uniqueFilename);
            $destination = public_path('uploads/Inventory/Suppliers/' . $uniqueFilename);

            // Check if source file exists before trying to copy
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }
        }

        if (!empty($request->supplier_contact)) {
            $supplierContactData = [];

            foreach ($request->supplier_contact as $contact) {
                if (!empty($contact['name'])) {
                    $supplierContactData[] = new SupplierContact($contact);
                }
            }

            $supplier->supplier_contact()->saveMany($supplierContactData);
        }

        DB::commit();
        return redirect()->route('suppliers.index');
    }


    public function edit(Supplier $supplier)
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $supplier->load('supplier_contact');
        $states = [];
        $cities = [];

        if ($supplier->country_id) {
            $states = State::where('country_id', $supplier->country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        if ($supplier->state_id) {
            $cities = City::where('state_id', $supplier->state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }
        return Inertia::render('Inventory/Supplier/Create', [
            'supplier' => $supplier,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        DB::beginTransaction();
        $oldImage = $supplier->logo;
        $newImage = $request->logo;

        if ($oldImage !== $newImage) {

            $source = public_path('storage/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Suppliers/' . $newImage);

            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            if (!empty($oldImage)) {
                $oldImagePath = public_path('uploads/Inventory/Suppliers/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $supplier->logo = $newImage;
        }
        $supplierData = $request->only($supplier->getFillable());

        $supplier->update($supplierData);

        $supplier->supplier_contact()->delete();
        foreach ($request->supplier_contact as $supplierContactData) {
            $supplier_contact = new SupplierContact($supplierContactData);
            $supplier->supplier_contact()->save($supplier_contact);
        }

        DB::commit();


        return redirect()->route('suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return Inertia::location(route('suppliers.index'));
    }
}
