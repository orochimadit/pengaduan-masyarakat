@extends('layouts/base')

@section('body')

<div class="bg-green-600 flex items-center justify-center gap-5 sm:gap-20 p-10 text-center text-white relative">
    <img class="border-4 h-20 p-1 sm:h-60 -rotate-12 border-white rounded-md" src="{{ asset('img/logo-e-Lapor-Sehati.png') }}" alt="tangspor-logo.png">

    <div class="text-center p-4">
        <p class="font-bold text-2xl sm:text-4xl">E-LAPOR SEHATI</p>
        <p class="font-bold italic text-lg sm:text-2xl mt-2">Elektronik - Layanan Aspirasi Pengaduan Online Rakyat untuk Sehat Bersama, Keluhan Teratasi</p>
        <small class="text-sm sm:text-lg mt-2 block">Adalah sebuah sistem pengaduan online inovatif yang dikembangkan dan dirancang untuk menjembatani komunikasi antara masyarakat dan penyedia layanan kesehatan, memungkinkan warga untuk dengan mudah menyampaikan keluhan dan aspirasi terkait layanan kesehatan melalui platform digital.</small>
    </div>

    <img class="border-4 h-20 sm:h-60 p-1 rotate-12 border-white rounded-md object-contain" src="{{ asset('img/pemkab.png') }}" alt="tangspor-logo.png">
</div>
<div class="sm:flex sm:justify-center sm:items-center my-5 px-3">
    <ul class="steps steps-vertical md:steps-horizontal block text-white md:text-black">
        <li class="step step-neutral"><span class="bg-gradient-to-r from-red-700 to-slate-500 md:bg-none p-3 rounded-md w-full text-left sm:text-center">Buat Akun</span></li>
        <li class="step step-neutral"><span class="bg-gradient-to-r from-red-700 to-slate-500 md:bg-none p-3 rounded-md w-full text-left sm:text-center">Login</span></li>
        <li class="step step-neutral"><span class="bg-gradient-to-r from-red-700 to-slate-500 md:bg-none p-3 rounded-md w-full text-left sm:text-center">Laporkan Masalahmu</span></li>
        <li class="step step-neutral"><span class="bg-gradient-to-r from-red-700 to-slate-500 md:bg-none p-3 rounded-md w-full text-left sm:text-center">Kirim</span></li>
        <li class="step step-neutral"><span class="bg-gradient-to-r from-red-700 to-slate-500 md:bg-none p-3 rounded-md w-full text-left sm:text-center">Selesai</span></li>
    </ul>
</div>

<div class="relative mx-5 sm:mx-0">
    <div class=" bg-white border-2 border-slate-800 rounded-md w-full sm:w-96 p-5 mx-auto my-10 after:bg-gradient-to-r from-slate-500 to-green-700 after:z-[-1] after:h-52 after:w-full after:left-0 after:block after:absolute after:top-36 after:-skew-y-[12deg]">
        <label for="tgl_pengaduan">
            <p class="font-bold text-2xl mb-5 text-center bg-green-700 text-white rounded-md py-2">Form Pengaduan</p>
        </label>
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <form id="formPengaduan" action="/pengaduan/create" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col gap-3">
                <div>
                    <label for="tgl_pengaduan">Tanggal Kejadian<span class="text-red-500">*</span></label>
                    <input hidden type="date" value="{{ date('Y-m-d') }}">
                    <input class="rounded-md w-full" type="date" max="{{ date('Y-m-d') }}" name="tgl_pengaduan" id="tgl_pengaduan">
                </div>
                <div>
                    <label for="kecamatan">Dinas<span class="text-red-500">*</span></label>
                    <select class="rounded-md w-full" name="kecamatan" id="kecamatan" required>
                        <option value="" disabled selected>- PILIH PUSKESMAS -</option>

                        @foreach ($kecamatan as $i)
                        <option value="{{ $i->id }}">{{ $i->kecamatan }}</option>
                        @endforeach

                    </select>
                </div>
                <div>
                    <label for="no_hp">nomor Hp<span class="text-red-500">*</span></label>
                    <input class="rounded-md w-full" type="text" name="no_hp" id="no_hp">
                </div>
                <div>
                    <label for="judul_pengaduan">Judul<span class="text-red-500">*</span></label>
                    <input class="rounded-md w-full" type="text" name="judul_pengaduan" id="judul_pengaduan">
                </div>
                <div>
                    <label for="isi_laporan">Isi<span class="text-red-500">*</span></label>
                    <textarea name="isi_laporan" id="isi_laporan" rows="3" class="rounded-md w-full"></textarea>
                </div>
                <div class="mb-5">
                    <label for="foto">Foto</label>

                    <input class="block w-full text-sm text-slate-500
                        border-2 rounded-full
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-red-100 file:text-red-700
                        hover:file:bg-red-100" type="file" name="foto" id="foto">
                </div>
                <div>
                    {{-- @if (Auth::check()) --}}
                    <small class="block mb-1">Catatan: Kolom yang memiliki bintang merah (<span class="text-red-500">*</span>) wajib diisi!</small>
                    <label for="modal-lapor" class="btn w-full">LAPOR!</label>


                    {{-- modal lapor --}}
                    <input type="checkbox" id="modal-lapor" class="modal-toggle" />
                    <div class="modal">
                        <div class="modal-box relative">
                            <h3 class="text-lg font-bold">Konfirmasi!</h3>
                            <p class="py-4">Anda yakin ingin melapor data tersebut?</p>

                            <div class="modal-action">
                                <label for="modal-lapor" class="btn">Batal</label>
                                <button class="btn bg-red-600 hover:bg-red-700">LAPOR!</button>
                            </div>
                        </div>
                    </div>

                    {{-- @else
                            <label class="btn w-full" for="modal-login">Login Terlebih Dahulu!</label>
                            <script>

                            </script>
                        @endif --}}
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bg-green-800 py-10 mt-5 text-center text-white border-y-4 border-red-900">
    <p class="font-semibold text-2xl">JUMLAH LAPORAN SAAT INI</p>
    <label class="text-5xl font-bold">{{ $jml_pengaduan }}</label>
</div>

<div class="flex bg-white items-center py-10 relative">

    <p class="ml-5 text-4xl font-bold">Klik untuk melihat informasi Tentang Kami <span class="ml-10 hidden sm:inline">--></span></p>
    <a class="btn relative z-20 btn-lg mx-10 w-32" href="/tentang-kami">KLIK</a>

    <div class="-z-[0] ml-auto sm:static absolute right-0 w-44 sm:w-1/4 h-20 border-4 border-r-0 rounded-l-lg border-slate-600 border-dashed">
    </div>
</div>

<div class="bg-slate-300 py-3">
    <p class="font-bold text-4xl text-center mb-3">Diinisiasi Oleh:</p>
    {{-- <div class="grid grid-cols-2 sm:grid-cols-4 grid-flow-row">
        <img src="{{ asset('img/sponsor.jpeg') }}" alt="img_sponsors">
    <img src="{{ asset('img/sponsor (2).jpeg') }}" alt="img_sponsors">
    <img src="{{ asset('img/sponsor (3).jpeg') }}" alt="img_sponsors">
    <img src="{{ asset('img/sponsor (4).jpg') }}" alt="img_sponsors">
</div> --}}
<div class="bg-white flex justify-center">
    <img src="{{ asset('img/Logo.png') }}" alt="img_sponsors" class="object-contain rounded-md w-64 h-64">
</div>
</div>

@endsection
