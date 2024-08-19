<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TanggapanController;
use App\Http\Controllers\UsersController;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Beranda
Route::get('/', [HomeController::class, 'index']);

// Tentang
Route::get('/tentang-kami', [HomeController::class, 'about']);

// Autentikasi
Route::get('/sign-out', [Controller::class, 'logout'])->middleware('loginCheck');
Route::post('/sign-in', [Controller::class, 'login'])->middleware('alreadyLogin');
Route::post('/sign-up', [Controller::class, 'register'])->middleware('alreadyLogin');

// Pengaduan Umum
Route::get('/pengaduan', [PengaduanController::class, 'index'])->middleware('loginCheck');
Route::post('/pengaduan/create', [PengaduanController::class, 'store']);

Route::post('/pengaduan/delete/{id}', [PengaduanController::class, 'destroy']);

Route::get('/pengaduan/me/{id}', [PengaduanController::class, 'show'])->middleware('loginCheck');
Route::get('/pengaduan-all', [PengaduanController::class, 'show_all'])->middleware('loginCheck');
Route::get('/pengaduan-belum-diproses', [PengaduanController::class, 'show_belum'])->middleware('loginCheck');
Route::get('/pengaduan-sedang-diproses', [PengaduanController::class, 'show_sedang'])->middleware('loginCheck');
Route::get('/pengaduan-telah-diproses', [PengaduanController::class, 'show_telah'])->middleware('loginCheck');

Route::post('/pengaduan/me/{id}/send-tanggapan', [PengaduanController::class, 'send']);
Route::post('/pengaduan/me/{id}/edit-status', [PengaduanController::class, 'update'])->middleware('petugasAdmin');
Route::get('/pengaduan/me/{id}/delete-tanggapan/{id_tanggapan}', [TanggapanController::class, 'destroy']);

// Akun
Route::get('/akun', [UsersController::class, 'index'])->middleware('loginCheck');
Route::post('/akun/update', [UsersController::class, 'update'])->middleware('loginCheck');

// Pengaduan Admin & Petugas
Route::get('/admin/seluruh-pengaduan', [adminController::class, 'index'])->middleware('petugasAdmin');
Route::get('/admin/pengaduan-belum-diproses', [adminController::class, 'show_belum'])->middleware('petugasAdmin');
Route::get('/admin/pengaduan-sedang-diproses', [adminController::class, 'show_sedang'])->middleware('petugasAdmin');
Route::get('/admin/pengaduan-selesai', [adminController::class, 'show_telah'])->middleware('petugasAdmin');

Route::get('/admin/seluruh-pengaduan/export-excel', [adminController::class, 'export'])->middleware('isAdmin');
Route::get('/admin/pengaduan-belum-diproses/export-excel', [adminController::class, 'export_belum'])->middleware('isAdmin');
Route::get('/admin/pengaduan-sedang-diproses/export-excel', [adminController::class, 'export_sedang'])->middleware('isAdmin');
Route::get('/admin/pengaduan-selesai/export-excel', [adminController::class, 'export_selesai'])->middleware('isAdmin');
Route::get('/admin/pengaduan/export-perbulan', [adminController::class, 'exportPerBulan'])->name('pengaduan.exportPerBulan');

// Rekapitulasi Pengguna
Route::get('/admin/user', [UsersController::class, 'show'])->middleware('isAdmin');
Route::get('/admin/user/administrator', [UsersController::class, 'show_admin'])->middleware('isAdmin');
Route::get('/admin/user/petugas', [UsersController::class, 'show_petugas'])->middleware('isAdmin');
Route::get('/admin/user/masyarakat', [UsersController::class, 'show_masyarakat'])->middleware('isAdmin');

Route::get('/admin/user/export', [UsersController::class, 'export_users'])->middleware('isAdmin');

Route::get('/admin/user/detail/{nik}', [UsersController::class, 'detail'])->middleware('petugasAdmin');
Route::get('/admin/user/detail/{nik}/export', [UsersController::class, 'export'])->middleware('petugasAdmin');

Route::post('/admin/user/create', [adminController::class, 'store'])->middleware('isAdmin');
Route::post('/admin/user/edit/{id}', [adminController::class, 'update'])->middleware('isAdmin');
Route::post('/admin/user/aktivasi/{id}', [UsersController::class, 'active'])->middleware('isAdmin');

// Dashboard Admin
Route::get('/admin/kecamatan', [adminController::class, 'kecamatan'])->middleware('isAdmin');
Route::post('/admin/kecamatan/create', [adminController::class, 'kecamatanCreate'])->middleware('isAdmin');
Route::post('/admin/kecamatan/edit/{id}', [adminController::class, 'kecamatanEdit'])->middleware('isAdmin');
Route::post('/admin/kecamatan/delete/{id}', [adminController::class, 'kecamatanDelete'])->middleware('isAdmin');
Route::resource('users', UsersController::class);

// Route::get('/dashboard', function () {
//     return view('dashboard/all')->with([
//         'pengaduan' => Pengaduan::all(),
//     ]);
// })->middleware(['adminOnly'])->name('dashboard');

// Route::middleware('adminOnly')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
