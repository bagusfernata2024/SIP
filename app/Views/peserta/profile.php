<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profil</h1>

    <!-- Profile Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Profil</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 text-center">
                    <img
                        src="<?= base_url('uploads/' . session()->get('foto')); ?>"
                        alt="Profile Picture"
                        class="img-profile rounded-circle"
                        style="width: 100px; height: 100px;">
                </div>
                <div class="col-lg-9">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">Nama</th>
                                <td><?= $data->nama ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?= $data->email ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Telepon</th>
                                <td><?= $data->notelp ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Alamat</th>
                                <td><?= $data->alamat ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Periode Magang</th>
                                <td><?= formatTanggalIndo($data->tgl_mulai) ?> - <?= formatTanggalIndo($data->tgl_selesai) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Perpanjangan Magang</th>
                                <td>
                                    <?php if ($data->tgl_perpanjangan): ?>
                                        <?= formatTanggalIndo($data->tgl_perpanjangan) ?>
                                    <?php else: ?>
                                        Belum diperpanjang
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?= base_url('dashboard/edit_profile'); ?>" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text">Edit Profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

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
                                <td><?= !empty($data->bank) ? $data->bank : 'Anda belum mengirimkan data bank' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Nomor Rekening</th>
                                <td><?= !empty($data->no_rekening) ? $data->no_rekening : 'Anda belum mengirimkan data bank' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Nama Penerima</th>
                                <td><?= !empty($data->nama_penerima_bank) ? $data->nama_penerima_bank : 'Anda belum mengirimkan data bank' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Buku Rekening</th>
                                <td><?= !empty($data->buku_rek) ? $data->buku_rek : 'Anda belum mengirimkan data bank' ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?= base_url('dashboard/edit_info_bank'); ?>" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text">Edit Informasi Bank</span>
                    </a>
                    <?php if (!empty($data->buku_rek)): ?>
                        <a href="<?= base_url('dashboard/download_buku_rekening/' . $data->buku_rek) ?>" class="btn btn-warning btn-icon-split">
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