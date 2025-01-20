<?php
namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    protected $allowedFields = ['id_nilai', 'id_magang', 'ketepatan_waktu', 'sikap_kerja', 'tanggung_jawab', 'kehadiran', 'kemampuan_kerja', 'keterampilan_kerja', 'kualitas_hasil', 'kemampuan_komunikasi', 'kerjasama', 'kerajinan', 'percaya_diri', 'mematuhi_aturan', 'penampilan', 'tgl_input', 'perilaku']; // Pastikan ini sesuai dengan field tabel


    public function getNilaiByMentor($user_nomor)
    {
        return $this->db->table($this->table)
            ->select('mentor.nama AS nama_mentor, mentor.nipg AS nipg, mentor.subsidiaries, nilai.*, anak_magang.*, registrasi.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->get()
            ->getResult();
    }

    public function getNilaiByIdMentor($id_mentor)
    {
        return $this->db->table($this->table)
            ->select('mentor.nama AS nama_mentor, mentor.nipg AS nipg, mentor.subsidiaries, nilai.*, registrasi.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('mentor.id_mentor', $id_mentor)
            ->get()
            ->getResult();
    }

    public function insertProfileNilai($data)
    {
        return $this->insert($data);
    }

    public function updateNilai($data, $id_magang)
    {
        return $this->db->table($this->table)
            ->where('id_magang', $id_magang)
            ->update($data);
    }

    public function getNilaiByMentorCountNotYetFill($user_nomor)
    {
        return $this->db->table($this->table)
            ->select('registrasi.nama, registrasi.instansi, registrasi.email, registrasi.nik, nilai.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->where('nilai.perilaku IS NULL', null, false)
            ->countAllResults();
    }

    public function getIdMagang($user_nomor)
    {
        $id_register = $this->db->table('registrasi')
            ->select('id_register')
            ->where('nomor', $user_nomor)
            ->get()
            ->getRow();

        if ($id_register) {
            $id_magang = $this->db->table('anak_magang')
                ->select('id_magang')
                ->where('id_register', $id_register->id_register)
                ->get()
                ->getRow();

            return $id_magang ? $id_magang->id_magang : null;
        }

        return null;
    }

    public function getNilaiByPeserta($id_magang)
    {
        return $this->db->table($this->table)
            ->select('mentor.nama AS nama_mentor, mentor.nipg AS nipg, mentor.subsidiaries, registrasi.*, nilai.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('nilai.id_magang', $id_magang)
            ->get()
            ->getResult();
    }
}
