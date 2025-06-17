<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login User</title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.png') ?>" sizes="20x20" type="image/png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css?v=1.0.1') ?>">
    <script src="https://kit.fontawesome.com/2bbd03827e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/simplebar.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/navbar-login.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Lexend+Deca:wght@100..900&family=Playpen+Sans:wght@100..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <style>
        /* Style Login Start */
        body {
            min-height: 100vh;
            background-size: cover;
            font-family: 'Lexend Deca', sans-serif;
            /* Ganti Arial dengan font yang diinginkan */
            font-size: 14px;
            /* Sesuaikan ukuran font agar tidak terlalu besar */
        }

        .authentication-bg {
            position: relative;
            z-index: 1;
        }

        .bg-overlay {
            background: rgba(0, 0, 0, 0.5);
            /* Optional: dark overlay for better visibility of login box */
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            /* Ensure the overlay doesn't block the content */
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
            /* Pastikan input-group tetap fleksibel */
            grid-template-columns: auto 1fr;
            align-items: center;
            /* Ikon dan input sejajar secara vertikal */
        }

        .input-group-text {
            grid-column: 1;
            align-items: center;
            /* Tengahkan konten di dalam ikon */
            justify-self: center;
            height: 50px;
            /* Samakan tinggi dengan kolom input */
            padding: 0.5rem 1rem;
            background-color: #D8001B;
            /* Warna latar ikon */
            color: #ffffff;
            /* Warna ikon */
            border: 2px solid #D8001B;
            /* Sama seperti input */
            border-radius: 0.375rem 0 0 0.375rem;
            /* Radius kiri untuk menyatu dengan input */
        }

        .form-control {
            grid-column: 2;
            border: 2px solid #D8001B;
            /* Border kotak input */
            border-radius: 0 0.375rem 0.375rem 0;
            /* Radius kanan */
            height: 50px;
            /* Tinggi yang sama untuk input */
            padding: 0.5rem 1rem;
            /* Ruang dalam input */
        }

        /* Mengubah ukuran heading dan paragraf di bagian login */
        h4 {
            font-size: 23px;
            /* Sesuaikan ukuran heading agar lebih kecil */
            font-weight: 600;
            /* Sesuaikan ketebalan huruf */
        }

        p.text-muted {
            font-size: 14px;
            /* Sesuaikan ukuran teks paragraf */
            color: #666666;
            /* Ganti warna teks untuk tampilan lebih lembut */
        }

        /* Mengubah tampilan link "Forgot your password?" dan "Register" */
        a.text-muted,
        a.fw-medium {
            text-decoration: none;
            /* Hilangkan garis bawah */
            color: #D8001B !important;
            /* Ganti dengan warna sesuai keinginan */
        }

        a.text-muted:hover,
        a.fw-medium:hover {
            color: #770000 !important;
            /* Warna saat link di-hover */
        }

        /* Mengubah tombol "Log In" */
        .btn-primary {
            background-color: #d8001b;
            /* Warna tombol */
            border: none;
            /* Hilangkan border */
            padding: 10px 20px;
            /* Tambah lebar dan tinggi tombol */
            width: 40%;
            /* Buat tombol memenuhi lebar form */
            font-size: 16px;
            /* Sesuaikan ukuran teks di tombol */
            text-transform: uppercase;
            /* Ubah teks menjadi huruf besar */
        }

        .btn-primary:hover {
            background-color: #ff596e;
            /* Warna tombol saat di-hover */
        }

        .form-check-input:checked {
            background-color: #d8001b;
            /* Warna sesuai kebutuhan */
            border-color: #ff596e;
        }

        @media (max-width: 768px) {
            .input-group-text {
                height: auto;
                padding: 0.5rem;
            }

            .form-control {
                height: auto;
                padding: 0.5rem;
            }
        }

        /*Style Login End*/
    </style>
</head>

<body>
    <div class="page-wrapper">

        <!-- Navbar start -->
        <nav class="navbar navbar-area style-three navbar-expand-lg">
            <div class="container nav-container">
                <div class="logo">
                    <a href="<?= base_url('Audiens/homepage') ?>">
                        <img src="<?= base_url('assets/images/logos/logo-one.png') ?>" alt="img">
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
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>

</html>