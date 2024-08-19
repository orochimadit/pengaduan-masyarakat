@extends('layouts/base')

@section('body')

<div class="bg-green-700 py-10 text-center text-white border-y-4 border-green-900">
    <p class="font-bold text-2xl">{{ $title }}</p>
    <small>Daftar Seluruh Pengguna</small>
</div>

<div class="pt-1 p-4 bg-slate-800">
    <div class="relative mb-5 mt-5">
        <h3 class="text-center text-white text-5xl font-bold before:inline-block before:h-12 before:absolute before:-ml-4 before:w-3 before:bg-red-700">List Pengguna</h3>
    </div>

    <div class="flex justify-end mb-5">
        <a href="{{ route('users.create') }}" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white">Buat Akun Baru</a>
    </div>

    <div class="rounded-b-md overflow-auto">
        <table class="table table-auto table-zebra table-compact w-full bg-white overflow-auto mb-5">
            <thead class="text-center">
                <tr>
                    <th class="sm:w-[5%]">No.</th>
                    <th class="sm:w-[15%]">NIP</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th class="sm:w-[10%]">Level</th>
                    <th class="sm:w-[10%]">Status</th>
                    <th class="sm:w-[15%]">Dinas</th>
                    <th class="sm:w-[10%]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr class="[&>td]:border [&>td]:p-2 text-center">
                        <th>{{ $users->firstItem() + $index }}</th>
                        <td>{{ $user->nik }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telp }}</td>
                        <td>{{ ucfirst($user->lvl) }}</td>
                        <td>{{ $user->aktif == '1' ? 'Aktif' : 'Non-Aktif' }}</td>
                        <td>{{ $user->kecamatan ? $user->kecamatan->kecamatan : 'Tidak Diketahui' }}</td>
                        <td class="">
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm bg-yellow-500 hover:bg-yellow-600 text-white">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-red-600 hover:bg-red-700 text-white mt-1">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>

    @if ($users->isEmpty())
        <p class="text-center text-2xl font-semibold p-5 rounded-b-md bg-slate-700 text-white">- Tidak ada Pengguna -</p>
    @endif
</div>

@endsection
