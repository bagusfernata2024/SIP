<?php
$status = session()->getFlashdata('status');
$email = session()->getFlashdata('email');
log_message('debug', 'Flashdata status di view: ' . $status);
log_message('debug', 'Flashdata email di view: ' . $email);
?>

<main class="main">
    <!-- Hero Section -->
    <section class="hero section">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card p-4 shadow">
                        <a href="<?php echo base_url(); ?>">
                            <img src="<?php echo base_url('/'); ?>assets/img/logo-pgn.png" alt="logo-pgn" width="100px">
                        </a>
                        <h2 class="text-center mb-4">Formulir Registrasi Peserta Magang</h2>
                        <p class="text-center mb-4">Isi formulir berikut untuk mendaftar program magang.</p>

                        <form action="<?= site_url('registrasi/proses_registrasi_peserta'); ?>" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                            <!-- Tipe -->
                            <div class="mb-3">
                                <label for="tujuan" class="form-label">Tujuan</label>
                                <select class="form-select" id="tujuan" name="tipe" required>
                                    <option value="" disabled selected>Pilih tujuan...</option>
                                    <option value="Penelitian">Penelitian</option>
                                    <option value="PKL">PKL (Sedang Menjalani Studi)
                                    </option>
                                    <option value="Internship">Internship (Fresh Graduate)</option>
                                </select>
                                <div class="invalid-feedback">Silakan pilih tipe.</div>
                            </div>
                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                                <div class="invalid-feedback">Nama wajib diisi.</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    required
                                    placeholder="Harus menggunakan @gmail.com"
                                    pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
                                    required>
                                <div class="invalid-feedback">Email harus menggunakan domain @gmail.com.</div>
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="mb-3">
                                <label for="notelp" class="form-label">Nomor Telepon</label>
                                <input type="number" class="form-control" id="notelp" name="notelp" required>
                                <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                <div class="invalid-feedback">Alamat wajib diisi.</div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="jkLaki" name="jk" value="L" required>
                                        <label class="form-check-label" for="jkLaki">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="jkPerempuan" name="jk" value="P" required>
                                        <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">Pilih jenis kelamin.</div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                                <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                            </div>

                            <!-- Strata -->
                            <div class="mb-3">
                                <label for="strata" class="form-label">Strata</label>
                                <select class="form-select" id="strata" name="strata" required>
                                    <option value="" disabled selected>Pilih strata...</option>
                                    <option value="D3">Diploma (D3)</option>
                                    <option value="S1">Sarjana (S1)</option>
                                    <option value="S2">Magister (S2)</option>
                                </select>
                                <div class="invalid-feedback">Silakan pilih strata.</div>
                            </div>

                            <!-- Jurusan -->
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Fakultas / Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                                <div class="invalid-feedback">Jurusan wajib diisi.</div>
                            </div>

                            <!-- Program Studi -->
                            <div class="mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input type="text" class="form-control" id="prodi" name="prodi" required>
                                <div class="invalid-feedback">Program studi wajib diisi.</div>
                            </div>

                            <!-- Instansi -->
                            <div class="mb-3">
                                <label for="instansi" class="form-label">Sekolah / Universitas</label>
                                <input type="text" class="form-control" id="instansi" name="instansi" required>
                                <div class="invalid-feedback">Instansi wajib diisi.</div>
                            </div>

                            <!-- Lama PKL -->

                            <!-- NIM / NIS -->
                            <div class="mb-3">
                                <label for="NIM / NIS" class="form-label">NIM / NIS</label>
                                <input type="number" class="form-control" id="NIM / NIS" name="nik" required>
                                <div class="invalid-feedback">NIM / NIS wajib diisi.</div>
                            </div>

                            <!-- Tanggal PKL -->
                            <div class="mb-0">
                                <label class="form-label">Periode PKL</label>
                                <div class="input-group">
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="tanggal1"
                                        name="tanggal1"
                                        placeholder="Tanggal Mulai"
                                        min="<?= date('Y-m-d'); ?>"
                                        required
                                        onchange="updatePeriode(); document.getElementById('tanggal2').min = this.value;"
                                        required>
                                    <span class="input-group-text">sampai</span>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="tanggal2"
                                        name="tanggal2"
                                        placeholder="Tanggal Selesai"
                                        min="<?= date('Y-m-d'); ?>"
                                        required
                                        onchange="updatePeriode();"
                                        required>
                                </div>
                                <!-- Area untuk menampilkan durasi PKL dalam bulan -->
                                <div id="keteranganPeriode" class="mt-2 text-muted"></div>
                            </div>

                            <?php if (session()->getFlashdata('status')): ?>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const status = '<?= session()->getFlashdata('status') ?>';
                                        const emailPeserta = '<?= session()->getFlashdata('email') ?>'; // Ambil email peserta

                                        console.log('Status:', status); // Debugging status
                                        console.log('Email Peserta:', emailPeserta); // Debugging email

                                        if (status === 'success') {
                                            console.log('Menampilkan modal sukses');
                                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                            document.getElementById('emailPeserta').innerText = emailPeserta; // Isi email peserta
                                            successModal.show();
                                        } else if (status === 'fail') {
                                            console.log('Menampilkan modal gagal');
                                            const failModal = new bootstrap.Modal(document.getElementById('failModal'));
                                            failModal.show();
                                        }
                                    });
                                </script>

                            <?php endif; ?>

                            <script>
                                function updatePeriode() {
                                    const tanggal1 = document.getElementById('tanggal1').value;
                                    const tanggal2 = document.getElementById('tanggal2').value;

                                    // Periksa apakah kedua tanggal sudah dipilih
                                    if (tanggal1 && tanggal2) {
                                        const tgl1 = new Date(tanggal1);
                                        const tgl2 = new Date(tanggal2);

                                        // Hitung selisih waktu dalam milidetik
                                        const selisihWaktu = tgl2 - tgl1;

                                        // Konversi milidetik ke hari
                                        const hari = selisihWaktu / (1000 * 60 * 60 * 24);

                                        // Hitung bulan dari selisih hari
                                        const bulan = Math.floor(hari / 30); // Asumsinya 30 hari = 1 bulan

                                        // Tampilkan hasil
                                        if (bulan > 0) {
                                            document.getElementById('keteranganPeriode').innerText = `Periode Magang: ${bulan} Bulan`;
                                        } else {
                                            document.getElementById('keteranganPeriode').innerText = 'Periode Magang: Kurang dari 1 Bulan';
                                        }
                                    } else {
                                        document.getElementById('keteranganPeriode').innerText = '';
                                    }
                                }
                            </script>

                            <!-- Minat -->
                            <div class="mb-3">
                                <label for="minat" class="form-label">Minat Satuan Kerja</label>
                                <select class="form-select" id="minat" name="minat" required>
                                    <option value="" disabled selected>Pilih minat satuan kerja...</option>
                                    <?php foreach ($daftar_minat as $minat): ?>
                                        <option value="<?= htmlspecialchars($minat['nama_satker']); ?>"><?= htmlspecialchars($minat['nama_satker']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Silakan pilih minat satuan kerja.</div>
                            </div>


                            <!-- LAMPIRAN -->
                            <h4 class="text-center mt-4">Lampiran</h4>

                            <!-- Surat Permohonan -->
                            <div class="mb-3">
                                <label for="surat_permohonan" class="form-label">Surat Permohonan / Surat Lamaran</label>
                                <input type="file" class="form-control" id="surat_permohonan" name="surat_permohonan" accept=".pdf" required>
                                <div class="invalid-feedback">Unggah surat permohonan (PDF).</div>
                            </div>

                            <!-- Proposal Magang -->
                            <div class="mb-3">
                                <label for="proposal_magang" class="form-label">Proposal Magang / Surat Keterangan Lulus</label>
                                <input type="file" class="form-control" id="proposal_magang" name="proposal_magang" accept=".pdf" required>
                                <div class="invalid-feedback">Unggah proposal magang (PDF).</div>
                            </div>

                            <!-- CV -->
                            <div class="mb-3">
                                <label for="cv" class="form-label">CV</label>
                                <input type="file" class="form-control" id="cv" name="cv" accept=".pdf" required>
                                <div class="invalid-feedback">Unggah CV (PDF).</div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                                <div class="invalid-feedback">Unggah Foto.</div>
                            </div>

                            <!-- FC KTP -->
                            <div class="mb-3">
                                <label for="fc_ktp" class="form-label">Fotokopi KTP</label>
                                <input type="file" class="form-control" id="fc_ktp" name="fc_ktp" accept=".pdf" required>
                                <div class="invalid-feedback">Unggah fotokopi KTP (PDF).</div>
                            </div>

                            <!-- Submit -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" id="submitButton">Daftar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Berhasil -->
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Registrasi Berhasil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Pendaftaran Anda berhasil. Silahkan menunggu informasi lebih lanjut yang akan dikirim ke email Anda:
                            <strong id="emailPeserta"></strong>.
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Gagal -->
            <div class="modal fade" id="failModal" tabindex="-1" aria-labelledby="failModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="failModalLabel">Registrasi Gagal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Terjadi kesalahan saat registrasi. Silakan periksa kembali data Anda.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Coba Lagi</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('error_message')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const errorMessage = '<?= session()->getFlashdata('error_message') ?>';
                        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        document.getElementById('errorModalBody').innerText = errorMessage; // Masukkan pesan error ke modal
                        errorModal.show();
                    });
                </script>
            <?php endif; ?>

            <!-- Modal Gagal (Error Message) -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="errorModalLabel">Registrasi Gagal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="errorModalBody">
                            <!-- Isi pesan error akan diupdate oleh JavaScript -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Coba Lagi</button>
                        </div>
                    </div>
                </div>
            </div>




        </div>

    </section><!-- /Hero Section -->


</main>