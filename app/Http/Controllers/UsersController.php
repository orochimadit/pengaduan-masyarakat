<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admas/akun')->with([
            'title' => 'Akun',
            'akun' => Auth::user(),
            'total_pengaduan' => Pengaduan::where('masyarakat_nik', '=', Auth::user()->nik)->get()->count(),
            'jml' => Pengaduan::where('masyarakat_nik', '=', Auth::user()->nik)->get(),
        ]);
    }

    public function detail($nik, Request $request)
    {
        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('search', $request->search);

        $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)->get();

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if (isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)->where('status', '=', $request->status)->get();
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->get();
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if (isset($request->search) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where('status', '=', $request->status)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where('status', '=', $request->status)
                ->get();
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where('status', '=', $request->status)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        return view('admas/akun')->with([
            'title' => 'Detail Pengguna',
            'akun' => User::withTrashed()->where('nik', '=', $nik)->first(),
            'jml' => Pengaduan::where('masyarakat_nik', '=', $nik)->get(),
            'pengaduan' => $pengaduan,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'status' => $request->status,
            'search' => $request->search,
        ]);
    }

    public function export_users(Request $request)
    {
        $akun = User::with('pengaduan')->get();

        if (isset($request->search)) {
            $akun = User::with('pengaduan')->withTrashed()->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->get();
        }

        if (isset($request->lvl)) {
            User::with('pengaduan')->withTrashed()->where('lvl', '=', $request->lvl)->get();
        }

        if (isset($request->aktif)) {
            if ($request->aktif == '0') {
                $akun = User::with('pengaduan')->onlyTrashed()->get();
            }

            if ($request->aktif == '1') {
                $akun = User::with('pengaduan')->get();
            }
        }

        if (isset($request->search) && isset($request->lvl)) {
            $akun = User::with('pengaduan')->withTrashed()->where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->get();
        }

        if (isset($request->search) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $akun = User::with('pengaduan')->onlyTrashed()->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->get();
            }

            if ($request->aktif == '1') {
                $akun = User::with('pengaduan')->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->get();
            }
        }

        if (isset($request->lvl) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $akun = User::with('pengaduan')->onlyTrashed()->where('lvl', '=', $request->lvl)->get();
            }

            if ($request->aktif == '1') {
                $akun = User::with('pengaduan')->where('lvl', '=', $request->lvl)->get();
            }
        }

        if (isset($request->search) && isset($request->lvl) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $akun = User::with('pengaduan')->onlyTrashed()->where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->get();
            }

            if ($request->aktif == '1') {
                $akun = User::with('pengaduan')->where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->get();
            }
        }

        // return $akun;

        return view('/admas/export-excel-users')->with([
            'akun' => $akun,
            'lvl' => $request->lvl,
        ]);
    }

    public function export($nik, Request $request)
    {
        $akun = User::where('nik', '=', $nik)->first();

        $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)->get();

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if (isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)->where('status', '=', $request->status)->get();
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->get();
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if (isset($request->search) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where('status', '=', $request->status)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where('status', '=', $request->status)
                ->get();
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::where('masyarakat_nik', '=', $nik)
                ->where('status', '=', $request->status)
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })
                ->get();
        }

        return view('/admas/export-excel-pengguna')->with([
            'akun' => $akun,
            'pengaduan' => $pengaduan,
            'search' => $request->search,
        ]);
    }

    public function active($id)
    {
        // return $id;
        $akun = User::withTrashed()->where('id', '=', $id)->first();
        // return $akun;

        if ($akun->trashed()) {
            User::withTrashed()
                ->where('id', $id)
                ->restore();
            return redirect('/admin/user')->with('success', 'Berhasil Aktifkan Akun');
        } else {
            User::destroy($id);
            return redirect('/admin/user')->with('success', 'Berhasil Non-aktifkan Akun');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('aktif', $request->aktif);
        Session::flash('lvl', $request->lvl);


        $admin = User::withTrashed()->where('lvl', '=', 'admin')->paginate(5);
        $petugas = User::withTrashed()->where('lvl', '=', 'petugas')->paginate(5);
        $masyarakat = User::withTrashed()->where('lvl', '=', 'masyarakat')->paginate(5);

        if (isset($request->search)) {
            $admin = User::withTrashed()->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->paginate(1000);
        }

        if (isset($request->lvl)) {
            $admin = User::withTrashed()->where('lvl', '=', $request->lvl)->paginate(1000);
        }

        if (isset($request->aktif)) {
            if ($request->aktif == '0') {
                $admin = User::onlyTrashed()->paginate(1000);
            }

            if ($request->aktif == '1') {
                $admin = User::paginate(1000);
            }
        }

        if (isset($request->search) && isset($request->lvl)) {
            $admin = User::withTrashed()->where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
                $query->orWhere('nik', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telp', 'like', '%' . $request->search . '%');
            })->paginate(1000);
        }

        if (isset($request->search) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $admin = User::onlyTrashed()->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }

            if ($request->aktif == '1') {
                $admin = User::whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }
        }

        if (isset($request->lvl) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $admin = User::onlyTrashed()->where('lvl', '=', $request->lvl)->paginate(1000);
            }

            if ($request->aktif == '1') {
                $admin = User::where('lvl', '=', $request->lvl)->paginate(1000);
            }
        }

        if (isset($request->search) && isset($request->lvl) && isset($request->aktif)) {
            if ($request->aktif == '0') {
                $admin = User::onlyTrashed()->where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
                    $query->orWhere('nik', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('telp', 'like', '%' . $request->search . '%');
                })->paginate(1000);
            }

            if ($request->aktif == '1') {
                $admin = User::where('lvl', '=', $request->lvl)->whereExists(function ($query) use ($request) {
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
            'title' => 'Rekapitulasi Pengguna',
            'admin' => $admin,
            'petugas' => $petugas,
            'masyarakat' => $masyarakat,
            'search' => $request->search,
            'aktif' => $request->aktif,
            'lvl' => $request->lvl,
        ]);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'telp' => 'required|max:13',
        ], [
            'name.required' => 'Nama Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'telp.required' => 'No. Telepon Wajib Diisi',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
        ];
        User::where('id', '=', Auth::user()->id)->update($data);
        return back()->with('success', 'Berhasil Simpan Akun');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
