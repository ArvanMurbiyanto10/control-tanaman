<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SensorData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // WAJIB DITAMBAHKAN AGAR HASH BISA DIGUNAKAN

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan tidak menduplikasi jika seeder dijalankan ulang
        if (User::where('username', 'doktortj')->count() === 0) {
            User::create([ // <-- Gunakan create langsung, tanpa factory()
                'name' => 'DoktorTj Tegal',
                'username' => 'doktortj',
                'password' => Hash::make('987654321'),
            ]);
        }

        // Seed data sensor tanaman jika masih kosong agar dashboard terlihat hidup
        if (SensorData::count() === 0) {
            $now = now();
            for ($i = 9; $i >= 0; $i--) {
                SensorData::create([
                    'suhu_tanah' => rand(235, 290) / 10,     // 23.5 - 29.0 °C
                    'ph_tanah' => rand(58, 68) / 10,         // 5.8 - 6.8
                    'kelembaban' => rand(50, 78),            // 50% - 78%
                    'status_pompa' => rand(0, 1) === 1,      // Status pompa menyala/mati secara acak
                    'created_at' => $now->copy()->subMinutes($i * 30),
                    'updated_at' => $now->copy()->subMinutes($i * 30),
                ]);
            }
        }
    }
}
