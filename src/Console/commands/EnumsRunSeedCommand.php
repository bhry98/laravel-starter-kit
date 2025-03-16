<?php

namespace Bhry98\LaravelStarterKit\Console\commands;

use Bhry98\LaravelStarterKit\Database\Seeders\core\CoreDefaultEnumsSeeder;
use Bhry98\LaravelStarterKit\Database\Seeders\core\CoreLocationsSeeder;
use Illuminate\Console\Command;

class EnumsRunSeedCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bhry98-starter:seed-default-enums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add default package enums';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line("starting seeding");
        $this->line("starting seeding default users enums");
        $this->call('db:seed', ['--class' => CoreDefaultEnumsSeeder::class]);
        $this->info("seeded successfully");
    }

}