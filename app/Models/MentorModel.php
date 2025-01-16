<?php
namespace App\Models;

use CodeIgniter\Model;

class MentorModel extends Model
{
    protected $table = 'mentor';
    protected $primaryKey = 'id_mentor';
    protected $allowedFields = ['nama', 'nipg', 'posisi', 'direktorat', 'division', 'subsidiaries', 'email', 'gender', 'job'];

    public function getData()
    {
        return $this->findAll();
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
