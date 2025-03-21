<?php
namespace App\Models;

use CodeIgniter\Model;

class IsiEmailModel extends Model
{
    protected $table = 'isi_email';
    protected $primaryKey = 'id';

    public function getData()
    {
        return $this->findAll();
    }

    public function getIsiEmailById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function countDaftarMinat()
    {
        return $this->countAll();
    }
}
