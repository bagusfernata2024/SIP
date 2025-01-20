<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
    }

    public function index()
    {

        if ($this->session->get('admin_logged_in')) {
            redirect('dashboard');
        }

        return view('web_templates/header') .
            view('templates/login_peserta', ['session' => $this->session]) .
            view('web_templates/footer');
    }

    public function prosesLoginPeserta()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->userModel->getUserWithUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
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
            ]);

            return redirect()->to('/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login');
        }
    }

    public function admin()
    {
        if ($this->session->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
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
        return redirect()->to('/login/mentor');
    }

    public function logoutPeserta()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
