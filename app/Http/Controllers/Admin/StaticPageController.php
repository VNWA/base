<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class StaticPageController extends Controller
{
    public function index()
    {
        return Inertia::render('static-pages');
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->get('rowsPerPage', 10);
        $page = (int) $request->get('page', 1);
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = strtolower($request->get('sortType', 'desc'));

        $query = StaticPage::query();

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
    public function detail($id)
    {
        $item = StaticPage::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'Trang tĩnh không tồn tại',
            ], 404);
        }
        return response()->json($item, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:static_pages,slug',
            'desc' => 'nullable|string',
            'content' => 'nullable|string',
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
        $data = $validator->validated();
        $item = StaticPage::create([
            'image' => $data['image'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'desc' => $data['desc'] ?? null,
            'content' => $data['content'] ?? null,
            'meta_title' => $data['meta']['title'] ?? null,
            'meta_desc' => $data['meta']['desc'] ?? null,
            'meta_image' => $data['meta']['image'] ?? null,
        ]);
        return response()->json([
            'message' => 'Tạo trang tĩnh thành công',
            'data' => $item,
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:static_pages,slug,' . $id,
            'desc' => 'nullable|string',
            'content' => 'nullable|string',
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
        $data = $validator->validated();
        $item = StaticPage::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'Trang tĩnh không tồn tại',
            ], 404);
        }
        $item->update([
            'image' => $data['image'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'desc' => $data['desc'] ?? null,
            'content' => $data['content'] ?? null,
            'meta_title' => $data['meta']['title'] ?? null,
            'meta_desc' => $data['meta']['desc'] ?? null,
            'meta_image' => $data['meta']['image'] ?? null,
        ]);
        return response()->json([
            'message' => 'Cập nhật trang tĩnh thành công',
            'data' => $item,
        ], 200);
    }
    public function delete(Request $request)
    {
        $items = $request->validate([
            '*.value' => 'required|exists:static_pages,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $i = StaticPage::findOrFail($item['value']);
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
