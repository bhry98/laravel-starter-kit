<?php

namespace Bhry98\LaravelStarterKit;

use Bhry98\LaravelStarterKit\Console\commands\CountriesRunSeedCommand;
use Bhry98\LaravelStarterKit\Console\commands\EnumsRunSeedCommand;
use Bhry98\LaravelStarterKit\Exceptions\HandlerUnAuthenticatedException;
use Bhry98\LaravelStarterKit\Models\core\sessions\CoreSessionsModel;
use Bhry98\LaravelStarterKit\Models\core\sessions\CoreSessionsPersonalAccessTokenModel;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class Bhry98StarterKitServiceProvider extends ServiceProvider
{
    const  CONFIG_FILE_NAME = "bhry98-starter";
    const  ROUTES_FILE_NAME = "bhry98-starter-kit";

    public function register(): void
    {
        // Merge package config
        $this->mergeConfigFrom(path: __DIR__ . "/Config/" . self::CONFIG_FILE_NAME . ".php", key: self::CONFIG_FILE_NAME);
        $this->app->singleton(abstract: ExceptionHandler::class, concrete: HandlerUnAuthenticatedException::class);
    }

    public function boot(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        // add commands
        self::PackageCommands();
        // Overwrite auth config
        self::PackageOverwriteConfigs();
        // Load package Translation
        self::PackageLocales();
        // Load package routes
        $this->loadRoutesFrom(path: __DIR__ . "{$ds}Routes{$ds}" . self::ROUTES_FILE_NAME . ".php");
        // Load migrations
        $this->loadMigrationsFrom(paths: [
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}core{$ds}enums",
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}core{$ds}sessions",
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}core{$ds}locations",
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}core{$ds}localizations",
            __DIR__ . "{$ds}Database{$ds}Migrations{$ds}users",
        ]);
        // Load views
        $this->loadViewsFrom(path: __DIR__ . "{$ds}Views", namespace: "Bhry98");
        // Automatically publish migrations
        if ($this->app->runningInConsole()) {
            // Publish migration file
            $this->publishes([
                __DIR__ . "{$ds}Database{$ds}Migrations{$ds}" => database_path(path: 'migrations'),
            ], groups: 'migrations');
            // Publish config file
            $this->publishes([
                __DIR__ . "{$ds}Config{$ds}" . self::CONFIG_FILE_NAME . ".php" => config_path(path: self::CONFIG_FILE_NAME . '.php'),
            ], groups: 'config');
        }
    }

    function packageOverwriteConfigs(): void
    {
        // set auth model in auth.php
        config()->set('auth.providers.users.model', config(key: self::CONFIG_FILE_NAME . ".config.auth.user_model", default: UsersCoreUsersModel::class));
        // set mail.php
        config()->set('mail.mailers.smtp', config(key: self::CONFIG_FILE_NAME . ".config.mail.smtp"));
        config()->set('mail.from', config(key: self::CONFIG_FILE_NAME . ".config.mail.from"));
        // set session table in session.php
        config()->set('session.table', CoreSessionsModel::TABLE_NAME);
        // Overriding Default Personal Access Token Models
        Sanctum::usePersonalAccessTokenModel(model: CoreSessionsPersonalAccessTokenModel::class);
    }

    function PackageCommands(): void
    {
        $this->commands([
            CountriesRunSeedCommand::class,
            EnumsRunSeedCommand::class,
        ]);
    }

    function PackageLocales(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $this->loadTranslationsFrom(
            path: __DIR__ . "{$ds}Lang", namespace: "bhry98");
    }

}