<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'new'],
            ['name' => 'edited'],
            ['name' => 'archived'],
            ['name' => 'promotion'],
            ['name' => 'featured'],
            ['name' => 'popular'],
            ['name' => 'review'],
            ['name' => 'support']
        ];

        DB::table('tags')->insert($tags);
    }
}
