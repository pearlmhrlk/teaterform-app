<!DOCTYPE html>
<html lang="en"> <!-- Sebaiknya gunakan "en" atau bahasa yang sesuai -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registrasi User</title>
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
            background: rgba(0, 0, 0, 0.4);
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
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(to bottom, #ffe3ec, #ff596e);
            /* Warna putih untuk kotak login */
            border-radius: 0px;
            /* Sudut membulat */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Efek bayangan */
        }

        .col-xxl-3 {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 700px;
            /* Lebar maksimum form */
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        .input-group-text {
            background-color: #D8001B;
            /* Warna kotak ikon */
            border: none;
            /* Hilangkan border */
            color: #007bff;
            /* Warna ikon */
        }

        .form-control {
            border: 2px solid #D8001B;
            /* Border kotak input */
            border-radius: 0.375rem;
            /* Sesuaikan dengan sudut */
            padding: 0.5rem 1rem;
            width: 100%;
            /* Memanjang ke samping */
            max-width: none;
            /* Maksimal lebar kolom */
            height: 50px;
            font-size: 14px;
            background-color: #f8f9fa;
            box-shadow: none;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            /* Beri jarak antar kolom horizontal */
            margin-bottom: 20px;
            /* Jarak antar baris */
            flex-wrap: wrap;
            gap: 20px;
            /* Jarak antar textarea */
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
            /* Sesuaikan ukuran heading agar lebih kecil */
            font-weight: 600;
            /* Sesuaikan ketebalan huruf */
        }

        a.fw-medium {
            text-decoration: none;
            /* Hilangkan garis bawah */
            color: #D8001B !important;
            /* Ganti dengan warna sesuai keinginan */
        }

        a.fw-medium:hover {
            color: #770000 !important;
            /* Warna saat link di-hover */
        }

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

        .pt-3.text-center {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            /* Memastikan elemen ini memenuhi lebar container */
            margin-top: 1rem;
            /* Menjaga jarak vertikal */
        }

        label.form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #d8001b;
            /* Sesuaikan warna */
        }

        .auth-form-group-custom {
            flex: 1;
            /* Membuat textarea berbagi ruang */
            min-width: 0;
            /* Menghindari textarea tetap sempit */
            display: block;
            width: 100%;
            margin-bottom: none;
            /* Atur sesuai kebutuhan */
        }

        input.form-control,
        select.form-control {
            display: block;
            /* Untuk memastikan tampilannya konsisten */
            width: 100%;
            /* Input merentang sesuai lebar container */
            padding: 0.5rem 1rem;
            /* Jarak di dalam input */
            height: 50px;
            font-size: 14px;
            font-family: 'Lexend Deca', sans-serif;
            border: 2px solid #D8001B;
            /* Warna garis tepi */
            border-radius: 0.375rem;
            /* Lengkung tepi */
            background-color: #f8f9fa;
            /* Sesuaikan latar belakang */
            box-shadow: none;
            /* Hilangkan bayangan */
        }

        textarea.form-control {
            resize: vertical;
            /* Allow resizing only vertically */
            border: 2px solid #D8001B;
            /* Warna border */
            border-radius: 0.375rem;
            /* Sesuaikan bentuk */
            padding: 10px;
            /* Tambahkan padding untuk kenyamanan */
            font-size: 14px;
            /* Ukuran teks */
            height: 100px;
        }

        #customControlInline {
            margin-bottom: 45px;
            /* Menambah jarak antara checkbox dan elemen berikutnya */
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
            /* Sesuaikan dengan border kolom lain */
            border-radius: 0.375rem;
            /* Radius sudut sama */
            padding: 0.5rem 1rem;
            width: 100%;
            max-width: 400px;
            height: 50px;
            /* Tinggi yang sama */
            font-size: 14px;
            background-color: #f8f9fa;
            /* Sama seperti kolom lain */
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
            /* Atur nilai minimum sesuai kebutuhan */
            box-sizing: border-box;
        }

        .draft-item span {
            overflow: hidden;
            /* Potong teks jika terlalu panjang */
            text-overflow: ellipsis;
            /* Tambahkan elipsis (...) untuk teks terpotong */
            white-space: nowrap;
            /* Atur teks dalam satu baris */
            padding-left: 10px;
            /* Menambahkan padding kiri untuk menghindari teks terlalu dekat dengan batas */
            padding-right: 10px;
            /* Menambahkan padding kanan untuk ruang yang lebih baik */
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
                            <a href="<?= base_url('mitraTeater.html') ?>">Mitra Teater</a>
                        </li>
                        <li>
                            <a href="<?= base_url('aboutUs.html') ?>">Tentang Kami</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>

</html>