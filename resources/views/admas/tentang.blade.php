@extends('layouts/base')

@section('body')


<div class="hero min-h-[400px] bg-slate-300 relative">
  
  <div class="bg-slate-400 w-10 h-80 absolute right-0 bottom-0"></div>
  <div class="bg-slate-400 w-10 h-60 absolute right-20 bottom-0"></div>
  <div class="bg-slate-400 w-10 h-40 absolute right-40 bottom-0"></div>
  <div class="bg-slate-400 w-10 h-20 absolute right-60 bottom-0"></div>
  <div class="bg-slate-400 w-10 h-10 absolute right-80 bottom-0"></div>

  <div class="hero-content text-center">
    <div class="max-w-md bg-white border border-black p-3 rounded-xl relative after:block after:h-full after:w-full after:bg-slate-200 after:-z-10 after:border after:top-4 after:border-black after:absolute after:rounded-xl">
            <h1 class="text-5xl font-bold bg-green-800 p-3 rounded-md text-white">Tentang Kami</h1>
              <p class="py-6">Aduan Masyarakat DINKES Melapor Dibuat pada tahun 2024.</p>
          </div>
        </div>
      </div>
      
      <div class="hover:top-28 hover:-rotate-3 transition-all  bg-slate-400 w-80 rounded-xl p-5 border-2 shadow-lg shadow-slate-600 border-slate-800 overflow-hidden hidden lg:block relative sm:left-8 sm:top-32 sm:absolute">
        <div class="bg-white absolute top-0 w-full left-0 flex z-50">
          
          <p class="font-bold p-3"><span class="bg-slate-800 p-1 h-1 w-5 rounded-full"></span> Group Warga DINKES BENGKAYANG</p>
        </div>
        <div class="chat chat-start">
          <div class="chat-image avatar">
            <div class="w-10 rounded-full">
              <img src="{{ asset('/img/me.jpg') }}" />
            </div>
          </div>
          <div class="chat-header">
          DINKES
          </div>
          <div class="chat-bubble">Semoga dengan website ini warga Kabupaten Bengkayang menjadi lebih makmur!!!</div>
        </div>
      
        <div class="">
          <div class="chat chat-end">
            <div class="chat-image avatar">
              <div class="w-10 rounded-full">
                <img src="{{ asset('/img/me.jpg') }}" />
              </div>
            </div>
            <div class="chat-header">
              Warga 1
            </div>
            <div class="chat-bubble">Setuju!</div>
          </div>
          <div class="chat chat-end">
            <div class="chat-image avatar">
              <div class="w-10 rounded-full">
                <img src="{{ asset('/img/me.jpg') }}" />
              </div>
            </div>
            <div class="chat-header">
              Warga 2
            </div>
            <div class="chat-bubble">Setuju!</div>
          </div>
          <div class="chat chat-end">
            <div class="chat-image avatar">
              <div class="w-10 rounded-full">
                <img src="{{ asset('/img/me.jpg') }}" />
              </div>
            </div>
            <div class="chat-header">
              Warga 3
            </div>
            <div class="chat-bubble">Setuju!</div>
          </div>
        </div>
      
        <div class="absolute bottom-0 left-0 w-full">
          <div class="flex gap-3 bg-white ">
            <input class="w-full rounded-md border-0" type="text" value="Setuju!" disabled>
            <button class="pointer-events-none btn-circle bg-blue-500 text-white"><i class="fa-solid fa-paper-plane"></i></button>
          </div>
        </div>
      
      </div>

  

  <div class="flex py-3 gap-3 bg-slate-200">

    <div class="rounded-r-xl border-t-4 border-r-4 border-b-4 border-dashed border-slate-500 h-32 w-96 overflow-hidden">
    </div>

    <div class="sm:mt-10">
      <h3 class="font-bold text-4xl ">Misi Kami</h3>
      <p class="text-md">mempermudah warga KABUPATEN BENGKAYANG dalam melaporkan permasalahan yang melibatkan kenyamanan dan keamanan publik.</p>
    </div>
  
  </div>

  <div class="px-5 sm:px-0 py-16 flex flex-col sm:flex-row gap-7 sm:gap-20">
    <div class="sm:ml-5">
      <h3 class="text-4xl font-bold">Kontak Kami</h3>
      <div class="mockup-code w-fit">
        <pre data-prefix="1"><code>ISB</code></pre> 
        <pre data-prefix="2"><code>ISB</code></pre> 
      </div>
    </div>

    <div>
      <h3 class="text-4xl font-bold mb-3">Medsos Kami</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">

        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-instagram"></i></span> @dinkeskabbengkayang.id</p>
        </div>
        
        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-facebook"></i></span> @dinkeskabbengkayang.id</p>
        </div>
        
        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-twitter"></i></span> @dinkeskabbengkayang.id</p>
        </div>
        
        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-linkedin"></i></span> @dinkeskabbengkayang.id</p>
        </div>
        
        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-github"></i></span> @dinkeskabbengkayang.id</p>
        </div>
        
        <div>
          <p class="text-xl"><span class="w-5"><i class="fa-brands fa-twitch"></i></span> @dinkeskabbengkayang.id</p>
        </div>

      </div>
    </div>

    <div class="hidden lg:block relative ml-auto w-96 h-40 border-4 border-r-0 border-slate-500 rounded-l-xl border-dashed">
      <img class="h-52 -mt-7 mx-auto rotate-12 border-4 border-dashed bg-white p-1 border-slate-500 rounded-md" src="{{ asset('/img/Logo.png') }}" alt="logo-tangspor.png">
    </div>

  </div>

   
    <div>
        <div class="hero bg-gradient-to-b from-gray-100 to-slate-600">
            <div class="hero-content flex-col lg:flex-row text-white">
              {{-- <img src="{{ asset('img/me.jpg') }}" class="max-w-[350px] rounded-lg shadow-2xl" /> --}}
              <div>
                <h1 class="text-5xl font-bold">Institut Shanti Buana</h1>
                <p class="py-6">Dinkes Creator</p>
              </div>
            </div>
          </div>
    </div>
    

@endsection