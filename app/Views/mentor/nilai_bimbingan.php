<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Penilaian Bimbingan Magang</h1>
    <p class="mb-4">
        Halaman ini digunakan oleh mentor untuk memberikan nilai kepada peserta bimbingan magang.
        Mentor dapat memeriksa dan menilai kinerja peserta berdasarkan laporan dan aktivitas mereka selama magang.
        Tombol aksi tersedia untuk menyimpan atau memperbarui penilaian.
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
                        <?php if (!empty($nilai)): ?>
                            <?php
                            $no = 1;
                            foreach ($nilai as $item):
                            ?>
                                <?php if ($item->perilaku == NULL): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $item->nomor ?></td>
                                        <td><?= $item->nama; ?></td>
                                        <td><?= $item->instansi ?></td>
                                        <td> <?= formatTanggalIndo($item->tanggal1) ?> - <?= formatTanggalIndo($item->tanggal2) ?></td>
                                        <td>
                                            <button class="btn btn-success btn-sm" onclick="openModal(<?= $item->id_magang ?>)">
                                                <i class="fas fa-edit" style="color: white;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada nilai yang harus diisi</td>
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
<div class="modal fade" id="inputNilaiModal" tabindex="-1" aria-labelledby="inputNilaiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputNilaiModalLabel">Form Penilaian</h5>
            </div>
            <form id="nilaiForm">
                <div class="modal-body">
                    <input type="hidden" id="idMagang" name="id_magang">

                    <div class="mb-3">
                        <div class="alert alert-danger" role="alert">
                            <strong>Berikan nilai rentang 0 - 100</strong>
                            (Pastikan anda memberikan nilai sesuai dengan kinerja peserta magang).
                        </div>
                    </div>

                    <!-- Ketepatan Waktu -->
                    <div class="mb-3">
                        <label for="ketepatan_waktu" class="form-label">Ketepatan Waktu</label>
                        <input type="number" class="form-control" id="ketepatan_waktu" name="ketepatan_waktu" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Sikap Kerja -->
                    <div class="mb-3">
                        <label for="sikap_kerja" class="form-label">Sikap Kerja</label>
                        <input type="number" class="form-control" id="sikap_kerja" name="sikap_kerja" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div class="mb-3">
                        <label for="tanggung_jawab" class="form-label">Tanggung Jawab</label>
                        <input type="number" class="form-control" id="tanggung_jawab" name="tanggung_jawab" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kehadiran -->
                    <div class="mb-3">
                        <label for="kehadiran" class="form-label">Kehadiran</label>
                        <input type="number" class="form-control" id="kehadiran" name="kehadiran" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kemampuan Kerja -->
                    <div class="mb-3">
                        <label for="kemampuan_kerja" class="form-label">Kemampuan Kerja</label>
                        <input type="number" class="form-control" id="kemampuan_kerja" name="kemampuan_kerja" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Keterampilan Kerja -->
                    <div class="mb-3">
                        <label for="keterampilan_kerja" class="form-label">Keterampilan Kerja</label>
                        <input type="number" class="form-control" id="keterampilan_kerja" name="keterampilan_kerja" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kualitas Hasil -->
                    <div class="mb-3">
                        <label for="kualitas_hasil" class="form-label">Kualitas Hasil</label>
                        <input type="number" class="form-control" id="kualitas_hasil" name="kualitas_hasil" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kemampuan Komunikasi -->
                    <div class="mb-3">
                        <label for="kemampuan_komunikasi" class="form-label">Kemampuan Komunikasi</label>
                        <input type="number" class="form-control" id="kemampuan_komunikasi" name="kemampuan_komunikasi" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kerjasama -->
                    <div class="mb-3">
                        <label for="kerjasama" class="form-label">Kerjasama</label>
                        <input type="number" class="form-control" id="kerjasama" name="kerjasama" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Kerajinan -->
                    <div class="mb-3">
                        <label for="kerajinan" class="form-label">Kerajinan</label>
                        <input type="number" class="form-control" id="kerajinan" name="kerajinan" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Percaya Diri -->
                    <div class="mb-3">
                        <label for="percaya_diri" class="form-label">Percaya Diri</label>
                        <input type="number" class="form-control" id="percaya_diri" name="percaya_diri" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Mematuhi Aturan -->
                    <div class="mb-3">
                        <label for="mematuhi_aturan" class="form-label">Mematuhi Aturan</label>
                        <input type="number" class="form-control" id="mematuhi_aturan" name="mematuhi_aturan" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Penampilan -->
                    <div class="mb-3">
                        <label for="penampilan" class="form-label">Penampilan</label>
                        <input type="number" class="form-control" id="penampilan" name="penampilan" min="0" max="100" step="1" required>
                        <div class="invalid-feedback">Nilai harus antara 0 hingga 100.</div>
                    </div>

                    <!-- Perilaku -->
                    <div class="mb-3">
                        <label for="perilaku" class="form-label">Perilaku</label>
                        <select class="form-control" id="perilaku" name="perilaku" required>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup Baik">Cukup Baik</option>
                            <option value="Tidak Baik">Tidak Baik</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- JavaScript -->
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