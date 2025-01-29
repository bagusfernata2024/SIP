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
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm cetakBtn">
                            <i class="bi bi-printer"></i> Cetak
                        </a>

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
                            <?php foreach ($absensi as $absen): ?>
                                <tr>
                                    <td><?= formatTanggalIndo($absen['tgl']) ?></td>
                                    <td><?= $absen['jam_masuk'] ?? '-' ?></td>
                                    <td><?= $absen['jam_pulang'] ?? '-' ?></td>
                                    <td><?= $absen['deskripsi'] ?? '-' ?></td>
                                    <td><?= $absen['statuss'] ?></td>
                                    <td>
                                        <span class="badge <?= $absen['approved'] == 'Y' ? 'bg-success' : ($absen['approved'] == 'N' ? 'bg-danger' : 'bg-warning') ?>">
                                            <?= $absen['approved'] == 'Y' ? 'Diterima' : ($absen['approved'] == 'N' ? 'Ditolak' : 'Menunggu') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Tombol Edit hanya aktif jika jam_pulang sudah ada dan status approved masih 'N' -->
                                        <?php if ($absen['jam_pulang'] && $absen['approved'] == 'N'): ?>
                                            <button class="btn btn-warning btn-sm editBtn" data-id="<?= $absen['id_absen'] ?? '' ?>" data-deskripsi="<?= $absen['deskripsi'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
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

    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            "order": [
                [0, 'desc']
            ], // Urutkan berdasarkan kolom pertama (Tanggal) secara descending
            "stateSave": true,
            "columnDefs": [{
                "targets": 0,
                "type": "date",
            }]
        });

        console.log(table.order()); // Ini akan menampilkan pengurutan kolom setelah inisialisasi
    });

    $(document).ready(function() {
        // Menangani klik tombol Edit
        $('.editBtn').on('click', function() {
            var idAbsensi = $(this).data('id');
            var deskripsi = $(this).data('deskripsi');

            // Isi nilai deskripsi di dalam modal
            $('#editDeskripsi').val(deskripsi);

            // Simpan ID absensi di dalam modal untuk digunakan saat menyimpan perubahan
            $('#saveEdit').data('id', idAbsensi);

            // Tampilkan modal
            $('#editModal').modal('show');
        });

        // Menangani klik tombol Simpan di modal
        $('#saveEdit').on('click', function() {
            var idAbsensi = $(this).data('id');
            var deskripsiBaru = $('#editDeskripsi').val();

            // Kirim data melalui AJAX untuk disimpan
            $.ajax({
                url: '<?= base_url('dashboard/updateDeskripsi'); ?>', // Sesuaikan URL dengan controller yang menangani pembaruan
                type: 'POST',
                data: {
                    id_absen: idAbsensi,
                    deskripsi: deskripsiBaru
                },
                success: function(response) {
                    if (response.success) {
                        // Menutup modal setelah berhasil
                        $('#editModal').modal('hide');
                        // Reload halaman untuk melihat perubahan
                        location.reload();
                    } else {
                        alert('Gagal memperbarui deskripsi!');
                    }
                }
            });
        });
    });


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

    $(document).ready(function() {
        // Menangani klik tombol Cetak
        $('.cetakBtn').on('click', function() {
            // Ambil hanya bagian yang ingin dicetak, dalam hal ini tabel
            var printContent = document.getElementById("dataTable").outerHTML;

            // Buat halaman baru untuk proses cetak
            var newWindow = window.open('', '', 'height=400, width=600');
            newWindow.document.write('<html><head><title>Cetak Data Absensi</title>');
            newWindow.document.write('</head><body>');
            newWindow.document.write('<h3>Data Absensi</h3>'); // Judul atau teks tambahan
            newWindow.document.write(printContent); // Tampilkan tabel yang akan dicetak
            newWindow.document.write('</body></html>');

            // Selesaikan proses cetak
            newWindow.document.close();
            newWindow.print();
        });
    });
</script>