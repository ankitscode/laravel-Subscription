<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Category::firstOrCreate(['name' => 'dairy', 'is_active' => 1,'is_menu' => 1]);
            Category::firstOrCreate(['name' => 'vegetables', 'is_active' => 1,'is_menu' => 1]);
            Category::firstOrCreate(['name' => 'meat', 'is_active' => 1,'is_menu' => 1]);
            Category::firstOrCreate(['name' => 'grocery', 'is_active' => 1,'is_menu' => 1]);
    }
}
