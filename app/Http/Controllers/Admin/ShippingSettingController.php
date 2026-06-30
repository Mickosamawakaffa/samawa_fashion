<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class ShippingSettingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $provinces = \App\Models\Province::orderBy('name')->get();

        // Get setting values or default fallbacks
        $originProvince = Setting::getValue('shipping_origin_province', '');
        $originCity = Setting::getValue('shipping_origin_city', '');
        
        if (!$originProvince || !$originCity) {
            $envRajaId = env('STORE_ORIGIN_CITY_ID', 153);
            $defaultCity = \App\Models\City::where('rajaongkir_id', $envRajaId)->first();
            if ($defaultCity) {
                $originCity = $originCity ?: $defaultCity->id;
                $originProvince = $originProvince ?: $defaultCity->province_id;
            } else {
                $originCity = $originCity ?: 158; // Jakarta Barat local ID fallback
                $originProvince = $originProvince ?: 11; // DKI Jakarta local ID fallback
            }
        }
        $freeShippingThreshold = Setting::getValue('shipping_free_min_spend', 500000);
        $defaultWeight = Setting::getValue('shipping_default_weight', 500);
        
        $courierJne = Setting::getValue('shipping_courier_jne', 1);
        $courierJnt = Setting::getValue('shipping_courier_jnt', 1);
        $courierSicepat = Setting::getValue('shipping_courier_sicepat', 1);

        // Fetch cities of the currently configured origin province to pre-populate city dropdown
        $cities = [];
        if ($originProvince) {
            $cities = \App\Models\City::where('province_id', $originProvince)->orderBy('name')->get();
        }

        return view('admin.settings.shipping', compact(
            'provinces',
            'cities',
            'originProvince',
            'originCity',
            'freeShippingThreshold',
            'defaultWeight',
            'courierJne',
            'courierJnt',
            'courierSicepat'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shipping_origin_province' => 'required|integer',
            'shipping_origin_city' => 'required|integer',
            'shipping_free_min_spend' => 'required|integer|min:0',
            'shipping_default_weight' => 'required|integer|min:1',
        ]);

        Setting::setValue('shipping_origin_province', $request->shipping_origin_province);
        Setting::setValue('shipping_origin_city', $request->shipping_origin_city);
        Setting::setValue('shipping_free_min_spend', $request->shipping_free_min_spend);
        Setting::setValue('shipping_default_weight', $request->shipping_default_weight);
        
        Setting::setValue('shipping_courier_jne', $request->has('shipping_courier_jne') ? 1 : 0);
        Setting::setValue('shipping_courier_jnt', $request->has('shipping_courier_jnt') ? 1 : 0);
        Setting::setValue('shipping_courier_sicepat', $request->has('shipping_courier_sicepat') ? 1 : 0);

        return redirect()->back()->with('success', 'Konfigurasi pengiriman berhasil diperbarui');
    }
}
