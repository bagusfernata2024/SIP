<!DOCTYPE html>
<html>
<head>
    <style>
        /* Reset margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Umum */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #333;
            padding: 20px 0;
            text-align: center;
        }

        /* Judul */
        h2 {
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Table Container */
        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 60%;
            max-width: 600px;
            margin: 20px auto;
        }

        /* Style tabel */
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        /* Teks keamanan dan info */
        .warning {
            font-size: 14px;
            color: #d9534f;
            margin: 10px 0;
            text-align: center;
        }

        p {
            margin: 15px 0;
            font-size: 14px;
            line-height: 1.5;
            color: #555;
        }

        /* Tombol dan footer */
        .footer {
            font-size: 12px;
            color: #555;
            margin-top: 20px;
            padding: 10px 0;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <center>
        <h2 style="color:black">Informasi Akun Anda</h2>
    </center>
    <div class="container">
        <!-- Informasi Akun dalam tabel -->
        <table>
            <tr>
                <th>Username</th>
                <td><?= $username; ?></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><?= $password; ?></td>
            </tr>
        </table>

        <!-- Informasi keamanan -->
        <div class="warning">
            <strong>PERINGATAN:</strong> Jangan berikan informasi akun Anda kepada siapapun. 
            Keteledoran dalam berbagi informasi ini di luar kendali kami dapat menyebabkan risiko keamanan yang tidak diinginkan.
        </div>

        <p>
            Silakan login menggunakan informasi di atas. Jika ada pertanyaan atau kebingungan,
            hubungi kami melalui kontak yang tersedia.
        </p>
    </div>

    <!-- Footer dengan catatan -->
    <div class="footer">
        &copy; <?= date('Y'); ?> Informasi Akun Anda. Semua hak dilindungi.
    </div>
</body>
</html>
