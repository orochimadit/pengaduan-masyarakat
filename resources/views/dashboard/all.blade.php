@extends('dashboard')

@section('isi')
<div class="bg-white w-1/2 p-5 my-5 rounded-md mx-auto">
    <p class="bg-slate-200 p-2 rounded-md text-center font-bold text-xl">Buat Akun</p>
    <div class="my-1">
        <form action="/sign-up" method="POST">
            @csrf
              <div class="flex flex-col gap-3">
                <div>
                  <label for="register_nik">NIK</label>
                  <input class="rounded-md w-full" type="text" name="register_nik" id="register_nik" required>
                </div>
    
                <div>
                    <label for="register_name">Nama Lengkap</label>
                    <input class="rounded-md w-full" type="text" name="register_name" id="register_name" required>
                </div>
                
                <div>
                    <label for="register_username">Username</label>
                    <input class="rounded-md w-full" type="text" name="register_username" id="register_username" required>
                </div>
    
                <div>
                    <label for="register_email">Email</label>
                    <input class="rounded-md w-full" type="email" name="register_email" id="register_email" required>
                </div>
                
                <div>
                    <label for="register_password">Password</label>
                    <input class="rounded-md w-full" type="password" name="register_password" id="register_password" required>
                </div>
                

                <div class="flex flex-1 gap-3 mb-5">
                    <div class="w-full">
                        <label for="register_telp">No. Telepon</label>
                        <input class="rounded-md w-full" type="number" name="register_telp" id="register_telp" required>
                    </div>
                    <div class="w-full">
                        <label for="lvl">Jabatan</label>
                        <select class="rounded-md w-full" name="lvl" id="lvl" required>
                            <option value="" selected disabled>-PILIH-</option>
                            <option value="admin">Admin</option>
                            <option value="guest">Tamu</option>
                        </select>
                    </div>
                    
                </div>
                
                <div>
                  <button class="btn w-full">Simpan</button>
                </div>
              </div>
            </form>
    </div>
</div>

<div class="grid grid-col-2 grid-flow-row sm:grid-cols-4 gap-3">
    <div class="bg-white p-3 rounded-md">
        <p>Seluruh Pengaduan</p>
        <a href="">Selengkapnya>></a>
    </div>
    
    <div class="bg-white p-3 rounded-md">
        <p>Pengaduan Belum Diproses</p>
        <a href="">Selengkapnya>></a>
    </div>

    <div class="bg-white p-3 rounded-md">
        <p>Pengaduan Sedang Diproses</p>
        <a href="">Selengkapnya>></a>
    </div>
    
    <div class="bg-white p-3 rounded-md">
        <p>Pengaduan Telah Diproses</p>
        <a href="">Selengkapnya>></a>
    </div>

</div>

<div class="bg-white w-full p-5 my-5 rounded-md">
    <p class="bg-slate-200 p-2 rounded-md text-center font-bold text-xl">Pengaduan Belum Diproses</p>
    <div class="my-1">
        <table class="w-full bg-white rounded-md overflow-hidden">
            <thead>
                <tr>
                    <th class="w-[5%]">No.</th>
                    <th class="w-[15%]">Tanggal</th>
                    <th class="w-[50%]">Judul</th>
                    <th class="w-[10%]">Status</th>
                    <th class="w-[20%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($pengaduan->where('status', '=', '0')->first()))
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($pengaduan->where('status', '=', '0') as $index => $i)
                        

                        <tr class="[&>td]:border [&>td]:p-2">
                            <td><p class="text-center">{{ $no++ }}</p></td>
                            <td><p class="text-center">{{ $i->tgl_pengaduan }}</p></td>
                            <td><p class="text-center">{{ $i->judul }}</p></td>
                            <td><p class="text-center"><label for="" class="bg-green-500 rounded-md p-1 text-white">{{ $i->status }}</label></p></td>
                            <td class="flex flex-col flex-1 gap-3 sm:flex-row">
                                <a href="/pengaduan/delete/{{ $i->id }}" class="bg-red-500 p-1 w-full rounded-md text-white text-center hover:bg-red-600">Hapus</a>
                                <a href="{{ '/pengaduan/me/' . $i->id }}" class="w-full bg-slate-700 p-1 block text-center text-white hover:bg-slate-900 rounded-md">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <td colspan="5"><p class="text-center text-2xl font-semibold p-5 bg-slate-700 text-white rounded-md">- Tidak ada Pengaduan -</p></td>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white w-full p-5 my-5 rounded-md">
    <p class="bg-slate-200 p-2 rounded-md text-center font-bold text-xl">Pengaduan Sedang Diproses</p>
    <div class="my-1">
        <table class="w-full bg-white rounded-md overflow-hidden">
            <thead>
                <tr>
                    <th class="w-[5%]">No.</th>
                    <th class="w-[15%]">Tanggal</th>
                    <th class="w-[50%]">Judul</th>
                    <th class="w-[10%]">Status</th>
                    <th class="w-[20%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($pengaduan->where('status', '=', '1')->first()))
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($pengaduan->where('status', '=', '1') as $index => $i)
                        

                        <tr class="[&>td]:border [&>td]:p-2">
                            <td><p class="text-center">{{ $no++ }}</p></td>
                            <td><p class="text-center">{{ $i->tgl_pengaduan }}</p></td>
                            <td><p class="text-center">{{ $i->judul }}</p></td>
                            <td><p class="text-center"><label for="" class="bg-green-500 rounded-md p-1 text-white">{{ $i->status }}</label></p></td>
                            <td class="flex flex-col flex-1 gap-3 sm:flex-row">
                                <a href="/pengaduan/delete/{{ $i->id }}" class="bg-red-500 p-1 w-full rounded-md text-white text-center hover:bg-red-600">Hapus</a>
                                <a href="{{ '/pengaduan/me/' . $i->id }}" class="w-full bg-slate-700 p-1 block text-center text-white hover:bg-slate-900 rounded-md">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <td colspan="5"><p class="text-center text-2xl font-semibold p-5 bg-slate-700 text-white rounded-md">- Tidak ada Pengaduan -</p></td>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white w-full p-5 my-5 rounded-md">
    <p class="bg-slate-200 p-2 rounded-md text-center font-bold text-xl">Pengaduan Telah Diproses</p>
    <div class="my-1">
        <table class="w-full bg-white rounded-md overflow-hidden">
            <thead>
                <tr>
                    <th class="w-[5%]">No.</th>
                    <th class="w-[15%]">Tanggal</th>
                    <th class="w-[50%]">Judul</th>
                    <th class="w-[10%]">Status</th>
                    <th class="w-[20%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($pengaduan->where('status', '=', '2')->first()))
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($pengaduan->where('status', '=', '2') as $index => $i)
                        

                        <tr class="[&>td]:border [&>td]:p-2">
                            <td><p class="text-center">{{ $no++ }}</p></td>
                            <td><p class="text-center">{{ $i->tgl_pengaduan }}</p></td>
                            <td><p class="text-center">{{ $i->judul }}</p></td>
                            <td><p class="text-center"><label for="" class="bg-green-500 rounded-md p-1 text-white">{{ $i->status }}</label></p></td>
                            <td class="flex flex-col flex-1 gap-3 sm:flex-row">
                                <a href="/pengaduan/delete/{{ $i->id }}" class="bg-red-500 p-1 w-full rounded-md text-white text-center hover:bg-red-600">Hapus</a>
                                <a href="{{ '/pengaduan/me/' . $i->id }}" class="w-full bg-slate-700 p-1 block text-center text-white hover:bg-slate-900 rounded-md">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <td colspan="5"><p class="text-center text-2xl font-semibold p-5 bg-slate-700 text-white rounded-md">- Tidak ada Pengaduan -</p></td>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection