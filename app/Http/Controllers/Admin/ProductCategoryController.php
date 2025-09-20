<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Str;
use Validator;
class ProductCategoryController extends Controller
{
    public function options()
    {
        $items = ProductCategory::get()->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->id,
            ];
        });
        return response()->json($items);
    }
    public function index()
    {
        return Inertia::render('product/categories');
    }

    public function loadTree(Request $request)
    {
        $categories = ProductCategory::orderBy('ord')
            ->get();
        $tree = $this->buildTree($categories);

        return response()->json($tree, 200);
    }


    // Build mảng dữ liệu để upsert
    protected function updateTreeRecursive($parentId, $nodes)
    {
        foreach ($nodes as $index => $node) {
            $categoryId = $node['id'];
            $newOrd = $index + 1;

            // Chỉ update các trường cần thiết
            ProductCategory::where('id', $categoryId)->update([
                'parent_id' => $parentId,
                'ord' => $newOrd,
                'updated_at' => now(),
            ]);

            // Đệ quy cho children nếu có
            if (!empty($node['children'])) {
                $this->updateTreeRecursive($categoryId, $node['children']);
            }
        }
    }

    /**
     * API cập nhật tree
     */
    public function updateTree(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'treeData' => 'required|array',
            'treeData.*.id' => 'required|integer|exists:product_categories,id',
        ]);

        $treeData = $request->treeData;

        DB::beginTransaction();
        try {
            $this->updateTreeRecursive(null, $treeData);
            DB::commit();

            return response()->json([
                'message' => 'Tree data has been updated successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Unable to update tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function detail($id)
    {
        try {
            $data = ProductCategory::findOrFail($id);
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:product_categories,id',
            'url_avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug',
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



        $category = ProductCategory::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'url_avatar' => $validated['url_avatar'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'meta_title' => data_get($validated, 'meta.title'),
            'meta_desc' => data_get($validated, 'meta.desc'),
            'meta_image' => data_get($validated, 'meta.image'),
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'id' => $category->id,
        ]);
    }
    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:product_categories,id',
            'url_avatar' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $id,
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

        $category->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'url_avatar' => $validated['url_avatar'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'meta_title' => data_get($validated, 'meta.title'),
            'meta_desc' => data_get($validated, 'meta.desc'),
            'meta_image' => data_get($validated, 'meta.image'),
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'id' => $category->id,
        ]);
    }

    public function delete(Request $request)
    {
        $items = $request->validate([
            '*.value' => 'required|exists:product_categories,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $i = ProductCategory::findOrFail($item['value']);
                $i->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Remove Product Category Success']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Remove Product Category Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
