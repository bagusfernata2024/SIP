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
        <a href="<?php echo base_url('mentor/dashboard'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    <br>
    <h1 class="h3 mb-4 text-gray-800">Cari Co Mentor</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Co Mentor</h6>
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
                    <a href="<?php echo base_url('mentor/dashboard/detail_data_peserta/' . $detail['encrypt_id']); ?>"
                        class="btn btn-secondary w-100">
                        Preview
                    </a>
                </div>
                <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('mentor/dashboard/cari_co_mentor/' . $detail['encrypt_id']); ?>"
                        class="btn btn-success w-100">
                        Cari Co Mentor
                    </a>
                </div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 34%;" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100">
                    <a href="<?php echo base_url('mentor/dashboard/review_surat/' . $detail['encrypt_id']); ?>"
                        class="btn btn-secondary w-100">
                        Review Surat
                    </a>
                </div>
            </div>
        </div>


        <div class="pilih-mentor" style="margin-left: 0px; margin-right: 0px;">
            <?php if (!$anak_magang) { ?>
            <?php } elseif ($anak_magang['nipg_co_mentor'] !== null) { ?>
                <div class="informasi-co-mentor" style="margin-left: 20px; margin-right: 20px;">
                    <h5 class="font-weight-bold mt-4">Informasi Co Mentor:</h5>

                    <table class="table table-bordered">
                        <div class="mt-4">
                            <!-- Logika untuk menentukan warna tombol berdasarkan status -->
                            <!-- Jika diterima, Maka data mentor akan ditampilkan -->
                            <tbody>
                                <tr>
                                    <th>NIPG</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['nipg']) && !empty($co_mentor['nipg'])) {
                                            echo $co_mentor['nipg'];
                                        } else {
                                            echo "NIPG tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['nama']) && !empty($co_mentor['nama'])) {
                                            echo $co_mentor['nama'];
                                        } else {
                                            echo "Nama tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['email']) && !empty($co_mentor['email'])) {
                                            echo $co_mentor['email'];
                                        } else {
                                            echo "Email tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Posisi</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['posisi']) && !empty($co_mentor['posisi'])) {
                                            echo $co_mentor['posisi'];
                                        } else {
                                            echo "Posisi tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Divisi</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['division']) && !empty($co_mentor['division'])) {
                                            echo $co_mentor['division'];
                                        } else {
                                            echo "Divisi tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job</th>
                                    <td>
                                        <?php
                                        if (isset($co_mentor['job']) && !empty($co_mentor['job'])) {
                                            echo $co_mentor['job'];
                                        } else {
                                            echo "Job tidak ditemukan";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>

            <?php } else { ?>
                <!-- Jika status belum diterima, tampilkan form pemilihan mentor -->
                <div class="alert alert-warning" role="alert" style="margin-left:20px; margin-right: 20px;">
                    <strong>Co Mentor Belum Dipilih</strong> Silakan pilih co mentor.
                </div>

                <div class="form-group mb-4" style="margin-left: 20px; margin-right: 20px;">
                    <form action="<?php echo base_url('mentor/dashboard/assign_co_mentor/' . $detail['encrypt_id']); ?>"
                        method="POST" class="d-inline">
                        <label for="mentor" class="form-label font-weight-bold">Pilih Co Mentor</label>
                        <select class="form-control form-select" id="mentor" name="nipg" required>
                            <option value="" disabled selected>-- Pilih Co Mentor --</option>
                            <?php foreach ($co_mentors as $mentor) { ?>
                                <option value="<?php echo $mentor['nipg']; ?>">
                                    <?php echo $mentor['nama'] . " | " . $mentor['nipg'] . " | " . $mentor['division']; ?>
                                </option>
                            <?php } ?>
                        </select>
                </div>
                <!-- Tombol Pilih Mentor -->
                <input type="hidden" name="id_register" value="<?php echo $detail['id_register']; ?>">
                <button type="submit" class="btn btn-primary btn-sm mb-2" style="margin-left: 20px;">Pilih</button>
                </form>
            <?php } ?>
            <div class="table-responsive">
                <!-- Progress bar dan data lainnya di sini -->

                <!-- Tombol Previous dan Next -->
                <div class="d-flex justify-content-between mt-4" style="margin-bottom: 20px; margin-right: 20px; margin-left: 20px;">
                    <a href="<?php echo base_url('mentor/dashboard/detail_data_peserta/' . $detail['encrypt_id']); ?>"
                        class="btn btn-warning">
                        Previous
                    </a>
                    <a href="<?php echo base_url('mentor/dashboard/review_surat/' . $detail['encrypt_id']); ?>"
                        class="btn btn-warning">
                        Next
                    </a>
                </div>
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