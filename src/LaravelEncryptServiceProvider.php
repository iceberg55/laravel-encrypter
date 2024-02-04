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
        // Register Config
        $configPath = __DIR__ . '/../config/laravel-encrypter.php';
        $this->mergeConfigFrom($configPath, 'laravel-encrypter');

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
        $configPath = __DIR__ . '/../config/laravel-encrypter.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('laravel-encrypter.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('laravel-encrypter.php')], 'config');
    }
}
