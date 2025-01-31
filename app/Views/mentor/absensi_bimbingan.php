<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Absensi Bimbingan</h1>
    <p class="mb-4">
        Halaman ini digunakan oleh mentor untuk memverifikasi dan menyetujui absensi mahasiswa bimbingan.
        Mentor dapat memeriksa geolocation yang tercatat untuk memastikan kehadiran mahasiswa sesuai dengan lokasi kegiatan.
        Tombol aksi tersedia untuk menyetujui atau menolak absensi berdasarkan hasil verifikasi.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Geolocation Masuk</th>
                            <th>Geolocation Keluar</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Geolocation Masuk</th>
                            <th>Geolocation Keluar</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($absen)): ?>
                            <?php
                            $no = 1;
                            foreach ($absen as $item):
                                // Hanya tampilkan data jika jam_masuk atau jam_pulang tidak null
                                if (!empty($item->jam_masuk) || !empty($item->jam_pulang)):
                            ?>
                                    <?php if ($item->approved == NULL): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= formatTanggalIndo($item->tgl) ?></td>
                                            <td><?= $item->nama; ?></td>
                                            <td><?= $item->jam_masuk; ?></td>
                                            <td><?= $item->jam_pulang; ?></td>
                                            <td><a href="https://www.google.com/maps?q=<?= $item->latitude_masuk; ?>,<?= $item->longitude_masuk; ?>" target="_Blank">Periksa Lokasi Masuk</a></td>
                                            <td><a href="https://www.google.com/maps?q=<?= $item->latitude_keluar; ?>,<?= $item->longitude_keluar; ?>" target="_Blank">Periksa Lokasi Keluar</a></td>
                                            <td><?= $item->deskripsi; ?></td>
                                            <td>
                                                <!-- Tombol Y -->
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="setAction('Y', <?= $item->id_magang; ?>, '<?= $item->tgl; ?>')">
                                                    <i class="fas fa-check-circle fa-sm" style="color: white;"></i> <!-- Ikon lebih kecil -->
                                                </button>
                                               
                                                <!-- Tombol N -->
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="setAction('N', <?= $item->id_magang; ?>, '<?= $item->tgl; ?>')">
                                                    <i class="fas fa-times-circle fa-sm" style="color: white;"></i> <!-- Ikon lebih kecil -->
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                            <?php
                                endif;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada absensi ditemukan</td>
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
    let selectedTgl = null;

    function setAction(action, idMagang, tgl) {
        selectedIdMagang = idMagang; // Simpan id_magang yang dipilih
        actionValue = action; // Simpan nilai aksi (Y/N)
        selectedTgl = tgl; // Simpan nilai tgl yang dipilih

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
                        status: actionValue, // Kirimkan nilai Y atau N
                        tgl: selectedTgl // Kirimkan nilai Y atau N
                    })
                })
                .then(response => response.json()) // Menangani response JSON
                .then(data => {
                    console.log(data); // Menampilkan response untuk debugging

                    if (data.success) {
                        alert('Status berhasil diperbarui!');
                        location.reload(); // Reload halaman setelah berhasil
                    } else {
                        alert('Gagal memperbarui status!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Menampilkan error jika terjadi kesalahan
                });
        }
    });

    // DataTables Initialization
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [
                [0, 'desc']
            ], // Mengurutkan berdasarkan kolom No secara descending
            "columnDefs": [{
                "targets": 0, // Kolom No harus diurutkan
                "orderable": true // Memastikan kolom pertama bisa diurutkan
            }]
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>