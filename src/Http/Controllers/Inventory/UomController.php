<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalInventory\Models\Inventory\Uom;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\UomRequest;

class UomController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:uoms.index')->only('index');
        $this->middleware('checkPermission:uoms.create')->only('create', 'store');
        $this->middleware('checkPermission:uoms.show')->only('show');
        $this->middleware('checkPermission:uoms.edit')->only('edit', 'update');
        $this->middleware('checkPermission:uoms.destroy')->only('destroy');
    }

    public function index()
    {
        $uoms = Uom::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Uom/Index', [
            'uoms' => $uoms,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Uom/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(UomRequest $request)
    {
        DB::beginTransaction();

        $uom = new Uom();
        $formData = $request->only($uom->getFillable());

        Uom::create($formData);

        DB::commit();

        return redirect()->route('uoms.index');
    }

    public function edit(Uom $uom)
    {
        return Inertia::render('Inventory/Uom/Create', [
            'uom' => $uom,
        ]);
    }

    public function update(UomRequest $request, Uom $uom)
    {
        DB::beginTransaction();

        $formData = $request->only($uom->getFillable());
        $uom->update($formData);

        DB::commit();

        return redirect()->route('uoms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uom $uom)
    {
        $uom->delete();
        return Inertia::location(route('uoms.index'));
    }
}
