<?php


namespace Modules\Transport\app\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class GeoapifyService
{
    protected string $baseUrl = 'https://api.geoapify.com';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.geoapify.key');

        if (empty($this->apiKey)) {
            throw new Exception(
                'Geoapify API key is not configured.'
            );
        }
    }

    /**
     * Calculate route between two points.
     *
     * @param float $fromLatitude
     * @param float $fromLongitude
     * @param float $toLatitude
     * @param float $toLongitude
     * @param string $mode
     */
    public function calculateRoute(
        float  $fromLatitude,
        float  $fromLongitude,
        float  $toLatitude,
        float  $toLongitude,
        string $mode = 'drive'
    ): array
    {
        $this->ensureConfigured();
        $response = Http::timeout(15)
            ->get($this->baseUrl . '/v1/routing', [
                'waypoints' =>
                    "{$fromLatitude},{$fromLongitude}|{$toLatitude},{$toLongitude}",

                'mode' => $mode,

                'apiKey' => $this->apiKey,
            ]);

        if ($response->failed()) {
            throw new Exception(
                'Geoapify routing request failed: ' .
                $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Calculate distance and time between
     * multiple sources and targets.
     */
    public function calculateMatrix(
        array  $sources,
        array  $targets,
        string $mode = 'drive'
    ): array
    {
        $this->ensureConfigured();
        $response = Http::timeout(15)
            ->get($this->baseUrl . '/v1/matrix', [
                'sources' => $this->formatPoints($sources),
                'targets' => $this->formatPoints($targets),
                'mode' => $mode,
                'apiKey' => $this->apiKey,
            ]);

        if ($response->failed()) {
            throw new Exception(
                'Geoapify matrix request failed: ' .
                $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Convert points to Geoapify format.
     */
    protected function formatPoints(array $points): string
    {
        return collect($points)
            ->map(function ($point) {
                return "{$point['latitude']},{$point['longitude']}";
            })
            ->implode('|');
    }

    protected function ensureConfigured(): void
    {
        if (empty($this->apiKey)) {
            throw new Exception('Geoapify API key is not configured.');
        }
    }
}
