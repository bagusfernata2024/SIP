<!-- Mulai Kontainer Data -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Peserta Magang</h6>
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
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $item->nomor; ?></td>
                                    <td><?= $item->nama; ?></td>
                                    <td><?= $item->instansi; ?></td>
                                    <td><?= formatTanggalIndo($item->tanggal1); ?> - <?= formatTanggalIndo($item->tanggal2); ?></td>
                                    <td></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/dashboard/detail_data_peserta/' . $item->id_magang); ?>">
                                            <button class="btn btn-success btn-sm">
                                                <i class="fas fa-search" style="color: white;"></i>
                                            </button></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada absensi ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>