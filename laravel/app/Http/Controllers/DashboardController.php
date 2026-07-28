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
        // Durasi disamakan dengan manual yaitu 10 detik
        $wateringDuration = 10;
        return view('dashboard.index', compact('latestData', 'historyData', 'wateringDuration'));
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
            'smart_sensor_enabled'   => Setting::get('smart_sensor_enabled', '1'),
            'smart_sensor_threshold' => Setting::get('smart_sensor_threshold', '40'),
            'scheduled_enabled'      => Setting::get('scheduled_enabled', '0'),
            'scheduled_times'        => json_decode(Setting::get('scheduled_times', '["06:30", "18:30"]'), true) ?: ["06:30", "18:30"],
        ];
        return view('dashboard.jadwal', compact('latestData', 'settings'));
    }

    // API: Data sensor terbaru (untuk AJAX auto-refresh)
    public function latestSensor()
    {
        try {
            $latestData = SensorData::latest()->first();
            if (!$latestData) {
                return response()->json([
                    'suhu_tanah'   => 0,
                    'ph_tanah'     => 0,
                    'kelembaban'   => 0,
                    'status_hujan' => false,
                    'status_pompa' => false,
                    'updated_at'   => now()->toDateTimeString(),
                ]);
            }
            return response()->json($latestData);
        } catch (\Exception $e) {
            return response()->json([
                'suhu_tanah'   => 0,
                'ph_tanah'     => 0,
                'kelembaban'   => 0,
                'status_hujan' => false,
                'status_pompa' => false,
                'updated_at'   => now()->toDateTimeString(),
                'error'        => true,
            ]);
        }
    }

    // Kontrol Pompa via MQTT Publish
    public function togglePompa(Request $request)
    {
        $action = $request->input('action', 'on');
        $isOn   = ($action === 'on');

        // Safety lock: cek kondisi hujan terkini sebelum menyalakan pompa
        $latestData = SensorData::latest()->first();
        if ($isOn && $latestData && $latestData->status_hujan) {
            return response()->json([
                'success'          => false,
                'blocked_by_rain'  => true,
                'message'          => 'Pompa tidak dapat dinyalakan: hujan sedang terdeteksi di area kebun.',
            ], 422);
        }

        // === STRATEGI DB-FIRST ===
        if ($latestData) {
            SensorData::create([
                'suhu_tanah'   => $latestData->suhu_tanah,
                'ph_tanah'     => $latestData->ph_tanah,
                'kelembaban'   => $latestData->kelembaban,
                'status_hujan' => $latestData->status_hujan,
                'status_pompa' => $isOn,
            ]);
        }

        // === KIRIM KE ESP32 VIA MQTT ===
        $mqttSuccess = false;
        $mqttMessage = '';
        try {
            $mqtt = new MqttClient(
                env('MQTT_HOST', 'broker.hivemq.com'),
                env('MQTT_PORT', 1883),
                'cota_web_' . uniqid()
            );
            $connectionSettings = (new ConnectionSettings)
                ->setKeepAliveInterval(10)
                ->setConnectTimeout(3);

            if ($username = env('MQTT_USERNAME')) {
                $connectionSettings = $connectionSettings->setUsername($username);
            }
            if ($password = env('MQTT_PASSWORD')) {
                $connectionSettings = $connectionSettings->setPassword($password);
            }
            if (env('MQTT_TLS', false)) {
                $connectionSettings = $connectionSettings->setUseTls(true);
                $connectionSettings = $connectionSettings->setTlsVerifyPeer(false);
                $connectionSettings = $connectionSettings->setTlsVerifyPeerName(false);
            }

            $mqtt->connect($connectionSettings, true);
            $mqtt->publish(env('MQTT_TOPIC_PERINTAH', 'cota/command/feed_all'), json_encode([
                'pompa'     => $isOn,
                'source'    => 'web',
                'timestamp' => now()->toDateTimeString(),
            ]), 0);
            $mqtt->disconnect();
            $mqttSuccess = true;
        } catch (\Exception $e) {
            $mqttMessage = 'Sinyal MQTT tidak terkirim (broker tidak terjangkau), namun status pompa sudah diperbarui.';
        }

        return response()->json([
            'success'      => true,
            'action'       => $action,
            'mqtt_ok'      => $mqttSuccess,
            'message'      => $mqttSuccess
                ? 'Perintah berhasil dikirim via MQTT.'
                : $mqttMessage,
        ]);
    }

    // Simpan Pengaturan Jadwal
    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'smart_sensor_enabled'   => 'required|in:0,1',
            'smart_sensor_threshold' => 'required|numeric|min:0|max:100',
            'scheduled_enabled'      => 'required|in:0,1',
            'scheduled_times'        => 'nullable|array',
            'scheduled_times.*'      => 'nullable|date_format:H:i',
        ]);

        // Filter jam yang kosong
        $times = array_filter($request->input('scheduled_times', []));
        sort($times);

        Setting::set('smart_sensor_enabled', $request->smart_sensor_enabled);
        Setting::set('smart_sensor_threshold', $request->smart_sensor_threshold);
        Setting::set('scheduled_enabled', $request->scheduled_enabled);
        // Durasi disamakan dengan manual yaitu 10 detik
        Setting::set('watering_duration', 10);
        Setting::set('scheduled_times', json_encode($times));

        // Publish settings ke ESP32 via MQTT
        try {
            $mqtt = new MqttClient(env('MQTT_HOST', 'broker.hivemq.com'), env('MQTT_PORT', 1883), 'cota_web_' . uniqid());
            $connectionSettings = (new ConnectionSettings)
                ->setKeepAliveInterval(60)
                ->setConnectTimeout(3);

            if ($username = env('MQTT_USERNAME')) {
                $connectionSettings = $connectionSettings->setUsername($username);
            }
            if ($password = env('MQTT_PASSWORD')) {
                $connectionSettings = $connectionSettings->setPassword($password);
            }
            if (env('MQTT_TLS', false)) {
                $connectionSettings = $connectionSettings->setUseTls(true);
                $connectionSettings = $connectionSettings->setTlsVerifyPeer(false);
                $connectionSettings = $connectionSettings->setTlsVerifyPeerName(false);
            }

            $mqtt->connect($connectionSettings, true);

            $payload = json_encode([
                'smart_sensor_enabled' => (bool) $request->smart_sensor_enabled,
                'threshold'            => (float) $request->smart_sensor_threshold,
                'scheduled_enabled'    => (bool) $request->scheduled_enabled,
                'watering_duration'    => 10,
                'scheduled_times'      => $times,
            ]);
            $mqtt->publish('cota/settings', $payload, 0);
            $mqtt->disconnect();
        } catch (\Exception $e) {
            // Settings tetap tersimpan di DB meski MQTT gagal
        }

        return response()->json(['success' => true, 'message' => 'Pengaturan berhasil disimpan']);
    }
}
