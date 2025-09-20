<?php

namespace App\Http\Controllers;

use Storage;

abstract class Controller
{
    public function imageUrl($path)
    {
        if (!$path) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::url($path); // Nếu bạn đã cấu hình 'public' disk
    }
    public function buildTree($categories, $parentId = null)
    {
        return $categories
            ->where('parent_id', $parentId)
            ->map(function ($category) use ($categories) {
                $category->children = $this->buildTree($categories, $category->id)->values();
                return $category;
            })
            ->values();
    }
    public function buildMetaSeo($data)
    {
        $meta = [
            'title' => 'Vinawebapp.com - Dịch vụ thiết kế website chuyên nghiệp',
            'desc' => 'Vinawebapp là công ty thiết kế website chuyên nghiệp với đội ngũ giàu kinh nghiệm, mang đến giải pháp web sáng tạo, hiệu quả và đậm dấu ấn thương hiệu. Hỗ trợ khách hàng toàn cầu, giao tiếp linh hoạt, bảo hành trọn đời.',
            'image' => Storage::url('vnwa_setup/vnwa_no_image.avif')
        ];


        if (!empty($data['title'])) {
            $meta['title'] = $data['title'];
        }
        if (!empty($data['desc'])) {
            $meta['desc'] = $data['desc'];
        }
        if (!empty($data['image'])) {
            $meta['image'] = $data['image'];
        }

        return $meta;
    }
}
