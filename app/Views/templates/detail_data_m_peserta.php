<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start">
        <a href="<?php echo base_url('admin/dashboard/data_mentor'); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Data Peserta
        </a>
    </div>
    <br>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Peserta Magang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="py-3">
                    <a href="<?php echo base_url('admin/dashboard/informasi_m_absensi/' . $id_magang) ?>" class="btn btn-info mr-2">Informasi Absensi</a>
                    <a href="<?php echo base_url('admin/dashboard/informasi_m_laporan/' . $id_magang) ?>" class="btn btn-primary mr-2">Informasi Laporan</a>
                    <a href="<?php echo base_url('admin/dashboard/informasi_m_nilai_akhir/' . $id_magang) ?>" class="btn btn-warning mr-2">Informasi Nilai Akhir</a>
                    <a href="<?php echo base_url('admin/dashboard/perpanjang_peserta/' . $id_magang) ?>" class="btn btn-danger mr-2">Perpanjang</a>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#statusModal">Status</button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="statusModalLabel">Ubah Status Peserta Magang</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="<?php echo base_url('admin/dashboard/changeStatus/' . $id_magang); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="status">Pilih Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Ubah Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <tr>
                    <th>Status</th>
                    <td>
                        <?php
                        if ($detail_peserta[0]->status === 'Aktif') {
                            echo '<span class="badge bg-success text-white">Aktif Magang</span>';
                        } elseif ($detail_peserta[0]->status === 'Tidak Aktif') {
                            echo '<span class="badge bg-danger text-white">Selesai Magang</span>';
                        } else {
                            echo '<span class="badge bg-warning text-white">Belum Diterima</span>';
                        }
                        ?>
                    </td>
                </tr>

                <h5 class="font-weight-bold mt-4">Informasi Mentor:</h5>
                <table class="table table-bordered">
                    <div class="mt-4">
                        <tbody>
                            <tr>
                                <th>NIPG</th>
                                <td>
                                    <?php
                                    if (isset($detail_peserta[0]->nipg) && !empty($detail_peserta[0]->nipg)) {
                                        echo $detail_peserta[0]->nipg;
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
                                    if (isset($detail_peserta[0]->nama_mentor) && !empty($detail_peserta[0]->nama_mentor)) {
                                        echo $detail_peserta[0]->nama_mentor;
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
                                    if (isset($detail_peserta[0]->email_mentor) && !empty($detail_peserta[0]->email_mentor)) {
                                        echo $detail_peserta[0]->email_mentor;
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
                                    if (isset($detail_peserta[0]->posisi) && !empty($detail_peserta[0]->posisi)) {
                                        echo $detail_peserta[0]->posisi;
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
                                    if (isset($detail_peserta[0]->direktorat) && !empty($detail_peserta[0]->direktorat)) {
                                        echo $detail_peserta[0]->direktorat;
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
                                    if (isset($detail_peserta[0]->division) && !empty($detail_peserta[0]->division)) {
                                        echo $detail_peserta[0]->division;
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
                                    if (isset($detail_peserta[0]->subsidiaries) && !empty($detail_peserta[0]->subsidiaries)) {
                                        echo $detail_peserta[0]->subsidiaries;
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
                                    if (isset($detail_peserta[0]->job) && !empty($detail_peserta[0]->job)) {
                                        echo $detail_peserta[0]->job;
                                    } else {
                                        echo "Job tidak ditemukan";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                </table>
                <h5 class="font-weight-bold mt-4">Data Diri:</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td><?php echo $detail_peserta[0]->nama ?></td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td><?php echo $detail_peserta[0]->jurusan ?></td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td><?php echo $detail_peserta[0]->prodi ?></td>
                        </tr>
                        <tr>
                            <th>Instansi</th>
                            <td><?php echo $detail_peserta[0]->instansi ?></td>
                        </tr>
                        <tr>
                            <th>No Telepon</th>
                            <td>
                                <?php echo $detail_peserta[0]->notelp ?>
                                <a href="https://wa.me/<?php echo '62' . ltrim($detail_peserta[0]->notelp, '0'); ?>" target="_blank" class="btn btn-success btn-sm">
                                    Hubungi via WhatsApp
                                </a>

                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $detail_peserta[0]->email ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo $detail_peserta[0]->alamat ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>
                                <?php
                                if ($detail_peserta[0]->jk === 'L') {
                                    echo 'Laki-laki';
                                } elseif ($detail_peserta[0]->jk === 'P') {
                                    echo 'Perempuan';
                                } else {
                                    echo 'Tidak Diketahui';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td><?php echo formatTanggalIndo($detail_peserta[0]->tgl_lahir) ?></td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td><?php echo $detail_peserta[0]->nik ?></td>
                        </tr>
                    </tbody>
                </table>
                <h5 class="font-weight-bold mt-4">Informasi Magang:</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Strata</th>
                            <td><?php echo $detail_peserta[0]->strata ?></td>
                        </tr>
                        <tr>
                            <th>Periode Magang</th>
                            <td><?php echo $detail_peserta[0]->lama_pkl ?> Bulan</td>
                        </tr>
                        <tr>
                            <th>Tanggal Registrasi</th>
                            <td><?php echo formatTanggalIndo($detail_peserta[0]->tgl_regis) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td><?php echo formatTanggalIndo($detail_peserta[0]->tgl_mulai) ?></td>
                        </tr>
                        <?php if ($detail_peserta[0]->tgl_perpanjangan != NULL) : ?>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td><?php echo formatTanggalIndo($detail_peserta[0]->tgl_selesai) ?> <strong> ( Extend : <?php echo formatTanggalIndo($detail_peserta[0]->tgl_perpanjangan) ?> ) </strong></td>
                            </tr>
                        <?php elseif ($detail_peserta[0]->tgl_perpanjangan == NULL) : ?>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td><?php echo formatTanggalIndo($detail_peserta[0]->tgl_selesai) ?></td>
                            </tr>
                        <?php endif ?>
                        <tr>
                            <th>Status</th>
                            <td style="color:white">
                                <span class="badge 
                                <?php
                                if ($detail_peserta[0]->status === 'Accept') echo 'bg-success';
                                elseif ($detail_peserta[0]->status === 'reject') echo 'bg-danger';
                                else echo 'bg-warning text-dark';
                                ?>">
                                    <?php echo $detail_peserta[0]->status ? ucfirst($detail_peserta[0]->status) : 'Belum Diterima'; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Minat Satuan Kerja</th>
                            <td><?php echo $detail_peserta[0]->minat ?></td>
                        </tr>
                        <tr>
                            <th>Tipe Magang</th>
                            <td><?php echo $detail_peserta[0]->tipe ?></td>
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
                            <td><?php echo $detail_peserta[0]->surat_permohonan ?></td>
                            <td>
                                <a href="<?= $detail_peserta[0]->surat_permohonan; ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>

                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Proposal Magang</td>
                            <td><?php echo $detail_peserta[0]->proposal_magang ?></td>
                            <td>
                                <a href="<?= $detail_peserta[0]->proposal_magang; ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Curriculum Vitae (CV)</td>
                            <td><?php echo $detail_peserta[0]->cv ?></td>
                            <td>
                                <a href="<?= $detail_peserta[0]->cv; ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Marksheet</td>
                            <td><?php echo $detail_peserta[0]->marksheet; ?></td>
                            <td>
                                <a href="<?= $detail_peserta[0]->marksheet; ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Fotocopy KTP</td>
                            <td><?php echo $detail_peserta[0]->fc_ktp; ?></td>
                            <td>
                                <a href="<?= $detail_peserta[0]->fc_ktp; ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>

                        <!-- Tombol Download Semua -->
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/dashboard/download_all/' . $detail_peserta[0]->id_register); ?>" class="btn btn-success btn-sm">Download Semua Lampiran</a>
                        </div>
                        <br>
                    </tbody>
                </table>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <!-- Profile Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Informasi Rekening Bank</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 text-center">
                                        <i class="fas fa-user-circle fa-2x" alt="Profile Icon" style="font-size:100px"></i>
                                    </div>
                                    <div class="col-lg-9">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Nama Bank</th>
                                                    <td><?= !empty($detail_peserta[0]->bank) ? $detail_peserta[0]->bank : 'Peserta belum mengirimkan data bank' ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Nomor Rekening</th>
                                                    <td><?= !empty($detail_peserta[0]->no_rekening) ? $detail_peserta[0]->no_rekening : 'Peserta belum mengirimkan data bank' ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Nama Penerima</th>
                                                    <td><?= !empty($detail_peserta[0]->nama_penerima_bank) ? $detail_peserta[0]->nama_penerima_bank : 'Peserta belum mengirimkan data bank' ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Buku Rekening</th>
                                                    <td><?= !empty($detail_peserta[0]->buku_rek) ? $detail_peserta[0]->buku_rek : 'Peserta belum mengirimkan data bank' ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php if (!empty($detail_peserta[0]->buku_rek)): ?>
                                            <a href="<?= base_url('admin/dashboard/download_buku_rekening/' . $detail_peserta[0]->buku_rek) ?>" class="btn btn-warning btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-download"></i>
                                                </span>
                                                <span class="text">Download Buku Rekening</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>