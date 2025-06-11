<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Traits\File;
use Inertia\Inertia;
use App\Traits\Stock;
use App\Models\UserRole;
use XelentAbrar\HospitalInventory\Models\Inventory\Uom;
use XelentAbrar\HospitalInventory\Models\Inventory\Salt;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemType;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\ItemCategory;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\ProductRequest;

class ProductController extends Controller
{
    use File;
    use Stock;

    public function __construct()
    {
        $this->middleware('checkPermission:products.index')->only('index');
        $this->middleware('checkPermission:products.create')->only('create', 'store');
        $this->middleware('checkPermission:products.show')->only('show');
        $this->middleware('checkPermission:products.edit')->only('edit', 'update');
        $this->middleware('checkPermission:products.destroy')->only('destroy');
        $this->middleware('checkPermission:products.destroy')->only('destroy');
    }

    // public function index()
    // {
    //     $products = Product::with('uom')->orderBy('created_at', 'desc')->paginate(100);
    //     $user = Auth::user();
    //     $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
    //     return Inertia::render('Inventory/Product/Index', [
    //         'products' => $products,
    //         'role' => $role,
    //     ]);
    // }
    public function index()
    {
        $query = Product::with('uom')
        ->orderByRaw("status = 'inactive', name");
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];

            $query = $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('uom', function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
        }

        $products = $query->paginate(100);

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        return Inertia::render('Inventory/Product/Index', [
            'products' => $products,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $uoms = Uom::select('id', 'name')->where('status', '!=', 'inactive')->get();
        $salt_items = Salt::select('id', 'salt_name')->where('status', '!=', 'inactive')->get();
        $item_type = ItemType::select('id', 'type_name')->where('status', '!=', 'inactive')->get();
        $item_category = ItemCategory::select('id', 'cat_name')->where('status', '!=', 'inactive')->get();
        return Inertia::render('Inventory/Product/Create', [
            'uoms' => $uoms,
            'salt_items' => $salt_items,
            'item_type' => $item_type,
            'item_category' => $item_category,
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(ProductRequest $request)
    {
        if (!file_exists(public_path() . '/uploads/Inventory/Product')) {
            mkdir(public_path() . '/uploads/Inventory/Product');
        }
        if (!file_exists(public_path() . '/uploads/Inventory/Product/Digital')) {
            mkdir(public_path() . '/uploads/Inventory/Product/Digital');
        }

        $product = new Product();
        $productData = $request->only($product->getFillable());

        DB::beginTransaction();
        $productData['description'] = Purifier::clean($request['description']);
        $uniqueFilename = $request->featured_image;
        $productData['created_by'] = auth()->id();


        $product = Product::create($productData);
        $this->updateStock($product->id, 'opening_stock');
        $this->updateStock($product->id, 'min_qty');

        if (!empty($uniqueFilename)) {
            $source = public_path('storage/uploads/Inventory/' . $uniqueFilename);
            $destination = public_path('uploads/Inventory/Product/' . $uniqueFilename);
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }
        }

        DB::commit();

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $uoms = Uom::select('id', 'name')->where('status', '!=', 'inactive')->get();
        $salt_items = Salt::select('id', 'salt_name')->where('status', '!=', 'inactive')->get();
        $item_type = ItemType::select('id', 'type_name')->where('status', '!=', 'inactive')->get();
        $item_category = ItemCategory::select('id', 'cat_name')->where('status', '!=', 'inactive')->get();
        return Inertia::render('Inventory/Product/Create', [
            'uoms' => $uoms,
            'salt_items' => $salt_items,
            'item_type' => $item_type,
            'item_category' => $item_category,
            'product' => $product,
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        $request['description'] = Purifier::clean($request['description']);

        $oldImage = $product->featured_image;
        $newImage = $request->featured_image;

        if ($oldImage !== $newImage) {
            $source = public_path('storage/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Product/'  . $newImage);
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            $oldImagePath = public_path('uploads/Inventory/Product' . $oldImage);
            if (isset($oldImage) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $product->featured_image = $newImage;
        }



        $productData = $request->only($product->getFillable());
        $productData['updated_by'] = auth()->id();
        $product->update($productData);
        $this->updateStock($product->id, 'opening_stock');
        $this->updateStock($product->id, 'min_qty');

        DB::commit();

        return redirect()->route('products.index');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return Inertia::location(route('products.index'));
    }
}
