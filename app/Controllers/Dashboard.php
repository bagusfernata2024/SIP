<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AnakMagangModel;
use App\Models\LaporanModel;
use App\Models\NilaiModel;
use App\Models\PesertaModel;
use CodeIgniter\Controller;
use App\Libraries\PdfGenerator;


class Dashboard extends BaseController
{
    protected $session;
    protected $absensiModel;
    protected $anakMagangModel;
    protected $nilaiModel;
    protected $pesertaModel;
    protected $laporanModel;
    protected $pdfgenerator;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->absensiModel = new AbsensiModel(); // Memuat model dengan CI4 syntax
        $this->anakMagangModel = new AnakMagangModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel();
        $this->laporanModel = new LaporanModel();
        $this->pdfgenerator = new PdfGenerator();

        if (!$this->session->get('peserta_logged_in')) {
            return redirect()->to('login');
        }

        if ($this->session->get('level') !== 'user') {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            $this->session->destroy();
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $user_nomor = $this->session->get('nomor');

        $total_absen_yang_belum_confirm = $this->absensiModel->getAbsenByPesertaCountNotYetConfirm($user_nomor);

        $data['total_absen_yang_belum_confirm'] = $total_absen_yang_belum_confirm;

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/dashboard', $data) .
            view('peserta/footer');
    }

    public function absensi()
    {
        helper('date');
        $user_nomor = $this->session->get('nomor');
        $id_magang = $this->absensiModel->getIdMagang($user_nomor);

        $tgl = date('Y-m-d');

        // Mengambil data absensi hari ini
        $data['absensi_today'] = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

        // Mengambil semua data absensi user, terurut berdasarkan tanggal terbaru
        $data['absensi'] = $this->absensiModel->getAbsensiByUserNomor($user_nomor);

        // ID magang digunakan untuk referensi
        $data['id_magang'] = $id_magang;

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/absensi', $data) .
            view('peserta/footer');
    }

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
        $id_absen = $this->request->getPost('id_absen');
        $deskripsi = $this->request->getPost('deskripsi');

        // Validasi atau proses lainnya sesuai kebutuhan
        $data = [
            'deskripsi' => $deskripsi
        ];

        // Update deskripsi pada absensi dengan id_absen yang diberikan
        $this->absensiModel->update($id_absen, $data);

        // Kembalikan response sukses
        return $this->response->setJSON(['success' => true]);
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
    //         view('peserta/sidebar') .
    //         view('peserta/topbar') .
    //         view('peserta/absensi', $data) .
    //         view('peserta/footer');
    // }

    public function checkIn()
    {
        $user_nomor = $this->session->get('nomor');
        $id_magang = $this->absensiModel->getIdMagang($user_nomor);

        $tgl = date('Y-m-d');
        $jam_masuk = $this->request->getPost('jam_masuk') ?: date('H:i:s');
        $latitude_masuk = $this->request->getPost('latitude');
        $longitude_masuk = $this->request->getPost('longitude');

        if (!$this->absensiModel->hasCheckedInToday($id_magang, $tgl)) { // Periksa jika belum check-in hari ini
            if ($latitude_masuk && $longitude_masuk) {
                $data = [
                    'id_magang' => $id_magang,
                    'tgl' => $tgl,
                    'jam_masuk' => $jam_masuk,
                    'latitude_masuk' => $latitude_masuk,
                    'longitude_masuk' => $longitude_masuk,
                    'approved' => NULL
                ];

                $this->absensiModel->saveCheckIn($data);
                $this->session->setFlashdata('success', 'Check-In berhasil!');
            } else {
                $this->session->setFlashdata('error', 'Lokasi tidak ditemukan, Check-In gagal!');
            }
        } else {
            $this->session->setFlashdata('error', 'Anda sudah Check-In hari ini!');
        }

        return redirect()->to('dashboard/absensi');
    }

    // public function checkOut()
    // {
    //     $user_nomor = $this->session->get('nomor');
    //     $id_magang = $this->absensiModel->getIdMagang($user_nomor);

    //     $tgl = date('Y-m-d');
    //     $jam_keluar = $this->request->getPost('jam_keluar') ?: date('H:i:s'); // Gunakan jam sekarang jika tidak ada input
    //     $latitude_keluar = $this->request->getPost('latitude');
    //     $longitude_keluar = $this->request->getPost('longitude');

    //     // Periksa apakah sudah melakukan check-in hari ini
    //     if ($this->absensiModel->hasCheckedInToday($id_magang, $tgl)) {
    //         // Jika sudah check-in, lakukan check-out
    //         if ($latitude_keluar && $longitude_keluar) {
    //             $data = [
    //                 'id_magang' => $id_magang,
    //                 'tgl' => $tgl,
    //                 'jam_pulang' => $jam_keluar,
    //                 'latitude_keluar' => $latitude_keluar,
    //                 'longitude_keluar' => $longitude_keluar,
    //                 'approved' => NULL // Status approved belum ada
    //             ];

    //             $this->absensiModel->saveCheckOut($data); // Simpan data check-out ke model

    //             // Set flash data untuk sukses
    //             $this->session->setFlashdata('success', 'Check-Out berhasil!');
    //         } else {
    //             $this->session->setFlashdata('error', 'Lokasi tidak ditemukan, Check-Out gagal!');
    //         }
    //     } else {
    //         $this->session->setFlashdata('error', 'Anda harus Check-In terlebih dahulu!');
    //     }

    //     return redirect()->to('dashboard/absensi');
    // }

    public function checkOut()
    {
        $user_nomor = $this->session->get('nomor');
        $id_magang = $this->absensiModel->getIdMagang($user_nomor);
        $tgl = date('Y-m-d');
        $jam_pulang = $this->request->getPost('jam_keluar'); // Ambil waktu checkout dari form
        $latitude_keluar = $this->request->getPost('latitude');
        $longitude_keluar = $this->request->getPost('longitude');
        $deskripsi = $this->request->getPost('deskripsi'); // Ambil deskripsi dari form

        // Pastikan bahwa pengguna belum check-out hari ini
        $existingCheckOut = $this->absensiModel->getTodayAbsence($id_magang, $tgl);

        if ($existingCheckOut && $existingCheckOut['jam_pulang'] == NULL) {
            if ($latitude_keluar != NULL && $longitude_keluar != NULL) {
                $data = [
                    'jam_pulang' => $jam_pulang, // Waktu checkout yang dikirimkan dari browser
                    'latitude_keluar' => $latitude_keluar,
                    'longitude_keluar' => $longitude_keluar,
                    'deskripsi' => $deskripsi, // Simpan deskripsi
                    'statuss' => 'Hadir'
                ];

                $this->absensiModel->updateCheckOut($id_magang, $tgl, $data);

                $this->session->setFlashdata('success', 'Check-Out berhasil!');
                return redirect()->to('dashboard/absensi');
            } else {
                $this->session->setFlashdata('error', 'Check-Out gagal! Lokasi tidak ditemukan.');
                return redirect()->to('dashboard/absensi');
            }
        } else {
            $this->session->setFlashdata('error', 'Check-Out gagal! Anda sudah Check-Out hari ini.');
            return redirect()->to('dashboard/absensi');
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
        $user_nomor = $this->session->get('nomor');

        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);

        if ($id_magang != NULL) {
            $data['laporan'] = $this->anakMagangModel->getLaporanByIdMagang($id_magang);
        } else {
            $data['laporan'] = null;
        }

        $data['id_magang'] = $id_magang; // Tambahkan ini agar tersedia di view
        $data['anakMagangModel'] = $this->anakMagangModel;

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/laporan', $data) . // Pastikan $data dikirim ke view
            view('peserta/footer');
    }


    public function proses_upload_laporan_akhir()
    {
        $session = session();
        $file = $this->request->getFile('file_laporan');
        $id_magang = $this->request->getPost('id');

        // Ambil informasi user dari session
        $nama_user = $session->get('nama');
        $instansi_user = $session->get('instansi');

        // Format nama file
        $nama_user_clean = str_replace(' ', '_', $nama_user);
        $instansi_clean = str_replace(' ', '_', $instansi_user);
        $tanggal = date('Ymd');

        // Konfigurasi upload file
        $config = [
            'uploadPath' => WRITEPATH . 'uploads/laporan/',
            'allowedTypes' => 'pdf|doc|docx',
            'maxSize' => 2048, // Maksimal ukuran file dalam KB
            'fileName' => "laporan_akhir_{$nama_user_clean}_{$instansi_clean}_{$tanggal}",
        ];

        // Proses upload file
        if ($file->isValid() && $file->move($config['uploadPath'])) {
            // Update kolom laporan_akhir di tabel anak_magang
            $data = [
                'laporan_akhir' => $file->getName()
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
        $file_name = rawurldecode($file_name); // Dekode nama file
        $file_path = WRITEPATH . 'uploads/laporan/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null)->setFileName($file_name);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan: ' . $file_path);
        }
    }


    public function nilai()
    {
        $session = session();
        $user_nomor = $session->get('nomor');
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);

        if ($id_magang != NULL) {
            $data['nilai_akhir'] = $this->nilaiModel->getNilaiByPeserta($id_magang);
        }

        $data['id_magang'] = $id_magang;
        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/nilai', $data) .
            view('peserta/footer');
    }

    public function cetak_nilai()
    {
        $session = session();
        $user_nomor = $session->get('nomor');
        $id_magang = $this->nilaiModel->get_id_magang($user_nomor);
        $data['nilai_akhir'] = $this->nilaiModel->get_nilai_by_peserta($id_magang);

        // Menggunakan layanan PDF untuk menghasilkan file
        $this->pdfgenerator->generate(view('peserta/cetak_nilai_akhir', $data), 'Data Random', 'A4', 'landscape');
    }

    public function profile()
    {
        helper('date');

        $session = session();
        $user_nomor = $session->get('nomor');
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/profile', $data) .
            view('peserta/footer');
    }

    public function editProfile()
    {
        $session = session();
        $user_nomor = $session->get('nomor');
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/edit_profile', $data) .
            view('peserta/footer');
    }

    public function updateProfile()
    {
        $session = session();
        $user_nomor = $session->get('nomor');
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'notelp' => $this->request->getPost('notelp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        if ($this->anakMagangModel->updateProfile($id_magang, $data)) {
            $session->setFlashdata('message', 'Profil berhasil diperbarui.');
        } else {
            $session->setFlashdata('message', 'Gagal memperbarui profil.');
        }

        return redirect()->to('dashboard/profile');
    }

    public function editInfoBank()
    {
        $session = session(); // Menginisialisasi session
        $user_nomor = $session->get('nomor'); // Mengambil data sesi
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);
        $data['banks'] = $this->anakMagangModel->getBankEnumValues('anak_magang', 'bank');
        $data['data'] = $this->anakMagangModel->getDataAnakMagang($id_magang);

        return view('peserta/header') .
            view('peserta/sidebar') .
            view('peserta/topbar') .
            view('peserta/edit_info_bank', $data) .
            view('peserta/footer');
    }


    public function downloadBukuRekening($file_name)
    {
        // Path file yang ingin di-download
        $file_path = WRITEPATH . 'uploads/buku_rekening/' . $file_name;

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



    public function updateBankInfo()
    {
        $user_nomor = $this->session->get('nomor'); // Menggunakan session CI4
        $instansi = $this->session->get('instansi'); // Ambil instansi dari session atau form jika diperlukan
        $nama_penerima_bank = $this->request->getPost('nama_penerima_bank'); // Menggunakan request CI4

        // Ambil id_magang berdasarkan nomor yang ada di session
        $id_magang = $this->anakMagangModel->getIdMagang($user_nomor);

        // Ambil data yang dikirim dari form
        $data = [
            'bank' => $this->request->getPost('bank'),
            'nama_penerima_bank' => $this->request->getPost('nama_penerima_bank'),
            'no_rekening' => $this->request->getPost('no_rekening'),
            'buku_rek' => $this->request->getPost('buku_rek') // pastikan nama kolomnya sesuai
        ];

        // Membuat nama file dengan format nama_nomor_instansi_tanggal
        $file_name = strtolower($nama_penerima_bank . '_' . $user_nomor . '_' . $instansi . '_' . date('YmdHis'));
        $file_name = str_replace(' ', '_', $file_name);

        // Cek apakah ada file PDF yang diupload
        if ($this->request->getFile('buku_rek')->isValid()) {
            // Konfigurasi upload file
            $file = $this->request->getFile('buku_rek'); // Ambil file yang di-upload

            // Tentukan direktori dan nama file
            $uploadPath = WRITEPATH . 'uploads/buku_rekening/';
            $file->move($uploadPath, $file_name . '.pdf'); // Pindahkan file ke direktori dengan nama baru

            // Simpan nama file PDF ke dalam data untuk update
            $data['buku_rek'] = $file_name . '.pdf'; // Menyimpan nama file yang diupload
        }

        // Tambahkan id_magang ke dalam data untuk update
        $data['id_magang'] = $id_magang;

        // Perbarui informasi rekening bank di tabel anak_magang
        if ($this->anakMagangModel->updateBankInfo($data)) {
            $this->session->setFlashdata('message', 'Informasi rekening bank berhasil diperbarui.');
        } else {
            $this->session->setFlashdata('message', 'Gagal memperbarui informasi rekening bank.');
        }

        // Redirect kembali ke halaman profil
        return redirect()->to('dashboard/profile');
    }
}
