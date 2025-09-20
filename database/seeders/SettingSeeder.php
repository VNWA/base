<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Storage;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::setValue('logo', [
            'logo_full' => 'vnwa_setup/vnwa_logo.webp',
            'logo_icon' => 'vnwa_setup/vnwa_icon.webp',
            'favicon' => 'vnwa_setup/vnwa_icon.png',
            'favicon_type' => 'png'
        ]);

        Setting::setValue('site_meta', [
            'title' => 'Vinawebapp.com - Dịch vụ thiết kế website chuyên nghiệp',
            'desc' => 'Vinawebapp là công ty thiết kế website chuyên nghiệp với đội ngũ giàu kinh nghiệm, mang đến giải pháp web sáng tạo, hiệu quả',
            'image' => Storage::url('vnwa_setup/vnwa_no_image.avif'),
        ]);

    }
}
