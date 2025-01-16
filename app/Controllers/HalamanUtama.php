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
