<?php

namespace App\Providers;

use App\Models\Manager;
use App\Models\Signer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //监听器
        if (config('app.debug')) {
            \DB::listen(function ($sql, $bindings, $time) {
                echo '<br/>SQL语句执行：'. $sql .'，参数：'. json_encode($bindings) .',耗时：'. $time .'ms<br/>';
            });
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if ($this->app->environment() !== 'production') {
            $this->app->register(
                \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton('Signer', function () {
            return new Signer();
        });

        $this->app->singleton('Manager', function () {
            return new Manager();
        });
    }
}
