<!-- Mulai Kontainer Data -->
<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Selamat datang, <strong><?= session()->get('nama'); ?>
		</strong></h1>
	<p class="mb-4">
		Halaman ini menampilkan tabel pendaftar magang untuk memantau dan mengubah status peserta, mengganti mentor, serta melihat data statistik program seperti total pendaftar, peserta diterima, dan ditolak.
		<!-- <a target="_blank" href="<?php echo base_url(''); ?>">Halaman utama</a>. -->
	</p>

	<div class="row">
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/absensi_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-info text-uppercase mb-1">
									Total Anak Bimbingan
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_anak_bimbingan ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fas fa-id-badge" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/absensi_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-success text-uppercase mb-1">
									Total Anak Bimbingan Aktif
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_anak_bimbingan_aktif ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fas fa-id-badge" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/absensi_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Total Anak Bimbingan Tidak Aktif
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_anak_bimbingan_tidak_aktif ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fas fa-id-badge" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/absensi_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-info text-uppercase mb-1">
									Total Absensi Belum di Konfirmasi
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_absen_yang_belum_confirm ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fas fa-calendar-times" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/laporan_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-warning text-uppercase mb-1">
									Total laporan belum di konfirmasi
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_laporan_yang_belum_confirm ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fas fa-fw fa-file-pdf" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="<?php echo site_url('mentor/dashboard/nilai_bimbingan'); ?>">
								<div
									class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Total penilaian yang belum diisi
								</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?= $total_nilai_yang_belum_diisi ?>
								</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-chart-line" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<!--  -->
			</div>
		</div>
	</div>
</div>