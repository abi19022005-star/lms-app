<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama' => 'Web Development', 'slug' => 'web-development'],
            ['nama' => 'Mobile Development', 'slug' => 'mobile-development'],
            ['nama' => 'Data Science', 'slug' => 'data-science'],
            ['nama' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
            ['nama' => 'Digital Marketing', 'slug' => 'digital-marketing'],
            ['nama' => 'Business & Management', 'slug' => 'business-management'],
            ['nama' => 'Photography', 'slug' => 'photography'],
            ['nama' => 'Music Production', 'slug' => 'music-production'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded: ' . count($categories) . ' categories');
    }
}
