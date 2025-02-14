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

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Timeline</th>
                            <td><b><?php echo $detail['timeline']; ?></b></td>
                        </tr>
                    </tbody>
                </table>
                <?php if ($detail['timeline'] !== 'Pencarian Mentor') { ?>
                    <h5 class="font-weight-bold mt-4">Informasi Mentor:</h5>
                    <table class="table table-bordered">
                        <div class="mt-4">
                            <!-- Logika untuk menentukan warna tombol berdasarkan status -->
                            <?php if ($detail['status'] === 'Accept') { ?>
                                <!-- Jika diterima, Maka data mentor akan ditampilkan -->
                                <button type="button" class="btn btn-success btn-sm disabled mb-4" disabled>
                                    Pendaftar diterima
                                </button>
                                <tbody>
                                    <tr>
                                        <th>NIPG</th>
                                        <td>
                                            <?php
                                            if (isset($detail_mentor['nipg']) && !empty($detail_mentor['nipg'])) {
                                                echo $detail_mentor['nipg'];
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
                                            if (isset($detail_mentor['nama']) && !empty($detail_mentor['nama'])) {
                                                echo $detail_mentor['nama'];
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
                                            if (isset($detail_mentor['email']) && !empty($detail_mentor['email'])) {
                                                echo $detail_mentor['email'];
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
                                            if (isset($detail_mentor['posisi']) && !empty($detail_mentor['posisi'])) {
                                                echo $detail_mentor['posisi'];
                                            } else {
                                                echo "Posisi tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Direktorat</th>
                                        <td>
                                            <?php
                                            if (isset($detail_mentor['direktorat']) && !empty($detail_mentor['direktorat'])) {
                                                echo $detail_mentor['direktorat'];
                                            } else {
                                                echo "Direktorat tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Divisi</th>
                                        <td>
                                            <?php
                                            if (isset($detail_mentor['division']) && !empty($detail_mentor['division'])) {
                                                echo $detail_mentor['division'];
                                            } else {
                                                echo "Divisi tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Subsidiaries</th>
                                        <td>
                                            <?php
                                            if (isset($detail_mentor['subsidiaries']) && !empty($detail['subsidiaries'])) {
                                                echo $detail_mentor['subsidiaries'];
                                            } else {
                                                echo "Subsidiaries tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Job</th>
                                        <td>
                                            <?php
                                            if (isset($detail_mentor['job']) && !empty($detail_mentor['job'])) {
                                                echo $detail_mentor['job'];
                                            } else {
                                                echo "Job tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                    </table>
                <?php } elseif ($detail['status'] === 'reject') { ?>
                    <!-- Jika status belum diterima, tampilkan form pemilihan mentor -->
                    <div class="alert alert-danger" role="alert">
                        <strong>Pendaftar Ditolak</strong>
                    </div>
                <?php } else { ?>
                    <!-- Jika status belum diterima, tampilkan form pemilihan mentor -->
                    <div class="alert alert-warning" role="alert">
                        <strong>Pendaftar Belum Diterima</strong> Silakan pilih mentor jika ingin menerima pendaftar magang.
                    </div>
                    <form method="post" action="<?php echo base_url('admin/dashboard/update_status'); ?>">
                        <div class="form-group mb-4">
                            <label for="mentor" class="form-label font-weight-bold">Pilih Mentor</label>
                            <select class="form-control form-select" id="mentor" name="nipg" required>
                                <option value="" disabled selected>-- Pilih Mentor --</option>
                                <?php foreach ($list_mentor as $mentor) { ?>
                                    <option value="<?php echo $mentor['nipg']; ?>">
                                        <?php echo $mentor['nipg'] . " | " . $mentor['nama'] . " | " . $mentor['division']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                <?php } ?>

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
                            <td><?php echo formatTanggalIndo($detail['tanggal1']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td><?php echo formatTanggalIndo($detail['tanggal2']) ?></td>
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
            <!-- Tombol Terima dan Tolak -->
            <div class="mt-4">
                <div class="d-flex justify-content-end">
                    <input type="hidden" name="id" value="<?php echo $detail['id_register']; ?>">

                    <?php if ($detail['status'] === 'Accept') { ?>
                        <!-- Jika sudah diterima, hanya tampilkan tombol nonaktif hijau -->
                        <button type="button" class="btn btn-success btn-sm disabled" disabled>
                            Pendaftar Diterima
                        </button>
                    <?php } elseif ($detail['status'] === 'reject') { ?>
                        <!-- Jika sudah ditolak, tampilkan tombol merah nonaktif -->
                        <button type="button" class="btn btn-danger btn-sm disabled" disabled>
                            Pendaftar Ditolak
                        </button>
                    <?php } else { ?>
                        <?php if ($detail['timeline'] !== 'Pencarian Mentor') { ?>
                            <!-- Jika belum ada keputusan, tampilkan tombol aktif untuk aksi -->
                            <button type="button" class="btn btn-success btn-sm ml-2" data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="setAction('Accept')">
                                Kirim
                            </button>
                            <button type="button" class="btn btn-danger btn-sm ml-2" data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="setAction('reject')">
                                Tolak
                            </button>
                        <?php } ?>
                    <?php } ?>
                </div>
                </form>
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

<script>
    let actionType = '';

    // Atur nilai aksi saat tombol diklik
    function setAction(action) {
        actionType = action; // Simpan aksi yang dipilih
    }

    document.getElementById('confirmAction').addEventListener('click', function() {
        // Ambil nilai nipg dari select
        const nipgValue = document.getElementById('mentor').value;

        if (actionType && nipgValue) {
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

            const nipgField = document.createElement('input');
            nipgField.type = 'hidden';
            nipgField.name = 'nipg';
            nipgField.value = nipgValue;

            // Tambahkan semua field ke form
            form.appendChild(idField);
            form.appendChild(actionField);
            form.appendChild(nipgField);

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