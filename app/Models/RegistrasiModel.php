<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrasiModel extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id_register';
    protected $allowedFields = ['tipe', 'nomor', 'nama', 'email', 'notelp', 'alamat', 'jk', 'tgl_lahir', 'strata', 'jurusan', 'prodi', 'instansi', 'lama_pkl', 'surat_permohonan', 'proposal_magang', 'cv', 'marksheet', 'tanggal1', 'tanggal2', 'status', 'tgl_regis', 'minat', 'nik', 'fc_ktp', 'tipe_magang', 'email_ap', 'foto', 'timeline', 'no_sertif'];


    public function getTimeline($id)
    {
        // Mengambil data timeline dari tabel registrasi berdasarkan id_register
        $result = $this->db->table('registrasi')
            ->select('timeline')
            ->where('id_register', $id)
            ->get()
            ->getRowArray();

        // Jika data ada, decode JSON timeline dan kembalikan sebagai array
        if ($result && isset($result['timeline'])) {
            return json_decode($result['timeline'], true);
        }

        return []; // Jika tidak ada data, kembalikan array kosong
    }

    public function getTanggalMagang($idMagang)
    {
        return $this->select('tanggal1, tanggal2')
            ->where('id_magang', $idMagang)
            ->first();
    }

    public function getByStatus($status = null)
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy('tgl_regis', 'DESC');

        if (!is_null($status)) {
            $builder->where('status', $status);
        } else {
            $builder->where('status IS NULL');
        }

        return $builder->get()->getResultArray();
    }

    public function getData()
    {
        return $this->db->table($this->table)
            ->orderBy('tgl_regis', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getDetail($id)
    {
        return $this->db->table($this->table)
            ->where('id_register', $id)
            ->get()
            ->getRowArray();
    }

    public function updateStatus($id, $status)
    {
        $this->db->table($this->table)
            ->where('id_register', $id)
            ->update(['status' => $status]);
    }

    public function updateTimelineAcc($id, $status)
    {
        $this->db->table($this->table)
            ->where('id_register', $id)
            ->update(['timeline' => $status]);
    }

    public function updateStatusAccMentor($idRegister, $status)
    {
        $builder = $this->db->table('registrasi');
        $builder->where('id_register', $idRegister);
        $builder->update(['status' => $status]);

        // Cek apakah ada perubahan pada data dengan menggunakan getAffectedRows
        $affectedRows = $this->db->affectedRows();

        // Debug query yang dijalankan
        log_message('debug', 'Query yang dijalankan: ' . $this->db->getLastQuery());

        // Memeriksa apakah ada baris yang terpengaruh
        if ($affectedRows > 0) {
            log_message('debug', 'Timeline berhasil diperbarui untuk ID Register: ' . $idRegister);
            return true; // Berhasil
        } else {
            log_message('error', 'Gagal memperbarui timeline untuk ID Register: ' . $idRegister);
            return false; // Gagal
        }
    }

    public function updateTimelineAccMentor($idRegister, $status)
    {
        $builder = $this->db->table('registrasi');
        $builder->where('id_register', $idRegister);
        $builder->update(['timeline' => $status]);

        // Cek apakah ada perubahan pada data dengan menggunakan getAffectedRows
        $affectedRows = $this->db->affectedRows();

        // Debug query yang dijalankan
        log_message('debug', 'Query yang dijalankan: ' . $this->db->getLastQuery());

        // Memeriksa apakah ada baris yang terpengaruh
        if ($affectedRows > 0) {
            log_message('debug', 'Timeline berhasil diperbarui untuk ID Register: ' . $idRegister);
            return true; // Berhasil
        } else {
            log_message('error', 'Gagal memperbarui timeline untuk ID Register: ' . $idRegister);
            return false; // Gagal
        }
    }

    public function updateStatusInactive($id_register)
    {
        // Query untuk memperbarui status pada tabel registrasi
        return $this->db->table('registrasi')
            ->set('status', 'Inactive')
            ->where('id_register', $id_register)
            ->update();
    }


    public function getPesertaById($id)
    {
        return $this->db->table($this->table)
            ->where('id_register', $id)
            ->get()
            ->getRowArray();
    }

    public function countRegisters()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    public function getUserFiles($id)
    {
        $query = $this->db->table($this->table)
            ->select('surat_permohonan, proposal_magang, cv, fc_ktp')
            ->where('id_register', $id)
            ->get();

        return $query->getNumRows() > 0 ? $query->getRowArray() : false;
    }

    public function getUserDataDiri($id)
    {
        $query = $this->db->table($this->table)
            ->select('instansi, nama, nik')
            ->where('id_register', $id)
            ->get();

        return $query->getNumRows() > 0 ? $query->getRowArray() : false;
    }

    public function countPesertaByStatus($status)
    {
        return $this->db->table($this->table)
            ->where('status', $status)
            ->countAllResults();
    }

    public function getRegistrasiByNomor($nomor)
    {
        return $this->where('nomor', $nomor)->first();  // Mengambil data pertama yang sesuai dengan nomor
    }

    public function getRegistrasiById($id)
    {
        return $this->where('id_register', $id)->first();
    }

    // Ambil no_sertif terakhir
    public function getLastNoSertif()
    {
        // Mengambil nilai no_sertif terakhir, pastikan kita mengakses hasil pertama dari array
        $result = $this->selectMax('no_sertif')->first();
        return $result ? $result['no_sertif'] : 0; // Mengembalikan no_sertif terakhir atau 0 jika tidak ada
    }

    // Update no_sertif pada tabel registrasi
    public function updateNoSertif($id_register, $data)
    {
        return $this->where('id_register', $id_register)->set($data)->update();
    }
}
