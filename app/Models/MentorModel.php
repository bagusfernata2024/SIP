<?php

namespace App\Models;

use CodeIgniter\Model;

class MentorModel extends Model
{
    protected $table = 'mentor';
    protected $primaryKey = 'id_mentor';
    protected $allowedFields = ['id_mentor', 'nama', 'nipg', 'posisi', 'direktorat', 'division', 'subsidiaries', 'email', 'gender', 'job'];

    public function getData()
    {
        return $this->findAll();
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

    // Fungsi untuk mendapatkan daftar mentor beserta jumlah peserta yang dibimbing
    public function getMentorWithParticipants($page = 1, $perPage = 10)
    {
        return $this->db->table('mentor')
            ->select('mentor.nama, COUNT(anak_magang.id_mentor) as jumlah_peserta')
            ->join('anak_magang', 'mentor.id_mentor = anak_magang.id_mentor', 'left')
            ->groupBy('mentor.id_mentor')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()->getResultArray();
    }

    public function countAllMentors()
    {
        return $this->db->table('mentor')
            ->join('anak_magang', 'mentor.id_mentor = anak_magang.id_mentor', 'left')
            ->select('mentor.id_mentor as mentor_id')  // Alias untuk kolom id_mentor
            ->groupBy('mentor.id_mentor')
            ->countAllResults();
    }

    public function getMentorByEmail($email)
    {
        return $this->where('LOWER(email)', strtolower($email))->findAll();
    }


    // Fungsi untuk mencari mentor berdasarkan query
    public function searchMentor($query)
    {
        return $this->table('mentor')
            ->like('nama_mentor', $query)  // Mencari nama mentor yang mengandung query
            ->findAll();
    }

    // Dalam MentorModel.php


    public function countAll()
    {
        return $this->db->table('mentor')
            ->select('mentor.id_mentor as mentor_id, anak_magang.id_mentor as anak_magang_id') // Memberikan alias untuk menghindari duplikat kolom
            ->join('anak_magang', 'mentor.id_mentor = anak_magang.id_mentor', 'left')
            ->groupBy('mentor.id_mentor')
            ->countAllResults();
    }




}
