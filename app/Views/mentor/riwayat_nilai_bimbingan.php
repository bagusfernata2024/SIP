<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Riwayat Nilai Bimbingan Magang</h1>
    <p class="mb-4">
        Halaman ini digunakan untuk menampilkan riwayat nilai yang telah diberikan oleh mentor kepada mahasiswa bimbingan selama program magang. Mentor dapat melihat daftar nilai yang sudah diberikan berdasarkan berbagai aspek penilaian, seperti ketepatan waktu, sikap kerja, tanggung jawab, dan lainnya. Informasi ini mempermudah pemantauan dan dokumentasi penilaian terhadap mahasiswa.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Nilai Bimbingan Magang
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
                                    <strong>Berikut adalah riwayat nilai yang telah diberikan oleh mentor.</strong>
                                    (Hubungi Admin jika terjadi kesalahan).
                                </div>
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
                                            <?php if (!empty($nilai)) : ?>
                                                <?php if ($nilai[0]->perilaku != NULL && $nilai[0]->ketepatan_waktu != NULL && $nilai[0]->sikap_kerja != NULL && $nilai[0]->tanggung_jawab != NULL && $nilai[0]->kehadiran != NULL && $nilai[0]->kemampuan_kerja != NULL && $nilai[0]->keterampilan_kerja != NULL && $nilai[0]->kualitas_hasil != NULL && $nilai[0]->kemampuan_komunikasi != NULL && $nilai[0]->kerjasama != NULL && $nilai[0]->kerajinan != NULL && $nilai[0]->percaya_diri != NULL && $nilai[0]->mematuhi_aturan != NULL && $nilai[0]->penampilan != NULL && $nilai[0]->perilaku != NULL): ?>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($nilai as $item):
                                                    ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= $item->nomor; ?></td>
                                                            <td><?= $item->nama; ?></td>
                                                            <td><?= $item->instansi; ?></td>
                                                            <td><?= formatTanggalIndo($item->tanggal1); ?> - <?= formatTanggalIndo($item->tanggal2); ?></td>
                                                            <td>
                                                                <span class="badge <?= $item->status === 'Lulus' ? 'bg-success' : 'bg-danger'; ?> text-white">
                                                                    <?= $item->status; ?>
                                                                </span>
                                                            </td>
                                                            <td><a href="<?php echo site_url('mentor/dashboard/detail_riwayat_nilai_bimbingan/' . $item->id_magang); ?>">
                                                                    <button class="btn btn-success btn-sm">
                                                                        <i class="fas fa-search" style="color: white;"></i>
                                                                    </button></a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada nilai ditemukan</td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php elseif (empty($nilai)) : ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada nilai ditemukan</td>
                                                </tr>
                                            <?php endif ?>
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
    function openModal(idMagang) {
        document.getElementById('idMagang').value = idMagang; // Isi id_magang ke input form
        const modal = new bootstrap.Modal(document.getElementById('inputNilaiModal'));
        modal.show();
    }

    document.getElementById('nilaiForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Hentikan submit form default

        const formData = new FormData(this);

        fetch('<?php echo base_url("mentor/dashboard/simpan_nilai"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Data berhasil disimpan!');
                    location.reload(); // Reload halaman
                } else {
                    alert('Gagal menyimpan data.');
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>