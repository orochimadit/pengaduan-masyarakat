@extends('layouts/base')

@section('body')
<div class="bg-red-700 py-10 text-center text-white border-y-4 border-red-900 relative">
    <p class="font-bold text-2xl">{{ $title }}</p>
    <small>Lihat Pengaduan Anda Disini</small>
</div>
<div class="p-3">
    <a href="/pengaduan" class="btn btn-sm w-full text-white"><< Kembali</a>
</div>

<div class="min-h-[300px]">
    <div class="relative mb-5 p-4">
        <h3 class="text-center text-5xl font-bold before:inline-block before:h-12 before:absolute before:-ml-4 before:w-3 before:bg-red-700">List Pengaduan</h3>
    </div>

    @php
            function search($title)
            {
                if($title == 'Seluruh Pengaduan')
                {
                    return '/pengaduan-all';
                }

                if($title == 'Pengaduan Belum Diproses')
                {
                    return '/pengaduan-belum-diproses';
                }

                if($title == 'Pengaduan Sedang Diproses')
                {
                    return '/pengaduan-sedang-diproses';
                }
                
                if($title == 'Pengaduan Selesai')
                {
                    return '/pengaduan-telah-diproses';
                }


            }
        @endphp

        <div class="mx-5 sm:mx-10">
            <form class="sm:flex items-center gap-2" action="{{ search($title) }}" method="GET">
                <div class="sm:flex items-center gap-5">
                    <div class="flex items-center gap-5">
                        <div class="">
                            <label for="tgl_awal" class="">Tanggal Awal: </label>
                            <input class="w-full sm:w-auto rounded-md p-1 text-[15px]" type="date" name="tgl_awal" id="tgl_awal" value="{{ Session::get('tgl_awal') }}">
                        </div>
                        
                        <div class="">
                            <label for="tgl_akhir" class="">Tanggal Akhir: </label>
                            <input class="w-full sm:w-auto rounded-md p-1 text-[15px]" type="date" name="tgl_akhir" id="tgl_akhir" value="{{ Session::get('tgl_akhir') }}">
                        </div>
                    </div>

                    @if ($title == 'Seluruh Pengaduan')
                        <div class="">
                            <label for="status" class="">Status Pengaduan: </label>
                            <select name="status" id="status" class="w-full sm:w-auto rounded-md p-1 pr-10 text-[15px]">
                                <option value="" selected>- PILIH -</option>
                                <option value="0" {{ Session::get('status') == '0' ? 'selected' : '' }} >Belum Diproses</option>
                                <option value="1" {{ Session::get('status') == '1' ? 'selected' : '' }} >Sedang Diproses</option>
                                <option value="2" {{ Session::get('status') == '2' ? 'selected' : '' }} >Selesai</option>
                            </select>
                        </div>
                    @endif
                    
                </div>
                            
                  
                        <div class="sm:ml-auto flex gap-3 my-3">
                            <input placeholder="Search disini..." class="w-full sm:w-auto rounded-md p-1 text-[15px]" type="search" name="search" id="search_pengaduan" value="{{ Session::get('search') }}">
                            <button data-tooltip-target="tooltip-filter" class="bg-sky-400 btn btn-sm sm:mt-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <div id="tooltip-filter" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Cari
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>

                            <a data-tooltip-target="tooltip-reset" href="{{ search($title) }}" class="bg-red-600 btn btn-sm sm:mt-0"><i class="fa-solid fa-arrow-rotate-left"></i></a>        
                            <div id="tooltip-reset" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Atur Ulang
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
            </form>
        </div>


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

    <div class="grid grid-flow-row grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-7 w-full p-5 sm:px-10">
        @foreach ($pengaduan as $index => $i)
            <div class="card card-compact w-full bg-base-100 shadow-xl box-border border-black border hover:scale-110 hover:z-50 transition-transform overflow-hidden">
                <figure class="bg-gray-300"><img class="h-[150px] sm:h-[150px] w-full object-contain" src="{{ $i->foto !== NULL ? (asset('img_pengaduan/' . $i->foto)) : asset('img/kosong.jpg') }}" alt="img_pengaduan" /></figure>
                <div class="p-3">

                    @if ($i->status == '0')
                        <label for="modal-hapus" class="font-bold first-letter:uppercase text-sm truncate"> <br class="sm:hidden"> <span class="absolute top-0 left-0 bg-red-600 hover:bg-red-700 px-6 py-1 rounded-tr rounded-br text-white font-semibold"><i class="fa-solid fa-trash-can"></i></span></label>
                    @endif

                    <h2 class="font-bold first-letter:uppercase text-sm truncate">{{ $i->judul }} <br class="sm:hidden"> <span class="absolute top-0 right-0 {{ $status->Color($i) }} p-1 rounded-tl rounded-bl text-white font-semibold">{{ $status->Text($i) }}</span></h2>
                    <div class="grid grid-flow-row grid-cols-2 gap-3 my-3">
                        <p class="text-sm">Tanggal Kejadian:<br> {{ date('d-m-Y',strtotime($i->tgl_pengaduan)) ?? 'NULL Tanggal' }}</p>
                        <p class="text-sm truncate">Lokasi:<br> {{ $i->kecamatan[0]->kecamatan ?? 'NULL Lokasi' }}</p>
                    </div>
                    <p class="truncate text-sm">Dibuat oleh:<br> {{ $i->user->username ?? 'NULL Username' }}</p>
                    <div class="card-actions justify-end mt-3">
                        <a href="/pengaduan/me/{{ $i->id }}" class="btn btn-sm w-full btn-primary">Detail</a>
                    </div>
                </div>
            </div>

            @if ($i->status == '0')
                <input type="checkbox" id="modal-hapus" class="modal-toggle" />
                <div class="modal">
                <div class="modal-box relative">
                    <h3 class="text-xl font-bold bg-red-700 text-white rounded-md p-3">Hapus Pengaduan</h3>
                    <p class="py-4">Anda yakin ingin menghapus pengaduan?</p>
                    <div class="modal-action">
                        <label for="modal-hapus" class="btn btn-sm">Batal</label>
                        <form action="/pengaduan/delete/{{ $i->id }}" method="POST">
                            @csrf
                            <button class="btn btn-sm bg-red-600 hover:bg-red-700" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
                </div>
                @endif
        @endforeach
    </div>
    <div class="px-10 mt-5">
        {{ $pengaduan->links() }}
    </div>

    
    @if (empty($pengaduan->first()))
        <div class="h-40 flex items-center justify-center">
            <p class="text-xl"><span class="text-white bg-slate-700 p-3 rounded-md">-Tidak Ada pengaduan-</span></p>
        </div>
    @endif

    <div class="text-center mt-10 bg-slate-600 p-10">
        <label class="text-white block font-bold text-5xl">Ingin Buat Pengaduan?</label>
        
        <a class="btn btn-lg bg-red-700 mt-5" href="/">Buat Pengaduan</a>
    </div>
</div>

@endsection