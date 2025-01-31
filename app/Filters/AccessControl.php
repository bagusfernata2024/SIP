<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class AccessControl implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Mendapatkan informasi level pengguna yang sedang login
        $session = session();
        $userLevel = $session->get('level'); // Misalnya 'user', 'mentor', atau 'admin'

        // Menentukan akses berdasarkan level pengguna
        if (in_array($userLevel, $arguments)) {
            // User memiliki akses
            return;
        }

        // Jika pengguna tidak memiliki akses, redirect ke halaman tidak diizinkan
        return redirect()->to('/no-access');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada tindakan yang diperlukan setelah request diproses
    }
}
