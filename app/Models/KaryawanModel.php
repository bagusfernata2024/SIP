<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'nipg';
    protected $allowedFields = ['nama', 'nipg', 'posisi', 'direktorat', 'division', 'subsidiaries', 'email', 'gender', 'job'];


    public function getDataByNipg($nipg)
    {
        return $this->where('nipg', $nipg)->first();  // Ambil data berdasarkan nipg
    }
    public function getData()
    {
        return $this->where('band <=', 19)->findAll();
    }


    public function getDataDesc()
    {
        return $this->orderBy('id_mentor', 'DESC') // Ganti 'id_mentor' dengan kolom yang relevan
            ->findAll();
    }


    public function countMentors()
    {
        return $this->countAll();
    }

    public function insertMentor($data)
    {
        return $this->insert($data);
    }

    public function getMentorByNipg($nipg)
    {
        return $this->where('nipg', $nipg)->first();
    }

    public function getMentorByIdMentorOne($id_mentor)
    {
        return $this->where('id_mentor', $id_mentor)->first();
    }

    public function getMentorByIdMentor($id_mentor)
    {
        return $this->db->table($this->table)
            ->select('mentor.*, registrasi.nomor, registrasi.nama AS nama_peserta, registrasi.instansi, registrasi.tanggal1, registrasi.tanggal2, detailregis.*, anak_magang.*')
            ->join('detailregis', 'detailregis.nipg = mentor.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->join('anak_magang', 'anak_magang.id_register = registrasi.id_register')
            ->where('mentor.id_mentor', $id_mentor)
            ->get()
            ->getResult();
    }
}
