<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Nilai Akhir</h1>
    <p class="mb-4">
        Halaman ini menampilkan nilai akhir yang diberikan oleh mentor kepada peserta magang selama proses magang di PGN.
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
                <?php if ($id_magang != NULL) : ?>
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
                                        <td>Ketepatan Waktu</td>
                                        <td><?= $nilai->ketepatan_waktu ?></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Sikap Kerja</td>
                                        <td><?= $nilai->sikap_kerja ?></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Tanggungjawab</td>
                                        <td><?= $nilai->tanggung_jawab ?></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Kehadiran</td>
                                        <td><?= $nilai->kehadiran ?></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Kemampuan Kerja</td>
                                        <td><?= $nilai->kemampuan_kerja ?></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Keterampilan Kerja</td>
                                        <td><?= $nilai->keterampilan_kerja ?></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Kualitas Hasil</td>
                                        <td><?= $nilai->kualitas_hasil ?></td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Kemampuan Komunikasi</td>
                                        <td><?= $nilai->kemampuan_komunikasi ?></td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Kerjasama</td>
                                        <td><?= $nilai->kerjasama ?></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Kerajinan</td>
                                        <td><?= $nilai->kerajinan ?></td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Percaya diri</td>
                                        <td><?= $nilai->percaya_diri ?></td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Mematuhi Aturan</td>
                                        <td><?= $nilai->mematuhi_aturan ?></td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Penampilan</td>
                                        <td><?= $nilai->penampilan ?></td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>Perilaku</td>
                                        <td><?= $nilai->perilaku ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif ($id_magang == NULL) : ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Pendaftaran belum diterima</strong>
                        </div>
                        <!-- Tombol Cetak Nilai -->
                        <div class="mt-3">
                            <a href="<?php echo base_url('dashboard/cetak_nilai') ?>" target="_Blank" class="btn btn-success btn-sm">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </div>
                    <?php endif ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Nilai anda belum diberikan</strong> (Hubungi mentor jika terjadi kesalahan).
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