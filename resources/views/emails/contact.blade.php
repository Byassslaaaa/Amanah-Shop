<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak Website Desa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 2px solid #16a34a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #16a34a;
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            background-color: #f0fdf4;
            border-left: 4px solid #16a34a;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .info-label {
            font-weight: bold;
            color: #166534;
            margin-bottom: 5px;
        }
        .message-content {
            background-color: #fafafa;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 14px;
            color: #6b7280;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏛️ Pesan dari Website Desa</h1>
            <p style="margin: 0; color: #6b7280;">Pesan kontak baru dari warga melalui website BUMDes Marketplace</p>
        </div>

        <!-- Sender Information -->
        <div class="info-section">
            <div class="info-label">👤 Informasi Pengirim:</div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px 0; font-weight: bold; width: 100px;">Nama:</td>
                    <td style="padding: 5px 0;">{{ $name }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Email:</td>
                    <td style="padding: 5px 0;">{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Subjek:</td>
                    <td style="padding: 5px 0;">{{ $subject }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Waktu:</td>
                    <td style="padding: 5px 0;">{{ now()->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        <!-- Message Content -->
        <div>
            <div class="info-label">💬 Isi Pesan:</div>
            <div class="message-content">
                {!! nl2br(e($messageContent)) !!}
            </div>
        </div>

        <!-- Reply Instructions -->
        <div class="warning">
            <strong>📧 Cara Membalas:</strong><br>
            Untuk membalas pesan ini, cukup klik "Reply" pada email ini atau kirim balasan langsung ke alamat: <strong>{{ $email }}</strong>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>BUMDes Marketplace - Sistem Kontak Website Desa</strong></p>
            <p style="margin: 10px 0 0 0;">
                Email ini dikirim otomatis dari sistem kontak website desa. 
                Jika Anda memiliki pertanyaan teknis, hubungi administrator website.
            </p>
            <hr style="margin: 15px 0; border: none; height: 1px; background-color: #e5e7eb;">
            <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                Dikirim pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</body>
</html>