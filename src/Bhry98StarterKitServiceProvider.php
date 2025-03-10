<?php

namespace Bhry98\LaravelStarterKit;

use Bhry98\LaravelStarterKit\Exceptions\HandlerUnAuthenticatedException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class Bhry98StarterKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge package config
        $this->mergeConfigFrom(path: __DIR__ . '/Config/bhry98-starter-kit.php', key: 'bhry98-starter-kit');
        $this->app->singleton(ExceptionHandler::class, HandlerUnAuthenticatedException::class);
    }

    public function boot(): void
    {
        $ds = DIRECTORY_SEPARATOR;
//        // add commands
//        self::PackageCommands();
//        // Overwrite auth config
//        self::PackageOverwriteConfigs();
//        // Load package Translation
//        self::PackageLocales();
        // Load package routes
//        $this->loadRoutesFrom(path: __DIR__ . "{$ds}Routes{$ds}users-core.php");
        // Load migrations
        $this->loadMigrationsFrom(paths: [
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}users",
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}core{$ds}locations",
        ]);
        // Load views
        $this->loadViewsFrom(path: __DIR__ . "{$ds}Views", namespace: "Bhry98");
        // Automatically publish migrations
        if ($this->app->runningInConsole()) {
            // Publish migration file
            $this->publishes([
                __DIR__ . "{$ds}Database{$ds}Migrations{$ds}" => database_path('migrations'),
            ], 'bhry98-starter-kit');
            // Publish config file
//            $this->publishes([
//                __DIR__ . "{$ds}Config{$ds}bhry98-starter-kit.php" => config_path('bhry98-starter-kit.php'),
//            ], 'bhry98-starter-kit');
        }
    }
}