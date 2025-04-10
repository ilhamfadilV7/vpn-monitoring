<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

Route::get('/', function () {
    // return auth()->check() ? redirect('/ovpn') : redirect('/login');
    if (Session::has('user_data')) {
        return redirect()->route('ovpn'); // atau route dashboard kamu
    }
    return redirect()->route('login');
});

Route::get('/login', [Controller::class, 'loginpage'])->name('login');
Route::post('/login', [Controller::class, 'login']);
Route::post('/logout', [Controller::class, 'logout'])->name('logout');



Route::middleware(['sessionauth'])->group(function () {
    Route::get('/pptp', [Controller::class, 'dashpptp'])->name('pptp');
    Route::get('/ovpn', [Controller::class, 'ovpn'])->name('ovpn');
});


Route::get('/keep-alive', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/mikrotik/get', [Controller::class, 'getdata']);
Route::post('/toggle-theme', function () {
    $current = session('theme', 'light');
    session(['theme' => $current === 'light' ? 'dark' : 'light']);
    return redirect()->back();
})->name('toggle.theme');
