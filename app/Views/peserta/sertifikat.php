<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Cetak Sertifikat</h1>
    <p class="mb-4">
        Halaman ini digunakan untuk mencetak sertifikat setelah Anda menyelesaikan program magang.
        <br>
        Anda tidak bisa mencetak sertifikat jika kegiatan Anda belum selesai.
    </p>

    <!-- DataTales Example -->
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
                        <?php if ($is_completed): ?>
                            <a href="<?php echo base_url('dashboard/generate_sertifikat/' . $id_magang) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-file-pdf"></i> Unduh Sertifikat (PDF)
                            </a>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                <strong><?php echo $message; ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- JS Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>