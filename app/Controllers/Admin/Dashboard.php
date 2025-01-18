<?php

namespace App\Controllers\Admin;

use App\Models\PesertaModel;
use App\Models\RegistrasiModel;
use App\Models\DetailRegisModel;
use App\Models\MentorModel;
use App\Models\AnakMagangModel;
use App\Models\NilaiModel;

use ZipArchive;

use App\Controllers\BaseController;


class Dashboard extends BaseController
{
    protected $session;
    protected $registrasiModel;
    protected $mentorModel;
    protected $nilaiModel;
    protected $pesertaModel;


    public function __construct()
    {
        $this->session = session();
        $this->registrasiModel = new RegistrasiModel();
        $this->mentorModel = new MentorModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel;


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

        $data['detail'] = $registrasiModel->getDetail($id);
        $data['detail_mentor'] = $detailRegisModel->getDetailWithMentor($id);
        $data['list_mentor'] = $mentorModel->getData();

        if (!$data['detail']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        return view('templates/header')
            . view('templates/sidebar')
            . view('templates/topbar')
            . view('templates/detail', $data)
            . view('templates/footer');
    }

    // public function updateStatus()
    // {
    //     $id = $this->request->getPost('id');
    //     $action = strtolower($this->request->getPost('action'));
    //     $nipg = $this->request->getPost('nipg');

    //     // Debug untuk memastikan data diterima dengan benar
    //     if (!$id || !$action || !$nipg) {
    //         log_message('error', 'Data tidak lengkap: id=' . $id . ', action=' . $action . ', nipg=' . $nipg);
    //         $this->session->setFlashdata('error', 'Data tidak lengkap.');
    //         return redirect()->to('/admin/dashboard');
    //     }
    // }


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

    //         if ($statusUpdate === 'Accept') {
    //             $dataDetailRegis = [
    //                 'id_register' => $id,
    //                 'nipg' => $nipg,
    //                 'approved' => 'Y'
    //             ];

    //             $detailRegisModel->insertDetailRegis($dataDetailRegis);

    //             $mentor = $mentorModel->getMentorByNipg($nipg);

    //             if ($mentor) {
    //                 $this->sendEmailToMentor($mentor, $peserta);
    //                 $this->sendEmailToPeserta($peserta, $statusUpdate, $mentor);

    //                 $unitKerja = $peserta['minat'];
    //                 $tglMulai = $peserta['tanggal1'];
    //                 $tglSelesai = $peserta['tanggal2'];

    //                 $dataAnakMagang = [
    //                     'id_register' => $id,
    //                     'unit_kerja' => $unitKerja,
    //                     'tgl_mulai' => $tglMulai,
    //                     'tgl_selesai' => $tglSelesai,
    //                     'id_mentor' => $mentor['id_mentor']
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

        $email->setFrom('ormasbbctestt@gmail.com', 'PGN GAS Admin Internship Program');
        $email->setTo($mentor['email']);
        $email->setSubject('Informasi Anak Bimbingan Baru');
        $email->setMessage("
        Anda memiliki anak bimbingan baru:
			- Nama: {$peserta['nama']}
			- No Telp: {$peserta['no_telp']}
			- Email: {$peserta['email']}
			- Prodi: {$peserta['prodi']}
			- Fakultas: {$peserta['fakultas']}
			- Instansi: {$peserta['instansi']}
			- Satuan Kerja: {$peserta['minat']}
			
			Silakan login ke sistem untuk informasi lebih lanjut.
        ");

        return $email->send();
    }

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
        $file_path = WRITEPATH . 'uploads/' . $file_name;

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

        $upload_path = WRITEPATH . 'uploads/';
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
        $data['mentor'] = $this->mentorModel->getData();

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

        echo view('templates/header');
        echo view('templates/sidebar');
        echo view('templates/topbar');
        echo view('templates/detail_data_m_peserta', $data);
        echo view('templates/footer');
    }

    public function data_peserta()
    {
        helper('date');
        $data['data_peserta'] = $this->pesertaModel->getPesertaMagang();

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
        $file_path = WRITEPATH . 'uploads/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan");
        }
    }

    public function file_laporan($file_name)
    {
        $file_path = WRITEPATH . 'uploads/laporan/' . $file_name;

        if (file_exists($file_path)) {
            return $this->response->download($file_path, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan: {$file_name}");
        }
    }



    public function download_buku_rekening($file_name)
    {
        $file_path = WRITEPATH . 'uploads/buku_rekening/' . $file_name;

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
}
