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
            font-family: Arial, sans-serif;
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

            body, html {
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
            <br><br><br><br><br><br><br><br><br><br><br>
            <p style="color: black; font-size: small;">Nomor Surat</p>
            <br><br><br><br><br>
            <p style="color: black;">Ahmad Zaidan Khalik</p>
            <br><br><br><br><br><br>
            <p style="color: black; font-size: small;">Satuan Kerja</p>
            <br><br>
            <p style="font-size: small;">Mulai tanggal {tgl_mulai} sampai dengan {tgl_selesai} dengan hasil</p>
            <br><br>
            <p style="color: black; font-size: small;"><b>Memuaskan</b></p>
            <br><br><br>
            <p style="margin-top: 14px; font-size: small;"><?= date('d F Y') ?></p>
        </div>
    </div>

    <script>
        async function generatePDFAndRedirect() {
            const { jsPDF } = window.jspdf;
            
            // Tangkap area yang ingin dicetak sebagai gambar
            const element = document.getElementById("printArea");
            const canvas = await html2canvas(element, { scale: 2 });

            // Konversi ke gambar data URL
            const imgData = canvas.toDataURL("image/png");

            // Buat PDF
            let pdf = new jsPDF("l", "mm", "a4");
            pdf.addImage(imgData, "PNG", 10, 10, 277, 190); // Sesuaikan posisi

            // Unduh file
            pdf.save("Sertifikat.pdf");

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
