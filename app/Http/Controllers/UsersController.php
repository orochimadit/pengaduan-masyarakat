<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Daftar Pengguna'; 
        $users = User::with('kecamatan')->paginate(10); // Menampilkan data users dengan kecamatan
        $deletedUsers = User::onlyTrashed()->with('kecamatan')->paginate(10);
        return view('admas.users.index', compact('users','title','deletedUsers'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kecamatans = Kecamatan::all(); // Mengambil semua kecamatan
        $title = 'Buat Akun Baru';
        return view('admas.users.create', compact('kecamatans','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Validasi data input
         $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:20|unique:users',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telp' => 'required|string|max:20',
            'lvl' => 'required|in:masyarakat,petugas,admin',
            'aktif' => 'required|in:1,0',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Membuat akun baru
        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telp' => $request->telp,
            'lvl' => $request->lvl,
            'aktif' => $request->aktif,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    }

    public function show_admin(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('aktif', $request->aktif);
        Session::flash('jabatan', $request->lvl);


        $user = User::withTrashed()->where('lvl', '=', 'admin')->paginate(10);

        if (isset($request->search)) {
            $user = User::withTrashed()->where('lvl', '=', 'admin')->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->paginate(1000);
        }

        if (isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'admin')->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'admin')->paginate(1000);
            }
        }

        if (isset($request->search) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'admin')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%')
                        ->orWhere('lvl', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'admin')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }
        }

        return view('admas/rekapitulasi')->with([
            'jml' => User::all(),
            'title' => 'Rekapitulasi Akun Admin',
            'admin' => $user,
            'search' => $request->search,
            'aktif' => $request->aktif,
            'lvl' => 'admin',
        ]);
    }

    public function show_petugas(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('aktif', $request->aktif);
        Session::flash('jabatan', $request->lvl);


        $user = User::withTrashed()->where('lvl', '=', 'petugas')->paginate(10);

        if (isset($request->search)) {
            $user = User::withTrashed()->where('lvl', '=', 'petugas')->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->paginate(1000);
        }

        if (isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'petugas')->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'petugas')->paginate(1000);
            }
        }

        if (isset($request->search) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'petugas')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%')
                        ->orWhere('lvl', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'petugas')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }
        }

        return view('admas/rekapitulasi')->with([
            'jml' => User::all(),
            'title' => 'Rekapitulasi Akun Petugas',
            'admin' => $user,
            'search' => $request->search,
            'aktif' => $request->aktif,
            'lvl' => 'petugas',
        ]);
    }

    public function show_masyarakat(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('aktif', $request->aktif);
        Session::flash('jabatan', $request->lvl);


        $user = User::withTrashed()->where('lvl', '=', 'masyarakat')->paginate(10);

        if (isset($request->search)) {
            $user = User::withTrashed()->where('lvl', '=', 'masyarakat')->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->paginate(1000);
        }

        if (isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'masyarakat')->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'masyarakat')->paginate(1000);
            }
        }

        if (isset($request->search) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $user = User::onlyTrashed()->where('lvl', '=', 'masyarakat')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%')
                        ->orWhere('lvl', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }

            if ($request->aktif == '1') {
                $user = User::where('lvl', '=', 'masyarakat')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }
        }

        return view('admas/rekapitulasi')->with([
            'jml' => User::all(),
            'title' => 'Rekapitulasi Akun Masyarakat',
            'admin' => $user,
            'search' => $request->search,
            'aktif' => $request->aktif,
            'lvl' => 'masyarakat',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $title = 'Edit Akun';
    $kecamatans = Kecamatan::all();

    return view('admas.users.edit', compact('title', 'user', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:20|unique:users,nik,' . $user->id,
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telp' => 'required|string|max:20',
            'lvl' => 'required|in:masyarakat,petugas,admin',
            'aktif' => 'required|in:1,0',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mengupdate data user
        $user->update([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'telp' => $request->telp,
            'lvl' => $request->lvl,
            'aktif' => $request->aktif,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dipulihkan');
    }

}
