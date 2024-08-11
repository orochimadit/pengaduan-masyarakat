<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function kecamatan(Request $request)
    {
        Session::flash('kecamatan', $request->kecamatan);

        $kecamatan = Kecamatan::paginate(10);

        if (isset($request->kecamatan)) {
            $kecamatan = Kecamatan::where('kecamatan', 'LIKE', '%' . $request->kecamatan . '%')->paginate(1000);
        }

        return view('admas/kecamatan')->with([
            'title' => 'Kecamatan',
            'kecamatan' => $kecamatan,
        ]);
    }

    public function kecamatanEdit(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required',
        ], [
            'kecamatan.required' => 'Nama Kecamatan Baru Wajib Diisi',
        ]);

        $data = [
            'kecamatan' => $request->kecamatan,
        ];

        Kecamatan::where('id', '=', $id)->update($data);
        return redirect('/admin/kecamatan')->with('success', 'Berhasil edit kecamatan');
    }

    public function kecamatanCreate(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required',
        ], [
            'kecamatan.required' => 'Nama Kecamatan Wajib Diisi',
        ]);

        $data = [
            'kecamatan' => $request->kecamatan,
        ];

        Kecamatan::create($data);
        return redirect('/admin/kecamatan')->with('success', 'Berhasil tambah kecamatan baru');
    }

    public function kecamatanDelete($id)
    {
        Kecamatan::destroy($id);
        return redirect('/admin/kecamatan')->with('success', 'Berhasil hapus data');
    }

    public function index(Request $request)
    {
        // Menyimpan filter dalam session
        Session::flash('search', $request->search);
        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('status', $request->status);
    
        // Mendapatkan user yang sedang login
        $user = Auth::user();
    
        // Memulai query untuk pengaduan
        $query = Pengaduan::query();
    
        // Jika user adalah kecamatan, batasi query ke kecamatan mereka
        if ($user->lvl === 'petugas') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        }
        // Jika user adalah admin, mereka dapat melihat semua data, jadi tidak ada batasan kecamatan
    
        // Tambahkan filter pencarian jika ada
        if (isset($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->orWhere('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                    ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
            });
        }
    
        // Tambahkan filter tanggal jika ada
        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $query->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir]);
        }
    
        // Tambahkan filter status jika ada
        if (isset($request->status)) {
            $query->where('status', '=', $request->status);
        }
    
        // Urutkan hasil berdasarkan tanggal pengaduan
        $pengaduan = $query->orderBy('tgl_pengaduan', 'desc')->paginate(10);
        
         // Simpan query dasar untuk penghitungan
    $baseQuery = clone $query;

    // Hitung jumlah pengaduan berdasarkan status
    $jmlPengaduan = [
        'total' => $baseQuery->count(),
        'belum_diproses' => $baseQuery->clone()->where('status', '=', '0')->count(),
        'sedang_diproses' => $baseQuery->clone()->where('status', '=', '1')->count(),
        'selesai' => $baseQuery->clone()->where('status', '=', '2')->count(),
    ];
    
        // Mengembalikan view dengan data yang telah difilter
        return view('admas/pengaduan_admin')->with([
            
            'jml' => $jmlPengaduan,
            'title' => 'Seluruh Pengaduan',
            'pengaduan' => $pengaduan,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'search' => $request->search,
            'status' => $request->status,
        ]);
    }
    


    public function export(Request $request)
    {
        $pengaduan = Pengaduan::orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where(function ($query) use ($request) {
                $query->orWhere('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                    ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
            })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->status)) {
            $pengaduan = Pengaduan::where('status', '=', $request->status)->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->where('status', '=', $request->status)->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && isset($request->status)) {
            $pengaduan = Pengaduan::where('status', '=', $request->status)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where('status', '=', $request->status)
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return \view('admas/export-excel')->with([
            'pengaduan' => $pengaduan,
        ]);
    }

    public function export_belum(Request $request)
    {
        $pengaduan = Pengaduan::where('status', '=', '0')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '0')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return \view('admas/export-excel')->with([
            'pengaduan' => $pengaduan,
        ]);
    }

    public function export_sedang(Request $request)
    {
        $pengaduan = Pengaduan::where('status', '=', '1')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '1')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return \view('admas/export-excel')->with([
            'pengaduan' => $pengaduan,
        ]);
    }

    public function export_selesai(Request $request)
    {
        $pengaduan = Pengaduan::where('status', '=', '2')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '2')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return \view('admas/export-excel')->with([
            'pengaduan' => $pengaduan,
        ]);
    }

    public function show_belum(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);

        $pengaduan = Pengaduan::where('status', '=', '0')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '0')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '0')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return view('admas/pengaduan_admin')->with([
            'jml' => Pengaduan::all(),
            'title' => 'Admin Pengaduan Belum Diproses',
            'pengaduan' => $pengaduan,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'search' => $request->search,
        ]);
    }

    public function show_sedang(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);

        $pengaduan = Pengaduan::where('status', '=', '1')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '1')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '1')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return view('admas/pengaduan_admin')->with([
            'jml' => Pengaduan::all(),
            'title' => 'Admin Pengaduan Sedang Diproses',
            'pengaduan' => $pengaduan,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'search' => $request->search,
        ]);
    }

    public function show_telah(Request $request)
    {
        Session::flash('search', $request->search);
        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);

        $pengaduan = Pengaduan::where('status', '=', '2')->orderBy('tgl_pengaduan', 'desc')->paginate(10);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::where('status', '=', '2')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && (isset($request->tgl_awal) && isset($request->tgl_akhir))) {
            $pengaduan = Pengaduan::where('status', '=', '2')
                ->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])
                ->where(function ($query) use ($request) {
                    $query->orWhere('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('isi_laporan', 'like', '%' . $request->search . '%')
                        ->orWhere('masyarakat_nik', 'like', '%' . $request->search . '%');
                })->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        return view('admas/pengaduan_admin')->with([
            'jml' => Pengaduan::all(),
            'title' => 'Admin Pengaduan Selesai',
            'pengaduan' => $pengaduan,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'search' => $request->search,
        ]);
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
        $request->validate([
            'nik' => 'required|max:16|unique:users,nik',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'telp' => 'required|max:13',
            'lvl' => 'required',
        ], [
            'nik.unique' => 'NIK Sudah Pernah Digunakan',
            'nik.required' => 'NIK Wajib Diisi',
            'name.required' => 'Nama Wajib Diisi',
            'username.required' => 'Username Wajib Diisi',
            'username.unique' => 'Username sudah pernah digunakan',
            'email.required' => 'Email Wajib Diisi',
            'email.unique' => 'Email sudah pernah digunakan',
            'password.required' => 'Password Wajib Diisi',
            'password.min' => 'Password minimal memiliki 8 karakter',
            'password.confirmed' => 'Konfirmasi Password Tidak Sesuai',
            'telp.required' => 'No. Telepon Wajib Diisi',
            'lvl.required' => 'Jabatan wajib diisi',
        ]);

        $data = [
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telp' => $request->telp,
            'lvl' => $request->lvl,
        ];
        User::create($data);

        return back()->with('success', 'Berhasil Membuat Akun');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|max:16',
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'telp' => 'required|max:13',
            'lvl' => 'required'
        ], [
            'nik.required' => 'NIK Wajib Diisi',
            'name.required' => 'Nama Wajib Diisi',
            'username.required' => 'Username Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'telp.required' => 'No. Telepon Wajib Diisi',
            'lvl.required' => 'Jabatan Akun Wajib Diisi',
        ]);

        $data = [
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'telp' => $request->telp,
            'lvl' => $request->lvl,
        ];

        User::where('id', '=', $id)->update($data);
        return redirect('/admin/user')->with('success', 'Berhasil Edit Akun');
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
