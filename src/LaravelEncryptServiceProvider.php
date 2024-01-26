<?php

/**
 * Laravel Encrypter.
 *
 * @author      Iman Abbasi
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/ibf/laravel-encrypter
 */

namespace ibf\LaravelEncrypter;

use Illuminate\Support\ServiceProvider;

class LaravelEncryptServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register hard-delete-expired artisan command
        $this->commands([
            LaravelEncryptCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $configPath = __DIR__.'/../config/laravel-encrypter.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('laravel-encrypter.php');
        } else {
            $publishPath = base_path('config/laravel-encrypter.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}
