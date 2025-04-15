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
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;




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
        $allUsers = $mikrotik->getAllVpnAccountsWithStatus();
        // $data = $mikrotik->getAllVpnAccountsWithStatus();


        // // Filter hanya OVPN
        // // $filtered = collect($data)->where('service', 'sstp')->values();
        $filtered = collect($allUsers)->values();
        $connectedCount = $filtered->where('status', 'Connected')->count();


        $filtered = $filtered->map(function ($item) {
            $item['uptime_formatted'] = $this->formatUptime($item['uptime']);
            return $item;
        });



        return view('layouts.ovpn', [
            'active_vpn' => $filtered,
            'connected_count' => $connectedCount
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

    // public function addVpnUser(Request $request, MikrotikService $mikrotikService)
    // {

    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //         'profile'  => 'required',
    //         'service'  => 'required',
    //         'local_address' => 'nullable|ip',
    //         'remote_address' => 'nullable|ip',
    //         'bandwidth_limit' => 'required',
    //     ]);

    //     Log::info('Request masuk:', $request->all());

    //     try {
    //         $result = $mikrotikService->addVpnUser(
    //             $request->username,
    //             $request->password,
    //             $request->profile,
    //             $request->service,
    //             $request->local_address,
    //             $request->remote_address,
    //             $request->bandwidth_limit
    //         );

    //         if (isset($result['error'])) {
    //             return redirect()->back()->with('error', $result['error']);
    //         }

    //         return redirect()->back()->with('success', 'Akun VPN berhasil ditambahkan.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    public function showVpnForm(MikrotikService $mikrotikService)
    {
        $services = $mikrotikService->getPppServices();
        return view('vpn.form', compact('services'));
    }



    public function vpnUsers(MikrotikService $mikrotikService)
    {
        $vpnUsers = collect($mikrotikService->getAllVpnUsers());
        $activeUsernames = collect($mikrotikService->getActiveVpnUsers());
        $queueList = collect($mikrotikService->getQueueList());

        $totalVpn = $vpnUsers->count();
        $vpnAktif = $activeUsernames->count();

        $vpnTidakAktif = $totalVpn - $vpnAktif;

        // Gabungkan status & bandwidth
        $vpnUsers = $vpnUsers->map(function ($user) use ($activeUsernames, $queueList) {
            $userName = $user['name'] ?? null;

            // Status: Connected / Disconnected
            $user['status'] = $activeUsernames->contains($userName) ? 'Connected' : 'Disconnected';

            // Bandwidth dari Simple Queue
            $matchingQueue = $queueList->first(fn($q) => $q['name'] === $userName);
            $rawLimit = $matchingQueue['max-limit'] ?? null;
            $user['bandwidth'] = $this->formatBandwidth($rawLimit);

            return $user;
        });

        return view('layouts.dashboard-admin', compact(
            'vpnUsers',
            'totalVpn',
            'vpnAktif',
            'vpnTidakAktif'
        ));
    }


    private function formatBandwidth($limit)
    {
        if (!$limit) return '-';

        // Format: "128000/128000"
        $parts = explode('/', $limit);
        if (count($parts) !== 2) return '-';

        // Ambil upload/download — kita ambil salah satu (biasanya sama)
        $bps = (int) $parts[0];

        // Konversi ke Kbps
        $kbps = $bps / 1000;

        // Buat friendly text
        if ($kbps <= 64) return '64 K';
        if ($kbps <= 128) return '128 K';
        if ($kbps <= 256) return '256 K';
        if ($kbps <= 512) return '512 K';
        if ($kbps <= 1024) return '1 M';
        if ($kbps <= 2048) return '2 M';

        return round($kbps / 1024, 1) . ' M';
    }

    // public function autoGenerateVpn(Request $request, MikrotikService $mikrotikService)
    // {
    //     $request->validate([
    //         'jumlah' => 'required|integer|min:1',
    //         'bandwidth_limit_auto' => 'required|string',
    //         'service' => 'required|string'
    //     ]);

    //     $jumlah = $request->jumlah;
    //     $bandwidth = $request->bandwidth_limit_auto;
    //     $service = $request->service;
    //     $profile = 'default-encryption';
    //     $prefix = 'user'; // Bisa dijadikan dinamis nanti

    //     $ipPool = $mikrotikService->getAvailableIpPool(); // Ambil IP pool dari Mikrotik
    //     $existingUsers = $mikrotikService->getAllVpnUsers();
    //     $existingUsernames = collect($existingUsers)->pluck('name')->toArray();

    //     $created = 0;
    //     $errors = [];

    //     for ($i = 1; $i <= $jumlah; $i++) {
    //         $username = $prefix . $i;
    //         $password = 'pw' . $username;

    //         // Cek jika username sudah ada
    //         if (in_array($username, $existingUsernames)) {
    //             $errors[] = "$username sudah ada, dilewati.";
    //             continue;
    //         }

    //         // Ambil IP dari pool (pastikan unik)
    //         $remoteAddress = $mikrotikService->getNextAvailableIp($ipPool);
    //         if (!$remoteAddress) {
    //             $errors[] = "IP Pool habis atau tidak tersedia.";
    //             break;
    //         }

    //         // Buat user VPN
    //         $result = $mikrotikService->addVpnUser(
    //             $username,
    //             $password,
    //             $profile,
    //             $service,
    //             '10.10.10.1', // local address tetap
    //             $remoteAddress,
    //             $bandwidth
    //         );

    //         if (isset($result['error'])) {
    //             $errors[] = "Gagal buat $username: " . $result['error'];
    //         } else {
    //             $created++;
    //         }
    //     }

    //     if ($created > 0) {
    //         return redirect()->back()->with('success', "$created akun berhasil dibuat.")->with('errors', $errors);
    //     } else {
    //         return redirect()->back()->with('error', 'Tidak ada akun berhasil dibuat.')->with('errors', $errors);
    //     }
    // }

    public function store(Request $request, MikrotikService $mikrotikService)
    {
        $mode = $request->input('mode', 'manual');
        Log::info("Request Masuk [Mode: $mode]", $request->all());

        if ($mode === 'manual') {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'profile'  => 'required',
                'service'  => 'required',
                'local_address' => 'nullable|ip',
                'remote_address' => 'nullable|ip',
                'bandwidth_limit' => 'required',
            ]);

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

        // ================================
        // AUTO MODE
        // ================================
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'bandwidth_limit_auto' => 'required|string',
            'service' => 'required|string',
        ]);

        $jumlah = $request->jumlah;
        $bandwidth = $request->bandwidth_limit_auto;
        $service = $request->service;
        $profile = 'default-encryption';
        $prefix = $request->username_prefix ?? 'user';

        $ipPool = $mikrotikService->getAvailableIpPool();
        $existingUsers = $mikrotikService->getAllVpnUsers();
        $existingUsernames = collect($existingUsers)->pluck('name')->toArray();

        $created = 0;
        $errors = [];

        for ($i = 1; $i <= $jumlah; $i++) {
            $username = $prefix . $i;
            $password = 'pw' . $username;

            if (in_array($username, $existingUsernames)) {
                $errors[] = "$username sudah ada, dilewati.";
                continue;
            }

            Log::info("Mencoba ambil IP dari pool: ", ['ipPool' => $ipPool]);
            $remoteAddress = $mikrotikService->getNextAvailableIp($ipPool);
            if (!$remoteAddress) {
                $errors[] = "IP Pool habis atau tidak tersedia.";
                break;
            }

            Log::info("Memanggil addVpnUser untuk: $username");
            $result = $mikrotikService->addVpnUser(
                $username,
                $password,
                $profile,
                $service,
                '10.60.60.1',
                $remoteAddress,
                $bandwidth
            );

            if (isset($result['error'])) {
                Log::error("Gagal buat user $username: ", ['error' => $result['error']]);
                $errors[] = "Gagal buat $username: " . $result['error'];
            } else {
                $created++;
            }
        }

        if ($created > 0) {
            return redirect()->back()->with('success', "$created akun berhasil dibuat.")->with('errors', $errors);
        } else {
            return redirect()->back()->with('error', 'Tidak ada akun berhasil dibuat.')->with('errors', $errors);
        }
    }


    public function getLiveStats(MikrotikService $mikrotikService)
    {
        $activeUsers = $mikrotikService->getActiveVpnUsers();

        $stats = collect($activeUsers)->map(function ($user) {
            return [
                'name' => $user['name'],
                'bytes_in' => (int) $user['bytes-in'],
                'bytes_out' => (int) $user['bytes-out'],
            ];
        });



        return response()->json($stats);
    }

    public function getLiveUserStats($username, MikrotikService $mikrotikService)
    {
        $activeUsers = $mikrotikService->getActiveVpnUsers();
        $user = collect($activeUsers)->firstWhere('name', $username);

        if (!$user) {
            return response()->json(['bytes_in' => 0, 'bytes_out' => 0]);
        }


        return response()->json([
            'bytes_in' => (int) $user['bytes-in'],
            'bytes_out' => (int) $user['bytes-out'],
        ]);
    }

    public function getUserTraffic(Request $request, MikrotikService $mikrotikService)
    {
        $username = $request->query('username');
        $data = $mikrotikService->getTrafficForUser($username);

        dd([
            'username' => $username,
            'data' => $data
        ]);


        return response()->json($data);
    }


    public function akun(Request $request)
    {

        return view('layouts.akun');
    }


    public function getAll()
    {
        $users = User::all();
        return view('layouts.akun', compact('users'));
    }


    function createAkun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:tbl_user,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,monitoring',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal membuat akun.')
                ->withErrors($validator)
                ->withInput();
        }

        $created = DB::table('tbl_user')->insert([
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'password'   => Hash::make($request->input('password')),
            'role'       => $request->input('role'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($created > 0) {
            return redirect()->back()->with('success', "$created akun berhasil dibuat.");
        } else {
            return redirect()->back()->with('error', 'Tidak ada akun berhasil dibuat.');
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,monitoring',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }
}
