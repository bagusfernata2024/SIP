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
                                        <td> <?= formatTanggalIndo($item->tanggal1) ?> - <?= formatTanggalIndo($item->tanggal2) ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm"
                                                onclick="openModal(<?= $item->id_magang ?>, <?= $item->id_register ?>)">
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
                    <input type="hidden" id="idRegister" name="id_register">

                    <div class="mb-3">
                        <div class="alert alert-danger" role="alert">
                            <strong>Berikan nilai rentang 40 - 100</strong>
                            (Pastikan anda memberikan nilai sesuai dengan kinerja peserta magang).
                        </div>
                    </div>

                    <!-- Kehadiran -->
                    <div class="mb-3">
                        <label for="kehadiran" class="form-label">Kehadiran</label>
                        <select class="form-control" id="kehadiran" name="kehadiran" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                            <option value="80">80</option>
                            <option value="90">90</option>
                            <option value="100">100</option>
                        </select>
                        <div class="invalid-feedback">Nilai harus dipilih dari daftar yang tersedia.</div>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div class="mb-3">
                        <label for="tanggung_jawab" class="form-label">Tanggung Jawab</label>
                        <select class="form-control" id="tanggung_jawab" name="tanggung_jawab" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                            <option value="80">80</option>
                            <option value="90">90</option>
                            <option value="100">100</option>
                        </select>
                        <div class="invalid-feedback">Nilai harus dipilih dari daftar yang tersedia.</div>
                    </div>

                    <!-- Kemampuan Kerja -->
                    <div class="mb-3">
                        <label for="kemampuan_kerja" class="form-label">Kemampuan Kerja</label>
                        <select class="form-control" id="kemampuan_kerja" name="kemampuan_kerja" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                            <option value="80">80</option>
                            <option value="90">90</option>
                            <option value="100">100</option>
                        </select>
                        <div class="invalid-feedback">Nilai harus dipilih dari daftar yang tersedia.</div>
                    </div>

                    <!-- Integritas -->
                    <div class="mb-3">
                        <label for="integritas" class="form-label">Integritas</label>
                        <select class="form-control" id="integritas" name="integritas" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                            <option value="80">80</option>
                            <option value="90">90</option>
                            <option value="100">100</option>
                        </select>
                        <div class="invalid-feedback">Nilai harus dipilih dari daftar yang tersedia.</div>
                    </div>

                    <!-- Perilaku -->
                    <div class="mb-3">
                        <label for="perilaku" class="form-label">Perilaku</label>
                        <select class="form-control" id="perilaku" name="perilaku" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                            <option value="80">80</option>
                            <option value="90">90</option>
                            <option value="100">100</option>
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
    function openModal(idMagang, idRegister) {
        document.getElementById('idMagang').value = idMagang; // Isi id_magang ke input form
        document.getElementById('idRegister').value = idRegister; // Isi id_magang ke input form
        const modal = new bootstrap.Modal(document.getElementById('inputNilaiModal'));
        modal.show();
    }

    document.getElementById('nilaiForm').addEventListener('submit', function (e) {
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
                    location.reload(); // Reload halaman untuk melihat perubahan
                } else {
                    alert('Gagal menyimpan data: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>