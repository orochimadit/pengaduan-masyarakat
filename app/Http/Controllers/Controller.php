<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Berhasil Logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_username' => 'required',
            'login_password' => 'required',
        ], [
            'login_username.required' => 'Username wajib diisi',
            'login_password.required' => 'Password wajib diisi',
        ]);

        $data = [
            'username' => $request->login_username,
            'password' => $request->login_password,
        ];

        if (Auth::attempt($data)) {
            if (Auth::user()->aktif == '0') {
                Auth::logout();
                return redirect('/')->withErrors('Akun tidak dapat digunakan!');
            }
            // return "Berhasil";
            return redirect('/')->with('success', 'Berhasil Login');
        } else {
            // return ' gagal';
            return redirect('/')->withErrors('Login Gagal');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'register_nik' => 'required|max:16|unique:users,nik',
            'register_name' => 'required',
            'register_username' => 'required',
            'register_email' => 'required|email|unique:users,email',
            'register_password' => 'required|min:8|confirmed',
            'register_telp' => 'required|max:13',
        ], [
            'register_nik.unique' => 'NIK Sudah Pernah Digunakan',
            'register_nik.required' => 'NIK Wajib Diisi',
            'register_name.required' => 'Nama Wajib Diisi',
            'register_username.required' => 'Username Wajib Diisi',
            'register_email.required' => 'Email Wajib Diisi',
            'register_password.required' => 'Password Wajib Diisi',
            'register_password.min' => 'Password minimal memiliki 8 karakter',
            'register_password.confirmed' => 'Konfirmasi Password Tidak Sesuai',
            'register_telp.required' => 'No. Telepon Wajib Diisi',
        ]);

        if (empty($request->lvl)) {
            $jabatan = 'masyarakat';
        } else {
            $jabatan = $request->lvl;
        }

        $data = [
            'nik' => $request->register_nik,
            'name' => $request->register_name,
            'username' => $request->register_username,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
            'telp' => $request->register_telp,
            'lvl' => $jabatan,
        ];
        User::create($data);

        $login = [
            'nik' => $request->register_nik,
            'name' => $request->register_name,
            'username' => $request->register_username,
            'email' => $request->register_email,
            'password' => $request->register_password,
            'telp' => $request->register_telp,
            'lvl' => 'masyarakat',
        ];
        Auth::attempt($login);
        return redirect('/')->with('success', 'Berhasil Register');
    }
}
