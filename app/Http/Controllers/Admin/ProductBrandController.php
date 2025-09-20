<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class ProductBrandController extends Controller
{
    public function options()
    {
        $items = ProductBrand::get()->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->id,
            ];
        });
        return response()->json($items);
    }
    public function index()
    {
        return Inertia::render('product/brands');
    }
    public function list(Request $request)
    {
        $perPage = (int) $request->get('rowsPerPage', 10);
        $page = (int) $request->get('page', 1);
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = strtolower($request->get('sortType', 'desc'));

        $query = ProductBrand::query();

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url_avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_brands,slug',
            'description' => 'nullable|string',
            'meta.title' => 'nullable|string|max:255',
            'meta.desc' => 'nullable|string|max:500',
            'meta.image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->toArray())
                ->map(fn($messages) => $messages[0]) // lấy message đầu tiên
                ->toArray();

            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $errors,
            ], 422);
        }

        $validated = $validator->validated();



        $category = ProductBrand::create([
            'url_avatar' => $validated['url_avatar'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'meta_title' => data_get($validated, 'meta.title'),
            'meta_desc' => data_get($validated, 'meta.desc'),
            'meta_image' => data_get($validated, 'meta.image'),
        ]);

        return response()->json([
            'message' => 'Brand created successfully',
            'id' => $category->id,
        ]);
    }
    public function detail($id)
    {
        $brand = ProductBrand::find($id);
        return response()->json($brand);
    }
    public function update(Request $request, $id)
    {
        $item = ProductBrand::find($id);

        if (!$item) {
            return response()->json([
                'message' => 'Brand not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'url_avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_brands,slug,' . $id,
            'description' => 'nullable|string',
            'meta.title' => 'nullable|string|max:255',
            'meta.desc' => 'nullable|string|max:500',
            'meta.image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->toArray())
                ->map(fn($messages) => $messages[0])
                ->toArray();

            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $errors,
            ], 422);
        }

        $validated = $validator->validated();

        $item->update([
            'url_avatar' => $validated['url_avatar'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'meta_title' => data_get($validated, 'meta.title'),
            'meta_desc' => data_get($validated, 'meta.desc'),
            'meta_image' => data_get($validated, 'meta.image'),
        ]);

        return response()->json([
            'message' => 'Brand updated successfully',
        ]);
    }

    public function delete(Request $request)
    {
        $items = $request->validate([
            '*.value' => 'required|exists:product_brands,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $i = ProductBrand::findOrFail($item['value']);
                $i->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Remove  Success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Remove  Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
