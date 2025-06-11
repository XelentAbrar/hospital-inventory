<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Http\Request;
use XelentAbrar\HospitalInventory\Models\Inventory\Salt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\SaltRequest;

class SaltController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:salts.index')->only('index');
        $this->middleware('checkPermission:salts.create')->only('create', 'store');
        $this->middleware('checkPermission:salts.show')->only('show');
        $this->middleware('checkPermission:salts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:salts.destroy')->only('destroy');
    }

    public function index()
    {
        $salts = Salt::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Salt/Index', [
            'salts' => $salts,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Salt/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(SaltRequest $request)
    {
        DB::beginTransaction();

        $salt = new Salt();
        $formData = $request->only($salt->getFillable());

        Salt::create($formData);

        DB::commit();

        return redirect()->route('salts.index');
    }


    public function edit(Salt $salt)
    {
        return Inertia::render('Inventory/Salt/Create', [
            'salt' => $salt,
        ]);
    }


    public function update(SaltRequest $request, Salt $salt)
    {
        DB::beginTransaction();

        $formData = $request->only($salt->getFillable());
        $salt->update($formData);

        DB::commit();

        return redirect()->route('salts.index');
    }


    public function destroy(Salt $salt)
    {
        $salt->delete();
        return Inertia::location(route('salts.index'));
    }
}
