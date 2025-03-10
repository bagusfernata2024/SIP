<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Riwayat Absensi Bimbingan</h1>
    <p class="mb-4">
        Halaman ini digunakan oleh mentor untuk merekap absensi peserta magang.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex py-3">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Periode Magang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Periode Magang</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($peserta)): ?>
                            <?php
                            $no = 1;
                            foreach ($peserta as $item):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $item->nomor; ?></td>
                                    <td><?= $item->nama; ?></td>
                                    <td><?= $item->instansi; ?></td>
                                    <td><?= formatTanggalIndo($item->tgl_mulai); ?> - <?= formatTanggalIndo($item->tgl_selesai); ?></td>
                                    <td><a href="<?php echo site_url('mentor/dashboard/detail_rekap_absensi_bimbingan/' . $item->encrypted_id); ?>">
                                            <button class="btn btn-success btn-sm">
                                                <i class="fas fa-search" style="color: white;"></i>
                                            </button></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada absensi ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dinamis konten modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmAction">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    let selectedIdMagang = null;
    let actionValue = null;

    function setAction(action, idMagang) {
        selectedIdMagang = idMagang; // Simpan id_magang yang dipilih
        actionValue = action; // Simpan nilai aksi (Y/N)

        // Update isi modal berdasarkan aksi
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `Apakah Anda yakin ingin mengirimkan nilai <strong>${action === 'Y' ? 'Approved (Y)' : 'Rejected (N)'}</strong>?`;
    }

    document.getElementById('confirmAction').addEventListener('click', function() {
        if (selectedIdMagang && actionValue) {
            // Kirim data ke backend melalui fetch
            fetch('<?php echo base_url("mentor/dashboard/update_status_absensi"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // Untuk membedakan request AJAX
                    },
                    body: JSON.stringify({
                        id_magang: selectedIdMagang,
                        status: actionValue // Kirimkan nilai Y atau N
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status berhasil diperbarui!');
                        location.reload(); // Reload halaman setelah berhasil
                    } else {
                        alert('Gagal memperbarui status!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>