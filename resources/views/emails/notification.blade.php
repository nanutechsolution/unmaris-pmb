<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 3px solid #000000; border-radius: 12px; overflow: hidden; margin-top: 20px; box-shadow: 8px 8px 0px #FACC15; }
        .header { background-color: #1E3A8A; padding: 20px; text-align: center; color: #ffffff; border-bottom: 3px solid #000000; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .greeting { font-weight: bold; font-size: 18px; margin-bottom: 20px; }
        .message-box { padding: 15px; border-left: 5px solid; background-color: #f9fafb; margin-bottom: 25px; }
        .type-success { border-color: #16A34A; background-color: #f0fdf4; }
        .type-warning { border-color: #FACC15; background-color: #fefce8; }
        .type-error { border-color: #DC2626; background-color: #fef2f2; }
        .type-info { border-color: #1E3A8A; background-color: #eff6ff; }
        
        .btn-container { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; background-color: #FACC15; color: #1E3A8A; font-weight: bold; text-decoration: none; padding: 12px 25px; border: 2px solid #000000; border-radius: 8px; box-shadow: 4px 4px 0px #000000; text-transform: uppercase; }
        .btn:hover { background-color: #fbbf24; transform: translate(2px, 2px); box-shadow: 2px 2px 0px #000000; }
        
        .footer { background-color: #000000; color: #6b7280; padding: 20px; text-align: center; font-size: 12px; }
        .footer a { color: #FACC15; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>PMB UNMARIS</h1>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="greeting">Halo, {{ $user->name }}! 👋</div>
            
            <p>Ada pembaruan status mengenai pendaftaran mahasiswa baru Anda:</p>

            <div class="message-box type-{{ $type }}">
                <h3 style="margin-top:0;">{{ $title }}</h3>
                <p>{{ $content }}</p>
            </div>

            @if($actionText && $actionUrl)
                <div class="btn-container">
                    <a href="{{ $actionUrl }}" class="btn">{{ $actionText }}</a>
                </div>
            @endif

            <p>Jika tombol di atas tidak berfungsi, silakan salin dan tempel tautan berikut ke browser Anda:<br>
            <small>{{ $actionUrl }}</small></p>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Universitas Stella Maris Sumba.<br>
            Jl. Soekarno Hatta No.05, Tambolaka, NTT</p>
            <p>Butuh bantuan? <a href="https://wa.me/6281234567890">Hubungi Admin</a></p>
        </div>
    </div>
</body>
</html>