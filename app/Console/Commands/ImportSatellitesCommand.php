<?php

namespace App\Console\Commands;

use App\Services\NasaService;
use Illuminate\Console\Command;

class ImportSatellitesCommand extends Command
{
    protected $signature = 'space:import-satellites';
    protected $description = 'Import satellites from Celestrak';

    public function handle(NasaService $nasa): int
    {
        $this->info('Fetching satellites from Celestrak...');
        $satellites = $nasa->fetchSatellites();

        if (empty($satellites)) {
            $this->error('No satellites found');
            return Command::FAILURE;
        }

        $count = $nasa->importSatellites($satellites);
        $this->info("Imported {$count} satellites");

        return Command::SUCCESS;
    }
}
