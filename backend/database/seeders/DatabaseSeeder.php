<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // create news sources
        NewsSource::insert([
            [ 'name' => 'NewsAPI.org', 'url' => null ],
            [ 'name' => 'The New York Times', 'url' => null ],
            [ 'name' => 'The Guardian:', 'url' => null ],
        ]);

        //create some categories
        Category::insert([
            ['name' => 'Business'],
            ['name' => 'Politics'],
            ['name' => 'Technology'],
            ['name' => 'Lifestyle'],
            ['name' => 'Health'],
            ['name' => 'Finance'],
        ]);

    }
}
