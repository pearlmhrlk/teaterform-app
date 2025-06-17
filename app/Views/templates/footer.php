<!DOCTYPE html>
<html lang="zxx">

<body>

    <!-- templates/footer.php -->
    <footer style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #ddd;">
        <div class="container">
            <p style="margin: 0; font-size: 14px; color: #6c757d;">
                &copy; <?= date('Y'); ?> Theaterform. All rights reserved.
            </p>
            <div style="margin-top: 10px;">
                <a href="https://facebook.com" target="_blank" class="mx-2 social-link">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="mx-2 social-link">
                    <i class="fa-brands fa-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="mx-2 social-link">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>
    <!-- footer area end -->
    </div>
    <!--End pagewrapper-->

    <!-- all plugins here -->
    <script data-cfasync="false" src="<?= base_url('assets/js/email-decode.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/appear.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/imageload.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/skill.bars.jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/slick.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/wow.min.js') ?>"></script>

    <script>
        // Menambahkan event listener untuk dropdown secara manual
        document.getElementById('page-header-user-dropdown').addEventListener('click', function(event) {
            // Menargetkan dropdown menu dengan ID yang tepat
            console.log("Dropdown toggle diklik!");
            const dropdownToggle = this; // Elemen tombol dropdown
            const dropdownMenu = document.getElementById('user-dropdown-menu');

            if (!dropdownMenu) {
                console.error('Dropdown menu tidak ditemukan!');
                return;
            }

            const dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(dropdownToggle);

            // Menyembunyikan dropdown yang sedang terbuka jika ada
            if (dropdownMenu.classList.contains('show')) {
                dropdownInstance.hide(); // Menutup dropdown
            } else {
                dropdownInstance.show(); // Membuka dropdown
            }

            event.preventDefault(); // Gunakan untuk mencegah aksi default seperti membuka tautan
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let accordionButtons = document.querySelectorAll(".accordion-button");

            accordionButtons.forEach(button => {
                button.addEventListener("click", function() {
                    let target = document.querySelector(this.getAttribute("data-bs-target"));
                    let isExpanded = this.classList.contains("active");

                    // Tutup semua opsi dan reset ikon + kecuali yang sedang diklik
                    accordionButtons.forEach(btn => {
                        let panel = document.querySelector(btn.getAttribute("data-bs-target"));

                        if (btn !== this) {
                            btn.classList.remove("active");
                            btn.setAttribute("aria-expanded", "false");
                            btn.innerHTML = btn.innerHTML.replace("−", "+"); // Kembalikan ikon +
                            panel.classList.remove("show");
                        }
                    });

                    // Jika opsi sedang tertutup, maka buka dan ubah ikon ke minus
                    if (!isExpanded) {
                        this.classList.add("active");
                        this.setAttribute("aria-expanded", "true");
                        this.innerHTML = this.innerHTML.replace("+", "−"); // Ubah ikon ke minus
                        target.classList.add("show");
                    }
                });
            });
        });
    </script>

    <!-- main js  -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>

</html>