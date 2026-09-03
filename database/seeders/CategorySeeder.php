<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * A sensible starting set — all manageable afterwards from
     * /admin/categories (add, rename, delete).
     */
    public function run(): void
    {
        $names = ['Product', 'Engineering', 'Technical Leadership', 'Entrepreneurship', 'Personal', 'Technology', 'Web3'];

        foreach ($names as $name) {
            Category::updateOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }
    }
}
