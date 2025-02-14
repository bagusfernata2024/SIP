<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\RegistrasiModel;
use App\Models\DaftarMinatModel;

class HalamanUtama extends BaseController
{
    protected $mentorModel;
    protected $registrasiModel;
    protected $daftarMinatModel;

    public function __construct()
    {
        $this->mentorModel = new MentorModel();
        $this->registrasiModel = new RegistrasiModel();
        $this->daftarMinatModel = new DaftarMinatModel();
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

        $data = [
            'mentor' => $this->mentorModel->getData(),
            'total_mentor' => $this->mentorModel->countMentors(),
            'total_register' => $this->registrasiModel->countRegisters(),
            'total_daftar_minat' => $this->daftarMinatModel->countDaftarMinat(),
        ];

        return view('web_templates/header') .
            view('halaman_utama', $data) .
            view('web_templates/footer');
    }
}
