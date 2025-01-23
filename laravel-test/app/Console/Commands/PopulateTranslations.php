<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;

class PopulateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:translations {count=100000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the translations table with dummy data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->output->info("Starting population of {$count} translations...");
        Translation::factory()->count($count)->create();
        $this->output->success("Successfully populated {$count} translations.");
    }
}
