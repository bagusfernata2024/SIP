<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Akhir Peserta Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fc;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 40px;
        }

        h1 {
            text-align: center;
            color: #4e73df;
            margin-bottom: 20px;
        }

        h2 {
            color: #4e73df;
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .card-header {
            background-color: #4e73df;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #e3e6f0;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-diri,
        .data-mentor {
            background-color: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .data-diri p,
        .data-mentor p {
            font-size: 16px;
            line-height: 1.6;
            margin: 5px 0;
        }

        .data-diri h3,
        .data-mentor h3 {
            font-size: 20px;
            color: #4e73df;
            margin-bottom: 15px;
        }

        /* Styling untuk dua kolom pada tampilan print */
        @media print {
            body {
                margin: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .card,
            .card-body {
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
            }

            .data-diri,
            .data-mentor {
                border: none;
                padding: 0;
                margin-bottom: 10px;
            }

            .card-header {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Nilai Akhir</h1>
        <p class="text-center">Halaman ini menampilkan nilai akhir yang diberikan oleh mentor kepada peserta magang selama proses magang di PGN.</p>

        <!-- Data Diri -->
        <div class="data-diri">
            <h3>Data Diri Peserta Magang</h3>
            <?php foreach ($nilai_akhir as $index => $data): ?>
                <p><strong>Nama:</strong> <?= $data->nama ?></p>
                <p><strong>NIM:</strong> <?= $data->nomor ?></p>
                <p><strong>Instansi:</strong> <?= $data->instansi ?></p>
                <p><strong>Periode Magang:</strong> <?= formatTanggalIndo($data->tanggal1) . ' - ' . formatTanggalIndo($data->tanggal2) ?></p>
                </p>
            <?php endforeach ?>
        </div>

        <!-- Data Mentor -->
        <div class="data-mentor">
            <h3>Data Mentor</h3>
            <?php foreach ($nilai_akhir as $index => $data): ?>
                <p><strong>Mentor:</strong> <?= $data->nama ?></p>
                <p><strong>NIPG:</strong> <?= $data->nipg ?></p>
                <p><strong>Subsidiaries:</strong> <?= $data->subsidiaries ?></p>
            <?php endforeach ?>
        </div>

        <div class="card">
            <div class="card-header">
                Hasil Nilai Akhir
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aspek</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nilai_akhir as $index => $nilai): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>Ketepatan Waktu</td>
                                    <td><?= $nilai->ketepatan_waktu ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 2 ?></td>
                                    <td>Sikap Kerja</td>
                                    <td><?= $nilai->sikap_kerja ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 3 ?></td>
                                    <td>Tanggungjawab</td>
                                    <td><?= $nilai->tanggung_jawab ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 4 ?></td>
                                    <td>Kehadiran</td>
                                    <td><?= $nilai->kehadiran ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 5 ?></td>
                                    <td>Kemampuan Kerja</td>
                                    <td><?= $nilai->kemampuan_kerja ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 6 ?></td>
                                    <td>Keterampilan Kerja</td>
                                    <td><?= $nilai->keterampilan_kerja ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 7 ?></td>
                                    <td>Kualitas Hasil</td>
                                    <td><?= $nilai->kualitas_hasil ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 8 ?></td>
                                    <td>Kemampuan Komunikasi</td>
                                    <td><?= $nilai->kemampuan_komunikasi ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 9 ?></td>
                                    <td>Kerjasama</td>
                                    <td><?= $nilai->kerjasama ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 10 ?></td>
                                    <td>Kerajinan</td>
                                    <td><?= $nilai->kerajinan ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 11 ?></td>
                                    <td>Percaya diri</td>
                                    <td><?= $nilai->percaya_diri ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 12 ?></td>
                                    <td>Mematuhi Aturan</td>
                                    <td><?= $nilai->mematuhi_aturan ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 13 ?></td>
                                    <td>Penampilan</td>
                                    <td><?= $nilai->penampilan ?></td>
                                </tr>
                                <tr>
                                    <td><?= $index + 14 ?></td>
                                    <td>Perilaku</td>
                                    <td><?= $nilai->perilaku ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>