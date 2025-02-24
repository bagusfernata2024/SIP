<?php

namespace App\Controllers\Admin;

use App\Models\PesertaModel;
use App\Models\RegistrasiModel;
use App\Models\DetailRegisModel;
use App\Models\MentorModel;
use App\Models\AnakMagangModel;
use App\Models\NilaiModel;
use App\Models\UserModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DateTime;
use DateInterval;
use DatePeriod;

use ZipArchive;

use App\Controllers\BaseController;


class Dashboard extends BaseController
{
    protected $session;
    protected $registrasiModel;
    protected $mentorModel;
    protected $nilaiModel;
    protected $pesertaModel;
    protected $anakMagangModel;
    protected $userModel;
    protected $encryption;
    private $encryption_key = 'ThisIsASecretKeyForEncryption';
    public function __construct()
    {
        $this->session = session();
        $this->registrasiModel = new RegistrasiModel();
        $this->mentorModel = new MentorModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel;
        $this->anakMagangModel = new AnakMagangModel;
        $this->userModel = new UserModel;
        $this->encryption = \Config\Services::encryption(); // Memanggil pustaka enkripsi

    }

    public function index()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');  // Load helper 'date'
        $registrasiModel = new RegistrasiModel();
        $anakMagangModel = new AnakMagangModel();  // Model untuk tabel anak_magang

        $statusFilter = $this->request->getGet('status');

        switch ($statusFilter) {
            case 'Accept':
                $data['registrasi'] = $registrasiModel->getByStatus('Accept');
                break;
            case 'reject':
                $data['registrasi'] = $registrasiModel->getByStatus('reject');
                break;
            case 'null':
                $data['registrasi'] = $registrasiModel->getByStatus('Waiting');
                break;
            default:
                $data['registrasi'] = $registrasiModel->getData();
        }
        // dd($this->encryption = \Config\Services::encryption());
        // $data['registrasi'] = $this->registrasiModel->findAll(); // Misalnya ambil semua peserta

        // foreach ($data['registrasi'] as $register) {
        //     $register['encrypted_id'] = urlencode(base64_encode($this->encryption->encrypt($register['id_register'])));
        // }

        // Menghitung total pendaftar diterima (berdasarkan tabel anak_magang, status diterima dianggap sebagai sudah ada data di tabel anak_magang)
        $data['total_accept'] = $anakMagangModel->countAll();  // Menghitung peserta aktif dari anak_magang dengan status 'Aktif'

        // Menghitung total peserta aktif (status 'Aktif' di tabel anak_magang)
        $data['total_active'] = $anakMagangModel->countByStatus('Aktif'); // Peserta dengan status aktif

        // Menghitung total peserta menunggu (status 'null' di tabel anak_magang)
        $data['total_waiting'] = $registrasiModel->countPesertaByStatus('Waiting');  // Peserta dengan status 'null' di tabel anak_magang

        // Menghitung total peserta ditolak (status 'reject' di tabel registrasi)
        $data['total_reject'] = $registrasiModel->countPesertaByStatus('reject');  // Peserta ditolak di registrasi

        // Menghitung total peserta selesai kegiatan (status 'Selesai Magang' di tabel anak_magang)
        $data['total_done'] = $anakMagangModel->countByStatus('Selesai Magang'); // Peserta dengan status 'Selesai Magang'

        // Menghitung total pendaftar berdasarkan semua data registrasi
        $data['total_pendaftar'] = $data['total_accept'] + $data['total_reject']; // Mengambil total pendaftar dari tabel registrasi

        // Total keseluruhan peserta (hanya untuk keperluan informasi)
        $data['total'] = $data['total_accept'] + $data['total_reject'] + $data['total_waiting'] + $data['total_done'];

        return view('templates/header')
            . view('templates/sidebar')
            . view('templates/topbar')
            . view('templates/dashboard', $data)
            . view('templates/footer');
    }

    public function generateSuratMagang()
    {
        // Membuat objek PhpWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection([
            'pageSizeW' => 11906, // Lebar halaman A4
            'pageSizeH' => 16838, // Tinggi halaman A4
            'marginTop' => 720,   // Margin atas
            'marginLeft' => 1440, // Margin kiri
            'marginRight' => 1440, // Margin kanan
            'marginBottom' => 1440, // Margin bawah
        ]);

        // Format umum teks
        $boldStyle = ['bold' => true];
        $normalStyle = ['size' => 11];
        $spasi1Style = ['spaceAfter' => 0, 'spaceBefore' => 0, 'size' => 11]; // Spasi 1
        $justifyStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]; // Justify alignment

        // Menambahkan Logo di Kanan Atas dengan posisi di belakang teks
        $section->addImage('C:\laragon\www\sip4\public\templates\logo.png', [
            'width' => 100,    // Ukuran logo disesuaikan
            'height' => 100,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
            'behindText' => true, // Logo berada di belakang teks
        ]);

        // Nomor, Sifat, Lampiran, Perihal dengan spasi 1
        $section->addText("Nomor : 009400.S/DL.03/HCBPS/2025", $normalStyle);
        $section->addText("Sifat : Segera", $normalStyle);
        $section->addText("Lampiran : 2 Berkas", $normalStyle);
        $section->addText("Perihal : Surat Persetujuan Magang a.n. Prity Nur Aisah", $normalStyle);
        $section->addTextBreak(1);

        // Tempat dan Tanggal Surat, ditulis di kiri
        $section->addText("Jakarta, 13 Februari 2025", $normalStyle);
        $section->addTextBreak(1);

        // "Yang Terhormat" hingga alamat kampus dengan spasi 1
        $section->addText("Yang Terhormat", $boldStyle);
        $section->addText("Bidang Akademik, Kemahasiswaan dan Alumni", $boldStyle);
        $section->addText("Fakultas Ekonomi dan Manajemen Institut Pertanian Bogor", $boldStyle);
        $section->addText("Jalan Agatis, Kampus IPB Dramaga, Bogor 16680", $normalStyle);
        $section->addTextBreak(1);

        // Isi Surat dengan justify alignment
        $section->addText("Sehubungan dengan Surat Bidang Akademik, Kemahasiswaan dan Alumni Fakultas Ekonomi dan Manajemen Institut Pertanian Bogor Nomor : 1118/IT3.F7/PK.01.06/M/B/2025 tanggal 01 Februari 2025 tentang Izin Magang, dengan ini disampaikan hal-hal sebagai berikut:", $normalStyle);
        $section->addTextBreak(1);

        // Poin 1
        $section->addText("1. Memberikan persetujuan untuk melakukan Praktik Kerja Lapangan (PKL) kepada mahasiswa sebagai berikut:", $normalStyle);

        // Tabel Data Mahasiswa dengan teks rata tengah
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
        $table->addRow();
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Nama', $boldStyle);
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Jurusan', $boldStyle);
        $table->addCell(4000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Penempatan', $boldStyle);
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Mentor', $boldStyle);

        // Isi Tabel
        $table->addRow();
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Prity Nur Aisah');
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Manajemen');
        $table->addCell(4000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Risk Strategy and Integrated Governance');
        $table->addCell(3000, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER])->addText('Markus Aditya');

        // Poin 2
        $section->addTextBreak(1);
        $section->addText("2. Periode PKL pada tanggal 17 Februari 2025 - 16 Mei 2025, yang dilakukan secara fisik di PT Perusahaan Gas Negara Tbk sesuai dengan lokasi penempatan pada point 1 (Satu).", $normalStyle);

        // Poin 3
        $section->addText("3. Selama melaksanakan PKL, kepada mahasiswa termasuk hanya diberikan uang saku (sesuai ketentuan yang berlaku di PT Perusahaan Gas Negara Tbk), dan tidak diberikan fasilitas apapun termasuk akses informasi yang menyangkut rahasia perusahaan.", $normalStyle);

        // Poin 4
        $section->addText("4. Untuk pelaksanaannya, mahasiswa yang bersangkutan dapat menghubungi Sdri. Firda Prihatin (email: PGN.HCM.Support@pertamina.com; atau HP.: +6289622484436).", $normalStyle);
        $section->addTextBreak(1);

        // Penutup
        $section->addText("Atas perhatianya, kami ucapkan terima kasih.", $normalStyle);
        $section->addTextBreak(2);

        // Tanda Tangan
        $section->addText("Division Head, Human Capital", $normalStyle);
        $section->addText("Business Partner and Services", $normalStyle);
        $section->addTextBreak(3);

        // Nama Pejabat
        $section->addText("Anisyah Roestantien", $boldStyle);
        $section->addText("Pertamina Gas Negara", $normalStyle);

        // Menyimpan dokumen ke file
        $fileName = 'Surat_Persetujuan_Magang_Prity_Nur_Aisah.docx';
        $filePath = FCPATH . $fileName;
        $phpWord->save($filePath, 'Word2007');

        // Mengunduh file
        return $this->response->download($filePath, null)->setFileName($fileName);
    }



    public function detail($id)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');  // Load helper 'date'
        $registrasiModel = new RegistrasiModel();
        $detailRegisModel = new DetailRegisModel();
        $mentorModel = new MentorModel();

        // $instansi = $this->request->getPost('instansi');

        // dd($this->request->getPost());

        // Ambil detail data registrasi
        $data['detail'] = $registrasiModel->getDetail($id);
        // Ambil detail mentor
        $data['detail_mentor'] = $detailRegisModel->getDetailWithMentor($id);
        // Ambil daftar mentor
        $data['list_mentor'] = [];

        // dd($data['detail_mentor']['nipg'] !== null && $data['detail']['status'] == 'Accept');

        // Ambil daftar mentor dan filter berdasarkan jumlah anak bimbingan
        $all_mentors = $mentorModel->getData();  // Ambil semua mentor
        foreach ($all_mentors as $mentor) {
            $nipg = $mentor['nipg'];

            // Hitung jumlah anak bimbingan berdasarkan nipg
            $count_children = $detailRegisModel->countMentorChildren($nipg);

            // Jika jumlah anak bimbingan kurang dari 2, tambahkan ke list_mentor
            if ($count_children < 2) {
                $data['list_mentor'][] = $mentor;
            }
        }

        // Ambil data timeline dari registrasi
        $data['timeline'] = $registrasiModel->getTimeline($id);

        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id);

        $data['anak_magang'] = $this->anakMagangModel->getPesertaByIdMagang($id_magang);
        // dd($data['anak_magang']);
        // dd($data['detail_mentor']);
        // Split timeline berdasarkan tanda koma (atau tanda lainnya sesuai format)
        if (!empty($data['timeline'])) {
            $data['timeline'] = explode(',', $data['timeline']);
        }

        if (!$data['detail']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        return view('templates/header')
            . view('templates/sidebar')
            . view('templates/topbar')
            . view('templates/detail', $data)
            . view('templates/footer');
    }

    private function createRenameFile($type, $name, $tipe, $nim, $instansi, $date)
    {
        return strtolower($type . '_' . str_replace(' ', '_', $name) . '_' . $tipe . '_' . $nim . '_' . $instansi . '_' . $date . '.' . pathinfo($_FILES[$type]['name'], PATHINFO_EXTENSION));
    }
    public function upload_surat_perjanjian()
    {
        // Cek apakah ada file yang diupload
        if ($this->request->getFile('surat_perjanjian')->isValid()) {
            $file = $this->request->getFile('surat_perjanjian');

            // Tentukan direktori penyimpanan file
            $path = FCPATH . 'uploads/surat_perjanjian_sent';  // Anda dapat menyesuaikan path sesuai dengan struktur direktori Anda

            // Pastikan direktori ada, jika tidak, buat
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $id_register = $this->request->getPost('id_register');

            $registrasi = $this->registrasiModel->getRegistrasiById($id_register);

            $nama = $registrasi['nama'];
            $instansi = $registrasi['instansi'];
            $tipe = $registrasi['tipe'];
            $nik = $registrasi['nomor'];
            $tanggal = date('Ymd');

            // dd($this->request->getPost());
            // Pindahkan file ke direktori yang sudah ditentukan
            $newName = $this->createRenameFile('surat_perjanjian', $nama, $tipe, $nik, $instansi, $tanggal);
            $fileName = $file->getRandomName();
            $file->move($path, $newName);

            // Simpan nama file ke database (misalnya di tabel 'detail' atau tabel lain yang relevan)
            $id_register = $this->request->getPost('id_register');
            $this->registrasiModel->update($id_register, ['surat_perjanjian' => $newName]);

            // Menampilkan flash message sukses
            session()->setFlashdata('success', 'Surat perjanjian berhasil diupload.');
        } else {
            // Jika tidak ada file yang diupload atau file tidak valid
            session()->setFlashdata('error', 'Terjadi kesalahan saat mengupload file.');
        }

        // Redirect kembali ke halaman sebelumnya (atau halaman detail)
        return redirect()->back();
    }


    public function updateStatus()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $id = $this->request->getPost('id');
        $action = strtolower($this->request->getPost('action'));
        $nipg = $this->request->getPost('nipg');

        if ($id && in_array($action, ['accept', 'reject'])) {
            $registrasiModel = new RegistrasiModel();
            $mentorModel = new MentorModel();
            $detailRegisModel = new DetailRegisModel();
            $anakMagangModel = new AnakMagangModel();

            $peserta = $registrasiModel->getPesertaById($id);
            if (!$peserta) {
                $this->session->setFlashdata('error', 'Peserta tidak ditemukan.');
                return redirect()->to('/admin/dashboard');
            }

            $statusUpdate = ($action === 'accept') ? 'Accept' : 'reject';
            $registrasiModel->updateTimelineAcc($id, 'Pencarian Mentor');

            $lastPrimaryKey = $detailRegisModel->selectMax('iddetail')->first();
            $newPrimaryKey = isset($lastPrimaryKey['iddetail']) ? $lastPrimaryKey['iddetail'] + 1 : 1;

            if ($statusUpdate === 'Accept') {
                $dataDetailRegis = [
                    'iddetail' => $newPrimaryKey,
                    'id_register' => $id,
                    'nipg' => $nipg,
                    'approved' => 'W'
                ];

                $detailRegisModel->insertDetailRegis($dataDetailRegis);

                $mentor = $mentorModel->getMentorByNipg($nipg);

                $fileName = $peserta['surat_perjanjian'];

                $this->sendEmailToPesertaSuratPerjanjian($mentor, $peserta, $statusUpdate, $fileName);

                // $unitKerja = $peserta['minat'];
                // $tglMulai = $peserta['tanggal1'];
                // $tglSelesai = $peserta['tanggal2'];

                // $lastPrimaryKey = $anakMagangModel->selectMax('id_magang')->first();
                // $newPrimaryKey = isset($lastPrimaryKey['id_magang']) ? $lastPrimaryKey['id_magang'] + 1 : 1;
                // $dataAnakMagang = [
                //     'id_magang' => $newPrimaryKey,
                //     'id_register' => $id,
                //     'unit_kerja' => $unitKerja,
                //     'tgl_mulai' => $tglMulai,
                //     'tgl_selesai' => $tglSelesai,
                //     'id_mentor' => $mentor['id_mentor'],
                // ];

                // $insertSuccess = $anakMagangModel->insertAnakMagang($dataAnakMagang);

                // if (!$insertSuccess) {
                //     $this->session->setFlashdata('error', 'Gagal memasukkan data ke tabel anak_magang.');
                //     return redirect()->to('/admin/dashboard');
                // }
                return redirect()->to('/admin/dashboard');
            } elseif ($statusUpdate === 'reject') {
                $mentor = $mentorModel->getMentorByNipg($nipg);
                $registrasiModel->updateTimelineAcc($id, null);
                $data = [
                    'status' => 'reject'
                ];
                $registrasiModel->update($peserta['id_register'], $data);
                $this->sendEmailToPesertaSuratPerjanjian($mentor, $peserta, $statusUpdate);
                return redirect()->to('/admin/dashboard');
            }

            $this->session->setFlashdata('success', 'Status berhasil diperbarui.');
            return redirect()->to('/admin/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Data atau aksi tidak valid.');
            return redirect()->to('/admin/dashboard');
        }
    }

    private function sendEmailToPesertaSuratPerjanjian($mentor, $peserta, $statusUpdate, $fileName = null)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login
        helper('date');

        $registrasiModel = new RegistrasiModel();
        $userModel = new UserModel();

        if ($user_level !== 'admin') {
            return view('no_access');
        }


        $email = \Config\Services::email();

        $email->setFrom('mdndfzn@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($peserta['email']);

        if ($statusUpdate === 'Accept') {
            $username = strtolower($peserta['tipe']) . $peserta['id_register'];
            $password = bin2hex(random_bytes(4));
            $userModel->insert([
                'nomor' => $peserta['nomor'],
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'level' => 'user',
                'aktif' => 'Y',
                'id_register' => $peserta['id_register']
            ]);
            $email->setSubject('Pendaftaran Anda Telah Diterima');
            $email->setMessage("
            <html>
            <head>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <p>Kepada Yth.</p>
                <p>{$peserta['nama']}</p>
                <br>
                <p>Sehubungan dengan pengajuan {$peserta['tipe']}, bersama ini dimohon kesediaannya untuk mengisi surat perjanjian sesuai template.</p>
                <p>Silahkan upload surat perjanjian yang telah ditandatangani ke dalam sistem.</p>
                <p>Berikut merupakan data akun anda.</p>

                <br>
                <p>Username : {$username}</p>
                <p>Password : {$password}</p>
                <br>
                <p>Silakan login ke sistem untuk informasi lebih lanjut, atau klik link berikut:</p>
                <p><a href='" . base_url('login') . "'>Link</a></p>
            </body>
            </html>
        ");
            $email->setMailType('html'); // Mengatur email dalam format HTML
            // Jika ada file surat perjanjian yang diupload, lampirkan file
            // dd($fileName);
            if ($fileName) {
                $filePath = FCPATH . 'uploads/surat_perjanjian_sent/' . $fileName;
                // dd($filePath);
                $email->attach($filePath);
            }
        } elseif ($statusUpdate === 'reject') {
            $email->setSubject('Hasil Pendaftaran Program');
            $email->setMessage("
            <html>
            <head>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <p>Kepada Yth.</p>
                <p>{$peserta['nama']}</p>
                <br>
                <p>Pendaftaran program yang anda pilih tidak diterima</p>
                <p>Salam Admin Program</p>
            </body>
            </html>
        ");
            $email->setMailType('html'); // Mengatur email dalam format HTML
        }
        $email->send();
        return redirect()->to('/admin/dashboard');
    }

    public function update_tanggal()
    {
        // Validasi request
        $id = $this->request->getPost('id');
        $tanggalMulai = $this->request->getPost('tanggalMulai');
        $tanggalSelesai = $this->request->getPost('tanggalSelesai');
        $registrasiModel = new RegistrasiModel();
        $detailRegisModel = new DetailRegisModel();
        $mentorModel = new MentorModel();

        // Ambil detail data registrasi
        $registrasi = $registrasiModel->getDetail($id);

        if (!$id || !$tanggalMulai || !$tanggalSelesai) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        // Load model
        $anakMagangModel = new AnakMagangModel();

        // Update data di database
        $data = [
            'tanggal1' => $tanggalMulai,
            'tanggal2' => $tanggalSelesai
        ];

        if ($registrasiModel->update($id, $data)) {
            return redirect()->back()->with('success', 'Tanggal berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui tanggal.');
        }
    }

    public function terima_surat_perjanjian()
    {
        // Pastikan id_register diterima dari form
        $id_register = $this->request->getPost('id_register');
        $id_magang = $this->anakMagangModel->getIdMagangByRegister($id_register);
        $anak_magang = $this->anakMagangModel->getPesertaByIdMagangOne($id_magang);
        $mentor = $this->mentorModel->getMentorByIdMentorOne($anak_magang['id_mentor']);
        // dd($nipg);
        // Model Registrasi
        $registrasiModel = new RegistrasiModel();

        // Cek apakah id_register ada
        $detail = $registrasiModel->find($id_register);
        if (!$detail) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Perbarui status menjadi 'Accept'
        $data = [
            'status' => 'Accept'
        ];

        $registrasiModel->update($id_register, $data);
        $registrasiModel->updateTimelineAcc($id_register, 'Kegiatan Dimulai');

        $data = [
            'status' => 'Aktif'
        ];

        $this->anakMagangModel->update($id_magang, $data);

        // $mentor = $this->mentorModel->getMentorByNipg($nipg);
        // dd($peserta);

        if ($mentor) {
            $this->sendEmailToPesertaAcc($mentor, $detail, true);
        } else {
            $this->session->setFlashdata('error', 'Informasi mentor tidak ditemukan.');
            return redirect()->to('/admin/dashboard');
        }

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->to('/admin/dashboard/detail/' . $id_register)->with('success', 'Surat Perjanjian diterima');
    }

    public function pilih_mentor()
    {
        // Ambil data dari form
        $id_register = $this->request->getPost('id_register');
        $nipg = $this->request->getPost('nipg');  // Mentor yang dipilih

        $peserta = $this->registrasiModel->getPesertaById($id_register);
        // Pastikan data yang dipilih ada
        if (empty($nipg)) {
            return redirect()->back()->with('error', 'Silakan pilih mentor');
        }
        $mentor = $this->mentorModel->getMentorByNipg($nipg);
        // Model DetailRegis
        $detailRegisModel = new DetailRegisModel();

        // Cek apakah pendaftar dan mentor ada
        $detail = $detailRegisModel->where('id_register', $id_register)->first();

        $this->sendEmailToMentor($mentor, $peserta);

        $unitKerja = $peserta['minat'];
        $tglMulai = $peserta['tanggal1'];
        $tglSelesai = $peserta['tanggal2'];

        $lastPrimaryKey = $this->anakMagangModel->selectMax('id_magang')->first();
        $newPrimaryKey = isset($lastPrimaryKey['id_magang']) ? $lastPrimaryKey['id_magang'] + 1 : 1;
        $dataAnakMagang = [
            'id_magang' => $newPrimaryKey,
            'id_register' => $id_register,
            'unit_kerja' => $unitKerja,
            'tgl_mulai' => $tglMulai,
            'tgl_selesai' => $tglSelesai,
            'id_mentor' => $mentor['id_mentor'],
        ];

        $insertSuccess = $this->anakMagangModel->insertAnakMagang($dataAnakMagang);
        // dd($detail);
        if (!$detail) {
            return redirect()->back()->with('error', 'Data registrasi tidak ditemukan');
        }

        // Update NIPG pada tabel detailregis
        $data = [
            'nipg' => $nipg,  // NIPG mentor yang dipilih
        ];

        // Perbarui data
        $detailRegisModel->update($detail['iddetail'], $data);

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->to('/admin/dashboard/detail/' . $id_register)->with('success', 'Mentor berhasil dipilih');
    }



    // Update Status Before UAT
    public function updateStatusBeforeUat()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $id = $this->request->getPost('id');
        $action = strtolower($this->request->getPost('action'));
        $nipg = $this->request->getPost('nipg');

        if ($id && in_array($action, ['accept', 'reject'])) {
            $registrasiModel = new RegistrasiModel();
            $mentorModel = new MentorModel();
            $detailRegisModel = new DetailRegisModel();
            $anakMagangModel = new AnakMagangModel();

            $peserta = $registrasiModel->getPesertaById($id);
            if (!$peserta) {
                $this->session->setFlashdata('error', 'Peserta tidak ditemukan.');
                return redirect()->to('/admin/dashboard');
            }

            $statusUpdate = ($action === 'accept') ? 'Accept' : 'reject';
            $registrasiModel->updateTimelineAcc($id, 'Pencarian Mentor');


            $lastPrimaryKey = $detailRegisModel->selectMax('iddetail')->first();
            $newPrimaryKey = isset($lastPrimaryKey['iddetail']) ? $lastPrimaryKey['iddetail'] + 1 : 1;

            if ($statusUpdate === 'Accept') {
                $dataDetailRegis = [
                    'iddetail' => $newPrimaryKey,
                    'id_register' => $id,
                    'nipg' => $nipg,
                    'approved' => 'W'
                ];

                $detailRegisModel->insertDetailRegis($dataDetailRegis);

                $mentor = $mentorModel->getMentorByNipg($nipg);
                // dd($peserta);

                if ($mentor) {
                    $this->sendEmailToMentor($mentor, $peserta);

                    $unitKerja = $peserta['minat'];
                    $tglMulai = $peserta['tanggal1'];
                    $tglSelesai = $peserta['tanggal2'];

                    $lastPrimaryKey = $anakMagangModel->selectMax('id_magang')->first();
                    $newPrimaryKey = isset($lastPrimaryKey['id_magang']) ? $lastPrimaryKey['id_magang'] + 1 : 1;
                    $dataAnakMagang = [
                        'id_magang' => $newPrimaryKey,
                        'id_register' => $id,
                        'unit_kerja' => $unitKerja,
                        'tgl_mulai' => $tglMulai,
                        'tgl_selesai' => $tglSelesai,
                        'id_mentor' => $mentor['id_mentor'],
                    ];

                    $insertSuccess = $anakMagangModel->insertAnakMagang($dataAnakMagang);

                    if (!$insertSuccess) {
                        $this->session->setFlashdata('error', 'Gagal memasukkan data ke tabel anak_magang.');
                        return redirect()->to('/admin/dashboard');
                    }
                } else {
                    $this->session->setFlashdata('error', 'Informasi mentor tidak ditemukan.');
                    return redirect()->to('/admin/dashboard');
                }
            } elseif ($statusUpdate === 'reject') {
                $this->sendEmailToPeserta($peserta, $statusUpdate);
            }

            $this->session->setFlashdata('success', 'Status berhasil diperbarui.');
            return redirect()->to('/admin/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Data atau aksi tidak valid.');
            return redirect()->to('/admin/dashboard');
        }
    }

    private function sendEmailToMentor($mentor, $peserta)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }

        // Pastikan nilai-nilai ini valid
        $mentorEmail = $mentor['email'] ?? '';
        if (empty($mentorEmail)) {
            log_message('error', 'Email mentor kosong');
            return false;
        }

        // Cek apakah peserta memiliki data yang valid
        if (empty($peserta['nama']) || empty($peserta['email'])) {
            log_message('error', 'Data peserta tidak lengkap');
            return false;
        }

        $email = \Config\Services::email();

        // Mengatur alamat pengirim, penerima, dan subjek
        $email->setFrom('mdndfzn@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($mentor['email']);
        $email->setSubject('Permohonan Persetujuan Anak Bimbingan Baru');

        // Membuat konten email dalam format HTML
        $email->setMessage("
            <html>
            <head>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <p>Kepada Yth.</p>
                <p>{$mentor['nama']}</p>
                <br>
                <p>Sehubungan dengan pengajuan {$peserta['tipe']}, bersama ini dimohon kesediaannya untuk dapat menjadi mentor dari:</p>
                <br>
                <p>Nama : {$peserta['nama']}</p>
                <p>Alamat : {$peserta['alamat']}</p>
                <p>Telepon : {$peserta['notelp']}</p>
                <p>Alamat Email : {$peserta['email']}</p>
                <br>
                <p>Silakan login ke sistem untuk informasi lebih lanjut, atau klik link berikut:</p>
                <p><a href='" . base_url('mentor/dashboard/daftar_peserta') . "'>Link</a></p>
            </body>
            </html>
        ");
        $email->setMailType('html'); // Mengatur email dalam format HTML

        // Menambahkan lampiran dari tabel registrasi
        $attachments = ['surat_permohonan', 'proposal_magang', 'cv', 'fc_ktp', 'foto'];
        foreach ($attachments as $file) {
            if (!empty($peserta[$file])) {
                $filePath = FCPATH . 'uploads/' . $peserta[$file];
                if (file_exists($filePath)) {
                    $email->attach($filePath);
                } else {
                    log_message('error', "File {$file} tidak ditemukan di path: {$filePath}");
                }
            }
        }

        // Mengirim email
        if ($email->send()) {
            log_message('info', 'Email berhasil dikirim ke ' . $mentor['email']);
            return true;
        } else {
            log_message('error', 'Gagal mengirim email ke ' . $mentor['email']);
            log_message('error', $email->printDebugger(['headers']));
            return false;
        }
    }

    private function sendEmailToPeserta($peserta, $status, $mentor = null)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $email = \Config\Services::email();

        $email->setFrom('mdndfzn@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($peserta['email']);

        if ($status === 'Accept' && $mentor) {
            $email->setSubject('Selamat! Pendaftaran Anda Telah Diterima');
            $email->setMessage("
				Kepada Yth. {$peserta['nama']},
				
				Dengan hormat,
				Kami dengan senang hati menginformasikan bahwa pendaftaran Anda dalam program ini telah diterima.
				
				Berikut adalah informasi terkait mentor Anda:
				- Nama: {$mentor['nama']}
				- NIPG: {$mentor['nipg']}
				- Email: {$mentor['email']}
				- Satuan Kerja: {$mentor['division']}
				
				Silakan login ke sistem kami untuk informasi lebih lanjut dan memulai program ini. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.
				
				Terima kasih atas partisipasi Anda.
	
				Hormat kami,
				Admin Program
            ");
        } elseif ($status === 'reject') {
            $email->setSubject('Hasil Pendaftaran Program');
            $email->setMessage("
            Kepada Yth. {$peserta['nama']},
				
				Dengan hormat,
				Kami mengucapkan terima kasih atas minat dan partisipasi Anda dalam program ini. 
				Namun, dengan berat hati kami sampaikan bahwa pendaftaran Anda belum dapat diterima.
				
				Kami mendorong Anda untuk tetap semangat dan terus meningkatkan kemampuan Anda. 
				Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami.
	
				Hormat kami,
				Admin Program
            ");
        }

        return $email->send();
    }

    private function sendEmailToPesertaAcc($mentor, $peserta, $status)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $email = \Config\Services::email();

        $email->setFrom('mdndfzn@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($peserta['email']);

        if ($status && $mentor) {
            $email->setSubject('Selamat! Pendaftaran Anda Telah Diterima');
            $email->setMessage("

            <html>
            <head>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <p>Kepada Yth.</p>
                <p>{$peserta['nama']}</p>
                <p>Dengan Hormat</p>
                <br>
                <p>Kami dengan senang hati menginformasikan bahwa pendaftaran Anda dalam program ini telah diterima.</p>
                <p>Berikut adalah informasi terkait mentor Anda:</p>

                <br>
                <p>- Nama: {$mentor['nama']}</p>
                <p>- NIPG: {$mentor['nipg']}</p>
                <p>- Email: {$mentor['email']}</p>
                <p>- Satuan Kerja: {$mentor['division']}</p>
                <br>
                <p>Silakan login ke sistem kami untuk informasi lebih lanjut dan memulai program ini. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
                <p>Terima kasih atas partisipasi Anda.</p>
                <p>Hormat kami,</p>
                <p>Admin Program</p>

            </body>
            </html>
            ");
        } elseif ($status == 'reject') {
            $email->setSubject('Hasil Pendaftaran Program');
        }

        return $email->send();
    }
    public function file($file_name)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $file_name = urldecode($file_name); // Decode URL jika perlu
        $file_path = FCPATH . 'uploads/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null)->setFileName($file_name);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }
    }
    public function downloadAll($id)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level');

        if ($user_level !== 'admin') {
            return view('no_access');
        }

        $user_data = $this->registrasiModel->getUserFiles($id);
        $user_data_diri = $this->registrasiModel->getUserDataDiri($id);

        if (!$user_data || !$user_data_diri) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $upload_path = FCPATH . 'uploads/';
        $zip = new ZipArchive();
        // Gunakan direktori sementara yang disediakan sistem
        $zip_file_path = sys_get_temp_dir() . '/lampiran_magang_' . uniqid() . '.zip';

        if ($zip->open($zip_file_path, ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Tidak dapat membuat file ZIP');
        }

        foreach ($user_data as $file) {
            $file_path = $upload_path . $file;

            if (file_exists($file_path)) {
                $zip->addFile($file_path, basename($file_path));
            } else {
                log_message('error', "File tidak ditemukan: $file_path");
            }
        }

        $nama = $user_data_diri['nama'];
        $nim = $user_data_diri['nik'];
        $instansi = $user_data_diri['instansi'];
        $date = date('Y-m-d');

        $zip_file_name = 'lampiran_magang_' . $instansi . '_' . $nama . '_' . $nim . '_' . $date . '.zip';

        if ($zip->close() === false) {
            throw new \RuntimeException('Tidak dapat menutup file ZIP');
        }

        return $this->response->download($zip_file_path, null)->setFileName($zip_file_name);
    }

    public function data_mentor()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $data['mentor'] = $this->mentorModel->getDataDesc();

        return view('templates/header') .
            view('templates/sidebar') .
            view('templates/topbar') .
            view('templates/mentor', $data) .
            view('templates/footer');
    }

    public function detailDataMentor($id_mentor)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');
        $data['detail_mentor'] = $this->mentorModel->getMentorByIdMentor($id_mentor);
        $data['nilai'] = $this->nilaiModel->getNilaiByIdMentor($id_mentor);

        foreach ($data['nilai'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/detail_data_mentor', $data);
        echo view('templates/footer');
    }

    public function detailDataPeserta($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');

        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['id_magang'] = $id_magang;
        if (!$data['detail_peserta']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peserta tidak ditemukan');
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/detail_data_peserta', $data);
        echo view('templates/footer');
    }

    public function detail_data_m_peserta($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }

        helper('date');

        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        if (!$data['detail_peserta']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peserta tidak ditemukan');
        }

        foreach ($data['detail_peserta'] as &$peserta) {
            // Menangani file foto
            if (!empty($peserta->foto)) {
                $peserta->foto = base_url('uploads/foto/' . $peserta->foto);
            } else {
                $peserta->foto = base_url('uploads/foto/default.png');
            }

            // Menangani file lampiran (hanya menyimpan nama file)
            if (!empty($peserta->surat_permohonan)) {
                $peserta->surat_permohonan = basename($peserta->surat_permohonan); // Hanya nama file
            }
            if (!empty($peserta->proposal_magang)) {
                $peserta->proposal_magang = basename($peserta->proposal_magang); // Hanya nama file
            }
            if (!empty($peserta->cv)) {
                $peserta->cv = basename($peserta->cv); // Hanya nama file
            }
            if (!empty($peserta->marksheet)) {
                $peserta->marksheet = basename($peserta->marksheet); // Hanya nama file
            }
            if (!empty($peserta->fc_ktp)) {
                $peserta->fc_ktp = basename($peserta->fc_ktp); // Hanya nama file
            }
            if (!empty($peserta->buku_rek)) {
                $peserta->buku_rek = basename($peserta->buku_rek); // Hanya nama file
            }
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/detail_data_m_peserta', $data);
        echo view('templates/footer');
    }

    public function data_peserta()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');
        $data['data_peserta'] = $this->anakMagangModel->getPesertaMagangDesc(); // Ambil data peserta magang
        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/peserta_magang', $data);
        echo view('templates/footer');
    }

    public function informasiAbsensi($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
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

        $data['peserta'] = $this->pesertaModel->getDetailAbsenPeserta($id_magang, $start_date, $end_date);
        $data['id_magang'] = $id_magang;

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_absensi', $data);
        echo view('templates/footer');
    }


    public function informasi_m_absensi($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
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

        $data['peserta'] = $this->pesertaModel->getDetailAbsenPeserta($id_magang, $start_date, $end_date);
        $data['id_magang'] = $id_magang;

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_m_absensi', $data);
        echo view('templates/footer');
    }

    public function cetakInformasiAbsensi($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');
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

        $data['peserta'] = $this->pesertaModel->getDetailAbsenPeserta($id_magang, $start_date, $end_date);
        $data['id_magang'] = $id_magang;

        $dompdf = new Dompdf();
        $html = view('mentor/cetak_detail_rekap_absensi_bimbingan', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Detail_Rekap_Absensi.pdf', ['Attachment' => 0]);
    }

    public function informasi_nilai_akhir($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');

        $data['nilai_akhir'] = $this->pesertaModel->getNilaiPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_nilai_akhir', $data);
        echo view('templates/footer');
    }

    public function informasi_m_nilai_akhir($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');
        $data['nilai_akhir'] = $this->pesertaModel->getNilaiPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_m_nilai_akhir', $data);
        echo view('templates/footer');
    }

    private function hitungTotalNilai($item)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
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

    public function cetak_informasi_nilai_akhir($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');
        $data['nilai_akhir'] = $this->pesertaModel->getNilaiPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        $dompdf = new Dompdf();
        $data['title'] = "Detail Rekap Nilai Akhir";
        $html = view('mentor/cetak_detail_riwayat_nilai_bimbingan', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Detail_Nilai_Akhir.pdf', ['Attachment' => 0]);
    }

    public function informasi_laporan($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['laporan'] = $this->pesertaModel->getLaporanPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_laporan', $data);
        echo view('templates/footer');
    }

    public function informasi_m_laporan($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['laporan'] = $this->pesertaModel->getLaporanPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/informasi_m_laporan', $data);
        echo view('templates/footer');
    }

    public function file_lampiran($file_name)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $file_path = FCPATH . 'uploads/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan");
        }
    }

    public function file_laporan($file_name)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $file_path = FCPATH . 'uploads/laporan/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan: {$file_name}");
        }
    }

    public function download_buku_rekening($file_name)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $file_path = FCPATH . 'uploads/buku_rekening/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan");
        }
    }

    public function perpanjang_peserta($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }

        helper('date');
        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        if (!$data['detail_peserta']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peserta tidak ditemukan");
        }

        // Pastikan foto peserta sudah di-setup
        foreach ($data['detail_peserta'] as &$peserta) {
            if (!empty($peserta->foto)) {
            } else {
                $peserta->foto = 'default.png'; // Foto default jika tidak ada
            }
        }
        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/perpanjang_peserta', $data);
        echo view('templates/footer');
    }

    public function perpanjang_magang()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }

        // Ambil data yang diperlukan
        $id_magang = $this->request->getPost('id_magang');
        $tgl_perpanjangan = $this->request->getPost('tgl_perpanjangan');

        // Validasi input
        if (empty($id_magang) || empty($tgl_perpanjangan)) {
            session()->setFlashdata('message', 'ID Magang atau Tanggal Perpanjangan tidak valid.');
            return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
        }

        // Langkah 1: Cari id_register berdasarkan id_magang dari tabel anak_magang
        $id_register = $this->pesertaModel->getIdRegisterByIdMagang($id_magang);

        // Jika id_register ditemukan, lanjutkan proses update
        if ($id_register) {
            // Langkah 2: Update tanggal perpanjangan pada tabel anak_magang
            $data_anak_magang = ['tgl_perpanjangan' => $tgl_perpanjangan];
            $success_anak_magang = $this->pesertaModel->updateTglPerpanjanganAnakMagang($id_magang, $data_anak_magang);

            // Jika berhasil memperbarui tanggal perpanjangan pada ketiga tabel
            if ($success_anak_magang) {
                session()->setFlashdata('message', 'Tanggal perpanjangan berhasil diperbarui.');

                // Tambahkan data absensi untuk periode yang baru
                $this->tambahAbsensiBaru($id_magang, $tgl_perpanjangan, $id_register);
            } else {
                session()->setFlashdata('message', 'Gagal memperbarui tanggal perpanjangan.');
            }
        } else {
            session()->setFlashdata('message', 'ID Magang tidak ditemukan.');
        }

        return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
    }

    // public function perpanjang_magang()
    // {
    //     // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
    //     $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

    //     if ($user_level !== 'admin') {
    //         return view('no_access');
    //     }

    //     // Ambil data yang diperlukan
    //     $id_magang = $this->request->getPost('id_magang');
    //     $tgl_perpanjangan = $this->request->getPost('tgl_perpanjangan');

    //     // Validasi input
    //     if (empty($id_magang) || empty($tgl_perpanjangan)) {
    //         session()->setFlashdata('message', 'ID Magang atau Tanggal Perpanjangan tidak valid.');
    //         return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
    //     }

    //     // Update tanggal perpanjangan pada tabel registrasi dan anak_magang
    //     $data = ['tgl_perpanjangan' => $tgl_perpanjangan];
    //     $success_anak_magang = $this->pesertaModel->updateTglPerpanjanganAnakMagang($id_magang, $data);

    //     // Jika berhasil memperbarui tanggal perpanjangan pada kedua tabel
    //     if ($success_anak_magang) {
    //         session()->setFlashdata('message', 'Tanggal perpanjangan berhasil diperbarui.');

    //         // Tambahkan data absensi untuk periode yang baru
    //         $this->tambahAbsensiBaru($id_magang, $tgl_perpanjangan);
    //     } else {
    //         session()->setFlashdata('message', 'Gagal memperbarui tanggal perpanjangan.');
    //     }

    //     return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
    // }

    private function tambahAbsensiBaru($id_magang, $tgl_perpanjangan, $id_register)
    {
        // Ambil data tanggal selesai lama
        $detail = $this->pesertaModel->getDetailPesertaByIdMagang($id_magang);
        $tgl_selesai_lama = $detail['tgl_selesai']; // Tanggal selesai lama dari tabel anak_magang

        // Jika tanggal selesai lama lebih kecil dari tanggal perpanjangan, tambahkan data absensi baru
        if ($tgl_selesai_lama < $tgl_perpanjangan) {

            // Langkah 3: Update tanggal2 pada tabel registrasi menggunakan id_register yang ditemukan
            $data_registrasi = ['tanggal2' => $tgl_perpanjangan];
            $success_registrasi = $this->pesertaModel->updateTglPerpanjanganRegistrasi($id_register, $data_registrasi);

            // Langkah 4: Update tanggal_selesai pada tabel anak_magang
            $data_tanggal_selesai = ['tgl_selesai' => $tgl_perpanjangan];
            $success_tanggal_selesai = $this->pesertaModel->updateTglSelesaiAnakMagang($id_magang, $data_tanggal_selesai);

            if ($success_registrasi && $success_tanggal_selesai) {
                // Loop untuk menambahkan data absensi baru dari tanggal selesai lama (tgl_selesai_lama) hingga tanggal perpanjangan
                $start_date = new DateTime($tgl_selesai_lama);
                $end_date = new DateTime($tgl_perpanjangan);
                $end_date->modify('+1 day'); // Menambahkan 1 hari agar tanggal perpanjangan tercakup
                $interval = new DateInterval('P1D'); // Menambahkan 1 hari per iterasi
                $daterange = new DatePeriod($start_date, $interval, $end_date);

                foreach ($daterange as $date) {
                    // Cek apakah absensi untuk tanggal ini sudah ada
                    $absen_exists = $this->pesertaModel->checkAbsensiExists($id_magang, $date->format('Y-m-d'));

                    // Jika absensi belum ada untuk tanggal ini, tambahkan
                    if (!$absen_exists) {
                        // Siapkan data untuk absensi baru
                        $data_absen = [
                            'id_magang' => $id_magang,
                            'tgl' => $date->format('Y-m-d'),
                            'statuss' => null, // Status default hadir
                            'approved' => null, // Menunggu persetujuan
                            'jam_masuk' => null, // Kosongkan jam masuk
                            'jam_pulang' => null, // Kosongkan jam pulang
                            'latitude_masuk' => null,
                            'longitude_masuk' => null,
                            'latitude_keluar' => null,
                            'longitude_keluar' => null,
                            'deskripsi' => null
                        ];

                        // Masukkan data absensi baru ke database
                        $this->pesertaModel->tambahAbsensi($data_absen);
                    }
                }
            }
        }
    }



    // public function perpanjang_magang()
    // {
    //     $id_magang = $this->request->getPost('id_magang');
    //     $tgl_perpanjangan = $this->request->getPost('tgl_perpanjangan');

    //     if (empty($id_magang) || empty($tgl_perpanjangan)) {
    //         session()->setFlashdata('message', 'ID Magang atau Tanggal Perpanjangan tidak valid.');
    //         return redirect()->to('dashboard/detail/' . $id_magang);
    //     }

    //     $data = ['tgl_perpanjangan' => $tgl_perpanjangan];
    //     $success = $this->pesertaModel->updateTglPerpanjangan($id_magang, $data);

    //     if ($success) {
    //         session()->setFlashdata('message', 'Tanggal perpanjangan berhasil diperbarui.');
    //     } else {
    //         session()->setFlashdata('message', 'Gagal memperbarui tanggal perpanjangan.');
    //     }

    //     return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
    // }

    public function changeStatus($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $status = $this->request->getPost('status');

        if ($status) {
            $data = $this->pesertaModel->getDetailPeserta($id_magang);

            if ($data) {
                $update_data = ['status' => $status];
                $this->pesertaModel->updateStatus($id_magang, $update_data);

                session()->setFlashdata('status_message', 'Status peserta magang berhasil diubah!');
            } else {
                session()->setFlashdata('error_message', 'Peserta magang tidak ditemukan!');
            }
        }

        return redirect()->to('admin/dashboard/detail_data_m_peserta/' . $id_magang);
    }

    public function exportToExcel()
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        helper('date');  // Load helper 'date'

        // Ambil data peserta magang
        $data_peserta = $this->anakMagangModel->getPesertaMagang();  // Ambil data peserta magang

        // Buat objek spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header untuk file Excel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Instansi');
        $sheet->setCellValue('E1', 'Periode Magang');
        $sheet->setCellValue('F1', 'Status');

        // Masukkan data ke dalam spreadsheet
        $row = 2;
        foreach ($data_peserta as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item['nomor']); // Akses array
            $sheet->setCellValue('C' . $row, $item['nama']);
            $sheet->setCellValue('D' . $row, $item['instansi']);
            $sheet->setCellValue('E' . $row, formatTanggalIndo($item['tanggal1']) . ' - ' . formatTanggalIndo($item['tanggal2']));
            $sheet->setCellValue('F' . $row, $item['status']);
            $row++;
        }

        // Set header untuk mendownload file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data_Peserta_Magang.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file dan langsung output ke browser
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function generateSertifikat($id_magang)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        // Ambil data peserta berdasarkan ID magang
        $peserta = $this->anakMagangModel->getPesertaByIdMagang($id_magang);

        if (!$peserta) {
            return redirect()->to('admin/dashboard')->with('error', 'Peserta tidak ditemukan');
        }

        // Buat sertifikat dalam format HTML
        $html = view('templates/sertifikat_template', ['peserta' => $peserta]);

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Load HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape');

        // Render HTML menjadi PDF
        $dompdf->render();

        // Output file PDF ke browser
        $dompdf->stream("sertifikat_" . $peserta['nama'] . ".pdf", ["Attachment" => false]);
    }

    public function sertifikat($id)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $id_magang = $id;
        $anakMagang = $this->anakMagangModel->find($id);
        $user_register = $this->registrasiModel->getRegistrasiById($id);
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
        $data['user_register'] = $user_register;

        return view('templates/header') .
            view('templates/sidebar') .
            view('templates/topbar') .
            view('templates/sertifikat', $data) . // Pastikan $data dikirim ke view
            view('templates/footer');
    }

    public function cetak($id)
    {
        // Cek level pengguna dari session (misalnya 'level' menyimpan informasi jenis pengguna)
        $user_level = $this->session->get('level'); // Pastikan 'level' di-set saat login

        if ($user_level !== 'admin') {
            return view('no_access');
        }
        $registrasiModel = new RegistrasiModel();
        $user_nomor = $this->session->get('nomor');
        // Ambil data nilai dan data registrasi
        $registrasi_data = $this->registrasiModel->getRegistrasiById($id); // Ambil data dari tabel registrasi berdasarkan nomo

        $data['registrasi'] = $registrasi_data;

        if (!$data['registrasi']) {
            return redirect()->to('/')->with('error', 'Data tidak ditemukan.');
        }

        return view('templates/cetak_sertifikat', $data);
    }

    // Submit nomor sertifikat dan update ke database
    public function submitNoSertifikat()
    {
        $no_sertif = $this->request->getPost('no_sertif');
        $id_register = $this->request->getPost('id_register');
        // Validasi input
        if (empty($no_sertif)) {
            return redirect()->back()->with('error', 'Nomor sertifikat tidak boleh kosong!');
        }

        $registrasiModel = new RegistrasiModel();
        $registrasiModel->update($id_register, ['no_sertif' => $no_sertif]);

        // Redirect dan beri pesan bahwa sertifikat berhasil disimpan
        return redirect()->to('admin/dashboard/data_peserta')->with('message', 'Nomor Sertifikat berhasil disimpan. Silakan unduh sertifikat.');
    }
}
