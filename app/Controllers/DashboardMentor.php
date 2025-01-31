<?php

namespace App\Controllers;

use \DateTime;
use App\Models\AbsensiModel;
use App\Models\AnakMagangModel;
use App\Models\LaporanModel;
use App\Models\NilaiModel;
use App\Models\PesertaModel;
use App\Models\RegistrasiModel;
use App\Models\DetailRegisModel;
use App\Models\MentorModel;
use App\Models\UserModel;
use App\Libraries\PdfGenerator;
use App\Controllers\BaseController;


class DashboardMentor extends BaseController
{
    protected $session;
    protected $absensiModel;
    protected $anakMagangModel;
    protected $laporanModel;
    protected $nilaiModel;
    protected $pesertaModel;
    protected $registrasiModel;
    protected $detailRegisModel;
    protected $mentorModel;
    protected $userModel;
    protected $pdfgenerator;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->anakMagangModel = new AnakMagangModel();
        $this->laporanModel = new LaporanModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel();
        $this->registrasiModel = new RegistrasiModel();
        $this->detailRegisModel = new DetailRegisModel();
        $this->mentorModel = new MentorModel();
        $this->userModel = new UserModel();
        $this->pdfgenerator = new PdfGenerator();
        $this->session = session();

        // // Session check
        // if (!session()->get('mentor_logged_in')) {
        //     return redirect()->to('login/mentor');
        // }

        // if (session()->get('level') !== 'mentor') {
        //     session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
        //     session()->destroy();
        //     return redirect()->to('login/mentor');
        // }
    }

    public function index()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $user_nomor = session()->get('nomor');

        // Fetch data
        $total_absen_yang_belum_confirm = $this->absensiModel->getAbsenByMentorCountNotYetConfirm($user_nomor);
        $total_laporan_yang_belum_confirm = $this->laporanModel->getLaporanByMentorCountNotYetConfirm($user_nomor);
        $total_nilai_yang_belum_diisi = $this->nilaiModel->getNilaiByMentorCountNotYetFill($user_nomor);
        $total_anak_bimbingan = $this->pesertaModel->getTotalAnakBimbingan($user_nomor);
        $total_anak_bimbingan_aktif = $this->pesertaModel->getTotalAnakBimbinganAktif($user_nomor);
        $total_anak_bimbingan_tidak_aktif = $this->pesertaModel->getTotalAnakBimbinganTidakAktif($user_nomor);

        // Assign data to the view
        $data = [
            'total_absen_yang_belum_confirm' => $total_absen_yang_belum_confirm,
            'total_laporan_yang_belum_confirm' => $total_laporan_yang_belum_confirm,
            'total_nilai_yang_belum_diisi' => $total_nilai_yang_belum_diisi,
            'total_anak_bimbingan' => $total_anak_bimbingan,
            'total_anak_bimbingan_aktif' => $total_anak_bimbingan_aktif,
            'total_anak_bimbingan_tidak_aktif' => $total_anak_bimbingan_tidak_aktif
        ];

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/dashboard', $data) .
            view('mentor/footer');
    }

    public function daftarPeserta()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');
        $data['peserta'] = $this->pesertaModel->getPesertaByMentor($user_nomor);
        // dd($data['peserta']);

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/daftar_peserta', $data) .
            view('mentor/footer');
    }

    // APPROVE WITH CETAK ABSEN
    // public function approve_peserta()
    // {
    //     if ($this->request->isAJAX()) {
    //         try {
    //             $input = $this->request->getJSON();

    //             // Validasi input JSON
    //             if (!isset($input->id_magang, $input->id_register)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    //             }

    //             $idMagang = $input->id_magang;
    //             $idRegister = $input->id_register;

    //             // Load model
    //             $anakMagangModel = new \App\Models\AnakMagangModel();
    //             $detailRegisModel = new \App\Models\DetailRegisModel();
    //             $registrasiModel = new \App\Models\RegistrasiModel();
    //             $userModel = new \App\Models\UserModel();
    //             $absensiModel = new \App\Models\AbsensiModel();

    //             // Get data registrasi
    //             $registrasi = $registrasiModel->find($idRegister);
    //             if (!$registrasi) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
    //             }

    //             // Update status di tabel anak_magang
    //             if (!$anakMagangModel->update($idMagang, ['status' => 'Aktif'])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status peserta']);
    //             }

    //             // Update approved di tabel detailregis
    //             if (!$detailRegisModel->where('id_register', $idRegister)->set(['approved' => 'Y'])->update()) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data registrasi']);
    //             }

    //             // Insert data ke tabel users
    //             $username = strtolower($registrasi['tipe']) . $idRegister; // Generate username
    //             $password = bin2hex(random_bytes(4));
    //             if (!$userModel->insert([
    //                 'nomor' => $registrasi['nomor'],
    //                 'username' => $username,
    //                 'password' => password_hash($password, PASSWORD_BCRYPT),
    //                 'level' => 'user',
    //                 'aktif' => 'Y'
    //             ])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun pengguna']);
    //             }

    //             // Get data magang (tanggal mulai dan selesai)
    //             $anakMagang = $anakMagangModel->find($idMagang);
    //             if (!$anakMagang) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data magang tidak ditemukan']);
    //             }

    //             $tanggalAwal = $anakMagang['tgl_mulai'];
    //             $tanggalAkhir = $anakMagang['tgl_selesai'];

    //             // Generate range tanggal
    //             $tanggalRange = $this->generateTanggalRange($tanggalAwal, $tanggalAkhir);

    //             // Insert data absensi
    //             $dataAbsensi = [];
    //             foreach ($tanggalRange as $tanggal) {
    //                 $lastPrimaryKey = $this->absensiModel->selectMax('id_absen')->first();
    //                 $newPrimaryKey = isset($lastPrimaryKey['id_absen']) ? $lastPrimaryKey['id_absen'] + 1 : 1;
    //                 $dataAbsensi[] = [
    //                     'id_absen' => $newPrimaryKey, // AUTO_INCREMENT
    //                     'id_magang' => $idMagang,
    //                     'tgl' => $tanggal,
    //                     'approved' => 'N', // Default value
    //                     'jam_masuk' => null,
    //                     'jam_pulang' => null,
    //                     'deskripsi' => null,
    //                     'statuss' => 'Belum Absen', // Default status
    //                     'latitude_masuk' => null,
    //                     'longitude_masuk' => null,
    //                     'latitude_keluar' => null,
    //                     'longitude_keluar' => null,
    //                 ];
    //             }
    //             $absensiModel->insertBatch($dataAbsensi);

    //             // Get mentor data
    //             $mentor = $anakMagangModel->select('mentor.nama, mentor.nipg, mentor.email, mentor.division')
    //                 ->join('mentor', 'mentor.id_mentor = anak_magang.id_mentor')
    //                 ->where('anak_magang.id_magang', $idMagang)
    //                 ->first();

    //             if (!$mentor) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data mentor tidak ditemukan']);
    //             }

    //             // Send email to peserta
    //             if (!$this->sendEmailToPeserta($registrasi, 'Accept', $mentor, $username, $password)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim email ke peserta']);
    //             }

    //             return $this->response->setJSON(['success' => true, 'message' => 'Peserta berhasil diapprove dan absensi telah dibuat.']);
    //         } catch (\Exception $e) {
    //             log_message('error', 'Error saat memproses approve peserta: ' . $e->getMessage());
    //             return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
    //         }
    //     }

    //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    // }

    // // Helper untuk generate range tanggal
    // private function generateTanggalRange($startDate, $endDate)
    // {
    //     $start = new \DateTime($startDate);
    //     $end = new \DateTime($endDate);
    //     $end->modify('+1 day'); // Tambahkan 1 hari untuk menyertakan tanggal akhir
    //     $interval = new \DateInterval('P1D'); // Interval 1 hari
    //     $dateRange = new \DatePeriod($start, $interval, $end);

    //     $dates = [];
    //     foreach ($dateRange as $date) {
    //         $dates[] = $date->format('Y-m-d');
    //     }

    //     return $dates;
    // }

    // Approve + Insert Nilai + Insert Absen
    public function approve_peserta()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');

        if ($this->request->isAJAX()) {
            try {
                $input = $this->request->getJSON();

                if (!isset($input->id_magang, $input->id_register)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
                }

                $idMagang = $input->id_magang;
                $idRegister = $input->id_register;

                // Load model
                $anakMagangModel = new AnakMagangModel();
                $detailRegisModel = new DetailRegisModel();
                $registrasiModel = new RegistrasiModel();
                $userModel = new UserModel();
                $nilaiModel = new NilaiModel();
                $absenModel = new AbsensiModel();

                // Get data registrasi
                $registrasi = $registrasiModel->find($idRegister);
                if (!$registrasi) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
                }

                // Update timeline status di tabel registrasi
                $registrasiModel->updateTimelineAccMentor($idRegister, 'Kegiatan Dimulai');

                // Update timeline status di tabel registrasi
                $registrasiModel->updateStatusAccMentor($idRegister, 'Accept');

                // Update status di tabel anak_magang
                $anakMagangModel->update($idMagang, ['status' => 'Aktif']);

                // Update approved di tabel detailregis
                $detailRegisModel->where('id_register', $idRegister)->set(['approved' => 'Y'])->update();

                // Insert data ke tabel users
                $username = strtolower($registrasi['tipe']) . $idRegister;
                $password = bin2hex(random_bytes(4));
                $userModel->insert([
                    'nomor' => $registrasi['nomor'],
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'level' => 'user',
                    'aktif' => 'Y',
                    'id_register' => $idRegister
                ]);

                // Insert default nilai for the new participant
                $nilaiModel->insert([
                    'id_magang' => $idMagang,
                    // Nilai lainnya
                ]);

                // Generate absen data for the participant
                $tanggalMulai = new DateTime($registrasi['tanggal1']);
                $tanggalSelesai = new DateTime($registrasi['tanggal2']);
                $tanggalSekarang = clone $tanggalMulai;

                $absenData = [];
                while ($tanggalSekarang <= $tanggalSelesai) {
                    $absenData[] = [
                        'id_magang' => $idMagang,
                        'tgl' => $tanggalSekarang->format('Y-m-d'),
                        'approved' => 'N'
                    ];
                    $tanggalSekarang->modify('+1 day');
                }

                if (!$absenModel->insertBatch($absenData)) {
                    log_message('error', 'Insert batch error: ' . json_encode($absenModel->errors()));
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat data absen']);
                }

                //Get mentor data
                $mentor = $anakMagangModel->select('mentor.nama, mentor.nipg, mentor.email, mentor.division')
                    ->join('mentor', 'mentor.id_mentor = anak_magang.id_mentor')
                    ->where('anak_magang.id_magang', $idMagang)
                    ->first();

                if (!$mentor) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data mentor tidak ditemukan']);
                }

                // Send email to peserta
                if (!$this->sendEmailToPeserta($registrasi, 'Accept', $mentor, $username, $password)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim email ke peserta']);
                }

                return $this->response->setJSON(['success' => true, 'message' => 'Peserta berhasil diapprove']);
            } catch (\Exception $e) {
                log_message('error', 'Error saat memproses approve peserta: ' . $e->getMessage());
                return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }



    //Approve + Insert Nilai
    // public function approve_peserta()
    // {
    //     if ($this->request->isAJAX()) {
    //         try {
    //             $input = $this->request->getJSON();

    //             if (!isset($input->id_magang, $input->id_register)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    //             }

    //             $idMagang = $input->id_magang;
    //             $idRegister = $input->id_register;

    //             // Load model
    //             $anakMagangModel = new AnakMagangModel();
    //             $detailRegisModel = new DetailRegisModel();
    //             $registrasiModel = new RegistrasiModel();
    //             $userModel = new UserModel();
    //             $nilaiModel = new NilaiModel();

    //             // Get data registrasi
    //             $registrasi = $registrasiModel->find($idRegister);
    //             if (!$registrasi) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
    //             }

    //             // Update timeline status di tabel registrasi
    //             $timelineUpdated = $registrasiModel->updateTimelineAccMentor($idRegister, 'Kegiatan Dimulai');
    //             if (!$timelineUpdated) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui timeline']);
    //             }

    //             // Update status di tabel anak_magang
    //             if (!$anakMagangModel->update($idMagang, ['status' => 'Aktif'])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status peserta']);
    //             }

    //             // Update approved di tabel detailregis
    //             if (!$detailRegisModel->where('id_register', $idRegister)->set(['approved' => 'Y'])->update()) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data registrasi']);
    //             }

    //             // Insert data ke tabel users
    //             $username = strtolower($registrasi['tipe']) . $idRegister;
    //             $password = bin2hex(random_bytes(4));
    //             if (!$userModel->insert([
    //                 'nomor' => $registrasi['nomor'],
    //                 'username' => $username,
    //                 'password' => password_hash($password, PASSWORD_BCRYPT),
    //                 'level' => 'user',
    //                 'aktif' => 'Y',
    //                 'id_register' => $idRegister
    //             ])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun pengguna']);
    //             }

    //             // Insert default nilai for the new participant
    //             $nilaiData = [
    //                 'id_magang' => $idMagang,
    //                 'ketepatan_waktu' => null,
    //                 'sikap_kerja' => null,
    //                 'tanggung_jawab' => null,
    //                 'kehadiran' => null,
    //                 'kemampuan_kerja' => null,
    //                 'keterampilan_kerja' => null,
    //                 'kualitas_hasil' => null,
    //                 'kemampuan_komunikasi' => null,
    //                 'kerjasama' => null,
    //                 'kerajinan' => null,
    //                 'percaya_diri' => null,
    //                 'mematuhi_aturan' => null,
    //                 'penampilan' => null,
    //                 'perilaku' => null,
    //             ];

    //             // Insert the nilai data
    //             if (!$nilaiModel->insert($nilaiData)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan data nilai']);
    //             }

    //             // Get mentor data
    //             $mentor = $anakMagangModel->select('mentor.nama, mentor.nipg, mentor.email, mentor.division')
    //                 ->join('mentor', 'mentor.id_mentor = anak_magang.id_mentor')
    //                 ->where('anak_magang.id_magang', $idMagang)
    //                 ->first();

    //             if (!$mentor) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data mentor tidak ditemukan']);
    //             }

    //             // Send email to peserta
    //             if (!$this->sendEmailToPeserta($registrasi, 'Accept', $mentor, $username, $password)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim email ke peserta']);
    //             }

    //             return $this->response->setJSON(['success' => true, 'message' => 'Peserta berhasil diapprove']);
    //         } catch (\Exception $e) {
    //             log_message('error', 'Error saat memproses approve peserta: ' . $e->getMessage());
    //             return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
    //         }
    //     }

    //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    // 


    // public function approve_peserta()
    // {
    //     if ($this->request->isAJAX()) {
    //         try {
    //             $input = $this->request->getJSON();

    //             // Validasi input JSON
    //             if (!isset($input->id_magang, $input->id_register)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    //             }

    //             $idMagang = $input->id_magang;
    //             $idRegister = $input->id_register;

    //             // Load model
    //             $anakMagangModel = new AnakMagangModel();
    //             $detailRegisModel = new DetailRegisModel();
    //             $registrasiModel = new RegistrasiModel();
    //             $userModel = new UserModel();

    //             // Get data registrasi
    //             $registrasi = $registrasiModel->find($idRegister);
    //             if (!$registrasi) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
    //             }

    //             $registrasiModel->updateTimelineAccMentor($idRegister, 'Kegiatan Dimulai');


    //             // Update status di tabel anak_magang
    //             if (!$anakMagangModel->update($idMagang, ['status' => 'Aktif'])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status peserta']);
    //             }

    //             // Update approved di tabel detailregis
    //             if (!$detailRegisModel->where('id_register', $idRegister)->set(['approved' => 'Y'])->update()) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data registrasi']);
    //             }

    //             // Insert data ke tabel users
    //             $username = strtolower($registrasi['tipe']) . $idRegister; // Generate username
    //             $password = bin2hex(random_bytes(4));
    //             if (!$userModel->insert([
    //                 'nomor' => $registrasi['nomor'],
    //                 'username' => $username,
    //                 'password' => password_hash($password, PASSWORD_BCRYPT),
    //                 'level' => 'user',
    //                 'aktif' => 'Y'
    //             ])) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat akun pengguna']);
    //             }

    //             // Get mentor data
    //             $mentor = $anakMagangModel->select('mentor.nama, mentor.nipg, mentor.email, mentor.division')
    //                 ->join('mentor', 'mentor.id_mentor = anak_magang.id_mentor')
    //                 ->where('anak_magang.id_magang', $idMagang)
    //                 ->first();

    //             if (!$mentor) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Data mentor tidak ditemukan']);
    //             }

    //             // Send email to peserta
    //             if (!$this->sendEmailToPeserta($registrasi, 'Accept', $mentor, $username, $password)) {
    //                 return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim email ke peserta']);
    //             }

    //             return $this->response->setJSON(['success' => true, 'message' => 'Peserta berhasil diapprove']);
    //         } catch (\Exception $e) {
    //             log_message('error', 'Error saat memproses approve peserta: ' . $e->getMessage());
    //             return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
    //         }
    //     }

    //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    // }

    private function sendEmailToPeserta($peserta, $status, $mentor = null, $username = null, $password = null)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $email = \Config\Services::email();

        if (empty($peserta['email'])) {
            log_message('error', 'Email peserta tidak tersedia.');
            return false;
        }

        $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($peserta['email']);

        if ($status === 'Accept' && $mentor && $username && $password) {
            $email->setSubject('Selamat! Pendaftaran Anda Telah Diterima');
            $email->setMessage("
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                }
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin-bottom: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                .button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    font-size: 16px;
                    border-radius: 5px;
                    display: inline-block;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <p>Kepada Yth. {$peserta['nama']},</p>
            <p>Dengan hormat,</p>
            <p>Kami dengan senang hati menginformasikan bahwa pendaftaran Anda dalam program ini telah diterima.</p>
            
            <h4>Informasi Akun Anda:</h4>
            <p>Username : {$username}</p>
            <p>Password : {$password}</p>
            <br>
            <h4>Informasi Mentor Anda:</h4>
            <p>Nama : {$mentor['nama']}</p>
            <p>NIPG : {$mentor['nipg']}</p>
            <p>Email : {$mentor['email']}</p>
            <p>Satuan Kerja : {$mentor['division']}</p>
            <p>Program : {$peserta['tipe']}</p>
            <br>
            <p>Silakan login ke sistem kami menggunakan username dan password di atas untuk informasi lebih lanjut dan memulai program ini. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            <p>Terima kasih atas partisipasi Anda.</p>
            
            <p>Hormat kami,<br>Admin Program</p>
            <a href='" . base_url('login') . "' class='button'>Login ke Sistem</a>
        </body>
        </html>
        ");
        } elseif ($status === 'reject') {
            $email->setSubject('Hasil Pendaftaran Program');
            $email->setMessage("
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <p>Kepada Yth. {$peserta['nama']},</p>
            <p>Dengan hormat,</p>
            <p>Kami mengucapkan terima kasih atas minat dan partisipasi Anda dalam program ini. Namun, dengan berat hati kami sampaikan bahwa pendaftaran Anda belum dapat diterima.</p>
            <p>Kami mendorong Anda untuk tetap semangat dan terus meningkatkan kemampuan Anda. Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.</p>
            <p>Hormat kami,<br>Admin Program</p>
        </body>
        </html>
        ");
        }

        // Proses pengiriman email
        if (!$email->send()) {
            log_message('error', 'Email gagal dikirim ke ' . $peserta['email']);
            log_message('error', 'Debugger Email: ' . $email->printDebugger(['headers', 'subject', 'body']));
            return false;
        }

        log_message('info', 'Email berhasil dikirim ke ' . $peserta['email']);
        return true;
    }


    // public function approve_peserta()
    // {
    //     if ($this->request->isAJAX()) {
    //         $input = $this->request->getJSON();
    //         $idMagang = $input->id_magang;
    //         $idRegister = $input->id_register;

    //         // Load model
    //         $anakMagangModel = new \App\Models\AnakMagangModel();
    //         $detailRegisModel = new \App\Models\DetailRegisModel();
    //         $registrasiModel = new \App\Models\RegistrasiModel();
    //         $userModel = new \App\Models\UserModel();

    //         // Get data registrasi
    //         $registrasi = $registrasiModel->find($idRegister);
    //         if (!$registrasi) {
    //             return $this->response->setJSON(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
    //         }

    //         // Update status di tabel anak_magang
    //         $anakMagangModel->update($idMagang, ['status' => 'Aktif']);

    //         // Update approved di tabel detailregis
    //         $detailRegisModel->where('id_register', $idRegister)
    //             ->set(['approved' => 'Y'])
    //             ->update();

    //         // Insert data ke tabel users
    //         $username = strtolower($registrasi['tipe']) . $idRegister; // Generate username
    //         $password = 'defaultpassword'; // Default password
    //         $userModel->insert([
    //             'nomor' => $registrasi['nomor'],
    //             'username' => $username,
    //             'password' => password_hash($password, PASSWORD_BCRYPT), // Default password
    //             'level' => 'user',
    //             'aktif' => 'Y'
    //         ]);

    //         // Get mentor data (optional)
    //         $mentor = $anakMagangModel->select('mentor.nama, mentor.nipg, mentor.email, mentor.division')
    //             ->join('mentor', 'mentor.id_mentor = anak_magang.id_mentor')
    //             ->where('anak_magang.id_magang', $idMagang)
    //             ->first();

    //         // Send email to peserta
    //         $this->sendEmailToPeserta($registrasi, 'Accept', $mentor, $username, $password);

    //         return $this->response->setJSON(['success' => true]);
    //     }

    //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    // }

    // private function sendEmailToPeserta($peserta, $status, $mentor = null, $username = null, $password = null)
    // {
    //     $email = \Config\Services::email();

    //     $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
    //     $email->setTo($peserta['email']);

    //     if ($status === 'Accept' && $mentor && $username && $password) {
    //         $email->setSubject('Selamat! Pendaftaran Anda Telah Diterima');
    //         $email->setMessage("
    //         Kepada Yth. {$peserta['nama']},

    //         Dengan hormat,
    //         Kami dengan senang hati menginformasikan bahwa pendaftaran Anda dalam program ini telah diterima.

    //         Berikut adalah informasi terkait akun Anda:
    //         - **Username**: {$username}
    //         - **Password**: {$password}

    //         Berikut juga informasi terkait mentor Anda:
    //         - Nama: {$mentor['nama']}
    //         - NIPG: {$mentor['nipg']}
    //         - Email: {$mentor['email']}
    //         - Satuan Kerja: {$mentor['division']}

    //         Silakan login ke sistem kami menggunakan username dan password di atas untuk informasi lebih lanjut dan memulai program ini. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.

    //         Terima kasih atas partisipasi Anda.

    //         Hormat kami,
    //         Admin Program
    //     ");
    //     } elseif ($status === 'reject') {
    //         $email->setSubject('Hasil Pendaftaran Program');
    //         $email->setMessage("
    //         Kepada Yth. {$peserta['nama']},

    //         Dengan hormat,
    //         Kami mengucapkan terima kasih atas minat dan partisipasi Anda dalam program ini. 
    //         Namun, dengan berat hati kami sampaikan bahwa pendaftaran Anda belum dapat diterima.

    //         Kami mendorong Anda untuk tetap semangat dan terus meningkatkan kemampuan Anda. 
    //         Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.

    //         Hormat kami,
    //         Admin Program
    //     ");
    //     }

    //     // Proses pengiriman email
    //     if (!$email->send()) {
    //         // Log jika email gagal dikirim
    //         echo $email->printDebugger(['headers']);
    //         log_message('error', 'Email gagal dikirim ke ' . $peserta['email']);
    //         log_message('error', 'Debug email: ' . $email->printDebugger(['headers', 'subject', 'body']));
    //         return false; // Kembalikan false jika gagal
    //     } else {
    //         // Log jika email berhasil dikirim
    //         log_message('info', 'Email berhasil dikirim ke ' . $peserta['email']);
    //         return true; // Kembalikan true jika berhasil
    //     }
    // }


    // private function sendEmailToPeserta($peserta, $status, $mentor = null, $username = null, $password = null)
    // {
    //     $email = \Config\Services::email();

    //     $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
    //     $email->setTo($peserta['email']);

    //     if ($status === 'Accept' && $mentor && $username && $password) {
    //         $email->setSubject('Selamat! Pendaftaran Anda Telah Diterima');
    //         $email->setMessage("
    //         Kepada Yth. {$peserta['nama']},

    //         Dengan hormat,
    //         Kami dengan senang hati menginformasikan bahwa pendaftaran Anda dalam program ini telah diterima.

    //         Berikut adalah informasi terkait akun Anda:
    //         - **Username**: {$username}
    //         - **Password**: {$password}

    //         Berikut juga informasi terkait mentor Anda:
    //         - Nama: {$mentor['nama']}
    //         - NIPG: {$mentor['nipg']}
    //         - Email: {$mentor['email']}
    //         - Satuan Kerja: {$mentor['division']}

    //         Silakan login ke sistem kami menggunakan username dan password di atas untuk informasi lebih lanjut dan memulai program ini. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.

    //         Terima kasih atas partisipasi Anda.

    //         Hormat kami,
    //         Admin Program
    //     ");
    //     } elseif ($status === 'reject') {
    //         $email->setSubject('Hasil Pendaftaran Program');
    //         $email->setMessage("
    //         Kepada Yth. {$peserta['nama']},

    //         Dengan hormat,
    //         Kami mengucapkan terima kasih atas minat dan partisipasi Anda dalam program ini. 
    //         Namun, dengan berat hati kami sampaikan bahwa pendaftaran Anda belum dapat diterima.

    //         Kami mendorong Anda untuk tetap semangat dan terus meningkatkan kemampuan Anda. 
    //         Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.

    //         Hormat kami,
    //         Admin Program
    //     ");
    //     }

    //     return $email->send();
    // }

    public function absensiBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');
        $data['absen'] = $this->absensiModel->getAbsenByMentor($user_nomor);

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/absensi_bimbingan', $data) .
            view('mentor/footer');
    }

    public function updateStatusAbsensi()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        if ($this->request->isAJAX()) {
            $data = $this->request->getJSON();

            // Debugging: lihat data yang diterima
            log_message('debug', 'Received Data: ' . print_r($data, true));

            $id_magang = $data->id_magang ?? null;
            $status = $data->status ?? null;
            $tgl = $data->tgl ?? null;

            if ($id_magang && in_array($status, ['Y', 'N'])) {
                // Update status
                $this->absensiModel->updateStatusAbsensi($id_magang, $tgl, $status);

                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid.']);
            }
        } else {
            return redirect()->to('/404');
        }
    }


    public function rekapAbsensiBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');
        $data['peserta'] = $this->pesertaModel->getPesertaByMentor($user_nomor);

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/rekap_absensi_bimbingan', $data) .
            view('mentor/footer');
    }

    public function detailRekapAbsensiBimbingan($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');

        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $filter_type = $this->request->getGet('filter_type');

        if ($filter_type == '7_days') {
            $start_date = date('Y-m-d', strtotime('-7 days'));
            $end_date = date('Y-m-d');
        } elseif ($filter_type == '1_month') {
            $start_date = date('Y-m-d', strtotime('-1 month'));
            $end_date = date('Y-m-d');
        } elseif ($filter_type == '3_months') {
            $start_date = date('Y-m-d', strtotime('-3 months'));
            $end_date = date('Y-m-d');
        }

        $data = [
            'peserta' => $this->pesertaModel->getDetailAbsenPesertaByMentor($user_nomor, $id_magang, $start_date, $end_date),
            'id_magang' => $id_magang
        ];

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/detail_rekap_absensi_bimbingan', $data) .
            view('mentor/footer');
    }


    public function cetakDetailRekapAbsensiBimbingan($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');

        $user_nomor = session()->get('nomor');

        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $filter_type = $this->request->getGet('filter_type');

        if ($filter_type == '7_days') {
            $start_date = date('Y-m-d', strtotime('-7 days'));
            $end_date = date('Y-m-d');
        } elseif ($filter_type == '1_month') {
            $start_date = date('Y-m-d', strtotime('-1 month'));
            $end_date = date('Y-m-d');
        } elseif ($filter_type == '3_months') {
            $start_date = date('Y-m-d', strtotime('-3 months'));
            $end_date = date('Y-m-d');
        }

        $data = [
            'peserta' => $this->pesertaModel->getDetailAbsenPesertaByMentor($user_nomor, $id_magang, $start_date, $end_date),
            'id_magang' => $id_magang
        ];

        $this->pdfgenerator->generate(
            view('mentor/cetak_detail_rekap_absensi_bimbingan', $data),
            "Detail Rekap Absensi",
            'A4',
            'landscape'
        );
    }

    public function laporanBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $user_nomor = session()->get('nomor');
        $data['laporan'] = $this->anakMagangModel->getLaporanAkhirByMentor($user_nomor);
        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/laporan_bimbingan', $data) .
            view('mentor/footer');
    }

    public function updateStatusLaporanAkhir()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        // Ambil data yang dikirim oleh frontend
        $data = $this->request->getJSON();

        if (isset($data->id_magang) && isset($data->status)) {
            $idMagang = $data->id_magang;
            $status = $data->status;

            // Update status laporan akhir
            $updateStatus = $this->anakMagangModel->updateStatusLaporanAkhir($idMagang, $status);

            if ($updateStatus) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    }


    public function file($file_name)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $file_path = FCPATH . 'uploads/laporan/' . $file_name; // Gunakan WRITEPATH untuk folder writable

        // Debugging: Log the file path
        log_message('debug', 'Looking for file: ' . $file_path);

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan: ' . $file_path);
        }
    }


    public function riwayatLaporanBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $user_nomor = session()->get('nomor');
        $data['laporan'] = $this->anakMagangModel->getLaporanAkhirByMentor($user_nomor);


        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/riwayat_laporan_bimbingan', $data) .
            view('mentor/footer');
    }

    public function nilaiBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');
        $data['nilai'] = $this->nilaiModel->getNilaiByMentor($user_nomor);

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/nilai_bimbingan', $data) .
            view('mentor/footer');
    }

    public function simpan_nilai()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $id_magang = $this->request->getPost('id_magang');

        // Data yang diterima dari form
        $data = [
            'ketepatan_waktu' => $this->request->getPost('ketepatan_waktu'),
            'sikap_kerja' => $this->request->getPost('sikap_kerja'),
            'tanggung_jawab' => $this->request->getPost('tanggung_jawab'),
            'kehadiran' => $this->request->getPost('kehadiran'),
            'kemampuan_kerja' => $this->request->getPost('kemampuan_kerja'),
            'keterampilan_kerja' => $this->request->getPost('keterampilan_kerja'),
            'kualitas_hasil' => $this->request->getPost('kualitas_hasil'),
            'kemampuan_komunikasi' => $this->request->getPost('kemampuan_komunikasi'),
            'kerjasama' => $this->request->getPost('kerjasama'),
            'kerajinan' => $this->request->getPost('kerajinan'),
            'percaya_diri' => $this->request->getPost('percaya_diri'),
            'mematuhi_aturan' => $this->request->getPost('mematuhi_aturan'),
            'penampilan' => $this->request->getPost('penampilan'),
            'perilaku' => $this->request->getPost('perilaku'),
            'tgl_input' => date('Y-m-d'),
        ];

        log_message('debug', "ID Magang yang diterima: $id_magang");
        log_message('debug', 'Data yang diterima untuk simpan_nilai: ' . json_encode($data));

        // Panggil model untuk update nilai
        $model = new NilaiModel();

        if ($model->updateNilai($data, $id_magang)) {
            log_message('debug', "Nilai berhasil diperbarui untuk id_magang: $id_magang");

            // Update status pada tabel anak_magang menjadi 'Inactive'
            $anakMagangModel = new AnakMagangModel();

            if ($anakMagangModel->updateStatusInactive($id_magang)) {
                log_message('debug', "Status anak_magang berhasil diperbarui menjadi Inactive untuk id_magang: $id_magang");
            } else {
                log_message('error', "Gagal memperbarui status anak_magang untuk id_magang: $id_magang");
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Nilai berhasil diperbarui dan status anak_magang diubah']);
        } else {
            log_message('error', "Gagal memperbarui nilai untuk id_magang: $id_magang");
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui nilai']);
        }
    }

    public function riwayatNilaiBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');
        $user_nomor = session()->get('nomor');

        // Memuat model
        $model = new NilaiModel();

        // Mengambil data nilai
        $data['nilai'] = $model->getNilaiByMentor($user_nomor);

        foreach ($data['nilai'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/riwayat_nilai_bimbingan', $data) .
            view('mentor/footer');
    }

    public function detailRiwayatNilaiBimbingan($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');

        $user_nomor = session()->get('nomor');

        // Memuat model
        $model = new NilaiModel();

        // Mengambil nilai berdasarkan mentor
        $data['nilai_akhir'] = $model->getNilaiByMentor($user_nomor);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        // Menampilkan view
        return view('mentor/header') .
            view('mentor/sidebar') .
            view('mentor/topbar') .
            view('mentor/detail_riwayat_nilai_bimbingan', $data) .
            view('mentor/footer');
    }

    private function hitungTotalNilai($item)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        $total = 0;

        $total += $item->ketepatan_waktu;
        $total += $item->sikap_kerja;
        $total += $item->tanggung_jawab;
        $total += $item->kehadiran;
        $total += $item->kemampuan_kerja;
        $total += $item->keterampilan_kerja;
        $total += $item->kualitas_hasil;
        $total += $item->kemampuan_komunikasi;
        $total += $item->kerjasama;
        $total += $item->kerajinan;
        $total += $item->percaya_diri;
        $total += $item->mematuhi_aturan;
        $total += $item->penampilan;

        switch ($item->perilaku) {
            case 'Sangat Baik':
                $total += 100;
                break;
            case 'Baik':
                $total += 75;
                break;
            case 'Cukup Baik':
                $total += 50;
                break;
            case 'Tidak Baik':
                $total += 0;
                break;
        }

        return $total / 14;
    }

    public function cetakDetailRiwayatNilaiBimbingan()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'mentor') {
            return view('no_access');
        }
        helper('date');

        $user_nomor = session()->get('nomor');
        $id_magang = $this->request->getUri()->getSegment(4);

        // Memuat model
        $model = new NilaiModel();
        $data['nilai_akhir'] = $model->getNilaiByMentor($user_nomor);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        // Menggunakan PdfGenerator (periksa pustaka PDF Anda di CI4)
        $pdf = new PdfGenerator();
        $data['title'] = "Detail Rekap Absensi";
        $file_pdf = $data['title'];
        $paper = 'A4';
        $orientation = "landscape";
        $html = view('mentor/cetak_detail_riwayat_nilai_bimbingan', $data);  // Menggunakan view() di CI4
        $pdf->generate($html, $file_pdf, $paper, $orientation);
    }
}
