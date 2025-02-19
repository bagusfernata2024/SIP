<?php

namespace App\Models;

use CodeIgniter\Model;

class AnakMagangModel extends Model
{
    protected $table = 'anak_magang';
    protected $primaryKey = 'id_magang';
    protected $allowedFields = ['id_magang', 'id_register', 'unit_kerja', 'tgl_mulai', 'tgl_selesai', 'tgl_perpanjangan', 'id_mentor', 'status', 'surat_persetujuan', 'surat_pernyataan', 'nota_dinas', 'bank', 'no_rekening', 'buku_rek', 'nama_penerima_bank', 'laporan_akhir', 'approved_laporan_akhir', 'bpp', 'suket']; // Pastikan ini sesuai dengan field tabel

    public function getData()
    {
        return $this->findAll();
    }

    public function countByStatus($status)
    {
        return $this->db->table($this->table)
            ->where('status', $status)
            ->countAllResults();
    }

    public function getTanggalMagang($idMagang)
    {
        return $this->select('tgl_mulai AS tanggal1, tgl_selesai AS tanggal')
            ->where('id_magang', $idMagang)
            ->first();
    }

    public function getIdMagangByRegister($id_register)
    {
        return $this->where('id_register', $id_register)->first()['id_magang'] ?? null;
    }

    public function getIdRegisterByIdMagang($id_magang)
    {
        // Query untuk mendapatkan id_register berdasarkan id_magang
        return $this->db->table('anak_magang')
            ->select('id_register')
            ->where('id_magang', $id_magang)
            ->get()
            ->getRow()->id_register ?? null;
    }


    public function updateStatusInactive($id_magang)
    {
        // Query untuk memperbarui status pada tabel anak_magang
        return $this->db->table('anak_magang')
            ->set('status', 'Selesai Magang')
            ->where('id_magang', $id_magang)
            ->update();
    }

    public function getAllPeserta()
    {
        return $this->db->table('anak_magang')->get()->getResult();
    }

    public function getPesertaByIdMagang($id_magang)
    {
        return $this->db->table('anak_magang')
            ->select('anak_magang.id_magang, registrasi.nomor, registrasi.nama, registrasi.instansi, registrasi.tanggal1, registrasi.tanggal2, anak_magang.status')
            ->join('registrasi', 'registrasi.id_register = anak_magang.id_register')
            ->where('anak_magang.id_magang', $id_magang)
            ->get()
            ->getRowArray(); // Mengembalikan satu baris sebagai array
    }

    public function getPesertaByIdMagangOne($id_magang)
    {
        return $this->where('id_magang', $id_magang)->first();
    }

    public function getPesertaMagangDesc()
    {
        return $this->db->table('registrasi')
            ->select('registrasi.nomor, registrasi.nama, registrasi.instansi, registrasi.tanggal1, registrasi.tanggal2, anak_magang.status, anak_magang.id_magang, registrasi.id_register') // Menambahkan id_register pada select
            ->join('anak_magang', 'registrasi.id_register = anak_magang.id_register') // Menghubungkan tabel registrasi dan anak_magang
            ->orderBy('anak_magang.id_magang', 'DESC') // Mengurutkan berdasarkan kolom id_magang secara descending
            ->get()
            ->getResultArray();
    }

    public function getPesertaMagang()
    {
        return $this->db->table('registrasi')
            ->select('registrasi.nomor, registrasi.nama, registrasi.instansi, registrasi.tanggal1, registrasi.tanggal2, anak_magang.status, anak_magang.id_magang')
            ->join('anak_magang', 'registrasi.id_register = anak_magang.id_register') // Perbaikan di sini
            ->get()
            ->getResultArray();
    }




    public function getIdMagang($id_register)
    {
        $id_register = $this->db->table('registrasi')
            ->select('id_register')
            ->where('id_register', $id_register)
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

    public function insertAnakMagang($data)
    {
        return $this->insert($data);
    }

    public function getBankEnumValues($table, $column)
    {
        $query = $this->db->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$column'");
        $row = $query->getRow();

        if ($row) {
            $enum_list = str_replace(["enum(", ")", "'"], '', $row->COLUMN_TYPE);
            return explode(',', $enum_list);
        }

        return [];
    }

    public function getDataAnakMagang($id_magang)
    {
        return $this->db->table('anak_magang')
            ->select('anak_magang.*, registrasi.*')
            ->join('registrasi', 'registrasi.id_register = anak_magang.id_register', 'left')
            ->where('anak_magang.id_magang', $id_magang)
            ->get()
            ->getRow();
    }

    public function updateProfile($id_magang, $data)
    {
        $id_register = $this->db->table('anak_magang')
            ->select('id_register')
            ->where('id_magang', $id_magang)
            ->get()
            ->getRow();

        if ($id_register) {
            return $this->db->table('registrasi')
                ->where('id_register', $id_register->id_register)
                ->update($data);
        }

        return false;
    }

    public function updateBankInfo($data)
    {
        if (isset($data['id_magang'])) {
            $this->db->table('anak_magang')
                ->where('id_magang', $data['id_magang'])
                ->update($data);

            unset($data['id_magang']);
        }

        return $this->db->table('anak_magang')->update($data);
    }

    public function updateTglPerpanjangan($id_magang, $data)
    {
        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->update($data);
    }

    public function updateLaporanAkhir($id_magang, $data)
    {
        if (empty($id_magang)) {
            return false;
        }

        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->update($data);
    }

    public function getLaporanByIdMagang($id_magang)
    {
        if (empty($id_magang)) {
            return null;
        }

        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->get()
            ->getRow();
    }

    public function getLaporanAkhirByMentor($id_register)
    {
        return $this->db->table('anak_magang')
            ->select('anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->where('mentor.nipg', $id_register)
            ->get()
            ->getResult();
    }

    public function updateStatusLaporanAkhir($idMagang, $status)
    {
        // Update status laporan akhir di database
        return $this->db->table('anak_magang')
            ->set('approved_laporan_akhir', $status)
            ->where('id_magang', $idMagang)
            ->update();
    }


    public function getById($id_magang)
    {
        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->get()
            ->getRow();
    }

    public function updateStatus($id_magang, $data)
    {
        return $this->db->table('anak_magang')
            ->where('id_magang', $id_magang)
            ->update($data);
    }

    public function updateRegistrasi($id_register, $data)
    {
        return $this->db->table('registrasi')
            ->where('id_register', $id_register)
            ->update($data);
    }

    public function getIdRegister($nomor)
    {
        return $this->db->table('registrasi')
            ->select('id_register')
            ->where('nomor', $nomor)
            ->get()
            ->getRowArray()['id_register'];
    }

    public function getBankInfo($id_magang)
    {
        return $this->where('id_magang', $id_magang)
            ->select('buku_rek')
            ->first();
    }

    public function getAnakMagangWithNama($id_magang)
    {
        // Pastikan hanya satu data yang diambil
        return $this->select('anak_magang.*, registrasi.nama')
            ->join('registrasi', 'registrasi.id_register = anak_magang.id_register')
            ->where('anak_magang.id_magang', $id_magang)
            ->first();
    }
}
