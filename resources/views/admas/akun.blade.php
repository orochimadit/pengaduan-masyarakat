@extends('layouts/base')

@section('body')

    @if ($title !== 'Detail Pengguna')
        <div class="bg-red-700 py-10 text-center text-white border-y-4 border-red-900">
            <p class="font-bold text-2xl">Profil</p>
            <small>Atur Akunmu Disini!</small>
        </div>
    @else
        <div class="bg-red-700 py-10 text-center text-white border-y-4 border-red-900">
            <p class="font-bold text-2xl">Akun Detail</p>
            <small>Lihat Detail Akun Disini!</small>
        </div>
    @endif

    <div class="bg-white border-2 border-black rounded-md p-5 mx-3 my-10">
        <label for="nik">
            <p class="font-bold text-2xl mb-5 text-center bg-red-700 text-white rounded-md py-2">Profil</p>
        </label>
        <form id="formAkun" action="/akun/update" method="POST" class="flex flex-col gap-10">
            @csrf

            <div id="lvl" class="{{ Auth::user()->lvl == 'masyarakat' ? 'hidden' : '' }}">
                <label for="inputLvl">Jabatan</label>
                <input id="inputLvl" name="nik" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="text" name="lvl" required value="{{ ucfirst($akun->lvl) }}">
            </div>

            <div class="sm:flex gap-5">
                <div id="nik" class="flex gap-5 flex-1">
                    <div class="flex-1 mb-10 sm:mb-0">
                        <label for="inputNik">NIK</label>
                        <input id="inputNik" name="nik" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="number" name="nik" required value="{{ $akun->nik }}">
                    </div>
                    
                    <div id="username" class="flex-1">
                        <label for="inputUsername">Username</label>
                        <input id="inputUsername" name="username" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="text" name="username" required value="{{ $akun->username }}">
                    </div>
                </div>
          
                <div class="flex-1">
                    <label for="inputName">Nama Lengkap</label>
                    <input id="inputName" name="name" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="text" name="name" required value="{{ $akun->name }}">
                </div>
            </div>

            
            
            <div class="sm:flex gap-5">
                <div class="flex-1 mb-10 sm:mb-0">
                    <label for="inputEmail">Email</label>
                    <input id="inputEmail" name="email" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="email" name="email" required value="{{ $akun->email }}">
                </div>

                <div class="mb-5 flex-1">
                    <label for="inputTelp">No. Telepon</label>
                    <input id="inputTelp" name="telp" disabled class="disabled:bg-slate-200 disabled:text-slate-500 rounded-md w-full" type="text" name="telp" required value="{{ $akun->telp }}">
                </div>
            </div>
            
          
           
            <div class="flex justify-end">
                @if ($title == 'Akun')
                    <button id="toggleEdit" onclick="editAkun()" type="button" class="btn text-slate-700 hover:text-white bg-yellow-400">Edit</button>
                @endif
                <div id="divEdit" class="w-full hidden">
                    <button id="btnSimpan" onclick="simpan()" type="button" class="btn bg-sky-500 hover:bg-sky-600 w-full my-1">Simpan</button>
                    <button id="btnBatal" onclick="resetFormAkun()" type="button" class="btn w-full my-1">Batal</button>
                </div>
            </div>
        </form>
    </div>

    <div>

        <div class="grid grid-cols-2 sm:grid-cols-4 grid-flow-row gap-3 my-5 mx-2 px-3 mb-10">

            <div class="border border-black rounded-md p-2 bg-white">
                <label for="tgl_pengaduan">
                    <p class="font-semibold text-white h-14 xl:h-fit text-center bg-sky-500 rounded-md sm:py-2">Seluruh <br class="xl:hidden">Pengaduan</p>
                </label>
                <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->count( ) }}</p>
            </div>
        
            <div class="border border-black rounded-md p-2 bg-white">
                <label for="tgl_pengaduan">
                    <p class="font-semibold text-white h-14 xl:h-fit text-center bg-red-700 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Belum Diproses</p>
                </label>
                <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '0')->count() }}</p>
            </div>
        
            <div class="border border-black rounded-md p-2 bg-white">
                <label for="tgl_pengaduan">
                    <p class="font-semibold text-white h-14 xl:h-fit text-center bg-yellow-500 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Sedang Diproses</p>
                </label>
                <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '1')->count() }}</p>
            </div>
        
            <div class="border border-black rounded-md p-2 bg-white">
                <label for="tgl_pengaduan">
                    <p class="font-semibold text-white h-14 xl:h-fit text-center bg-green-500 rounded-md sm:py-2">Pengaduan <br class="xl:hidden">Selesai</p>
                </label>
                <p class="text-4xl font-bold text-center my-5 truncate">{{ $jml->where('status', '=', '2')->count() }}</p>
            </div>
        
        </div>

        @if ($title == 'Detail Pengguna')
            <div>
                <div class="mx-5 sm:mx-10">
                    <form class="sm:flex items-center gap-2" action="/admin/user/detail/{{ $akun->nik }}" method="GET">
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
        
                            <div class="">
                                <label for="status" class="">Status Pengaduan: </label>
                                <select name="status" id="status" class="w-full sm:w-auto rounded-md p-1 pr-10 text-[15px]">
                                    <option value="" selected>- PILIH -</option>
                                    <option value="0" {{ Session::get('status') == '0' ? 'selected' : '' }} >Belum Diproses</option>
                                    <option value="1" {{ Session::get('status') == '1' ? 'selected' : '' }} >Sedang Diproses</option>
                                    <option value="2" {{ Session::get('status') == '2' ? 'selected' : '' }} >Selesai</option>
                                </select>
                            </div>
                        </div>
                                    
                        
                                <div class="sm:ml-auto flex gap-3 my-3">
                                    <input placeholder="Search disini..." class="w-full sm:w-auto rounded-md p-1 text-[15px]" type="search" name="search" id="search_pengaduan" value="{{ Session::get('search') }}">
                                    <button data-tooltip-target="tooltip-filter" class="bg-sky-400 btn btn-sm sm:mt-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <div id="tooltip-filter" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Cari
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <a href="/admin/user/detail/{{ $akun->nik}}/export?tgl_awal={{ $tgl_awal }}&tgl_akhir={{ $tgl_akhir }}&search={{ $search }}&status={{ $status }}" data-tooltip-target="tooltip-export" class="bg-green-500 btn btn-sm sm:mt-0"><i class="fa-solid fa-file-excel"></i></a>
                                    <div id="tooltip-export" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Export-Excel
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
        
                                    <a data-tooltip-target="tooltip-reset" href="/admin/user/detail/{{ $akun->nik }}" class="bg-red-600 btn btn-sm sm:mt-0"><i class="fa-solid fa-arrow-rotate-left"></i></a>        
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
        
                
                @endforeach
            </div>
            {{-- <div class="px-10 mt-5">
                {{ $pengaduan->links() }}
            </div> --}}
            </div>
        @endif


    </div>



    <script>
        const inputName = document.getElementById('inputName');
        const inputEmail = document.getElementById('inputEmail');
        const inputTelp = document.getElementById('inputTelp');

        const lvl = document.getElementById('lvl');
        const nik = document.getElementById('nik');
        const username = document.getElementById('username');

        const toggleEdit = document.getElementById('toggleEdit');
        const formAkun = document.getElementById('formAkun');
        const divEdit = document.getElementById('divEdit');

        function editAkun()
        {
            lvl.style.display = 'none';
            nik.style.display = 'none';
            username.style.display = 'none';

            inputName.disabled = false
            inputEmail.disabled = false
            inputTelp.disabled = false

            inputName.focus()

            toggleEdit.classList.add('hidden')
            divEdit.classList.remove('hidden')
        }

        function resetFormAkun()
        {
            location.reload()
        }

        function simpan()
        {
            if (confirm("Anda yakin ingin menyimpan data?") == true) {
                formAkun.submit()
            } else {
                location.reload()
            }
        }
    </script>
@endsection