@extends('layouts/base')

@section('body')
<div class="bg-red-700 py-10 text-center text-white">
    <p class="font-bold text-2xl">Pengaduan</p>
    <small>Lihat Pengaduan Anda Disini</small>
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 grid-flow-row gap-3 my-5 mx-2 px-3 mb-10">

    <div class="border border-black rounded-md p-2 hover:scale-110 transition bg-white">
        <label for="tgl_pengaduan">
            <p class="font-semibold text-white h-14 xl:h-fit text-center bg-sky-500 rounded-md sm:py-2">Seluruh <br class="xl:hidden">Pengaduan</p>
        </label>
        <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->count( ) }}</p>
        <a href='/pengaduan-all' class="btn btn-sm w-full">Selengkapnya>></a>
    </div>

    <div class="border border-black rounded-md p-2 hover:scale-110 transition bg-white">
        <label for="tgl_pengaduan">
            <p class="font-semibold text-white h-14 xl:h-fit text-center bg-red-700 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Belum Diproses</p>
        </label>
        <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '0')->count() }}</p>
        <a href='/pengaduan-belum-diproses' class="btn btn-sm w-full">Selengkapnya>></a>
    </div>

    <div class="border border-black rounded-md p-2 hover:scale-110 transition bg-white">
        <label for="tgl_pengaduan">
            <p class="font-semibold text-white h-14 xl:h-fit text-center bg-yellow-500 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Sedang Diproses</p>
        </label>
        <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '1')->count() }}</p>
        <a href='/pengaduan-sedang-diproses' class="btn btn-sm w-full">Selengkapnya>></a>
    </div>

    <div class="border border-black rounded-md p-2 hover:scale-110 transition bg-white">
        <label for="tgl_pengaduan">
            <p class="font-semibold text-white h-14 xl:h-fit text-center bg-green-500 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Selesai</p>
        </label>
        <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '2')->count() }}</p>
        <a href='/pengaduan-telah-diproses' class="btn btn-sm w-full">Selengkapnya>></a>
    </div>

</div>

<div class="relative mb-5 p-4">
    <h3 class="text-center text-5xl font-bold before:inline-block before:h-12 before:absolute before:-ml-4 before:w-3 before:bg-red-700">
        Terbaru
    </h3>

    @if ($terbaru->first() !== NULL)
    @php
    class statusPengaduan
    {
        function Text($detail)
        {
            switch ($detail) {
                case ($detail->status == 0):
                    return 'Belum Diproses';
                break;

                case ($detail->status == 1):
                    return 'Sedang Diproses';
                break;

                case ($detail->status == 2):
                    return 'Selesai';
                break;
            }
        }

        function Color($detail)
        {
            switch ($detail) {
                case ($detail->status == 0):
                    return 'bg-pink-500';
                break;

                case ($detail->status == 1):
                    return 'bg-yellow-500';
                break;

                case ($detail->status == 2):
                    return 'bg-green-500';
                break;
            }
        }
    }

    $status = new statusPengaduan;
@endphp

        <div class="grid grid-flow-row grid-cols-1 sm:grid-cols-5 gap-3 sm:gap-7 w-full p-5">
            @foreach ($terbaru as $index => $i)
                <div class="card card-compact w-full bg-base-100 shadow-xl box-border border-black border hover:scale-110 hover:z-50 transition-transform overflow-hidden">
                    <figure class="bg-gray-300"><img class="h-[150px] sm:h-[150px] w-full object-contain" src="{{ asset('img_pengaduan/' . $i->foto) }}" alt="img_pengaduan" /></figure>
                    <div class="p-3">
                        <h2 class="font-bold first-letter:uppercase text-sm truncate">{{ $i->judul }} <br class="sm:hidden"> <span class="absolute top-0 right-0 {{ $status->Color($i) }} p-1 rounded-tl rounded-bl text-white font-semibold">{{ $status->Text($i) }}</span></h2>
                        <div class="grid grid-flow-row grid-cols-2 gap-3 my-3">
                            <p class="text-sm">Tanggal Kejadian:<br> {{ date('d-m-Y' ,strtotime($i->tgl_pengaduan)) ?? 'NULL Tanggal' }}</p>
                            <p class="text-sm truncate">Lokasi:<br> {{ $i->kecamatan[0]->kecamatan ?? 'NULL Lokasi' }}</p>
                        </div>
                        <p class="truncate text-sm">Dibuat oleh:<br> {{ $i->user->username ?? 'NULL Username' }}</p>
                        <div class="card-actions justify-end mt-3">
                            <a href="/pengaduan/me/{{ $i->id }}" class="btn btn-sm w-full btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="my-10">
            <p class="bg-slate-600 font-bold text-white py-1 px-3 rounded-md text-2xl mx-auto w-fit">- KOSONG -</p>
        </div>
    @endif

    
</div>

<br><br><br>

<div class="text-center mt-10 bg-slate-600 p-10">
    <label class="text-white block font-bold text-5xl">Ingin Buat Pengaduan?</label>
    
    <a class="btn btn-lg bg-red-700 mt-5" href="/">Buat Pengaduan</a>
</div>

@endsection