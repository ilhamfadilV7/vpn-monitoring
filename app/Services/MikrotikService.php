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

    public function getAllVpnAccountsWithStatus()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if ($this->API->connect($host, $user, $pass)) {
            // Get all registered VPN accounts
            $this->API->write('/ppp/secret/print');
            $secrets = $this->API->parseResponse($this->API->read(false));

            // Get active connections
            $this->API->write('/ppp/active/print');
            $actives = $this->API->parseResponse($this->API->read(false));

            $this->API->disconnect();

            $activesByName = collect($actives)->keyBy('name');

            $accounts = collect($secrets)->map(function ($user) use ($activesByName) {
                $active = $activesByName[$user['name']] ?? null;

                return [
                    'name'     => $user['name'] ?? '',
                    'service'  => $user['service'] ?? '',
                    'profile'  => $user['profile'] ?? '',
                    'disabled' => $user['disabled'] ?? 'false',
                    'status'   => $active ? 'Connected' : 'Disconnected',
                    'uptime'   => $active['uptime'] ?? null,
                    'address'  => $active['address'] ?? null,
                ];
            });

            return $accounts;
        }

        return collect([
            ['error' => 'Gagal konek ke MikroTik.']
        ]);
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
            Log::error("Cek koneksi atau kredensial: ", compact('host', 'user'));
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

    public function getActiveVpnUsers()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if ($this->API->connect($host, $user, $pass)) {
            $activeUsers = $this->API->comm('/ppp/active/print');
            $this->API->disconnect();

            return collect($activeUsers)->pluck('name')->toArray(); // hanya ambil username
        }

        return [];
    }


    public function getQueueList()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if ($this->API->connect($host, $user, $pass)) {
            $queues = $this->API->comm('/queue/simple/print');
            $this->API->disconnect();
            return $queues;
        }

        return [];
    }

    public function getAvailableIpPool()
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if ($this->API->connect($host, $user, $pass)) {
            $pool = $this->API->comm('/ip/pool/print');
            $this->API->disconnect();

            // Kalau Mikrotik nggak balikin pool apa-apa, fallback ke hardcoded
            if (empty($pool)) {
                Log::warning("IP Pool kosong dari Mikrotik, fallback ke hardcoded.");
                return [[
                    'name' => 'hardcoded-pool',
                    'ranges' => '10.60.60.2-10.60.60.255'
                ]];
            }

            Log::info("Isi IP Pool dari Mikrotik:", $pool);
            return $pool;
        }

        Log::error("Gagal konek untuk ambil IP pool");
        return [[
            'name' => 'hardcoded-fallback',
            'ranges' => '10.60.60.2-10.60.60.255'
        ]];
    }


    public function getNextAvailableIp($poolList)
    {
        if (empty($poolList)) return null;

        $usedIps = collect($this->getAllVpnUsers())
            ->pluck('remote-address')
            ->filter()
            ->toArray();

        foreach ($poolList as $pool) {
            if (!isset($pool['ranges'])) continue;

            $ranges = explode(',', $pool['ranges']);

            foreach ($ranges as $range) {
                $range = trim($range);

                if (str_contains($range, '-')) {
                    [$startIp, $endIp] = explode('-', $range);
                } else {
                    // Jika hanya satu IP (misalnya "10.10.10.5")
                    $startIp = $endIp = $range;
                }

                $start = ip2long($startIp);
                $end = ip2long($endIp);

                for ($ip = $start; $ip <= $end; $ip++) {
                    $candidate = long2ip($ip);
                    if (!in_array($candidate, $usedIps)) {
                        return $candidate;
                    }
                }
            }
        }

        return null; // Semua IP sudah dipakai
    }

    public function getTrafficForUser($username)
    {
        $host = env('MIKROTIK_HOST');
        $user = env('MIKROTIK_USER');
        $pass = env('MIKROTIK_PASS');

        if (!$this->API->connect($host, $user, $pass)) {
            Log::error("Gagal koneksi ke MikroTik untuk traffic user: $username");
            return [
                'username' => $username,
                'bytes_in' => 0,
                'bytes_out' => 0,
            ];
        }

        $activeUsers = $this->API->comm('/ppp/active/print', [
            "?name" => $username
        ]);

        $this->API->disconnect();

        if (empty($activeUsers)) {
            return [
                'username' => $username,
                'bytes_in' => 0,
                'bytes_out' => 0,
            ];
        }

        $userData = $activeUsers[0];

        return [
            'username' => $username,
            'bytes_in' => (int) ($userData['bytes-in'] ?? 0),
            'bytes_out' => (int) ($userData['bytes-out'] ?? 0),
        ];
    }
}
