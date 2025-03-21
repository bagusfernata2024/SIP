<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Landing Page
$routes->get('/', 'HalamanUtama::index');
$routes->get('/cetak_word', 'Admin\Dashboard::generateSuratMagang');


//Login
$routes->get('/login', 'Login::index');
$routes->post('/login/proses_login', 'Login::loginLDAP');

//Login Peserta
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
$routes->post('/registrasi/validateEmail', 'Registrasi::validateEmail');
$routes->post('/registrasi/cek_email', 'Registrasi::cekEmail');


//Registrasi Mentor
$routes->get('/registrasi/registrasi_mentor', 'Registrasi::registrasiMentor');
$routes->post('/registrasi/proses_registrasi_mentor', 'Registrasi::prosesRegistrasiMentor');


//Dashboard Admin
$routes->get('/admin/dashboard', 'Admin\Dashboard::index');
$routes->get('admin/dashboard/send-reminder', 'Admin\Dashboard::sendReminder');
$routes->get('/admin/dashboard/data_mentor', 'Admin\Dashboard::data_mentor');
$routes->get('/admin/dashboard/data_peserta', 'Admin\Dashboard::data_peserta');
$routes->get('/admin/dashboard/detail_data_mentor/(:any)', 'Admin\Dashboard::detailDataMentor/$1');
$routes->get('/admin/dashboard/detail_data_peserta/(:any)', 'Admin\Dashboard::detailDataPeserta/$1');
$routes->get('/admin/dashboard/detail/(:any)', 'Admin\Dashboard::detail/$1');
$routes->get('/admin/dashboard/cari_mentor/(:any)', 'Admin\Dashboard::cari_mentor/$1');
$routes->post('admin/dashboard/assign_mentor/(:any)', 'Admin\Dashboard::assign_mentor/$1');
$routes->post('admin/dashboard/assign_co_mentor/(:any)', 'Admin\Dashboard::assign_co_mentor/$1');
$routes->get('/admin/dashboard/upload_surat/(:any)', 'Admin\Dashboard::upload_surat/$1');
$routes->get('/admin/dashboard/kirim_surat/(:any)', 'Admin\Dashboard::kirim_surat/$1');
$routes->get('/admin/dashboard/review_surat/(:any)', 'Admin\Dashboard::review_surat/$1');
$routes->get('/admin/dashboard/informasi_absensi/(:any)', 'Admin\Dashboard::informasiAbsensi/$1');
$routes->get('/admin/dashboard/cetak_informasi_absensi/(:any)', 'Admin\Dashboard::cetakInformasiAbsensi/$1');
$routes->get('/admin/dashboard/download_all/(:any)', 'Admin\Dashboard::downloadAll/$1');
$routes->post('/admin/dashboard/update_status/', 'Admin\Dashboard::updateStatus');
$routes->post('admin/dashboard/terima_surat_perjanjian', 'Admin\Dashboard::terima_surat_perjanjian');
$routes->get('admin/dashboard/tolak_surat_perjanjian/(:any)', 'Admin\Dashboard::tolak_surat_perjanjian/$1');
$routes->post('admin/dashboard/pilih_mentor', 'Admin\Dashboard::pilih_mentor');
$routes->get('/admin/dashboard/file_lampiran/(:any)', 'Admin\Dashboard::file_lampiran/$1');
$routes->get('/admin/dashboard/detail_data_m_peserta/(:any)', 'Admin\Dashboard::detail_data_m_peserta/$1');
$routes->get('/admin/dashboard/informasi_m_absensi/(:any)', 'Admin\Dashboard::informasi_m_absensi/$1');
$routes->get('/admin/dashboard/informasi_m_laporan/(:any)', 'Admin\Dashboard::informasi_m_laporan/$1');
$routes->get('/admin/dashboard/file/(:any)', 'Admin\Dashboard::file_laporan/$1');
$routes->get('/admin/dashboard/informasi_m_nilai_akhir/(:any)', 'Admin\Dashboard::informasi_m_nilai_akhir/$1');
$routes->get('/admin/dashboard/cetak_informasi_nilai_akhir/(:any)', 'Admin\Dashboard::cetak_informasi_nilai_akhir/$1');
$routes->get('/admin/dashboard/perpanjang_peserta/(:any)', 'Admin\Dashboard::perpanjang_peserta/$1');
$routes->post('/admin/dashboard/perpanjang_magang', 'Admin\Dashboard::perpanjang_magang');
$routes->post('/admin/dashboard/changeStatus/(:any)', 'Admin\Dashboard::changeStatus/$1');
$routes->get('/admin/dashboard/informasi_laporan/(:any)', 'Admin\Dashboard::informasi_laporan/$1');
$routes->get('/admin/dashboard/informasi_nilai_akhir/(:any)', 'Admin\Dashboard::informasi_nilai_akhir/$1');
$routes->get('/admin/dashboard/exportToExcel', 'Admin\Dashboard::exportToExcel');
$routes->get('/admin/dashboard/generateSertifikat/(:any)', 'Admin\Dashboard::generateSertifikat/$1');
$routes->get('/admin/dashboard/download_buku_rekening/(:any)', 'Admin\Dashboard::download_buku_rekening/$1');
$routes->get('/admin/dashboard/sertifikat/(:any)', 'Admin\Dashboard::sertifikat/$1');
$routes->get('/admin/dashboard/cetak-sertifikat/(:any)', 'Admin\Dashboard::cetak/$1');
$routes->post('/admin/dashboard/submitNoSertifikat', 'Admin\Dashboard::submitNoSertifikat');
$routes->post('admin/dashboard/update_tanggal_mulai', 'Admin\Dashboard::update_tanggal_mulai');
$routes->post('admin/dashboard/update_tanggal_selesai', 'Admin\Dashboard::update_tanggal_selesai');
$routes->post('admin/dashboard/upload_surat_perjanjian', 'Admin\Dashboard::upload_surat_perjanjian');
$routes->post('admin/dashboard/upload_surat_persetujuan', 'Admin\Dashboard::upload_surat_persetujuan');
$routes->post('admin/dashboard/update_satuan_kerja', 'Admin\Dashboard::update_satuan_kerja');



//Dashboard Peserta
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('registrasi/uploadSurat', 'Dashboard::uploadSurat');
$routes->get('/dashboard/profile', 'Dashboard::profile');
$routes->get('/dashboard/edit_profile', 'Dashboard::editProfile');
$routes->post('/dashboard/update_profile', 'Dashboard::updateProfile');
$routes->get('/dashboard/edit_info_bank', 'Dashboard::editInfoBank');
$routes->post('/dashboard/update_bank_info', 'Dashboard::updateBankInfo');
$routes->get('/dashboard/download_buku_rekening/(:any)', 'Dashboard::downloadBukuRekening/$1');
$routes->get('/dashboard/absensi', 'Dashboard::Absensi');
$routes->get('/peserta/laporan', 'Dashboard::Laporan');
$routes->get('/dashboard/nilai', 'Dashboard::Nilai');
$routes->get('/dashboard/cetak_nilai', 'Dashboard::cetak_nilai');
$routes->post('/dashboard/checkIn', 'Dashboard::checkIn');
$routes->post('/dashboard/checkOut', 'Dashboard::checkOut');
$routes->post('/dashboard/proses_upload_laporan_akhir', 'Dashboard::proses_upload_laporan_akhir');
$routes->get('dashboard/file/(:any)', 'Dashboard::file/$1');
$routes->post('dashboard/updateDeskripsi', 'Dashboard::updateDeskripsi');
$routes->get('dashboard/sertifikat', 'Dashboard::sertifikat');
$routes->get('dashboard/cetak_absensi', 'Dashboard::cetak_absensi');
$routes->get('dashboard/cetak-sertifikat/(:any)', 'Dashboard::cetak/$1');

//Dashboard Mentor
$routes->get('/mentor/dashboard', 'DashboardMentor::index');
$routes->get('/mentor/dashboard/daftar_peserta', 'DashboardMentor::daftarPeserta');
$routes->get('/mentor/dashboard/detail_data_peserta/(:any)', 'DashboardMentor::detailDataPeserta/$1');
$routes->get('/mentor/dashboard/file_lampiran/(:any)', 'DashboardMentor::file_lampiran/$1');
$routes->get('/mentor/dashboard/download_all/(:any)', 'DashboardMentor::downloadAll/$1');
$routes->get('/mentor/dashboard/cari_co_mentor/(:any)', 'DashboardMentor::cari_co_mentor/$1');
$routes->post('mentor/dashboard/assign_co_mentor/(:any)', 'DashboardMentor::assign_co_mentor/$1');
$routes->get('/mentor/dashboard/review_surat/(:any)', 'DashboardMentor::review_surat/$1');
$routes->post('mentor/dashboard/approve_peserta', 'DashboardMentor::approve_peserta');
$routes->get('/mentor/dashboard/absensi_bimbingan', 'DashboardMentor::absensiBimbingan');
$routes->post('/mentor/dashboard/update_status_absensi', 'DashboardMentor::updateStatusAbsensi');
$routes->get('/mentor/dashboard/rekap_absensi_bimbingan', 'DashboardMentor::rekapAbsensiBimbingan');
$routes->get('/mentor/dashboard/detail_rekap_absensi_bimbingan/(:any)', 'DashboardMentor::detailRekapAbsensiBimbingan/$1');
$routes->get('/mentor/dashboard/cetak_detail_rekap_absensi_bimbingan/(:any)', 'DashboardMentor::cetakDetailRekapAbsensiBimbingan/$1');
$routes->get('/mentor/dashboard/laporan_bimbingan', 'DashboardMentor::laporanBimbingan');
$routes->post('/mentor/dashboard/update_status_laporan_akhir', 'DashboardMentor::updateStatusLaporanAkhir');
$routes->get('/mentor/dashboard/file/(:any)', 'DashboardMentor::file/$1');
$routes->post('/mentor/dashboard/update_status_laporan_akhir', 'DashboardMentor::updateStatusLaporanAkhir');
$routes->get('/mentor/dashboard/riwayat_laporan_bimbingan', 'DashboardMentor::riwayatLaporanBimbingan');
$routes->get('/mentor/dashboard/nilai_bimbingan', 'DashboardMentor::nilaiBimbingan');
$routes->post('/mentor/dashboard/simpan_nilai', 'DashboardMentor::simpan_nilai');
$routes->get('/mentor/dashboard/riwayat_nilai_bimbingan', 'DashboardMentor::riwayatNilaiBimbingan');
$routes->get('/mentor/dashboard/detail_riwayat_nilai_bimbingan/(:any)', 'DashboardMentor::detailRiwayatNilaiBimbingan/$1');
$routes->get('mentor/dashboard/cetak_detail_riwayat_nilai_bimbingan/(:any)', 'DashboardMentor::cetakDetailRiwayatNilaiBimbingan/$1');