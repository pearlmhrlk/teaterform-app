<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registrasi User</title>
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
        body {
            min-height: 100vh;
            background-size: cover;
            font-family: 'Lexend Deca', sans-serif;
            font-size: 14px;
        }

        .authentication-bg {
            position: relative;
            z-index: 1;
        }

        .bg-overlay {
            background: rgba(0, 0, 0, 0.4);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(to bottom, #ffe3ec, #ff596e);
            border-radius: 0px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .col-xxl-3 {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        .input-group-text {
            background-color: #D8001B;
            border: none;
            color: #007bff;
        }

        .form-control {
            border: 2px solid #D8001B;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            width: 100%;
            max-width: none;
            height: 50px;
            font-size: 14px;
            background-color: #f8f9fa;
            box-shadow: none;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }

        .d-flex>* {
            flex: 1;
            max-width: 48%;
        }

        .d-flex>.form-control {
            flex-grow: 1;
        }

        /* Mengubah ukuran heading dan paragraf di bagian login */
        h4 {
            font-size: 23px;
            font-weight: 600;
        }

        a.fw-medium {
            text-decoration: none;
            color: #D8001B !important;
        }

        a.fw-medium:hover {
            color: #770000 !important;
        }

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

        .pt-3.text-center {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 1rem;
        }

        label.form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #d8001b;
        }

        .auth-form-group-custom {
            flex: 1;
            min-width: 0;
            display: block;
            width: 100%;
            margin-bottom: none;
        }

        input.form-control,
        select.form-control {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            height: 50px;
            font-size: 14px;
            font-family: 'Lexend Deca', sans-serif;
            border: 2px solid #D8001B;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            box-shadow: none;
        }

        textarea.form-control {
            resize: vertical;
            border: 2px solid #D8001B;
            border-radius: 0.375rem;
            padding: 10px;
            font-size: 14px;
            height: 100px;
        }

        #customControlInline {
            margin-bottom: 45px;
        }

        /* Button Add Another Account */
        #social-media-input {
            margin-bottom: 0.5rem;
        }

        /* Menghapus tanda panah dropdown pada "Choose Platform" */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }

        #social-media-input .btn {
            padding: 0.5rem 1rem;
            font-size: 12px;
            background-color: #ff596e;
            border: none;
            cursor: pointer;
        }

        #social-media-input .btn:hover {
            background-color: #d8001b;
        }

        #acc_mitra,
        #platform_name {
            border: 2px solid #D8001B;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            width: 100%;
            max-width: 400px;
            height: 50px;
            font-size: 14px;
            background-color: #f8f9fa;
            box-shadow: none;
            margin-bottom: 0.5rem;
            margin-right: 10px;
        }

        .draft-item {
            max-width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px;
            background-color: #fffdb8;
            border: 1px solid #D8001B;
            border-radius: 0.375rem;
            margin-top: 0.5rem;
            margin: 5px 0;
            width: 220px;
            height: 30px;
        }

        .draft-section {
            flex: 1;
            min-width: 100px;
            box-sizing: border-box;
        }

        .draft-item span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-left: 10px;
            padding-right: 10px;
            color: #d8001b;
        }

        .delete-draft-btn {
            background-color: transparent;
            border: none;
            color: red;
            padding: 0;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .delete-draft-btn:hover {
            color: #d8001b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .d-flex>* {
                max-width: 100%;
                width: 100%;
            }

            .card-body {
                padding: 1.5rem;
            }

            h4 {
                font-size: 20px;
            }

            .btn-primary {
                width: 100%;
                font-size: 14px;
                padding: 12px 20px;
            }

            .form-control {
                height: 45px;
                font-size: 13px;
            }

            label.form-label {
                font-size: 13px;
            }

            .col-xxl-3 {
                max-width: 100%;
                padding: 0 0.5rem;
            }

            .draft-item {
                width: 100%;
                max-width: 100%;
            }

            #acc_mitra,
            #platform_name {
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }

            h4 {
                font-size: 18px;
            }

            .form-control {
                height: 40px;
                font-size: 12px;
                padding: 0.4rem 0.8rem;
            }

            label.form-label {
                font-size: 12px;
                margin-bottom: 0.3rem;
            }

            .btn-primary {
                font-size: 13px;
                padding: 10px 15px;
            }

            .d-flex {
                margin-bottom: 15px;
                gap: 8px;
            }

            textarea.form-control {
                height: 80px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .col-xxl-3 {
                padding: 0 0.25rem;
            }

            .card {
                margin: 0.5rem 0;
            }

            .form-control {
                height: 38px;
                font-size: 11px;
            }

            h4 {
                font-size: 16px;
            }

            .btn-primary {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        /* Navbar Responsive */
        @media (max-width: 991px) {
            .navbar-collapse {
                display: none !important;
            }

            .navbar-toggler {
                display: block;
            }
        }

        @media (min-width: 992px) {
            .navbar-toggler {
                display: none;
            }

            .mobile-menu-modal {
                display: none !important;
            }
        }

        /*Style Login End*/
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