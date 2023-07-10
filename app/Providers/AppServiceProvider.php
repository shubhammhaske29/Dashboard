<?php

namespace App\Providers;

use App\Ward;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $wards = [];
        if (Schema::hasTable('wards')) {
            foreach (Ward::all() as $ward) {
                $wards[$ward->zone][] = $ward->ward;
            }
            Config::set('common.zones',$wards);
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
