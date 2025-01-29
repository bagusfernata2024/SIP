<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            border: 10px solid #007BFF;
            padding: 20px;
            border-radius: 15px;
        }
        h1 {
            font-size: 50px;
            color: #007BFF;
        }
        p {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SERTIFIKAT MAGANG</h1>
        <p>Dengan ini menyatakan bahwa:</p>
        <h2><?= $peserta['nama']; ?></h2>
        <p>Telah menyelesaikan program magang di</p>
        <h3><?= $peserta['instansi']; ?></h3>
        <p>Periode: <?= date('d M Y', strtotime($peserta['tanggal1'])); ?> - <?= date('d M Y', strtotime($peserta['tanggal2'])); ?></p>
        <br>
        <p>Terima kasih atas kontribusinya.</p>
        <br><br>
        <p><strong>Direktur</strong></p>
        <p>(Nama Direktur)</p>
    </div>
</body>
</html>
