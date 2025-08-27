<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login User</title>
    <link rel="icon" href="<?= base_url('public/assets/images/favicon.png') ?>" sizes="20x20" type="image/png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css?v=1.0.1') ?>">
    <script src="https://kit.fontawesome.com/2bbd03827e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('public/assets/css/simplebar.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/navbar-login.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Lexend+Deca:wght@100..900&family=Playpen+Sans:wght@100..800&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <style>
        /* Style Login Start */
        html {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            max-width: 100%;
            overflow-x: hidden;
            min-height: 100vh;
            background-size: cover;
            font-family: 'Lexend Deca', sans-serif;
            font-size: 14px;
        }

        .hamburger {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 25px;
            height: 18px;
            cursor: pointer;
        }

        .hamburger span {
            display: block;
            height: 3px;
            background-color: white;
        }

        .authentication-bg {
            position: relative;
            z-index: 1;
        }

        .bg-overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .card {
            background: linear-gradient(to bottom, #ffe3ec, #ff596e);
            /* Warna putih untuk kotak login */
            border-radius: 0px;
            /* Sudut membulat */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Efek bayangan */
        }

        .card-body {
            padding: 2rem;
        }

        .input-group {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
        }

        .input-group-text {
            grid-column: 1;
            align-items: center;
            justify-self: center;
            height: 50px;
            padding: 0.5rem 1rem;
            background-color: #D8001B;
            color: #ffffff;
            border: 2px solid #D8001B;
            border-radius: 0.375rem 0 0 0.375rem;
        }

        .form-control {
            grid-column: 2;
            border: 2px solid #D8001B;
            border-radius: 0 0.375rem 0.375rem 0;
            height: 50px;
            padding: 0.5rem 1rem;
        }

        /* Mengubah ukuran heading dan paragraf di bagian login */
        h4 {
            font-size: 23px;
            font-weight: 600;
        }

        p.text-muted {
            font-size: 14px;
            color: #666666;
        }

        /* Mengubah tampilan link "Forgot your password?" dan "Register" */
        a.text-muted,
        a.fw-medium {
            text-decoration: none;
            color: #D8001B !important;
        }

        a.text-muted:hover,
        a.fw-medium:hover {
            color: #770000 !important;
        }

        /* Mengubah tombol "Log In" */
        .btn-primary {
            background-color: #d8001b;
            border: none;
            padding: 10px 20px;
            width: 40%;
            font-size: 16px;
            text-transform: uppercase;
        }

        .btn-primary:hover {
            background-color: #ff596e;
        }

        .form-check-input:checked {
            background-color: #d8001b;
            border-color: #ff596e;
        }

        /* Tambahkan di CSS utama */
        .mobile-menu-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }

        /* Saat menu aktif (diklik) */
        .mobile-menu-modal.active {
            display: block;
        }

        .mobile-menu-modal.show {
            display: block;
        }

        .mobile-menu-content {
            position: absolute;
            top: 70px;
            right: 15px;
            width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
            font-family: 'Segoe UI', sans-serif;
            animation: fadeIn 0.3s ease;
        }

        /* Menu Title */
        .mobile-menu-content h3 {
            margin-bottom: 20px;
            font-size: 18px;
            color: #111;
        }

        /* Menu Links */
        .mobile-menu-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-menu-content ul li {
            margin: 10px 0;
        }

        .mobile-menu-content ul li a {
            text-decoration: none;
            color: #0077cc;
            font-weight: 500;
            transition: color 0.2s;
            outline: none;
        }

        .mobile-menu-content ul li a:hover {
            color: #005999;
        }

        .mobile-menu-content ul li a:focus {
            outline: none;
            /* hilangkan garis biru */
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-mobile-menu {
            position: absolute;
            top: 10px;
            right: 12px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            border: none;
            background: none;
        }

        @media (max-width: 768px) {
            #hamburger-btn {
                display: block;
                position: absolute;
                right: 20px;
                top: 20px;
                z-index: 9999;
            }

            .no-scroll {
                overflow: hidden;
                height: 100vh;
            }

            #Iitechie_main_menu {
                display: none;
            }

            .mobile-menu-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 9999;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
            }

            .mobile-menu-modal.active {
                display: block;
            }

            .input-group-text {
                height: auto;
                padding: 0.75rem;
            }

            .form-control {
                height: auto;
                padding: 0.5rem;
            }

            .btn-primary {
                width: 55%;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <nav class="navbar navbar-area style-three navbar-expand-lg">
            <div class="container nav-container">
                <div class="logo">
                    <a href="<?= base_url('Audiens/homepage') ?>">
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
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Modal -->
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
                </ul>
            </div>
        </div>
    </div>

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
                document.body.classList.add('no-scroll');
            });

            function closeMobileMenuFunc() {
                mobileMenuModal.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.classList.remove('no-scroll');
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