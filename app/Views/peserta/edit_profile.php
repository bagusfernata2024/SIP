<div class="container-fluid">
    <!-- Page Heading -->
    <a href="<?php echo base_url('dashboard/profile'); ?>" class="btn btn-secondary btn-sm mb-4">
        <i class="fa fa-arrow-left"></i> Kembali ke Profile
    </a>
    <h1 class="h3 mb-4 text-gray-800">Edit Profil</h1>

    <!-- Edit Profile Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Detail Profil</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('dashboard/update_profile'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $data->nama ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $data->email ?>" required>
                </div>
                <div class="form-group">
                    <label for="notelp">Telepon</label>
                    <input type="text" class="form-control" id="notelp" name="notelp" value="<?= $data->notelp ?>">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $data->alamat ?></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto Profil</label>
                    <div class="mb-3">
                        <!-- Menampilkan foto sebelum diedit -->
                        <?php if (!empty($data->foto)) : ?>
                            <img src="<?= base_url('uploads/' . $data->foto); ?>" alt="Foto Profil" class="img-thumbnail" style="max-width: 150px;">
                        <?php else : ?>
                            <p class="text-muted">Foto profil belum diunggah</p>
                        <?php endif; ?>
                    </div>
                    <input type="file" class="form-control-file" id="foto" name="foto">
                    <!-- Hidden input untuk menyimpan nama file foto lama -->
                    <input type="hidden" name="foto_lama" value="<?= $data->foto ?>">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>


        </div>
    </div>
</div>