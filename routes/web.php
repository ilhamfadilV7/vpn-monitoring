<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes(['verify' => true]);

// Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');


// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

// Route::get('/', function () {
//     // return auth()->check() ? redirect('/ovpn') : redirect('/login');
//     if (Session::has('user_data')) {
//         return redirect()->route('ovpn'); // atau route dashboard kamu
//     }
//     return redirect()->route('login');
// });

Route::get('/', function () {
    if (Session::has('user')) {
        $role = Session::get('role');

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'monitoring') {
            return redirect()->route('monitoring.dashboard');
        }

        // Jika role tidak dikenali
        Session::flush();
        return redirect()->route('login')->withErrors(['email' => 'Role tidak dikenali.']);
    }

    return redirect()->route('login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::middleware('role:admin')->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('layouts.dashboard-admin');
//     })->name('admin.dashboard');
//     Route::get('/admin/vpn-users', [Controller::class, 'vpnUsers'])->name('admin.vpn-users');
// });
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [Controller::class, 'vpnUsers'])->name('admin.dashboard');
    Route::get('/admin/akun', [Controller::class, 'getAll'])->name('admin.akun');
    Route::post('/users', [Controller::class, 'createAkun'])->name('users.store');
    Route::delete('/user/{id}', [Controller::class, 'destroy'])->name('user.destroy');
    Route::put('/admin/akun/{id}', [Controller::class, 'update'])->name('admin.akun.update');
});




Route::middleware(['monitoring'])->prefix('monitoring')->group(function () {
    Route::get('/ovpn', [Controller::class, 'ovpn'])->name('monitoring.ovpn');
    // Route::get('/pptp', [Controller::class, 'dashpptp'])->name('monitoring.pptp');
    Route::get('/dashboard', function () {
        return redirect()->route('monitoring.ovpn'); // Atau halaman utama monitoring
    })->name('monitoring.dashboard');
    Route::get('/vpn/stats', [Controller::class, 'getLiveStats'])->name('vpn.stats');
    Route::get('/vpn/stats/{username}', [Controller::class, 'getLiveUserStats']);
    Route::get('/api/vpn-traffic', [Controller::class, 'getUserTraffic']);
    Route::get('/monitoring/search-vpn', [Controller::class, 'searchVpn'])->name('vpn.search');
});

// Route::post('/vpn-account/store', [Controller::class, 'addVpnUser'])->name('vpn.store');
// Route::post('/vpn/auto-generate', [Controller::class, 'autoGenerateVpn'])->name('vpn.autoGenerate');
Route::post('/vpn/store', [Controller::class, 'store'])->name('vpn.store');





// Route::middleware(['sessionauth'])->group(function () {
//     Route::get('/pptp', [Controller::class, 'dashpptp'])->name('pptp');
//     Route::get('/ovpn', [Controller::class, 'ovpn'])->name('ovpn');
// });


Route::get('/keep-alive', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/mikrotik/get', [Controller::class, 'getdata']);
Route::post('/toggle-theme', function () {
    $current = session('theme', 'light');
    session(['theme' => $current === 'light' ? 'dark' : 'light']);
    return redirect()->back();
})->name('toggle.theme');
