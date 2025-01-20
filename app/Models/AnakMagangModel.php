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

    public function getLaporanAkhirByMentor($user_nomor)
    {
        return $this->db->table('anak_magang')
            ->select('anak_magang.*')
            ->join('detailregis', 'detailregis.id_register = anak_magang.id_register')
            ->join('mentor', 'mentor.nipg = detailregis.nipg')
            ->where('mentor.nipg', $user_nomor)
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
}
