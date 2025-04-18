<!-- Sidebar -->
<ul
	class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
	id="accordionSidebar">
	<!-- Sidebar - Brand -->
	<a
		class="sidebar-brand d-flex align-items-center justify-content-center"
		href="<?php echo base_url('dashboard') ?>">
		<img src="<?php echo base_url('/assets/img/LogoPGNLandscapeWhite.svg') ?>">
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0" />
	<?php if ($anak_magang == null) { ?>
		<li class="nav-item <?= service('uri')->getSegment(1) == 'dashboard' && service('uri')->getSegment(2) == '' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard'); ?>">
				<i class="fa fa-tachometer-alt"></i>
				<span>Upload Surat Perjanjian</span></a>
		</li>
	<?php } elseif ($registrasi['surat_perjanjian_ttd'] !== null && $anak_magang['status'] == 'Aktif') {  ?>

		<!-- Nav Item - Dashboard -->
		<li class="nav-item <?= service('uri')->getSegment(1) == 'dashboard' && service('uri')->getSegment(2) == '' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard'); ?>">
				<i class="fa fa-tachometer-alt"></i>
				<span>Dashboard</span></a>
		</li>


		<!-- Divider -->
		<hr class="sidebar-divider" />

		<!-- Heading -->
		<div class="sidebar-heading">Akun</div>
		<li class="nav-item <?= service('uri')->getSegment(2) == 'profile' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard/profile'); ?>">
				<i class="fas fa-user fa-sm fa-fw"></i>
				<span>Profile</span></a>
		</li>
		<div class="sidebar-heading">Aktivitas</div>
		<!-- Nav Item - Absensi/Kegiatan -->
		<li class="nav-item <?= service('uri')->getSegment(2) == 'absensi' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard/absensi'); ?>">
				<i class="fas fa-fw fa-calendar-check"></i>
				<span>Absensi/Kegiatan</span></a>
		</li>


		<!-- Nav Item - Laporan -->
		<li class="nav-item <?= service('uri')->getSegment(2) == 'laporan' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('peserta/laporan'); ?>">
				<i class="fas fa-fw fa-file-pdf"></i>
				<span>Laporan</span>
			</a>
		</li>


		<!-- Heading -->
		<div class="sidebar-heading">Penilaian</div>
		<!-- Nav Item - Nilai Akhir -->
		<li class="nav-item <?= service('uri')->getSegment(2) == 'nilai' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard/nilai'); ?>">
				<i class="fas fa-graduation-cap"></i>
				<span>Nilai Akhir</span>
			</a>
		</li>

		<li class="nav-item <?= service('uri')->getSegment(2) == 'sertifikat' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard/sertifikat'); ?>">
				<i class="fas fa-file"></i>
				<span>Sertifikat</span>
			</a>
		</li>
	<?php } else { ?>
		<li class="nav-item <?= service('uri')->getSegment(1) == 'dashboard' && service('uri')->getSegment(2) == '' ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo site_url('dashboard'); ?>">
				<i class="fa fa-tachometer-alt"></i>
				<span>Upload Surat Perjanjian</span></a>
		</li>

	<?php } ?>



	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block" />

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
<!-- End of Sidebar -->