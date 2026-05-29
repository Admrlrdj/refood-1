<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
// Panggil semua model MongoDB lu di sini
use App\Models\Admin;
use App\Models\Donor;
use App\Models\Food;
use App\Models\Receiver;
use App\Models\Delivery;
use App\Models\Volunteer;

class MigrateSqlToMongo extends Command
{
    // Ini perintah yang nanti dijalankan di terminal
    protected $signature = 'db:migrate-to-mongo';
    protected $description = 'Migrasi data dari MySQL ke MongoDB';

    public function handle()
    {
        $this->info('--- MEMULAI MIGRASI DATA ---');

        // 1. Ambil & Pindahkan Data Kategori / Master: Donors
        $this->info('Memindahkan data Donors...');
        $donors = DB::connection('mysql_lama')->table('donors')->get();
        foreach ($donors as $donor) {
            Donor::create([
                '_id' => $donor->id, // Mengunci ID lama agar relasi tidak rusak
                'name' => $donor->name,
                'type' => $donor->type,
                'pic_name' => $donor->pic_name,
                'phone' => $donor->phone,
                'email' => $donor->email,
                'address' => $donor->address,
                'created_at' => $donor->created_at,
                'updated_at' => $donor->updated_at,
            ]);
        }

        // 2. Ambil & Pindahkan Data Receivers
        $this->info('Memindahkan data Receivers...');
        $receivers = DB::connection('mysql_lama')->table('receivers')->get();
        foreach ($receivers as $receiver) {
            Receiver::create([
                '_id' => $receiver->id,
                'name' => $receiver->name,
                'type' => $receiver->type,
                'pic_name' => $receiver->pic_name,
                'phone' => $receiver->phone,
                'email' => $receiver->email,
                'address' => $receiver->address,
                'capacity_people' => $receiver->capacity_people,
                'need_level' => $receiver->need_level,
                'created_at' => $receiver->created_at,
                'updated_at' => $receiver->updated_at,
            ]);
        }

        // 3. Ambil & Pindahkan Data Foods
        $this->info('Memindahkan data Foods...');
        $foods = DB::connection('mysql_lama')->table('foods')->get();
        foreach ($foods as $food) {
            Food::create([
                '_id' => $food->id,
                'name' => $food->name,
                'category' => $food->category,
                'portion' => $food->portion,
                'donor_id' => $food->donor_id,
                'receiver_id' => $food->receiver_id,
                'status' => $food->status,
                'collection_date' => $food->collection_date,
                'photo' => $food->photo,
                'note' => $food->note,
                'created_at' => $food->created_at,
                'updated_at' => $food->updated_at,
            ]);
        }

        // 4. Ambil & Pindahkan Data Deliveries
        $this->info('Memindahkan data Deliveries...');
        $deliveries = DB::connection('mysql_lama')->table('deliveries')->get();
        foreach ($deliveries as $delivery) {
            
            // Opsi A: REFERENCING (Saran Gw - Tetap simpan ID saja)
            Delivery::create([
                '_id' => $delivery->id,
                'food_id' => $delivery->food_id,
                'donor_id' => $delivery->donor_id,
                'receiver_id' => $delivery->receiver_id,
                'volunteer_id' => $delivery->volunteer_id,
                'status' => $delivery->status,
                'pickup_time' => $delivery->pickup_time,
                'eta_minutes' => $delivery->eta_minutes,
                'is_expiring' => $delivery->is_expiring,
                'note' => $delivery->note,
                'lat' => $delivery->lat,
                'lng' => $delivery->lng,
                'created_at' => $delivery->created_at,
                'updated_at' => $delivery->updated_at,
            ]);

            /* // Tambahan info buat lu bray: 
            // Opsi B: EMBEDDING (Gaya murni NoSQL)
            // Kalau mau pake ini, lu harus join-in datanya langsung di sini, contoh:
            $foodData = DB::connection('mysql_lama')->table('foods')->where('id', $delivery->food_id)->first();
            Delivery::create([
                'status' => $delivery->status,
                'food_detail' => $foodData ? (array)$foodData : null, // Datanya langsung nempel di dalam delivery
            ]);
            */
        }

        // 5. Ambil & Pindahkan Data Admins (Untuk Login)
        $this->info('Memindahkan data Admins...');
        $admins = DB::connection('mysql_lama')->table('admins')->get();
        foreach ($admins as $admin) {
            Admin::create([
                '_id' => $admin->id,
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
                'password' => $admin->password, // Hash password aman bawaan SQL tetap terjaga
                'last_login_at' => $admin->last_login_at,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);
        }

        $this->info('--- MIGRASI BERHASIL SELESAI ---');
    }
}