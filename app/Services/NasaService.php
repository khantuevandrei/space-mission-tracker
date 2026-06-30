<?php

namespace App\Services;

use App\Models\Satellite;
use App\Models\Mission;
use Illuminate\Support\Facades\Http;

class NasaService
{
    private string $baseUrl = 'https://api.nasa.gov';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NASA_API_KEY');
    }

    /**
     * Fetch satellites from NASA NEO (Near Earth Objects) API.
     * Return asteroids/comets tracked by NASA.
     */
    public function fetchNearEarthObjects(): array
    {
        $response = Http::get("{$this->baseUrl}/neo/rest/v1/neo/browse", [
            'api_key' => $this->apiKey,
        ]);

        if (!$response->ok()) return [];

        return $response->json('near_earth_objects', []);
    }

    /**
     * Fetch EPIC (Earth Polychromatic Imaging Camera) data.
     * Earth photos from DSCOVR sattelite.
     */
    public function fetchEpicImages(): array
    {
        $response = Http::get("{$this->baseUrl}/EPIC/api/natural", [
            'api_key' => $this->apiKey,
        ]);

        if (!$response->ok()) return [];

        return array_slice($response->json(), 0, 5);
    }

    /**
     * Fetch APOD (Atronomy Picture of the Day).
     */
    public function fetchApod(): ?array
    {
        $response = Http::get("{$this->baseUrl}/planetary/apod", [
            'api_key' => $this->apiKey,
        ]);

        if (!$response->ok()) return null;

        return $response->json();
    }

    /**
     * Fetch Mars Rover photos (Curiosity, Perseverance).
     */
    public function fetchMarsPhotos(string $rover = 'perseverance', int $sol = 1000): array
    {
        $response = Http::get("{$this->baseUrl}/mars-photos/api/v1/rovers/{$rover}/photos", [
            'api_key' => $this->apiKey,
            'sol' => $sol,
        ]);

        if (!$response->ok()) return [];

        return array_slice($response->json('photos', []), 0, 10);
    }

    /**
     * Fetch known satellites from Celestrak (TLE data).
     * This is main data source for orbit tracking.
     */
    public function fetchSatellites(): array
    {
        // Celestrak public API — no key required
        $url = 'https://celestrak.org/NORAD/elements/gp.php?GROUP=active&FORMAT=tle';

        $response = Http::withHeaders(['Cache-Control' => 'no-cache'])
            ->withOptions(['verify' => false])
            ->get($url);

        if (!$response->ok()) return [];

        $lines = explode("\n", $response->body());
        $satellites = [];

        for ($i = 0; $i < count($lines) - 2; $i += 3) {
            $name = trim($lines[$i]);
            $tle1 = trim($lines[$i + 1] ?? '');
            $tle2 = trim($lines[$i + 2] ?? '');

            if (empty($name) || empty($tle1)) continue;

            $satellites[] = [
                'name' => $name,
                'tle_line1' => $tle1,
                'tle_line2' => $tle2,
                'status' => 'active',
                'type' => 'unknown',
            ];
        }

        return $satellites;
    }

    /**
     * Save satellites to database, skipping duplicates by NORAD ID.
     */
    public function importSatellites(array $satellites): int
    {
        $count = 0;

        foreach ($satellites as $data) {
            // Extract NORAD ID from TLE line 2 (columns 3-7)
            $noradId = isset($data['tle_line2']) ? (int) substr($data['tle_line2'], 2, 5) : null;

            if (!$noradId) continue;

            Satellite::firstOrCreate(
                ['norad_id' => $noradId],
                [
                    'name' => $data['name'],
                    'tle_line1' => $data['tle_line1'],
                    'tle_line2' => $data['tle_line2'],
                    'status' => $data['status'] ?? 'active',
                    'type' => $data['type'] ?? 'unknown',
                ]
            );
            $count++;
        }

        return $count;
    }
}
