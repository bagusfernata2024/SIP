<!-- Begin Page Content -->
<div class="container-fluid">
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
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($id_magang != NULL): ?>
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
                                <tr>
                                    <td>1</td>
                                    <td>Kehadiran</td>
                                    <td><?= isset($nilai_akhir['kehadiran']) ? $nilai_akhir['kehadiran'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Tanggung Jawab</td>
                                    <td><?= isset($nilai_akhir['tanggung_jawab']) ? $nilai_akhir['tanggung_jawab'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Kemampuan Kerja</td>
                                    <td><?= isset($nilai_akhir['kemampuan_kerja']) ? $nilai_akhir['kemampuan_kerja'] : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Integritas</td>
                                    <td><?= isset($nilai_akhir['integritas']) ? $nilai_akhir['integritas'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Perilaku</td>
                                    <td><?= isset($nilai_akhir['perilaku']) ? $nilai_akhir['perilaku'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-end"><strong>Rata-Rata</strong></td>
                                    <td class="text-end" style="text-end">
                                        <?= $nilai_akhir['rata'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Predikat</strong></td>
                                    <td class="text-end" style="text-end">
                                        <?= $nilai_akhir['predikat'] ?>
                                    </td>
                                </tr>
                                <!-- Lanjutkan untuk kolom lainnya -->
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <?php if ($nilai_akhir['kehadiran'] != null): ?>
                                <a href="<?php echo base_url('dashboard/cetak_nilai') ?>" target="_blank"
                                    class="btn btn-success btn-sm">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Nilai anda belum diberikan</strong> (Hubungi mentor jika terjadi kesalahan).
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Pendaftaran belum diterima</strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->