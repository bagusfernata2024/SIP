<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start">
        <a href="<?php echo base_url('admin/dashboard/data_peserta'); ?>" class="btn btn-secondary btn-sm">
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
                    <a href="<?php echo base_url('admin/dashboard/informasi_absensi/' . $encrypt_id) ?>" class="btn btn-info mr-2">Informasi Absensi</a>
                    <a href="<?php echo base_url('admin/dashboard/informasi_laporan/' . $encrypt_id) ?>" class="btn btn-primary mr-2">Informasi Laporan</a>
                    <a href="<?php echo base_url('admin/dashboard/informasi_nilai_akhir/' . $encrypt_id) ?>" class="btn btn-warning">Informasi Nilai Akhir</a>
                </div>
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
                                    if ($detail_peserta[0]->status === 'Aktif') {
                                        // Jika status Accept, cek apakah tanggal mulai magang belum terjadi
                                        $current_date = date('Y-m-d'); // Tanggal saat ini
                                        if ($detail_peserta[0]->tgl_mulai > $current_date) {
                                            echo 'bg-warning text-light'; // Warna untuk status Belum Aktif
                                            $status_text = 'Belum Aktif';
                                        } else {
                                            echo 'bg-success text-light'; // Warna untuk status Aktif
                                            $status_text = 'Aktif';
                                        }
                                    } elseif ($detail_peserta[0]->status === 'reject') {
                                        echo 'bg-danger text-light';
                                        $status_text = 'Ditolak';
                                    } elseif ($detail_peserta[0]->status === 'Selesai Magang') {
                                        echo 'bg-info text-light';
                                        $status_text = 'Selesai Magang';
                                    } else {
                                        echo 'bg-warning text-light';
                                        $status_text = 'Menunggu Konfirmasi';
                                    }
                                    ?>">
                                    <?php echo $status_text; ?>
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
                            <td><?php echo !empty($detail_peserta[0]->surat_permohonan) ? $detail_peserta[0]->surat_permohonan : 'Tidak Ada'; ?></td>
                            <td>
                                <a href="<?= base_url('uploads/' . $detail_peserta[0]->surat_permohonan); ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Proposal Magang</td>
                            <td><?php echo !empty($detail_peserta[0]->proposal_magang) ? $detail_peserta[0]->proposal_magang : 'Tidak Ada'; ?></td>
                            <td>
                                <a href="<?= base_url('uploads/' . $detail_peserta[0]->proposal_magang); ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Curriculum Vitae (CV)</td>
                            <td><?php echo !empty($detail_peserta[0]->cv) ? $detail_peserta[0]->cv : 'Tidak Ada'; ?></td>
                            <td>
                                <a href="<?= base_url('uploads/' . $detail_peserta[0]->cv); ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Fotocopy KTP</td>
                            <td><?php echo !empty($detail_peserta[0]->fc_ktp) ? $detail_peserta[0]->fc_ktp : 'Tidak Ada'; ?></td>
                            <td>
                                <a href="<?= base_url('uploads/' . $detail_peserta[0]->fc_ktp); ?>" class="btn btn-primary btn-sm" download>Download</a>
                            </td>
                        </tr>

                        <!-- Tombol Download Semua -->
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/dashboard/download_all/' . $encrypt_id_register); ?>" class="btn btn-success btn-sm">Download Semua Lampiran</a>
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