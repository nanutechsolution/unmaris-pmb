<?php

use App\Http\Controllers\Api\CbtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint untuk Login Aplikasi Ujian (React)
Route::post('/cbt/login', [CbtController::class, 'login']);

// Endpoint yang butuh Token (Harus Login Dulu)
Route::middleware('auth:sanctum')->prefix('cbt')->group(function () {
    Route::get('/soal', [CbtController::class, 'getSoal']); // Ambil daftar soal
    Route::post('/jawaban', [CbtController::class, 'simpanJawaban']); // Simpan tiap kali peserta klik opsi
    Route::post('/selesai', [CbtController::class, 'selesaiUjian']); // Hitung nilai dan update ke pendaftars
});