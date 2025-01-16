<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start mb-4">
        <a href="<?php echo base_url('admin/dashboard/detail_data_m_peserta/' . $id_magang); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Data Peserta
        </a>
    </div>
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
                    <i class="fas fa-user-circle fa-2x" alt="Profile Icon" style="font-size:100px"></i>
                </div>
                <div class="col-lg-9">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">Nama</th>
                                <td><?= $detail_peserta[0]->nama ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?= $detail_peserta[0]->email ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Telepon</th>
                                <td><?= $detail_peserta[0]->notelp ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Alamat</th>
                                <td><?= $detail_peserta[0]->alamat ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Periode Magang</th>
                                <td><?= formatTanggalIndo($detail_peserta[0]->tgl_mulai) ?> - <?= formatTanggalIndo($detail_peserta[0]->tgl_selesai) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Perpanjangan Magang</th>
                                <td>
                                    <?php if ($detail_peserta[0]->tgl_perpanjangan): ?>
                                        <?= formatTanggalIndo($detail_peserta[0]->tgl_perpanjangan) ?>
                                    <?php else: ?>
                                        Belum diperpanjang
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <form action="<?= base_url('admin/dashboard/perpanjang_magang') ?>" method="post">
                                        <input type="hidden" name="id_magang" value="<?= $detail_peserta[0]->id_magang ?>">
                                        <div class="form-group">
                                            <label for="tgl_perpanjangan">Pilih Tanggal Perpanjangan:</label>
                                            <input type="date" class="form-control" id="tgl_perpanjangan" name="tgl_perpanjangan" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Perpanjang</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>