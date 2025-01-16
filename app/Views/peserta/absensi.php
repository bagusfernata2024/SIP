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
            <button
                class="btn btn-success <?= ($absensi_today && $absensi_today['jam_masuk']) ? 'disabled' : '' ?>"
                id="btnCheckIn"
                <?= ($absensi_today && $absensi_today['jam_masuk']) ? 'disabled' : '' ?>
                data-jam-masuk="<?= $absensi_today['jam_masuk'] ?? '' ?>">
                Check-In
            </button>
            <button
                class="btn btn-danger <?= ($absensi_today && $absensi_today['jam_masuk'] && !$absensi_today['jam_pulang']) ? '' : 'disabled' ?>"
                id="btnCheckOut"
                <?= ($absensi_today && $absensi_today['jam_masuk'] && !$absensi_today['jam_pulang']) ? '' : 'disabled' ?>
                data-jam-pulang="<?= $absensi_today['jam_pulang'] ?? '' ?>">
                Check-Out
            </button>
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
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Geolocation Masuk</th>
                                <th>Geolocation Keluar</th>
                                <th>Approved by Mentor</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Geolocation Masuk</th>
                                <th>Geolocation Keluar</th>
                                <th>Approved by Mentor</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($absensi as $absen) { ?>
                                <tr>
                                    <td><?= formatTanggalIndo($absen['tgl']) ?></td>
                                    <td><?= $absen['jam_masuk']; ?></td>
                                    <td><?= $absen['jam_pulang']; ?></td>
                                    <td><?= $absen['deskripsi']; ?></td>
                                    <td><?= $absen['statuss']; ?></td>
                                    <td>
                                        <?= $absen['latitude_masuk'] && $absen['longitude_masuk']
                                            ? '<a href="https://www.google.com/maps?q=' . $absen['latitude_masuk'] . ',' . $absen['longitude_masuk'] . '" target="_Blank">Periksa Lokasi Masuk</a>'
                                            : 'Anda belum absen'; ?>
                                    </td>
                                    <td>
                                        <?= $absen['latitude_keluar'] && $absen['longitude_keluar']
                                            ? '<a href="https://www.google.com/maps?q=' . $absen['latitude_keluar'] . ',' . $absen['longitude_keluar'] . '" target="_Blank">Periksa Lokasi Keluar</a>'
                                            : 'Anda belum absen'; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $absen['approved'] == 'Y' ? 'bg-success' : ($absen['approved'] == 'N' ? 'bg-danger' : 'bg-warning'); ?> text-white">
                                            <?= $absen['approved'] == 'Y' ? 'Diterima' : ($absen['approved'] == 'N' ? 'Ditolak' : 'Menunggu'); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
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

<!-- /.container-fluid -->

<script>
    let latitude, longitude;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setLocation, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function setLocation(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
    }

    function showError(error) {
        alert("Error occurred: " + error.message);
    }

    document.getElementById("btnCheckIn").addEventListener("click", function() {
        let jamMasuk = this.dataset.jamMasuk;
        console.log("Jam Masuk:", jamMasuk);

        getLocation();
        $('#checkInModal').modal('show');
    });


    document.getElementById("confirmCheckIn").addEventListener("click", function() {
        const currentTime = new Date().toLocaleTimeString('en-GB'); // Format HH:mm:ss
        console.log("Jam Masuk:", currentTime);

        $.ajax({
            url: '<?= base_url('dashboard/checkIn'); ?>',
            type: 'POST',
            data: {
                latitude: latitude,
                longitude: longitude,
                jam_masuk: currentTime, // Kirim waktu lokal ke server
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    // document.getElementById("btnCheckOut").addEventListener("click", function() {
    //     let jamPulang = this.dataset.jamPulang;
    //     console.log("Jam Pulang:", jamPulang);

    //     getLocation();
    //     $('#checkOutModal').modal('show');
    // });

    // document.getElementById("confirmCheckOut").addEventListener("click", function() {
    //     const currentTime = new Date().toLocaleTimeString('en-GB'); // Format HH:mm:ss
    //     console.log("Jam Keluar:", currentTime);

    //     $.ajax({
    //         url: '<?= base_url('dashboard/checkOut'); ?>',
    //         type: 'POST',
    //         data: {
    //             latitude: latitude,
    //             longitude: longitude,
    //             jam_keluar: currentTime, // Kirim waktu lokal ke server
    //         },
    //         success: function(response) {
    //             location.reload(); // Reload halaman setelah data berhasil disimpan
    //         }
    //     });
    // });


    // document.getElementById("btnCheckOut").addEventListener("click", function() {
    //     let jamPulang = this.dataset.jamPulang;
    //     console.log("Jam Pulang:", jamPulang);

    //     getLocation();
    //     $('#checkOutModal').modal('show');
    // });


    // document.getElementById("confirmCheckOut").addEventListener("click", function() {
    //     const currentTime = new Date().toLocaleTimeString('en-GB'); // Format HH:mm:ss
    //     console.log("Jam Keluar:", currentTime);

    //     $.ajax({
    //         url: '<?= base_url('dashboard/checkOut'); ?>',
    //         type: 'POST',
    //         data: {
    //             latitude: latitude,
    //             longitude: longitude,
    //             jam_keluar: currentTime, // Kirim waktu lokal ke server
    //         },
    //         success: function(response) {
    //             location.reload();
    //         }
    //     });

    //     console.log("Latitude:", latitude);
    //     console.log("Longitude:", longitude);
    //     console.log("Jam Keluar:", currentTime);
    // });

    document.getElementById("btnCheckOut").addEventListener("click", function() {
        getLocation(); // Ambil lokasi
        $('#checkOutModal').modal('show'); // Tampilkan modal checkout
    });

    document.getElementById("confirmCheckOut").addEventListener("click", function() {
        const currentTime = new Date().toLocaleTimeString('en-GB'); // Ambil waktu checkout dari browser
        const deskripsi = document.getElementById('deskripsi').value; // Ambil deskripsi dari form

        console.log("Jam Keluar:", currentTime);
        console.log("Deskripsi:", deskripsi);

        $.ajax({
            url: '<?= base_url('dashboard/checkOut'); ?>',
            type: 'POST',
            data: {
                latitude: latitude,
                longitude: longitude,
                jam_keluar: currentTime, // Kirim waktu checkout
                deskripsi: deskripsi // Kirim deskripsi
            },
            success: function(response) {
                location.reload(); // Reload halaman setelah data berhasil disimpan
            }
        });
    });


    // document.getElementById("btnCheckOut").addEventListener("click", function() {
    //     let jamPulang = this.dataset.jamPulang;
    //     console.log("Jam Pulang:", jamPulang);

    //     getLocation();
    //     $('#checkOutModal').modal('show');
    // });
    // document.getElementById("confirmCheckOut").addEventListener("click", function() {
    //     const currentTime = new Date().toLocaleTimeString('en-GB'); // Format HH:mm:ss
    //     console.log("Jam Keluar:", currentTime);

    //     $.ajax({
    //         url: '<?= base_url('dashboard/checkOut'); ?>',
    //         type: 'POST',
    //         data: {
    //             latitude: latitude,
    //             longitude: longitude,
    //             jam_keluar: currentTime, // Kirim waktu lokal ke server
    //         },
    //         success: function(response) {
    //             location.reload();
    //         }
    //     });
    // });
</script>