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
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = 'cota_backend_' . uniqid();

        $this->info("Menghubungkan ke MQTT Broker: {$server}...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = (new ConnectionSettings)->setKeepAliveInterval(60);

            $mqtt->connect($connectionSettings, true);
            $this->info("Berhasil terhubung! Menunggu data dari topik: cota/sensor/data");

            // Topik ini harus sama persis dengan yang di-publish oleh Arduino nanti
            $mqtt->subscribe('cota/sensor/data', function ($topic, $message) {
                $this->info("Ada data masuk: " . $message);

                $data = json_decode($message, true);

                if (isset($data['suhu_tanah']) && isset($data['ph_tanah'])) {
                    SensorData::create([
                        'suhu_tanah' => $data['suhu_tanah'],
                        'ph_tanah' => $data['ph_tanah'],
                        'kelembaban' => $data['kelembaban'] ?? 0,
                        'status_pompa' => $data['status_pompa'] ?? false,
                    ]);
                    $this->info("Data berhasil disimpan ke HeidiSQL!");
                }
            }, 0);

            $mqtt->loop(true);
        } catch (\Exception $e) {
            $this->error("Gagal terhubung: " . $e->getMessage());
        }
    }
}
