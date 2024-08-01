<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\mahasiswaluluscontroller;
use App\Http\Controllers\mahasiswaaktifcontroller;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('kerangka.master');
// });

Route::middleware(['auth','shareMahasiswaData'])->group(function () {

    Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::match(['get', 'post'], '/dashboard', [DashboardController::class, 'combined'])->name('dashboard');
   
    Route::get('/jumlah_mahasiswa_lulus_kelamin', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_kelamin');
    Route::get('jumlah_mahasiswa_lulus_kelamin', [mahasiswaluluscontroller::class, 'jumlah_mahasiswa_kelamin'])->name('jumlah_mahasiswa_lulus_kelamin');
    
    Route::get('/jumlah_mahasiswa_lulus_jurusan', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_jurusan');
    Route::get('jumlah_mahasiswa_lulus_jurusan', [mahasiswaluluscontroller::class, 'jurusan'])->name('jumlah_mahasiswa_lulus_jurusan');
   
    Route::get('/jumlah_mahasiswa_lulus_nilai_bahasa', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_nilai_bahasa');
    Route::get('jumlah_mahasiswa_lulus_nilai_bahasa', [mahasiswaluluscontroller::class, 'nilaibahasa'])->name('jumlah_mahasiswa_lulus_nilai_bahasa');
    
    Route::get('/jumlah_mahasiswa_lulus_kategori_ipk', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_kategori_ipk');
    Route::get('jumlah_mahasiswa_lulus_kategori_ipk', [mahasiswaluluscontroller::class, 'kategoriipk'])->name('jumlah_mahasiswa_lulus_kategori_ipk');

    Route::get('/jumlah_mahasiswa_lulus_kategori_lulus', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_kategori_lulus');
    Route::get('jumlah_mahasiswa_lulus_kategori_lulus', [mahasiswaluluscontroller::class, 'kategorilulus'])->name('jumlah_mahasiswa_lulus_kategori_lulus');

    Route::get('/jumlah_mahasiswa_lulus_tahun_lulus', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_tahun_lulus');
    Route::get('jumlah_mahasiswa_lulus_tahun_lulus', [mahasiswaluluscontroller::class, 'tahunlulus'])->name('jumlah_mahasiswa_lulus_tahun_lulus');
    
    Route::get('/jumlah_mahasiswa_lulus_jurusan_sekolah', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_jurusan_sekolah');
    Route::get('jumlah_mahasiswa_lulus_jurusan_sekolah', [mahasiswaluluscontroller::class, 'jurusansekolah'])->name('jumlah_mahasiswa_lulus_jurusan_sekolah');

    Route::get('/jumlah_mahasiswa_lulus_jenis_sekolah', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_jenis_sekolah');
    Route::get('jumlah_mahasiswa_lulus_jenis_sekolah', [mahasiswaluluscontroller::class, 'jenissekolah'])->name('jumlah_mahasiswa_lulus_jenis_sekolah');

    Route::get('/jumlah_mahasiswa_lulus_jenis_seleksi', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_jenis_seleksi');
    Route::get('jumlah_mahasiswa_lulus_jenis_seleksi', [mahasiswaluluscontroller::class, 'jenisseleksi'])->name('jumlah_mahasiswa_lulus_jenis_seleksi');

    Route::get('/jumlah_mahasiswa_lulus_kota', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_kota');
    Route::get('jumlah_mahasiswa_lulus_kota', [mahasiswaluluscontroller::class, 'kota'])->name('jumlah_mahasiswa_lulus_kota');

    Route::get('/jumlah_mahasiswa_lulus_kategori_kota', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_kategori_kota');
    Route::get('jumlah_mahasiswa_lulus_kategori_kota', [mahasiswaluluscontroller::class, 'kategorikota'])->name('jumlah_mahasiswa_lulus_kategori_kota');

    Route::get('/jumlah_mahasiswa_lulus_provinsi', [mahasiswaluluscontroller::class, 'index'])->name('jumlah_mahasiswa_lulus_provinsi');
    Route::get('jumlah_mahasiswa_lulus_provinsi', [mahasiswaluluscontroller::class, 'provinsi'])->name('jumlah_mahasiswa_lulus_provinsi');

    Route::get('/jumlah_mahasiswa_aktif_kelamin', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_kelamin');
    Route::get('jumlah_mahasiswa_aktif_kelamin', [mahasiswaaktifcontroller::class, 'jumlah_mahasiswa_kelamin'])->name('jumlah_mahasiswa_aktif_kelamin');
    
    Route::get('/jumlah_mahasiswa_aktif_jurusan', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_jurusan');
    Route::get('jumlah_mahasiswa_aktif_jurusan', [mahasiswaaktifcontroller::class, 'jurusan'])->name('jumlah_mahasiswa_aktif_jurusan');
   
    Route::get('/jumlah_mahasiswa_aktif_nilai_bahasa', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_nilai_bahasa');
    Route::get('jumlah_mahasiswa_aktif_nilai_bahasa', [mahasiswaaktifcontroller::class, 'nilaibahasa'])->name('jumlah_mahasiswa_aktif_nilai_bahasa');
    
    Route::get('/jumlah_mahasiswa_aktif_kategori_ipk', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_kategori_ipk');
    Route::get('jumlah_mahasiswa_aktif_kategori_ipk', [mahasiswaaktifcontroller::class, 'kategoriipk'])->name('jumlah_mahasiswa_aktif_kategori_ipk');

    Route::get('/jumlah_mahasiswa_aktif_jurusan_sekolah', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_jurusan_sekolah');
    Route::get('jumlah_mahasiswa_aktif_jurusan_sekolah', [mahasiswaaktifcontroller::class, 'jumlah_mahasiswa_aktif_jurusansekolah'])->name('jumlah_mahasiswa_aktif_jurusan_sekolah');

    Route::get('/jumlah_mahasiswa_aktif_jenis_sekolah', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_jenis_sekolah');
    Route::get('jumlah_mahasiswa_aktif_jenis_sekolah', [mahasiswaaktifcontroller::class, 'jenissekolah'])->name('jumlah_mahasiswa_aktif_jenis_sekolah');

    Route::get('/jumlah_mahasiswa_aktif_jenis_seleksi', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_jenis_seleksi');
    Route::get('jumlah_mahasiswa_aktif_jenis_seleksi', [mahasiswaaktifcontroller::class, 'jenisseleksi'])->name('jumlah_mahasiswa_aktif_jenis_seleksi');

    Route::get('/jumlah_mahasiswa_aktif_kota', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_kota');
    Route::get('jumlah_mahasiswa_aktif_kota', [mahasiswaaktifcontroller::class, 'kota'])->name('jumlah_mahasiswa_aktif_kota');

    Route::get('/jumlah_mahasiswa_aktif_kategori_kota', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_kategori_kota');
    Route::get('jumlah_mahasiswa_aktif_kategori_kota', [mahasiswaaktifcontroller::class, 'kategorikota'])->name('jumlah_mahasiswa_aktif_kategori_kota');

    Route::get('/jumlah_mahasiswa_aktif_provinsi', [mahasiswaaktifcontroller::class, 'index'])->name('jumlah_mahasiswa_aktif_provinsi');
    Route::get('jumlah_mahasiswa_aktif_provinsi', [mahasiswaaktifcontroller::class, 'provinsi'])->name('jumlah_mahasiswa_aktif_provinsi');

    Route::get('/test', [TestController::class, 'index'])->name('test');
    Route::get('/3test', [TestController::class, 'iindex'])->name('3test');
    Route::get('/sidebar', [SidebarController::class, 'index'])->name('sidebar.index');
    // Route::get('/sidebar', [SidebarController::class, 'nama'])->name('sidebar.nama');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});


// Rute lainnya

Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/regist', [RegisterController::class, 'store'])->name('register.store');

// Rute debug

