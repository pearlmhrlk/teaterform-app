<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Additional responsive styles untuk body */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }

            .card {
                margin: 10px 0;
            }

            .auth-logo {
                height: 25px !important;
            }

            .alert {
                font-size: 13px;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }

            .card {
                margin: 5px 0;
            }

            .auth-logo {
                height: 20px !important;
            }

            .p-2 {
                padding: 1rem !important;
            }

            .mt-5 {
                margin-top: 2rem !important;
            }

            .alert {
                font-size: 12px;
                padding: 8px;
                margin-bottom: 15px;
            }

            .btn-close {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .col-10 {
                flex: 0 0 95%;
                max-width: 95%;
            }

            .auth-logo {
                height: 18px !important;
            }

            .mt-3 {
                margin-top: 1.5rem !important;
            }

            .mt-5 {
                margin-top: 1.5rem !important;
            }

            .text-center p {
                font-size: 12px;
            }
        }

        /* Responsive form validation messages */
        @media (max-width: 768px) {
            .text-danger {
                font-size: 12px;
                margin-top: 5px;
                display: block;
            }
        }

        /* Responsive logo adjustments */
        @media (max-width: 768px) {
            .logo-dark img {
                max-height: 25px;
            }
        }

        @media (max-width: 576px) {
            .logo-dark img {
                max-height: 20px;
            }
        }
    </style>
</head>

<body style="background: url('<?= base_url('public/assets/images/login/login-bg3.png') ?>') no-repeat center center fixed; background-size: cover;">
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <div>
                                <a href="<?= base_url('/Audiens/homepage') ?>" class="logo-dark">
                                    <img src="<?= base_url('public/assets/images/logos/logo-two.png') ?>" alt="" height="30" class="auth-logo logo-dark mx-auto">
                                </a>
                            </div>

                            <h4 class="font-size-18 mt-4">REGISTRASI USER</h4>
                        </div>
                        <div class="p-2 mt-5">

                            <?php if (session()->getFlashdata('success')) : ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form class="" action="<?= base_url('Audiens/registration') ?>" method="POST">
                                <?= csrf_field() ?>

                                <!-- Hidden input untuk menentukan role -->
                                <input type="hidden" name="id_role" value="1"> <!-- id_role 1 untuk Audiens -->

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Create username" aria-label="Username" value="<?= old('username') ?>">
                                        <?php if (\Config\Services::validation()->getError('username')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('username') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="auth-form-group-custom">
                                        <label for="nama" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter your name" aria-label="Name" value="<?= old('nama') ?>">
                                        <?php if (\Config\Services::validation()->getError('nama')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('nama') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" aria-label="Email" value="<?= old('email') ?>">
                                        <?php if (\Config\Services::validation()->getError('email')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('email') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Create password" aria-label="Password" value="<?= old('password') ?>">
                                        <?php if (\Config\Services::validation()->getError('password')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('password') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom me-2">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select id="gender" name="gender" class="form-control" aria-label="Gender">
                                            <option value="" selected disabled>Choose Gender</option>
                                            <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                        <?php if (\Config\Services::validation()->getError('gender')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('gender') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="Select your Birth Date" aria-label="Date of Birth" value="<?= old('tgl_lahir') ?>">
                                        <?php if (\Config\Services::validation()->getError('tgl_lahir')): ?>
                                            <div class="text-danger"><?= \Config\Services::validation()->getError('tgl_lahir') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Register</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Already have an account ? <a href="<?= base_url('User/login') ?>" class="fw-medium text-primary"> Login </a> </p>
                                </div>
                            </form>
                        </div>
                        <div class="mt-5 text-center">
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Theaterform Crafted <i class="mdi mdi-heart text-danger"></i> by Pearl
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('public/assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/waves.min.js') ?>"></script>

    <script src="<?= base_url('public/assets/js/app.js') ?>"></script>

</body>

</html>