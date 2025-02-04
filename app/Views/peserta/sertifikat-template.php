<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate PDF dengan Background</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
        }

        /* Sertifikat Container */
        .certificate-container {
            background-image: url('<?php echo base_url("templates/1.jpg"); ?>'); /* Ganti dengan URL atau path gambar latar belakang */            background-size: cover;
            background-position: center;
            width: 80%;
            height: 80%;
            border-radius: 10px;
            position: relative;
        }
    </style>
</head>
<body>

    <!-- Sertifikat Container -->
    <div class="certificate-container" id="certificate">
        <!-- Konten Sertifikat akan ditambahkan di sini -->
    </div>

    <!-- Button untuk generate PDF -->
    <button onclick="generatePDF()">Generate PDF</button>

    <script>
        function generatePDF() {
            const { jsPDF } = window.jspdf;

            // Membuat instance jsPDF
            const doc = new jsPDF('p', 'mm', 'a4');

            // Menambahkan gambar latar belakang
            doc.addImage('background.jpg', 'JPEG', 0, 0, 210, 297); // Ganti 'background.jpg' dengan gambar yang sesuai (path relatif atau URL)

            // Menambahkan teks atau elemen lainnya ke dalam PDF
            doc.setFontSize(20);
            doc.text('SERTIFIKAT', 105, 50, { align: 'center' });

            // Menambahkan teks lain, seperti {NOMOR SURAT}, {NAMA}, dll.
            doc.setFontSize(12);
            doc.text('Nomor Surat: {NOMOR SURAT}', 105, 70, { align: 'center' });
            doc.text('Diberikan kepada: {NAMA}', 105, 90, { align: 'center' });

            // Simpan atau unduh PDF
            doc.save('sertifikat.pdf');
        }
    </script>

</body>
</html>
