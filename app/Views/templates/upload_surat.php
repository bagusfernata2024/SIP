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
    <h1 class="h3 mb-4 text-gray-800">Upload Surat</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upload Surat</h6>
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

        <div class="py-3" style="margin-left: 10px; margin-right: 10px; margin-top: 20px;">
            <div class="progress">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/detail/' . $detail['id_register']); ?>" class="btn btn-secondary w-100">
                        Preview
                    </a>
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/cari_mentor/' . $detail['id_register']); ?>" class="btn btn-secondary w-100">
                        Cari Mentor
                    </a>
                </div>
                <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/upload_surat/' . $detail['id_register']); ?>" class="btn btn-success w-100">
                        Upload Surat
                    </a>
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('admin/dashboard/review_surat/' . $detail['id_register']); ?>" class="btn btn-secondary w-100">
                        Review Surat
                    </a>
                </div>
            </div>
        </div>

        <div class="upload_surat" style="margin-left: 20px; margin-right: 20px;">
            <!-- File Surat Perjanjian -->
            <h5 class="font-weight-bold mt-4">Upload Surat Persetujuan:</h5>

            <?php if (!empty($detail['surat_persetujuan'])): ?>
                <!-- Jika surat perjanjian sudah ada di database -->
                <p>Surat Persetujuan telah diupload: <a href="<?php echo base_url('uploads/' . $detail['surat_persetujuan']); ?>" target="_blank"><?php echo $detail['surat_persetujuan']; ?></a></p>
            <?php else: ?>
                <!-- Jika surat perjanjian belum diupload -->
                <form method="POST" action="<?php echo base_url('admin/dashboard/upload_surat_persetujuan'); ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="suratPersetujuan" class="form-label">Pilih Surat Persetujuan</label>
                        <input type="file" class="form-control" id="suratPersetujuan" name="surat_persetujuan" required>
                    </div>
                    <input type="hidden" name="id_register" value="<?php echo $detail['id_register']; ?>">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="upload_surat" style="margin-left: 20px; margin-right: 20px;">
            <!-- File Surat Perjanjian -->
            <h5 class="font-weight-bold mt-4">Upload Surat Perjanjian:</h5>

            <?php if (!empty($detail['surat_perjanjian'])): ?>
                <!-- Jika surat perjanjian sudah ada di database -->
                <p>Surat Perjanjian telah diupload: <a href="<?php echo base_url('uploads/' . $detail['surat_perjanjian']); ?>" target="_blank"><?php echo $detail['surat_perjanjian']; ?></a></p>
            <?php else: ?>
                <!-- Jika surat perjanjian belum diupload -->
                <form method="POST" action="<?php echo base_url('admin/dashboard/upload_surat_perjanjian'); ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="suratPerjanjian" class="form-label">Pilih Surat Perjanjian</label>
                        <input type="file" class="form-control" id="suratPerjanjian" name="surat_perjanjian" required>
                    </div>
                    <input type="hidden" name="id_register" value="<?php echo $detail['id_register']; ?>">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            <?php endif; ?>
            <?php if (isset($detail['upload_surat']) && $detail['upload_surat'] === 'Y'): ?>
                    <button type="button" class="btn btn-success btn-sm ml-2" disabled>
                        Surat Terkirim
                    </button>

            <?php else: ?>
                <a href="<?php echo base_url('admin/dashboard/kirim_surat/' . $detail['id_register']); ?>"><button type="button" class="btn btn-success btn-sm ml-2">
                        Kirim
                    </button></a>
            <?php endif; ?>
        </div>

        <div class="tabel-surat" style="margin-left: 20px; margin-right: 20px; margin-bottom: 20px;">
            <div class="table-responsive">
                <!-- Progress bar dan data lainnya di sini -->

                <!-- Tombol Previous dan Next -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo base_url('admin/dashboard/cari_mentor/' . $detail['id_register']); ?>" class="btn btn-warning">
                        Previous
                    </a>
                    <a href="<?php echo base_url('admin/dashboard/review_surat/' . $detail['id_register']); ?>" class="btn btn-warning">
                        Next
                    </a>
                </div>
            </div>

        </div>


        <!-- Modal Konfirmasi -->
        <!-- <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
        </div> -->
    </div>
</div>
</div>
</div>
</div>

<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>