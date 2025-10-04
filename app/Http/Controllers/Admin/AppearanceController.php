<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appearance;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function loadData($key, Request $request)
    {
        $data = Appearance::getValue($key);

        return response()->json([
            'data' => $data,
            'message' => 'success',
        ]);
    }

    public function update(Request $request, $key)
    {
        $data = $request->except(['_token', '_method', 'locale']);

        Appearance::setValue($key, $data); // lưu + refresh cache

        return response()->json([
            'message' => 'Update Appearance Success',
            'key' => $key,
            'data' => $data
        ]);
    }




}