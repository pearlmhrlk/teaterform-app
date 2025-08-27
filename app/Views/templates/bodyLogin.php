<!DOCTYPE html>
<html lang="en">

<body
    style="background: url('<?= base_url('public/assets/images/login/login-bg.png') ?>') no-repeat center center fixed; background-size: cover;">
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="<?= base_url('/Audiens/homepage') ?>" class="logo-dark">
                                <img src="<?= base_url('public/assets/images/logos/logo-two.png') ?>"
                                    alt="TheaterForm Logo"
                                    height="30"
                                    class="auth-logo logo-dark mx-auto mb-3">
                            </a>
                            <h4 class="mt-4">LOGIN USER</h4>
                        </div>

                        <div class="p-2 mt-5">

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form class="" action="<?= base_url('User/login') ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="background-color: #D8001B; color: white;"
                                        id="basic-addon1">
                                        <i class="fa-regular fa-user" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control" name="username" id="username"
                                        placeholder="Enter username" aria-label="Username"
                                        aria-describedby="basic-addon1" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="background-color: #d8001b; color: white;"
                                        id="basic-addon2">
                                        <i class="fa-solid fa-unlock" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Enter password" aria-label="Password"
                                        aria-describedby="basic-addon2" required>
                                </div>

                                <div class="mb-sm-5 form-options">
                                    <div class="form-check remember-me">
                                        <input type="checkbox" class="form-check-input" id="customControlInline">
                                        <label class="form-check-label" for="customControlInline">Remember me</label>
                                    </div>
                                    <div class="forgot-password">
                                        <a href="auth-recoverpw.html" class="text-muted">
                                            <i class="mdi mdi-lock me-1"></i> Forgot your password?
                                        </a>
                                    </div>
                                </div>

                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Log
                                        In</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Don't have an account ? <a
                                            href="<?= base_url('Audiens/registration') ?>"
                                            class="fw-medium text-primary"> Register </a> </p>
                                </div>
                            </form>
                        </div>

                        <div class="mt-5 text-center footer-text">
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Theaterform. Crafted with <i class="mdi mdi-heart text-danger"></i> by Pearl
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

    <style>
        /* Additional responsive styles for bodyLogin.php */

        /* Base responsive adjustments */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .remember-me {
            order: 1;
        }

        .forgot-password {
            order: 2;
        }

        /* Extra Small devices (phones, less than 576px) */
        @media (max-width: 575.98px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .form-options {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .remember-me {
                order: 2;
            }

            .forgot-password {
                order: 1;
            }

            .form-check {
                justify-content: center;
            }

            .forgot-password a {
                font-size: 13px;
            }

            .footer-text p {
                font-size: 12px;
                line-height: 1.4;
            }

            .mt-5 {
                margin-top: 2rem !important;
            }

            .mb-sm-5 {
                margin-bottom: 2rem !important;
            }
        }

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .form-options {
                flex-direction: row;
                justify-content: space-between;
            }

            .remember-me {
                order: 1;
            }

            .forgot-password {
                order: 2;
            }
        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .col-md-8 {
                max-width: 60%;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            .col-lg-6 {
                max-width: 45%;
            }
        }

        /* Extra responsive fixes for very small screens */
        @media (max-width: 360px) {
            .card {
                margin: 5px;
            }

            .p-2 {
                padding: 0.5rem !important;
            }

            .forgot-password a {
                font-size: 12px;
                display: block;
                word-break: break-word;
            }

            .form-check-label {
                font-size: 13px;
            }
        }

        /* Landscape orientation adjustments for mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .min-vh-100 {
                min-height: auto;
                padding: 10px 0;
            }

            .mt-5 {
                margin-top: 1rem !important;
            }

            .mt-4 {
                margin-top: 0.5rem !important;
            }

            .footer-text {
                margin-top: 1rem !important;
            }

            .mb-sm-5 {
                margin-bottom: 1rem !important;
            }
        }

        /* Focus states for better accessibility */
        .form-control:focus,
        .form-check-input:focus,
        .btn:focus {
            outline: 2px solid #D8001B;
            outline-offset: 2px;
        }

        /* Ensure proper spacing on all screen sizes */
        @media (max-width: 480px) {
            .input-group {
                margin-bottom: 1rem;
            }

            .alert {
                font-size: 13px;
                padding: 0.5rem 0.75rem;
            }

            .btn-close {
                font-size: 12px;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .card {
                border: 2px solid #000;
            }

            .input-group-text {
                border: 2px solid #000;
            }

            .form-control {
                border: 2px solid #000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {

            .waves-effect,
            .fade {
                animation: none;
                transition: none;
            }
        }
    </style>

</body>

</html>