<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nomor', 'username', 'pasword', 'level', 'foto', 'aktif', 'last_login'];

    public function insertUser($data)
    {
        return $this->insert($data);
    }

    public function getAdmin($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserWithRegistrasi($username)
    {
        return $this->db->table($this->table)
            ->select('users.*, registrasi.*')
            ->join('registrasi', 'users.nomor = registrasi.nik')
            ->where('users.username', $username)
            ->get()
            ->getRowArray();
    }

    public function getUserWithMentor($username)
    {
        return $this->db->table($this->table)
            ->select('users.*, mentor.*')
            ->join('mentor', 'users.nomor = mentor.nipg')
            ->where('users.username', $username)
            ->get()
            ->getRowArray();
    }
}
