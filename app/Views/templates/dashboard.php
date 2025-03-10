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

<!-- Mulai Kontainer Data -->
<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Selamat datang <strong><?= session('nama'); ?></strong></h1>
	<p class="mb-4">
		Halaman ini menampilkan tabel pendaftar magang untuk memantau dan mengubah status peserta, mengganti mentor,
		serta melihat data statistik program seperti total pendaftar, peserta diterima, dan ditolak.
		<!-- <a target="_blank" href="<?php echo base_url(''); ?>">Halaman utama</a>. -->
	</p>

	<div class="row">
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
								Total Pendaftar
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_pendaftar ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Pendaftar Diterima
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_accept ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-check"></i>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
								Peserta Aktif
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_active ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Earnings (Annual) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
								Pendaftar Menunggu
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_waiting ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-hourglass-half"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Tasks Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
								Peserta Ditolak
							</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
										<?= $total_reject ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-times"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
								Selesai Kegiatan
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_done ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Top 10 Jurusan
								<!-- Tombol untuk menampilkan Modal -->

							</div>
							<button type="button" class="btn btn-primary" data-toggle="modal"
								data-target="#topJurusanModal">
								Lihat
							</button>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-check"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Realisasi Satker
								<!-- Tombol untuk menampilkan Modal -->

							</div>
							<button type="button" class="btn btn-primary" data-toggle="modal"
								data-target="#realisasiSatker">
								Lihat
							</button>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-check"></i>
						</div>
					</div>
				</div>
			</div>
		</div>



		<!-- Modal untuk menampilkan Top 10 Jurusan -->
		<div class="modal fade" id="topJurusanModal" tabindex="-1" role="dialog" aria-labelledby="topJurusanModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="topJurusanModalLabel">Top 10 Jurusan Terbanyak</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-bordered" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>No</th>
										<th>Jurusan</th>
										<th>Jumlah</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; ?>
									<?php foreach ($top_10_jurusan as $jurusan): ?>
										<tr>
											<td><?= $no++; ?></td>
											<td><?= $jurusan['prodi']; ?></td>
											<td><?= $jurusan['jumlah']; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal untuk menampilkan Realisasi Satker -->
		<div class="modal fade" id="realisasiSatker" tabindex="-1" role="dialog" aria-labelledby="realisasiSatkerLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="realisasiSatkerLabel">Realisasi Satker</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-bordered" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th class="text-center">No</th>
										<th class="text-center">Unit Kerja</th>
										<th class="text-center">Jumlah Peserta</th>
									</tr>
								</thead>
								<tbody id="realisasiSatkerBody">
									<!-- Data Realisasi Satker akan dimuat di sini -->
								</tbody>
							</table>

						</div>
						<!-- Pagination -->
						<div class="d-flex justify-content-between">
							<button id="previousBtn" class="btn btn-secondary" disabled>Previous</button>
							<button id="nextBtn" class="btn btn-primary">Next</button>
						</div>
					</div>

				</div>
			</div>
		</div>


		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<script>
			let currentPage = 1;  // Halaman awal
			const totalPages = <?= $totalPages; ?>;

			// Fungsi untuk memuat data Realisasi Satker menggunakan AJAX
			function loadRealisasiSatker(page) {
				$.ajax({
					url: '<?= base_url('admin/dashboard') ?>',
					type: 'GET',
					data: { page: page },
					dataType: 'json',
					success: function (response) {
						// Memperbarui bagian Realisasi Satker dalam modal dengan data yang diterima
						$('#realisasiSatkerBody').html(response.realisasi_satker);
						currentPage = page;

						// Menonaktifkan tombol Next/Previous jika sudah di halaman pertama atau terakhir
						$('#previousBtn').prop('disabled', currentPage <= 1);
						$('#nextBtn').prop('disabled', currentPage >= totalPages);
					},
					error: function () {
						alert('Terjadi kesalahan saat memuat data.');
					}
				});
			}

			// Panggil fungsi untuk memuat data saat modal sepenuhnya terbuka
			$('#realisasiSatker').on('shown.bs.modal', function () {
				loadRealisasiSatker(currentPage); // Muat data saat modal ditampilkan
			});

			// Tombol Previous
			$('#previousBtn').click(function () {
				if (currentPage > 1) {
					loadRealisasiSatker(currentPage - 1);
				}
			});

			// Tombol Next
			$('#nextBtn').click(function () {
				if (currentPage < totalPages) {
					loadRealisasiSatker(currentPage + 1);
				}
			});
		</script>

		<script>
			let currentPage = 1;
			const totalPages = <?= $totalPages; ?>;

			// Fungsi untuk memuat data Realisasi Satker menggunakan AJAX
			function loadRealisasiSatker(page) {
				$.ajax({
					url: '<?= base_url('admin/dashboard') ?>',
					type: 'GET',
					data: { page: page },
					dataType: 'json',
					success: function (response) {
						// Memperbarui bagian Realisasi Satker dalam modal dengan data yang diterima
						$('#realisasiSatkerBody').html(response.realisasi_satker);
						currentPage = page;
						// Menonaktifkan tombol Next/Previous jika sudah di halaman pertama atau terakhir
						$('#previousBtn').prop('disabled', currentPage <= 1);
						$('#nextBtn').prop('disabled', currentPage >= totalPages);
					},
					error: function () {
						alert('Terjadi kesalahan saat memuat data.');
					}
				});
			}

			// Panggil fungsi untuk memuat data saat modal dibuka
			$('#realisasiSatker').on('show.bs.modal', function () {
				loadRealisasiSatker(currentPage); // Muat data saat modal dibuka
			});

			// Tombol Previous
			$('#previousBtn').click(function () {
				if (currentPage > 1) {
					loadRealisasiSatker(currentPage - 1);
				}
			});

			// Tombol Next
			$('#nextBtn').click(function () {
				if (currentPage < totalPages) {
					loadRealisasiSatker(currentPage + 1);
				}
			});
		</script>






	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Data Pendaftar Magang</h6>
		</div>
		<!-- Mulai Kontainer Utama -->

		<div class="card-body">
			<!-- Dropdown Filter dengan Keterangan Aktif -->
			<div class="dropdown no-arrow">
				<form method="get" action="<?php echo base_url('dashboard'); ?>">
					<!-- Tombol Dropdown menampilkan pilihan aktif dari GET -->
					<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Pilih Status:
						<?php
						if (isset($_GET['status'])) {
							if ($_GET['status'] === 'Accept') {
								echo 'Diterima';
							} elseif ($_GET['status'] === 'reject') {
								echo 'Ditolak';
							} elseif ($_GET['status'] === 'null') {
								echo 'Belum Diterima';
							} elseif ($_GET['status'] === '') {
								echo 'Semua';
							}
						} else {
							echo 'Semua';
						}
						?>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="<?php echo base_url('admin/dashboard?status='); ?>">Semua</a>
						<a class="dropdown-item" href="<?php echo base_url('admin/dashboard?status=null'); ?>">Belum
							Diterima</a>
						<a class="dropdown-item"
							href="<?php echo base_url('admin/dashboard?status=Accept'); ?>">Diterima</a>
						<a class="dropdown-item"
							href="<?php echo base_url('admin/dashboard?status=reject'); ?>">Ditolak</a>
					</div>
				</form>
			</div>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Jurusan</th>
							<th>Instansi</th>
							<th>Tipe</th>
							<th>Email</th>
							<th>Registrasi</th>
							<th>Status</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Jurusan</th>
							<th>Instansi</th>
							<th>Tipe</th>
							<th>Email</th>
							<th>Registrasi</th>
							<th>Status</th>
							<th>Detail</th>
						</tr>
					</tfoot>
					<tbody>
						<?php $number = 1 ?>
						<?php foreach ($registrasi as $register): ?>
							<tr>
								<td><?php echo $number ?></td>
								<td><?php echo $register['nama'] ?></td>
								<td><?php echo $register['jurusan'] ?></td>
								<td><?php echo $register['instansi'] ?></td>
								<td><?php echo $register['tipe'] ?></td>
								<td><?php echo $register['email'] ?></td>
								<td><?php echo formatTanggalIndo($register['tgl_regis']) ?></td>
								<td style="color:#fff">
									<?php
									if ($register['status'] === 'Accept') {
										echo '<span class="badge bg-success">Diterima</span>';
									} elseif ($register['status'] === 'reject') {
										echo '<span class="badge bg-danger">Ditolak</span>';
									} else {
										echo '<span class="badge bg-warning text-light">Waiting</span>';
									}
									?>
								</td>
								<td style="text-align:center">
									<a
										href="<?php echo base_url('admin/dashboard/detail/' . $register['encrypted_id']); ?>">
										<i class="fas fa-search"></i>
									</a>
								</td>
							</tr>
							<?php $number = $number + 1 ?>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>