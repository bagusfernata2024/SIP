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
                                    <td>Ketepatan Waktu</td>
                                    <td><?= isset($nilai_akhir['ketepatan_waktu']) ? $nilai_akhir['ketepatan_waktu'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Sikap Kerja</td>
                                    <td><?= isset($nilai_akhir['sikap_kerja']) ? $nilai_akhir['sikap_kerja'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Tanggung Jawab</td>
                                    <td><?= isset($nilai_akhir['tanggung_jawab']) ? $nilai_akhir['tanggung_jawab'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Kehadiran</td>
                                    <td><?= isset($nilai_akhir['kehadiran']) ? $nilai_akhir['kehadiran'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Kemampuan Kerja</td>
                                    <td><?= isset($nilai_akhir['kemampuan_kerja']) ? $nilai_akhir['kemampuan_kerja'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Keterampilan Kerja</td>
                                    <td><?= isset($nilai_akhir['keterampilan_kerja']) ? $nilai_akhir['keterampilan_kerja'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Kualitas Hasil</td>
                                    <td><?= isset($nilai_akhir['kualitas_hasil']) ? $nilai_akhir['kualitas_hasil'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Kemampuan Komunikasi</td>
                                    <td><?= isset($nilai_akhir['kemampuan_komunikasi']) ? $nilai_akhir['kemampuan_komunikasi'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Kerjasama</td>
                                    <td><?= isset($nilai_akhir['kerjasama']) ? $nilai_akhir['kerjasama'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Kerajinan</td>
                                    <td><?= isset($nilai_akhir['kerajinan']) ? $nilai_akhir['kerajinan'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Percaya Diri</td>
                                    <td><?= isset($nilai_akhir['percaya_diri']) ? $nilai_akhir['percaya_diri'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Mematuhi Aturan</td>
                                    <td><?= isset($nilai_akhir['mematuhi_aturan']) ? $nilai_akhir['mematuhi_aturan'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>Penampilan</td>
                                    <td><?= isset($nilai_akhir['penampilan']) ? $nilai_akhir['penampilan'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>Perilaku</td>
                                    <td><?= isset($nilai_akhir['perilaku']) ? $nilai_akhir['perilaku'] : '-' ?></td>
                                </tr>
                                <!-- Lanjutkan untuk kolom lainnya -->
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <a href="<?php echo base_url('dashboard/cetak_nilai') ?>" target="_blank" class="btn btn-success btn-sm">
                                <i class="fas fa-print"></i> Cetak
                            </a>

                            <?php
                            // Periksa apakah semua nilai tidak null
                            $allFieldsFilled = !in_array(null, array_values($nilai_akhir));
                            if ($allFieldsFilled): ?>
                                <a href="<?php echo base_url('dashboard/generate_sertifikat') ?>" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-file-pdf"></i> Sertifikat
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