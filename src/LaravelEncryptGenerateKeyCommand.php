<?php

/**
 * Laravel Encrypter.
 *
 * @author      Iman Abbasi
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/ibf/laravel-encrypter
 */

namespace ibf\LaravelEncrypter;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LaravelEncryptGenerateKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibf-encrypt:generate
                { --key_length= : Custom Encryption Key Length}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Encryption Key';

    protected $warned = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $env = base_path('.env');
        if ($this->option('key_length')) {
            $keyLength = $this->option('key_length');
        } else {
            $keyLength = config('laravel-encrypter.key_length', 16);
        }

        if (file_exists($env)) {
            $envContent = file_get_contents($env);
            $token = bin2hex(openssl_random_pseudo_bytes($keyLength));
            
            if (str_contains($envContent, 'LARAVEL_ENCRYPTION_KEY='.env('LARAVEL_ENCRYPTION_KEY'))) {
                $envContent = str_replace('LARAVEL_ENCRYPTION_KEY='.env('LARAVEL_ENCRYPTION_KEY'), 'LARAVEL_ENCRYPTION_KEY='.$token, $envContent);
            } else {
                $envContent .= "LARAVEL_ENCRYPTION_KEY=$token\n";
            }
            file_put_contents($env, $envContent);
        } else {
            $this->error('.env not exist !');
        }
    }
}
