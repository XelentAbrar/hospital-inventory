<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalInventory\Models\Inventory\Tax;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\TaxRequest;

class TaxController extends Controller
{


    public function __construct()
    {
        $this->middleware('checkPermission:taxes.index')->only('index');
        $this->middleware('checkPermission:taxes.create')->only('create', 'store');
        $this->middleware('checkPermission:taxes.show')->only('show');
        $this->middleware('checkPermission:taxes.edit')->only('edit', 'update');
        $this->middleware('checkPermission:taxes.destroy')->only('destroy');
    }

    public function index()
    {
        $taxes = Tax::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Tax/Index', [
            'taxes' => $taxes,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Tax/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(TaxRequest $request)
    {
        DB::beginTransaction();

        $tax = new Tax();
        $formData = $request->only($tax->getFillable());

        Tax::create($formData);

        DB::commit();

        return redirect()->route('taxes.index');
    }


    public function edit(Tax $tax)
    {
        return Inertia::render('Inventory/Tax/Create', [
            'tax' => $tax,
        ]);
    }


    public function update(TaxRequest $request, Tax $tax)
    {
        DB::beginTransaction();

        $formData = $request->only($tax->getFillable());
        $tax->update($formData);

        DB::commit();

        return redirect()->route('taxes.index');
    }


    public function destroy(Tax $tax)
    {
        $tax->delete();
        return Inertia::location(route('taxes.index'));
    }
}
