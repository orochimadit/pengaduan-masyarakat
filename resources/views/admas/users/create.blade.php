@extends('layouts/base')

@section('body')

<div class="bg-green-700 py-10 text-center text-white border-y-4 border-green-900">
    <p class="font-bold text-2xl">{{ $title }}</p>
    <small>Buat Akun Baru</small>
</div>

<div class="pt-1 p-4 bg-slate-800 flex justify-center">
    <div class="w-full max-w-md">
        <div class="relative mb-5 mt-5">
            <h3 class="text-center text-white text-3xl font-bold before:inline-block before:h-12 before:absolute before:-ml-4 before:w-3 before:bg-red-700">Formulir Pembuatan Akun</h3>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-5">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nik" class="block text-white font-semibold">NIP</label>
                <input type="text" class="w-full rounded-md p-2 text-black" id="nik" name="nik" value="{{ old('nik') }}" required>
            </div>
            <div>
                <label for="name" class="block text-white font-semibold">Nama</label>
                <input type="text" class="w-full rounded-md p-2 text-black" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="username" class="block text-white font-semibold">Username</label>
                <input type="text" class="w-full rounded-md p-2 text-black" id="username" name="username" value="{{ old('username') }}" required>
            </div>
            <div>
                <label for="email" class="block text-white font-semibold">Email</label>
                <input type="email" class="w-full rounded-md p-2 text-black" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div>
                <label for="password" class="block text-white font-semibold">Password</label>
                <input type="password" class="w-full rounded-md p-2 text-black" id="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation" class="block text-white font-semibold">Konfirmasi Password</label>
                <input type="password" class="w-full rounded-md p-2 text-black" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div>
                <label for="telp" class="block text-white font-semibold">Telepon</label>
                <input type="text" class="w-full rounded-md p-2 text-black" id="telp" name="telp" value="{{ old('telp') }}" required>
            </div>
            <div>
                <label for="lvl" class="block text-white font-semibold">Level</label>
                <select class="w-full rounded-md p-2 text-black" id="lvl" name="lvl" required>
                    {{-- <option value="masyarakat" {{ old('lvl') == 'masyarakat' ? 'selected' : '' }}>Masyarakat</option> --}}
                    <option value="petugas" {{ old('lvl') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="admin" {{ old('lvl') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label for="aktif" class="block text-white font-semibold">Status Aktif</label>
                <select class="w-full rounded-md p-2 text-black" id="aktif" name="aktif" required>
                    <option value="1" {{ old('aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div>
                <label for="kecamatan_id" class="block text-white font-semibold">Dinas</label>
                <select class="w-full rounded-md p-2 text-black" id="kecamatan_id" name="kecamatan_id" required>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white w-full mt-5">Buat Akun</button>
        </form>
    </div>
</div>

@endsection
