<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Selamat Datang, <strong><?= session()->get('nama'); ?></strong></h1>
    <p class="mb-2">Anda dapat melihat dan mengisi absensi masuk dan keluar pada tabel di bawah ini.</p>
    <?php if ($id_magang != NULL) : ?>
        <!-- Tombol Check-In dan Check-Out -->
        <div class="mb-3">
            <!-- Tombol Check-In -->
            <button
                class="btn btn-success"
                id="btnCheckIn"
                <?= ($absensi_today && !empty($absensi_today['jam_masuk'])) || $isTodayAbsent ? 'disabled' : '' ?>>
                Check-In
            </button>

            <!-- Tombol Check-Out -->
            <button
                class="btn btn-danger"
                id="btnCheckOut"
                <?= ($absensi_today && !empty($absensi_today['jam_masuk']) && empty($absensi_today['jam_pulang'])) || $isTodayAbsent ? 'disabled' : '' ?>>
                Check-Out
            </button>

            <a href="<?= base_url('dashboard/cetak_absensi') ?>" target="_blank" class="btn btn-warning">
                <i class="fas fa-file-pdf"></i> Cetak Absen
            </a>
        </div>

        <!-- Modal untuk Konfirmasi Check-In -->
        <div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="modalLabelCheckIn" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCheckIn">Konfirmasi Check-In</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin melakukan Check-In?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="confirmCheckIn">Ya</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal untuk Konfirmasi Check-Out -->
        <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="modalLabelCheckOut" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCheckOut">Konfirmasi Check-Out</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin melakukan Check-Out?</p>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="4" placeholder="Isi deskripsi aksi Anda..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmCheckOut">Ya</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Edit Deskripsi -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalLabelEdit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelEdit">Edit Deskripsi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <div class="form-group">
                                <label for="editDeskripsi">Deskripsi</label>
                                <textarea class="form-control" id="editDeskripsi" rows="4" placeholder="Isi deskripsi aksi Anda..." required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Data Absensi -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Absensi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <thead>
                            <tr>
                                <th class="sorting_desc" aria-controls="dataTable" aria-label="Tanggal: activate to sort column ascending" aria-sort="descending">Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Approved</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($absensi)): ?>
                                <?php foreach ($absensi as $absen): ?>
                                    <tr>
                                        <td><?= $absen['tgl'] ?></td>
                                        <td><?= $absen['jam_masuk'] ?? '-' ?></td>
                                        <td><?= $absen['jam_pulang'] ?? '-' ?></td>
                                        <td><?= $absen['deskripsi'] ?? '-' ?></td>
                                        <td><?= $absen['statuss'] ?></td>
                                        <td>
                                            <?php if ($absen['approved'] === 'Y'): ?>
                                                <span class="badge bg-success text-light">Diterima</span>
                                            <?php elseif ($absen['approved'] === 'N'): ?>
                                                <span class="badge bg-danger text-light">Ditolak</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Tombol Edit hanya aktif jika approved bukan Y dan deskripsi tidak null -->
                                            <?php if ($absen['approved'] !== 'Y' && !empty($absen['deskripsi'])): ?>
                                                <button class="btn btn-warning btn-sm editBtn" data-id="<?= $absen['id_absen'] ?>" data-deskripsi="<?= $absen['deskripsi'] ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Data absensi belum tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                </div>
            </div>
        <?php elseif ($id_magang == NULL) : ?>
            <div class="alert alert-warning" role="alert">
                <strong>Pendaftaran belum diterima</strong>
            </div>
        <?php endif ?>
        </div>
</div>

<script>
    let latitude, longitude;

    // Fungsi untuk mendapatkan lokasi
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setLocation, showError);
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    // Fungsi untuk menyimpan lokasi ke variabel global
    function setLocation(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        console.log("Lokasi diperoleh:", latitude, longitude);
    }

    // Fungsi untuk menangani error lokasi
    function showError(error) {
        console.error("Error mendapatkan lokasi:", error.message);
        alert("Gagal mendapatkan lokasi. Periksa pengaturan GPS Anda.");
    }

    // Event listener untuk Check-In
    document.getElementById("btnCheckIn").addEventListener("click", function() {
        getLocation();
        $('#checkInModal').modal('show');
    });

    document.getElementById("confirmCheckIn").addEventListener("click", function() {
        const currentTime = new Date().toLocaleTimeString('en-GB');
        $.ajax({
            url: '<?= base_url("dashboard/checkIn"); ?>',
            type: 'POST',
            data: {
                latitude: latitude,
                longitude: longitude,
                jam_masuk: currentTime
            },
            success: function(response) {
                alert(response.success || response.error);
                location.reload();
            },
            error: function(error) {
                console.error("Error Check-In:", error.responseText);
                alert("Gagal Check-In.");
            }
        });
    });

    // Event listener untuk Check-Out
    document.getElementById("btnCheckOut").addEventListener("click", function() {
        getLocation();
        $('#checkOutModal').modal('show');
    });

    document.getElementById("confirmCheckOut").addEventListener("click", function() {
        const currentTime = new Date().toLocaleTimeString('en-GB');
        const deskripsi = document.getElementById('deskripsi').value;
        $.ajax({
            url: '<?= base_url("dashboard/checkOut"); ?>',
            type: 'POST',
            data: {
                latitude: latitude,
                longitude: longitude,
                jam_keluar: currentTime,
                deskripsi: deskripsi
            },
            success: function(response) {
                alert(response.success || response.error);
                location.reload();
            },
            error: function(error) {
                console.error("Error Check-Out:", error.responseText);
                alert("Gagal Check-Out.");
            }
        });
    });

    // Event listener untuk tombol Edit
    document.querySelectorAll(".editBtn").forEach(function(button) {
        button.addEventListener("click", function() {
            const idAbsen = button.getAttribute("data-id");
            const deskripsi = button.getAttribute("data-deskripsi");

            // Isi data deskripsi ke modal edit
            document.getElementById("editDeskripsi").value = deskripsi;

            // Tampilkan modal Edit
            $('#editModal').modal('show');

            // Simpan perubahan deskripsi
            document.getElementById("saveEdit").addEventListener("click", function() {
                const idAbsen = document.querySelector(".editBtn").getAttribute("data-id"); // Ambil ID Absen
                const newDeskripsi = document.getElementById("editDeskripsi").value;

                $.ajax({
                    url: '<?= base_url("dashboard/updateDeskripsi"); ?>', // Endpoint untuk update deskripsi
                    type: 'POST',
                    data: {
                        id_absen: idAbsen,
                        deskripsi: newDeskripsi,
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.success); // Tampilkan pesan sukses
                            location.reload(); // Reload halaman untuk memperbarui data
                        } else {
                            alert(response.error); // Tampilkan pesan error jika ada
                        }
                    },
                    error: function(error) {
                        console.error("Error Update:", error.responseText);
                        alert("Gagal memperbarui deskripsi."); // Pesan default jika ada error
                    },
                });
            });

        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Ambil data tanggal mulai dan selesai absensi dari PHP
        const tglMulai = '<?= $tgl_mulai; ?>'; // Tanggal mulai absensi dari controller
        const tglSelesai = '<?= $tgl_selesai; ?>'; // Tanggal selesai absensi dari controller
        const today = new Date().toISOString().split('T')[0]; // Format tanggal hari ini: YYYY-MM-DD

        // Fungsi untuk mengecek apakah hari ini berada dalam rentang absensi
        function isWithinAbsencePeriod() {
            // Cek apakah hari ini dalam rentang tanggal mulai dan selesai absensi
            console.log(today);
            console.log(tglMulai);
            console.log(tglSelesai);
            console.log(today >= tglMulai && today <= tglSelesai);
            return today >= tglMulai && today <= tglSelesai;
        }

        // Fungsi untuk mengupdate status tombol Check-In dan Check-Out
        function updateButtonState() {
            const btnCheckIn = document.getElementById("btnCheckIn");
            const btnCheckOut = document.getElementById("btnCheckOut");

            // Jika hari ini tidak dalam rentang absensi, nonaktifkan tombol dan keluar
            if (!isWithinAbsencePeriod()) {
                btnCheckIn.disabled = true;
                btnCheckOut.disabled = true;
                btnCheckIn.classList.add("disabled");
                btnCheckOut.classList.add("disabled");
                console.log("Tombol Check-In dan Check-Out dinonaktifkan karena hari ini tidak dalam periode absensi.");
                return; // Hentikan eksekusi lebih lanjut jika hari ini tidak dalam rentang absensi
            }

            // Logika untuk Clock-In (hanya aktif sebelum jam 20:00)
            const currentTime = new Date();
            const currentHour = currentTime.getHours();
            const currentMinute = currentTime.getMinutes();

            // Logika untuk Check-In (hanya aktif sebelum jam 20:00)
            if (currentHour < 20) {
                btnCheckIn.disabled = false;
                btnCheckIn.classList.remove("disabled");
            } else {
                btnCheckIn.disabled = true;
                btnCheckIn.classList.add("disabled");
            }

            // Logika untuk Check-Out (aktif hanya antara 12:00 - 17:00)
            if (currentHour >= 11 && currentHour < 21) {
                btnCheckOut.disabled = false;
                btnCheckOut.classList.remove("disabled");
            } else {
                btnCheckOut.disabled = true;
                btnCheckOut.classList.add("disabled");
            }

            // Debugging log untuk memastikan fungsi berjalan
            console.log(`Waktu sekarang: ${currentHour}:${currentMinute}`);
            console.log(`Clock-In: ${!btnCheckIn.disabled}, Clock-Out: ${!btnCheckOut.disabled}`);
        }

        // Panggil fungsi updateButtonState untuk memeriksa status tombol saat halaman pertama kali dimuat
        updateButtonState();

        // Atur interval untuk mengecek setiap menit (60000 ms)
        setInterval(updateButtonState, 1); // Perbarui setiap menit
    });
</script>


<!-- /.container-fluid -->