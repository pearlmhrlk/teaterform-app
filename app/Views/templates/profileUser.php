<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>
    <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css?v=1.0.1') ?>">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .profile-card .card-header {
            background-color: #D8001B;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .profile-card .card-body {
            padding: 2rem;
        }

        .profile-card img {
            border: 5px solid #D8001B;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card profile-card">
            <div class="card-header text-center">
                <h2>User Profile</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="<?= base_url('public/assets/images/logos/avatar-6.png') ?>" class="img-fluid rounded-circle mb-3" alt="Profile Image" style="width: 150px; height: 150px;">
                    </div>
                    <div class="col-md-8">
                        <h4>Username: <span id="username"><?= esc($user['username']) ?></span></h4>
                        <h4>Nama: <span id="nama"><?= esc($user['nama']) ?></span></h4>
                        <h4>Email: <span id="email"><?= esc($user['email']) ?></span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('public/assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>