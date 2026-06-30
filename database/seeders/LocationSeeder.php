<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\City;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing local data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        City::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Check if indonesia_provinces is empty, if so, seed Laravolt first
        if (DB::table('indonesia_provinces')->count() === 0) {
            $this->command->info('Seeding Laravolt Indonesia data...');
            \Illuminate\Support\Facades\Artisan::call('laravolt:indonesia:seed');
        }

        // 1. Fetch from indonesia_provinces
        $laravoltProvinces = DB::table('indonesia_provinces')->get();

        $rajaProvinces = [
            1 => 'Bali',
            2 => 'Bangka Belitung',
            3 => 'Banten',
            4 => 'Bengkulu',
            5 => 'DI Yogyakarta',
            6 => 'DKI Jakarta',
            7 => 'Gorontalo',
            8 => 'Jambi',
            9 => 'Jawa Barat',
            10 => 'Jawa Tengah',
            11 => 'Jawa Timur',
            12 => 'Kalimantan Barat',
            13 => 'Kalimantan Selatan',
            14 => 'Kalimantan Tengah',
            15 => 'Kalimantan Timur',
            16 => 'Kalimantan Utara',
            17 => 'Kepulauan Riau',
            18 => 'Lampung',
            19 => 'Maluku',
            20 => 'Maluku Utara',
            21 => 'Nanggroe Aceh Darussalam (NAD)',
            22 => 'Nusa Tenggara Barat (NTB)',
            23 => 'Nusa Tenggara Timur (NTT)',
            24 => 'Papua',
            25 => 'Papua Barat',
            26 => 'Riau',
            27 => 'Sulawesi Barat',
            28 => 'Sulawesi Selatan',
            29 => 'Sulawesi Tengah',
            30 => 'Sulawesi Tenggara',
            31 => 'Sulawesi Utara',
            32 => 'Sumatera Barat',
            33 => 'Sumatera Selatan',
            34 => 'Sumatera Utara'
        ];

        $provinceCodeMap = []; // code -> local_id

        foreach ($laravoltProvinces as $lp) {
            // Find matched RajaOngkir ID
            $rajaId = null;
            $lpNameClean = strtolower(trim(str_replace(['provinsi', 'di ', 'dki '], '', $lp->name)));
            
            foreach ($rajaProvinces as $roId => $roName) {
                $roNameClean = strtolower(trim(str_replace(['nanggroe ', ' (nad)', ' (ntb)', ' (ntt)', 'di ', 'dki '], '', $roName)));
                if (str_contains($lpNameClean, $roNameClean) || str_contains($roNameClean, $lpNameClean)) {
                    $rajaId = $roId;
                    break;
                }
            }

            $province = Province::create([
                'rajaongkir_id' => $rajaId,
                'name' => ucwords(strtolower($lp->name)),
            ]);

            $provinceCodeMap[$lp->code] = $province->id;
        }

        // 2. Fetch from indonesia_cities
        $laravoltCities = DB::table('indonesia_cities')->get();

        // Fallback RajaOngkir mapping for key cities (in case API is offline/not configured)
        $rajaCitiesMap = [
            'jakarta barat' => ['id' => 151, 'type' => 'Kota', 'postal' => '11210'],
            'jakarta pusat' => ['id' => 152, 'type' => 'Kota', 'postal' => '10110'],
            'jakarta selatan' => ['id' => 153, 'type' => 'Kota', 'postal' => '12110'],
            'jakarta timur' => ['id' => 154, 'type' => 'Kota', 'postal' => '13110'],
            'jakarta utara' => ['id' => 155, 'type' => 'Kota', 'postal' => '14110'],
            'kepulauan seribu' => ['id' => 156, 'type' => 'Kabupaten', 'postal' => '14510'],
            'bekasi' => ['id' => 55, 'type' => 'Kota', 'postal' => '17110'],
            'kabupaten bekasi' => ['id' => 54, 'type' => 'Kabupaten', 'postal' => '17510'],
            'bogor' => ['id' => 78, 'type' => 'Kota', 'postal' => '16110'],
            'kabupaten bogor' => ['id' => 77, 'type' => 'Kabupaten', 'postal' => '16910'],
            'depok' => ['id' => 115, 'type' => 'Kota', 'postal' => '16410'],
            'tangerang' => ['id' => 456, 'type' => 'Kota', 'postal' => '15110'],
            'kabupaten tangerang' => ['id' => 455, 'type' => 'Kabupaten', 'postal' => '15510'],
            'tangerang selatan' => ['id' => 457, 'type' => 'Kota', 'postal' => '15310'],
            'bandung' => ['id' => 23, 'type' => 'Kota', 'postal' => '40110'],
            'kabupaten bandung' => ['id' => 22, 'type' => 'Kabupaten', 'postal' => '40310'],
            'bandung barat' => ['id' => 24, 'type' => 'Kabupaten', 'postal' => '40550'],
            'surabaya' => ['id' => 444, 'type' => 'Kota', 'postal' => '60110'],
            'malang' => ['id' => 256, 'type' => 'Kota', 'postal' => '65110'],
            'kabupaten malang' => ['id' => 255, 'type' => 'Kabupaten', 'postal' => '65150'],
            'semarang' => ['id' => 399, 'type' => 'Kota', 'postal' => '50110'],
            'kabupaten semarang' => ['id' => 398, 'type' => 'Kabupaten', 'postal' => '50510'],
            'surakarta' => ['id' => 445, 'type' => 'Kota', 'postal' => '57110'],
            'yogyakarta' => ['id' => 501, 'type' => 'Kota', 'postal' => '55110'],
            'sleman' => ['id' => 419, 'type' => 'Kabupaten', 'postal' => '55510'],
            'denpasar' => ['id' => 114, 'type' => 'Kota', 'postal' => '80110'],
            'makassar' => ['id' => 254, 'type' => 'Kota', 'postal' => '90110'],
            'medan' => ['id' => 278, 'type' => 'Kota', 'postal' => '20110'],
            'palembang' => ['id' => 327, 'type' => 'Kota', 'postal' => '30110'],
        ];

        foreach ($laravoltCities as $lc) {
            $localProvinceId = $provinceCodeMap[$lc->province_code] ?? null;
            if (!$localProvinceId) {
                continue;
            }

            // Detect type (Kota or Kabupaten)
            $type = 'Kabupaten';
            $lcNameLower = strtolower(trim($lc->name));
            if (str_contains($lcNameLower, 'kota')) {
                $type = 'Kota';
            }

            $nameClean = str_ireplace(['kabupaten administrasi ', 'kota administrasi ', 'kabupaten ', 'kota '], '', $lc->name);
            $nameClean = trim($nameClean);

            // Pre-mapped RajaOngkir ID fallback
            $rajaId = null;
            $postalCode = null;
            $mapKey = strtolower(trim(str_ireplace(['kabupaten administrasi ', 'kota administrasi '], ['kabupaten ', 'kota '], $lc->name)));
            
            // Try matching directly
            if (isset($rajaCitiesMap[$mapKey])) {
                $rajaId = $rajaCitiesMap[$mapKey]['id'];
                $type = $rajaCitiesMap[$mapKey]['type'];
                $postalCode = $rajaCitiesMap[$mapKey]['postal'];
            } else {
                // Try matching without type prefix
                $mapKeyNoType = str_replace(['kabupaten ', 'kota '], '', $mapKey);
                if (isset($rajaCitiesMap[$mapKeyNoType])) {
                    $rajaId = $rajaCitiesMap[$mapKeyNoType]['id'];
                    $type = $rajaCitiesMap[$mapKeyNoType]['type'];
                    $postalCode = $rajaCitiesMap[$mapKeyNoType]['postal'];
                }
            }

            City::create([
                'province_id' => $localProvinceId,
                'rajaongkir_id' => $rajaId,
                'name' => ucwords(strtolower($nameClean)),
                'type' => $type,
                'postal_code' => $postalCode,
            ]);
        }
    }
}
