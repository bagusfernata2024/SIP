<?php
namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_lap';
    protected $allowedFields = ['id_lap', 'id_magang', 'nama_file', 'approved']; // Pastikan ini sesuai dengan field tabel


    public function insertLaporan($data)
    {
        return $this->insert($data);
    }

    public function checkLaporanByIdMagang($id_magang)
    {
        $result = $this->db->table($this->table)
            ->select('id_magang')
            ->where('id_magang', $id_magang)
            ->get()
            ->getRow();

        return $result ? "Ada" : null;
    }

    public function getLaporanByUser($user_nomor)
    {
        $id_register = $this->db->table('registrasi')
            ->select('id_register')
            ->where('nomor', $user_nomor)
            ->get()
            ->getRow();

        if (!$id_register) {
            return null;
        }

        $id_magang = $this->db->table('anak_magang')
            ->select('id_magang')
            ->where('id_register', $id_register->id_register)
            ->get()
            ->getRow();

        if (!$id_magang) {
            return null;
        }

        return $this->db->table($this->table)
            ->where('id_magang', $id_magang->id_magang)
            ->get()
            ->getRow();
    }

    public function getLaporanByMentorCountNotYetConfirm($user_nomor)
    {
        return $this->db->table($this->table)
            ->select('laporan.*')
            ->join('anak_magang', 'anak_magang.id_magang = laporan.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->where('laporan.approved', null)
            ->countAllResults();
    }

    public function updateStatusLaporan($id_magang, $status)
    {
        return $this->db->table($this->table)
            ->where('id_magang', $id_magang)
            ->update(['approved' => $status]);
    }
}
