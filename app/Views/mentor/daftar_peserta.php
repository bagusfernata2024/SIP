<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Peserta</h1>
    <p class="mb-4">
        Halaman ini digunakan oleh mentor untuk monitor peserta magang.
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
                            <th>Status</th>
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
                            <th>Status</th>
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
                                    <td><?= formatTanggalIndo($item->tgl_mulai); ?> -
                                        <?= formatTanggalIndo($item->tgl_selesai); ?>
                                    </td>
                                    <td style="color:white">
                                        <span class="badge 
                                    <?php
                                    if ($item->status === 'Aktif') {
                                        // Jika status Accept, cek apakah tanggal mulai magang belum terjadi
                                        $current_date = date('Y-m-d'); // Tanggal saat ini
                                        if ($item->tgl_mulai > $current_date) {
                                            echo 'bg-warning text-light'; // Warna untuk status Belum Aktif
                                            $status_text = 'Belum Aktif';
                                        } else {
                                            echo 'bg-success text-light'; // Warna untuk status Aktif
                                            $status_text = 'Aktif';
                                        }
                                    } elseif ($item->status === 'reject') {
                                        echo 'bg-danger text-light';
                                        $status_text = 'Ditolak';
                                    } elseif ($item->status === 'Selesai Magang') {
                                        echo 'bg-info text-light';
                                        $status_text = 'Selesai Magang';
                                    } else {
                                        echo 'bg-warning text-light';
                                        $status_text = 'Proses Kelengkapan Dokumen';
                                    }
                                    ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a
                                            href="<?php echo site_url('mentor/dashboard/detail_data_peserta/' . $item->encrypted_id); ?>">
                                            <button class="btn btn-success btn-sm">
                                                <i class="fas fa-search fa-sm" style="color: white; font-size: 12px;"></i>
                                            </button>
                                        </a>
                                        <br>
                                        <br>
                                        
                                        <?php if ($item->status !== 'Aktif' && $item->status !== 'Selesai Magang' && $item->status == null): ?>
                                            <button class="btn btn-success btn-sm" title="Terima"
                                                onclick="approvePeserta(<?= $item->id_magang; ?>, '<?= $item->id_register; ?>')">
                                                <i class="fas fa-check-circle fa-sm" style="color: white;"></i>
                                            </button>
                                            
                                        <?php else: ?>
                                            <span class="text-success" title="Sudah Diterima">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        <?php endif; ?>
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

    document.getElementById('confirmAction').addEventListener('click', function () {
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

    function approvePeserta(idMagang, idRegister) {
        if (confirm("Apakah Anda yakin ingin mengaktifkan peserta ini?")) {
            fetch('<?php echo base_url("mentor/dashboard/approve_peserta"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id_magang: idMagang,
                    id_register: idRegister
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Peserta berhasil diaktifkan!');
                        location.reload();
                    } else {
                        alert('Gagal mengaktifkan peserta.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada server.');
                });
        }
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>