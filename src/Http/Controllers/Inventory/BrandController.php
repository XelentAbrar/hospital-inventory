<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\BrandRequest;
use XelentAbrar\HospitalInventory\Models\Inventory\Brand;
use App\Models\UserRole;
use App\Traits\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BrandController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware('checkPermission:brands.index')->only('index');
        $this->middleware('checkPermission:brands.create')->only('create', 'store');
        $this->middleware('checkPermission:brands.show')->only('show');
        $this->middleware('checkPermission:brands.edit')->only('edit', 'update');
        $this->middleware('checkPermission:brands.destroy')->only('destroy');
    }

    public function index()
    {
        $brands = Brand::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/Brand/Index', [
            'brands' => $brands,
            'role' =>$role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Brand/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    public function store(BrandRequest $request)
    {

        DB::beginTransaction();

        $uniqueFilename = $request->logo;

        Brand::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'logo' => $uniqueFilename,
            'status' => $request->status,
        ]);


        if (!file_exists(public_path() . '/uploads/Inventory/Brands')) {
            mkdir(public_path() . '/uploads/Inventory/Brands');
        }
        if (!empty($uniqueFilename)) {

            $source = storage_path('app/public/uploads/Inventory/' . $uniqueFilename);
            $destination = public_path('uploads/Inventory/Brands/' . $uniqueFilename);

            // Check if source file exists before trying to copy
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }
        }

        DB::commit();

        return redirect()->route('brands.index');
    }

    public function edit(Brand $brand)
    {
        return Inertia::render('Inventory/Brand/Create', [
            'brand' => $brand,
        ]);
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        DB::beginTransaction();

        $oldImage = $brand->logo;
        $newImage = $request->logo;

        if ($oldImage !== $newImage) {

            $source = storage_path('app/public/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Brands/' . $newImage);

            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            if (!empty($oldImage)) {
                $oldImagePath = public_path('uploads/Inventory/Brands/' . $oldImage);
                if (isset($oldImage) && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $brand->logo = $newImage;
        }

        $brand->name = $request->name;
        if ($brand->name != $request->name) {
            $brand->slug = $this->generateUniqueSlug($request->name);
        }
        $brand->description = $request->description;
        $brand->logo = $brand->logo;
        $brand->status = $request->status;

        $brand->save();

        DB::commit();

        return redirect()->route('brands.index');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return Inertia::location(route('brands.index'));
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        if (Brand::where('slug', $slug)->doesntExist()) {
            return $slug;
        }

        $existingSlugs = Brand::where('slug', $slug)->pluck('slug')->toArray();
        $suffix = 2;
        while (in_array($slug . '-' . $suffix, $existingSlugs)) {
            $suffix++;
        }

        return $slug . '-' . $suffix;
    }
}
