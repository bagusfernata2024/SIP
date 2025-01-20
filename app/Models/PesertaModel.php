<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id_register';
    protected $allowedFields = ['tipe', 'nomor', 'nama', 'email', 'notelp', 'alamat', 'jk', 'tgl_lahir', 'strata', 'jurusan', 'prodi', 'instansi', 'lama_pkl', 'surat_permohonan', 'proposal_magang', 'cv', 'marksheet', 'tanggal1', 'tanggal2', 'status', 'tgl_regis', 'minat', 'nik', 'fc_ktp', 'tipe_magang', 'email_ap', 'foto'];

    public function getData()
    {
        return $this->findAll();
    }

    public function insertPeserta($data)
    {
        return $this->insert($data);
    }

    public function updateTglPerpanjangan($id_magang, $data)
    {
        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->update($data);
    }

    

    public function getPesertaByMentor($user_nomor)
    {
        return $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->get()
            ->getResult();
    }

    public function getDetailAbsenPesertaByMentor($user_nomor, $id_magang, $start_date = null, $end_date = null)
    {
        $builder = $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*, absen.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->join('absen', 'absen.id_magang = anak_magang.id_magang')
            ->where('detailregis.nipg', $user_nomor)
            ->where('anak_magang.id_magang', $id_magang);

        if ($start_date && $end_date) {
            $builder->where('tgl >=', $start_date)->where('tgl <=', $end_date);
        }

        return $builder->get()->getResult();
    }

    public function getDetailAbsenPeserta($id_magang, $start_date = null, $end_date = null)
    {
        $builder = $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*, absen.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->join('absen', 'absen.id_magang = anak_magang.id_magang')
            ->where('anak_magang.id_magang', $id_magang);

        if ($start_date && $end_date) {
            $builder->where('tgl >=', $start_date)->where('tgl <=', $end_date);
        }

        return $builder->get()->getResult();
    }

    public function getNilaiPeserta($id_magang)
    {
        return $this->db->table('nilai')
            ->select('registrasi.*, anak_magang.*, mentor.nama AS nama_mentor, mentor.nipg, mentor.subsidiaries, nilai.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('nilai.id_magang', $id_magang)
            ->get()
            ->getResult();
    }

    public function getLaporanPeserta($id_magang)
    {
        return $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('anak_magang.id_magang', $id_magang)
            ->get()
            ->getRow();
    }

    public function getPesertaByIdMagangIdMentor($id_magang, $id_mentor)
    {
        return $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $id_mentor)
            ->where('anak_magang.id_magang', $id_magang)
            ->get()
            ->getResult();
    }

    public function getTotalAnakBimbingan($id_mentor)
    {
        return $this->db->table('anak_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $id_mentor)
            ->countAllResults();
    }

    public function getTotalAnakBimbinganAktif($id_mentor)
    {
        return $this->db->table('anak_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->join('nilai', 'nilai.id_magang = anak_magang.id_magang')
            ->where('detailregis.nipg', $id_mentor)
            ->where('nilai.id_magang IS NULL', null, false)
            ->countAllResults();
    }

    public function getTotalAnakBimbinganTidakAktif($id_mentor)
    {
        return $this->db->table('anak_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->join('nilai', 'nilai.id_magang = anak_magang.id_magang')
            ->where('detailregis.nipg', $id_mentor)
            ->where('nilai.id_magang IS NOT NULL', null, false)
            ->countAllResults();
    }

    public function getPesertaMagang()
    {
        return $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->orderBy('registrasi.tgl_regis', 'DESC')
            ->get()
            ->getResult();
    }

    public function getDetailPeserta($id_magang)
    {
        return $this->db->table('anak_magang')
            ->select('registrasi.*, anak_magang.*, mentor.nama AS nama_mentor, mentor.email AS email_mentor, mentor.posisi, mentor.direktorat, mentor.division, mentor.subsidiaries, mentor.job, mentor.nipg')
            ->join('registrasi', 'registrasi.id_register = anak_magang.id_register')
            ->join('detailregis', 'detailregis.id_register = registrasi.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->where('anak_magang.id_magang', $id_magang)
            ->orderBy('registrasi.tgl_regis', 'DESC')
            ->get()
            ->getResult();
    }

    public function updateStatus($id_magang, $status)
    {
        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->update(['status' => $status]);
    }
}
