  <main class="main">
      <!-- Hero Section -->
      <section class="hero section">
          <div class="container" data-aos="fade-up">
              <div class="d-flex flex-column justify-content-center align-items-center mb-4">
                  <h2 class="mb-4" style="color:white">Pilih Jenis Pendaftaran</h2>
                  <div class="btn-group" role="group">
                      <a href="<?php echo base_url('registrasi') ?>" class="btn btn-primary btn-lg">Daftar Sebagai Peserta</a>
                      <a href="<?php echo base_url('registrasi/registrasi_mentor') ?>" class="btn btn-warning btn-lg disabled">Daftar Sebagai Mentor</a>
                  </div>
              </div>

              <div class="row justify-content-center">
                  <div class="col-lg-8">
                      <div class="card p-4 shadow">
                          <a href="<?php echo base_url(); ?>">
                              <img src="<?php echo base_url('/'); ?>assets/img/logo-pgn.png" alt="logo-pgn" width="100px">
                          </a>
                          <h2 class="text-center mb-4">Formulir Registrasi Mentor Magang</h2>
                          <p class="text-center mb-4">Isi formulir berikut untuk mendaftar program magang.</p>

                          <form action="<?= site_url('registrasi/proses_registrasi_mentor'); ?>" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">


                              <!-- Nama -->
                              <div class="mb-3">
                                  <label for="nama" class="form-label">Nama Lengkap</label>
                                  <input type="text" class="form-control" id="nama" name="nama" required>
                                  <div class="invalid-feedback">Nama wajib diisi.</div>
                              </div>

                              <!-- NIPG -->
                              <div class="mb-3">
                                  <label for="NIPG" class="form-label">NIPG</label>
                                  <input type="number" class="form-control" id="NIPG" name="nipg" required>
                                  <div class="invalid-feedback">NIPG wajib diisi.</div>
                              </div>

                              <!-- Email -->
                              <div class="mb-3">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email"
                                      class="form-control"
                                      id="email"
                                      name="email"
                                      required
                                      placeholder="Harus menggunakan @pertamina.com">
                                  <div class="invalid-feedback">Email harus menggunakan domain @pertamina.com.</div>
                              </div>
                              <!-- pattern="^[a-zA-Z0-9._%+-]+@pertamina\.com$" -->
                              <!-- Jenis Kelamin -->
                              <div class="mb-3">
                                  <label class="form-label">Jenis Kelamin</label>
                                  <div>
                                      <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" id="jkLaki" name="gender" value="L" required>
                                          <label class="form-check-label" for="jkLaki">Laki-laki</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" id="jkPerempuan" name="gender" value="P" required>
                                          <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                      </div>
                                  </div>
                                  <div class="invalid-feedback">Pilih jenis kelamin.</div>
                              </div>

                              <!-- Direktorat -->
                              <div class="mb-3">
                                  <label for="direktorat" class="form-label">Direktorat</label>
                                  <input type="text" class="form-control" id="direktorat" name="direktorat" required>
                                  <div class="invalid-feedback">Direktorat wajib diisi</div>
                              </div>

                              <!-- Divisi -->
                              <div class="mb-3">
                                  <label for="minat" class="form-label">Divisi</label>
                                  <select class="form-select" id="minat" name="division" required>
                                      <option value="" disabled selected>Pilih divisi...</option>
                                      <?php foreach ($divisions as $divisi): ?>
                                          <option value="<?= htmlspecialchars($divisi['nama_satker']); ?>"><?= htmlspecialchars($divisi['nama_satker']); ?></option>
                                      <?php endforeach; ?>
                                  </select>
                                  <div class="invalid-feedback">Silakan pilih divisi.</div>
                              </div>

                              <!-- Subsidiaries -->
                              <div class="mb-3">
                                  <label for="subsidiaries" class="form-label">Subsidiaries</label>
                                  <input type="text" class="form-control" id="subsidiaries" name="subsidiaries" required>
                                  <div class="invalid-feedback">Subsidiaries wajib diisi</div>
                              </div>

                              <!-- Job -->
                              <div class="mb-3">
                                  <label for="job" class="form-label">Job</label>
                                  <input type="text" class="form-control" id="job" name="job" required>
                                  <div class="invalid-feedback">Job wajib diisi</div>
                              </div>

                              <!-- Submit -->
                              <div class="text-center">
                                  <button type="submit" class="btn btn-primary" id="submitButton">Daftar</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>


          </div>

      </section><!-- /Hero Section -->

  </main>