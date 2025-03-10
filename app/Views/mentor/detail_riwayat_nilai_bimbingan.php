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
        Halaman ini menampilkan nilai akhir yang diberikan oleh mentor kepada peserta magang selama proses magang di PGN.
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
                <p style="margin-left: 20px;"><strong>Periode Magang:</strong> <?= formatTanggalIndo($data->tgl_mulai) . ' - ' . formatTanggalIndo($data->tgl_selesai) ?></p>
                <?php if ($data->tgl_perpanjangan != NULL): ?>
                    <p style="margin-left: 20px;"><strong>Perpanjangan Magang:</strong> <?= formatTanggalIndo($data->tgl_perpanjangan) ?></p>
                <?php elseif ($data->tgl_perpanjangan == NULL) : ?>
                    <p style="margin-left: 20px;"><strong>Perpanjangan Magang:</strong> Tidak diperpanjang</p>
                <?php endif; ?>
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
                    <!-- Tombol Cetak Nilai -->
                    <div class="mt-3">
                        <a href="<?php echo base_url('mentor/dashboard/cetak_detail_riwayat_nilai_bimbingan/' . $encrypt_id) ?>" target="_Blank" class="btn btn-success btn-sm">
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