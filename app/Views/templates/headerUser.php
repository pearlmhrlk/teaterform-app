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
    <link rel="stylesheet" href="<?= base_url('public/assets/css/navbar-before-login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/penampilan-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/audisi-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/homepage-audiens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/list-mitrateater.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/detail-mitrateater.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/tentang-kami.css') ?>">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Lexend+Deca:wght@100..900&family=Playpen+Sans:wght@100..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <style>
        .status-container .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .status-container .status-message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }

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
                    <a href="<?= base_url('Audiens/homepage') ?>">
                        <img src="<?= base_url('public/assets/images/logos/logo-one.png') ?>" alt="img">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="Iitechie_main_menu">
                    <ul class="navbar-nav menu-open">

                        <!-- Menu tambahan untuk Audisi dan Penampilan -->
                        <li>
                            <a href="<?= base_url('Audiens/listAudisi') ?>">Audisi</a>
                        </li>
                        <li>
                            <a href="<?= base_url('Audiens/listPenampilan') ?>">Penampilan</a>
                        </li>
                        <li>
                            <a href="<?= base_url('User/mitraTeater') ?>">Mitra Teater</a>
                        </li>
                        <li>
                            <a href="<?= base_url('User/tentangKami') ?>">Tentang Kita</a>
                        </li>
                        <li>
                            <a href="<?= base_url('User/login') ?>">Masuk</a>
                        </li>
                        <!-- Menu Home dengan sub-menu -->
                        <li class="menu-item-has-children">
                            <a href="javascript:void(0)">Daftarkan Akunmu!</a>
                            <ul class="sub-menu">
                                <li><a href="<?= base_url('Audiens/registration') ?>">Audiens</a></li>
                                <li><a href="<?= base_url('MitraTeater/registration') ?>">Mitra Teater</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>

</html>