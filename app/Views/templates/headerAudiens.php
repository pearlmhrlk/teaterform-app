<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title) ?></title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.png') ?>" sizes="20x20" type="image/png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css?v=1.0.1') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.min.css?v=1.0.3') ?>">
    <script src="https://kit.fontawesome.com/2bbd03827e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/magnific-popup.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/slick.css?v=1.0.1') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/navbar-after-login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/detail-penampilan.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/detail-audisi.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/homepage-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/penampilan-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/audisi-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/list-mitrateater.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/detail-mitrateater.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Lexend+Deca:wght@100..900&family=Playpen+Sans:wght@100..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <style>
        .social-link {
            color: #6c757d;
            transition: color 0.3s ease-in-out, transform 0.2s ease-in-out;
            text-decoration: none;
        }

        .social-link:hover {
            color: #007bff;
            transform: scale(1.2);
        }

        @media (min-width: 768px) {
            .slide-content {
                padding-top: 50px;
                /* Memberikan lebih banyak ruang untuk teks */
                padding-bottom: 50px;
            }
        }

        /* Mengatur Padding pada Slider untuk Layar Kecil */
        @media (max-width: 768px) {
            .slide-content {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .slide-content h1 {
                font-size: 24px;
                /* Mengurangi ukuran font pada layar kecil */
            }

            .slide-content p {
                font-size: 16px;
                margin-bottom: 20px;
                /* Mengatur jarak antar elemen */
            }

            .slider-btns {
                gap: 10px;
                /* Mengurangi jarak antar tombol pada layar kecil */
            }

            .theme-btn {
                padding: 8px 15px;
                /* Mengurangi padding tombol */
                font-size: 14px;
                /* Mengurangi ukuran font pada layar kecil */
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <!-- preloader area start -->
        <div class="preloader" id="preloader">
            <div class="preloader-inner"></div>
        </div>
        <!-- preloader area end -->

        <!-- Navbar start -->
        <nav class="navbar navbar-area style-three navbar-expand-lg">
            <div class="container nav-container">
                <div class="logo">
                    <a href="<?= base_url('Audiens/homepageAudiens') ?>">
                        <img src="<?= base_url('assets/images/logos/logo-one.png') ?>" alt="img">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="Iitechie_main_menu">
                    <ul class="navbar-nav menu-open">

                        <!-- Menu tambahan untuk Audisi dan Penampilan -->
                        <li>
                            <a href="<?= base_url('Audiens/audisiAudiens') ?>">Audisi</a>
                        </li>
                        <li>
                            <a href="<?= base_url('Audiens/penampilanAudiens') ?>">Penampilan</a>
                        </li>
                        <li>
                            <a href="<?= base_url('Audiens/mitraTeater') ?>">Mitra Teater</a>
                        </li>
                        <li>
                            <a href="<?= base_url('Audiens/aboutUs') ?>">Tentang Kita</a>
                        </li>
                        <!-- Start Profile -->
                        <div class="dropdown d-inline-block" data-bs-auto-close="outside">
                            <button type="button" class="btn btn-sm top-icon p-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded avatar-2xs p-0" src="<?= base_url('assets/images/logos/avatar-6.png') ?>" alt="Header Avatar">
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown-menu-animated overflow-hidden py-0" id="user-dropdown-menu">
                                <div class="card border-0">
                                    <div class="card-header bg-primary rounded-0">
                                        <div class="rich-list-item w-100 p-0">
                                            <div class="rich-list-prepend">
                                                <div class="avatar avatar-label-light avatar-circle">
                                                    <div class="avatar-display"><i class="fa-solid fa-user"></i></div>
                                                </div>
                                            </div>
                                            <div class="rich-list-content">
                                                <!-- Menampilkan nama dan email dari data user yang diteruskan ke view -->
                                                <h3 class="rich-list-title text-white"><?= esc($user['nama']) ?></h3>
                                                <span class="rich-list-subtitle text-white"><?= esc($user['email']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <a href="<?= base_url('Audiens/profile') ?>" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="fa-regular fa-address-card"></i></div>
                                            <span class="grid-nav-content">Profile</span>
                                        </a>
                                    </div>
                                    <div class="card-footer card-footer-bordered rounded-0">
                                        <a href="<?= base_url('User/logout') ?>" class="btn btn-label-danger">Sign out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Profile -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>

</html>