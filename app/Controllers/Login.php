<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    protected $session;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
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

        return view('web_templates/header') .
            view('templates/login', ['session' => $this->session]) .
            view('web_templates/footer');
    }

    public function prosesLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil data user berdasarkan username
        $user = $this->userModel->getUserWithUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Set data sesi berdasarkan level user
            $sessionData = [
                'logged_in' => true,
                'id' => $user['id'],
                'nomor' => $user['nomor'],
                'username' => $user['username'],
                'level' => $user['level'],
                'nama' => $user['nama'],
            ];

            // Jika user adalah peserta
            if ($user['level'] === 'user') {
                // Ambil data tambahan dari tabel registrasi
                $registrasi = $this->db->table('registrasi')
                    ->select('foto')
                    ->where('id_register', $user['id_register'])
                    ->get()
                    ->getRowArray();

                $registrasi_tipe = $this->db->table('registrasi')
                    ->select('tipe')
                    ->where('id_register', $user['id_register'])
                    ->get()
                    ->getRowArray();

                $sessionData['user_logged_in'] = true;
                $sessionData['instansi'] = $user['instansi'];
                $sessionData['email'] = $user['email'];
                $sessionData['alamat'] = $user['alamat'];
                $sessionData['notelp'] = $user['notelp'];
                $sessionData['id_register'] = $user['id_register'];
                $sessionData['tipe'] = $registrasi_tipe['tipe'];
                $sessionData['foto'] = $registrasi['foto'] ?? 'default.png';

                $this->session->set($sessionData);
                return redirect()->to('/dashboard');
            }

            // Jika user adalah mentor
            if ($user['level'] === 'mentor') {
                $sessionData['mentor_logged_in'] = true;
                $sessionData['foto'] = 'default.jpg';
                $this->session->set($sessionData);
                return redirect()->to('/mentor/dashboard');
            }
        } else {
            // Jika username/password salah
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login');
        }
    }


    public function prosesLoginPeserta()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil data dari tabel user
        $admin = $this->userModel->getUserWithUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            // Ambil data dari tabel registrasi
            $registrasi = $this->db->table('registrasi')
                ->select('foto')
                ->where('id_register', $admin['id_register'])
                ->get()
                ->getRowArray();

            $registrasi_tipe = $this->db->table('registrasi')
                ->select('tipe')
                ->where('id_register', $admin['id_register'])
                ->get()
                ->getRowArray();

            dd($registrasi_tipe);
            // Set data sesi dengan data foto dari tabel registrasi
            $this->session->set([
                'peserta_logged_in' => true,
                'id' => $admin['id'],
                'nomor' => $admin['nomor'],
                'username' => $admin['username'],
                'level' => $admin['level'],
                'nama' => $admin['nama'],
                'instansi' => $admin['instansi'],
                'email' => $admin['email'],
                'alamat' => $admin['alamat'],
                'notelp' => $admin['notelp'],
                'id_register' => $admin['id_register'],
                'tipe' => $registrasi_tipe['tipe'],
                'foto' => $registrasi['foto'] ?? 'default.png', // Gunakan foto dari tabel registrasi atau default
            ]);
            return redirect()->to('/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login');
        }
    }

    public function admin()
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

        return view('web_templates/header_special') .
            view('templates/login_admin') .
            view('web_templates/footer_special');
    }

    public function prosesLoginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->userModel->getAdmin($username);

        if ($admin && password_verify($password, $admin['password'])) {
            $this->session->set([
                'admin_logged_in' => true,
                'id' => $admin['id'],
                'nomor' => $admin['nomor'],
                'username' => $admin['username'],
                'nama' => $admin['username'],
                'level' => $admin['level'],
            ]);

            return redirect()->to('/admin/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login/admin');
        }
    }

    public function mentor()
    {
        // if ($this->session->get('mentor_logged_in')) {
        //     return redirect()->to('/dashboard');
        // }

        return view('web_templates/header_special') .
            view('templates/login_mentor') .
            view('web_templates/footer_special');
    }

    public function prosesLoginMentor()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $mentor = $this->userModel->getUserWithMentor($username);

        if ($mentor && password_verify($password, $mentor['password'])) {
            $this->session->set([
                'mentor_logged_in' => true,
                'id' => $mentor['id'],
                'nomor' => $mentor['nomor'],
                'username' => $mentor['username'],
                'nama' => $mentor['nama'],
                'level' => $mentor['level'],
            ]);

            return redirect()->to('/mentor/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login/mentor');
        }
    }

    public function logoutAdmin()
    {
        $this->session->destroy();
        return redirect()->to('/login/admin');
    }

    public function logoutMentor()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    public function logoutPeserta()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
