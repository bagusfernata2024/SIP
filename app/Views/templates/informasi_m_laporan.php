<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start mb-4">
        <a href="<?php echo base_url('admin/dashboard/detail_data_m_peserta/' . $encrypt_id); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Detail Peserta
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Laporan Akhir
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Grow In Utility -->
                <div class="col-lg-12">
                    <div class="card position-relative">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Detail laporan akhir <?= $detail_peserta[0]->nama ?> | <?= $detail_peserta[0]->nomor ?> | <?= $detail_peserta[0]->instansi ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if ($laporan): ?>
                                <div class="mb-3">
                                    <?php if ($laporan->approved_laporan_akhir == NULL && $laporan->laporan_akhir == NULL): ?>
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Peserta belum memasukan data laporan akhir</strong>
                                        </div>
                                    <?php elseif ($laporan->approved_laporan_akhir == 'Y' && $laporan->laporan_akhir != NULL): ?>
                                        <div class="alert alert-success" role="alert">
                                            <strong>File laporan peserta telah diterima</strong>
                                        </div>
                                    <?php elseif ($laporan->approved_laporan_akhir == 'N' && $laporan->laporan_akhir != NULL): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <strong>File laporan peserta ditolak</strong>
                                        </div>
                                    <?php elseif ($laporan->approved_laporan_akhir == NULL && $laporan->laporan_akhir != NULL) : ?>
                                        <div class="alert alert-warning" role="alert">
                                            <strong>File laporan peserta sedang di review tunggu hingga beberapa saat</strong>
                                        </div>
                                    <?php endif; ?>
                                    <!-- File Section -->
                                    <?php if ($laporan->laporan_akhir != NULL) : ?>
                                        <h5 class="font-weight-bold mt-4">File Lampiran:</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Lampiran</th>
                                                        <th>Nama File</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Laporan Akhir</td>
                                                        <td><?php echo $laporan->laporan_akhir ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url('admin/dashboard/file/' . $laporan->laporan_akhir); ?>"
                                                                class="btn btn-primary btn-sm">Download</a>

                                                        </td>
                                                    </tr>
                                                    <br>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php else: ?>
                                Data tidak ada
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->