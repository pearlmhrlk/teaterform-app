<!DOCTYPE html>
<html lang="en">

<body>
    <div class="status-container">
        <div class="container">
            <h3>Cek Status Akun</h3>
            <p>Masukkan email yang digunakan saat pendaftaran.</p>

            <form action="<?= site_url('MitraTeater/cekStatus') ?>" method="post">
                <?= csrf_field(); ?> <!-- Tambahkan CSRF token di sini -->

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Cek Status</button>
            </form>

            <?php if (session()->getFlashdata('status')) : ?>
                <div class="alert <?= esc(session()->getFlashdata('class')); ?> mt-3">
                    <?= esc(session()->getFlashdata('status')); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger mt-3">
                    <?= esc(session()->getFlashdata('error')); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>