<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Mengirim notifikasi yang diberikan.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // 1. Cek Method
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        // 2. Validasi Token
        $token = env('WA_API_TOKEN');
        if (empty($token)) {
            // Kita throw Exception agar Job di terminal menjadi FAILED (Merah)
            throw new \Exception('WA GAGAL: WA_API_TOKEN belum diisi di .env');
        }

        // 3. Ambil Data
        $data = $notification->toWhatsApp($notifiable);
        $message = $data['message'] ?? '';
        
        // Prioritas target: Route testing > Config notifikasi
        $target = $notifiable->routeNotificationFor('whatsapp') 
               ?? $data['target'] 
               ?? null;

        if (empty($target)) {
            // Warning saja, jangan fail job
            Log::warning('WA SKIP: Target nomor HP kosong.');
            return;
        }

        // 4. Kirim ke Fonnte
        try {
            Log::info("WA Mengirim ke $target...");

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            $responseData = $response->json();

            // CEK ERROR DARI FONNTE (PENTING)
            // Fonnte sering return HTTP 200 (OK) padahal statusnya gagal (misal: device disconnect)
            if (isset($responseData['status']) && $responseData['status'] == false) {
                $reason = $responseData['reason'] ?? 'Unknown reason';
                // Lempar error agar muncul di terminal queue
                throw new \Exception("FONNTE MENOLAK: $reason");
            }

            // Jika request HTTP gagal total (404, 500, dll)
            if ($response->failed()) {
                throw new \Exception("HTTP ERROR: " . $response->body());
            }

            Log::info("WA SUKSES: " . $response->body());

        } catch (\Exception $e) {
            // Catat log lalu lempar ulang errornya agar worker tahu ini gagal
            Log::error('WA EXCEPTION: ' . $e->getMessage());
            throw $e; 
        }
    }
}