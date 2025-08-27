<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title) ?></title>
    <link rel="icon" href="<?= base_url('public/assets/images/favicon.png') ?>" sizes="20x20" type="image/png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css?v=1.0.1') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/animate.min.css?v=1.0.3') ?>">
    <script src="https://kit.fontawesome.com/2bbd03827e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('public/assets/css/magnific-popup.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/slick.css?v=1.0.1') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/slick-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/navbar-after-login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/homepage-mitra.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/penampilan-mitra.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/audisi-mitra.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/list-mitrateater.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/detail-mitrateater.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/audisi-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/penampilan-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/tentang-kami.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Lexend+Deca:wght@100..900&family=Playpen+Sans:wght@100..800&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

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

        <!-- Navbar start -->
        <nav class="navbar navbar-area style-three navbar-expand-lg">
            <div class="container nav-container">
                <div class="logo">
                    <a href="<?= base_url('Mitra/homepage') ?>">
                        <img src="<?= base_url('public/assets/images/logos/logo-one.png') ?>" alt="img">
                    </a>
                </div>

                <!-- Hamburger Menu Button -->
                <button class="navbar-toggler" type="button" id="hamburger-btn">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>

                <div class="collapse navbar-collapse" id="Iitechie_main_menu">
                    <ul class="navbar-nav menu-open">

                        <!-- Menu tambahan untuk Audisi dan Penampilan -->
                        <li>
                            <a href="<?= base_url('MitraTeater/crudAudisi') ?>">Audisi</a>
                        </li>
                        <li>
                            <a href="<?= base_url('MitraTeater/crudPenampilan') ?>">Penampilan</a>
                        </li>
                        <li>
                            <a href="<?= base_url('MitraTeater/listMitraTeater') ?>">Mitra Teater</a>
                        </li>
                        <li>
                            <a href="<?= base_url('MitraTeater/aboutUs') ?>">Tentang Kita</a>
                        </li>
                        <!-- Start Profile -->
                        <div class="dropdown d-inline-block" data-bs-auto-close="outside">
                            <button type="button" class="btn btn-sm top-icon p-0" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded avatar-2xs p-0"
                                    src="<?= base_url('public/assets/images/logos/avatar-6.png') ?>" alt="Header Avatar">
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown-menu-animated overflow-hidden py-0"
                                id="user-dropdown-menu">
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
                                                <span
                                                    class="rich-list-subtitle text-white"><?= esc($user['email']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <a href="<?= base_url('Mitra/profile') ?>" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="fa-regular fa-address-card"></i></div>
                                            <span class="grid-nav-content">Profile</span>
                                        </a>
                                    </div>
                                    <div class="card-footer card-footer-bordered rounded-0">
                                        <a href="<?= base_url('User/logout') ?>" class="btn btn-label-danger">Sign
                                            out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Profile -->
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Modal
        <div class="mobile-menu-modal" id="mobile-menu-modal">
            <div class="mobile-menu-content">
                <div class="mobile-menu-header">
                    <h5 style="margin: 0; color: #333;">Menu</h5>
                    <button class="close-mobile-menu" id="close-mobile-menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <ul class="mobile-menu-list">
                    <li>
                        <a href="<.?= base_url('MitraTeater/crudAudisi') ?>">Audisi</a>
                    </li>
                    <li>
                        <a href="<.?= base_url('MitraTeater/crudPenampilan') ?>">Penampilan</a>
                    </li>
                    <li>
                        <a href="<.?= base_url('MitraTeater/listMitraTeater') ?>">Mitra Teater</a>
                    </li>
                    <li>
                        <a href="<.?= base_url('MitraTeater/aboutUs') ?>">Tentang Kita</a>
                    </li>
                    <li>
                        <a href="<.?= base_url('MitraTeater/profile') ?>">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="<.?= base_url('User/logout') ?>" class="text-danger">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div> -->

        <!-- JavaScript untuk mobile menu -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hamburgerBtn = document.getElementById('hamburger-btn');
                const mobileMenuModal = document.getElementById('mobile-menu-modal');
                const closeMobileMenu = document.getElementById('close-mobile-menu');
                const hamburger = document.querySelector('.hamburger');

                hamburgerBtn.addEventListener('click', function() {
                    mobileMenuModal.classList.add('active');
                    hamburger.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                function closeMobileMenuFunc() {
                    mobileMenuModal.classList.remove('active');
                    hamburger.classList.remove('active');
                    document.body.style.overflow = '';
                }

                closeMobileMenu.addEventListener('click', closeMobileMenuFunc);

                mobileMenuModal.addEventListener('click', function(e) {
                    if (e.target === mobileMenuModal) {
                        closeMobileMenuFunc();
                    }
                });

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) {
                        closeMobileMenuFunc();
                    }
                });
            });
        </script>
</body>

</html>