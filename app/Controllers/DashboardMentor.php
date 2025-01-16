<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AnakMagangModel;
use App\Models\LaporanModel;
use App\Models\NilaiModel;
use App\Models\PesertaModel;
use App\Libraries\PdfGenerator;

use App\Controllers\BaseController;

class DashboardMentor extends BaseController
{
    protected $absensiModel;
    protected $anakMagangModel;
    protected $laporanModel;
    protected $nilaiModel;
    protected $pesertaModel;
    protected $pdfgenerator;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->anakMagangModel = new AnakMagangModel();
        $this->laporanModel = new LaporanModel();
        $this->nilaiModel = new NilaiModel();
        $this->pesertaModel = new PesertaModel();
        $this->pdfgenerator = new PdfGenerator();

        // Session check
        if (!session()->get('mentor_logged_in')) {
            return redirect()->to('login/mentor');
        }

        if (session()->get('level') !== 'mentor') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            session()->destroy();
            return redirect()->to('login/mentor');
        }
    }

    public function index()
    {
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

    public function absensiBimbingan()
    {
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
        $file_path = WRITEPATH . 'uploads/laporan/' . $file_name; // Gunakan WRITEPATH untuk folder writable

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
        $id_magang = $this->request->getPost('id_magang');
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

        // Memuat model
        $model = new NilaiModel();

        // Update data nilai
        $result = $model->updateNilai($data, $id_magang);

        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Nilai berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui nilai']);
        }
    }

    public function riwayatNilaiBimbingan()
    {
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
