<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	<!-- Sidebar - Brand -->
	<a
		class="sidebar-brand d-flex align-items-center justify-content-center"
		href="<?php echo base_url('mentor/dashboard') ?>">
		<img src="<?php echo base_url('/assets/img/LogoPGNLandscapeWhite.svg') ?>">
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0" />

	<!-- Nav Item - Dashboard -->
	<?php $uri = service('uri'); ?>
	<li class="nav-item <?= $uri->getSegment(2) == 'dashboard' && $uri->getSegment(3) == '' ? 'active' : ''; ?>">
		<a class="nav-link" href="<?php echo site_url('mentor/dashboard'); ?>">
			<i class="fa fa-tachometer-alt"></i>
			<span>Dashboard</span>
		</a>
	</li>

	<!-- Divider -->
	<hr class="sidebar-divider" />

	<!-- Heading -->
	<div class="sidebar-heading">Peserta</div>
	<?php $uri = service('uri'); ?>
	<li class="nav-item <?= $uri->getSegment(3) == 'daftar_peserta' ? 'active' : ''; ?>">
		<a class="nav-link" href="<?= site_url('mentor/dashboard/daftar_peserta'); ?>">
			<i class="fa fa-list"></i>
			<span>Daftar Peserta</span>
		</a>
	</li>

	<hr class="sidebar-divider" />

	<div class="sidebar-heading">Absensi Peserta</div>

	<?php $uri = service('uri'); ?>
	<li class="nav-item <?= $uri->getSegment(3) == 'absensi_bimbingan' ? 'active' : ''; ?>">
		<a class="nav-link" href="<?= site_url('mentor/dashboard/absensi_bimbingan'); ?>">
			<i class="fa fa-calendar-check"></i>
			<span>Absensi Bimbingan</span>
		</a>
	</li>


	<?php $uri = service('uri'); ?>
	<li class="nav-item <?= ($uri->getSegment(3) == 'rekap_absensi_bimbingan' || $uri->getSegment(3) == 'detail_rekap_absensi_bimbingan') ? 'active' : ''; ?>">
		<a class="nav-link collapsed" href="<?= site_url('mentor/dashboard/rekap_absensi_bimbingan'); ?>">
			<i class="fa fa-history"></i>
			<span>Riwayat Absensi</span>
		</a>
	</li>


	<!-- Divider -->
	<hr class="sidebar-divider" />

	<!-- Heading -->
	<div class="sidebar-heading">Laporan Peserta</div>

	<?php $uri = service('uri'); ?>

	<li class="nav-item <?= $uri->getSegment(3) == 'laporan_bimbingan' ? 'active' : ''; ?>">
		<a class="nav-link collapsed" href="<?= site_url('mentor/dashboard/laporan_bimbingan'); ?>">
			<i class="fas fa-fw fa-file-pdf"></i>
			<span>Laporan Bimbingan</span>
		</a>
	</li>

	<?php $uri = service('uri'); ?>

	<li class="nav-item <?= $uri->getSegment(3) == 'riwayat_laporan_bimbingan' ? 'active' : ''; ?>">
		<a class="nav-link" href="<?php echo site_url('mentor/dashboard/riwayat_laporan_bimbingan'); ?>">
			<i class="fa fa-folder-open"></i>
			<span>Riwayat Laporan</span>
		</a>
	</li>

	<!-- Heading -->
	<div class="sidebar-heading">Penilaian</div>
	<?php $uri = service('uri'); ?>

	<li class="nav-item <?= $uri->getSegment(3) == 'nilai_bimbingan' ? 'active' : ''; ?>">
		<a class="nav-link collapsed" href="<?php echo site_url('mentor/dashboard/nilai_bimbingan'); ?>">
			<i class="fa fa-graduation-cap"></i>
			<span>Nilai Bimbingan</span>
		</a>
	</li>

	<?php $uri = service('uri'); ?>
	<li class="nav-item <?= ($uri->getSegment(3) == 'riwayat_nilai_bimbingan' || $uri->getSegment(3) == 'detail_riwayat_nilai_bimbingan') ? 'active' : ''; ?>">
		<a class="nav-link collapsed" href="<?php echo site_url('mentor/dashboard/riwayat_nilai_bimbingan'); ?>">
			<i class="fa fa-chart-line"></i>
			<span>Riwayat Nilai</span>
		</a>
	</li>


	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block" />

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
<!-- End of Sidebar -->

<style>
	.nav-item.active .nav-link {
		background-color: #4e73df;
		color: white;
		font-weight: bold;
	}
</style>