<!DOCTYPE html>
<html lang="eng">

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
    <script src="<?= base_url('assets/js/search-filter-pertunjukan.js') ?>"></script>
    <script src="<?= base_url('assets/js/dropdown-navbar.js') ?>"></script>

    <script>
        $(document).ready(function() {
            console.log("DOM ready:", $('.poster-carousel').length);

            function initSlickIfNeeded() {
                $('.poster-carousel').each(function() {
                    const $carousel = $(this);

                    if (!$carousel.hasClass('slick-initialized')) {
                        $carousel.slick({
                            infinite: false,
                            slidesToShow: 4,
                            slidesToScroll: 1,
                            autoplay: false,
                            arrows: true,
                            dots: false,
                            centerMode: false,
                            variableWidth: false,

                            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
                            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',

                            responsive: [{
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1
                                }
                            }]
                        });
                    } else {
                        $carousel.slick('setPosition');
                    }
                });
            }

            // Paksa hitung ulang ukuran saat semua konten & gambar selesai dimuat
            $(window).on('load', function() {
                console.log("Window load:", $('.poster-carousel').length);
                initSlickIfNeeded();
            });

            // Kalau pakai tab atau show/hide container
            $('a[data-toggle="tab"], button[data-toggle="tab"]').on('shown.bs.tab', function() {
                initSlickIfNeeded();
            });

            initSlickIfNeeded(); // ⬅️ Tambahkan baris ini di akhir $(document).ready
        });

        document.querySelectorAll('.teater-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <script>
        function showLoginPopup() {
            document.getElementById("loginPopup").style.display = "flex";
        }

        function closeLoginPopup() {
            document.getElementById("loginPopup").style.display = "none";
        }
    </script>

    <script>
        document.querySelectorAll(".openSeatMap").forEach(item => {
            item.addEventListener("click", function(event) {
                event.preventDefault();
                let imageUrl = this.getAttribute("data-image");
                document.getElementById("seatMapImage").src = imageUrl;
                document.getElementById("seatMapModal").style.display = "block";
            });
        });

        document.querySelector(".close").addEventListener("click", function() {
            document.getElementById("seatMapModal").style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target == document.getElementById("seatMapModal")) {
                document.getElementById("seatMapModal").style.display = "none";
            }
        });
    </script>

    <script>
        document.getElementById("btnPesan").addEventListener("click", function() {
            const idTeater = this.dataset.id;
            const tipeJadwal = this.dataset.tipe;

            fetch(`<?= base_url('Audiens/booking-popup/${tipeJadwal}/${idTeater}') ?>`)
                .then(response => response.json())
                .then(data => {
                    const jadwalSelect = document.getElementById("selectJadwal");
                    jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

                    data.jadwal.forEach(j => {
                        const mulai = j.waktu_mulai.slice(0, 5);
                        const selesai = j.waktu_selesai.slice(0, 5);

                        const option = document.createElement("option");
                        option.value = j.id_jadwal; // Support audisi dan penampilan
                        option.textContent = `${j.tanggal}, ${mulai} - ${selesai}`;
                        jadwalSelect.appendChild(option);
                    });

                    // Simpan ke tombol YA
                    document.getElementById("btnYa").onclick = function() {
                        const selectedJadwal = jadwalSelect.value;
                        if (!selectedJadwal) {
                            alert("Silakan pilih jadwal terlebih dahulu.");
                            return;
                        }

                        localStorage.setItem("pendingUpload", "true");
                        localStorage.setItem("selectedJadwal", selectedJadwal);
                        localStorage.setItem("tipeJadwal", tipeJadwal);

                        fetch('<?= base_url('Booking/simpanBooking') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_jadwal: selectedJadwal,
                                    tipe_jadwal: tipeJadwal
                                })
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    // Pastikan server mengirimkan result.is_free
                                    localStorage.setItem("isFree", result.is_free);

                                    let url = data.url_pendaftaran;
                                    if (!/^https?:\/\//i.test(url)) {
                                        url = 'https://' + url;
                                    }
                                    window.location.href = url;
                                } else {
                                    alert(result.message || "Gagal menyimpan booking.");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                alert("Terjadi kesalahan.");
                            });
                    };

                    document.getElementById("popupKonfirmasi").style.display = "block";
                    document.getElementById("overlay").style.display = "block";
                });
        });

        document.getElementById("btnTidak").addEventListener("click", function() {
            document.getElementById("popupKonfirmasi").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        });

        window.onload = function() {
            const pending = localStorage.getItem("pendingUpload");
            const jadwal = localStorage.getItem("selectedJadwal");
            const isFree = localStorage.getItem("isFree");

            // Tampilkan popup HANYA jika semua data lengkap dan valid
            if (pending === "true" && jadwal && isFree !== null) {
                if (isFree === "1") {
                    document.getElementById("popupGratis").style.display = "block";
                } else {
                    document.getElementById("popupUpload").style.display = "block";
                }
                document.getElementById("overlay").style.display = "block";
            }
        };

        document.getElementById("btnBatalUpload").addEventListener("click", function() {
            const idJadwal = localStorage.getItem("selectedJadwal");

            // Kirim permintaan hapus booking pending ke server
            fetch(`<?= base_url('Booking/hapusBookingPending/${idJadwal}') ?>`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Booking dibatalkan:", data);
                    // Tutup popup dan hapus localStorage
                    document.getElementById("popupUpload").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    localStorage.removeItem("pendingUpload");
                    localStorage.removeItem("selectedJadwal");
                    localStorage.removeItem("isFree");
                });
        });

        document.getElementById("btnUpload").addEventListener("click", function() {
            const idJadwal = localStorage.getItem("selectedJadwal");

            fetch(`<?= base_url('Booking/ubahStatusSuccess/${idJadwal}') ?>`, {
                    method: 'POST'
                }).then(res => res.json())
                .then(data => {
                    console.log("Status booking berbayar berhasil diupdate:", data);

                    document.getElementById("popupUpload").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    localStorage.removeItem("pendingUpload");
                    localStorage.removeItem("selectedJadwal");
                    localStorage.removeItem("isFree");

                    alert("Bukti pembayaran berhasil diupload!");
                });
        });

        document.getElementById("btnBatalGratis").addEventListener("click", function() {
            const idJadwal = localStorage.getItem("selectedJadwal");

            fetch(`<?= base_url('Booking/hapusBookingPending/${idJadwal}') ?>`, {
                    method: 'DELETE'
                }).then(res => res.json())
                .then(data => {
                    console.log("Booking gratis dibatalkan:", data);
                    // Tutup popup dan overlay
                    document.getElementById("popupGratis").style.display = "none";
                    document.getElementById("overlay").style.display = "none";

                    // Bersihkan localStorage
                    localStorage.removeItem("pendingUpload");
                    localStorage.removeItem("selectedJadwal");
                    localStorage.removeItem("isFree");
                });
        });

        document.getElementById("btnKonfirmasiGratis").addEventListener("click", function() {
            const idJadwal = localStorage.getItem("selectedJadwal");

            fetch(`<?= base_url('Booking/ubahStatusSuccess/${idJadwal}') ?>`, {
                    method: 'POST'
                }).then(res => res.json())
                .then(data => {
                    console.log("Status booking gratis berhasil diupdate:", data);

                    // Tutup popup dan bersihkan localStorage
                    document.getElementById("popupGratis").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    localStorage.removeItem("pendingUpload");
                    localStorage.removeItem("selectedJadwal");
                    localStorage.removeItem("isFree");

                    alert("Pendaftaran berhasil dikonfirmasi!");
                });
        });
    </script>
</body>

</html>