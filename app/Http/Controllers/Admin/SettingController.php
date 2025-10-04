<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function loadData($key, Request $request)
    {
        $data = Setting::getValue($key); // mặc định trả mảng rỗng nếu chưa tồn tại

        return response()->json([
            'data' => $data,
            'message' => 'success',
        ]);
    }

    public function update(Request $request, $key)
    {
        $data = $request->except(['_token', '_method', 'locale']);

        Setting::setValue($key, $data); // lưu + refresh cache

        return response()->json([
            'message' => 'Update Setting Success',
            'key' => $key,
            'data' => $data
        ]);
    }

}