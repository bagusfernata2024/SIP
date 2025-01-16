<!-- Mulai Kontainer Data -->
<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Selamat datang, <strong><?= session()->get('nama'); ?></strong></h1>
	<p class="mb-4">
		Halaman ini menampilkan pesan pengingat seperti absensi, project, dan tugas.
		<!-- <a target="_blank" href="<?php echo base_url(''); ?>">Halaman utama</a>. -->
	</p>

	<div class="row">
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div
								class="text-xs font-weight-bold text-info text-uppercase mb-1">
								Total absensi yang belum di approved
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?= $total_absen_yang_belum_confirm ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-calendar-times" style="font-size:30px"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">INFORMASI</h6>
		</div>
		<!-- Mulai Kontainer Utama -->

		<div class="card-body">

		</div>

		<div class="card-body">
			<div class="table-responsive">

			</div>
		</div>
	</div>
</div>