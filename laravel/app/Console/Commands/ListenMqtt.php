<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\SensorData;

class ListenMqtt extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Mendengarkan data sensor dari Arduino via MQTT';

    public function handle()
    {
        // Ganti dengan broker MQTT yang nanti Anda pakai di kodingan Arduino
        $server   = env('MQTT_HOST', 'broker.hivemq.com');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'cota_backend_' . uniqid();

        $this->info("Menghubungkan ke MQTT Broker: {$server}...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = (new ConnectionSettings)->setKeepAliveInterval(60);

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
            $sensorTopic = env('MQTT_TOPIC_SENSOR', 'cota/sensor/data');
            $this->info("Berhasil terhubung! Menunggu data dari topik: {$sensorTopic}");

            // Topik ini harus sama persis dengan yang di-publish oleh Arduino nanti
            $mqtt->subscribe($sensorTopic, function ($topic, $message) {
                $this->info("Ada data masuk: " . $message);

                $data = json_decode($message, true);

                if (!is_array($data)) {
                    $this->warn("Payload bukan format JSON yang valid, dilewati: " . $message);
                    return;
                }

                $suhu        = $data['suhu_tanah'] ?? $data['suhu'] ?? null;
                $ph          = $data['ph_tanah'] ?? $data['ph'] ?? null;
                $kelembaban  = $data['kelembaban'] ?? 0;
                $statusHujan = (bool) ($data['status_hujan'] ?? $data['rain'] ?? false);
                $statusPompa = (bool) ($data['status_pompa'] ?? false);

                // Jika hujan terdeteksi, pompa otomatis dimatikan (safety lock)
                if ($statusHujan) {
                    $statusPompa = false;
                    $this->warn("Hujan terdeteksi! Pompa dimatikan secara otomatis.");
                }

                if ($suhu !== null && $ph !== null) {
                    SensorData::create([
                        'suhu_tanah'   => $suhu,
                        'ph_tanah'     => $ph,
                        'kelembaban'   => $kelembaban,
                        'status_hujan' => $statusHujan,
                        'status_pompa' => $statusPompa,
                    ]);
                    $this->info("Data berhasil disimpan ke database!");
                } else {
                    $this->warn("Data sensor tidak lengkap (suhu/ph null), dilewati.");
                }
            }, 0);

            $mqtt->loop(true);
        } catch (\Exception $e) {
            $this->error("Gagal terhubung: " . $e->getMessage());
        }
    }
}
