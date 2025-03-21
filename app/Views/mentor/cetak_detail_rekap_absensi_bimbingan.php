<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Absensi</title>
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

        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px 0;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-success:hover {
            background-color: #218838;
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

        @media print {
            body {
                margin: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .btn {
                display: none;
            }

            .container {
                width: 100%;
            }

            .card {
                border: none;
                box-shadow: none;
            }

            .card-header {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Page Heading -->
        <h1>Detail Riwayat Absensi</h1>
        <p class="mb-4">
            Halaman ini menampilkan detail riwayat absensi anak bimbingan magang yang telah diterima atau ditolak oleh
            mentor selama proses magang di PGN.
        </p>

        <!-- Tabel Riwayat Absensi -->
        <div class="card">
            <div class="card-header">
                Data Riwayat Absensi
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $absenCountApproved = 0; // Variable to count approved absences ?>
                            <?php foreach ($peserta as $absen): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= formatTanggalIndo($absen->tgl) ?></td>
                                    <td><?= $absen->jam_masuk; ?></td>
                                    <td><?= $absen->jam_pulang; ?></td>
                                    <td><?= $absen->deskripsi; ?></td>
                                    <?php if ($absen->approved == 'Y'): ?>
                                        <td>Diterima</td>
                                        <?php $absenCountApproved++; // Increment count for approved absences ?>

                                    <?php elseif ($absen->approved == 'N'): ?>
                                        <td>Ditolak</td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br><br>    
                <p><strong>Perhitungan Uang Saku</strong></p>
                <p><strong>Jumlah Hari : <?= $absenCountApproved ?></strong></p>
                <p><strong>Total Uang Saku: </strong>Rp <?= number_format($absenCountApproved * 50000, 0, ',', '.') ?></p>

            </div>

        </div>

        <!-- Total Uang Saku -->
        <div class="footer">
        </div>
    </div>
</body>

</html>