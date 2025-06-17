<!DOCTYPE html>
<html lang="en">

<body style="background: url('<?= base_url('assets/images/login/login-bg4.png') ?>') no-repeat center center fixed; background-size: cover;">
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

                            <h4 class="font-size-18 mt-4">REGISTRASI MITRA TEATER</h4>
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

                            <?php if (session()->get('errors')) : ?>
                                <ul>
                                    <?php foreach (session()->get('errors') as $error) : ?>
                                        <li><?= esc($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <form class="" action="<?= base_url('MitraTeater/registration') ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                                <?= csrf_field() ?>

                                <!-- Hidden input untuk menentukan role -->
                                <input type="hidden" name="id_role" value="2"> <!-- id_role 2 untuk mitra teater -->

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Create username" aria-label="Username" required>
                                        <?= \Config\Services::validation()->getError('username') ?>
                                    </div>
                                    <div>
                                        <label for="nama" class="form-label">Community/Company Name</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Community/Company name" aria-label="Community or Company Name" required>
                                        <?= \Config\Services::validation()->getError('nama') ?>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="email" class="form-label">Community/Company Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter community/company email" aria-label="Community or Company email" required>
                                        <?= \Config\Services::validation()->getError('email') ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Create password" aria-label="Password" required>
                                        <?= \Config\Services::validation()->getError('password') ?>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom">
                                        <label for="id_platform_sosmed[]" class="form-label">Community/Company Social Media</label>
                                        <div id="social-media-input">
                                            <div class="social-media-mitra">
                                                <select name="id_platform_sosmed[]" id="platform_name" class="form-control" aria-label="Platform" required>
                                                    <option value="" selected disabled>Choose Platform</option>
                                                    <option value="1" data-nama="instagram">Instagram</option>
                                                    <option value="2" data-nama="twitter">Twitter</option>
                                                    <option value="3" data-nama="facebook">Facebook</option>
                                                    <option value="4" data-nama="threads">Threads</option>
                                                    <option value="5" data-nama="tiktok">Tiktok</option>
                                                    <option value="7" data-nama="telegram">Telegram</option>
                                                    <option value="8" data-nama="discord">Discord</option>
                                                    <option value="10" data-nama="line">LINE</option>
                                                    <option value="9" data-nama="whatsapp">Whatsapp</option>
                                                    <option value="6" data-nama="youtube">Youtube</option>
                                                </select>

                                                <input type="text" name="acc_mitra[]" id="acc_mitra" placeholder="Enter your account name">
                                                <?= !empty(\Config\Services::validation()->getError('acc_mitra')) ? \Config\Services::validation()->getError('acc_mitra') : \Config\Services::validation()->getError('hidden_accounts') ?>
                                            </div>
                                            <button id="add-account-btn" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                        </div>
                                        <div id="draft-accounts"></div>
                                        <input type="hidden" name="hidden_accounts" value="">
                                    </div>
                                    <div class="input-group auth-form-group-custom">
                                        <label for="alamat" class="form-label">Community/Company Address</label>
                                        <textarea class="form-control" id="alamat" name="alamat" placeholder="Enter community/company address" aria-label="Community or Company Address" maxlength="255" required></textarea>
                                        <?= \Config\Services::validation()->getError('alamat') ?>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom">
                                        <label for="berdiri_sejak" class="form-label">Berdiri Sejak</label>
                                        <input type="date" class="form-control" id="berdiri_sejak" name="berdiri_sejak" placeholder="Since..." aria-label="Tanggal Berdirinya Komunitas atau Perusahaan" required>
                                        <?= \Config\Services::validation()->getError('berdiri_sejak') ?>
                                    </div>
                                    <div class="input-group auth-form-group-custom">
                                        <label for="deskripsi" class="form-label">Community/Company Description</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Describe the Community/Company" aria-label="Community or Company Description" required></textarea>
                                        <?= \Config\Services::validation()->getError('deskripsi') ?>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="input-group auth-form-group-custom">
                                        <label for="history_show" class="form-label">Riwayat Pementasan Sebelumnya</label>
                                        <textarea class="form-control" id="history_show" name="history_show" placeholder="Contoh: Aladin (2020), JinJun (2019), ..." aria-label="Riwayat Pementasan Sebelumnya"></textarea>
                                        <small class="text-muted">*Tidak wajib diisi</small>
                                    </div>
                                    <div class="input-group auth-form-group-custom">
                                        <label for="prestasi" class="form-label">Prestasi yang pernah diraih</label>
                                        <textarea class="form-control" id="prestasi" name="prestasi" placeholder="Contoh: 'Theater of The Year' Grammy Award (2020), 'Best Theater Show' Alibaba Academy (2019), ..." aria-label="Prestasi yang pernah diraih"></textarea>
                                        <small class="text-muted">*Tidak wajib diisi</small>
                                    </div>
                                </div>

                                <div class="input-group auth-form-group-custom">
                                    <label for="logo" class="form-label">Upload Logo Community/Company</label>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required />
                                    <small class="text-muted">Format yang diperbolehkan: JPG, PNG, GIF. Maksimal ukuran: 2MB.</small>
                                    <?= \Config\Services::validation()->getError('logo') ?>
                                </div>

                                <div class="text-center pt-3">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" id="submitBtn" type="submit">Register</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Already have an account ? <a href="<?= base_url('MitraTeater/login') ?>" class="fw-medium text-primary"> Login </a> </p>
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

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/waves.min.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('add-account-btn');

            if (button) {
                button.addEventListener('click', function() {
                    const selectElement = document.querySelector('#social-media-input select[name="id_platform_sosmed[]"]');
                    const platformId = selectElement.value; // Ambil ID platform dari value
                    const platformName = selectElement.options[selectElement.selectedIndex].dataset.nama; // Ambil nama platform dari data-nama
                    const accountInput = document.querySelector('#social-media-input input[name="acc_mitra[]"]');

                    if (accountInput && accountInput.value.trim() !== '') {
                        const draftContainer = document.getElementById('draft-accounts');

                        // Tambahkan item draft ke container draft
                        const draftItem = document.createElement('div');
                        draftItem.classList.add('draft-item');
                        draftItem.setAttribute('data-platform-id', platformId);

                        // Tambahkan detail platform dan akun
                        draftItem.innerHTML = `
                            <span>${platformName}</span>
                            <span>${accountInput.value.trim()}</span>
                            <button type="button" class="delete-draft-btn">x</button>
                        `;
                        draftContainer.appendChild(draftItem);

                        // Perbarui hidden input dengan data baru
                        let hiddenInput = document.querySelector('input[name="hidden_accounts"]');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'hidden_accounts';
                            document.querySelector('form').appendChild(hiddenInput);
                        }

                        // Tambahkan data platform dan akun ke hidden input
                        const hiddenData = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];
                        hiddenData.push({
                            platformId: platformId,
                            platformName: platformName,
                            account: accountInput.value.trim(),
                        });
                        hiddenInput.value = JSON.stringify(hiddenData);
                        console.log('Updated Hidden Input Value:', hiddenInput.value);

                        // Tambahkan input acc_name[] untuk validasi backend
                        const newAccMitraInput = document.createElement('input');
                        newAccMitraInput.type = 'hidden';
                        newAccMitraInput.name = 'acc_mitra[]';
                        newAccMitraInput.value = accountInput.value.trim();
                        draftItem.appendChild(newAccMitraInput); // Tambahkan ke dalam draft item agar bisa dihapus nanti

                        // Kosongkan input setelah data ditambahkan
                        accountInput.value = '';
                        accountInput.removeAttribute('required');

                        // Tambahkan listener untuk tombol delete
                        draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                            draftItem.remove();

                            // Hapus data dari hidden input
                            const updatedData = hiddenData.filter(item => item.account !== newAccMitraInput.value);
                            hiddenInput.value = JSON.stringify(updatedData);

                            // Hapus elemen input acc_name[] terkait
                            const accInputs = document.querySelectorAll('input[name="acc_mitra[]"]');
                            accInputs.forEach(input => {
                                if (input.value === newAccMitraInput.value) {
                                    input.remove();
                                }
                            });

                            // Kembalikan atribut required jika draft kosong
                            if (draftContainer.children.length === 0) {
                                const accountInput = document.querySelector('#social-media-input input[name="acc_mitra[]"]');
                                if (accountInput) {
                                    accountInput.style.display = 'block'; // Tampilkan input yang sebelumnya tersembunyi
                                    accountInput.setAttribute('required', 'required');
                                }
                            }
                        });
                    } else {
                        console.log('Kolom Account Name kosong');
                    }
                });
            }

            document.querySelector('form').addEventListener('submit', function(event) {
                const hiddenInput = document.querySelector('input[name="hidden_accounts"]');
                const draftContainer = document.querySelector('#draft-accounts');

                if (!draftContainer || draftContainer.children.length === 0) {
                    alert('Harap tambahkan setidaknya satu akun media sosial.');
                    event.preventDefault(); // Batalkan submit jika kosong
                    return;
                }

                const hiddenData = [];
                draftContainer.querySelectorAll('.draft-item').forEach(item => {
                    const platformId = item.getAttribute('data-platform-id');
                    const accountName = item.querySelector('span:nth-child(2)').textContent;
                    hiddenData.push({
                        platformId: platformId,
                        account: accountName
                    });
                });

                hiddenInput.value = JSON.stringify(hiddenData); // Set nilai hidden input
                console.log('Hidden Input Value before submit:', hiddenInput.value);
            });
        });

        document.getElementById("submitBtn").addEventListener("click", function() {
            document.querySelectorAll(".social-media-mitra input, .social-media-mitra select").forEach(el => {
                if (el.name) {
                    el.dataset.tempName = el.name; // Simpan name agar bisa dikembalikan jika dibutuhkan
                    el.removeAttribute("name"); // Hapus name agar tidak dikirim ke server
                }
            });
        });
    </script>

    <script src="<?= base_url('assets/js/app.js') ?>"></script>

</body>

</html>