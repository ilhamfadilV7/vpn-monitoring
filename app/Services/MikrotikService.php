<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

require_once app_path('Libraries/routeros-api/routeros_api.class.php');

class MikrotikService
{
    protected $API;

    public function __construct()
    {
        $this->API = new \RouterosAPI();
    }

    public function getActiveOvpnConnections()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');
        if ($this->API->connect($host, $user, $pass)) {
            $this->API->write('/ppp/active/print');

            $response = $this->API->read(false); // ⬅️ penting!
            $parsed = $this->API->parseResponse($response);

            $this->API->disconnect();
            return $parsed;
        } else {
            return ['error' => 'Gagal konek ke MikroTik.'];
        }
    }


    public function addVpnUser($username, $password, $profile = 'default-encryption', $service = 'any', $localAddress = null, $remoteAddress = null, $rateLimit = null)
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        Log::info("Mencoba koneksi ke MikroTik...");

        if ($this->API->connect($host, $user, $pass)) {
            Log::info("Berhasil konek ke MikroTik");

            // Step 1: Buat user PPP
            $params = [
                "name"     => $username,
                "password" => $password,
                "profile"  => $profile,
                "service"  => $service,
            ];

            if ($localAddress) {
                $params['local-address'] = $localAddress;
            }

            if ($remoteAddress) {
                $params['remote-address'] = $remoteAddress;
            }

            Log::info("Parameter user baru:", $params);

            $addResult = $this->API->comm('/ppp/secret/add', $params);
            Log::info("Hasil tambah user:", ['result' => $addResult]);

            // Step 2: Buat Simple Queue kalau ada rateLimit dan remoteAddress
            if ($rateLimit && $remoteAddress) {
                Log::info("Menambahkan queue untuk user $username", [
                    'target' => $remoteAddress,
                    'max-limit' => $rateLimit
                ]);

                $queueResult = $this->API->comm('/queue/simple/add', [
                    'name'      => $username,
                    'target'    => $remoteAddress,
                    'max-limit' => $rateLimit,
                ]);

                Log::info("Hasil pembuatan queue:", ['queue' => $queueResult]);
            } else {
                Log::warning("Queue tidak dibuat. rateLimit atau remoteAddress kosong", [
                    'rateLimit' => $rateLimit,
                    'remoteAddress' => $remoteAddress
                ]);
            }

            $this->API->disconnect();
            Log::info("Koneksi MikroTik diputuskan.");

            return $addResult;
        } else {
            Log::error("Gagal konek ke MikroTik");
            return ['error' => 'Gagal konek ke MikroTik.'];
        }
    }




    public function getPppServices()
    {
        // Daftar service VPN yang valid di MikroTik
        return [
            'any',
            'pptp',
            'l2tp',
            'ovpn',
            'sstp',
        ];
    }

    public function getAllVpnUsers()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if ($this->API->connect($host, $user, $pass)) {
            $users = $this->API->comm('/ppp/secret/print');
            $this->API->disconnect();
            return $users;
        }

        return [];
    }
}
