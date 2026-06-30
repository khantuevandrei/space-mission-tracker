<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    private $client;

    public function __construct()
    {
        $host = env('ELASTICSEARCH_HOST', 'elasticsearch:9200');
        $this->client = ClientBuilder::create()
            ->setHosts([$host])
            ->build();
    }

    /**
     * Index a satellite document.
     */
    public function indexSatellite(array $data): void
    {
        $this->client->index([
            'index' => 'satellites',
            'id' => $data['id'],
            'body' => $data,
        ]);
    }

    /**
     * Search satellites by query.
     */
    public function search(string $query, array $filters = []): array
    {
        $params = [
            'index' => 'satellites',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => $query,
                                'fields' => ['name^3', 'country', 'type', 'orbit_type'],
                            ],
                        ],
                    ],
                ],
                'size' => 20,
            ],
        ];

        // Add filters
        if (!empty($filters['country'])) {
            $params['body']['query']['bool']['filter'][] = ['term' => ['country' => $filters['country']]];
        }
        if (!empty($filters['type'])) {
            $params['body']['query']['bool']['filter'][] = ['term' => ['type' => $filters['type']]];
        }

        $response = $this->client->search($params);
        return $response['hits']['hits'] ?? [];
    }

    /**
     * Bulk index all satellites.
     */
    public function bulkIndexSatellites(array $satellites): void
    {
        $body = [];
        foreach ($satellites as $sat) {
            $body[] = ['index' => ['_index' => 'satellites', '_id' => $sat['id']]];
            $body[] = $sat;
        }

        if (!empty($body)) {
            $this->client->bulk(['body' => $body]);
        }
    }
}
