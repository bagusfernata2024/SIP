<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    protected $allowedFields = ['id_nilai', 'id_magang', 'ketepatan_waktu', 'sikap_kerja', 'tanggung_jawab', 'kehadiran', 'kemampuan_kerja', 'keterampilan_kerja', 'kualitas_hasil', 'kemampuan_komunikasi', 'kerjasama', 'kerajinan', 'percaya_diri', 'mematuhi_aturan', 'penampilan', 'tgl_input', 'perilaku', 'integritas', 'predikat', 'rata']; // Pastikan ini sesuai dengan field tabel


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
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));
        log_message('debug', "Memulai proses update untuk id_magang: $id_magang");

        // Pastikan record ada di database
        $existingRecord = $this->where('id_magang', $id_magang)->first();

        if (!$existingRecord) {
            log_message('error', "Record dengan id_magang $id_magang tidak ditemukan.");
            return false; // Jika tidak ada, return false
        }

        // Lakukan update berdasarkan id_magang
        return $this->db->table($this->table)
            ->where('id_magang', $id_magang)
            ->update($data);
    }


    // public function updateNilai($id_magang, $data)
    // {
    //     // Pastikan id_magang ada
    //     $record = $this->find($id_magang);
    //     if (!$record) {
    //         log_message('error', 'Record dengan id_magang ' . $id_magang . ' tidak ditemukan.');
    //         return false;
    //     }

    //     // Debug data sebelum update
    //     log_message('debug', 'Data yang akan diperbarui untuk id_magang ' . $id_magang . ': ' . json_encode($data));

    //     // Update data
    //     if ($this->update($id_magang, $data)) {
    //         log_message('info', 'Update berhasil untuk id_magang ' . $id_magang);
    //         return true;
    //     } else {
    //         log_message('error', 'Gagal update untuk id_magang ' . $id_magang . '. Error: ' . json_encode($this->errors()));
    //         return false;
    //     }
    // }



    // public function updateNilai($data, $id_magang)
    // {
    //     return $this->db->table($this->table)
    //         ->where('id_magang', $id_magang)
    //         ->update($data);
    // }

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
            ->where('id_register', $user_nomor)
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

    public function getNilaiByIdMagang($id_magang)
    {
        return $this->where('id_magang', $id_magang)->first();
    }

    public function getNilaiByIdMagangPure($id_magang)
    {
        return $this->select('ketepatan_waktu, sikap_kerja, tanggung_jawab, kehadiran, kemampuan_kerja, keterampilan_kerja, kualitas_hasil, kemampuan_komunikasi, kerjasama, kerajinan, percaya_diri, mematuhi_aturan, penampilan, perilaku, integritas, predikat, rata') // Kolom yang ingin diambil
            ->where('id_magang', $id_magang)
            ->first();
    }

    public function getNilaiByIdMagangFull($id_magang)
    {
        return $this->db->table($this->table)
            ->select('mentor.nama AS nama_mentor, mentor.nipg AS nipg, mentor.subsidiaries, nilai.*, anak_magang.*, registrasi.*')
            ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
            ->where('anak_magang.id_magang', $id_magang)  // Mencari berdasarkan id_magang
            ->get()
            ->getResult();
    }



    public function getNilaiByPeserta($id_magang)
    {
        return $this->db->table($this->table)
            ->select('nilai.*')  // Pilih semua kolom yang dibutuhkan
            ->where('id_magang', $id_magang)
            ->get()
            ->getRowArray(); // Gunakan getRowArray() jika hanya mengambil satu baris data
    }


    // public function getNilaiByPeserta($id_magang)
    // {
    //     return $this->db->table($this->table)
    //         ->select('mentor.nama AS nama_mentor, mentor.nipg AS nipg, mentor.subsidiaries, registrasi.*, nilai.*')
    //         ->join('anak_magang', 'anak_magang.id_magang = nilai.id_magang')
    //         ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
    //         ->join('mentor', 'mentor.nipg = detailregis.nipg')
    //         ->join('registrasi', 'registrasi.id_register = detailregis.id_register')
    //         ->where('nilai.id_magang', $id_magang)
    //         ->get()
    //         ->getResult();
    // }
}
