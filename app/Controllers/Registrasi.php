<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\UserModel;
use App\Models\PesertaModel;
use App\Models\DaftarMinatModel;
use App\Models\IsiEmailModel;


use CodeIgniter\Controller;

class Registrasi extends BaseController
{
    protected $mentorModel;
    protected $userModel;
    protected $pesertaModel;
    protected $daftarMinatModel;
    protected $isiEmailModel;
    protected $session;


    public function __construct()
    {
        $this->mentorModel = new MentorModel();
        $this->userModel = new UserModel();
        $this->pesertaModel = new PesertaModel();
        $this->daftarMinatModel = new DaftarMinatModel();
        $this->isiEmailModel = new IsiEmailModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {

        // Cek apakah session 'level' tersedia
        $user_level = session()->get('level'); // Ambil level pengguna dari session

        // Jika level ada, arahkan berdasarkan level pengguna
        if ($user_level) {
            if ($user_level === 'admin') {
                // Jika level user adalah admin, arahkan ke admin dashboard
                return redirect()->to('admin/dashboard');
            } elseif ($user_level === 'user') {
                // Jika level user adalah user, arahkan ke dashboard user
                return redirect()->to('dashboard');
            } elseif ($user_level === 'mentor') {
                // Jika level user adalah mentor, arahkan ke mentor dashboard
                return redirect()->to('mentor/dashboard');
            }
        }
        $message = $this->isiEmailModel->getIsiEmailById(1);
        // dd($message);
        $data['daftar_minat'] = $this->daftarMinatModel->findAll();
        return view('web_templates/header') .
            view('templates/registrasi_peserta', $data) .
            view('web_templates/footer');
    }

    public function registrasiMentor()
    {
        $data['divisions'] = $this->daftarMinatModel->findAll();
        return view('web_templates/header_special') .
            view('templates/registrasi_mentor', $data) .
            view('web_templates/footer_special');
    }
    public function prosesRegistrasiMentor()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|trim',
            'nipg' => 'required|numeric',
            'email' => 'required|valid_email',
            'gender' => 'required',
            'direktorat' => 'required',
            'division' => 'required',
            'subsidiaries' => 'required',
            'job' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/registrasi/registrasi_mentor')->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nipg' => $this->request->getPost('nipg'),
            'email' => $this->request->getPost('email'),
            'gender' => $this->request->getPost('gender'),
            'direktorat' => $this->request->getPost('direktorat'),
            'division' => $this->request->getPost('division'),
            'subsidiaries' => $this->request->getPost('subsidiaries'),
            'job' => $this->request->getPost('job'),
        ];

        $password = $this->generatePassword();
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        if ($this->mentorModel->insert($data)) {
            $userData = [
                'nomor' => $data['nipg'],
                'username' => $data['nipg'],
                'password' => $passwordHash,
                'level' => 'mentor',
                'aktif' => 'Y'
            ];

            $this->userModel->insert($userData);
            return redirect()->to('/registrasi/registrasi_mentor')->with('success', 'Pendaftaran berhasil. Email dengan informasi akun telah dikirim.');
        }

        return redirect()->to('/registrasi/registrasi_mentor')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    public function prosesRegistrasiPeserta()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tipe' => 'required',
            'nama' => 'required|trim',
            'email' => 'required|valid_email|trim',
            'notelp' => 'required|numeric',
            'alamat' => 'required|trim',
            'jk' => 'required',
            'tgl_lahir' => 'required',
            'strata' => 'required',
            'jurusan' => 'required|trim',
            'prodi' => 'required|trim',
            'instansi' => 'required|trim',
            'nik' => 'required|numeric',
            'tanggal1' => 'required',
            'tanggal2' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            log_message('debug', 'Validation failed: ' . json_encode($validation->getErrors()));
            session()->setFlashdata('status', 'fail');
            return redirect()->to('/registrasi')->withInput();
        }

        $nik = $this->request->getPost('nik');
        $tipe = $this->request->getPost('tipe');
        $email = $this->request->getPost('email');


        // Cek apakah nik sudah pernah mendaftar untuk tipe program yang sama
        $existingRegistration = $this->pesertaModel
            ->where('email', $email)
            ->where('tipe', $tipe)
            ->where('status !=', 'reject')  // Filter tambahan untuk memastikan status bukan 'reject'
            ->first();


        if ($existingRegistration) {
            session()->setFlashdata('status', 'fail');
            session()->setFlashdata('error_message', 'Anda sudah pernah mendaftar untuk tipe program ini. Silakan pilih tipe program lain.');
            return redirect()->to('/registrasi')->withInput();
        }

        // Jika nik belum pernah mendaftar untuk tipe ini, lanjutkan proses registrasi
        $files = ['surat_permohonan', 'proposal_magang', 'cv', 'fc_ktp', 'foto'];
        $uploads = [];
        $nama = $this->request->getPost('nama');
        $instansi = $this->request->getPost('instansi');
        $tipe = $this->request->getPost('tipe');

        $tanggal = date('Ymd');

        foreach ($files as $file) {
            $uploadedFile = $this->request->getFile($file);
            if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
                $newName = $this->createRenameFile($file, $nama, $tipe, $nik, $instansi, $tanggal);
                $uploadedFile->move(FCPATH . 'uploads', $newName);
                $uploads[$file] = $newName;
                log_message('debug', "File $file uploaded successfully.");
            } else {
                log_message('debug', "File $file failed to upload: " . $uploadedFile->getErrorString());
                session()->setFlashdata('status', 'fail');
                return redirect()->to('/registrasi')->with('error', $uploadedFile->getErrorString());
            }
        }

        $tanggal1 = new \DateTime($this->request->getPost('tanggal1'));
        $tanggal2 = new \DateTime($this->request->getPost('tanggal2'));
        $interval = $tanggal1->diff($tanggal2);
        $lamaPkl = ($interval->days <= 30) ? 1 : ceil($interval->days / 30);

        $tglRegis = date('Ymd');
        $password = $this->generatePassword();

        $lastPrimaryKey = $this->pesertaModel->selectMax('id_register')->first();
        $newPrimaryKey = isset($lastPrimaryKey['id_register']) ? $lastPrimaryKey['id_register'] + 1 : 1;
        $timeline = 'Review Berkas Awal';
        $data = [
            'tipe' => $tipe,
            'nomor' => $nik,
            'nama' => $nama,
            'email' => $this->request->getPost('email'),
            'notelp' => $this->request->getPost('notelp'),
            'alamat' => $this->request->getPost('alamat'),
            'jk' => $this->request->getPost('jk'),
            'tgl_lahir' => $this->request->getPost('tgl_lahir'),
            'strata' => $this->request->getPost('strata'),
            'jurusan' => $this->request->getPost('jurusan'),
            'prodi' => $this->request->getPost('prodi'),
            'instansi' => $instansi,
            'tanggal1' => $this->request->getPost('tanggal1'),
            'tanggal2' => $this->request->getPost('tanggal2'),
            'status' => 'Waiting',
            'tgl_regis' => $tglRegis,
            'lama_pkl' => $lamaPkl,
            'surat_permohonan' => $uploads['surat_permohonan'],
            'proposal_magang' => $uploads['proposal_magang'],
            'cv' => $uploads['cv'],
            'marksheet' => null,
            'fc_ktp' => $uploads['fc_ktp'],
            'foto' => $uploads['foto'],
            'timeline' => $timeline
        ];

        if ($this->pesertaModel->insert($data)) {
            
            // Kirim email ke peserta
            session()->setFlashdata('status', 'success');
            $email = \Config\Services::email();

            $email->setFrom('mdndfzn@gmail.com', 'PGN GAS Admin Magang Program');
            $email->setTo($this->request->getPost('email'));
            $email->setSubject("PGN {$tipe} - Pendaftaran Sedang Dievaluasi");
            $message = $this->isiEmailModel->getIsiEmailById(1);

            $email->setMessage("
            <p>Yth. $nama</p>
            <br>
            <p>Terima kasih atas pendaftaran Anda dalam program <b>{$tipe}</b>.</p>
            <p>Berkas Anda sedang dalam proses evaluasi, dan kami akan segera menginformasikan hasilnya.</p>
            <p>Mohon menunggu email pemberitahuan lebih lanjut.</p>
            <br>
            <p>Regards</p>
            <p>HCM Division</p>
            <p>PT Perusahaan Gas Negara Tbk</p>

        ");

            if ($email->send()) {
                log_message('info', 'Email berhasil dikirim ke ' . $this->request->getPost('email'));
            } else {
                log_message('error', 'Gagal mengirim email ke ' . $this->request->getPost('email'));
                log_message('error', $email->printDebugger(['headers']));
            }
            session()->setFlashdata('status', 'success');
            session()->setFlashdata('email', $this->request->getPost('email'));
            return redirect()->to('/registrasi');
        } else {
            session()->setFlashdata('status', 'fail');
            return redirect()->to('/registrasi');
        }
    }

    public function cekEmail()
    {
        // Mendapatkan data yang dikirim via AJAX
        $data = $this->request->getJSON();
        $tipe = $data->tipe;
        $email = $data->email;

        // Cek apakah sudah ada peserta dengan email dan tipe yang sama
        $existingRegistration = $this->pesertaModel
            ->where('email', $email)
            ->where('tipe', $tipe)
            ->where('status !=', 'reject')
            ->first();

        if ($existingRegistration) {
            // Jika sudah ada, kirimkan status fail
            return $this->response->setJSON(['status' => 'fail']);
        } else {
            // Jika belum ada, kirimkan status success
            return $this->response->setJSON(['status' => 'success']);
        }
    }
    private function createRenameFile($type, $name, $tipe, $nim, $instansi, $date)
    {
        return strtolower($type . '_' . str_replace(' ', '_', $name) . '_' . $tipe . '_' . $nim . '_' . $instansi . '_' . $date . '.' . pathinfo($_FILES[$type]['name'], PATHINFO_EXTENSION));
    }

    private function generatePassword()
    {
        return bin2hex(random_bytes(4));
    }
}
