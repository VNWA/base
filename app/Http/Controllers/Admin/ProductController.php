<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('product/list');
    }
    public function create()
    {
        return Inertia::render('product/create');
    }
    public function edit($id)
    {
        return Inertia::render('product/edit');
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->get('rowsPerPage', 10);
        $page = (int) $request->get('page', 1);
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = strtolower($request->get('sortType', 'desc'));
        $name = $request->get('name');
        $categories = (array) $request->input('categories', []);
        $brands = (array) $request->input('brands', []);

        $query = Product::with(['brand', 'categories']);

        // Lọc theo tên
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        // Lọc theo brands
        if (!empty($brands)) {
            $query->whereIn('product_brand_id', $brands);
        }

        // Lọc theo categories
        if (!empty($categories)) {
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('product_categories.id', $categories);
            });
        }

        $list = $query->get();
        $data = $list->sortBy($sortBy, SORT_REGULAR, $sortType === 'desc')->values();
        $total = $data->count();
        $results = $data->forPage($page, $perPage)->values();

        return response()->json([
            'data' => $results,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
        ], 200);
    }

    public function loadDataCategoriesAndBrands()
    {
        try {
            $categories = ProductCategory::get(['id', 'name']);
            $brands = Brand::get(['id', 'name']);
            return response()->json(['categories' => $categories, 'brands' => $brands], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    protected function getChildCategories($parentId)
    {
        // Lấy danh mục con của $parentId đã sắp xếp theo 'ord'
        $children = ProductCategory::where('parent_id', $parentId)
            ->orderBy('ord')
            ->get(['id', 'parent_id', 'name', 'ord'])
            ->map(function ($category) {
                // Đệ quy lấy danh mục con của danh mục hiện tại
                $category->children = $this->getChildCategories($category->id);
                return $category;
            });

        return $children;
    }
    public function loadDataCategoriesTreeAndBrands()
    {
        try {
            $categories = ProductCategory::whereNull('parent_id')
                ->orderBy('ord')
                ->get(['id', 'parent_id', 'name', 'ord'])
                ->map(function ($category) {
                    // Đệ quy lấy danh mục con
                    $category->children = $this->getChildCategories($category->id);
                    return $category;
                });
            $brands = Brand::get(['id', 'name']);
            return response()->json(['categories' => $categories, 'brands' => $brands], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function loadDataTable(Request $request)
    {
        // Lấy các tham số từ request
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = $request->get('sortType', 'desc');
        $name = $request->get('name');
        $categoryIds = $request->get('categories_id', []);
        $brandIds = $request->get('brands_id', []);

        // Khởi tạo query
        $query = Product::query();

        // Lọc theo tên product nếu có
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // Lọc theo danh mục nếu có
        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('product_categories.id', $categoryIds); // Sửa chỗ này
            });
        }
        if (!empty($brandIds)) {
            $query->whereHas('brand', function ($q) use ($brandIds) { // Dùng 'brand' (số ít)
                $q->whereIn('brands.id', $brandIds); // Lọc theo brand IDs
            });
        }


        // Sắp xếp và phân trang
        $total = $query->count();
        $products = $query->skip(($page - 1) * $perPage)->take($perPage)->with('categories')->with('brand')->orderBy($sortBy, $sortType)->get();

        return response()->json([
            'data' => $products,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
        ], 200);
    }
    public function showEdit($id)
    {
        $product = Product::with('categories')->find($id);
        $categoriesId = $product->categories->pluck('id');
        $product->categoriesId = $categoriesId;

        return Inertia::render('Admin/Ecommerce/Product/Edit', ['product' => $product]);

    }

    function stpre(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:urls,slug',
            'images' => 'required|array',
            'images.*' => 'string',
            'description' => 'nullable|string',
            'tech_info' => 'nullable|string',
            'brand_id' => 'required|integer|exists:brands,id',
            'sku' => 'required|string',
            'stock' => 'required|integer',
            'origin' => 'required|string',
            'seo_meta.meta_image' => 'nullable|string',
            'seo_meta.meta_title' => 'nullable|string|max:255',
            'seo_meta.meta_desc' => 'nullable|string|max:255',
            'parentIds' => 'nullable|array',
            'parentIds.*' => 'integer|exists:product_categories,id',
        ]);

        $data = [
            'is_show' => $request->is_show ? 1 : 0,
            'name' => $request->name,
            'slug' => $request->slug,
            'images' => $request->images,
            'description' => $request->description,
            'tech_info' => $request->tech_info,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'origin' => $request->origin,
            'catalogs' => $request->catalogs ?? [],
            'meta_image' => $request->seo_meta['meta_image'] ?? null,
            'meta_title' => $request->seo_meta['meta_title'] ?? null,
            'meta_desc' => $request->seo_meta['meta_desc'] ?? null,
        ];
        DB::beginTransaction();

        try {
            // Tạo product
            $product = Product::create($data);

            // Liên kết product với các danh mục
            foreach ($request->parentIds as $parentId) {
                ProductCategoryAssignment::create([
                    'product_id' => $product->id,
                    'product_category_id' => $parentId,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Create Product Success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th);

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    function update(Request $request, $id)
    {
        $product = Product::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:urls,slug,' . $product->url->id,
            'images' => 'required|array',
            'images.*' => 'string',
            'description' => 'nullable|string',
            'tech_info' => 'nullable|string',
            'brand_id' => 'required|integer|exists:brands,id',
            'sku' => 'required|string',
            'stock' => 'required|integer',
            'origin' => 'required|string',
            'seo_meta.meta_image' => 'nullable|string',
            'seo_meta.meta_title' => 'nullable|string|max:255',
            'seo_meta.meta_desc' => 'nullable|string|max:255',
            'parentIds' => 'nullable|array',
            'parentIds.*' => 'integer|exists:product_categories,id',
        ]);

        $data = [
            'is_show' => $request->is_show ? 1 : 0,
            'name' => $request->name,
            'slug' => $request->slug,
            'images' => $request->images,
            'description' => $request->description,
            'tech_info' => $request->tech_info,
            'brand_id' => $request->brand_id,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'origin' => $request->origin,
            'catalogs' => $request->catalogs ?? [],
            // 'meta_image' => $request->seo_meta['meta_image'] ?? null,
            // 'meta_title' => $request->seo_meta['meta_title'] ?? null,
            // 'meta_desc' => $request->seo_meta['meta_desc'] ?? null,
        ];
        DB::beginTransaction();

        try {
            // Tạo product
            $product->update($data);
            ProductCategoryAssignment::where('product_id', $id)->delete();

            // Liên kết product với các danh mục
            foreach ($request->parentIds as $parentId) {
                ProductCategoryAssignment::create([
                    'product_id' => $id,
                    'product_category_id' => $parentId,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Update Product Success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th);

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    function delete(Request $request)
    {

        try {
            Product::whereIn('id', $request->dataId)->delete();
            return response()->json(['message' => 'Xóa dữ liệu thành công'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
