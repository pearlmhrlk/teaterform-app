<!DOCTYPE html>
<html lang="en">

<body style="background: url('<?= base_url('assets/images/login/login-bg3.png') ?>') no-repeat center center fixed; background-size: cover;">
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <div>
                                <a href="<?= base_url('/Audiens/homepage') ?>" class="logo-dark">
                                    <img src="<?= base_url('assets/images/logos/logo-two.png') ?>" alt="" height="30" class="auth-logo logo-dark mx-auto">
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
                                        <?= \Config\Services::validation()->getError('username') ?>
                                    </div>
                                    <div>
                                        <label for="nama" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter your name" aria-label="Name" value="<?= old('nama') ?>">
                                        <?= \Config\Services::validation()->getError('nama') ?>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" aria-label="Email" value="<?= old('email') ?>">
                                        <?= \Config\Services::validation()->getError('email') ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Create password" aria-label="Password" value="<?= old('password') ?>">
                                        <?= \Config\Services::validation()->getError('password') ?>
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
                                        <?= \Config\Services::validation()->getError('gender') ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="Select your Birth Date" aria-label="Date of Birth" value="<?= old('tgl_lahir') ?>">
                                        <?= \Config\Services::validation()->getError('tgl_lahir') ?>
                                    </div>
                                </div>

                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Register</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Already have an account ? <a href="<?= base_url('Audiens/login') ?>" class="fw-medium text-primary"> Login </a> </p>
                                </div>
                            </form>
                        </div>
                        <div class="mt-5 text-center">
                            <p>©
                                <script>document.write(new Date().getFullYear())</script> Theaterform Crafted <i class="mdi mdi-heart text-danger"></i> by Pearl
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