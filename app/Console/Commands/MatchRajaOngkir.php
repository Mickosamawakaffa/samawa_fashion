<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\Http;

class MatchRajaOngkir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:match-rajaongkir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match local provinces and cities with RajaOngkir API IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.rajaongkir.key');
        if (!$apiKey) {
            $this->error('RAJAONGKIR_API_KEY is not configured in .env. Real-time API matching skipped.');
            return 1;
        }

        $baseUrl = config('services.rajaongkir.url', 'https://api.rajaongkir.com/starter');

        $this->info('Fetching provinces from RajaOngkir...');
        try {
            $response = Http::withHeaders(['key' => $apiKey])->get("{$baseUrl}/province");
            if ($response->failed()) {
                $this->error('Failed to fetch provinces from RajaOngkir: ' . $response->body());
                return 1;
            }

            $rajaProvinces = $response->json()['rajaongkir']['results'] ?? [];
            foreach ($rajaProvinces as $rp) {
                // Match by name
                $localProv = Province::where('name', 'like', '%' . $rp['province'] . '%')
                    ->orWhere('name', 'like', '%' . str_replace(' ', '%', $rp['province']) . '%')
                    ->first();

                if ($localProv) {
                    $localProv->update(['rajaongkir_id' => $rp['province_id']]);
                    $this->line("Matched Province: {$rp['province']} -> RajaOngkir ID: {$rp['province_id']}");
                }
            }
        } catch (\Exception $e) {
            $this->error('Province matching error: ' . $e->getMessage());
        }

        $this->info('Fetching cities from RajaOngkir...');
        try {
            $response = Http::withHeaders(['key' => $apiKey])->get("{$baseUrl}/city");
            if ($response->failed()) {
                $this->error('Failed to fetch cities from RajaOngkir: ' . $response->body());
                return 1;
            }

            $rajaCities = $response->json()['rajaongkir']['results'] ?? [];
            foreach ($rajaCities as $rc) {
                $localProv = Province::where('rajaongkir_id', $rc['province_id'])->first();
                if (!$localProv) {
                    continue;
                }

                $cityNameClean = trim(str_ireplace(['Kabupaten', 'Kota'], '', $rc['city_name']));
                
                $localCity = City::where('province_id', $localProv->id)
                    ->where(function($query) use ($cityNameClean) {
                        $query->where('name', 'like', '%' . $cityNameClean . '%')
                              ->orWhere('name', 'like', '%' . str_replace(' ', '%', $cityNameClean) . '%');
                    })
                    ->first();

                if ($localCity) {
                    $localCity->update([
                        'rajaongkir_id' => $rc['city_id'],
                        'type' => $rc['type'],
                        'postal_code' => $rc['postal_code']
                    ]);
                    $this->line("Matched City: {$rc['type']} {$rc['city_name']} -> RajaOngkir ID: {$rc['city_id']}");
                }
            }
        } catch (\Exception $e) {
            $this->error('City matching error: ' . $e->getMessage());
        }

        $this->info('RajaOngkir matching finished!');
        return 0;
    }
}
