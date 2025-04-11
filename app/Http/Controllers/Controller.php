<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }


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

        // Hitung jumlah koneksi PPTP aktif
        $pptpCount = $filtered->count();

        $filtered = $filtered->map(function ($item) {
            $item['uptime_formatted'] = $this->formatUptime($item['uptime']);
            return $item;
        });


        return view('layouts.pptp', [
            'active_pptp' => $filtered,
            'pptp_count' => $pptpCount
        ]);
    }

    public function addVpnUser(Request $request, MikrotikService $mikrotikService)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'profile'  => 'required',
            'service'  => 'required',
            'local_address' => 'nullable|ip',
            'remote_address' => 'nullable|ip',
            'bandwidth_limit' => 'required',
        ]);

        Log::info('Request masuk:', $request->all());

        try {
            $result = $mikrotikService->addVpnUser(
                $request->username,
                $request->password,
                $request->profile,
                $request->service,
                $request->local_address,
                $request->remote_address,
                $request->bandwidth_limit
            );

            if (isset($result['error'])) {
                return redirect()->back()->with('error', $result['error']);
            }

            return redirect()->back()->with('success', 'Akun VPN berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showVpnForm(MikrotikService $mikrotikService)
    {
        $services = $mikrotikService->getPppServices();
        return view('vpn.form', compact('services'));
    }

    public function vpnUsers(MikrotikService $mikrotikService)
    {
        $vpnUsers = $mikrotikService->getAllVpnUsers(); // fungsi ambil semua user ppp secret


        return view('layouts.dashboard-admin', compact('vpnUsers'));
    }
}
