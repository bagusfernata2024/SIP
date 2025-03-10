<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AnakMagangModel;
use App\Models\LaporanModel;
use App\Models\NilaiModel;
use App\Models\PesertaModel;
use App\Models\RegistrasiModel;
use App\Models\DetailRegisModel;

use CodeIgniter\Controller;
use App\Libraries\PdfGenerator;
use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;
use PhpOffice\PhpWord\TemplateProcessor;


class Dashboard extends BaseController
{
    protected $session;
    protected $absensiModel;
    protected $anakMagangModel;
    protected $nilaiModel;
    protected $pesertaModel;
    protected $laporanModel;
    protected $pdfgenerator;
    protected $registrasiModel;
    protected $detailRegisModel;



    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->absensiModel = new AbsensiModel(); // Memuat model dengan CI4 syntax
        $this->anakMagangModel = new AnakMagangModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel();
        $this->laporanModel = new LaporanModel();
        $this->pdfgenerator = new PdfGenerator();
        $this->registrasiModel = new RegistrasiModel();
        $this->detailRegisModel = new DetailRegisModel();


        // if (!$this->session->get('peserta_logged_in')) {
        //     return redirect()->to('login');
        // }

        // if ($this->session->get('level') !== 'user') {
        //     $this->session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
        //     $this->session->destroy();
        //     return redirect()->to('login');
        // }
    }

    public function index()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }

        $total_absen_yang_belum_confirm = $this->absensiModel->getAbsenByPesertaCountNotYetConfirm($id_register);

        $data['total_absen_yang_belum_confirm'] = $total_absen_yang_belum_confirm;
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;

        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/dashboard', $data) .
            view('peserta/footer');
    }

    public function uploadSurat()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $nama = $this->session->get('nama');
        $tipe = $this->session->get('tipe');
        $nomor = $this->session->get('nomor');
        $instansi = $this->session->get('instansi');
        $tanggal = date('Ymd');
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;


        if ($file = $this->request->getFile('surat_perjanjian')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $this->createRenameFile('surat_perjanjian', $nama, $tipe, $nomor, $instansi, $tanggal);
                $file->move(FCPATH . 'uploads/surat_perjanjian_ttd/', $newName);

                // Simpan ke database
                $this->registrasiModel->update($id_register, ['surat_perjanjian_ttd' => $newName]);

                return redirect()->to(base_url('dashboard'))->with('success', 'Surat perjanjian berhasil diunggah.');
            }
        }
        return redirect()->to(base_url('dashboard'))->with('error', 'Gagal mengunggah file.');
    }

    private function createRenameFile3($type, $name, $tipe)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        return strtolower($type . '_' . str_replace(' ', '_', $name) . '_' . $tipe . '.pdf');
    }

    private function createRenameFile($type, $name, $tipe, $nim, $instansi, $date)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        return strtolower($type . '_' . str_replace(' ', '_', $name) . '_' . $tipe . '_' . $nim . '_' . $instansi . '_' . $date . '.' . pathinfo($_FILES[$type]['name'], PATHINFO_EXTENSION));
    }

    public function absensi()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }

        // Ambil id_register dari session
        $id_register = $this->session->get('id_register');

        // Inisialisasi variabel absensi dan id_magang
        $id_magang = null;
        $absensi = [];
        $absensi_today = null;
        $isTodayAbsent = false; // Variabel untuk cek absensi hari ini
        $tgl_mulai = $tgl_selesai = null; // Variabel untuk tanggal mulai dan selesai absensi

        // Periksa apakah id_register tersedia di session
        if ($id_register) {
            // Ambil ID magang berdasarkan id_register
            $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);

            // Debugging
            log_message('debug', 'ID Register dari session: ' . $id_register);
            log_message('debug', 'ID Magang ditemukan: ' . $id_magang);

            // Periksa apakah ID magang valid
            if ($id_magang) {
                // Ambil data tanggal mulai dan selesai absensi dari database (misalnya dari tabel magang atau absensi)
                $magang = $this->anakMagangModel->find($id_magang); // Asumsi ada model magangModel yang menangani data magang

                // Ambil tanggal mulai dan tanggal selesai
                if ($magang) {
                    $tgl_mulai = $magang['tgl_mulai'];
                    $tgl_selesai = $magang['tgl_selesai'];
                }

                // Ambil semua data absensi berdasarkan ID magang
                $absensi = $this->absensiModel->where('id_magang', $id_magang)
                    ->orderBy('tgl', 'desc')
                    ->findAll();

                // Ambil data absensi hari ini
                $absensi_today = $this->absensiModel->getTodayAbsence($id_magang, date('Y-m-d'));

                // Jika tidak ada absensi hari ini, set flag isTodayAbsent
                if (empty($absensi_today)) {
                    $isTodayAbsent = true;
                }

                // Debugging
                log_message('debug', 'Data Absensi Hari Ini: ' . json_encode($absensi_today));
            }
        }

        // Data yang akan dikirimkan ke view
        $data = [
            'absensi' => $absensi,
            'id_magang' => $id_magang,
            'absensi_today' => $absensi_today,
            'isTodayAbsent' => $isTodayAbsent,
            'tgl_mulai' => $tgl_mulai, // Kirim tanggal mulai
            'tgl_selesai' => $tgl_selesai, // Kirim tanggal selesai
        ];
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Tampilkan view dengan data
        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/absensi', $data) .
            view('peserta/footer');
    }

    // public function absensi()
    // {
    //     helper('date');
    //     $user_id_register = $this->session->get('id_register'); // Ambil id_register dari session
    //     $anakMagangModel = new \App\Models\AnakMagangModel();
    //     // Ambil data anak magang berdasarkan id_register
    //     $anakMagang = $anakMagangModel->where('id_register', $user_id_register)->first();

    //     // Debug untuk memastikan data anak magang ditemukan
    //     if (!$anakMagang) {
    //         return view('peserta/header') .
    //             view('peserta/sidebar', $data) .
    //             view('peserta/topbar') .
    //             view('peserta/absensi', [
    //                 'absensi_today' => null,
    //                 'absensi' => [],
    //                 'id_magang' => null,
    //                 'error_message' => 'Anda belum terdaftar pada program magang.'
    //             ]) .
    //             view('peserta/footer');
    //     }

    //     // Ambil tanggal awal dan akhir magang
    //     $tanggalAwal = $anakMagang['tgl_mulai'];
    //     $tanggalAkhir = $anakMagang['tgl_selesai'];
    //     $tanggalRange = $this->generateTanggalRange($tanggalAwal, $tanggalAkhir);

    //     // Ambil data absensi yang sudah ada berdasarkan id_magang
    //     $absensiExist = $this->absensiModel->where('id_magang', $anakMagang['id_magang'])->findAll();

    //     // Debug untuk memastikan data absensi ditemukan
    //     if (empty($absensiExist)) {
    //         echo "Data absensi tidak ditemukan untuk id_magang: " . $anakMagang['id_magang'];
    //         die();
    //     }

    //     // Map absensi yang sudah ada berdasarkan tanggal
    //     $absensiMapped = [];
    //     foreach ($absensiExist as $absen) {
    //         $absensiMapped[$absen['tgl']] = $absen;
    //     }

    //     // Buat data absensi lengkap dengan entri default jika belum ada
    //     $absensiFinal = [];
    //     foreach ($tanggalRange as $tanggal) {
    //         if (isset($absensiMapped[$tanggal])) {
    //             $absensiFinal[] = $absensiMapped[$tanggal];
    //         } else {
    //             $absensiFinal[] = [
    //                 'tgl' => $tanggal,
    //                 'jam_masuk' => null,
    //                 'jam_pulang' => null,
    //                 'deskripsi' => null,
    //                 'statuss' => 'Belum Absen',
    //                 'approved' => null,
    //             ];
    //         }
    //     }

    //     // Data absensi hari ini
    //     $tgl = date('Y-m-d');
    //     $data['absensi_today'] = $absensiMapped[$tgl] ?? null;

    //     // Data untuk view
    //     $data['absensi'] = $absensiFinal;
    //     $data['id_magang'] = 2035;


    //     // Debug data akhir untuk memastikan data diteruskan ke view
    //     // echo "<pre>";
    //     // print_r($data);
    //     // echo "</pre>";
    //     // die();

    //     return view('peserta/header') .
    //         view('peserta/sidebar', $data) .
    //         view('peserta/topbar') .
    //         view('peserta/absensi', $data) .
    //         view('peserta/footer');
    // }

    // Helper untuk generate range tanggal
    private function generateTanggalRange($startDate, $endDate)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); // Tambahkan 1 hari untuk menyertakan tanggal akhir
        $interval = new \DateInterval('P1D'); // Interval 1 hari
        $dateRange = new \DatePeriod($start, $interval, $end);

        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;

        return $dates;
    }




    // public function absensi()
    // {
    //     helper('date');
    //     $user_nomor = $this->session->get('nomor');
    //     $id_magang = $this->absensiModel->getIdMagang($user_nomor);

    //     $tgl = date('Y-m-d');

    //     // Mengambil data absensi hari ini
    //     $data['absensi_today'] = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

    //     // Mengambil semua data absensi user, terurut berdasarkan tanggal terbaru
    //     $data['absensi'] = $this->absensiModel->getAbsensiByUserNomor($user_nomor);

    //     // ID magang digunakan untuk referensi
    //     $data['id_magang'] = $id_magang;

    //     return view('peserta/header') .
    //         view('peserta/sidebar', $data) .
    //         view('peserta/topbar') .
    //         view('peserta/absensi', $data) .
    //         view('peserta/footer');
    // }

    // public function updateDeskripsi()
    // {
    //     // Mendapatkan data dari request POST
    //     $id_absen = $this->request->getPost('id_absen');
    //     $deskripsi = $this->request->getPost('deskripsi');

    //     // Validasi data
    //     if (!$id_absen || !$deskripsi) {
    //         return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    //     }

    //     // Update deskripsi pada absensi dengan id_absen yang diberikan
    //     try {
    //         $data = [
    //             'deskripsi' => $deskripsi
    //         ];

    //         // Memastikan bahwa id_absen ada dan data berhasil diupdate
    //         $updateResult = $this->absensiModel->update($id_absen, $data);

    //         if ($updateResult) {
    //             return $this->response->setJSON(['success' => true]);
    //         } else {
    //             return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data']);
    //         }
    //     } catch (\Exception $e) {
    //         // Tangani error jika ada
    //         return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }

    public function updateDeskripsi()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $id_absen = $this->request->getPost('id_absen');
        $deskripsi = $this->request->getPost('deskripsi');
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Validasi data
        if (empty($id_absen) || empty($deskripsi)) {
            return $this->response->setJSON(['error' => 'ID Absen atau deskripsi tidak boleh kosong.']);
        }

        // Update deskripsi pada database
        $updateData = ['deskripsi' => $deskripsi];
        $result = $this->absensiModel->update($id_absen, $updateData);

        if ($result) {
            return $this->response->setJSON(['success' => 'Berhasil memperbarui deskripsi.']);
        } else {
            return $this->response->setJSON(['error' => 'Gagal memperbarui deskripsi.']);
        }
    }



    // public function absensi()
    // {
    //     helper('date');
    //     $user_nomor = $this->session->get('nomor');
    //     $id_magang = $this->absensiModel->getIdMagang($user_nomor);

    //     $tgl = date('Y-m-d');

    //     $data['absensi_today'] = $this->absensiModel->getTodayAbsence($id_magang, $tgl);
    //     $data['absensi'] = $this->absensiModel->getAbsensiByUserNomor($user_nomor);
    //     $data['id_magang'] = $id_magang;

    //     return view('peserta/header') .
    //         view('peserta/sidebar', $data) .
    //         view('peserta/topbar') .
    //         view('peserta/absensi', $data) .
    //         view('peserta/footer');
    // }

    public function checkIn()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $id_register = $this->session->get('id_register'); // Ambil id_register dari sesi
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register); // Cari id_magang berdasarkan id_register
        $tgl = date('Y-m-d'); // Tanggal hari ini
        $jam_masuk = $this->request->getPost('jam_masuk') ?: date('H:i:s'); // Jam masuk (current time jika tidak di-post)
        $latitude_masuk = $this->request->getPost('latitude'); // Lokasi latitude
        $longitude_masuk = $this->request->getPost('longitude'); // Lokasi longitude

        // Validasi lokasi
        if (!$latitude_masuk || !$longitude_masuk) {
            return $this->response->setJSON(['error' => 'Lokasi tidak ditemukan. Pastikan GPS aktif.']);
        }

        // Cari record absensi pada tanggal hari ini
        $absen = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

        if ($absen) {
            // Jika record ditemukan, update data jam_masuk
            if (empty($absen['jam_masuk'])) {
                $data = [
                    'jam_masuk' => $jam_masuk,
                    'latitude_masuk' => $latitude_masuk,
                    'longitude_masuk' => $longitude_masuk,
                ];
                $this->absensiModel->updateAbsensi($absen['id_absen'], $data);
                return $this->response->setJSON(['success' => 'Check-In berhasil!']);
            } else {
                return $this->response->setJSON(['error' => 'Anda sudah Check-In hari ini!']);
            }
        } else {
            // Buat record baru untuk absensi jika tidak ditemukan
            $data = [
                'id_magang' => $id_magang,
                'tgl' => $tgl,
                'jam_masuk' => $jam_masuk,
                'latitude_masuk' => $latitude_masuk,
                'longitude_masuk' => $longitude_masuk,
            ];

            $this->absensiModel->insert($data);

            log_message('debug', 'Data baru absensi: ' . json_encode($data));
            return $this->response->setJSON(['success' => 'Check-In berhasil!']);
        }
    }

    public function checkOut()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $id_register = $this->session->get('id_register'); // Ambil id_register dari session
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register); // Cari id_magang berdasarkan id_register
        $tgl = date('Y-m-d'); // Tanggal hari ini
        $jam_pulang = $this->request->getPost('jam_keluar') ?: date('H:i:s'); // Jam keluar
        $latitude_keluar = $this->request->getPost('latitude'); // Lokasi latitude
        $longitude_keluar = $this->request->getPost('longitude'); // Lokasi longitude
        $deskripsi = $this->request->getPost('deskripsi'); // Deskripsi

        // Validasi lokasi
        if (!$latitude_keluar || !$longitude_keluar) {
            return $this->response->setJSON(['error' => 'Lokasi tidak ditemukan. Pastikan GPS aktif.']);
        }

        // Cari record absensi hari ini
        $absen = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

        if ($absen) {
            // Pastikan belum Check-Out
            if (empty($absen['jam_pulang'])) {
                $data = [
                    'jam_pulang' => $jam_pulang,
                    'latitude_keluar' => $latitude_keluar,
                    'longitude_keluar' => $longitude_keluar,
                    'deskripsi' => $deskripsi,
                ];
                $this->absensiModel->updateAbsensi($absen['id_absen'], $data);

                log_message('debug', 'Data Check-Out diperbarui: ' . json_encode($data));
                return $this->response->setJSON(['success' => 'Check-Out berhasil!']);
            } else {
                return $this->response->setJSON(['error' => 'Anda sudah Check-Out hari ini!']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Anda belum Check-In hari ini!']);
        }
    }

    // public function checkOut()
    // {
    //     $user_nomor = $this->session->get('nomor');
    //     $id_magang = $this->absensiModel->getIdMagang($user_nomor);
    //     $tgl = date('Y-m-d');
    //     $jam_pulang = date('H:i:s'); // Pastikan ini waktu server
    //     $latitude_keluar = $this->request->getPost('latitude');
    //     $longitude_keluar = $this->request->getPost('longitude');
    //     $deskripsi = $this->request->getPost('deskripsi');

    //     // Pastikan bahwa pengguna belum check-out hari ini
    //     $existingCheckOut = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

    //     if ($existingCheckOut && $existingCheckOut['jam_pulang'] == NULL) {
    //         if ($latitude_keluar != NULL && $longitude_keluar != NULL) {
    //             $data = [
    //                 'jam_pulang' => $jam_pulang, // Waktu server
    //                 'latitude_keluar' => $latitude_keluar,
    //                 'longitude_keluar' => $longitude_keluar,
    //                 'deskripsi' => $deskripsi,
    //                 'statuss' => 'Hadir'
    //             ];

    //             $this->absensiModel->updateCheckOut($id_magang, $tgl, $data);

    //             $this->session->setFlashdata('success', 'Check-Out berhasil!');
    //             return redirect()->to('dashboard/absensi');
    //         } else {
    //             $this->session->setFlashdata('error', 'Check-Out gagal! Lokasi tidak ditemukan.');
    //             return redirect()->to('dashboard/absensi');
    //         }
    //     } else {
    //         $this->session->setFlashdata('error', 'Check-Out gagal! Anda sudah Check-Out hari ini.');
    //         return redirect()->to('dashboard/absensi');
    //     }
    // }


    public function laporan()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $id_register = $this->session->get('id_register');

        $id_magang = $this->anakMagangModel->getIdMagang($id_register);

        if ($id_magang != NULL) {
            $data['laporan'] = $this->anakMagangModel->getLaporanByIdMagang($id_magang);
        } else {
            $data['laporan'] = null;
        }

        $data['id_magang'] = $id_magang; // Tambahkan ini agar tersedia di view
        $data['anakMagangModel'] = $this->anakMagangModel;

        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/laporan', $data) . // Pastikan $data dikirim ke view
            view('peserta/footer');
    }


    public function proses_upload_laporan_akhir()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $file = $this->request->getFile('file_laporan');
        $id_magang = $this->request->getPost('id');

        // Ambil informasi user dari session
        $nama_user = $session->get('nama');
        $tipe = $session->get('tipe');
        $instansi_user = $session->get('instansi');

        // Format nama file
        $nama_user_clean = str_replace(' ', '_', $nama_user);
        $instansi_clean = str_replace(' ', '_', $instansi_user);
        $tanggal = date('Ymd');

        // Konfigurasi upload file
        $config = [
            'uploadPath' => FCPATH . 'uploads/laporan/',
            'allowedTypes' => 'pdf|doc|docx',
            'maxSize' => 2048, // Maksimal ukuran file dalam KB
            'fileName' => "laporan_akhir_{$nama_user_clean}_{$instansi_clean}_{$tanggal}",
        ];

        // Proses upload file
        if ($file->isValid()) {
            $nama_file = 'laporan';
            $newName = $this->createRenameFile3($nama_file, $nama_user, $tipe);
            $file->move(FCPATH . 'uploads/laporan', $newName);

            // Update kolom laporan_akhir di tabel anak_magang
            $data = [
                'laporan_akhir' => $newName
            ];

            if ($this->anakMagangModel->updateLaporanAkhir($id_magang, $data)) {
                $session->setFlashdata('success', 'Laporan akhir berhasil diunggah.');
            } else {
                $session->setFlashdata('error', 'Gagal memperbarui data laporan di database.');
            }
        } else {
            // Jika proses upload gagal
            $session->setFlashdata('error', $file->getErrorString());
        }

        // Redirect kembali ke halaman laporan
        return redirect()->to('peserta/laporan');
    }

    public function file($file_name)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $file_name = rawurldecode($file_name); // Dekode nama file
        $file_path = FCPATH . 'uploads/laporan/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null)->setFileName($file_name);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan: ' . $file_path);
        }
    }


    public function nilai()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $id_register = $session->get('id_register');
        $id_magang = $this->anakMagangModel->getIdMagang($id_register); // Ambil id_magang

        if ($id_magang != NULL) {
            // Pastikan Anda mengambil data nilai berdasarkan id_magang
            $data['nilai_akhir'] = $this->nilaiModel->getNilaiByPeserta($id_magang);
        } else {
            $data['nilai_akhir'] = null; // Jika id_magang tidak ditemukan, set nilai_akhir menjadi null
        }

        $data['id_magang'] = $id_magang;
        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/nilai', $data) .
            view('peserta/footer');
    }

    public function sertifikat()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $id_register = $this->session->get('id_register');
        $id_magang = $this->anakMagangModel->getIdMagang($id_register);
        $anakMagang = $this->anakMagangModel->find($id_magang);
        $user_register = $this->registrasiModel->getRegistrasiById($this->session->get('id_register'));
        if ($id_magang != NULL) {
            // Periksa apakah tgl_selesai sudah terlewati
            if (empty($anakMagang['tgl_selesai']) || strtotime($anakMagang['tgl_selesai']) > time() || $user_register['no_sertif'] == null) {
                // Jika program belum selesai, tampilkan pesan
                $data['is_completed'] = false;
                $data['message'] = 'Program belum selesai';
            } else {
                // Jika sudah selesai, tampilkan tombol cetak sertifikat
                $data['is_completed'] = true;
                $data['message'] = 'Program anda telah selesai';
            }
        } else {
            $data['laporan'] = null;
        }

        $data['id_magang'] = $id_magang; // Tambahkan ini agar tersedia di view
        $data['anakMagangModel'] = $this->anakMagangModel;
        $data['anakMagang'] = $anakMagang;
        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/sertifikat', $data) . // Pastikan $data dikirim ke view
            view('peserta/footer');
    }

    public function cetak_nilai()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }

        helper('date');


        $session = session();
        $id_register = $session->get('id_register');
        $id_magang = $this->nilaiModel->getIdMagang($id_register);
        // Ambil data nilai dan data registrasi
        $nilai_akhir = $this->nilaiModel->getNilaiByPeserta($id_magang);
        $registrasi_data = $this->registrasiModel->getRegistrasiById($id_register); // Ambil data dari tabel registrasi berdasarkan nomo
        // Gabungkan data registrasi dan nilai_akhir dalam array
        $nilai_akhir['nama'] = $registrasi_data['nama'];
        $nilai_akhir['nomor'] = $registrasi_data['nomor'];
        $nilai_akhir['instansi'] = $registrasi_data['instansi'];
        $nilai_akhir['tanggal1'] = $registrasi_data['tanggal1'];
        $nilai_akhir['tanggal2'] = $registrasi_data['tanggal2'];

        // Kirim data ke view
        $data['nilai_akhir'] = $nilai_akhir;
        // Menggunakan layanan PDF untuk menghasilkan file
        $this->pdfgenerator->generate(view('peserta/cetak_nilai_akhir', $data), 'Data Nilai', 'A4', 'landscape');
    }


    public function profile()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        helper('date');

        $session = session();
        $id_register = $session->get('id_register');
        $id_magang = $this->anakMagangModel->getIdMagang($id_register);
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/profile', $data) .
            view('peserta/footer');
    }

    public function editProfile()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $id_register = $session->get('id_register');
        $id_magang = $this->anakMagangModel->getIdMagang($id_register);
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/edit_profile', $data) .
            view('peserta/footer');
    }

    public function updateProfile()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $id_register = $session->get('id_register');

        if (!$id_register) {
            $session->setFlashdata('message', 'Data tidak ditemukan.');
            return redirect()->to('dashboard/profile');
        }

        $fileFoto = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('foto_lama'); // Ambil nama foto lama dari input hidden

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Jika ada file baru yang diunggah
            $newName = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads', $newName); // Simpan di folder uploads
        } else {
            // Gunakan foto lama jika tidak ada file baru
            $newName = $fotoLama;
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'notelp' => $this->request->getPost('notelp'),
            'alamat' => $this->request->getPost('alamat'),
            'foto' => $newName
        ];

        // Update data di tabel registrasi
        if ($this->anakMagangModel->updateRegistrasi($id_register, $data)) {
            // Set ulang session key 'foto' dengan foto terbaru
            $session->set('foto', $newName);

            $session->setFlashdata('message', 'Profil berhasil diperbarui.');
        } else {
            $session->setFlashdata('message', 'Gagal memperbarui profil.');
        }

        return redirect()->to('dashboard/profile');
    }

    public function editInfoBank()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session(); // Menginisialisasi session
        $id_register = $session->get('id_register'); // Mengambil data sesi
        $id_magang = $this->anakMagangModel->getIdMagang($id_register);
        $data['banks'] = $this->anakMagangModel->getBankEnumValues('anak_magang', 'bank');
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar', $data) .
            view('peserta/topbar') .
            view('peserta/edit_info_bank', $data) .
            view('peserta/footer');
    }

    public function updateBankInfo()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $id_register = $session->get('id_register'); // Mengambil data id_register peserta dari session
        $id_magang = $this->anakMagangModel->getIdMagang($id_register); // Mengambil id_magang dari model berdasarkan id_register

        if (!$id_magang) {
            $session->setFlashdata('message', 'Data peserta tidak ditemukan.');
            return redirect()->to('dashboard/profile');
        }

        // Ambil data input dari form dan gunakan htmlspecialchars untuk sanitasi
        $bank = htmlspecialchars($this->request->getPost('bank'), ENT_QUOTES, 'UTF-8');
        $no_rekening = htmlspecialchars($this->request->getPost('no_rekening'), ENT_QUOTES, 'UTF-8');
        $nama_penerima_bank = htmlspecialchars($this->request->getPost('nama_penerima_bank'), ENT_QUOTES, 'UTF-8');

        // Cek apakah file baru diunggah
        $file = $this->request->getFile('buku_rek');
        $file_name = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Tentukan nama file baru
            $file_name = strtolower($nama_penerima_bank . '_' . $id_register . '_' . date('YmdHis'));
            $file_name = str_replace(' ', '_', $file_name) . '.pdf';

            // Tentukan lokasi penyimpanan file
            $uploadPath = FCPATH . 'uploads/buku_rekening/';
            $file->move($uploadPath, $file_name); // Pindahkan file
        } else {
            // Jika tidak ada file baru, gunakan nama file lama dari database
            $file_name = $this->anakMagangModel->getBankInfo($id_magang)['buku_rek'] ?? null;
        }

        // Siapkan data untuk diperbarui
        $data = [
            'bank' => $bank,
            'no_rekening' => $no_rekening,
            'nama_penerima_bank' => $nama_penerima_bank,
            'buku_rek' => $file_name
        ];

        // Update data di tabel anak_magang
        if ($this->anakMagangModel->update($id_magang, $data)) {
            $session->setFlashdata('message', 'Informasi rekening bank berhasil diperbarui.');
        } else {
            $session->setFlashdata('message', 'Gagal memperbarui informasi rekening bank.');
        }

        // Redirect kembali ke halaman profil
        return redirect()->to('dashboard/profile');
    }

    public function downloadBukuRekening($file_name)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        // Path file yang ingin di-download
        $file_path = FCPATH . 'uploads/buku_rekening/' . $file_name;

        // Cek apakah file ada
        if (file_exists($file_path)) {
            // Set header untuk memberitahu browser bahwa ini adalah file yang akan di-download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            header('Pragma: no-cache');
            header('Expires: 0');

            // Membaca file dan mengirimkan kontennya ke browser
            readfile($file_path);

            // Menghentikan eksekusi lebih lanjut setelah file dikirim
            exit;
        } else {
            // Jika file tidak ditemukan, tampilkan pesan error
            $session = session();
            $session->setFlashdata('message', 'File tidak ditemukan.');
            return redirect()->to(base_url('dashboard/profile'));
        }
    }

    public function cetak_absensi()
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $session = session();
        $id_register = $session->get('id_register');
        $id_magang = $this->anakMagangModel->getIdMagang($id_register); // Ambil id_magang
        if (!$id_magang) {
            return redirect()->to('dashboard')->with('error', 'ID magang tidak ditemukan.');
        }

        // Ambil data absensi berdasarkan ID magang
        $absensi = $this->absensiModel->getAbsensiByMagang($id_magang);
        // Buat template HTML untuk PDF
        $html = view('peserta/absensi_pdf', ['absensi' => $absensi]);

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Load HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');

        // Render HTML menjadi PDF
        $dompdf->render();

        // Output file PDF ke browser
        $dompdf->stream("absensi_" . $id_register . ".pdf", ["Attachment" => false]);
    }

    public function cetak($id)
    {
        $id_register = $this->session->get('id_register');
        $register = $this->registrasiModel->getRegistrasiById($id_register);
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        $data['anak_magang'] = $anak_magang;
        $data['registrasi'] = $register;
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'user') {
            return view('no_access');
        }
        $registrasiModel = new RegistrasiModel();
        $id_register = $this->session->get('id_register');
        // Ambil data nilai dan data registrasi
        $registrasi_data = $this->registrasiModel->getRegistrasiById($id); // Ambil data dari tabel registrasi berdasarkan nomo

        $data['registrasi'] = $registrasi_data;

        if (!$data['registrasi']) {
            return redirect()->to('/')->with('error', 'Data tidak ditemukan.');
        }

        return view('peserta/cetak_sertifikat', $data);
    }
}
