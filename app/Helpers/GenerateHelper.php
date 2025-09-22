<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateHelper
{
    /**
     * Generate a unique slug
     */
    public static function generateSlug(string $name, string $table, string $column = 'slug', ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        // Tạo query cơ bản
        $query = fn ($slugValue) =>
            DB::table($table)
                ->where($column, $slugValue)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists();

        while ($query($slug)) {
            $slug = $original.'-'.$i;
            $i++;
        }

        return $slug;
    }

    /**
     * Generate a unique SKU
     */
    public static function generateSku(string $name, string $table, string $column = 'sku', ?int $ignoreId = null): string
    {
        // Tách tên thành các từ, lấy chữ cái đầu
        $words = preg_split('/\s+/', trim($name));
        $prefix = '';
        foreach ($words as $word) {
            $prefix .= strtoupper(mb_substr($word, 0, 1));
        }

        // Nếu không lấy được prefix thì fallback bằng random
        if (empty($prefix)) {
            $prefix = 'PRD';
        }

        $number = 1;
        $sku = $prefix.'-'.str_pad($number, 4, '0', STR_PAD_LEFT);

        $query = fn ($skuValue) =>
            DB::table($table)
                ->where($column, $skuValue)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists();

        while ($query($sku)) {
            $number++;
            $sku = $prefix.'-'.str_pad($number, 4, '0', STR_PAD_LEFT);
        }

        return $sku;
    }
}
