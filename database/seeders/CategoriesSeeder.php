<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['title' => 'Electronics', 'parent_id' => null],
            ['title' => 'Laptops & PC', 'parent_id' => 1],
            ['title' => 'Laptops', 'parent_id' => 2],
            ['title' => 'PC', 'parent_id' => 2],
            ['title' => 'Cameras & photo', 'parent_id' => 1],
            ['title' => 'Camera', 'parent_id' => 5],
            ['title' => 'Phones & Accessories', 'parent_id' => 1],
            ['title' => 'Smartphones', 'parent_id' => 7],
            ['title' => 'Android', 'parent_id' => 8],
            ['title' => 'iOS', 'parent_id' => 8],
            ['title' => 'Other Smartphones', 'parent_id' => 8],
            ['title' => 'Batteries', 'parent_id' => 7],
            ['title' => 'Headsets', 'parent_id' => 7],
            ['title' => 'Screen Protectors', 'parent_id' => 7],
        ];

        DB::table('categories')->insert($categories);
    }
}
