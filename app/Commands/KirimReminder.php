<?php

namespace App\Commands;

use App\Models\AnakMagangModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Email\Email;

class KirimReminder extends BaseCommand
{
    protected $group = 'custom';
    protected $name = 'reminder:magang';
    protected $description = 'Mengirim email reminder ke peserta magang yang akan selesai dalam 3 hari';

    public function run(array $params)
    {
        $model = new AnakMagangModel();
        $peserta = $model->getPesertaHampirSelesai();

        if (empty($peserta)) {
            CLI::write('Tidak ada peserta yang perlu diingatkan hari ini.', 'yellow');
            return;
        }

        $email = service('email');

        foreach ($peserta as $p) {
            $email->setFrom('mdndfzn@gmail.com', 'Admin Magang');
            $email->setTo($p['email']);
            $email->setSubject('Pengingat Selesai Magang');
            $email->setMessage("
                <p>Halo <b>{$p['nama']}</b>,</p>
                <p>Magang Anda akan berakhir dalam 3 hari, tepatnya pada <b>{$p['tgl_selesai']}</b>.</p>
                <p>Jangan lupa untuk menyelesaikan tugas akhir Anda dan mempersiapkan laporan magang.</p>
                <p>Terima kasih.</p>
            ");

            if ($email->send()) {
                CLI::write("Reminder terkirim ke: {$p['email']}", 'green');
            } else {
                CLI::write("Gagal mengirim ke: {$p['email']}", 'red');
            }
        }
    }
}
