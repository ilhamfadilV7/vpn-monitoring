<?php

namespace App\Services;

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
}
