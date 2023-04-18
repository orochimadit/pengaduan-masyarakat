<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pengaduan = Pengaduan::where('masyarakat_nik', '=', Auth::user()->nik);
        $jml = Pengaduan::get()->where('masyarakat_nik', '=', Auth::user()->nik);

        $terbaru = Pengaduan::where('masyarakat_nik', '=', Auth::user()->nik)->orderBy('created_at', 'desc')->take(5)->get();


        // return $pengaduan;
        return view('admas/pengaduan')->with([
            'title' => 'Pengaduan',
            'pengaduan' => $pengaduan,
            'jml' => $jml,
            'terbaru' => $terbaru,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admas/pengaduan')->with([
        //     'title' => 'Pengaduan',
        // ]);
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
            'tgl_pengaduan' => 'required|date',
            'kecamatan' => 'required',
            'judul_pengaduan' => 'required',
            'isi_laporan' => 'required',
            'foto' => 'mimes:jpg,jpeg,png',
        ], [
            'tgl_pengaduan.required' => 'Tanggal Laporan Wajib ',
            'kecamatan.required' => 'Kecamatan Wajib Diisi',
            'tgl_pengaduan.date' => 'Tanggal Harus Dengan Format "yyyy-mm-dd"',
            'judul_pengaduan.required' => 'Judul Wajib Diisi',
            'isi_laporan.required' => 'Isi Laporan Wajib Diisi',
            'foto.mimes' => 'Type Foto Yang Diizinkan (jpg, jpeg, png)',
        ]);

        $new_name_foto = "";
        if (NULL !== $request->file('foto')) {
            $file_foto = $request->file('foto');
            $ext_foto = $request->foto->extension();
            $save_dir = public_path('img_pengaduan/');
            $new_name_foto =  "TANGSPOR_" . date('Y-m-d') . "_" . date('H-i-s') . "_" . Auth::user()->nik .  "." . $ext_foto;
            $file_foto->move($save_dir, $new_name_foto);
        }

        $data = [
            'tgl_pengaduan' => $request->tgl_pengaduan,
            'masyarakat_nik' => Auth::user()->nik,
            'kecamatan_id' => $request->kecamatan,
            'judul' => $request->judul_pengaduan,
            'isi_laporan' => $request->isi_laporan,
            'foto' => $new_name_foto,
            'status' => '0'
        ];

        Pengaduan::create($data);
        return redirect('/pengaduan')->with('success', 'Berhasil Melapor');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = Pengaduan::with('user')->where('id', '=', $id)->first();
        $tanggapan = Tanggapan::with('user')->get()->where('pengaduan_id', '=', $id);
        // return $tanggapan->all();
        return view('admas/pengaduan_detail')->with([
            'title' => 'Detail Pengaduan',
            'detail' => $detail,
            'tanggapan' => $tanggapan,
        ]);
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required',
        ], [
            'pesan.required' => 'Tidak dapat mengirim pesan kosong',
        ]);

        $data = [
            'pengaduan_id' => $id,
            'petugas_id' => Auth::user()->nik,
            'tanggapan' => $request->pesan,
            'tgl_tanggapan' => date('Y-m-d'),
        ];

        Tanggapan::create($data);
        return back()->with('success', 'Tanggapan Terkirim');
    }

    public function show_all(Request $request)
    {

        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('search', $request->search);
        Session::flash('status', $request->status);

        $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->orderBy('tgl_pengaduan', 'desc')->paginate(16);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('judul', 'like', '%' . $request->search . '%')->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->status)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', $request->status)->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->search) && isset($request->status)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('judul', 'like', '%' . $request->search . '%')->where('status', '=', $request->status)->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->status)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->where('status', '=', $request->status)->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('judul', 'like', '%' . $request->search . '%')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if ((isset($request->tgl_awal) && isset($request->tgl_akhir)) && isset($request->search) && isset($request->status)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', $request->status)->where('judul', 'like', '%' . $request->search . '%')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        // return $pengaduan;
        return view('admas/pengaduan_anda')->with([
            'title' => 'Seluruh Pengaduan',
            'pengaduan' => $pengaduan,
        ]);
    }
    public function show_belum(Request $request)
    {

        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('search', $request->search);

        $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '0')->orderBy('tgl_pengaduan', 'desc')->paginate(16);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '0')->where('judul', 'like', '%' . $request->search . '%')->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '0')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir) && isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '0')->where('judul', 'like', '%' . $request->search . '%')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        // return $pengaduan;
        return view('admas/pengaduan_anda')->with([
            'title' => 'Pengaduan Belum Diproses',
            'pengaduan' => $pengaduan,
        ]);
    }
    public function show_sedang(Request $request)
    {

        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('search', $request->search);

        $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '1')->orderBy('tgl_pengaduan', 'desc')->paginate(16);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '1')->where('judul', 'like', '%' . $request->search . '%')->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '1')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir) && isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '1')->where('judul', 'like', '%' . $request->search . '%')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        // return $pengaduan;
        return view('admas/pengaduan_anda')->with([
            'title' => 'Pengaduan Sedang Diproses',
            'pengaduan' => $pengaduan,
        ]);
    }
    public function show_telah(Request $request)
    {

        Session::flash('tgl_awal', $request->tgl_awal);
        Session::flash('tgl_akhir', $request->tgl_akhir);
        Session::flash('search', $request->search);

        $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '2')->orderBy('tgl_pengaduan', 'desc')->paginate(16);

        if (isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '2')->where('judul', 'like', '%' . $request->search . '%')->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '2')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        if (isset($request->tgl_awal) && isset($request->tgl_akhir) && isset($request->search)) {
            $pengaduan = Pengaduan::with('user', 'kecamatan')->where('masyarakat_nik', '=', Auth::user()->nik)->where('status', '=', '2')->where('judul', 'like', '%' . $request->search . '%')->whereBetween('tgl_pengaduan', [$request->tgl_awal, $request->tgl_akhir])->orderBy('tgl_pengaduan', 'desc')->paginate(1000);
        }

        // return $pengaduan;
        return view('admas/pengaduan_anda')->with([
            'title' => 'Pengaduan Selesai',
            'pengaduan' => $pengaduan,
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pengaduan' => 'required',
        ], [
            'status_pengaduan.required' => 'Status Wajib Diisi',
        ]);

        $data = [
            'status' => $request->status_pengaduan,
        ];

        Pengaduan::where('id', '=', $id)->update($data);

        switch ($request->status_pengaduan) {
            case '0':
                $tanggapan = 'Pengaduan Belum Diproses';
                break;

            case '1':
                $tanggapan = 'Pengaduan Sedang Diproses';
                break;

            case '2':
                $tanggapan = 'Pengaduan Selesai';
                break;
        }

        Tanggapan::create([
            'pengaduan_id' => $id,
            'tgl_tanggapan' => date('Y-m-d'),
            'tanggapan' => $tanggapan,
            'petugas_id' => Auth::user()->nik,
        ]);

        return back()->with('success', 'Berhasil Edit Status');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pengaduan::destroy($id);
        return back()->with('success', 'Berhasil Hapus Pengaduan');
    }
}
