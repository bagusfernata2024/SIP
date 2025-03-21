<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start mb-4">
        <a href="<?php echo base_url('mentor/dashboard/riwayat_nilai_bimbingan'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
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
            <br>
            <h3 style="margin-left: 20px;">Data Diri Peserta Magang</h3>
            <?php foreach ($nilai_akhir as $index => $data): ?>
                <p style="margin-left: 20px;"><strong>Nama:</strong> <?= $data->nama ?></p>
                <p style="margin-left: 20px;"><strong>NIM:</strong> <?= $data->nomor ?></p>
                <p style="margin-left: 20px;"><strong>Instansi:</strong> <?= $data->instansi ?></p>
                <p style="margin-left: 20px;"><strong>Periode Magang:</strong>
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
                                    <td>Tanggungjawab</td>
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
                            <?php endforeach; ?>

                            <!-- Baris untuk total dan rata-rata -->
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
                        </tbody>
                    </table>
                    <!-- Tombol Cetak Nilai -->
                    <div class="mt-3">
                        <a href="<?php echo base_url('mentor/dashboard/cetak_detail_riwayat_nilai_bimbingan/' . $encrypt_id) ?>"
                            target="_Blank" class="btn btn-success btn-sm">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Nilai anda belum diberikan</strong> (Hubungi mentor jika terjadi kesalahan).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->