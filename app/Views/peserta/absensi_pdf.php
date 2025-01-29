<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Data Absensi</h1>
    <p>Berikut adalah data absensi Anda:</p>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Approved</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($absensi)): ?>
                <?php foreach ($absensi as $absen): ?>
                    <tr>
                        <td><?= $absen['tgl']; ?></td>
                        <td><?= $absen['jam_masuk'] ?? '-'; ?></td>
                        <td><?= $absen['jam_pulang'] ?? '-'; ?></td>
                        <td><?= $absen['deskripsi'] ?? '-'; ?></td>
                        <td><?= $absen['statuss']; ?></td>
                        <td><?= $absen['approved'] === 'Y' ? 'Diterima' : ($absen['approved'] === 'R' ? 'Ditolak' : '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Data absensi tidak tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
