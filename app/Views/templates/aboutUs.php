<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us</title>
  <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css?v=1.0.1') ?>">
  <style>
    body {
      font-family: 'Lexend Deca', sans-serif;
      background-color: #f9f9f9;
    }

    .section-header-box {
      background-color: #f8d7da;
      /* Merah muda lembut */
      color: #7a1b1b;
      padding: 40px 20px;
      margin: 40px auto 20px;
      border-radius: 20px;
      max-width: 800px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .section-header-box h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .section-header-box p {
      font-size: 1.1rem;
      color: #5a2d2d;
    }

    @media (max-width: 768px) {
      .section-header-box h1 {
        font-size: 2rem;
      }

      .section-header-box p {
        font-size: 1rem;
      }
    }

    .about-image {
      max-width: 100%;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .highlight-box {
      background-color: #ffffff;
      border-left: 5px solid #D8001B;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body>

  <!-- Hero Section -->
  <div class="section-header-box">
    <h1>Tentang Kami</h1>
    <p>Platform booking tiket teater untuk pertunjukan dan audisi dengan pengalaman modern dan cepat.</p>
  </div>

  <!-- About Content -->
  <section class="py-5">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <img src="<?= base_url('public/assets/images/about/tentang-kami.jpg') ?>" alt="Teater Illustration" class="about-image">
        </div>
        <div class="col-md-6">
          <div class="highlight-box">
            <h2 class="fw-bold mb-3">Solusi Modern untuk Dunia Teater</h2>
            <p class="text-muted">
              Kami hadir sebagai jembatan antara penonton dan dunia pertunjukan. Melalui sistem booking online kami, Anda dapat dengan mudah memesan tiket teater baik untuk pertunjukan utama maupun audisi publik.
            </p>
            <ul class="list-unstyled mt-4">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i>Pesan tiket teater kapan saja, di mana saja</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i>Lihat jadwal audisi yang sedang berlangsung</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i>Proses cepat, transparan, dan terverifikasi</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="text-center py-5 bg-light">
    <div class="container">
      <h3 class="fw-semibold">Mulai Jelajahi Dunia Teater Hari Ini</h3>
      <p class="text-muted">Gabung sekarang dan jadilah bagian dari pertunjukan terbaik dan audisi eksklusif.</p>
      <a href="<?= base_url('Audiens/registration') ?>" class="btn btn-danger px-4 py-2 mt-2">Registrasi akun sekarang</a>
    </div>
  </section>

  <script src="<?= base_url('public/assets/js/bootstrap.min.js') ?>"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</body>

</html>