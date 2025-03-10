<!-- Mulai Kontainer Data -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Sertifikat
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <!-- Grow In Utility -->
                <div class="col-lg-12">
                    <div class="card position-relative">
                        <?php if (!empty($user_register['no_sertif'])): ?>
                            <!-- Tombol untuk mengunduh sertifikat jika nomor sertifikat sudah ada -->
                            <a href="<?php echo base_url('admin/dashboard/cetak-sertifikat/' . $encrypt_id_register); ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-file-pdf"></i> Unduh Sertifikat (PDF)
                            </a>
                        <?php else: ?>


                            <!-- Tombol untuk memunculkan Modal Input Nomor Sertifikat -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inputNoSertifikatModal">
                                Masukkan Nomor Sertifikat
                            </button>

                            <!-- Modal Input Nomor Sertifikat -->
                            <div class="modal fade" id="inputNoSertifikatModal" tabindex="-1" role="dialog" aria-labelledby="inputNoSertifikatModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="inputNoSertifikatModalLabel">Masukkan Nomor Sertifikat</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="<?php echo base_url('admin/dashboard/submitNoSertifikat'); ?>">
                                            <div class="modal-body">
                                                <input type="hidden" name="id_register" value="<?php echo $user_register['id_register']; ?>">
                                                <div class="form-group">
                                                    <label for="no_sertif">Nomor Sertifikat</label>
                                                    <input type="text" class="form-control" id="no_sertif" name="no_sertif" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Nomor Sertifikat</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>