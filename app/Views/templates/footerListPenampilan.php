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
    <script data-cfasync="false" src="<?= base_url('public/assets/js/email-decode.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/appear.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/imageload.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/skill.bars.jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/slick.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/wow.min.js') ?>"></script>

    <script>
        const searchUrl = <?= isset($searchUrl) ? json_encode($searchUrl) : 'null' ?>;
    </script>

    <script src="<?= base_url('public/assets/js/search-filter-pertunjukan.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/dropdown-navbar.js') ?>"></script>

    <script>
        $(document).ready(function() {

            function initSlickIfNeeded() {
                $('.poster-carousel').each(function() {
                    const $carousel = $(this);
                    const itemCount = $carousel.find('.teater-item').length;

                    // Hindari init ulang
                    if (!$carousel.hasClass('slick-initialized')) {
                        const slideCount = itemCount >= 4 ? 4 : itemCount; // ⬅️ Dinamis di sini

                        $carousel.slick({
                            infinite: false,
                            slidesToShow: slideCount,
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

        document.querySelectorAll('.teater-item')?.forEach(item => {
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
        // Tampilkan modal QR Code saat klik teks
        const linkQRCode = document.getElementById("linkQRCode");
        const modalQRCode = document.getElementById("modalQRCode");
        const imgQRCode = document.getElementById("imgQRCode");
        const btnTutupQRCode = document.getElementById("btnTutupQRCode");

        linkQRCode.addEventListener("click", function() {
            modalQRCode.style.display = "block";
        });

        // Tutup modal QR Code
        btnTutupQRCode.addEventListener("click", function() {
            modalQRCode.style.display = "none";
        });

        // Tutup modal kalau klik di overlay
        window.addEventListener("click", function(event) {
            if (event.target == modalQRCode) {
                modalQRCode.style.display = "none";
            }
        });

        window.onload = function() {
            const pending = localStorage.getItem("pendingUpload");
            const jadwal = localStorage.getItem("selectedJadwal");
            const isFree = localStorage.getItem("isFree") === "1"; // true/false

            const popup = document.getElementById("popupKonfirmasi");
            const overlay = document.getElementById("overlay");
            const divUpload = document.getElementById("divUploadBukti");

            // reset semua elemen
            divUpload.style.display = "none";
            linkQRCode.style.display = "none";

            if (pending === "true" && jadwal && !isNaN(isFree)) {
                popup.style.display = "block";
                overlay.style.display = "block";

                if (isFree) { // cukup boolean check
                    document.getElementById("popupGratis").style.display = "block";
                    divUpload.style.display = "none";
                    linkQRCode.style.display = "none";
                } else {
                    divUpload.style.display = "block";
                    linkQRCode.style.display = "inline-block";
                }
            }
        };

        // Saat klik tombol Pesan
        document.getElementById("btnPesan").addEventListener("click", function() {
            const idTeater = this.dataset.id;
            const tipeJadwal = this.dataset.tipe;
            const baseUrl = "<?= base_url() ?>";
            const url = `${baseUrl}Audiens/booking-popup/${tipeJadwal}/${idTeater}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const jadwalSelect = document.getElementById("selectJadwal");
                    jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

                    data.jadwal.forEach(j => {
                        const mulai = j.waktu_mulai.slice(0, 5);
                        const selesai = j.waktu_selesai.slice(0, 5);
                        const option = document.createElement("option");
                        option.value = j.id_jadwal;
                        option.textContent = `${j.tanggal}, ${mulai} - ${selesai}`;
                        option.dataset.isFree = j.is_free;
                        option.dataset.qrcode = j.qrcode_bayar || '';
                        jadwalSelect.appendChild(option);
                    });

                    document.getElementById("popupKonfirmasi").style.display = "block";
                    document.getElementById("overlay").style.display = "block";

                    const divUpload = document.getElementById("divUploadBukti");
                    const linkQRCode = document.getElementById("linkQRCode");

                    // reset awal saat popup dibuka
                    divUpload.style.display = "none";
                    linkQRCode.style.display = "none";

                    jadwalSelect.onchange = function() {
                        const selected = this.selectedOptions[0];
                        if (!selected || !selected.value) {
                            divUpload.style.display = "none";
                            linkQRCode.style.display = "none";
                            return;
                        }

                        const isFree = selected.dataset.isFree === "1"; // ambil langsung dari option
                        const qrcode = selected.dataset.qrcode;

                        localStorage.setItem("selectedJadwal", this.value);
                        localStorage.setItem("isFree", isFree ? "1" : "0"); // update localStorage

                        if (isFree) {
                            divUpload.style.display = "none"; // tetap sembunyi untuk jadwal gratis
                            linkQRCode.style.display = "none";
                        } else {
                            divUpload.style.display = "block"; // tampil untuk jadwal bayar
                            linkQRCode.style.display = "inline-block";
                            imgQRCode.src = qrcode || '';
                        }
                    };

                    document.getElementById("btnKonfirmasi").onclick = async function() {
                        const selected = jadwalSelect.selectedOptions[0];
                        if (!selected) return alert("Silakan pilih jadwal.");

                        const idJadwal = selected.value;
                        const isFree = selected.dataset.isFree == "1";

                        // Simpan booking dulu
                        const res = await fetch('<?= base_url("Booking/simpanBooking") ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_jadwal: idJadwal,
                                tipe_jadwal: tipeJadwal
                            })
                        });
                        const result = await res.json();
                        if (!result.success) return alert(result.message || "Gagal simpan booking");

                        localStorage.setItem("idBooking", result.id_booking);

                        if (isFree) {
                            alert("Booking berhasil (gratis).");
                            closePopup();
                        } else {
                            const fileInput = document.getElementById("buktiPembayaran");
                            if (fileInput.files.length === 0) {
                                return alert("Silakan upload bukti pembayaran.");
                            }

                            const formData = new FormData(document.getElementById("formUploadBukti"));
                            const uploadRes = await fetch(`<?= base_url('Booking/konfirmasiUploadBukti/') ?>${result.id_booking}`, {
                                method: 'POST',
                                body: formData
                            });
                            const uploadResult = await uploadRes.json();

                            if (uploadResult.success) {
                                alert("Upload berhasil! Bukti pembayaran akan diperiksa.");
                                closePopup();
                            } else {
                                alert("Upload gagal: " + uploadResult.message);
                            }
                        }
                    };

                    document.getElementById("btnTidak").onclick = closePopup;

                    function closePopup() {
                        document.getElementById("popupKonfirmasi").style.display = "none";
                        document.getElementById("overlay").style.display = "none";
                        divUpload.style.display = "none";
                        localStorage.clear();
                    }
                });
        });

        // Tombol Konfirmasi Gratis
        document.getElementById("btnKonfirmasiGratis").addEventListener("click", function() {
            const idJadwal = localStorage.getItem("selectedJadwal");
            fetch(`<?= base_url('Booking/ubahStatusSuccess/') ?>${idJadwal}`, {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(() => {
                    alert("Pendaftaran gratis berhasil dikonfirmasi!");
                    document.getElementById("popupGratis").style.display = "none";
                    document.getElementById("popupKonfirmasi").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    localStorage.clear();
                });
        });

        // Tombol Batal Gratis
        document.getElementById("btnBatalGratis").addEventListener("click", function() {
            const idBooking = localStorage.getItem("idBooking");
            fetch(`<?= base_url('Booking/hapusBookingPending/') ?>${idBooking}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(() => {
                    document.getElementById("popupGratis").style.display = "none";
                    document.getElementById("popupKonfirmasi").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    localStorage.clear();
                });
        });
    </script>
</body>

</html>