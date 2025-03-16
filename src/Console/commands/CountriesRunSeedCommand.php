<?php

namespace Bhry98\LaravelStarterKit\Console\commands;

use Bhry98\LaravelStarterKit\Database\Seeders\core\CoreDefaultEnumsSeeder;
use Bhry98\LaravelStarterKit\Database\Seeders\core\CoreLocationsSeeder;
use Illuminate\Console\Command;

class CountriesRunSeedCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bhry98-starter:seed-default-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add default package countries';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line("starting seeding");
        $this->line("starting seeding locations");
        $this->call('db:seed', ['--class' => CoreLocationsSeeder::class]);
        $this->info("seeded successfully");
    }

}