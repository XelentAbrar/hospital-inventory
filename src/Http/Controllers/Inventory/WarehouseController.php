<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Traits\File;
use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use XelentAbrar\HospitalInventory\Models\Inventory\Warehouse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\WarehouseRequest;

class WarehouseController extends Controller
{

    use File;

    public function __construct()
    {
        $this->middleware('checkPermission:warehouses.index')->only('index');
        $this->middleware('checkPermission:warehouses.create')->only('create', 'store');
        $this->middleware('checkPermission:warehouses.show')->only('show');
        $this->middleware('checkPermission:warehouses.edit')->only('edit', 'update');
        $this->middleware('checkPermission:warehouses.destroy')->only('destroy');
    }

    public function index()
    {
        $warehouses = Warehouse::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Warehouse/Index', [
            'warehouses' => $warehouses,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Warehouse/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(WarehouseRequest $request)
    {

        DB::beginTransaction();

        $warehouse = new Warehouse();
        $formData = $request->only($warehouse->getFillable());

        Warehouse::create($formData);


        if (!file_exists(public_path() . '/uploads/Inventory/Warehouse')) {
            mkdir(public_path() . '/uploads/Inventory/Warehouse');
        }
        if (isset($uniqueFilename)) {
            $source = public_path('storage/uploads/Inventory/' . $uniqueFilename);
            $destination = public_path('uploads/Inventory/Warehouse/' . $uniqueFilename);
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }
        }
        DB::commit();

        return redirect()->route('warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        return Inertia::render('Inventory/Warehouse/Create', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        DB::beginTransaction();

        $oldImage = $warehouse->logo;
        $newImage = $request->logo;

        if ($oldImage !== $newImage) {
            $source = public_path('storage/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Warehouse/'  . $newImage);
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            $oldImagePath = public_path('uploads/Inventory/Warehouse' . $oldImage);
            if (isset($oldImage) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $formData = $request->only($warehouse->getFillable());
        $warehouse->update($formData);

        DB::commit();

        return redirect()->route('warehouses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return Inertia::location(route('warehouses.index'));
    }
}
