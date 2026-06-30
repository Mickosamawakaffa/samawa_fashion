<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->baseUrl = config('services.rajaongkir.url');
    }

    /**
     * Get all provinces from RajaOngkir
     *
     * @return array
     */
    public function getProvinces()
    {
        if (!$this->apiKey) {
            logger()->warning('RajaOngkir API Key is not configured.');
            return [];
        }

        return Cache::remember('rajaongkir_provinces', 24 * 60 * 60, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get("{$this->baseUrl}/province");

                if ($response->successful()) {
                    return $response->json()['rajaongkir']['results'] ?? [];
                }
                
                logger()->error('RajaOngkir getProvinces failed: ' . $response->body());
            } catch (\Exception $e) {
                logger()->error('RajaOngkir getProvinces exception: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Get cities in a province from RajaOngkir
     *
     * @param int|string $provinceId
     * @return array
     */
    public function getCities($provinceId)
    {
        if (!$this->apiKey) {
            logger()->warning('RajaOngkir API Key is not configured.');
            return [];
        }

        $cacheKey = 'rajaongkir_cities_prov_' . $provinceId;
        return Cache::remember($cacheKey, 24 * 60 * 60, function () use ($provinceId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get("{$this->baseUrl}/city", [
                    'province' => $provinceId
                ]);

                if ($response->successful()) {
                    return $response->json()['rajaongkir']['results'] ?? [];
                }
                
                logger()->error('RajaOngkir getCities failed: ' . $response->body());
            } catch (\Exception $e) {
                logger()->error('RajaOngkir getCities exception: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Calculate cost of shipping
     *
     * @param int|string $origin
     * @param int|string $destination
     * @param int $weight
     * @param string $courier
     * @return array
     */
    public function getCost($origin, $destination, $weight, $courier)
    {
        if (!$this->apiKey) {
            logger()->warning('RajaOngkir API Key is not configured.');
            return [];
        }

        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->asForm()->post("{$this->baseUrl}/cost", [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => (int)$weight,
                'courier' => strtolower($courier)
            ]);

            if ($response->successful()) {
                return $response->json()['rajaongkir']['results'] ?? [];
            }
            
            logger()->error('RajaOngkir cost calculation failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            logger()->error('RajaOngkir getCost exception: ' . $e->getMessage());
        }

        return [];
    }
}
