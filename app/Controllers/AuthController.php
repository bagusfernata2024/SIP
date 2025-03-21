<?php

namespace App\Controllers;

use App\Models\ServicesModel;
use App\Models\PegawaiModel;
use App\Models\PresenceModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\I18n\Time;

class AuthController extends ResourceController
{
    protected $ServicesModel;
    protected $PegawaiModel;
    protected $PresenceModel;

    public function __construct()
    {
        $this->ServicesModel = new ServicesModel();
        $this->PegawaiModel = new PegawaiModel();
        $this->PresenceModel = new PresenceModel();
    }

    public function signIn()
    {
        $key = '682-!234abc';

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $device = $this->request->getPost('device');
        $access_menu = $this->request->getPost('access_menu');

        $username = $this->xorDecrypt($username, $key);
        $password = $this->xorDecrypt($password, $key);

        // Sanitasi username
        $username = str_replace(' ', '', $username);
        $username = str_replace('@pgn.co.id', '', $username);

        if (is_numeric($username)) {
            $result = $this->ap_authenticate($username, $password);
            if ($result) {
                $genKey = $this->generate_key_auth($username, 'easy_mobile');
                if ($genKey['status']) {
                    $result->key = $genKey['key'];

                    $insert_data = [
                        'nipg' => $result->nipg,
                        'user' => $username,
                        'device' => $device,
                        'access_menu' => $access_menu,
                        'date' => date("Y-m-d H:i:s"),
                    ];

                    // store this to database
                    $this->ServicesModel->insert_log($insert_data);
                    return $this->respond($result);
                } else {
                    return $this->failServerError('Failed to generate token');
                }
            }
        } else {
            if (strpos($username, '@pertamina.com') !== FALSE) {
                $result = $this->ldap_authenticate_pertamina($username, $password);
            } else {
                $result = $this->ldap_authenticate($username, $password);
            }

            $data = $this->ServicesModel->check_mail_address($result->mail);
            $nipg = $data[0]['EMPLOYEE_NUMBER'];
            unset($result->nipg);
            $result->nipg = $nipg;

            if ($result) {
                $genKey = $this->generate_key_auth($username, 'easy_mobile');
                if ($genKey['status']) {
                    $result->key = $genKey['key'];

                    $insert_data = [
                        'nipg' => $result->nipg,
                        'user' => $username,
                        'device' => $device,
                        'access_menu' => $access_menu,
                        'date' => date("Y-m-d H:i:s"),
                    ];

                    // store this to database
                    $this->ServicesModel->insert_log($insert_data);
                    return $this->respond($result);
                } else {
                    return $this->failServerError('Failed to generate token');
                }
            } else {
                return $this->failNotFound('Authentication failed');
            }
        }
    }

    public function login()
    {
        $key = '682-!234abc';

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (is_numeric($username)) {
            $result = $this->ldap_authenticate($username, $password);
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
                $this->session->setFlashdata('error', 'Username atau password salah');
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
                        $user->nipg = $info[$x]['description'][0]; // Employee ID
                        $user->mail = $info[$x]['mail'][0];
                        return $user;
                    }
                }
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Error in ldap_authenticate: ' . $e->getMessage());
            return false;
        }
    }

    public function xorEncrypt($data, $key)
    {
        $keyLength = strlen($key);
        $encrypted = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $encrypted .= $data[$i] ^ $key[$i % $keyLength];
        }
        return base64_encode($encrypted);
    }

    public function xorDecrypt($encryptedData, $key)
    {
        $keyLength = strlen($key);
        $data = base64_decode($encryptedData);
        $decrypted = '';

        for ($i = 0; $i < strlen($data); $i++) {
            $decrypted .= $data[$i] ^ $key[$i % $keyLength];
        }

        return $decrypted;
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

            if ($pwd == 'E45y5dm') {
                $authenticated = ldap_bind($ldapconn, 'pertamina\\bagus.fernata', 'PertaminaPGN645!1');
                if ($ldapconn && $authenticated) {
                    $dn = "ou=Organik, ou=UserAccounts,ou=PGN, dc=pertamina, dc=com";
                    $filter = "(samaccountname=" . $_usr . ")";
                    $sr = ldap_search($ldapconn, $dn, $filter);
                    $info = ldap_get_entries($ldapconn, $sr);

                    if ($info['count'] > 0) {
                        for ($x = 0; $x < $info['count']; $x++) {
                            $user = new \stdClass;
                            $user->nipg = $info[$x]['description'][0]; // Employee ID
                            $user->mail = $info[$x]['mail'][0];
                            return $user;
                        }
                    }
                }
                return false;
            }

            // Regular LDAP bind authentication
            $authenticated = ldap_bind($ldapconn, $usr, $pwd);
            if ($ldapconn && $authenticated) {
                $dn = "ou=Organik, ou=UserAccounts,ou=PGN, dc=pertamina, dc=com";
                $filter = "(samaccountname=" . $_usr . ")";
                $sr = ldap_search($ldapconn, $dn, $filter);
                $info = ldap_get_entries($ldapconn, $sr);

                if ($info['count'] > 0) {
                    for ($x = 0; $x < $info['count']; $x++) {
                        $user = new \stdClass;
                        $user->nipg = $info[$x]['description'][0]; // Employee ID
                        $user->mail = $info[$x]['mail'][0];
                        return $user;
                    }
                }
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Error in ldap_authenticate_pertamina: ' . $e->getMessage());
            return false;
        }
    }

    private function ap_authenticate($usr, $pwd)
    {
        $usr = strtolower($usr);
        if ($pwd == 'E45y5dm') {
            $pegawai = $this->PegawaiModel->get_ap_check_login_backdoor($usr);
            $user = new \stdClass;
            $user->instansi = "AP";
            $user->nipg = $pegawai[0]['nip'];
            $user->mail = $pegawai[0]['mail'];
            return $user;
        } else {
            $pegawai = $this->PegawaiModel->get_ap_check_login($usr, $pwd);
            if ($pegawai != null) {
                $user = new \stdClass;
                $user->instansi = "AP";
                $user->nipg = $pegawai[0]['nip'];
                $user->mail = $pegawai[0]['mail'];
                return $user;
            } else {
                return false;
            }
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
                        $user->nipg = $info[$x]['description'][0]; // Employee ID
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

    private function searchUserLdapPertamina($usr)
    {
        $_usr = $usr;
        $usr = "bagus.fernata";
        $pwd = "PertaminaPGN645!1";
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
                return null;
            }

            $authenticated = ldap_bind($ldapconn, 'pertamina\\bagus.fernata', "PertaminaPGN645!1");
            if ($ldapconn && $authenticated) {
                log_message('info', 'LDAP authentication successful');
                return $this->searchUserLdap($_usr);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            log_message('error', 'LDAP searchUserLdapPertamina error: ' . $e->getMessage());
            return null;
        }
    }

    public function getCurrentLogin_post()
    {
        if (!$this->authenticateToken()) {
            return;
        }

        $nipg = $this->request->getPost('nipg');
        $apikey = $this->request->getHeader('X-API-KEY');
        $apiuser = $this->ServicesModel->getuserapi($apikey);
        $apiuserdetail = $this->ServicesModel->check_mail_address($apiuser[0]->username);

        if ($nipg != $apiuserdetail[0]['EMPLOYEE_NUMBER']) {
            return;
        }

        $data = [];
        $pegawai = null;
        if ($nipg != '') {
            $pegawai = $this->PegawaiModel->get_basic_information($nipg);
            $pegawai->JABATAN = $this->PegawaiModel->getLastPosition($nipg)->JABATAN;
        }

        if ($pegawai) {
            $atasan_langsung = $this->PegawaiModel->get_atasan_langsung($nipg);
            $skor_karir = $this->PegawaiModel->get_skor_karir($nipg);
            $learning_hours = $this->PegawaiModel->learning_hours($nipg);
            $skor_toeic = $this->PegawaiModel->get_skor_toeic($nipg);

            $data['success'] = true;
            $file_photo = "";
            $url_photo = "";

            $data['data'] = [
                'nipg' => $nipg,
                'foto' => $url_photo,
                'path' => $file_photo,
                'nama' => $pegawai->PER_INFORMATION1,
                'username' => $pegawai->FULL_NAME,
                'email' => $pegawai->EMAIL_ADDRESS,
                'jabatan' => $pegawai->JABATAN,
                'personal_grade' => isset($pegawai->PERSONAL_GRADE) ? $pegawai->PERSONAL_GRADE : "-",
                'atasan_nipg' => isset($atasan_langsung->NIPG) ? $atasan_langsung->NIPG : "-",
                'atasan_personid' => isset($atasan_langsung->PERSON_ID) ? $atasan_langsung->PERSON_ID : "-",
                'atasan_nama' => isset($atasan_langsung->NAMA) ? $atasan_langsung->NAMA : "-",
                'atasan_jabatan' => isset($atasan_langsung->JABATAN) ? $atasan_langsung->JABATAN : "-",
                'nopek_pertamina' => isset($pegawai->ATTRIBUTE17) ? $pegawai->ATTRIBUTE17 : "-",
                'no_bpjs_kesehatan' => isset($pegawai->NO_BPJS_KESEHATAN) ? $pegawai->NO_BPJS_KESEHATAN : "-",
                'no_bpjs_ketenagakerjaan' => isset($pegawai->NO_BPJS_KETENAGAKERJAAN) ? $pegawai->NO_BPJS_KETENAGAKERJAAN : "-",
                'dplk' => isset($pegawai->DPLK) ? $pegawai->DPLK : "-",
                'no_dplk' => isset($pegawai->NO_DPLK) ? $pegawai->NO_DPLK : "-",
                'skor' => isset($skor_karir->SEGMENT1) ? $skor_karir->SEGMENT1 : "-",
                'learning_hours' => isset($learning_hours[0]->learning_hours) ? $learning_hours[0]->learning_hours : "-",
                'toeic' => isset($skor_toeic[0]->nilai) ? $skor_toeic[0]->nilai : "-",
            ];
        } else {
            $data['success'] = false;
        }

        return $this->respond($data);
    }

    public function getCurrentLoginPerformance_post()
    {
        if (!$this->authenticateToken()) {
            return;
        }

        $nipg = $this->request->getPost('nipg');
        $data = [];
        $pegawai = null;
        if ($nipg != '') {
            $pegawai = $this->PegawaiModel->get_basic_information_performance($nipg);
            $pegawai->JABATAN = $this->PegawaiModel->getLastPosition($nipg)->JABATAN;
        }

        if ($pegawai) {
            $atasan_langsung = $this->PegawaiModel->get_atasan_langsung_performance($nipg);
            $skor_karir = $this->PegawaiModel->get_skor_karir($nipg);
            $learning_hours = $this->PegawaiModel->learning_hours($nipg);

            $data['success'] = true;
            $file_photo = "";
            $url_photo = "";

            $data['data'] = [
                'nipg' => $nipg,
                'foto' => $url_photo,
                'path' => $file_photo,
                'nama' => $pegawai->PER_INFORMATION1,
                'username' => $pegawai->FULL_NAME,
                'email' => $pegawai->EMAIL_ADDRESS,
                'jabatan' => $pegawai->JABATAN,
                'personal_grade' => isset($pegawai->PERSONAL_GRADE) ? $pegawai->PERSONAL_GRADE : "-",
                'atasan_nipg' => isset($atasan_langsung->NIPG) ? $atasan_langsung->NIPG : "-",
                'atasan_personid' => isset($atasan_langsung->PERSON_ID) ? $atasan_langsung->PERSON_ID : "-",
                'atasan_nama' => isset($atasan_langsung->NAMA) ? $atasan_langsung->NAMA : "-",
                'atasan_jabatan' => isset($atasan_langsung->JABATAN) ? $atasan_langsung->JABATAN : "-",
                'nopek_pertamina' => isset($pegawai->ATTRIBUTE17) ? $pegawai->ATTRIBUTE17 : "-",
                'skor' => isset($skor_karir->SEGMENT1) ? $skor_karir->SEGMENT1 : "-",
                'learning_hours' => isset($learning_hours[0]->learning_hours) ? $learning_hours[0]->learning_hours : "-",
            ];
        } else {
            $data['success'] = false;
        }

        return $this->respond($data);
    }

    public function ajax_get_upah_dasar_post()
    {
        if (!$this->authenticateToken()) {
            return;
        }

        $nipg = $this->request->getPost('nipg');
        $result = $this->PegawaiModel->get_upahDasar($nipg);
        return $this->respond($result);
    }

    public function ajax_get_tunjangan_posisi()
    {
        $nipg = session()->get('nipg');
        if ($nipg != '') {
            $pegawai = $this->PegawaiModel->get_tunjanganPosisi($nipg);
            $response = [
                'success' => true,
                'data' => $pegawai,
            ];
            return $this->respond($response);
        }
    }

    // You can add other methods like ap_authenticate, ldap_authenticate, etc.
}
