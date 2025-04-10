<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function loginpage()
    {
        return view('auth-signin');
    }




    public function login(Request $request)
    {
        $data = [
            "user" => $request->user,
            "password" => $request->password
        ];

        $response = Http::withBasicAuth($data['user'], $data['password'])->withOptions(['verify' => false,])->get('https://10.20.10.244/rest/system/resource');

        if ($response->successful()) {
            //berhasil login
            session()->put('user_data', $data);
            return view('auth-success-msg');
            return redirect()->route('ovpn');
        } else {
            return view('auth-error');
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus session user

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman login
        return redirect('/login');
    }


    public function ovpn(MikrotikService $mikrotik)
    {
        $data = $mikrotik->getActiveOvpnConnections();

        // Filter hanya OVPN
        $filtered = collect($data)->where('service', 'ovpn')->values();


        $filtered = $filtered->map(function ($item) {
            $item['uptime_formatted'] = $this->formatUptime($item['uptime']);
            return $item;
        });

        return view('layouts.ovpn', [
            'active_vpn' => $filtered
        ]);
    }

    private function formatUptime($uptime)
    {
        // Match angka untuk d, h, m, s
        preg_match('/(?:(\d+)d)?(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?/', $uptime, $matches);

        // Inisialisasi hasil
        $result = [];

        // Cek dan tambahkan hari
        if (!empty($matches[1])) {
            $result[] = (int)$matches[1] . ' hari';
        }

        // Cek dan tambahkan jam
        if (!empty($matches[2])) {
            $result[] = (int)$matches[2] . ' jam';
        }

        // Cek dan tambahkan menit
        if (!empty($matches[3])) {
            $result[] = (int)$matches[3] . ' menit';
        }

        // Optional: Tambahkan detik jika mau
        // if (!empty($matches[4])) {
        //     $result[] = (int)$matches[4] . ' detik';
        // }

        // Kalau semua kosong, fallback ke "0 menit"
        if (empty($result)) {
            $result[] = '0 menit';
        }

        return implode(' ', $result);
    }



    public function dashpptp(MikrotikService $mikrotik)
    {
        $data = $mikrotik->getActiveOvpnConnections();



        // Filter hanya OVPN
        $filtered = collect($data)->where('service', 'pptp')->values();

        // dd($filtered);

        $filtered = $filtered->map(function ($item) {
            $item['uptime_formatted'] = $this->formatUptime($item['uptime']);
            return $item;
        });


        return view('layouts.pptp', [
            'active_pptp' => $filtered
        ]);
    }
}
