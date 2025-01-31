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

                <p><strong>Nama:</strong> <?= $nilai_akhir['nama'] ?></p>
                <p><strong>NIM:</strong> <?= $nilai_akhir['nomor'] ?></p>
                <p><strong>Instansi:</strong> <?= $nilai_akhir['instansi'] ?></p>
                <p><strong>Periode Magang:</strong> <?= formatTanggalIndo($nilai_akhir['tanggal1']) . ' - ' . formatTanggalIndo($nilai_akhir['tanggal2']) ?></p>

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
                                <tr>
                                    <td>1</td>
                                    <td>Ketepatan Waktu</td>
                                    <td><?= $nilai_akhir['ketepatan_waktu'] ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Sikap Kerja</td>
                                    <td><?= $nilai_akhir['sikap_kerja'] ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Tanggungjawab</td>
                                    <td><?= $nilai_akhir['tanggung_jawab'] ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Kehadiran</td>
                                    <td><?= $nilai_akhir['kehadiran'] ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Kemampuan Kerja</td>
                                    <td><?= $nilai_akhir['kemampuan_kerja'] ?></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Keterampilan Kerja</td>
                                    <td><?= $nilai_akhir['keterampilan_kerja'] ?></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Kualitas Hasil</td>
                                    <td><?= $nilai_akhir['kualitas_hasil'] ?></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Kemampuan Komunikasi</td>
                                    <td><?= $nilai_akhir['kemampuan_komunikasi'] ?></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Kerjasama</td>
                                    <td><?= $nilai_akhir['kerjasama'] ?></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Kerajinan</td>
                                    <td><?= $nilai_akhir['kerajinan'] ?></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Percaya diri</td>
                                    <td><?= $nilai_akhir['percaya_diri'] ?></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Mematuhi Aturan</td>
                                    <td><?= $nilai_akhir['mematuhi_aturan'] ?></td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>Penampilan</td>
                                    <td><?= $nilai_akhir['penampilan'] ?></td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>Perilaku</td>
                                    <td><?= $nilai_akhir['perilaku'] ?></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>