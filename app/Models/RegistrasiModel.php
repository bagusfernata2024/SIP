<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrasiModel extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id_register';

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
            ->select('surat_permohonan, proposal_magang, cv, marksheet, fc_ktp')
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

    
}
