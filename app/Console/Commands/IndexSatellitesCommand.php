<?php

namespace App\Console\Commands;

use App\Models\Satellite;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class IndexSatellitesCommand extends Command
{
    protected $signature = 'space:index-satellites';
    protected $description = 'Index all satellites to Elasticsearch';

    public function handle(ElasticsearchService $es): int
    {
        $this->info('Indexing satellites...');
        $satellites = Satellite::all(['id', 'name', 'country', 'type', 'orbit_type', 'status'])->toArray();

        $es->bulkIndexSatellites($satellites);
        $this->info('Indexed ' . count($satellites) . ' satellites');

        return Command::SUCCESS;
    }
}
