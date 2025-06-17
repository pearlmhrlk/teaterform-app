<!DOCTYPE html>
<html lang="en">

<body style="background: url('<?= base_url('assets/images/login/login-bg.png') ?>') no-repeat center center fixed; background-size: cover;">
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="<?= base_url('/Audiens/homepage') ?>" class="logo-dark">
                                <img src="<?= base_url('assets/images/logos/logo-two.png') ?>" alt="" height="30" class="auth-logo logo-dark mx-auto">
                            </a>
                            <h4 class="mt-4">LOGIN USER</h4>
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

                            <form class="" action="<?= base_url('User/login') ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="background-color: #D8001B; color: white;" id="basic-addon1"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="background-color: #d8001b; color: white;" id="basic-addon2"><i class="fa-solid fa-unlock"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" aria-label="Password" aria-describedby="basic-addon2" required>
                                </div>

                                <div class="mb-sm-5">
                                    <div class="form-check float-sm-start">
                                        <input type="checkbox" class="form-check-input" id="customControlInline">
                                        <label class="form-check-label" for="customControlInline">Remember me</label>
                                    </div>
                                    <div class="float-sm-end">
                                        <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                    </div>
                                </div>

                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Log In</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Don't have an account ? <a href="<?= base_url('Audiens/registration') ?>" class="fw-medium text-primary"> Register </a> </p>
                                </div>
                            </form>
                        </div>

                        <div class="mt-5 text-center">
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

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/waves.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/app.js') ?>"></script>

</body>

</html>