<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailRegisModel extends Model
{
    protected $table = 'detailregis';
    protected $primaryKey = 'iddetail';
    protected $allowedFields = ['iddetail', 'id_register', 'nipg', 'approved', 'email_kasat']; // Pastikan ini sesuai dengan field tabel


    public function getData()
    {
        return $this->findAll();
    }

    public function getDetailWithMentor($id)
    {
        return $this->db->table($this->table . ' dr')
            ->select('dr.*, m.*')
            ->join('mentor m', 'dr.nipg = m.nipg', 'left')
            ->where('dr.id_register', $id)
            ->get()
            ->getRowArray();
    }

    public function insertDetailRegis($data)
    {
        return $this->insert($data);
    }

    public function getDataByRegisterId($id_register)
    {
        return $this->where('id_register', $id_register)->first();
    }

    // Di dalam DetailRegisModel
    public function countMentorChildren($nipg)
    {
        return $this->where('nipg', $nipg)->countAllResults();  // Menghitung berapa banyak anak bimbingan per NIPG
    }
}
