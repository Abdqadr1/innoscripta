<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        set_time_limit(0);

        // create news sources
        NewsSource::insert([
            [ 'name' => 'NewsAPI.org', 'url' => null, 'last_fetched' => Carbon::parse('2024-12-25') ],
            [ 'name' => 'The New York Times', 'url' => null, 'last_fetched' => Carbon::parse('2024-12-25') ],
            [ 'name' => 'The Guardian:', 'url' => null, 'last_fetched' => Carbon::parse('2024-12-25') ],
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

        for ($i=0; $i < 5; $i++) { 

            (new NewsApiService)();
            (new NewYorkTimesService)();
            (new TheGuardianService)();

        }
    

    }
}
