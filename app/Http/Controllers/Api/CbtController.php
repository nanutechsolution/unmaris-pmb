<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pendaftar;
use App\Models\UjianPeserta;
use App\Models\UjianSoal;
use App\Models\UjianJawaban;
use App\Models\UjianPilihan;

class CbtController extends Controller
{
    /**
     * 1. API LOGIN PESERTA UJIAN
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Cek apakah user adalah pendaftar (camaba)
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        if (!$pendaftar) {
            return response()->json(['message' => 'Anda belum terdaftar sebagai calon mahasiswa.'], 403);
        }

        // (Opsional) Cek status pembayaran sebelum ujian
        if ($pendaftar->status_pembayaran !== 'lunas') {
            return response()->json(['message' => 'Harap selesaikan administrasi pembayaran sebelum mengikuti ujian.'], 403);
        }

        // Buat token Sanctum untuk React
        $token = $user->createToken('cbt-token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'token' => $token,
            'user' => [
                'nama' => $user->name,
                'email' => $user->email,
                'pendaftar_id' => $pendaftar->id
            ]
        ], 200);
    }

    /**
     * 2. API AMBIL DAFTAR SOAL (Tanpa Kunci Jawaban)
     */
    public function getSoal(Request $request)
    {
        $user = $request->user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Cari sesi ujian peserta (asumsi admin sudah membuatkan sesi di tabel ujian_pesertas)
        // Atau Anda bisa men-generate otomatis di sini jika belum ada
        $peserta = UjianPeserta::where('pendaftar_id', $pendaftar->id)
                    ->whereIn('status', ['belum', 'mengerjakan'])
                    ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Tidak ada jadwal ujian aktif untuk Anda.'], 404);
        }

        // Jika baru pertama kali klik mulai, update status dan waktu
        if ($peserta->status === 'belum') {
            $peserta->update([
                'status' => 'mengerjakan',
                'waktu_mulai' => now(),
                // Misal durasi ujian diambil dari relasi tabel ujian_paket
                'waktu_selesai' => now()->addMinutes($peserta->paket->durasi_menit ?? 60)
            ]);
        }

        // Ambil soal berdasarkan paket ujian yang diatur untuk peserta ini
        // PERHATIAN: Jangan kirim kolom `is_benar` ke frontend React untuk menghindari kecurangan!
        $soal = UjianSoal::where('ujian_paket_id', $peserta->ujian_paket_id)
            ->with(['pilihans' => function($query) {
                // Hanya kirim id dan teks pilihan
                $query->select('id', 'ujian_soal_id', 'teks_pilihan');
            }])
            ->get();

        // Ambil jawaban yang sudah pernah dipilih (untuk me-restore status jika peserta ter-refresh)
        $jawabanTersimpan = UjianJawaban::where('ujian_peserta_id', $peserta->id)
            ->pluck('ujian_pilihan_id', 'ujian_soal_id');

        return response()->json([
            'ujian_peserta_id' => $peserta->id,
            'waktu_selesai' => $peserta->waktu_selesai,
            'soal' => $soal,
            'jawaban_tersimpan' => $jawabanTersimpan
        ], 200);
    }

    /**
     * 3. API SIMPAN JAWABAN (Auto-save setiap kali peserta klik opsi)
     */
    public function simpanJawaban(Request $request)
    {
        $request->validate([
            'ujian_peserta_id' => 'required|exists:ujian_pesertas,id',
            'ujian_soal_id' => 'required|exists:ujian_soals,id',
            'ujian_pilihan_id' => 'required|exists:ujian_pilihans,id',
        ]);

        // Cek apakah waktu ujian sudah habis
        $peserta = UjianPeserta::find($request->ujian_peserta_id);
        if (now()->greaterThan($peserta->waktu_selesai)) {
            return response()->json(['message' => 'Waktu ujian sudah habis!'], 403);
        }

        // Simpan atau update jawaban (jika peserta mengubah jawaban)
        UjianJawaban::updateOrCreate(
            [
                'ujian_peserta_id' => $request->ujian_peserta_id,
                'ujian_soal_id' => $request->ujian_soal_id
            ],
            [
                'ujian_pilihan_id' => $request->ujian_pilihan_id
            ]
        );

        return response()->json(['message' => 'Jawaban disimpan'], 200);
    }

    /**
     * 4. API SELESAI UJIAN (Hitung Nilai & Sinkronisasi ke PMB)
     */
    public function selesaiUjian(Request $request)
    {
        $request->validate([
            'ujian_peserta_id' => 'required|exists:ujian_pesertas,id'
        ]);

        $peserta = UjianPeserta::find($request->ujian_peserta_id);

        if ($peserta->status === 'selesai') {
            return response()->json(['message' => 'Ujian ini sudah diselesaikan sebelumnya.'], 400);
        }

        // Ambil semua jawaban peserta
        $jawabans = UjianJawaban::where('ujian_peserta_id', $peserta->id)->get();
        $totalSoal = UjianSoal::where('ujian_paket_id', $peserta->ujian_paket_id)->count();
        $jawabanBenar = 0;

        foreach ($jawabans as $jawaban) {
            $pilihan = UjianPilihan::find($jawaban->ujian_pilihan_id);
            if ($pilihan && $pilihan->is_benar) {
                $jawabanBenar++;
            }
        }

        // Hitung skala nilai 0-100
        $skorAkhir = ($totalSoal > 0) ? round(($jawabanBenar / $totalSoal) * 100) : 0;

        // 1. Update status di tabel ujian CBT
        $peserta->update([
            'status' => 'selesai',
            'skor_akhir' => $skorAkhir
        ]);

        // 2. SINKRONISASI: Update kolom di tabel PMB yang sudah ada
        $pendaftar = Pendaftar::find($peserta->pendaftar_id);
        if ($pendaftar) {
            $pendaftar->update([
                'nilai_ujian' => $skorAkhir,
                'catatan_penguji' => "Selesai CBT otomatis. Skor: $skorAkhir ($jawabanBenar benar dari $totalSoal soal)."
            ]);
        }

        // (Opsional) Hapus token agar user otomatis logout dari sistem CBT
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Ujian selesai, nilai berhasil disimpan.',
            'skor' => $skorAkhir
        ], 200);
    }
}