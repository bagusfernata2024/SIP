<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start">
        <a href="<?php echo base_url('admin/dashboard/data_mentor'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Data Mentor
        </a>
    </div>
    <br>
    <div class="card shadow mb-4">
        <div cFlass="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Mentor</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <h5 class="font-weight-bold mt-4">Data Diri:</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td><?php echo $detail_mentor[0]->nama ?></td>
                        </tr>
                        <tr>
                            <th>NIPG</th>
                            <td><?php echo $detail_mentor[0]->nipg ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $detail_mentor[0]->email ?></td>
                        </tr>
                        <tr>
                            <th>Posisi</th>
                            <td><?php echo $detail_mentor[0]->posisi ?></td>
                        </tr>
                        <tr>
                            <th>Direktorat</th>
                            <td><?php echo $detail_mentor[0]->direktorat ?></td>
                        </tr>
                        <tr>
                            <th>Divisi</th>
                            <td><?php echo $detail_mentor[0]->division ?></td>
                        </tr>
                        <tr>
                            <th>Subsidiaries</th>
                            <td><?php echo $detail_mentor[0]->subsidiaries ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>
                                <?php
                                if ($detail_mentor[0]->gender === 'L') {
                                    echo 'Laki-laki';
                                } elseif ($detail_mentor[0]->gender === 'P') {
                                    echo 'Perempuan';
                                } else {
                                    echo 'Tidak Diketahui';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Job</th>
                            <td><?php echo $detail_mentor[0]->job ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <h5 class="font-weight-bold mt-4">Data Anak Bimbingan:</h5>
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
                            <?php if (!empty($detail_mentor)): ?>
                                <?php
                                $no = 1;
                                foreach ($detail_mentor as $item):
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>202131152</td>
                                        <td><?= $item->nama_peserta; ?></td>
                                        <td><?= $item->instansi; ?></td>
                                        <td><?= formatTanggalIndo($item->tanggal1); ?> - <?= formatTanggalIndo($item->tanggal2); ?></td>
                                        <td>
                                            <span class="badge 
                                            <?php
                                            if ($item->status === null) {
                                                echo 'bg-warning text-dark'; // Warna kuning untuk Proses
                                            } elseif ($item->status === 'Lulus') {
                                                echo 'bg-success text-white'; // Warna hijau untuk Lulus
                                            } elseif ($item->status === 'Tidak Lulus') {
                                                echo 'bg-danger text-white'; // Warna merah untuk Tidak Lulus
                                            }
                                            ?>">
                                                <?= $item->status === null ? 'Proses' : $item->status; ?>
                                            </span>
                                        </td>

                                        <td><a href="<?php echo site_url('admin/dashboard/detail_data_m_peserta/' . $item->id_magang); ?>">
                                                <button class="btn btn-success btn-sm">
                                                    <i class="fas fa-search" style="color: white;"></i>
                                                </button></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada anak bimbingan ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <strong>Pastikan mentor dipilih</strong> (Diterima atau ditolak mentor harus dipilih).
                </div>
                Apakah Anda yakin ingin melakukan aksi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmAction">Ya</button>
            </div>
        </div>
    </div>
</div>

<!--  -->



<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>