@extends('layouts/base')

@section('body')
<div class=" bg-slate-700 min-h-screen ">

    <div class="bg-red-700 py-10 mb-10 text-center text-white border-y-4 border-red-900">
        <p class="font-bold text-2xl">{{ $title }}</p>
    </div>

    
        <div class="flex mx-5 flex-col sm:flex-row sm:justify-evenly ">
            <div class="sm:w-1/4">
                <div class="p-3  bg-white border border-black rounded-md h-fit mb-5">
                    <p class="p-3 bg-red-700 rounded-md text-white font-semibold text-2xl mb-3">Tambah Kecamatan</p>
                    <form action="/admin/kecamatan/create" method="POST" class="flex flex-col gap-5">
                        @csrf
                        <div>
                            <label for="inp_kecamatan">Nama Kecamatan</label>
                            <input class="rounded-md w-full" type="text" name="kecamatan" id="inp_kecamatan">
                        </div>
                        <div class="text-right">
                            <label for="my-modal-3" class="bg-blue-500 btn btn-sm">+ Tambah</label>
                        </div>
               
                        <input type="checkbox" id="my-modal-3" class="modal-toggle" />
                        <div class="modal">
                            <div class="modal-box relative">
                                <h3 class="text-lg font-bold">Konfirmasi!</h3>
                                <p class="py-4">Anda yakin ingin tambah kecamatan baru?</p>
                                <div class="modal-action">
                                    <label for="my-modal-3" class="btn btn-sm">Batal</label>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 btn btn-sm">+ Tambah</button>
                                </div>
                            </div>
                        </div>
        
                    </form>
                </div>

                <div class="hidden sm:block p-3  bg-white border border-black rounded-md h-fit mb-5">
                    <p class="p-3 bg-red-700 rounded-md text-white font-semibold text-2xl mb-3">Filter</p>
                    <form action="/admin/kecamatan" method="GET" class="flex flex-col gap-5">
                        <div>
                            <label for="inp_kecamatan">Nama Kecamatan</label>
                            <input class="rounded-md w-full" type="text" name="kecamatan" id="inp_kecamatan" value="{{ Session::get('kecamatan') }}">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-blue-500 btn btn-sm"><i class="fa-solid fa-magnifying-glass mr-3"></i> Cari</button>
                            <a class="bg-red-700 btn btn-sm" href="/admin/kecamatan"><i class="fa-solid fa-arrow-rotate-left mr-3"></i> Atur Ulang</a>
                        </div>
        
                    </form>
                </div>
            </div>
    
    
            <div class="sm:w-1/2 mb-10">

                <form  class="sm:hidden flex gap-2 mb-3" action="/admin/kecamatan" method="GET">
                    <input placeholder="Search disini..." class="w-full sm:w-auto rounded-md p-1 text-[15px]" type="search" name="kecamatan" value="{{ Session::get('kecamatan') }}">
                    <button type="submit" data-tooltip-target="tooltip-filter" class="bg-sky-400 btn btn-sm sm:mt-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    
                    <div id="tooltip-filter" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Cari
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <div>
                        <a data-tooltip-target="tooltip-reset" href="/admin/kecamatan" class="bg-red-600 btn btn-sm"><i class="fa-solid fa-arrow-rotate-left"></i></a>        
                        <div id="tooltip-reset" role="tooltip" class="border border-black transition absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium bg-white rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Atur Ulang
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </form>
                        
                <div class="bg-white border border-black rounded-md overflow-auto mb-5">
                    <table class="table table-zebra table-auto w-full">
                        <thead class="text-center">
                            <th class="w-[10%]">No.</th>
                            <th>Kecamatan</th>
                            <th class="w-[10%]">Aksi</th>
                        </thead>
                        <tbody>
                            @foreach ($kecamatan as $index => $i)
                                <tr class="[&>td]:border [&>td]:p-2">
                                    <th class="text-center">{{ $kecamatan->firstItem() + $index }}</th>
                                    <td class="text-center">{{ $i->kecamatan }}</td>
                                    <td class="text-center">
                                        <label for="modal-edit-{{ $i->id }}" class="btn btn-sm bg-yellow-500 hover:bg-yellow-600"><i class="fa-solid fa-pen-to-square"></i></label>
                                        <label for="modal-hapus-{{ $i->id }}" class="btn btn-sm bg-red-700 hover:bg-red-800"><i class="fa-solid fa-trash-can"></i></label>
                                    </td>
        
                                    
                                </tr>
                                {{-- modal edit --}}
                                <input type="checkbox" id="modal-edit-{{ $i->id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box relative">
                                        <h3 class="text-lg font-bold bg-red-700 px-3 py-2 rounded-md text-white mb-5">Edit Kecamatan</h3>
                                        <form action="/admin/kecamatan/edit/{{ $i->id }}" method="POST">
                                            @csrf
                                            <div class="mb-5">
                                                <label for="inp_edit_kecamatan">Nama Kecamatan Lama</label>
                                                <input class="w-full rounded-md bg-gray-200 text-slate-600" type="text" value="{{ $i->kecamatan }}" disabled>
                                            </div>
                                            
                                            <div class="mb-5">
                                                <label for="inp_edit_kecamatan">Nama Kecamatan Baru</label>
                                                <input class="w-full rounded-md" type="text" name="kecamatan" id="inp_edit_kecamatan">
                                            </div>
                                            
                                            <div class="text-right">
                                                <label for="modal-edit-{{ $i->id }}" class="btn btn-sm">Batal</label>
                                                <button class="btn btn-sm bg-yellow-500 hover:bg-yellow-600">Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
        
                                {{-- modal hapus --}}
                                <input type="checkbox" id="modal-hapus-{{ $i->id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box relative">
                                        <h3 class="text-lg font-bold">Konfirmasi!</h3>
                                        <p class="py-4">Anda yakin ingin menghapus kecamatan?</p>
        
                                        <div class="modal-action">
                                            <label for="modal-hapus-{{ $i->id }}" class="btn btn-sm">Batal</label>
                                            <form method="POST" action="/admin/kecamatan/delete/{{ $i->id }}">
                                                @csrf
                                                <button class="btn btn-sm bg-red-700 hover:bg-red-800" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $kecamatan->links() }}
            </div>
        </div>

    </div>

@endsection