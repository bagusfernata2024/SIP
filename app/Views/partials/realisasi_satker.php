<div class="table-responsive">
    <table class="table table-bordered" width="100%" cellspacing="0">
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($realisasi_satker as $unit_kerja): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $unit_kerja['unit_kerja']; ?></td>
                    <td><?= $unit_kerja['jumlah_peserta']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
