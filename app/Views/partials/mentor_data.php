<table class="table table-bordered" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Mentor</th>
            <th>Jumlah Peserta</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mentors as $index => $mentor): ?>
            <tr>
                <td><?= $index + 1; ?></td>
                <td><?= $mentor['nama']; ?></td>
                <td><?= $mentor['jumlah_peserta']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Paginasi -->
<div class="d-flex justify-content-between">
    <button id="previousBtn" class="btn btn-secondary" <?= $currentPage <= 1 ? 'disabled' : ''; ?>>Previous</button>
    <button id="nextBtn" class="btn btn-primary" <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>>Next</button>
</div>
