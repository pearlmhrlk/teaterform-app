<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registrasi Berhasil</title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.png') ?>" sizes="20x20" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f8fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .container h2 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .container p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .btn {
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            margin-right: 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?= session()->getFlashdata('message') ?>

    <div class="container">
        <h2>Anda berhasil melakukan pendaftaran!</h2>
        <p>Mohon melakukan verifikasi email dengan cara mengklik tautan yang telah dikirim ke email terdaftar.</p>

        <a href="<?= base_url('Audiens/homepage') ?>" class="btn btn-primary">Kembali ke Halaman Depan</a>
    </div>
</body>
</html>