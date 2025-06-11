<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemCategory;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\ItemCategoryRequest;

class ItemCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:item_categories.index')->only('index');
        $this->middleware('checkPermission:item_categories.create')->only('create', 'store');
        $this->middleware('checkPermission:item_categories.show')->only('show');
        $this->middleware('checkPermission:item_categories.edit')->only('edit', 'update');
        $this->middleware('checkPermission:item_categories.destroy')->only('destroy');
    }

    public function index()
    {
        $item_categories = ItemCategory::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/ItemCategory/Index', [
            'item_categories' => $item_categories,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/ItemCategory/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(ItemCategoryRequest $request)
    {
        DB::beginTransaction();

        $item_category = new ItemCategory();
        $formData = $request->only($item_category->getFillable());

        ItemCategory::create($formData);

        DB::commit();

        return redirect()->route('item_categories.index');
    }


    public function edit(ItemCategory $item_category)
    {
        return Inertia::render('Inventory/ItemCategory/Create', [
            'item_category' => $item_category,
        ]);
    }


    public function update(ItemCategoryRequest $request, ItemCategory $item_category)
    {
        DB::beginTransaction();

        $formData = $request->only($item_category->getFillable());
        $item_category->update($formData);

        DB::commit();

        return redirect()->route('item_categories.index');
    }


    public function destroy(ItemCategory $item_category)
    {
        $item_category->delete();
        return Inertia::location(route('item_categories.index'));
    }
}
