<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = [
                'username' => $request->input('username'), // Sesuaikan dengan nama kolom di database
                'password' => $request->input('password'),
            ];
            
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil', 
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {
        $level = LevelModel::select('level_id', 'level_nama', 'level_kode')->get();
        return view('auth.register', ['level' => $level]);
    }

    public function addNewAccount(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'password' => 'required|min:5',
            'nama' => 'required|string|max:100',
            'level_id' => 'required|integer'
        ]);
        UserModel::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'level_id' => $request->level_id
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil! Silakan login.',
            'redirect' => url('/login')
        ]);
        
    }
}
