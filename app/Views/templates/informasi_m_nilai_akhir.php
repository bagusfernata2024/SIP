<style>
    h2 {
        color: #4e73df;
        margin-top: 20px;
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
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start mb-4">
        <a href="<?php echo base_url('admin/dashboard/detail_data_m_peserta/' . $encrypt_id); ?>"
            class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Detail Peserta
        </a>
    </div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Nilai Akhir</h1>
    <p class="mb-4">
        Halaman ini menampilkan nilai akhir yang diberikan oleh mentor kepada peserta magang selama proses magang di
        PGN.
    </p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Hasil Nilai Akhir
            </h6>
        </div>
        <!-- Data Diri -->
        <div class="data-diri">
            <h3>Data Diri Peserta Magang</h3>
            <?php foreach ($nilai_akhir as $index => $data): ?>
                <p><strong>Nama:</strong> <?= $data->nama ?></p>
                <p><strong>NIM:</strong> <?= $data->nomor ?></p>
                <p><strong>Instansi:</strong> <?= $data->instansi ?></p>
                <p><strong>Periode Magang:</strong>
                    <?= formatTanggalIndo($data->tgl_mulai) . ' - ' . formatTanggalIndo($data->tgl_selesai) ?></p>
            <?php endforeach ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($nilai_akhir != NULL): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aspek</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nilai_akhir as $nilai): ?>
                                <tr>
                                    <td>1</td>
                                    <td>Kehadiran</td>
                                    <td><?= $nilai->kehadiran ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Tanggung Jawab</td>
                                    <td><?= $nilai->tanggung_jawab ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Kemampuan Kerja</td>
                                    <td><?= $nilai->kemampuan_kerja ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Integritas</td>
                                    <td><?= $nilai->integritas ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Perilaku</td>
                                    <td><?= $nilai->perilaku ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-end"><strong>Rata-Rata</strong></td>
                                    <td class="text-end" style="text-end">
                                        <?= $nilai->rata ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Predikat</strong></td>
                                    <td class="text-end" style="text-end">
                                        <?= $nilai->predikat ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Tombol Cetak Nilai -->
                    <div class="mt-3">
                        <a href="<?php echo base_url('admin/dashboard/cetak_informasi_nilai_akhir/' . $encrypt_id) ?>"
                            target="_Blank" class="btn btn-success btn-sm">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Nilai peserta belum diberikan</strong> (Hubungi mentor jika terjadi kesalahan).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->