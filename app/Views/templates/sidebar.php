<!-- Sidebar -->
<ul
	class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
	id="accordionSidebar">
	<!-- Sidebar - Brand -->
	<a
		class="sidebar-brand d-flex align-items-center justify-content-center"
		href="<?php echo base_url() ?>">
		<div class="sidebar-brand-icon rotate-n-15">
			<i class="fas fa-gas-pump"></i>
		</div>
		<div class="sidebar-brand-text mx-3">Pertamina Gas Negara</div>
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0" />

	<!-- Nav Item - Dashboard -->
	<li class="nav-item <?= (service('uri')->getSegment(3) == '' || service('uri')->getSegment(3) == 'detail') ? 'active' : ''; ?>">
		<a class="nav-link" href="<?php echo site_url('admin/dashboard'); ?>">
			<i class="fas fa-fw fa-tachometer-alt"></i>
			<span>Dashboard</span></a>
	</li>


	<!-- Divider -->
	<hr class="sidebar-divider" />

	<!-- Heading -->
	<div class="sidebar-heading">Data</div>

	<!-- Nav Item - Mentor -->
	<li class="nav-item <?= (service('uri')->getSegment(3) == 'data_mentor' ||
							service('uri')->getSegment(3) == 'detail_data_mentor' ||
							service('uri')->getSegment(3) == 'detail_data_m_peserta' ||
							service('uri')->getSegment(3) == 'informasi_m_absensi' ||
							service('uri')->getSegment(3) == 'informasi_m_laporan' ||
							service('uri')->getSegment(3) == 'informasi_m_nilai_akhir' ||
							service('uri')->getSegment(3) == 'perpanjang_peserta') ? 'active' : ''; ?>">
		<a class="nav-link" href="<?php echo site_url('admin/dashboard/data_mentor'); ?>">
			<i class="fas fa-chalkboard-teacher"></i>
			<span>Mentor</span></a>
	</li>

	<!-- Nav Item - Peserta -->
	<li class="nav-item <?= (service('uri')->getSegment(3) == 'data_peserta' ||
							service('uri')->getSegment(3) == 'detail_data_peserta' ||
							service('uri')->getSegment(3) == 'informasi_absensi' ||
							service('uri')->getSegment(3) == 'informasi_nilai_akhir' ||
							service('uri')->getSegment(3) == 'informasi_laporan') ? 'active' : ''; ?>">
		<a class="nav-link" href="<?php echo site_url('admin/dashboard/data_peserta'); ?>">
			<i class="fas fa-id-badge"></i>
			<span>Peserta</span></a>
	</li>


	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block" />

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
<!-- End of Sidebar -->