<div class="navbar bg-red-800 text-white sticky top-0 z-10">
    <div class="navbar-start">

      <label for="my-drawer" class="btn btn-square btn-outline border-white text-white" >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
      </label>
    </div>
    <div class="navbar-center">
      <a href="/" class="btn btn-ghost normal-case text-xl">TANGSPOR!</a>
    </div>
    <div class="navbar-end gap-1">
      @if (Auth::check())
        <div class="dropdown dropdown-bottom dropdown-end mt-1 sm:mt-0">
          <label tabindex="0" class="btn btn-outline border-white text-white w-16 sm:w-[340px] relative">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg> --}}
            <i class="fa-solid fa-user mr-2 absolute left-3"></i><span class="hidden sm:inline max-w-[100px] sm:max-w-[250px] truncate">{{ Auth::user()->name }}</span><i class="ml-2 fa-solid fa-caret-down absolute right-3"></i>
          </label>
          <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 text-slate-700">
            <li><a href="/akun"><i class="fa-solid fa-user"></i> Profil</a></li>
            <li><a href="/sign-out"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
          </ul>
        </div>
      @else
      <!-- The button to open modal -->
        <label for='modal-login' class="w-20 btn btn-outline text-white border-white">Login</label>
        <label for='modal-register' class="w-20 btn btn-outline text-white border-white">Register</label>

        <!-- Put this part before </body> tag -->

        {{-- Modal Login --}}
        <input type="checkbox" id="modal-login" class="modal-toggle" />
        <label class="modal text-slate-700" for="modal-login">
          <label class="modal-box relative" for="">
            <form action="/sign-in" method="POST">
              @csrf
              <p class="relative font-bold text-2xl mb-5 text-center bg-red-700 text-white rounded-md py-2" for="">Login <label for="modal-login" class="btn btn-sm btn-circle absolute right-0 mr-2">✕</label></p>
                <div class="flex flex-col gap-3">
                  <div>
                      <label for="login_username">Username</label>
                      <input class="rounded-md w-full" type="text" name="login_username" id="login_username">
                  </div>
                  <div>
                      <label for="login_password">Password</label>
                      <input class="rounded-md w-full" type="password" name="login_password" id="login_password">
                  </div>
                  <div>
                      <button type="submit" class="btn w-full">Login</button>
                  </div>
                </div>
              </form>
          </label>
        </label>

        {{-- Modal Register --}}
        <input type="checkbox" id="modal-register" class="modal-toggle" />
        <label class="modal text-slate-700" for="modal-register">
          <label class="modal-box relative" for="">

            <form action="/sign-up" method="POST">
              @csrf
              <p class="relative font-bold text-2xl mb-5 text-center bg-red-700 text-white rounded-md py-2" for="">Register <label for="modal-register" class="btn btn-sm btn-circle absolute right-0 mr-2">✕</label></p>
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
                 
                  <div>
                      <label for="register_password_confirmation">Konfirmasi Password</label>
                      <input class="rounded-md w-full" type="password" name="register_password_confirmation" id="register_password_confirmation" required>
                  </div>
                  
                  <div class="mb-5">
                      <label for="register_telp">No. Telepon</label>
                      <input class="rounded-md w-full" type="number" name="register_telp" id="register_telp" required>
                  </div>
                  
                  <div>
                    <button class="btn w-full">Simpan</button>
                  </div>
                </div>
              </form>

          </label>
        </label>

      @endif
      
    </div>
  </div>

  