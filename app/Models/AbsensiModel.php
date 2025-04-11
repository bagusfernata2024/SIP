<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $allowedFields = ['id_absen', 'id_magang', 'tgl', 'jam_masuk', 'jam_pulang', 'deskripsi', 'statuss', 'latitude_masuk', 'longtitude_masuk', 'latitude_keluar', 'longtitude_keluar', 'approved']; // Pastikan ini sesuai dengan field tabel

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

    // public function saveCheckIn($data)
    // {
    //     $this->db->table('absen')->insert($data);
    // }



    public function saveCheckIn($data)
    {
        log_message('debug', 'Data untuk Check-In: ' . json_encode($data));
        if ($this->insert($data)) {
            log_message('debug', 'Data Check-In berhasil disimpan.');
            return true;
        } else {
            log_message('error', 'Error saat menyimpan Check-In: ' . json_encode($this->errors()));
            return false;
        }
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

    // public function getTodayAbsence($id_magang, $tgl)
    // {
    //     return $this->db->table('absen')
    //         ->where('id_magang', $id_magang)
    //         ->where('tgl', $tgl)
    //         ->get()
    //         ->getRowArray();
    // }

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
        ->where('absen.deskripsi IS NOT NULL')  // menambahkan filter untuk deskripsi yang tidak null
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
            ->where('absen.approved', null) // Belum dikonfirmasi
            ->where('absen.jam_masuk IS NOT NULL') // Kondisi untuk jam_masuk yang tidak null
            ->countAllResults();
    }


    public function getAbsenByPesertaCountNotYetConfirm($user_nomor)
    {
        return $this->db->table('absen')
            ->join('anak_magang', 'anak_magang.id_magang = absen.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('registrasi.id_register', $user_nomor)
            ->where('absen.approved', null) // Belum dikonfirmasi
            ->where('absen.jam_masuk IS NOT NULL') // Kondisi untuk jam_masuk yang tidak null
            ->countAllResults();
    }


    public function updateStatusAbsensi($id_magang, $tgl, $status, $statuss)
    {
        $builder = $this->db->table('absen');
        $builder->where('id_magang', $id_magang);
        $builder->where('tgl', $tgl);
        $builder->update(['approved' => $status]);
        $builder->where('id_magang', $id_magang);
        $builder->where('tgl', $tgl);
        $builder->update(['statuss' => $statuss]);
    }
    public function updateDeskripsi($id_absen, $data)
    {
        return $this->db->table($this->table)
            ->where('id_absen', $id_absen)
            ->update($data);
    }

    public function getAbsensiByMagang($id_magang)
    {
        return $this->where('id_magang', $id_magang)
            ->orderBy('tgl', 'ASC') // Mengurutkan berdasarkan tanggal descending
            ->findAll();
    }

    public function getTodayAbsensi($id_magang, $date)
    {
        return $this->where('id_magang', $id_magang)
            ->where('tgl', $date)
            ->get()
            ->getRowArray(); // Ambil baris pertama sebagai array
    }

    public function hasCheckedInToday($id_magang, $tgl)
    {
        return $this->where('id_magang', $id_magang)->where('tgl', $tgl)->countAllResults() > 0;
    }

    public function getTodayAbsence($id_magang, $tgl)
    {
        return $this->db->table($this->table)
            ->where('id_magang', $id_magang)
            ->where('tgl', $tgl)
            ->get()
            ->getRowArray();
    }

    public function updateAbsensi($id_absen, $data)
    {
        return $this->db->table($this->table)
            ->where('id_absen', $id_absen)
            ->update($data);
    }



    // public function updateAbsensi($id_magang, $tgl, $data)
    // {
    //     // Update record absensi berdasarkan id_magang dan tanggal
    //     return $this->db->table('absen')
    //         ->where('id_magang', $id_magang)
    //         ->where('tgl', $tgl)
    //         ->update($data);
    // }

    // public function getTodayAbsence($id_magang, $tgl)
    // {
    //     // Ambil record absensi berdasarkan id_magang dan tanggal
    //     return $this->db->table('absen')
    //         ->where('id_magang', $id_magang)
    //         ->where('tgl', $tgl)
    //         ->get()
    //         ->getRowArray();
    // }


    // public function getTodayAbsence($id_magang, $tgl)
    // {
    //     return $this->where('id_magang', $id_magang)->where('tgl', $tgl)->first();
    // }


    // public function hasCheckedInToday($id_magang, $tgl)
    // {
    //     return $this->db->table('absen')
    //         ->where('id_magang', $id_magang)
    //         ->where('tgl', $tgl)
    //         ->countAllResults() > 0;
    // }

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
