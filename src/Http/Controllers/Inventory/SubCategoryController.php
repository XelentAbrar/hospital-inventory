<?php

namespace XelentAbrar\HospitalInventory\Http\Controllers\Inventory;

use App\Traits\File;
use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Str;
use XelentAbrar\HospitalInventory\Models\Inventory\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalInventory\Models\Inventory\SubCategory;
use XelentAbrar\HospitalInventory\Http\Requests\Inventory\SubCategoryRequest;

class SubCategoryController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware('checkPermission:sub-categories.index')->only('index');
        $this->middleware('checkPermission:sub-categories.create')->only('create', 'store');
        $this->middleware('checkPermission:sub-categories.show')->only('show');
        $this->middleware('checkPermission:sub-categories.edit')->only('edit', 'update');
        $this->middleware('checkPermission:sub-categories.destroy')->only('destroy');
    }

    public function index()
    {

        $subCategories = SubCategory::with('category')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Inventory/SubCategory/Index', [
            'subCategories' => $subCategories,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        return Inertia::render('Inventory/SubCategory/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(SubCategoryRequest $request)
    {
        DB::beginTransaction();

        $uniqueFilename = $request->image;

        SubCategory::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $uniqueFilename,
            'status' => $request->status,
        ]);
        $source = storage_path('app/public/uploads/Inventory/' . $uniqueFilename);
        $destinationDir = public_path('uploads/Inventory/sub-categories/');
        if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (file_exists($source)) {
            copy($source, $destinationDir . $uniqueFilename);
            unlink($source);
        } else {
            Log::error("Source file does not exist: " . $source);
        }
        // $source = storage_path('app/public/uploads/Inventory/' . $uniqueFilename);
        // $destination = public_path('uploads/Inventory/sub-categories/' . $uniqueFilename);

        // if (file_exists($source)) {
        //     copy($source, $destination);
        //     unlink($source);
        // }

        DB::commit();

        return redirect()->route('sub-categories.index');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return Inertia::render('Inventory/SubCategory/Create', [
            'subCategory' => $subCategory,
            'categories' => $categories,
        ]);
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory)
    {
        DB::beginTransaction();
        $oldImage = $subCategory->image;
        $newImage = $request->image;

        if ($oldImage !== $newImage) {

            $source = storage_path('app/public/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/sub-categories/' . $newImage);

            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            if (!empty($oldImage)) {
                $oldImagePath = public_path('uploads/Inventory/sub-categories/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $subCategory->image = $newImage;
        }

        $subCategory->name = $request->name;
        if ($subCategory->name != $request->name) {
            $subCategory->slug = $this->generateUniqueSlug($request->name);
        }
        $subCategory->category_id = $request->category_id;
        $subCategory->description = $request->description;
        $subCategory->image = $subCategory->image;
        $subCategory->status = $request->status;

        $subCategory->save();

        DB::commit();

        return redirect()->route('sub-categories.index');
    }

    public function destroy(SubCategory $subCategory)
    {
        DB::beginTransaction();
        $subCategory->delete();
        if (!empty($subCategory->image)) {
            $oldImagePath = public_path('uploads/Inventory/sub-categories/' . $subCategory->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        DB::commit();
        return Inertia::location(route('sub-categories.index'));
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        if (SubCategory::where('slug', $slug)->doesntExist()) {
            return $slug;
        }
        $existingSlugs = SubCategory::where('slug', $slug)->pluck('slug')->toArray();
        $suffix = 2;
        while (in_array($slug . '-' . $suffix, $existingSlugs)) {
            $suffix++;
        }

        return $slug . '-' . $suffix;
    }
}
