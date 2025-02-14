<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
            <div class="company-badge mb-4">
              <i class="bi bi-gear-fill me-2"></i>
              Menyalakan Potensi, Menggerakkan Masa Depan
            </div>

            <h1 class="mb-4">
              Magang Bersama PGN, <br>
              Wujudkan Karirmu <br>
              <span class="accent-text">Di Dunia Gas</span>
            </h1>

            <p class="mb-4 mb-md-5">
              Bergabunglah dengan program magang kami dan temukan pengalaman berharga
              dalam industri energi gas. Bersama kami, kembangkan keterampilan dan
              wujudkan ambisimu di masa depan.
            </p>

            <div class="hero-buttons">
              <a href="<?php echo base_url('/registrasi'); ?>" class="btn btn-primary me-0 me-sm-2 mx-1">Daftar Sekarang</a>
              <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="btn btn-link mt-2 mt-sm-0 glightbox">
                <i class="bi bi-play-circle me-1"></i>
                Play Video
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
            <img src="<?php echo base_url('/'); ?>assets/img/illustration-1.webp" alt="Hero Image" class="img-fluid">
          </div>
        </div>
      </div>

      <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
        <div class="col-lg-4 col-md-6">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-person-plus"></i>
            </div>
            <div class="stat-content">
              <h4>Register Magang</h4>
              <p class="mb-0"><?php echo $total_register; ?></p> 
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-person-workspace"></i>
            </div>
            <div class="stat-content">
              <h4>Total Mentor</h4>
              <p class="mb-0"><?php echo $total_mentor; ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-content">
              <h4>Total Posisi</h4>
              <p class="mb-0"><?php echo $total_daftar_minat; ?></p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </section><!-- /Hero Section -->

  <!-- About Section -->
  <section id="about" class="about section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4 align-items-center justify-content-between">

        <div class="col-xl-5" data-aos="fade-up" data-aos-delay="200">
          <span class="about-meta">Tentang PGN</span>
          <h2 class="about-title">Penyedia Energi Gas untuk Masa Depan Indonesia</h2>
          <p class="about-description">
            PGN, sebagai bagian dari Holding Migas Pertamina, berkomitmen untuk mengelola dan menyediakan energi gas bumi yang efisien, aman, dan ramah lingkungan. Kami hadir untuk mendukung pembangunan ekonomi nasional dan meningkatkan kualitas hidup masyarakat Indonesia.
          </p>

          <div class="row feature-list-wrapper">
            <div class="col-md-6">
              <ul class="feature-list">
                <li><i class="bi bi-check-circle-fill"></i> Infrastruktur gas bumi terintegrasi</li>
                <li><i class="bi bi-check-circle-fill"></i> Layanan pelanggan terbaik</li>
                <li><i class="bi bi-check-circle-fill"></i> Solusi energi ramah lingkungan</li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="feature-list">
                <li><i class="bi bi-check-circle-fill"></i> Mendukung pembangunan nasional</li>
                <li><i class="bi bi-check-circle-fill"></i> Inovasi di sektor energi</li>
                <li><i class="bi bi-check-circle-fill"></i> Komitmen keberlanjutan</li>
              </ul>
            </div>
          </div>

          <div class="info-wrapper">
            <!-- <div class="row gy-4">
                <div class="col-lg-5">
                <div class="profile d-flex align-items-center gap-3">
                    <img src="<?php echo base_url('/'); ?>assets/img/avatar-1.webp" alt="CEO Profile" class="profile-image">
                    <div>
                    <h4 class="profile-name">John Doe</h4>
                    <p class="profile-position">CEO &amp; President Director</p>
                    </div>
                </div>
                </div>
                <div class="col-lg-7">
                <div class="contact-info d-flex align-items-center gap-2">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                    <p class="contact-label">Hubungi Kami</p>
                    <p class="contact-number">+62 21 1234-5678</p>
                    </div>
                </div>
                </div>
            </div> -->
          </div>
        </div>

        <div class="col-xl-6" data-aos="fade-up" data-aos-delay="300">
          <div class="image-wrapper">
            <div class="images position-relative" data-aos="zoom-out" data-aos-delay="400">
              <img src="<?php echo base_url('/'); ?>assets/img/about-5.webp" alt="Pembangunan Infrastruktur" class="img-fluid main-image rounded-4">
              <img src="<?php echo base_url('/'); ?>assets/img/about-2.webp" alt="Tim PGN" class="img-fluid small-image rounded-4">
            </div>
            <div class="experience-badge floating">
              <h3>50+ <span>Tahun</span></h3>
              <p>Pengalaman di bidang energi gas bumi</p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </section><!-- /About Section -->

  <!-- Features Section -->
  <section id="features" class="features section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Persyaratan</h2>
      <p>Persyaratan Berkas untuk Pendaftaran Program</p>
    </div><!-- End Section Title -->

    <div class="container">

      <div class="d-flex justify-content-center">

        <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

          <li class="nav-item">
            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-undergraduate">
              <h4>Undergraduate</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-freshgraduate">
              <h4>Freshgraduate</h4>
            </a>
          </li><!-- End tab nav item -->

        </ul>

      </div>

      <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

        <!-- Undergraduate Tab -->
        <div class="tab-pane fade active show" id="features-tab-undergraduate">
          <div class="row">
            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
              <h3>Syarat Berkas untuk Undergraduate</h3>
              <p class="fst-italic">
                Program ini ditujukan bagi mahasiswa aktif yang sedang menempuh pendidikan di perguruan tinggi.
                Kami membutuhkan dokumen yang mencerminkan kesiapan dan komitmen peserta terhadap program yang akan dijalani.
              </p>
              <ul>
                <li><i class="bi bi-check2-all"></i> Surat Permohonan dari pihak universitas yang bersangkutan.</li>
                <li><i class="bi bi-check2-all"></i> Proposal Magang yang menjelaskan tujuan dan rencana kerja.</li>
                <li><i class="bi bi-check2-all"></i> Curriculum Vitae (CV) terbaru.</li>
                <li><i class="bi bi-check2-all"></i> Marksheet (transkrip nilai) semester terakhir.</li>
                <li><i class="bi bi-check2-all"></i> Fotokopi KTP yang masih berlaku.</li>
              </ul>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 text-center">
              <img src="<?php echo base_url('/'); ?>assets/img/features-illustration-1.webp" alt="Undergraduate Illustration" class="img-fluid">
            </div>
          </div>
        </div><!-- End Undergraduate Tab -->

        <!-- Freshgraduate Tab -->
        <div class="tab-pane fade" id="features-tab-freshgraduate">
          <div class="row">
            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
              <h3>Syarat Berkas untuk Freshgraduate</h3>
              <p class="fst-italic">
                Program ini dirancang untuk lulusan baru yang ingin mengembangkan pengalaman dan keterampilan profesional.
                Peserta diharapkan mampu menunjukkan potensi dan motivasi untuk bekerja di bidang yang diminati.
              </p>
              <ul>
                <li><i class="bi bi-check2-all"></i> Surat Permohonan yang ditujukan kepada penyelenggara program.</li>
                <li><i class="bi bi-check2-all"></i> Proposal Magang yang mencakup perencanaan pengembangan karier.</li>
                <li><i class="bi bi-check2-all"></i> Curriculum Vitae (CV) dengan informasi terbaru.</li>
                <li><i class="bi bi-check2-all"></i> Marksheet (ijazah dan transkrip nilai). </li>
                <li><i class="bi bi-check2-all"></i> Fotokopi KTP yang masih berlaku.</li>
              </ul>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 text-center">
              <img src="<?php echo base_url('/'); ?>assets/img/features-illustration-3.webp" alt="Freshgraduate Illustration" class="img-fluid">
            </div>
          </div>
        </div><!-- End Freshgraduate Tab -->

      </div>

    </div>

  </section><!-- /Features Section -->

  <!-- Call To Action Section -->
  <section id="call-to-action" class="call-to-action section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row content justify-content-center align-items-center position-relative">
        <div class="col-lg-8 mx-auto text-center">
          <h2 class="display-4 mb-4">Jelajahi Potensimu Bersama Kami!</h2>
          <p class="mb-4">
            Raih pengalaman berharga dan kembangkan keahlianmu dengan bergabung di program magang kami.
            Dapatkan bimbingan profesional, peluang membangun jaringan, dan kesiapan menghadapi dunia kerja.
            Jangan lewatkan kesempatan ini untuk memulai perjalanan kariermu yang cemerlang!
          </p>
          <a href="<?php echo base_url('/registrasi'); ?>" class="btn btn-cta">Daftar Sekarang</a>
        </div>
      </div>


      <!-- Abstract Background Elements -->
      <div class="shape shape-1">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M47.1,-57.1C59.9,-45.6,68.5,-28.9,71.4,-10.9C74.2,7.1,71.3,26.3,61.5,41.1C51.7,55.9,35,66.2,16.9,69.2C-1.3,72.2,-21,67.8,-36.9,57.9C-52.8,48,-64.9,32.6,-69.1,15.1C-73.3,-2.4,-69.5,-22,-59.4,-37.1C-49.3,-52.2,-32.8,-62.9,-15.7,-64.9C1.5,-67,34.3,-68.5,47.1,-57.1Z" transform="translate(100 100)"></path>
        </svg>
      </div>

      <div class="shape shape-2">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M41.3,-49.1C54.4,-39.3,66.6,-27.2,71.1,-12.1C75.6,3,72.4,20.9,63.3,34.4C54.2,47.9,39.2,56.9,23.2,62.3C7.1,67.7,-10,69.4,-24.8,64.1C-39.7,58.8,-52.3,46.5,-60.1,31.5C-67.9,16.4,-70.9,-1.4,-66.3,-16.6C-61.8,-31.8,-49.7,-44.3,-36.3,-54C-22.9,-63.7,-8.2,-70.6,3.6,-75.1C15.4,-79.6,28.2,-58.9,41.3,-49.1Z" transform="translate(100 100)"></path>
        </svg>
      </div>

      <!-- Dot Pattern Groups -->
      <div class="dots dots-1">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <pattern id="dot-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
          </pattern>
          <rect width="100" height="100" fill="url(#dot-pattern)"></rect>
        </svg>
      </div>

      <div class="dots dots-2">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <pattern id="dot-pattern-2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
          </pattern>
          <rect width="100" height="100" fill="url(#dot-pattern-2)"></rect>
        </svg>
      </div>

      <div class="shape shape-3">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M43.3,-57.1C57.4,-46.5,71.1,-32.6,75.3,-16.2C79.5,0.2,74.2,19.1,65.1,35.3C56,51.5,43.1,65,27.4,71.7C11.7,78.4,-6.8,78.3,-23.9,72.4C-41,66.5,-56.7,54.8,-65.4,39.2C-74.1,23.6,-75.8,4,-71.7,-13.2C-67.6,-30.4,-57.7,-45.2,-44.3,-56.1C-30.9,-67,-15.5,-74,0.7,-74.9C16.8,-75.8,33.7,-70.7,43.3,-57.1Z" transform="translate(100 100)"></path>
        </svg>
      </div>
    </div>

    </div>

  </section><!-- /Call To Action Section -->

</main>
