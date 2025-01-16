<?php
namespace App\Models;

use CodeIgniter\Model;

class DaftarMinatModel extends Model
{
    protected $table = 'daftar_minat';
    protected $primaryKey = 'Id';

    public function getData()
    {
        return $this->findAll();
    }

    public function countDaftarMinat()
    {
        return $this->countAll();
    }
}
