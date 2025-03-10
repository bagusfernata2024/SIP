<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Unggah Laporan Akhir</h1>
    <p class="mb-4">
        Halaman ini digunakan untuk mengunggah file laporan akhir. Jika Anda telah mendapatkan izin untuk mengirimkan
        laporan akhir dalam periode tertentu, silakan gunakan halaman ini untuk mengunggah file Anda.
        <br>
        Jika halaman ini belum berfungsi atau Anda mengalami kendala, harap segera menghubungi mentor Anda untuk bantuan
        lebih lanjut.
    </p>
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
                        <?php if ($id_magang != NULL): ?>
                            <?php if ($laporan->laporan_akhir != NULL): ?>
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <?php if ($laporan->approved_laporan_akhir == NULL): ?>
                                            Laporan anda sedang di review
                                        <?php elseif ($laporan->approved_laporan_akhir == 'Y'): ?>
                                            Laporan anda telah diterima
                                        <?php elseif ($laporan->approved_laporan_akhir == 'N'): ?>
                                            Laporan anda ditolak
                                        <?php else: ?>
                                            Masukan data laporan akhir
                                        <?php endif; ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($laporan): ?>
                                        <div class="mb-3">
                                            <?php if ($laporan->approved_laporan_akhir == NULL): ?>
                                                <div class="alert alert-warning" role="alert">
                                                    <strong>File laporan anda sedang di review tunggu hingga beberapa saat</strong>
                                                    (Hubungi mentor jika terjadi kesalahan).
                                                </div>
                                            <?php elseif ($laporan->approved_laporan_akhir == 'Y'): ?>
                                                <div class="alert alert-success" role="alert">
                                                    <strong>File laporan anda telah diterima</strong>
                                                    (Hubungi mentor jika terjadi kesalahan).
                                                </div>
                                            <?php elseif ($laporan->approved_laporan_akhir == 'N'): ?>
                                                <div class="alert alert-danger" role="alert">
                                                    <strong>File laporan anda ditolak</strong>
                                                    (Hubungi mentor jika terjadi kesalahan).
                                                </div>
                                            <?php else: ?>
                                                Masukan data laporan akhir
                                            <?php endif; ?>
                                            <!-- File Section -->
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
                                                                <a href="<?php echo base_url('dashboard/file/' . urlencode($laporan->laporan_akhir)); ?>"
                                                                    class="btn btn-primary btn-sm">Download</a>
                                                            </td>
                                                        </tr>
                                                        <br>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <div class="alert alert-danger" role="alert">
                                                <strong>Unggah laporan
                                                </strong>
                                                (Pastikan anda tidak salah memilih file laporan akhir).
                                            </div>
                                        </div>
                                        <?php if ($this->session->flashdata('success')): ?>
                                            <div class="alert alert-success">
                                                <?php echo $this->session->flashdata('success'); ?>
                                            </div>
                                        <?php elseif ($this->session->flashdata('error')): ?>
                                            <div class="alert alert-danger">
                                                <?php echo $this->session->flashdata('error'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="small mb-2">Tekan tombol dibawah ini untuk mengirim laporan:</div>
                                    </div>
                                <?php endif ?>
                            <?php endif; ?>
                            <?php if ($laporan->laporan_akhir == NULL): ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal" onclick="setAction('Accept')">
                                    Kirim laporan
                                </button>
                            <?php endif ?>
                        <?php elseif ($id_magang == NULL): ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Pendaftaran belum diterima</strong>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <strong>Silahkan pilih file laporan akhir</strong> (Pastikan anda tidak salah memilih file laporan
                    akhir).
                </div>
                <form action="<?php echo base_url('dashboard/proses_upload_laporan_akhir'); ?>" method="post"
                    enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id_magang; ?>">
                    <div class="form-group">
                        <label for="file_laporan">Unggah Laporan Akhir</label>
                        <input type="file" class="form-control" id="file_laporan" name="file_laporan" accept=".pdf"
                            required onchange="validateFileSize(this)">
                        <small class="form-text text-muted">Ukuran file maksimal 2MB. Pastikan file Anda tidak melebihi
                            batas ini.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Unggah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    let actionType = '';

    // Atur nilai aksi saat tombol diklik
    function setAction(action) {
        actionType = action; // Simpan aksi yang dipilih
    }

    function validateFileSize(input) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const errorMessage = "Ukuran file harus kurang dari 2MB.";

        if (file.size > maxSize) {
            alert(errorMessage);
            input.value = ''; // Reset file input
        }
    }


    document.getElementById('confirmAction').addEventListener('click', function () {
        // Ambil nilai nipg dari select
        const nipgValue = document.getElementById('mentor').value;

        if (actionType && nipgValue) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo base_url("dashboard/proses_upload_laporan_akhir"); ?>';

            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            idField.value = '<?php echo $id_magang; ?>';

            const actionField = document.createElement('input');
            actionField.type = 'hidden';
            actionField.name = 'action';
            actionField.value = actionType;

            // Tambahkan semua field ke form
            form.appendChild(idField);
            form.appendChild(actionField);

            // Tambahkan form ke body dan submit
            document.body.appendChild(form);
            form.submit();
        } else {
            console.error('No action type or Laporan value set');
        }
    });
</script>

<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>