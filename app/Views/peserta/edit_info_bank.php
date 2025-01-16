<div class="container-fluid">
    <!-- Page Heading -->
    <a href="<?php echo base_url('dashboard/profile'); ?>" class="btn btn-secondary btn-sm mb-4">
        <i class="fa fa-arrow-left"></i> Kembali ke Profile
    </a>
    <h1 class="h3 mb-4 text-gray-800">Edit Profil</h1>
    <!-- Edit Bank Information Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Informasi Rekening Bank</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('dashboard/update_bank_info'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bank">Nama Bank</label>
                    <select class="form-control" id="bank" name="bank" required>
                        <option value="">Pilih Bank</option>
                        <?php foreach ($banks as $bank): ?>
                            <!-- Cek apakah $bank sama dengan $data->bank untuk menetapkan selected -->
                            <option value="<?= $bank ?>" <?= $bank == $data->bank ? 'selected' : ''; ?>>
                                <?= $bank ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="no_rekening">Nomor Rekening</label>
                    <input type="number" class="form-control" id="no_rekening" name="no_rekening" value="<?= $data->no_rekening ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_penerima_bank">Nama Penerima</label>
                    <input type="text" class="form-control" id="nama_penerima_bank" name="nama_penerima_bank" value="<?= $data->nama_penerima_bank ?>" required>
                </div>
                <div class="form-group">
                    <label for="buku_rek">Buku Rekening (PDF)</label>

                    <?php if (!empty($data->buku_rek)): ?>
                        <!-- Tombol download yang mengarah ke controller -->
                        <div>
                            <a href="<?= base_url('dashboard/download_buku_rekening/' . $data->buku_rek) ?>" class="btn btn-success btn-sm">
                                Download Buku Rekening
                            </a>
                            <br><br>
                            <label for="buku_rek">Ganti Buku Rekening</label>
                            <input type="file" class="form-control" id="buku_rek" name="buku_rek" accept=".pdf">
                        </div>
                    <?php else: ?>
                        <input type="file" class="form-control" id="buku_rek" name="buku_rek" accept=".pdf">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>