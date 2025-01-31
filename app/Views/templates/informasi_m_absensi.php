<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="mt-4 d-flex justify-content-start mb-4">
        <a href="<?php echo base_url('admin/dashboard/detail_data_m_peserta/' . $id_magang); ?>" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali ke Detail Peserta
        </a>
    </div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Riwayat Absensi</h1>
    <p class="mb-4">
        Halaman ini menampilkan detail riwayat absensi anak bimbibingan magang yang telah diterima / ditolak oleh mentor magang selama proses magang di PGN.
    </p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 mb-4">
            <h6 class="m-0 font-weight-bold text-primary">
                <?php if (!empty($peserta)): ?>
                    Detail riwayat absensi <?= $peserta[0]->nama ?> | <?= $peserta[0]->nomor ?> | <?= $peserta[0]->instansi ?>
                <?php else: ?>
                    Tidak ada data peserta yang ditemukan.
                <?php endif; ?>
            </h6>
        </div>
        <div class="container-fluid">
            <!-- Form Filter -->
            <form action="<?php echo base_url('admin/dashboard/informasi_m_absensi/' . $id_magang); ?>" method="get">
                <div class="form-group">
                    <label for="filter_type">Filter Berdasarkan</label>
                    <select name="filter_type" id="filter_type" class="form-control">
                        <option value="all">Semua</option>
                        <option value="7_days">7 Hari Terakhir</option>
                        <option value="1_month">1 Bulan Terakhir</option>
                        <option value="3_months">3 Bulan Terakhir</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="form-group" id="custom-date" style="display: none;">
                    <label for="start_date">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                    <label for="end_date">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Terapkan Filter</button>
            </form>

            <!-- Form Cetak -->
            <form action="<?php echo base_url('admin/dashboard/cetak_informasi_absensi/' . $id_magang); ?>" method="get" target="_blank">
                <input type="hidden" name="filter_type" id="filter_type_cetak" value="all">
                <input type="hidden" name="start_date" id="start_date_cetak">
                <input type="hidden" name="end_date" id="end_date_cetak">
                <button type="submit" class="btn btn-success mt-3"><i class="fas fa-print"></i> Cetak</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Geolocation Masuk</th>
                            <th>Geolocation Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Geolocation Masuk</th>
                            <th>Geolocation Keluar</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $no = 0 ?>
                        <?php if (!empty($peserta)): ?>
                            <?php $no = 0 ?>
                            <?php foreach ($peserta as $absen): ?>
                                <tr>
                                    <td><?= $no = $no + 1 ?></td>
                                    <td><?= $absen->tgl ?></td>
                                    <td><?= $absen->jam_masuk ?></td>
                                    <td><?= $absen->jam_pulang ?></td>
                                    <td><?= $absen->latitude_masuk ?>, <?= $absen->longitude_masuk ?></td>
                                    <td><?= $absen->latitude_keluar ?>, <?= $absen->longitude_keluar ?></td>
                                    <td><?= $absen->approved ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada riwayat absensi ditemukan untuk peserta ini</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<script>
    // Tampilkan input custom date hanya jika opsi "custom" dipilih
    const filterType = document.getElementById('filter_type');
    const customDate = document.getElementById('custom-date');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const startDateCetak = document.getElementById('start_date_cetak');
    const endDateCetak = document.getElementById('end_date_cetak');
    const filterTypeCetak = document.getElementById('filter_type_cetak');

    filterType.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDate.style.display = 'block';
        } else {
            customDate.style.display = 'none';
        }

        // Update filter di form cetak
        filterTypeCetak.value = this.value;
    });

    // Update tanggal di form cetak secara otomatis
    startDate.addEventListener('change', function() {
        startDateCetak.value = this.value;
    });
    endDate.addEventListener('change', function() {
        endDateCetak.value = this.value;
    });
</script>