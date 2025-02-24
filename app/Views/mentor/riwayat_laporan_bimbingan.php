<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Riwayat Laporan Akhir Bimbingan Magang</h1>
    <p class="mb-4">
        Halaman ini menampilkan riwayat laporan akhir magang yang telah diajukan oleh mahasiswa bimbingan. Mentor dapat melihat status laporan, termasuk apakah laporan telah disetujui, ditolak, atau masih dalam proses peninjauan. Informasi ini membantu mentor dan mahasiswa dalam memantau perkembangan evaluasi laporan secara transparan.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Laporan Akhir
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Grow In Utility -->
                <div class="col-lg-12">
                    <div class="card position-relative">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">

                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="alert alert-warning" role="alert">
                                    <strong>Berikut adalah file laporan akhir yang telah dikumpulkan oleh anak bimbingan anda.</strong>
                                    (Hubungi Admin jika terjadi kesalahan).
                                </div>
                                <!-- File Section -->
                                <h5 class="font-weight-bold mt-4">File Lampiran:</h5>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered"
                                        id="dataTable"
                                        width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Lampiran</th>
                                                <th>Nama</th>
                                                <th>Nama File</th>
                                                <th>Periksa</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Lampiran</th>
                                                <th>Nama</th>
                                                <th>Nama File</th>
                                                <th>Periksa</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php if (!empty($laporan)): ?>
                                                <?php
                                                $no = 1;
                                                foreach ($laporan as $item):
                                                ?>
                                                    <?php if ($item->approved_laporan_akhir != NULL): ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td>Laporan Akhir</td>
                                                            <td><?= $item->nama; ?></td>
                                                            <td><?= $item->laporan_akhir; ?></td>
                                                            <td>
                                                                <a href="<?php echo base_url('mentor/dashboard/file/' . $item->laporan_akhir); ?>" class="btn btn-primary btn-sm">Download</a>
                                                            </td>
                                                            <td>
                                                                <?php if ($item->approved_laporan_akhir === 'Y'): ?>
                                                                    <span class="badge bg-success text-white">Diterima</span>
                                                                <?php elseif ($item->approved_laporan_akhir === 'N'): ?>
                                                                    <span class="badge bg-danger text-white">Ditolak</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada laporan ditemukan</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

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

<!-- Javascript -->
<script>
    let selectedIdMagang = null;
    let actionValue = null;

    function setAction(action, idMagang) {
        selectedIdMagang = idMagang; // Simpan id_magang yang dipilih
        actionValue = action; // Simpan nilai aksi (Y/N)

        // Update isi modal berdasarkan aksi
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `Apakah Anda yakin ingin memberikan nilai <strong>${action === 'Y' ? 'DITERIMA' : 'DITOLAK'}</strong>?`;
    }

    document.getElementById('confirmAction').addEventListener('click', function() {
        if (selectedIdMagang && actionValue) {
            fetch('<?php echo base_url("/mentor/dashboard/update_status_laporan_akhir"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_magang: selectedIdMagang,
                        status: actionValue
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