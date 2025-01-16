<?php
namespace App\Models;

use CodeIgniter\Model;

class DetailRegisModel extends Model
{
    protected $table = 'detailregis';
    protected $primaryKey = 'iddetail';

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
}
