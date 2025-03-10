<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start">
        <a href="<?php echo base_url('admin/dashboard'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    <br>
    <h1 class="h3 mb-4 text-gray-800">Review Surat</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Review Surat</h6>
        </div>

        <style>
            /* Memperbesar progress bar */
            .progress {
                height: 40px;
                /* Lebar bar */
                border-radius: 30px;
                /* Menambahkan border radius pada tombol */
                overflow: hidden;
                /* Untuk memastikan progress bar tetap terlihat dengan border-radius */

            }

            /* Memperbesar tombol dalam progress bar */
            .progress-bar .btn {
                height: 100%;
                /* Menyesuaikan tinggi tombol dengan tinggi progress bar */
                font-size: 1rem;
                /* Ukuran font untuk tombol */
                padding: 10px 0;
                /* Padding agar tombol lebih besar */
                border-radius: 30px;
                /* Menambahkan border radius pada tombol */

            }
        </style>

        <div class="py-3" style="margin-left: 10px; margin-right: 10px;">
            <div class="progress">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/detail/' . $detail['encrypt_id']); ?>"
                        class="btn btn-secondary w-100">
                        Preview
                    </a>
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/cari_mentor/' . $detail['encrypt_id']); ?>"
                        class="btn btn-secondary w-100">
                        Cari Mentor
                    </a>
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/upload_surat/' . $detail['encrypt_id']); ?>"
                        class="btn btn-secondary w-100">
                        Upload Surat
                    </a>
                </div>
                <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/review_surat/' . $detail['encrypt_id']); ?>"
                        class="btn btn-success w-100">
                        Review Surat
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabel Surat Perjanjian -->
        <h5 class="font-weight-bold mt-4" style="margin-left: 20px;">Surat Perjanjian:</h5>
        <div class="tabel-surat" style="margin-left: 20px; margin-right:20px;">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail['surat_perjanjian_ttd'])) { ?>
                            <tr>
                                <td>1</td>
                                <td><?php echo $detail['surat_perjanjian_ttd']; ?></td>
                                <td>
                                    <a href="<?php echo base_url('uploads/surat_perjanjian_ttd/' . $detail['surat_perjanjian']); ?>"
                                        class="btn btn-primary btn-sm" download>
                                        Download
                                    </a>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-danger">
                                    <strong>Surat perjanjian belum diunggah.</strong>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4" style="margin-right: 20px;">
            <form method="post" action="<?php echo base_url('admin/dashboard/terima_surat_perjanjian'); ?>">
                <div class="d-flex justify-content-end">
                    <input type="hidden" name="id_register" value="<?php echo $detail['id_register']; ?>">
                    <?php if ($anak_magang) { ?>
                        <?php if ($anak_magang['status'] == 'Aktif') { ?>
                            <!-- Jika sudah diterima, hanya tampilkan tombol nonaktif hijau -->
                            <button type="button" class="btn btn-success btn-sm disabled" disabled>
                                Pendaftar Diterima
                            </button>
                        <?php } elseif ($anak_magang['status'] == 'reject') { ?>
                            <!-- Jika sudah ditolak, tampilkan tombol merah nonaktif -->
                            <button type="button" class="btn btn-danger btn-sm disabled btn-white" disabled>
                                Pendaftar Ditolak
                            </button>
                        <?php } else { ?>

                        <?php } ?>
                    <?php } else { ?>
                        <?php if ($detail['status'] === 'Accept') { ?>
                            <!-- Jika sudah diterima, hanya tampilkan tombol nonaktif hijau -->
                            <button type="button" class="btn btn-success btn-sm disabled" disabled>
                                Pendaftar Diterima
                            </button>
                        <?php } elseif ($detail['status'] === 'reject') { ?>
                            <!-- Jika sudah ditolak, tampilkan tombol merah nonaktif -->
                            <button type="button" class="btn btn-danger btn-sm disabled btn-white" disabled>
                                Pendaftar Ditolak
                            </button>
                        <?php } else { ?>

                        <?php } ?>

                    <?php } ?>
                    <?php if ($detail['status'] !== 'Accept' && $detail['status'] !== 'reject') { ?>
                        <!-- Jika belum ada keputusan, tampilkan tombol aktif untuk aksi -->
                        <button type="submit" class="btn btn-success btn-sm ml-2" data-bs-toggle="modal"
                            data-bs-target="#confirmModal" onclick="setAction('Accept')">
                            Terima
                        </button>

                        <a
                            href="<?php echo base_url('admin/dashboard/tolak_surat_perjanjian/' . $detail['encrypt_id']); ?>"><button
                                type="button" class="btn btn-danger btn-sm ml-2" data-bs-toggle="modal"
                                data-bs-target="#confirmModal" onclick="setAction('reject')">
                                Tolak
                            </button></a>
                    <?php } ?>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi -->
        <!-- <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
                    </div>
                    <div class="modal-body">
                        
                        Apakah Anda yakin ingin melakukan aksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="confirmAction">Ya</button>
                    </div>
                </div>
            </div>
        </div> -->


        <div class="tabel-surat" style="margin-left: 30px; margin-right: 20px; margin-bottom: 20px;">
            <div class="table-responsive">
                <!-- Progress bar dan data lainnya di sini -->

                <!-- Tombol Previous dan Next -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo base_url('admin/dashboard/upload_surat/' . $detail['encrypt_id']); ?>"
                        class="btn btn-warning">
                        Previous
                    </a>
                </div>
            </div>
        </div>



    </div>
</div>
</div>
</div>
</div>

<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>