<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GenerateHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $product = Product::find($id);
        $product->brand_id = $product->product_brand_id;
        $product->category_ids = $product->categories->pluck('id');
        $product->meta = [
            'title' => $product->meta_title ?? '',
            'desc' => $product->meta_desc ?? '',
            'image' => $product->meta_image ?? '',
        ];

        return Inertia::render('product/edit', ['product' => $product]);
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
            $query->where('name', 'like', '%'.$name.'%')->orWhere('sku', 'like', '%'.$name.'%');
        }

        // Lọc theo brands
        if (! empty($brands)) {
            $query->whereIn('product_brand_id', $brands);
        }

        // Lọc theo categories
        if (! empty($categories)) {
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
        if (! empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('product_categories.id', $categoryIds); // Sửa chỗ này
            });
        }
        if (! empty($brandIds)) {
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

    public function store(Request $request)
    {
        $data = $request->all();

        // Validate dữ liệu
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'price_old' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'stock' => 'nullable|integer|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:product_categories,id',
            'url_avatar' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255',
            'description' => 'nullable|string',
            'append.delivery_and_returns' => 'nullable|string',
            'append.specification' => 'nullable|string',
            'meta.title' => 'nullable|string|max:255',
            'meta.desc' => 'nullable|string|max:500',
            'meta.image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->toArray())
                ->map(fn ($messages) => $messages[0]) // lấy message đầu tiên
                ->toArray();

            return response()->json([
                'message' => 'Data is invalid.',
                'errors' => $errors,
            ], 422);
        }

        // Generate slug nếu không có
        $data['slug'] = ! empty($data['slug'])
            ? $data['slug']
            : GenerateHelper::generateSlug($data['name'], 'products', 'slug');

        // Generate SKU nếu không có
        $data['sku'] = ! empty($data['sku'])
            ? $data['sku']
            : GenerateHelper::generateSku($data['name'], 'products', 'sku');

        // Tạo product
        $product = Product::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'price' => $data['price'],
            'price_old' => $data['price_old'] ?? null,
            'sku' => $data['sku'],
            'stock' => $data['stock'] ?? 0,
            'product_brand_id' => $data['brand_id'] ?? null,
            'url_avatar' => $data['url_avatar'] ?? null,
            'images' => $data['images'] ?? [],
            'description' => $data['description'] ?? null,
            'append' => $data['append'] ?? [],
            'meta_title' => $data['meta']['title'] ?? null,
            'meta_desc' => $data['meta']['desc'] ?? null,
            'meta_image' => $data['meta']['image'] ?? null,
        ]);

        // Nếu có category_ids thì sync
        if (! empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $data = $request->all();

        // Validate dữ liệu
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$product->id,
            'price' => 'required|numeric|min:0',
            'price_old' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:255|unique:products,sku,'.$product->id,
            'stock' => 'nullable|integer|min:0',
            'brand_id' => 'nullable|exists:product_brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:product_categories,id',
            'url_avatar' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255',
            'description' => 'nullable|string',
            'append.delivery_and_returns' => 'nullable|string',
            'append.specification' => 'nullable|string',
            'meta.title' => 'nullable|string|max:255',
            'meta.desc' => 'nullable|string|max:500',
            'meta.image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->toArray())
                ->map(fn ($messages) => $messages[0]) // lấy message đầu tiên
                ->toArray();

            return response()->json([
                'message' => 'Data is invalid.',
                'errors' => $errors,
            ], 422);
        }

        // Generate slug nếu không có
        $data['slug'] = ! empty($data['slug'])
            ? $data['slug']
            : GenerateHelper::generateSlug($data['name'], 'products', 'slug', $product->id);

        // Generate SKU nếu không có
        $data['sku'] = ! empty($data['sku'])
            ? $data['sku']
            : GenerateHelper::generateSku($data['name'], 'products', 'sku', $product->id);
        // Cập nhật product
        $product->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'price' => $data['price'],

            'price_old' => $data['price_old'] ?? null,
            'sku' => $data['sku'],
            'stock' => $data['stock'] ?? 0,
            'product_brand_id' => $data['brand_id'] ?? null,
            'url_avatar' => $data['url_avatar'] ?? null,
            'images' => $data['images'] ?? [],
            'description' => $data['description'] ?? null,
            'append' => $data['append'] ?? [],
            'meta_title' => $data['meta']['title'] ?? null,
            'meta_desc' => $data['meta']['desc'] ?? null,
            'meta_image' => $data['meta']['image'] ?? null,
        ]);
        // Nếu có category_ids thì sync
        if (isset($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
            $product->load('categories');
        }

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product,
        ], 200);
    }

    public function delete(Request $request)
    {
        $items = $request->validate([
            '*.value' => 'required|exists:products,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $i = Product::findOrFail($item['value']);
                $i->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Remove Product  Success']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Remove Product  Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
