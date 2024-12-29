<?php

namespace App\Providers;

use App\Database\MySqlGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        $this->dbConnections();
    }

    public function dbConnections()
    {
        DB::connection()->setQueryGrammar( new MySqlGrammar );
    }

}
