<?php

/**
 * Laravel Encrypter.
 *
 * @author      Iman Abbasi
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/ibf/laravel-encrypter
 */

namespace ibf\LaravelEncrypter;

use ibf\LaravelEncrypter\Facades\Obfuscator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Exception;

class LaravelEncryptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibf-encrypt
                { --source= : Path(s) to encrypt }
                { --destination= : Destination directory }
                { --force : Force the operation to run when destination directory already exists }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypts PHP files';

    protected $warned = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (empty($this->option('source'))) {
            $sources = config('laravel-encrypter.source', ['app', 'database', 'routes']);
        } else {
            $sources = $this->option('source');
            $sources = explode(',', $sources);
        }
        if (empty($this->option('destination'))) {
            $destination = config('laravel-encrypter.destination', 'encrypted');
        } else {
            $destination = $this->option('destination');
        }

        if (! $this->option('force') && File::exists(base_path($destination)) && ! $this->confirm("The directory $destination already exists. Delete directory?")) {
            $this->line('Command canceled.');

            return 1;
        }

        File::deleteDirectory(base_path($destination));
        File::makeDirectory(base_path($destination));

        foreach ($sources as $source) {
            if (! File::exists($source)) {
                $this->error("File $source does not exist.");

                return 1;
            }

            @File::makeDirectory($destination.'/'.File::dirname($source), 493, true);
            if (File::isFile($source)) {
                self::encryptFile($source, $destination);
                continue;
            }
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path($source)));
            foreach ($files as $file) {
                $filePath = Str::replaceFirst(base_path(), '', $file->getRealPath());
                self::encryptFile($filePath, $destination);
            }
        }
        $this->info('Encrypting Completed Successfully!');
        $this->info("Destination directory: $destination");

        if ($this->confirm('Are you want to replace encrypted files with sources?')) {
            $keepPath = config('laravel-encrypter.keep_path', 'old');
            File::deleteDirectory(base_path($keepPath));
            File::makeDirectory(base_path($keepPath));

            foreach ($sources as $source) {
                @File::makeDirectory($keepPath.'/'.File::dirname($source), 493, true);
                if (File::isDirectory(base_path($source))) {
                    File::moveDirectory(base_path($source), base_path("$keepPath/$source"));
                } else {
                    File::move(base_path($source), base_path("$keepPath/$source"));
                }
            }

            foreach ($sources as $source) {
                if (File::isDirectory(base_path($source))) {
                    File::moveDirectory(base_path("$destination/$source"), base_path($source));
                } else {
                    File::move(base_path("$destination/$source"), base_path($source));
                }
            }
            File::deleteDirectory(base_path($destination));

            if ($this->confirm('Are you want to delete original sources?')) {
                File::deleteDirectory(base_path($keepPath));
            }
        }

        return 0;
    }

    private function encryptFile($filePath, $destination)
    {
        if (File::isDirectory(base_path($filePath))) {
            if (! File::exists(base_path($destination.$filePath))) {
                File::makeDirectory(base_path("$destination/$filePath"), 493, true);
            }

            return;
        }

        $extension = Str::after($filePath, '.');

        if ($extension == 'blade.php' || $extension != 'php') {
            if (! in_array($extension, $this->warned)) {
                $this->warn("Encryption of $extension files is not currently supported. These files will be copied without change.");
                $this->warned[] = $extension;
            }
            File::copy(base_path($filePath), base_path("$destination/$filePath"));

            return;
        }

        Obfuscator::obfuscateFileFromTo(base_path($filePath), base_path("$destination/$filePath"));
    }
}
