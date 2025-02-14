<!-- Mulai Kontainer Data -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Peserta Magang</h6>
            <br>
            <!-- Tombol Ekspor Excel -->
            <a href="<?= site_url('admin/dashboard/exportToExcel'); ?>">
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </a>
        </div>
        <!-- Mulai Kontainer Utama -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Periode Magang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Periode Magang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($data_peserta)): ?>
                            <?php
                            $no = 1;
                            foreach ($data_peserta as $item):
                                $current_date = date('Y-m-d'); // Tanggal saat ini
                                $status_text = '';
                                $status_class = '';

                                if ($item['status'] === 'Aktif') {
                                    if ($item['tanggal1'] > $current_date) {
                                        $status_class = 'bg-warning text-light';
                                        $status_text = 'Belum Aktif';
                                    } else if ($item['tanggal2'] < $current_date) {
                                        $status_class = 'bg-info text-light';
                                        $status_text = 'Selesai Magang';
                                    } else {
                                        $status_class = 'bg-success text-light';
                                        $status_text = 'Aktif';
                                    }
                                } elseif ($item['status'] === 'reject') {
                                    $status_class = 'bg-danger text-light';
                                    $status_text = 'Ditolak';
                                } elseif ($item['status'] === 'Selesai Magang') {
                                    $status_class = 'bg-info text-light';
                                    $status_text = 'Selesai Magang';
                                } else {
                                    $status_class = 'bg-secondary text-light';
                                    $status_text = 'Menunggu Konfirmasi';
                                }
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $item['nomor']; ?></td> <!-- Nomor dari tabel registrasi -->
                                    <td><?= $item['nama']; ?></td> <!-- Nama dari tabel registrasi -->
                                    <td><?= $item['instansi']; ?></td> <!-- Instansi dari tabel registrasi -->
                                    <td><?= formatTanggalIndo($item['tanggal1']); ?> - <?= formatTanggalIndo($item['tanggal2']); ?></td> <!-- Periode magang dari tabel registrasi -->
                                    <td>
                                        <span class="badge <?= $status_class; ?>">
                                            <?= $status_text; ?>
                                        </span>
                                    </td> <!-- Status dari tabel anak_magang -->
                                    <td>
                                        <a href="<?php echo site_url('admin/dashboard/detail_data_peserta/' . $item['id_magang']); ?>">
                                            <button class="btn btn-success btn-sm">
                                                <i class="fas fa-search" style="color: white; font-size: 9px;"></i>
                                            </button>
                                        </a>
                                        <br>
                                        <br>
                                        <!-- Tombol Generate Sertifikat -->
                                        <a href="<?= site_url('admin/dashboard/sertifikat/' . $item['id_register']); ?>">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-file-pdf" style="color: white; font-size: 12px;"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada peserta magang ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>