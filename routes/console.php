<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-complete shipped orders after 7 days
Schedule::call(function () {
    $orders = Order::where('status', 'shipped')
        ->where('shipped_at', '<=', now()->subDays(7))
        ->get();

    foreach ($orders as $order) {
        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                'completed_at' => now(),
            ]);
            DB::commit();

            // Notify user
            try {
                Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Auto-delivery mail failed: ' . $mailEx->getMessage());
            }
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Auto-delivery transaction failed: ' . $e->getMessage());
        }
    }
})->daily();

// Auto-clear flash sale prices after end datetime passes
Schedule::call(function () {
    \App\Models\Product::whereNotNull('flash_sale_price')
        ->where('flash_sale_end', '<=', now())
        ->update([
            'flash_sale_price' => null,
            'flash_sale_start' => null,
            'flash_sale_end' => null,
        ]);
})->everyFiveMinutes();

Artisan::command('app:prepare-production', function () {
    $this->info('Memulai pembersihan data untuk persiapan production...');
    
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    $this->line('- Menghapus transaksi testing...');
    DB::table('payments')->truncate();
    DB::table('order_items')->truncate();
    DB::table('orders')->truncate();
    DB::table('carts')->truncate();
    DB::table('wishlists')->truncate();
    DB::table('product_reviews')->truncate();

    $this->line('- Menghapus akun customer testing (user@samawa.com)...');
    DB::table('users')->where('email', 'user@samawa.com')->delete();

    $admin = DB::table('users')->where('email', 'admin@samawa.com')->first();
    if ($admin) {
        $newPass = \Illuminate\Support\Str::random(12);
        DB::table('users')->where('email', 'admin@samawa.com')->update([
            'password' => \Illuminate\Support\Facades\Hash::make($newPass),
        ]);
        $this->warn("Password admin@samawa.com berhasil di-reset menjadi: {$newPass}");
        $this->warn("Catat password di atas dengan baik sebelum go-live!");
    }
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    $this->info('Database siap untuk production!');
})->purpose('Clean up testing data and secure admin password for production launch');
