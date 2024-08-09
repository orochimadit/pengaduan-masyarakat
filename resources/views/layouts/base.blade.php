<!DOCTYPE html>
<html class="bg-stone-100 scroll-smooth">
<head>
    <link rel="shortcut icon" href="{{ asset('img/logo-tangspor.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <script src="{{ asset('fa/js/all.min.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title.' - '.env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>
<body class="min-h-screen">
  @include('components/navbar-admas')
    <div class="drawer h-full">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
          <!-- Page content here -->
          @include('components/pesan')
            @yield('body')


            @include('components/footer')
{{-- End of body content --}}
        </div> 
        <div class="drawer-side">
          <label for="my-drawer" class="drawer-overlay fixed w-full h-full"></label>
          <ul class="menu h-full overflow-x-auto fixed p-4 bg-slate-900 text-white">
              <label for="my-drawer" class="text-4xl font-bold text-center mb-3 bg-red-700 p-3 rounded-md"><img class="inline-block -mt-2 h-10 border-2 border-white rounded" src="{{ asset('img/logo-tangspor.png') }}" alt="logo-tangspor.png"> ADUAN MASYARAKAT!</label>
            <!-- Sidebar content here -->
            <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/"><div class="w-5 mr-1"><i class="fa-solid fa-house"></i></div> Beranda</a></li>
            
            {{-- @if (Auth::check())
              <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/pengaduan"><div class="w-5 mr-1"><i class="fa-solid fa-user-pen"></i></div> Pengaduan Anda</a></li>
            @endif --}}

            <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/tentang-kami"><div class="w-5 mr-1"><i class="fa-solid fa-circle-info"></i></div> Tentang Kami</a></li>

            @if ((Auth::user()->lvl ?? NULL) == 'admin' || (Auth::user()->lvl ?? NULL) == 'petugas')
              <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/admin/seluruh-pengaduan"><div class="w-5 mr-1"><i class="fa-solid fa-comments"></i></div> Seluruh Pengaduan</a></li>
            @endif

            @if ((Auth::user()->lvl ?? NULL) == 'admin')
              {{-- <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/admin/user"><div class="w-5 mr-1"><i class="fa-solid fa-users"></i></div> Rekapitulasi Pengguna</a></li> --}}
              <li class="border-2 border-slate-700 my-1 rounded-md hover:bg-slate-700 transition"><a href="/admin/kecamatan"><div class="w-5 mr-1"><i class="fa-solid fa-location-dot"></i></div> Puskesmas</a></li>
            @endif
          </ul>
        </div>
      </div>
</body>
</html>