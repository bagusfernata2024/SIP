<?php

namespace App\Controllers\Admin;

use App\Models\PesertaModel;
use App\Models\RegistrasiModel;
use App\Models\DetailRegisModel;
use App\Models\MentorModel;
use App\Models\AnakMagangModel;
use App\Models\NilaiModel;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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



    public function __construct()
    {
        $this->session = session();
        $this->registrasiModel = new RegistrasiModel();
        $this->mentorModel = new MentorModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel;
        $this->anakMagangModel = new AnakMagangModel;



        // Cek jika belum login
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/login/admin');
        }

        // Cek jika level bukan 'admin'
        if ($this->session->get('level') !== 'admin') {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            $this->session->destroy();
            return redirect()->to('/login/admin');
        }
    }

    public function index()
    {
        helper('date');  // Load helper 'date'
        $registrasiModel = new RegistrasiModel();

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

        $data['total_accept'] = $registrasiModel->countPesertaByStatus('Accept');
        $data['total_reject'] = $registrasiModel->countPesertaByStatus('reject');
        $data['total_waiting'] = $registrasiModel->countPesertaByStatus('Waiting');
        $data['total'] = $data['total_accept'] + $data['total_reject'] + $data['total_waiting'];

        return view('templates/header')
            . view('templates/sidebar')
            . view('templates/topbar')
            . view('templates/dashboard', $data)
            . view('templates/footer');
    }

    public function detail($id)
    {
        helper('date');  // Load helper 'date'
        $registrasiModel = new RegistrasiModel();
        $detailRegisModel = new DetailRegisModel();
        $mentorModel = new MentorModel();

        // Ambil detail data registrasi
        $data['detail'] = $registrasiModel->getDetail($id);
        // Ambil detail mentor
        $data['detail_mentor'] = $detailRegisModel->getDetailWithMentor($id);
        // Ambil daftar mentor
        $data['list_mentor'] = $mentorModel->getData();
        // Ambil data timeline dari registrasi
        $data['timeline'] = $registrasiModel->getTimeline($id);

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


    // public function detail($id)
    // {
    //     helper('date');  // Load helper 'date'
    //     $registrasiModel = new RegistrasiModel();
    //     $detailRegisModel = new DetailRegisModel();
    //     $mentorModel = new MentorModel();

    //     $data['detail'] = $registrasiModel->getDetail($id);
    //     $data['detail_mentor'] = $detailRegisModel->getDetailWithMentor($id);
    //     $data['list_mentor'] = $mentorModel->getData();

    //     if (!$data['detail']) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
    //     }


    //     return view('templates/header')
    //         . view('templates/sidebar')
    //         . view('templates/topbar')
    //         . view('templates/detail', $data)
    //         . view('templates/footer');
    // }

    public function updateStatus()
    {
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

    // public function updateStatus()
    // {
    //     $id = $this->request->getPost('id');
    //     $action = strtolower($this->request->getPost('action'));
    //     $nipg = $this->request->getPost('nipg');

    //     if ($id && in_array($action, ['accept', 'reject'])) {
    //         $registrasiModel = new RegistrasiModel();
    //         $mentorModel = new MentorModel();
    //         $detailRegisModel = new DetailRegisModel();
    //         $anakMagangModel = new AnakMagangModel();

    //         $peserta = $registrasiModel->getPesertaById($id);
    //         if (!$peserta) {
    //             $this->session->setFlashdata('error', 'Peserta tidak ditemukan.');
    //             return redirect()->to('/admin/dashboard');
    //         }

    //         $statusUpdate = ($action === 'accept') ? 'Accept' : 'reject';
    //         $registrasiModel->updateStatus($id, $statusUpdate);
    //         $registrasiModel->updateTimelineAcc($id, 'Pencarian Mentor');


    //         $lastPrimaryKey = $detailRegisModel->selectMax('iddetail')->first();
    //         $newPrimaryKey = isset($lastPrimaryKey['iddetail']) ? $lastPrimaryKey['iddetail'] + 1 : 1;

    //         if ($statusUpdate === 'Accept') {
    //             $dataDetailRegis = [
    //                 'iddetail' => $newPrimaryKey,
    //                 'id_register' => $id,
    //                 'nipg' => $nipg,
    //                 'approved' => 'W'
    //             ];

    //             $detailRegisModel->insertDetailRegis($dataDetailRegis);

    //             $mentor = $mentorModel->getMentorByNipg($nipg);

    //             if ($mentor) {
    //                 $this->sendEmailToMentor($mentor, $peserta);

    //                 $unitKerja = $peserta['minat'];
    //                 $tglMulai = $peserta['tanggal1'];
    //                 $tglSelesai = $peserta['tanggal2'];

    //                 $lastPrimaryKey = $anakMagangModel->selectMax('id_magang')->first();
    //                 $newPrimaryKey = isset($lastPrimaryKey['id_magang']) ? $lastPrimaryKey['id_magang'] + 1 : 1;
    //                 $dataAnakMagang = [
    //                     'id_magang' => $newPrimaryKey,
    //                     'id_register' => $id,
    //                     'unit_kerja' => $unitKerja,
    //                     'tgl_mulai' => $tglMulai,
    //                     'tgl_selesai' => $tglSelesai,
    //                     'id_mentor' => $mentor['id_mentor'],
    //                 ];

    //                 $insertSuccess = $anakMagangModel->insertAnakMagang($dataAnakMagang);

    //                 if (!$insertSuccess) {
    //                     $this->session->setFlashdata('error', 'Gagal memasukkan data ke tabel anak_magang.');
    //                     return redirect()->to('/admin/dashboard');
    //                 }
    //             } else {
    //                 $this->session->setFlashdata('error', 'Informasi mentor tidak ditemukan.');
    //                 return redirect()->to('/admin/dashboard');
    //             }
    //         } elseif ($statusUpdate === 'reject') {
    //             $this->sendEmailToPeserta($peserta, $statusUpdate);
    //         }

    //         $this->session->setFlashdata('success', 'Status berhasil diperbarui.');
    //         return redirect()->to('/admin/dashboard');
    //     } else {
    //         $this->session->setFlashdata('error', 'Data atau aksi tidak valid.');
    //         return redirect()->to('/admin/dashboard');
    //     }
    // }


    private function sendEmailToPeserta($peserta, $status, $mentor = null)
    {
        $email = \Config\Services::email();

        $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
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

    private function sendEmailToMentor($mentor, $peserta)
    {
        $email = \Config\Services::email();

        // Mengatur alamat pengirim, penerima, dan subjek
        $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
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

    // private function sendEmailToMentor($mentor, $peserta)
    // {
    //     $email = \Config\Services::email();

    //     $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
    //     $email->setTo($mentor['email']);
    //     $email->setSubject('Informasi Anak Bimbingan Baru');
    //     $email->setMessage("
    //     Anda memiliki anak bimbingan baru:
    // 		- Nama: {$peserta['nama']}
    // 		- No Telp: {$peserta['notelp']}
    // 		- Email: {$peserta['email']}
    // 		- Prodi: {$peserta['prodi']}
    // 		- Fakultas: {$peserta['jurusan']}
    // 		- Instansi: {$peserta['instansi']}
    // 		- Satuan Kerja: {$peserta['minat']}
    //         - Jenis Kegiatan: {$peserta['tipe']}

    // 		Silakan login ke sistem untuk informasi lebih lanjut.
    //     ");

    //     return $email->send();
    // }

    // public function file($file_name)
    // {
    //     $file_path = WRITEPATH . 'uploads/' . $file_name;

    //     if (file_exists($file_path)) {
    //         $mime_type = mime_content_type($file_path); // Mendapatkan MIME type file
    //         return $this->response
    //             ->setHeader('Content-Type', $mime_type) // Menentukan Content-Type
    //             ->setHeader('Content-Disposition', 'attachment; filename="' . basename($file_name) . '"') // Menambahkan header untuk unduhan
    //             ->setBody(file_get_contents($file_path)); // Membaca isi file
    //     } else {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
    //     }
    // }

    public function file($file_name)
    {
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
        $user_data = $this->registrasiModel->getUserFiles($id);
        $user_data_diri = $this->registrasiModel->getUserDataDiri($id);

        if (!$user_data || !$user_data_diri) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $upload_path = FCPATH . 'uploads/';
        $zip = new ZipArchive();
        $zip_file_path = tempnam(sys_get_temp_dir(), 'zip');

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
        $zip->close();

        return $this->response->download($zip_file_path, null)->setFileName($zip_file_name);
    }

    public function data_mentor()
    {
        $data['mentor'] = $this->mentorModel->getDataDesc();

        return view('templates/header') .
            view('templates/sidebar') .
            view('templates/topbar') .
            view('templates/mentor', $data) .
            view('templates/footer');
    }

    public function detailDataMentor($id_mentor)
    {
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

    // public function detailDataPeserta($id_magang)
    // {
    //     helper('date');

    //     $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
    //     $data['id_magang'] = $id_magang;

    //     if (!$data['detail_peserta']) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peserta tidak ditemukan');
    //     }

    //     // Ambil data timeline dari tabel registrasi
    //     $timelineData = $this->pesertaModel->getTimelineFromRegistrasi($id_magang); // Pastikan method ini mengambil data timeline yang diperlukan

    //     dd($timelineData);
    //     // Menambahkan data timeline ke dalam array data
    //     $data['detail_peserta']['timeline'] = $timelineData;

    //     echo view('templates/header');
    //     echo view('templates/sidebar');
    //     echo view('templates/topbar');
    //     echo view('templates/detail_data_peserta', $data);
    //     echo view('templates/footer');
    // }

    public function detailDataPeserta($id_magang)
    {
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
        helper('date');

        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        if (!$data['detail_peserta']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peserta tidak ditemukan');
        }

        foreach ($data['detail_peserta'] as &$peserta) {
            if (!empty($peserta->foto)) {
                $peserta->foto = base_url('uploads/foto/' . $peserta->foto);
            } else {
                // Jika foto tidak ada, gunakan foto default
                $peserta->foto = base_url('uploads/foto/default.png');
            }
        }

        // Pastikan hanya nama file yang disimpan
        foreach ($data['detail_peserta'] as &$peserta) {

            if (!empty($peserta->surat_permohonan)) {
                $peserta->surat_permohonan = base_url('uploads/' . $peserta->surat_permohonan);
            }
            if (!empty($peserta->proposal_magang)) {
                $peserta->proposal_magang = base_url('uploads/' . $peserta->proposal_magang);
            }
            if (!empty($peserta->cv)) {
                $peserta->cv = base_url('uploads/' . $peserta->cv);
            }
            if (!empty($peserta->marksheet)) {
                $peserta->marksheet = base_url('uploads/' . $peserta->marksheet);
            }
            if (!empty($peserta->fc_ktp)) {
                $peserta->fc_ktp = base_url('uploads/' . $peserta->fc_ktp);
            }
            if (!empty($peserta->buku_rek)) {
                $peserta->buku_rek = $peserta->buku_rek; // Simpan hanya nama file
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

        $dompdf = new \Dompdf\Dompdf();
        $html = view('mentor/cetak_detail_rekap_absensi_bimbingan', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Detail_Rekap_Absensi.pdf', ['Attachment' => 0]);
    }

    public function informasi_nilai_akhir($id_magang)
    {
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
        helper('date');
        $data['nilai_akhir'] = $this->pesertaModel->getNilaiPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        foreach ($data['nilai_akhir'] as $item) {
            $item->total_nilai = $this->hitungTotalNilai($item);
            $item->status = $item->total_nilai > 75 ? 'Lulus' : 'Tidak Lulus';
        }

        $dompdf = new \Dompdf\Dompdf();
        $data['title'] = "Detail Rekap Nilai Akhir";
        $html = view('mentor/cetak_detail_riwayat_nilai_bimbingan', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Detail_Nilai_Akhir.pdf', ['Attachment' => 0]);
    }

    public function informasi_laporan($id_magang)
    {
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
        $file_path = FCPATH . 'uploads/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan");
        }
    }

    public function file_laporan($file_name)
    {
        $file_path = FCPATH . 'uploads/laporan/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan: {$file_name}");
        }
    }



    public function download_buku_rekening($file_name)
    {
        $file_path = FCPATH . 'uploads/buku_rekening/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan");
        }
    }

    public function perpanjang_peserta($id_magang)
    {
        helper('date');
        $data['detail_peserta'] = $this->pesertaModel->getDetailPeserta($id_magang);
        $data['id_magang'] = $id_magang;

        if (!$data['detail_peserta']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peserta tidak ditemukan");
        }

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/perpanjang_peserta', $data);
        echo view('templates/footer');
    }

    public function perpanjang_magang()
    {
        $id_magang = $this->request->getPost('id_magang');
        $tgl_perpanjangan = $this->request->getPost('tgl_perpanjangan');

        if (empty($id_magang) || empty($tgl_perpanjangan)) {
            session()->setFlashdata('message', 'ID Magang atau Tanggal Perpanjangan tidak valid.');
            return redirect()->to('dashboard/detail/' . $id_magang);
        }

        $data = ['tgl_perpanjangan' => $tgl_perpanjangan];
        $success = $this->pesertaModel->updateTglPerpanjangan($id_magang, $data);

        if ($success) {
            session()->setFlashdata('message', 'Tanggal perpanjangan berhasil diperbarui.');
        } else {
            session()->setFlashdata('message', 'Gagal memperbarui tanggal perpanjangan.');
        }

        return redirect()->to('admin/dashboard/perpanjang_peserta/' . $id_magang);
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
}
