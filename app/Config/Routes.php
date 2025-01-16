<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Landing Page
$routes->get('/', 'HalamanUtama::index');

//Login Peserta
$routes->get('/login', 'Login::index');
$routes->post('/login/proses_login_peserta', 'Login::prosesLoginPeserta');

//Login Mentor
$routes->get('/login/mentor', 'Login::mentor');
$routes->post('/login/proses_login_mentor', 'Login::prosesLoginMentor');

//Login Admin
$routes->get('/login/admin', 'Login::admin');
$routes->post('/login/proses_login_admin', 'Login::prosesLoginAdmin');

//Logout Admin
$routes->get('/admin/logout', 'Login::logoutAdmin');

//Logout Peserta
$routes->get('/logout', 'Login::logoutPeserta');

//Logout Mentor
$routes->get('/mentor/logout', 'Login::logoutMentor');

//Registrasi Peserta
$routes->get('/registrasi', 'Registrasi::index');
$routes->post('/registrasi/proses_registrasi_peserta', 'Registrasi::prosesRegistrasiPeserta');

//Registrasi Mentor
$routes->get('/registrasi/registrasi_mentor', 'Registrasi::registrasiMentor');
$routes->post('/registrasi/proses_registrasi_mentor', 'Registrasi::prosesRegistrasiMentor');

//Dashboard Admin
$routes->get('/admin/dashboard', 'Admin\Dashboard::index');
$routes->get('/admin/dashboard/data_mentor', 'Admin\Dashboard::data_mentor');
$routes->get('/admin/dashboard/data_peserta', 'Admin\Dashboard::data_peserta');
$routes->get('/admin/dashboard/detail_data_mentor/(:num)', 'Admin\Dashboard::detailDataMentor/$1');
$routes->get('/admin/dashboard/detail_data_peserta/(:num)', 'Admin\Dashboard::detailDataPeserta/$1');
$routes->get('/admin/dashboard/detail/(:num)', 'Admin\Dashboard::detail/$1');
$routes->get('/admin/dashboard/informasi_absensi/(:num)', 'Admin\Dashboard::informasiAbsensi/$1');
$routes->get('/admin/dashboard/cetak_informasi_absensi/(:num)', 'Admin\Dashboard::cetakInformasiAbsensi/$1');
$routes->get('/admin/dashboard/download_all/(:num)', 'Admin\Dashboard::downloadAll/$1');
$routes->post('/admin/dashboard/update_status/', 'Admin\Dashboard::updateStatus');
$routes->get('/admin/dashboard/file_lampiran/(:any)', 'Admin\Dashboard::file_lampiran/$1');
$routes->get('/admin/dashboard/detail_data_m_peserta/(:num)', 'Admin\Dashboard::detail_data_m_peserta/$1');
$routes->get('/admin/dashboard/informasi_m_absensi/(:num)', 'Admin\Dashboard::informasi_m_absensi/$1');
$routes->get('/admin/dashboard/informasi_m_laporan/(:num)', 'Admin\Dashboard::informasi_m_laporan/$1');
$routes->get('/admin/dashboard/file/(:any)', 'Admin\Dashboard::file_laporan/$1');
$routes->get('/admin/dashboard/informasi_m_nilai_akhir/(:num)', 'Admin\Dashboard::informasi_m_nilai_akhir/$1');
$routes->get('/admin/dashboard/cetak_informasi_nilai_akhir/(:num)', 'Admin\Dashboard::cetak_informasi_nilai_akhir/$1');
$routes->get('/admin/dashboard/perpanjang_peserta/(:num)', 'Admin\Dashboard::perpanjang_peserta/$1');
$routes->post('/admin/dashboard/perpanjang_magang', 'Admin\Dashboard::perpanjang_magang');
$routes->post('/admin/dashboard/changeStatus/(:num)', 'Admin\Dashboard::changeStatus/$1');
$routes->get('/admin/dashboard/informasi_laporan/(:num)', 'Admin\Dashboard::informasi_laporan/$1');
$routes->get('/admin/dashboard/informasi_nilai_akhir/(:num)', 'Admin\Dashboard::informasi_nilai_akhir/$1');


// $routes->get('/admin/dashboard/perpanjang_peserta/(:num)', 'Admin\Dashboard::perpanjang_peserta/$1');
// $routes->post('/admin/dashboard/perpanjang_magang/(:num)', 'Admin\Dashboard::perpanjang_magang/$1');


//Dashboard Peserta
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/profile', 'Dashboard::profile');
$routes->get('/dashboard/edit_profile', 'Dashboard::editProfile');
$routes->post('/dashboard/update_profile', 'Dashboard::updateProfile');
$routes->get('/dashboard/edit_info_bank', 'Dashboard::editInfoBank');
$routes->post('/dashboard/update_bank_info', 'Dashboard::updateBankInfo');
$routes->get('/dashboard/download_buku_rekening/(:any)', 'Dashboard::downloadBukuRekening/$1');
$routes->get('/dashboard/absensi', 'Dashboard::Absensi');
$routes->get('/peserta/laporan', 'Dashboard::Laporan');
$routes->get('/dashboard/nilai', 'Dashboard::Nilai');
$routes->post('/dashboard/checkIn', 'Dashboard::checkIn');
$routes->post('/dashboard/checkOut', 'Dashboard::checkOut');
$routes->post('/dashboard/proses_upload_laporan_akhir', 'Dashboard::proses_upload_laporan_akhir');
$routes->get('dashboard/file/(:any)', 'Dashboard::file/$1');



//Dashboard Mentor
$routes->get('/mentor/dashboard', 'DashboardMentor::index');
$routes->get('/mentor/dashboard/absensi_bimbingan', 'DashboardMentor::absensiBimbingan');
$routes->post('/mentor/dashboard/update_status_absensi', 'DashboardMentor::updateStatusAbsensi');
$routes->get('/mentor/dashboard/rekap_absensi_bimbingan', 'DashboardMentor::rekapAbsensiBimbingan');
$routes->get('/mentor/dashboard/detail_rekap_absensi_bimbingan/(:num)', 'DashboardMentor::detailRekapAbsensiBimbingan/$1');
$routes->get('/mentor/dashboard/cetak_detail_rekap_absensi_bimbingan/(:num)', 'DashboardMentor::cetakDetailRekapAbsensiBimbingan/$1');
$routes->get('/mentor/dashboard/laporan_bimbingan', 'DashboardMentor::laporanBimbingan');
$routes->post('/mentor/dashboard/update_status_laporan_akhir', 'DashboardMentor::updateStatusLaporanAkhir');
$routes->get('/mentor/dashboard/file/(:any)', 'DashboardMentor::file/$1');
$routes->post('/mentor/dashboard/update_status_laporan_akhir', 'DashboardMentor::updateStatusLaporanAkhir');
$routes->get('/mentor/dashboard/riwayat_laporan_bimbingan', 'DashboardMentor::riwayatLaporanBimbingan');
$routes->get('/mentor/dashboard/nilai_bimbingan', 'DashboardMentor::nilaiBimbingan');
$routes->get('/mentor/dashboard/riwayat_nilai_bimbingan', 'DashboardMentor::riwayatNilaiBimbingan');
$routes->get('/mentor/dashboard/detail_riwayat_nilai_bimbingan/(:num)', 'DashboardMentor::detailRiwayatNilaiBimbingan/$1');
$routes->get('mentor/dashboard/cetak_detail_riwayat_nilai_bimbingan/(:num)', 'DashboardMentor::cetakDetailRiwayatNilaiBimbingan/$1');














