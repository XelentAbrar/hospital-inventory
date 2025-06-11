<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Http\Request;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\ItemTypeRequest;

class ItemTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:item_types.index')->only('index');
        $this->middleware('checkPermission:item_types.create')->only('create', 'store');
        $this->middleware('checkPermission:item_types.show')->only('show');
        $this->middleware('checkPermission:item_types.edit')->only('edit', 'update');
        $this->middleware('checkPermission:item_types.destroy')->only('destroy');
    }

    public function index()
    {
        $item_types = ItemType::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/ItemType/Index', [
            'item_types' => $item_types,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/ItemType/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(ItemTypeRequest $request)
    {
        DB::beginTransaction();

        $item_type = new ItemType();
        $formData = $request->only($item_type->getFillable());

        ItemType::create($formData);

        DB::commit();

        return redirect()->route('item_types.index');
    }


    public function edit(ItemType $item_type)
    {
        return Inertia::render('Inventory/ItemType/Create', [
            'item_type' => $item_type,
        ]);
    }


    public function update(ItemTypeRequest $request, ItemType $item_type)
    {
        DB::beginTransaction();

        $formData = $request->only($item_type->getFillable());
        $item_type->update($formData);

        DB::commit();

        return redirect()->route('item_types.index');
    }


    public function destroy(ItemType $item_type)
    {
        $item_type->delete();
        return Inertia::location(route('item_types.index'));
    }
}
