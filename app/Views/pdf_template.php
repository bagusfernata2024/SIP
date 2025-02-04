
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .content {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            text-align: center;
            color: black;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Menampilkan Gambar Sertifikat -->
        <img src="<?= base_url('templates/sertifikat_template.jpg') ?>" style="width: 100%; height: auto;">

        <!-- Konten di atas sertifikat -->
        <div class="content">
            <h1><?= $title ?></h1>
            <p>Nama Penerima: <b>Ahmad</b></p>
            <p>Tanggal: <b><?= date('d F Y') ?></b></p>
        </div>
    </div>
</body>

</html>