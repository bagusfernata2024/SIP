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
    <h1 class="h3 mb-4 text-gray-800">Detail Registrasi</h1>
    <p class="mb-4">Berikut adalah detail data registrasi:</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Pendaftar</h6>
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

        <div class="card-body">
            <div class="table-responsive">
                <div class="py-3">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            <a href="<?php echo base_url('admin/dashboard/detail/' . $detail['id_register']); ?>" class="btn btn-success w-100">
                                Preview
                            </a>
                        </div>
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            <a href="<?php echo base_url('admin/dashboard/cari_mentor/' . $detail['id_register']); ?>" class="btn btn-secondary w-100">
                                Cari Mentor
                            </a>
                        </div>
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 33%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            <a href="<?php echo base_url('admin/dashboard/upload_surat/' . $detail['id_register']); ?>" class="btn btn-secondary w-100">
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


                <h5 class="font-weight-bold mt-4">Data Diri:</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td><?php echo $detail['nama']; ?></td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td><?php echo $detail['jurusan']; ?></td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td><?php echo $detail['prodi']; ?></td>
                        </tr>
                        <tr>
                            <th>Instansi</th>
                            <td><?php echo $detail['instansi']; ?></td>
                        </tr>
                        <tr>
                            <th>No Telepon</th>
                            <td>
                                <?php echo $detail['notelp']; ?>
                                <a href="https://wa.me/<?php echo '62' . ltrim($detail['notelp'], '0'); ?>" target="_blank" class="btn btn-success btn-sm">
                                    Hubungi via WhatsApp
                                </a>

                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $detail['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo $detail['alamat']; ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>
                                <?php
                                if ($detail['jk'] === 'L') {
                                    echo 'Laki-laki';
                                } elseif ($detail['jk'] === 'P') {
                                    echo 'Perempuan';
                                } else {
                                    echo 'Tidak Diketahui';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td><?php echo formatTanggalIndo($detail['tgl_lahir']) ?></td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td><?php echo $detail['nik']; ?></td>
                        </tr>
                    </tbody>
                </table>

                <h5 class="font-weight-bold mt-4">Informasi Magang:</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Strata</th>
                            <td><?php echo $detail['strata']; ?></td>
                        </tr>
                        <tr>
                            <th>Periode Magang</th>
                            <td><?php echo $detail['lama_pkl']; ?> Bulan</td>
                        </tr>
                        <tr>
                            <th>Tanggal Registrasi</th>
                            <td><?php echo formatTanggalIndo($detail['tgl_regis']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>
                                <?php echo formatTanggalIndo($detail['tanggal1']); ?>
                                <?php if ($detail['timeline'] == 'Review Berkas Awal') { ?>


                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>
                                <?php echo formatTanggalIndo($detail['tanggal2']); ?>
                                <?php if ($detail['timeline'] == 'Review Berkas Awal') { ?>

                                    <button type="button" class="btn btn-warning btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#editTanggalModal">
                                        Edit
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td style="color:white">
                                <span class="badge 
                                    <?php
                                    if ($detail['status'] === 'Accept') echo 'bg-success text-light';
                                    elseif ($detail['status'] === 'reject') echo 'bg-danger text-light';
                                    else echo 'bg-warning text-light';
                                    ?>">
                                    <?php echo $detail['status'] ? ucfirst($detail['status']) : 'Belum Diterima'; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Minat Satuan Kerja</th>
                            <td><?php echo $detail['minat']; ?></td>
                        </tr>
                        <tr>
                            <th>Tipe Magang</th>
                            <td><?php echo $detail['tipe']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>




            <!-- File Section -->
            <h5 class="font-weight-bold mt-4">File Lampiran:</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lampiran</th>
                            <th>Nama File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Surat Permohonan</td>
                            <td><?php echo $detail['surat_permohonan']; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/dashboard/file_lampiran/' . urlencode($detail['surat_permohonan'])); ?>"
                                    class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Proposal Magang</td>
                            <td><?php echo $detail['proposal_magang']; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/dashboard/file_lampiran/' . urlencode($detail['proposal_magang'])); ?>"
                                    class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Curriculum Vitae (CV)</td>
                            <td><?php echo $detail['cv']; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/dashboard/file_lampiran/' . urlencode($detail['cv'])); ?>"
                                    class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Fotocopy KTP</td>
                            <td><?php echo $detail['fc_ktp']; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/dashboard/file_lampiran/' . urlencode($detail['fc_ktp'])); ?>"
                                    class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>

                        <!-- Tombol Download Semua -->
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/dashboard/download_all/' . $detail['id_register']); ?>" class="btn btn-success btn-sm">Download Semua Lampiran</a>
                        </div>
                        <br>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <!-- Progress bar dan data lainnya di sini -->

                <!-- Tombol Previous dan Next -->
                <div class="d-flex justify-content-between mt-4" style="margin-left: 890px;">
                    
                    <a href="<?php echo base_url('admin/dashboard/cari_mentor/' . $detail['id_register']); ?>" class="btn btn-warning">
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

<!-- Modal Edit Tanggal -->
<div class="modal fade" id="editTanggalModal" tabindex="-1" aria-labelledby="editTanggalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTanggalModalLabel">Edit Tanggal Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo base_url('admin/dashboard/update_tanggal'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $detail['id_register']; ?>">

                    <div class="mb-3">
                        <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai" value="<?php echo $detail['tanggal2']; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    let actionType = '';

    // Atur nilai aksi saat tombol diklik
    function setAction(action) {
        actionType = action; // Simpan aksi yang dipilih
    }

    document.getElementById('confirmAction').addEventListener('click', function() {
        // Ambil nilai nipg dari select

        if (actionType) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo base_url("admin/dashboard/update_status"); ?>';

            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            idField.value = '<?php echo $detail["id_register"]; ?>';

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
            console.error('No action type or NIPG value set');
        }
    });
</script>



<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>