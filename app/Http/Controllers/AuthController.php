<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth-signin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user'    => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('tbl_user')->where('email', $request->user)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Simpan user ke session manual
            Session::put('user', $user);
            Session::put('role', $user->role);

            // Update last_login_at
            DB::table('tbl_user')->where('id', $user->id)->update([
                'last_login_at' => now()
            ]);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'monitoring') {
                return redirect()->route('monitoring.ovpn');
            } else {
                return redirect('/')->with('error', 'Role tidak dikenali.');
            }
        }

        return back()->withErrors(['user' => 'Email atau password salah']);
    }

    public function logout(Request $request)
    {
        $user = Session::get('user');
        if ($user) {
            DB::table('tbl_user')->where('id', $user->id)->update([
                'last_logout_at' => now()
            ]);
        }

        Session::flush();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
