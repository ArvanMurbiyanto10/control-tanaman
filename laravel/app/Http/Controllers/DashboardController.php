<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Setting;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class DashboardController extends Controller
{
    // Halaman Dasbor Utama
    public function index()
    {
        $latestData = SensorData::latest()->first();
        $historyData = SensorData::latest()->take(10)->get();
        return view('dashboard.index', compact('latestData', 'historyData'));
    }

    // Halaman Detail & Grafik (Kelembaban, pH, Suhu)
    public function detail($jenis)
    {
        $latestData = SensorData::latest()->first();
        $historyData = SensorData::latest()->take(10)->get();
        return view('dashboard.detail', compact('latestData', 'historyData', 'jenis'));
    }

    // Halaman Jadwal & Automatisasi Pompa
    public function jadwal()
    {
        $latestData = SensorData::latest()->first();
        $settings = [
            'smart_sensor_enabled' => Setting::get('smart_sensor_enabled', '1'),
            'smart_sensor_threshold' => Setting::get('smart_sensor_threshold', '40'),
            'scheduled_enabled' => Setting::get('scheduled_enabled', '0'),
            'scheduled_start' => Setting::get('scheduled_start', '06:30'),
            'scheduled_end' => Setting::get('scheduled_end', '06:45'),
        ];
        return view('dashboard.jadwal', compact('latestData', 'settings'));
    }

    // API: Data sensor terbaru (untuk AJAX auto-refresh)
    public function latestSensor()
    {
        $latestData = SensorData::latest()->first();
        if (!$latestData) {
            return response()->json([
                'suhu_tanah' => 0,
                'ph_tanah' => 0,
                'kelembaban' => 0,
                'status_pompa' => false,
                'updated_at' => now()->toDateTimeString(),
            ]);
        }
        return response()->json($latestData);
    }

    // Kontrol Pompa via MQTT Publish
    public function togglePompa(Request $request)
    {
        $action = $request->input('action', 'on');

        try {
            $mqtt = new MqttClient('broker.hivemq.com', 1883, 'cota_web_' . uniqid());
            $connectionSettings = (new ConnectionSettings)->setKeepAliveInterval(60);
            $mqtt->connect($connectionSettings, true);

            $payload = json_encode([
                'pompa' => $action === 'on',
                'source' => 'web',
                'timestamp' => now()->toDateTimeString(),
            ]);
            $mqtt->publish('cota/pompa/kontrol', $payload, 0);
            $mqtt->disconnect();

            return response()->json(['success' => true, 'action' => $action, 'message' => 'Perintah berhasil dikirim ke ESP32']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim perintah: ' . $e->getMessage()], 500);
        }
    }

    // Simpan Pengaturan Jadwal
    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'smart_sensor_enabled' => 'required|in:0,1',
            'smart_sensor_threshold' => 'required|numeric|min:0|max:100',
            'scheduled_enabled' => 'required|in:0,1',
            'scheduled_start' => 'required|date_format:H:i',
            'scheduled_end' => 'required|date_format:H:i',
        ]);

        Setting::set('smart_sensor_enabled', $request->smart_sensor_enabled);
        Setting::set('smart_sensor_threshold', $request->smart_sensor_threshold);
        Setting::set('scheduled_enabled', $request->scheduled_enabled);
        Setting::set('scheduled_start', $request->scheduled_start);
        Setting::set('scheduled_end', $request->scheduled_end);

        // Publish settings ke ESP32 via MQTT
        try {
            $mqtt = new MqttClient('broker.hivemq.com', 1883, 'cota_web_' . uniqid());
            $connectionSettings = (new ConnectionSettings)->setKeepAliveInterval(60);
            $mqtt->connect($connectionSettings, true);

            $payload = json_encode([
                'smart_sensor_enabled' => (bool) $request->smart_sensor_enabled,
                'threshold' => (float) $request->smart_sensor_threshold,
                'scheduled_enabled' => (bool) $request->scheduled_enabled,
                'scheduled_start' => $request->scheduled_start,
                'scheduled_end' => $request->scheduled_end,
            ]);
            $mqtt->publish('cota/settings', $payload, 0);
            $mqtt->disconnect();
        } catch (\Exception $e) {
            // Settings tetap tersimpan di DB meski MQTT gagal
        }

        return response()->json(['success' => true, 'message' => 'Pengaturan berhasil disimpan']);
    }
}
