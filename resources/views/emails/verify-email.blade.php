<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email UNMARIS</title>
    <style>
        /* Reset */
        body { margin: 0; padding: 0; background-color: #FEF08A; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        
        /* Container Neo-Brutalist */
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border: 4px solid #000000;
            box-shadow: 10px 10px 0px #1E3A8A; /* Shadow Biru */
            overflow: hidden;
            border-radius: 0; /* Kotak tegas */
        }

        /* Header */
        .header {
            background-color: #1E3A8A;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #000000;
        }
        .header h1 {
            color: #FACC15; /* Kuning */
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 0px #000000;
        }

        /* Body */
        .content {
            padding: 40px 30px;
            color: #000000;
            text-align: center;
        }
        .greeting {
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #333;
        }

        /* Button */
        .btn-container {
            margin: 30px 0;
        }
        .btn {
            background-color: #FACC15;
            color: #000000;
            font-size: 18px;
            font-weight: 900;
            text-decoration: none;
            padding: 15px 40px;
            border: 3px solid #000000;
            box-shadow: 5px 5px 0px #000000;
            display: inline-block;
            text-transform: uppercase;
            transition: all 0.2s;
        }
        .btn:hover {
            box-shadow: 2px 2px 0px #000000;
            transform: translate(3px, 3px);
        }

        /* Warning Box */
        .warning {
            background-color: #fee2e2;
            border: 2px dashed #ef4444;
            padding: 15px;
            font-size: 14px;
            margin-top: 30px;
            text-align: left;
        }

        /* Footer */
        .footer {
            background-color: #000000;
            color: #FACC15;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .link-fallback {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>PMB UNMARIS</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Halo, {{ $user->name }}! 👋</div>
            
            <p class="message">
                Terima kasih sudah mendaftar di Universitas Stella Maris Sumba. 
                Tinggal satu langkah lagi untuk mengaktifkan akunmu. Silakan klik tombol di bawah ini untuk memverifikasi email.
            </p>

            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Verifikasi Sekarang 🚀</a>
            </div>

            <div class="warning">
                <strong>⚠️ PENTING:</strong><br>
                Jika Anda tidak merasa mendaftar di PMB UNMARIS, abaikan email ini. Akun yang tidak diverifikasi dalam 24 jam akan dihapus otomatis.
            </div>

            <div class="link-fallback">
                <p>Jika tombol di atas error, copy link ini ke browser:</p>
                <a href="{{ $url }}">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Universitas Stella Maris Sumba<br>
            Masa Depan Cerah Dimulai Di Sini.
        </div>
    </div>
</body>
</html>