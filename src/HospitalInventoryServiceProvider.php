<?php

namespace XelentAbrar\HospitalInventory;

use Illuminate\Support\ServiceProvider;

class HospitalInventoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HospitalInventory');
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'jspages');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/inventory.php' => config_path('inventory.php'),
        ], 'config');


        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages'),
        ], 'inventory-vue');

        // $this->publishes([
        //     __DIR__.'/routes/admin' => resource_path('./../routes/admin'),
        // ], 'inventory-routes');
        
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/inventory.php', 'inventory');
    }
}
