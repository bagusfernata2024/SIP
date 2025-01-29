<main class="main">
    <section id="login" class="hero section">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card p-4 shadow">
                        <a href="<?php echo base_url(); ?>">
                            <img src="<?php echo base_url('/'); ?>assets/img/logo-pgn.png" alt="logo-pgn" width="100px">
                        </a>
                        <h2 class="text-center mb-4">Login</h2>
                        <p class="text-center mb-4">Silahkan masukan username dan password anda.</p>
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                        <?php endif; ?>

                        <form action="<?= site_url('login/proses_login'); ?>" method="POST" class="needs-validation" novalidate>
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="invalid-feedback">Username wajib diisi.</div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">Password wajib diisi.</div>
                            </div>

                            <!-- Submit -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>