<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $allowedFields = ['id_absen', 'id_magang','tgl', 'jam_masuk', 'jam_pulang', 'deskripsi', 'statuss', 'latitude_masuk', 'longtitude_masuk', 'latitude_keluar', 'longtitude_keluar', 'approved']; // Pastikan ini sesuai dengan field tabel

    public function getAbsensiByUserNomor($user_nomor)
    {
        return $this->db->table('absen')
            ->select('absen.*, anak_magang.id_magang, anak_magang.id_mentor, users.nomor')
            ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang', 'left')
            ->join('registrasi', 'registrasi.id_register = anak_magang.id_register', 'left')
            ->join('users', 'users.nomor = registrasi.nik', 'left')
            ->where('users.nomor', $user_nomor)
            ->orderBy('absen.tgl', 'DESC') // Mengurutkan berdasarkan tanggal terbaru
            ->get()
            ->getResultArray();
    }


    // public function getAbsensiByUserNomor($user_nomor)
    // {
    //     return $this->db->table('absen')
    //         ->select('absen.*, anak_magang.id_magang, anak_magang.id_mentor, users.nomor')
    //         ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang', 'left')
    //         ->join('registrasi', 'registrasi.id_register = anak_magang.id_register', 'left')
    //         ->join('users', 'users.nomor = registrasi.nik', 'left')
    //         ->where('users.nomor', $user_nomor)
    //         ->orderBy('absen.tgl', 'ASC')
    //         ->get()
    //         ->getResultArray();
    // }

    public function saveCheckIn($data)
    {
        $this->db->table('absen')->insert($data);
    }

    public function saveCheckOut($data)
    {
        log_message('debug', 'Data check-out yang diterima untuk disimpan: ' . print_r($data, true));

        // Insert data check-out ke tabel absensi
        return $this->db->table('absensi')->insert($data);
    }

    public function updateCheckOut($id_magang, $tgl, $data)
    {
        $this->db->table('absen')
            ->where('id_magang', $id_magang)
            ->where('tgl', $tgl)
            ->update($data);
    }


    // public function updateCheckOut($id_magang, $tgl, $data)
    // {
    //     $this->db->table('absen')
    //         ->where('id_magang', $id_magang)
    //         ->where('tgl', $tgl)
    //         ->update($data);
    // }

    public function getTodayAbsence($id_magang, $tgl)
    {
        return $this->db->table('absen')
            ->where('id_magang', $id_magang)
            ->where('tgl', $tgl)
            ->get()
            ->getRowArray();
    }

    public function getIdMagang($user_nomor)
    {
        $id_register = $this->db->table('registrasi')
            ->select('id_register')
            ->where('nomor', $user_nomor)
            ->get()
            ->getRow();

        if ($id_register) {
            $id_register = $id_register->id_register;

            $id_magang = $this->db->table('anak_magang')
                ->select('id_magang')
                ->where('id_register', $id_register)
                ->get()
                ->getRow();

            return $id_magang ? $id_magang->id_magang : null;
        }

        return null;
    }

    public function getAbsenByMentor($user_nomor)
    {
        return $this->db->table('absen')
            ->select('absen.*, registrasi.nama, registrasi.instansi, registrasi.email, registrasi.nik')
            ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->get()
            ->getResult();
    }

    public function getAbsenByMentorCountNotYetConfirm($user_nomor)
    {
        return $this->db->table('absen')
            ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('detailregis.nipg', $user_nomor)
            ->where('absen.approved', null)
            ->countAllResults();
    }

    public function getAbsenByPesertaCountNotYetConfirm($user_nomor)
    {
        return $this->db->table('absen')
            ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('registrasi.nomor', $user_nomor)
            ->where('absen.approved', null)
            ->countAllResults();
    }

    public function updateStatusAbsensi($id_magang, $tgl, $status)
    {
        $builder = $this->db->table('absen');
        $builder->where('id_magang', $id_magang);
        $builder->where('tgl', $tgl);
        $builder->update(['approved' => $status]);
    }

    public function updateDeskripsi($id_absen, $data)
    {
        return $this->update($id_absen, $data); // Fungsi update
    }

    public function hasCheckedInToday($id_magang, $tgl)
    {
        return $this->db->table('absen')
            ->where('id_magang', $id_magang)
            ->where('tgl', $tgl)
            ->countAllResults() > 0;
    }

    public function getLastIdAbsen()
    {
        $query = $this->db->table('absen')
            ->selectMax('id_absen', 'last_id')
            ->get();

        $result = $query->getRow();
        return $result ? $result->last_id : 0; // Jika tabel kosong, kembalikan 0
    }

    public function tambahAbsen()
    {
        // Dapatkan ID terakhir
        $lastId = $this->absensiModel->getLastIdAbsen();

        // Hitung ID baru
        $newId = $lastId + 1;

        // Data absensi baru
        $data = [
            'id_absen' => $newId,
            'id_magang' => 916,
            'tgl' => date('Y-m-d'),
            'jam_masuk' => '08:00:00',
            'jam_pulang' => NULL,
            'statuss' => 'Hadir',
        ];

        // Simpan data ke tabel
        $this->absensiModel->insert($data);

        return redirect()->to('/absensi');
    }
}
