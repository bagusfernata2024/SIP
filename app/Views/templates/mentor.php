<!-- Mulai Kontainer Data -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Mentor</h6>
        </div>
        <!-- Mulai Kontainer Utama -->

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIPG</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Direktorat</th>
                            <th>Divisi</th>
                            <th>Subsidiaris</th>
                            <th>Email</th>
                            <th>Job</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>NIPG</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Direktorat</th>
                            <th>Divisi</th>
                            <th>Subsidiaris</th>
                            <th>Email</th>
                            <th>Job</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $number = 1 ?>
                        <?php foreach ($mentor as $mentor) : ?>
                            <tr>
                                <td><?php echo $number ?></td>
                                <td><?php echo $mentor['nipg'] ?></td>
                                <td><?php echo $mentor['nama'] ?></td>
                                <td><?php echo $mentor['posisi'] ?></td>
                                <td><?php echo $mentor['direktorat'] ?></td>
                                <td><?php echo $mentor['division'] ?></td>
                                <td><?php echo $mentor['subsidiaries'] ?></td>
                                <td><?php echo $mentor['email'] ?></td>
                                <td><?php echo $mentor['job'] ?></td>
                                <td><a href="<?php echo site_url('admin/dashboard/detail_data_mentor/' . $mentor['id_mentor']); ?>">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-search" style="color: white;"></i>
                                        </button></a>
                                </td>
                            </tr>
                            <?php $number = $number + 1 ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>