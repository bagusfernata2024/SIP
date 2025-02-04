<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Sertifikat</title>

    <!-- Tambahkan Library html2canvas & jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        /* Reset default margin & padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Pastikan body memiliki background full tanpa terpotong */
        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Calibri, sans-serif;
            background: url('<?= base_url('templates/sertifikat_template.jpg') ?>') no-repeat center center fixed;
            background-size: contain;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Container utama */
        .print-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        /* Sertifikat */
        .sertifikat {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        /* Backup IMG jika background CSS gagal */
        .background-img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: contain;
            z-index: -1;
        }

        /* Mode Cetak */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body,
            html {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background: url('<?= base_url('templates/sertifikat_template.jpg') ?>') no-repeat center center !important;
                background-size: contain !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <div class="print-container" id="printArea">
        <!-- Gambar cadangan jika background gagal -->
        <img src="<?= base_url('templates/sertifikat_template.jpg') ?>" class="background-img">

        <div class="sertifikat">
            <h1 style="color: black;"></h1>
            <br><br><br><br><br><br>
            <p style="color: black; font-size: 15px; font-family:Calibri; margin-top: 16px;"><?= $registrasi['no_sertif'] ?></p>
            <br><br><br>
            <p style="color: #1F497D; font-size:28px; margin-top:6px; font-family:Calibri;"><b><?= $registrasi['nama'] ?></b></p>
            <br><br>
            <p style="color: black; font-size: 28px; font-family:Calibri; margin-top: 25px;"><b><?= $registrasi['minat'] ?></b></p>
            <br>
            <p style="font-size: 18px; font-family:Calibri;">Mulai tanggal
                <?php
                // Menampilkan tanggal mulai dengan bulan dalam bahasa Indonesia
                $bulan = [
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember'
                ];
                $tanggal_mulai = date('d', strtotime($registrasi['tanggal1']));
                $bulan_mulai = date('m', strtotime($registrasi['tanggal1']));
                $tahun_mulai = date('Y', strtotime($registrasi['tanggal1']));
                $formatted_tanggal_mulai = $tanggal_mulai . ' ' . $bulan[$bulan_mulai] . ' ' . $tahun_mulai;
                echo $formatted_tanggal_mulai;
                ?>
                sampai dengan
                <?php
                // Format tanggal selesai dengan bulan dalam bahasa Indonesia
                $tanggal_selesai = date('d', strtotime($registrasi['tanggal2']));
                $bulan_selesai = date('m', strtotime($registrasi['tanggal2']));
                $tahun_selesai = date('Y', strtotime($registrasi['tanggal2']));
                $formatted_tanggal_selesai = $tanggal_selesai . ' ' . $bulan[$bulan_selesai] . ' ' . $tahun_selesai;
                echo $formatted_tanggal_selesai;
                ?>
                dengan hasil
            </p>
            <p style="color: black; font-size: 28px; font-family:Calibri; margin-top: 5px;"><b>Memuaskan</b></p>
            <p style="margin-top: 25px; font-size: 18px; font-family:Calibri;">
                <?php
                // Tampilkan tanggal saat ini dengan format "04 Januari 2025"
                $tanggal_saat_ini = date('d', time());
                $bulan_saat_ini = date('m', time());
                $tahun_saat_ini = date('Y', time());
                $formatted_tanggal_saat_ini = $tanggal_saat_ini . ' ' . $bulan[$bulan_saat_ini] . ' ' . $tahun_saat_ini;
                echo $formatted_tanggal_saat_ini;
                ?>
            </p>
        </div>
    </div>

    <script>
        async function generatePDFAndRedirect() {
            const {
                jsPDF
            } = window.jspdf;

            // Tangkap area yang ingin dicetak sebagai gambar
            const element = document.getElementById("printArea");
            const canvas = await html2canvas(element, {
                scale: 2
            });

            // Konversi ke gambar data URL
            const imgData = canvas.toDataURL("image/png");

            // Buat PDF
            let pdf = new jsPDF("l", "mm", "a4");
            pdf.addImage(imgData, "PNG", 10, 10, 277, 190); // Sesuaikan posisi

            // Unduh file
            pdf.save("Sertifikat_<?= $registrasi['nama'] ?>.pdf");

            // Redirect ke halaman sebelumnya setelah download selesai
            setTimeout(function() {
                window.history.back();
            }, 1000); // Delay agar download selesai dulu
        }

        // Jalankan fungsi setelah halaman selesai dimuat
        window.onload = function() {
            generatePDFAndRedirect();
        };
    </script>

</body>

</html>