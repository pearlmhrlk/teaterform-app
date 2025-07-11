<!DOCTYPE html>
<html lang="en">

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
    <!-- Footer end -->

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
    <script src="<?= base_url('assets/js/dropdown-navbar.js') ?>"></script>
    <script src="<?= base_url('assets/js/search-filter-pertunjukan.js') ?>"></script>

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
        const baseUrl = window.location.origin + "/CodeIgniter4/public";

        let idTeater = null;
        let idPenampilan = null;

        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("showPopup"); // ID Popup
            const popupTitle = document.getElementById("popupTitle"); // Judul Popup
            const form = document.getElementById("showForm"); // Form di dalam popup
            const addShowBtn = document.getElementById("addShowBtn"); // Tombol untuk membuka popup
            const cancelBtn = document.getElementById("cancelBtn"); // Tombol batal
            const btn = document.getElementById('addSchedule');

            if (!form) {
                console.error("Form tidak ditemukan!");
                return;
            }

            form.addEventListener("submit", function(e) {
                e.preventDefault(); // Mencegah reload halaman

                let hiddenScheduleInput = document.querySelector('[name="hidden_schedule"]');

                if (!hiddenScheduleInput) {
                    alert("Input tersembunyi untuk jadwal tidak ditemukan.");
                    return;
                }

                // Validasi hanya untuk harga (kategori tidak wajib)
                for (let schedule of currentSchedules) {
                    if (schedule.tipe_harga === "Bayar" && (!schedule.harga || schedule.harga.trim() === "")) {
                        alert("Harga harus diisi untuk jadwal berbayar.");
                        return;
                    }
                }

                // Simpan JSON schedule ke input tersembunyi
                hiddenScheduleInput.value = JSON.stringify(currentSchedules);

                let formData = new FormData(form);
                let actionUrl = form.getAttribute("action");

                for (let schedule of currentSchedules) {
                    const file = uploadedFiles[schedule.id];

                    if (uploadedFiles[schedule.id] instanceof File) {
                        console.log('✅ Upload file:', file.name, 'untuk ID:', schedule.id);
                        formData.append('denah_mapping[]', schedule.id);
                        formData.append('denah_seat[]', file);
                    } else {
                        console.warn('⛔ DILEWATKAN: Jadwal ID', schedule.id, {
                            denah_seat: schedule.denah_seat,
                            file,
                        });
                    }
                }

                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value instanceof File ? value.name : value);
                }

                fetch(actionUrl, {
                        method: "POST",
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            throw new Error(`Server error: ${response.status}`);
                        }

                        const text = await response.text();
                        try {
                            const json = JSON.parse(text);
                            return json;
                        } catch (e) {
                            console.warn("❗Response bukan JSON atau kosong:", text);
                            return {
                                success: false,
                                message: "Respons dari server tidak valid atau kosong.",
                                errors: {}
                            };
                        }
                    })
                    .then(data => {
                        console.log("Server Response:", data);

                        if (data.success && data.redirect) {
                            alert(data.message || "Data berhasil disimpan.");
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || "Gagal menyimpan data.");
                            console.error(data.errors || "Tidak ada detail error.");
                        }
                    })
                    .catch(error => {
                        console.error("Fetch error:", error);
                        alert("Terjadi kesalahan pada server.");
                    });
            });

            // Buka popup "Tambah Pertunjukan"
            addShowBtn.addEventListener("click", function() {
                popupTitle.textContent = "Tambah Pertunjukan";
                form.setAttribute("action", `<?= base_url('MitraTeater/saveShow') ?>`);
                form.reset(); // Bersihkan semua input
                popup.style.display = "flex"; // Tampilkan popup
            });

            // Tombol "Batal" untuk menutup popup dan mereset ID Teater
            cancelBtn.addEventListener("click", function() {
                form.reset(); // Reset semua input dalam form
                idTeater = null; // Hapus nilai ID Teater
                idPenampilan = null;
                popup.style.display = "none"; // Sembunyikan popup
            });

            console.log("Menjalankan script...");

            let scheduleDrafts = []; // Semua jadwal pertunjukan disimpan di sini, termasuk file denah per jadwal

            let tipeHarga = document.getElementById('tipe_harga');
            let nominalHarga = document.getElementById('nominal-harga');
            let seatOption = document.getElementById('seat-option');
            let seatConfig = document.getElementById('seat-config');
            let denahSeat = document.getElementById('denah_seat');
            let draftContainer = document.getElementById('draft-seats');

            let hargaSebelumnya = null;
            let kategoriSebelumnya = []; // Menyimpan kategori sebelumnya
            let savedDrafts = []; // Menyimpan sementara draft kursi saat checkbox diubah
            let denahSebelumnya = null; // Simpan denah sebelum dihapus

            // Tampilkan atau sembunyikan input harga berdasarkan tipe harga
            tipeHarga.addEventListener('change', function() {
                if (this.value === "Bayar") {
                    nominalHarga.style.display = "block";
                    if (hargaSebelumnya !== null) {
                        document.getElementById('harga').value = hargaSebelumnya; // Kembalikan harga sebelumnya
                    }

                    // Tampilkan kembali kategori sebelumnya
                    if (kategoriSebelumnya.length > 0 && draftContainer.children.length === 0) {
                        kategoriSebelumnya.forEach(seat => {
                            let draftItem = createDraftItem(seat.kategori, seat.harga);
                            draftContainer.appendChild(draftItem);
                        });
                    }
                } else {
                    nominalHarga.style.display = "none";
                    hargaSebelumnya = document.getElementById('harga').value || hargaSebelumnya; // Simpan harga terakhir sebelum diubah
                    document.getElementById('harga').value = null; // Kosongkan input harga

                    // Hapus semua draft seat jika Gratis dipilih
                    draftContainer.innerHTML = '';
                    kategoriSebelumnya = []; // Hapus data kategori sebelumnya
                    denahSeat.value = "";
                    denahSeat.removeAttribute('required');
                }
            });

            // Tampilkan atau sembunyikan konfigurasi kursi
            seatOption.addEventListener('change', function() {
                seatConfig.style.display = this.checked ? "block" : "none";

                if (!this.checked) {
                    // Simpan draft kursi sebelum dihapus
                    savedDrafts = [...draftContainer.children];
                    draftContainer.innerHTML = ''; // Hanya kosongkan tampilan, bukan data
                    denahSebelumnya = denahSeat.value; // Simpan denah seat sebelum dihapus
                    denahSeat.removeAttribute('required');
                    denahSeat.value = ""; // Kosongkan denah
                } else {
                    // Kembalikan draft kursi jika sebelumnya sudah ada
                    if (savedDrafts.length > 0) {
                        savedDrafts.forEach(draft => draftContainer.appendChild(draft));
                        savedDrafts = []; // Kosongkan setelah dikembalikan
                    }

                    if (denahSebelumnya && tipeHarga.value !== "Gratis") {
                        denahSeat.value = denahSebelumnya; // Kembalikan denah sebelumnya
                    }
                }
            });

            // Format harga menjadi ribuan (10,000)
            // function formatHarga(input) {
            //     let value = input.value.replace(/\D/g, ''); // Hanya angka
            //     input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            // }

            // document.getElementById('harga').addEventListener('input', function() {
            //     formatHarga(this);
            // });

            // Tambahkan draft kategori
            document.getElementById('addSeatCategory').addEventListener('click', function() {
                const kategoriInput = document.getElementById('nama_kategori');
                const hargaKategoriInput = document.getElementById('harga');
                let kategori = kategoriInput.value.trim();
                let hargaKategori = hargaKategoriInput.value.trim();

                if (kategori === '' || hargaKategori === '') {
                    alert("Harap isi kategori dan harga");
                    return;
                }

                let draftItem = createDraftItem(kategori, hargaKategori);
                draftContainer.appendChild(draftItem);

                kategoriSebelumnya.push({
                    kategori,
                    harga: hargaKategori
                });

                document.getElementById('nama_kategori').value = '';
                document.getElementById('harga').value = '';

                denahSeat.setAttribute('required', 'required');
            });

            function createDraftItem(kategori, harga) {
                let draftItem = document.createElement('div');
                draftItem.classList.add('draft-item');
                draftItem.innerHTML = `
            <span title="${kategori}">${kategori}</span> - 
            <span title="${harga}">${harga}</span>
            <button type="button" class="delete-draft-btn delete-seat-btn">x</button>
        `;

                let hiddenKategori = document.createElement('input');
                hiddenKategori.type = 'hidden';
                hiddenKategori.name = 'seat_kategori[]';
                hiddenKategori.value = kategori;

                let hiddenHarga = document.createElement('input');
                hiddenHarga.type = 'hidden';
                hiddenHarga.name = 'seat_harga[]';
                hiddenHarga.value = harga;

                draftItem.appendChild(hiddenKategori);
                draftItem.appendChild(hiddenHarga);

                draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    draftItem.remove();
                    if (draftContainer.children.length === 0) {
                        denahSeat.removeAttribute('required');
                    }
                });

                return draftItem;
            }

            // Validasi akhir sebelum submit form
            document.querySelector('form').addEventListener('submit', function(event) {
                if (seatOption.checked && draftContainer.children.length > 0 && draftContainer.children.length < 2) {
                    alert("Minimal harus ada dua kategori seat.");
                    event.preventDefault();
                    return;
                }

                if (seatOption.checked && draftContainer.children.length >= 2 && denahSeat.files.length === 0) {
                    alert("Upload denah seat wajib jika menggunakan kategori kursi.");
                    event.preventDefault();
                    return;
                }
            });

            document.getElementById("kota-select")?.addEventListener("change", function() {
                const hiddenKota = document.getElementById("hidden-kota");
                const lainnyaContainer = document.getElementById("lainnya-container");
                const kotaInput = document.getElementById("kota-input");

                if (this.value === "lainnya") {
                    lainnyaContainer.style.display = "block";
                    kotaInput.required = true;
                    kotaInput.focus();
                    hiddenKota.value = kotaInput.value;
                } else {
                    lainnyaContainer.style.display = "none";
                    kotaInput.required = false;
                    kotaInput.value = "";
                    hiddenKota.value = this.value;
                }
            });

            // Update hidden-kota jika user mengetik di input "Lainnya"
            document.getElementById("kota-input")?.addEventListener("input", function() {
                document.getElementById("hidden-kota").value = this.value;
            });

            function resetInputFileDenah() {
                const oldInput = document.getElementById('denah_seat');
                if (!oldInput) return;

                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.accept = 'image/*';
                newInput.id = 'denah_seat';
                newInput.name = 'denah_seat';
                newInput.className = oldInput.className;

                oldInput.replaceWith(newInput);

                // Optional: kalau kamu punya listener upload file, daftarkan lagi di sini
                newInput.addEventListener('change', function(e) {
                    console.log("Denah seat baru dipilih:", e.target.files[0]?.name);
                });
            }

            let currentSchedules = [];
            let uploadedFiles = {};

            document.getElementById('addSchedule').addEventListener('click', function() {
                let tanggal = document.getElementById('tanggal').value;
                let waktuMulai = document.getElementById('waktu_mulai').value;
                let waktuSelesai = document.getElementById('waktu_selesai').value;
                let tipeHarga = document.getElementById('tipe_harga').value;
                let harga = document.getElementById('harga').value.trim();
                let kotaSelect = document.getElementById('kota-select');
                let kotaInput = document.getElementById('kota-input');
                let kota = kotaSelect.value === 'lainnya' && kotaInput ? kotaInput.value : kotaSelect.value; // Update kode
                let tempat = document.getElementById('tempat').value.trim();
                let seatOption = document.getElementById('seat-option').checked;
                let draftContainer = document.getElementById('draft-seats');

                let denahFileInput = document.getElementById('denah_seat');
                let denahSeatFile = denahFileInput && denahFileInput.files[0]; // Ambil file sebenarnya
                let denahSeat = null;

                // Ambil semua kategori seat yang sudah ditambahkan
                let seatDrafts = [];
                document.querySelectorAll('#draft-seats .draft-item').forEach(item => {
                    let kategoriInput = item.querySelector('input[name="seat_kategori[]"]');
                    let hargaInput = item.querySelector('input[name="seat_harga[]"]');

                    console.log("Kategori Input:", kategoriInput);
                    console.log("Harga Input:", hargaInput);

                    if (kategoriInput && hargaInput) { // Pastikan elemen ditemukan sebelum akses
                        let kategori = kategoriInput.value;
                        let harga = hargaInput.value;
                        seatDrafts.push({
                            kategori,
                            harga
                        });
                    }
                });

                console.log("Seat Drafts:", seatDrafts);

                if (tipeHarga === "Gratis") {
                    seatDrafts = [];
                }

                if (!tanggal || !waktuMulai || !waktuSelesai || !kota || !tempat || !tipeHarga) {
                    alert("Mohon lengkapi semua field.");
                    return;
                }

                const scheduleId = Date.now();
                let scheduleItem = document.createElement('div');
                scheduleItem.classList.add('draft-schedule-item');
                scheduleItem.dataset.scheduleId = scheduleId;

                denahFileInput.setAttribute('data-schedule-id', scheduleId);
                let draftIndex = currentSchedules.length;

                if (tipeHarga === "Bayar" && seatOption && seatDrafts.length >= 2) {
                    if (!denahSeatFile) {
                        alert("Denah seat belum diunggah.");
                        return;
                    }

                    // Simpan file ke uploadedFiles berdasarkan id
                    uploadedFiles[scheduleId] = denahSeatFile;
                    console.log("Set uploaded file untuk scheduleId", scheduleId, denahSeatFile);
                    denahSeat = "uploaded";
                    scheduleItem.dataset.denahSeat = denahSeatFile.name;
                }

                // Validasi harga jika memilih "Bayar"
                if (tipeHarga === "Bayar" && !seatOption && seatDrafts.length === 0) {
                    let hargaNominal = parseInt(harga.replace(/,/g, ''), 10);
                    if (!hargaNominal || hargaNominal <= 0) {
                        alert("Harga harus diisi dengan angka yang valid.");
                        return;
                    }
                }

                // Validasi seat kategori: minimal 2 dan harus upload denah
                if (seatOption && seatDrafts.length > 0 && seatDrafts.length < 2) {
                    alert("Minimal harus ada dua kategori seat.");
                    return;
                }

                if (seatOption && seatDrafts.length >= 2 && !denahSeatFile) {
                    alert("Wajib upload denah seat jika menggunakan kategori kursi.");
                    return;
                }

                const existingKey = currentSchedules.find(sch => sch.kota === kota && sch.tempat === tempat);
                if (seatOption && existingKey) {
                    alert("Denah sudah diunggah untuk kombinasi kota dan tempat ini. Hanya satu denah diperbolehkan per lokasi.");
                    return;
                }

                let draftText;
                if (tipeHarga === "Gratis") {
                    draftText = "Gratis";
                } else if (seatDrafts.length > 0) {
                    draftText = seatDrafts.map(seat => `${seat.kategori} - ${seat.harga}`).join(", ");
                } else {
                    draftText = harga;
                }

                const copiedSeatDrafts = [...seatDrafts]; // shallow copy array

                // Simpan data dalam bentuk JSON
                let newSchedule = {
                    id: scheduleId,
                    tanggal: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                    tipe_harga: tipeHarga,
                    nama_kategori: seatDrafts.map(seat => seat.kategori).join(", "), // Simpan nama kategori
                    harga: (tipeHarga === "Gratis") ? "" : (seatDrafts.length > 0 ?
                        seatDrafts.map(seat => seat.harga).join(", ") :
                        harga),
                    kota: kota,
                    tempat: tempat,
                    denah_seat: seatOption ? "uploaded" : null,
                    seatDrafts: copiedSeatDrafts,
                    denah_filename: denahSeatFile?.name || ''
                };

                console.log("Schedule Drafts saat submit:", currentSchedules);

                let hiddenInput = document.querySelector('input[name="hidden_schedule"]');
                currentSchedules.push(newSchedule);
                hiddenInput.value = JSON.stringify(currentSchedules);

                resetInputFileDenah();

                let draftSchedule = document.getElementById('draft-schedule');
                scheduleItem.innerHTML = `
            <p><strong>${newSchedule.tanggal}, ${newSchedule.waktu_mulai} - ${newSchedule.waktu_selesai}</strong></p>
            <p>${draftText}</p>
            <p>${newSchedule.kota} - ${newSchedule.tempat}</p>
            <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
        `;

                scheduleItem.setAttribute('data-draft-index', draftIndex);

                draftSchedule.appendChild(scheduleItem);

                console.log("Draft Schedule Item ditambahkan:", scheduleItem.innerHTML);

                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    const draftIndex = scheduleItem.getAttribute('data-draft-index');
                    const scheduleId = scheduleItem.dataset.scheduleId;
                    const denahSeat = scheduleItem.dataset.denahSeat;

                    // Log sebelum dan sesudah hapus denah
                    console.log("Before delete:", scheduleItem.dataset.denahSeat);
                    delete scheduleItem.dataset.denahSeat;
                    console.log("After delete:", scheduleItem.dataset.denahSeat);

                    // Hapus file denah dari server jika ada
                    if (denahSeat) {
                        fetch(`<?= base_url('MitraTeater/deleteDenah?file=${encodeURIComponent(denahSeat)}') ?>`, {
                                method: 'DELETE'
                            }).then(response => response.json())
                            .then(data => {
                                if (data.status !== 'success') {
                                    console.error("Gagal menghapus file denah di server:", data.message);
                                }
                            }).catch(error => {
                                console.error("Terjadi kesalahan saat menghapus file denah:", error);
                            });
                    }

                    // Hapus item dari tampilan
                    draftSchedule.removeChild(scheduleItem);

                    // Kosongkan tampilan draft container
                    draftContainer.innerHTML = '';

                    // Perbarui data terhidden
                    const updatedSchedules = currentSchedules.filter(s => s.id != scheduleId);
                    hiddenInput.value = JSON.stringify(updatedSchedules);
                    currentSchedules = updatedSchedules;

                    console.log("Updated Hidden Input Value (JSON):", hiddenInput.value);

                    delete uploadedFiles[scheduleId];

                    // Reset input file denah jika tidak ada draft tersisa
                    const inputDenahSeat = document.getElementById('denah_seat');
                    if (draftContainer.children.length === 0 && inputDenahSeat) {
                        inputDenahSeat.removeAttribute('required');
                        inputDenahSeat.value = '';

                        const newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.accept = 'image/*';
                        newInput.id = 'denah_seat';
                        newInput.name = 'denah_seat';
                        newInput.classList = inputDenahSeat.classList;
                        newInput.required = false;

                        // Ganti input lama dengan baru
                        inputDenahSeat.replaceWith(newInput);

                        // Tambahkan kembali event listener upload
                        newInput.addEventListener('change', function(event) {
                            if (event.target.files.length > 0) {
                                const file = event.target.files[0];
                                console.log('File denah dipilih:', file.name);

                                // Bisa tambahkan penanda jika ingin:
                                newInput.setAttribute('data-filename', file.name);
                            }
                        });
                    }
                });
            });

            document.getElementById("showForm").addEventListener("submit", function(event) {
                event.preventDefault(); // Cegah submit default

                let form = event.target;

                // Daftar field yang tidak ingin dikirim (gunakan regex untuk mencocokkan array)
                let fieldsToRemove = ["harga", "nama_kategori", "tanggal", "waktu_mulai", "waktu_selesai", "tipe_harga", "kota", "kota_real", "tempat"];
                let regexPattern = new RegExp(`^(${fieldsToRemove.join("|")})(\\[\\])?$`);

                // Hapus atribut name sebelum FormData dibuat
                document.querySelectorAll("input, select, textarea").forEach(el => {
                    if (regexPattern.test(el.name)) {
                        el.removeAttribute("name"); // Hapus name sebelum pengambilan FormData
                    }
                });
            });

            const periodeCheckbox = document.getElementById('aturPeriodeCheckbox');
            const periodeFields = document.getElementById('periodeManualFields');
            const infoOtomatis = document.getElementById('infoOtomatis');

            function togglePeriodeFields() {
                if (periodeCheckbox.checked) {
                    periodeFields.style.display = 'block';
                    infoOtomatis.style.display = 'none';
                } else {
                    periodeFields.style.display = 'none';
                    infoOtomatis.style.display = 'block';
                }
            }

            // Panggil saat pertama kali halaman dimuat
            togglePeriodeFields();

            // Tambahkan event listener saat checkbox diubah
            periodeCheckbox.addEventListener('change', togglePeriodeFields);

            periodeCheckbox.addEventListener('change', togglePeriodeFields);
            togglePeriodeFields(); // inisialisasi saat halaman dibuka

            // Fetch daftar mitra yang sudah di-approve
            fetch('<?= base_url('teater/getApprovedMitra') ?>')
                .then(response => response.json())
                .then(data => {
                    console.log('Data mitra diterima:', data); // Debugging

                    let selectMitra = document.getElementById('mitra_teater');
                    selectMitra.innerHTML = '<option value="">Pilih Mitra Teater</option>'; // Reset opsi

                    data.forEach(mitra => {
                        let option = document.createElement('option');
                        option.value = mitra.id_mitra;
                        option.textContent = mitra.nama;
                        selectMitra.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching mitra:', error));

            const mitraSelect = document.getElementById('mitra_teater');
            const checkbox = document.getElementById('same-sosmed');
            const draftAccount = document.getElementById('draft-accounts');
            const hiddenAccount = document.querySelector('input[name="hidden_accounts"]');
            const addAccountBtn = document.getElementById('add-account-btn');
            const platformSelect = document.getElementById('platform_name');
            const accountInput = document.getElementById('acc_name');

            let hiddenData = hiddenAccount.value ? JSON.parse(hiddenAccount.value) : [];

            async function copySosmed() {
                if (!checkbox.checked) return;

                const mitraId = mitraSelect.value;
                if (!mitraId) {
                    alert('Pilih mitra terlebih dahulu!');
                    checkbox.checked = false;
                    return;
                }

                try {
                    // Fetch sosial media mitra yang sudah dihubungkan ke sosial media teater
                    const result = await fetch(`<?= base_url('teater/getMitraSosmed') ?>/${mitraId}`);
                    const responseData = await result.json();

                    console.log('Data Sosial Media Mitra yang Terkait:', responseData);

                    if (responseData.status === 'success' && Array.isArray(responseData.data)) {
                        let added = false;

                        responseData.data.forEach(item => {
                            console.log('Memproses sosial media:', item);

                            // Cek apakah sosial media ini sudah ada dalam draft
                            const existing = hiddenData.some(d => d.platformId === item.id_platform_sosmed);
                            if (existing) return;

                            added = true; // Jika ada yang berhasil ditambahkan, ubah menjadi true

                            const draftItem = document.createElement('div');
                            draftItem.classList.add('draft-item');
                            draftItem.setAttribute('data-platform-id', item.id_platform_sosmed);

                            draftItem.innerHTML = `
                            <span title="${item.platform_name}">${item.platform_name}</span> - 
                            <span title="${item.acc_mitra}">${item.acc_mitra}</span>
                            <button type="button" class="delete-draft-btn delete-sosmed-btn">x</button>
                        `;

                            draftAccount.appendChild(draftItem);

                            // ⬇️ Tambahkan data baru TANPA menghapus data lama
                            hiddenData.push({
                                platformId: item.id_platform_sosmed,
                                platformName: item.platform_name,
                                account: item.acc_mitra
                            });

                            // Tambahkan event listener untuk hapus draft
                            draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                                draftItem.remove();
                                hiddenData = hiddenData.filter(d => !(d.account === item.acc_mitra && d.platformName === item.platform_name));
                                hiddenInput.value = JSON.stringify(hiddenData);
                                console.log('Draft Sosial Media Setelah Hapus:', hiddenData); // Debugging
                            });
                        });

                        // ⬇️ Simpan hasil gabungan ke hidden input
                        hiddenInput.value = JSON.stringify(hiddenData);

                        if (!added) {
                            alert('Semua sosial media mitra sudah ada dalam draft.');
                            checkbox.checked = false;
                        }

                        console.log('Draft Sosial Media Keseluruhan (Setelah Copy Mitra):', hiddenData);

                    } else {
                        console.warn('Respons bukan array atau kosong:', responseData);
                        checkbox.checked = false;
                    }
                } catch (error) {
                    console.error('Error fetching mitra sosmed:', error);
                }
            }

            // Event listener untuk checkbox
            checkbox.addEventListener('change', copySosmed);

            addAccountBtn.addEventListener('click', function() {
                const platformId = platformSelect.value;
                const platformName = platformSelect.options[platformSelect.selectedIndex].getAttribute('data-nama');
                const accountName = accountInput.value.trim();

                if (!platformId || !accountName) {
                    alert('Pilih platform dan isi nama akun!');
                    return;
                }

                // Cek apakah akun sudah ada dengan platform yang sama
                const existing = hiddenData.some(d => d.account === accountName && d.platformName === platformName);
                if (existing) {
                    alert('Akun ini sudah ada dalam daftar untuk platform yang sama!');
                    return;
                }

                // Buat draft item baru
                const draftItem = document.createElement('div');
                draftItem.classList.add('draft-item');

                draftItem.innerHTML = `
                <span title="${platformName}">${platformName}</span> - 
                <span title="${accountName}">${accountName}</span>
                <button type="button" class="delete-draft-btn delete-sosmed-btn">x</button>
            `;

                draftAccount.appendChild(draftItem);

                // ⬇️ Tambahkan data baru TANPA menghapus data lama
                hiddenData.push({
                    platformId: platformId,
                    platformName: platformName,
                    account: accountName
                });

                // Simpan hasil gabungan ke hidden input
                hiddenInput.value = JSON.stringify(hiddenData);

                console.log('Draft Sosial Media Keseluruhan (Setelah Tambah Manual):', hiddenData);

                // Tambahkan event listener untuk hapus draft
                draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    draftItem.remove();
                    hiddenData = hiddenData.filter(d => !(d.account === accountName && d.platformName === platformName));
                    hiddenInput.value = JSON.stringify(hiddenData);
                    console.log('Draft Sosial Media Setelah Hapus:', hiddenData);
                });

                // Reset input
                accountInput.value = '';
            });

            document.getElementById('submitBtn').addEventListener('submit', function(event) {
                const hiddenInput = document.querySelector('input[name="hidden_accounts"]');
                console.log('Final Draft Sosial Media (Sebelum Submit):', hiddenInput.value);

                const hiddenData = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                if (hiddenData.length === 0) {
                    alert('Tambahkan setidaknya satu sosial media!');
                    event.preventDefault();
                    return;
                }

                hiddenInput.value = JSON.stringify(hiddenData);
            });

            const addWebButton = document.getElementById('add-web-btn');
            const draftWeb = document.getElementById('draft-web');
            const hiddenInput = document.querySelector('input[name="hidden_web"]');

            function updateHiddenInput() {
                const draftItems = draftWeb.querySelectorAll('.draft-item');
                const data = [];

                draftItems.forEach(item => {
                    const title = item.getAttribute('data-title');
                    const url = item.getAttribute('data-url');
                    data.push({
                        title,
                        url
                    });
                });

                hiddenInput.value = JSON.stringify(data);
                console.log('Updated Hidden Input:', hiddenInput.value);
            }

            if (addWebButton) {
                addWebButton.addEventListener('click', function() {
                    const titleInput = document.querySelector('input[name="judul_web[]"]');
                    const urlInput = document.querySelector('input[name="url_web[]"]');

                    const title = titleInput.value.trim();
                    const url = urlInput.value.trim();

                    // Validasi: Jika salah satu diisi, keduanya harus diisi
                    if ((title !== '' && url === '') || (title === '' && url !== '')) {
                        alert('Harap isi kedua kolom (Judul Web dan URL) atau biarkan keduanya kosong.');
                        return;
                    }

                    // Jika keduanya kosong, tidak menambahkan draft
                    if (title === '' && url === '') return;

                    // Tambahkan item draft ke container draft
                    const draftItem = document.createElement('div');
                    draftItem.classList.add('draft-item');
                    draftItem.setAttribute('data-title', title);
                    draftItem.setAttribute('data-url', url);
                    draftItem.innerHTML = `
                    <span>${title}</span> - 
                    <span>${url}</span>
                    <button type="button" class="delete-draft-btn delete-web-btn">x</button>
                `;

                    draftWeb.appendChild(draftItem);

                    // Perbarui hidden input
                    updateHiddenInput();

                    // Kosongkan input setelah data ditambahkan
                    titleInput.value = '';
                    urlInput.value = '';

                    // Tambahkan listener untuk tombol delete
                    draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                        draftItem.remove();
                        updateHiddenInput(); // Perbarui hidden input setelah menghapus draft
                    });
                });
            }

            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    updateHiddenInput();
                    if (draftWeb.children.length === 0) {
                        hiddenInput.value = '';
                    }
                    console.log('Final Draft Web (Sebelum Submit):', hiddenInput.value);
                });
            }
            once: true
        });

        let hiddenDataEdit = [];

        document.addEventListener("DOMContentLoaded", function() {
            let currentSchedulesEdit = []; // untuk menampung draft jadwal di popup edit
            let deletedSchedules = []; // untuk menyimpan id jadwal yang akan dihapus
            updateDeletedSchedulesInput(); // fungsi untuk update input hidden

            const popupEdit = document.getElementById("editPopup");
            const popupTitleEdit = document.getElementById("popupTitleEdit");
            const formEdit = document.getElementById("editForm");
            const cancelEditBtn = document.getElementById("cancelEditBtn");

            // === SUBMIT FORM PERTUNJUKAN ===
            if (formEdit) {
                formEdit.addEventListener("submit", function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    fetch("<?= base_url('MitraTeater/saveShow') ?>", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                alert("Gagal menyimpan pertunjukan.");
                                console.error(data.errors || "Tidak ada pesan error dari server.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Terjadi kesalahan pada server.");
                        });
                });
            }

            // === TOMBOL EDIT ===
            document.querySelectorAll(".editBtn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const idTeater = this.dataset.id;
                    const idPenampilan = this.dataset.idpenampilan;

                    if (!formEdit) {
                        console.error("Form edit tidak ditemukan!");
                        return;
                    }

                    popupTitleEdit.textContent = "Edit Pertunjukan";
                    popupEdit.style.display = "flex";
                    formEdit.reset();

                    // Tambahkan input hidden id_penampilan (cek dulu apakah sudah ada)
                    if (idPenampilan) {
                        let inputHidden = formEdit.querySelector('input[name="id_penampilan"]');
                        if (!inputHidden) {
                            inputHidden = document.createElement("input");
                            inputHidden.type = "hidden";
                            inputHidden.name = "id_penampilan";
                            formEdit.appendChild(inputHidden);
                        }
                        inputHidden.value = idPenampilan;
                    }

                    fetch(`<?= base_url('MitraTeater/editShow/${idTeater}') ?>`)
                        .then(response => response.json())
                        .then(result => {
                            if (result.status === "success") {
                                const data = result.data;
                                formEdit.reset();
                                resetFormEdit();
                                popupEdit.style.display = "flex";

                                prefillForm(data.teater, data.penampilan, data.jadwal, data.user, data.sosmed, data.websites);
                            } else {
                                alert(result.message || "Gagal mengambil data pertunjukan.");
                                popupEdit.style.display = "none";
                            }
                        })
                        .catch(error => {
                            console.error("Fetch error:", error);
                            alert("Terjadi kesalahan saat mengambil data.");
                            popupEdit.style.display = "none";
                        });

                    // === Prefill data ke form ===
                    function prefillForm(teater, penampilan, jadwal, user, sosmed, websites) {

                        // Hidden inputs
                        resetFormEdit();
                        updateDeletedSchedulesInput();

                        document.querySelector('input[name="id_teater"]').value = teater.id_teater;
                        document.getElementById('tipe_teater_edit').value = 'penampilan';
                        document.getElementById('id_schedule_edit').value = jadwal.id_schedule;
                        document.querySelector('input[name="id_user"]').value = user.id_user;
                        document.querySelector('input[name="id_penampilan"]').value = penampilan.id_penampilan;

                        // Input Teks
                        document.getElementById('judul_edit').value = teater.judul;
                        document.getElementById('sinopsis_edit').value = teater.sinopsis;

                        const tipeHargaSelect = document.getElementById('tipe_harga_edit');
                        const nominalContainer = document.getElementById('nominal-harga-edit');
                        const hargaInput = document.getElementById('harga_edit');
                        const checkboxSeat = document.getElementById('seat-option-edit');
                        const seatConfigContainer = document.getElementById('seat-config-edit');
                        const draftSeatsContainer = document.getElementById('draft-seats-edit');
                        const denahFileInputs = document.getElementById('denah-file-inputs-edit');

                        // Reset tampilan
                        draftSeatsContainer.innerHTML = '';
                        denahFileInputs.innerHTML = '';
                        checkboxSeat.checked = false;
                        seatConfigContainer.style.display = 'none';
                        hargaInput.style.display = 'block';
                        hargaInput.required = false;
                        hargaInput.value = '';

                        // Prefill berdasarkan tipe harga
                        if (jadwal.tipe_harga === 'Bayar') {
                            tipeHargaSelect.value = 'Bayar';
                            nominalContainer.style.display = 'block';

                            const seatDrafts = jadwal.seatDrafts || [];

                            if (seatDrafts.length > 0) {
                                checkboxSeat.checked = true;
                                seatConfigContainer.style.display = 'block';

                                hargaInput.style.display = 'none';
                                hargaInput.required = false;
                                hargaInput.value = '';

                                draftSeatsContainer.innerHTML = '';

                                seatDrafts.forEach((kategori, index) => {
                                    const item = document.createElement('div');
                                    item.classList.add('draft-seat-item');
                                    item.setAttribute('data-nama', kategori.kategori);
                                    item.setAttribute('data-harga', kategori.harga);

                                    const hiddenKategori = document.createElement('input');
                                    hiddenKategori.type = 'hidden';
                                    hiddenKategori.name = 'seat_kategori_edit[]';
                                    hiddenKategori.value = kategori.kategori;

                                    const hiddenHarga = document.createElement('input');
                                    hiddenHarga.type = 'hidden';
                                    hiddenHarga.name = 'seat_harga_edit[]';
                                    hiddenHarga.value = kategori.harga;

                                    item.innerHTML = `
                <strong>${kategori.kategori}</strong> - ${parseInt(kategori.harga).toLocaleString()} 
                <button type="button" class="delete-draft-btn delete-seat-btn">X</button>
            `;

                                    item.appendChild(hiddenKategori);
                                    item.appendChild(hiddenHarga);

                                    item.querySelector('.delete-seat-btn').addEventListener('click', function() {
                                        draftSeatsContainer.removeChild(item);
                                    });

                                    draftSeatsContainer.appendChild(item);
                                });

                                updateHiddenSeatDataEdit();

                            } else {
                                // Tidak pakai kategori
                                checkboxSeat.checked = false;
                                seatConfigContainer.style.display = 'none';

                                hargaInput.style.display = 'block';
                                hargaInput.required = true;
                                hargaInput.value = jadwal.harga || '';
                            }

                        } else {
                            // Gratis
                            tipeHargaSelect.value = 'Gratis';
                            nominalContainer.style.display = 'none';
                            hargaInput.value = '';
                        }

                        const kotaSelect = document.getElementById("kota-select-edit");
                        const kotaInput = document.getElementById("kota-edit");
                        const lainnyaContainer = document.getElementById("lainnya-container-edit");
                        const hiddenKota = document.getElementById("hidden-kota-edit");

                        const jabodetabek = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi'];
                        const kotaDariDB = jadwal.kota?.trim();

                        if (jabodetabek.includes(kotaDariDB)) {
                            // Pilih dari select
                            kotaSelect.value = kotaDariDB;
                            kotaInput.value = '';
                            hiddenKota.value = kotaDariDB;
                            lainnyaContainer.style.display = 'none';
                            kotaInput.required = false;
                        } else {
                            // Pilih "lainnya" dan tampilkan input
                            kotaSelect.value = 'lainnya';
                            kotaInput.value = kotaDariDB || '';
                            hiddenKota.value = kotaDariDB || '';
                            lainnyaContainer.style.display = 'block';
                            kotaInput.required = true;
                        }

                        const draftContainer = document.getElementById('draft-schedule-edit');
                        const hiddenInput = document.getElementById('hidden_schedule_edit');

                        // Kosongkan kontainer & array sebelum isi ulang
                        draftContainer.innerHTML = '';
                        currentSchedulesEdit = [];

                        jadwal.forEach((j) => {
                            const schedule = {
                                tanggal: j.tanggal,
                                waktu_mulai: formatTimeToHHMM(j.waktu_mulai),
                                waktu_selesai: formatTimeToHHMM(j.waktu_selesai),
                                tipe_harga: j.tipe_harga,
                                harga: j.harga || '',
                                nama_kategori: j.nama_kategori || '',
                                kota: j.kota,
                                tempat: j.tempat,
                                denah_seat: j.denah_seat || null,
                                denah_filename: j.denah_filename || '',
                                seatDrafts: j.seatDrafts || [],
                                id: j.id || null
                            };

                            currentSchedulesEdit.push(schedule);

                            const scheduleItem = document.createElement('div');
                            scheduleItem.classList.add('draft-schedule-item');
                            const uniqueId = schedule.id || `${schedule.tanggal}-${schedule.waktu_mulai}-${schedule.tempat}-${schedule.kota}`;
                            scheduleItem.dataset.scheduleId = uniqueId;

                            if (schedule.denah_filename) {
                                scheduleItem.dataset.denahSeat = schedule.denah_filename;
                            }

                            let draftText = '';
                            if (schedule.tipe_harga === 'Gratis') {
                                draftText = 'Gratis';
                            } else if (schedule.seatDrafts.length > 0) {
                                draftText = schedule.seatDrafts
                                    .map(k => `${k.kategori} - ${parseInt(k.harga).toLocaleString()}`)
                                    .join(', ');
                            } else {
                                draftText = parseInt(schedule.harga).toLocaleString();
                            }

                            scheduleItem.innerHTML = `
        <p><strong>${schedule.tanggal}, ${schedule.waktu_mulai} - ${schedule.waktu_selesai}</strong></p>
        <p>${draftText}</p>
        <p>${schedule.kota} - ${schedule.tempat}</p>
        <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
    `;

                            scheduleItem.querySelector('.delete-schedule-btn').addEventListener('click', function() {
                                draftContainer.removeChild(scheduleItem);

                                currentSchedulesEdit = currentSchedulesEdit.filter(item =>
                                    (item.id || `${item.tanggal}-${item.waktu_mulai}-${item.tempat}-${item.kota}`) !== uniqueId
                                );

                                hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                            });

                            draftContainer.appendChild(scheduleItem);
                        });

                        // Simpan ke input tersembunyi
                        hiddenInput.value = JSON.stringify(currentSchedulesEdit);

                        document.getElementById('penulis_edit').value = teater.penulis;
                        document.getElementById('url_pendaftaran_edit').value = teater.url_pendaftaran;
                        document.getElementById('sutradara_edit').value = teater.sutradara;
                        document.getElementById('staff_edit').value = teater.staff;
                        document.getElementById('aktor_edit').value = penampilan.aktor;

                        //Dropdown Mitra Teater (set selected option)
                        const mitraSelect = document.getElementById('mitra_teater_edit');
                        if (mitraSelect) {
                            for (const option of mitraSelect.options) {
                                if (parseInt(option.value) === parseInt(penampilan.id_mitra)) {
                                    option.selected = true;
                                    break;
                                }
                            }
                        }

                        if (penampilan.sosmed && Array.isArray(penampilan.sosmed)) {
                            const hiddenInput = document.querySelector('input[name="hidden_accounts_edit"]');

                            // Konversi struktur agar cocok dengan script luar
                            const formattedSosmed = penampilan.sosmed.map(item => ({
                                platformId: item.id_platform_sosmed,
                                platformName: item.platform_name,
                                account: item.acc_teater
                            }));

                            hiddenInput.value = JSON.stringify(formattedSosmed);
                        }

                        // Untuk checkbox "Sama dengan Mitra Komunitas"
                        if (penampilan.accounts_komunitas) {
                            window.audisiGlobal = {
                                accounts_komunitas: penampilan.accounts_komunitas
                            };
                        }

                        if (penampilan.websites && Array.isArray(penampilan.websites)) {
                            hiddenWebEdit = penampilan.websites.map(item => ({
                                id: item.id || null,
                                title: item.title,
                                url: item.url
                            }));

                            renderWebDrafts();
                        }

                        togglePeriodeFieldsEdit(); // Panggil agar sinkron
                    }
                });
            });

            function resetFormEdit() {
                // Bersihkan draft schedule container & reset array jadwal
                const draftContainer = document.getElementById('draft-schedule-edit');
                if (draftContainer) draftContainer.innerHTML = '';

                if (typeof currentSchedulesEdit !== 'undefined') currentSchedulesEdit.length = 0;
                if (typeof deletedSchedules !== 'undefined') deletedSchedules.length = 0;

                const hiddenScheduleInput = document.getElementById('hidden_schedule_edit');
                if (hiddenScheduleInput) hiddenScheduleInput.value = '';

                const deletedSchedulesInput = document.getElementById('deleted_schedules_edit');
                if (deletedSchedulesInput) deletedSchedulesInput.value = '';

                // Reset tipe harga & nominal umum
                const tipeHargaSelect = document.getElementById('tipe_harga_edit');
                const nominalContainer = document.getElementById('nominal-harga-edit');
                const hargaInput = document.getElementById('harga_edit');
                if (tipeHargaSelect) tipeHargaSelect.value = 'Gratis';
                if (nominalContainer) nominalContainer.style.display = 'none';
                if (hargaInput) hargaInput.value = '';

                // Reset seat/denah
                const draftSeatContainer = document.getElementById('draft-seat-container-edit');
                if (draftSeatContainer) draftSeatContainer.innerHTML = '';

                const denahInput = document.getElementById('denah_seat'); // pakai ID sesuai script awal
                if (denahInput) denahInput.value = '';

                const hiddenDenahEdit = document.getElementById('hidden_denah_edit');
                if (hiddenDenahEdit) hiddenDenahEdit.value = '';

                const seatOption = document.getElementById('atur-seat-edit');
                if (seatOption) seatOption.checked = false;

                const seatConfig = document.getElementById('seat-config-edit');
                if (seatConfig) seatConfig.style.display = 'none';

                if (typeof kategoriSebelumnya !== 'undefined') kategoriSebelumnya = [];
                if (typeof denahSebelumnya !== 'undefined') denahSebelumnya = '';

                // Reset kota
                const kotaSelect = document.getElementById('kota-select-edit');
                const kotaInput = document.getElementById('kota-input-edit');
                const lainnyaContainer = document.getElementById('lainnya-container-edit');
                const hiddenKota = document.getElementById('hidden-kota-edit');
                if (kotaSelect) kotaSelect.value = '';
                if (kotaInput) {
                    kotaInput.value = '';
                    kotaInput.required = false;
                }
                if (lainnyaContainer) lainnyaContainer.style.display = 'none';
                if (hiddenKota) hiddenKota.value = '';

                // Reset sosial media (sosmed)
                const draftAccountEdit = document.getElementById('draft-accounts-edit');
                if (draftAccountEdit) draftAccountEdit.innerHTML = '';

                const hiddenAccountEdit = document.querySelector('input[name="hidden_accounts_edit"]');
                if (hiddenAccountEdit) hiddenAccountEdit.value = '';

                if (typeof hiddenDataEdit !== 'undefined') hiddenDataEdit = [];

                const checkboxEdit = document.getElementById('same-sosmed-edit');
                if (checkboxEdit) checkboxEdit.checked = false;

                // Reset data website
                const draftWebEdit = document.getElementById('draft-web-edit');
                if (draftWebEdit) draftWebEdit.innerHTML = '';

                const hiddenInputWeb = document.getElementById('hidden_web_edit');
                if (hiddenInputWeb) hiddenInputWeb.value = '';

                const deletedInputWeb = document.getElementById('deleted_webs');
                if (deletedInputWeb) deletedInputWeb.value = '';

                if (typeof hiddenWebEdit !== 'undefined') hiddenWebEdit = [];
                if (typeof deletedWebs !== 'undefined') deletedWebs = [];

                const judulInput = document.getElementById('judul_web_edit');
                const urlInput = document.getElementById('url_web_edit');
                if (judulInput) judulInput.value = '';
                if (urlInput) urlInput.value = '';

                // Reset checkbox periode & field tanggal
                const periodeCheckboxEdit = document.getElementById('aturPeriodeCheckboxAktorEdit')
                if (periodeCheckboxEdit) periodeCheckboxEdit.checked = false;
                togglePeriodeFieldsEdit(); // fungsi ini akan reset tampilan tanggal
            }

            if (cancelEditBtn) {
                cancelEditBtn.addEventListener("click", function() {
                    formEdit.reset();
                    popupEdit.style.display = "none";
                    resetFormEdit(); // fungsi reset yang sudah kamu buat
                });
            }

            function updateDeletedSchedulesInput() {
                document.getElementById('deleted_schedules_edit').value = JSON.stringify(deletedSchedules);
            }

            function formatTimeToHHMM(timeStr) {
                return timeStr ? timeStr.slice(0, 5) : "";
            }

            function updateHiddenSeatDataEdit() {
                const items = draftSeatsContainer.querySelectorAll('.draft-seat-item');
                const kategoriHidden = document.querySelectorAll('input[name="seat_kategori_edit[]"]');
                const hargaHidden = document.querySelectorAll('input[name="seat_harga_edit[]"]');

                // Hapus semua hidden lama
                kategoriHidden.forEach(el => el.remove());
                hargaHidden.forEach(el => el.remove());

                items.forEach(item => {
                    const kategori = item.getAttribute('data-nama');
                    const harga = item.getAttribute('data-harga');

                    const hiddenKategori = document.createElement('input');
                    hiddenKategori.type = 'hidden';
                    hiddenKategori.name = 'seat_kategori_edit[]';
                    hiddenKategori.value = kategori;

                    const hiddenHarga = document.createElement('input');
                    hiddenHarga.type = 'hidden';
                    hiddenHarga.name = 'seat_harga_edit[]';
                    hiddenHarga.value = harga;

                    item.appendChild(hiddenKategori);
                    item.appendChild(hiddenHarga);
                });
            }

            // Jika form adalah mode edit
            let isEditMode = document.getElementById('edit-mode-flag'); // misalnya input hidden
            if (isEditMode && isEditMode.value === "1") {

                let kategoriData = document.getElementById('prefill_seat_kategori');
                let denahInput = document.getElementById('denah_seat');
                let denahHidden = document.getElementById('hidden_denah_edit');
                let tipeHargaSelect = document.getElementById('tipe_harga_edit');
                let nominalHarga = document.getElementById('nominal-harga-edit');

                try {
                    const prefillKategoris = JSON.parse(kategoriData.value || '[]');
                    if (prefillKategoris.length > 0) {
                        seatOption.checked = true;
                        seatConfig.style.display = "block";
                        tipeHargaSelect.value = "Bayar";
                        nominalHarga.style.display = "block";
                        denahInput.setAttribute('required', 'required');

                        prefillKategoris.forEach(item => {
                            let draftItem = createDraftItem(item.kategori, item.harga);
                            draftContainer.appendChild(draftItem);
                            kategoriSebelumnya.push({
                                kategori: item.kategori,
                                harga: item.harga
                            });
                        });

                        // Jika ada denah seat sebelumnya
                        if (denahHidden && denahHidden.value) {
                            denahSebelumnya = denahHidden.value;
                            denahInput.value = denahSebelumnya;
                        }
                    } else {
                        // Jika tidak ada kategori, asumsikan 'Gratis'
                        tipeHargaSelect.value = "Gratis";
                        nominalHarga.style.display = "none";
                        denahInput.removeAttribute('required');
                    }
                } catch (e) {
                    console.error('Gagal parsing kategori kursi edit:', e);
                }
            }

            // === Mekanisme Kota Aktor Edit ===
            document.getElementById("kota-select-edit")?.addEventListener("change", function() {
                const kotaInput = document.getElementById("kota-edit");
                const hiddenKota = document.getElementById("hidden-kota-edit");
                const lainnyaContainer = document.getElementById("lainnya-container-edit");

                if (this.value === "lainnya") {
                    lainnyaContainer.style.display = "block";
                    kotaInput.required = true;
                    kotaInput.focus();
                    hiddenKota.value = kotaInput.value;
                } else {
                    lainnyaContainer.style.display = "none";
                    kotaInput.required = false;
                    kotaInput.value = "";
                    hiddenKota.value = this.value;
                }
            });

            document.getElementById("kota-edit").addEventListener("input", function() {
                document.getElementById("hidden-kota-edit").value = this.value;
            });

            // Versi Edit: Mirip penambahan, tapi pakai hidden_schedule_edit
            let uploadedFilesEdit = {};

            // Fungsi inisialisasi data dari hidden input saat form dimuat
            window.initializeEditSchedules = function() {
                const hiddenInput = document.querySelector('input[name="hidden_schedule_edit"]');
                if (!hiddenInput || !hiddenInput.value) return;

                try {
                    currentSchedulesEdit = JSON.parse(hiddenInput.value);
                    const draftSchedule = document.getElementById('draft-schedule-edit');

                    currentSchedulesEdit.forEach((schedule, index) => {
                        const scheduleItem = document.createElement('div');
                        scheduleItem.classList.add('draft-schedule-item');
                        scheduleItem.dataset.scheduleId = schedule.id;
                        scheduleItem.setAttribute('data-draft-index', index);

                        if (schedule.denah_filename) {
                            scheduleItem.dataset.denahSeat = schedule.denah_filename;
                        }

                        const draftText = schedule.tipe_harga === 'Gratis' ? 'Gratis' :
                            schedule.seatDrafts && schedule.seatDrafts.length > 0 ?
                            schedule.seatDrafts.map(seat => `${seat.kategori} - ${seat.harga}`).join(", ") :
                            schedule.harga;

                        scheduleItem.innerHTML = `
                <p><strong>${schedule.tanggal}, ${schedule.waktu_mulai} - ${schedule.waktu_selesai}</strong></p>
                <p>${draftText}</p>
                <p>${schedule.kota} - ${schedule.tempat}</p>
                <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
            `;

                        // Tombol hapus
                        scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                            const scheduleId = scheduleItem.dataset.scheduleId;
                            const denahSeat = scheduleItem.dataset.denahSeat;

                            if (denahSeat) {
                                fetch(`<?= base_url('MitraTeater/deleteDenah?file=${encodeURIComponent(denahSeat)}') ?>`, {
                                        method: 'DELETE'
                                    }).then(response => response.json())
                                    .then(data => {
                                        if (data.status !== 'success') {
                                            console.error("Gagal menghapus file denah:", data.message);
                                        }
                                    }).catch(err => console.error("Error hapus file denah:", err));
                            }

                            draftSchedule.removeChild(scheduleItem);
                            currentSchedulesEdit = currentSchedulesEdit.filter(s => s.id != scheduleId);
                            hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                            delete uploadedFilesEdit[scheduleId];
                        });

                        draftSchedule.appendChild(scheduleItem);
                    });
                } catch (e) {
                    console.error("Gagal parsing hidden_schedule_edit:", e);
                }
            };

            initializeEditSchedules();

            const draftAccountEdit = document.getElementById('draft-accounts-edit');
            const hiddenAccountEdit = document.querySelector('input[name="hidden_accounts_edit"]');
            const checkboxEdit = document.getElementById('same-sosmed-edit');
            const addAccountBtn = document.getElementById('add-account-btn');
            const platformSelect = document.getElementById('platform_name');
            const accountInput = document.getElementById('acc_name');

            // Ambil data awal dari hidden input (saat prefill edit)
            if (hiddenAccountEdit && hiddenAccountEdit.value) {
                try {
                    hiddenDataEdit = JSON.parse(hiddenAccountEdit.value);

                    hiddenDataEdit.forEach(item => {
                        const draftItem = document.createElement('div');
                        draftItem.classList.add('draft-item');
                        draftItem.setAttribute('data-platform-id', item.platformId);

                        draftItem.innerHTML = `
                    <span title="${item.platformName}">${item.platformName}</span> - 
                    <span title="${item.account}">${item.account}</span>
                    <button type="button" class="delete-draft-btn delete-sosmed-btn">x</button>
                `;

                        draftAccountEdit.appendChild(draftItem);

                        draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                            draftItem.remove();
                            hiddenDataEdit = hiddenDataEdit.filter(d => !(d.account === item.account && d.platformName === item.platformName));
                            hiddenAccountEdit.value = JSON.stringify(hiddenDataEdit);
                        });
                    });

                } catch (e) {
                    console.error('Gagal parsing data sosmed edit:', e);
                }
            }

            // Checkbox "Sama dengan Mitra Komunitas"
            checkboxEdit.addEventListener('change', function() {
                if (!checkboxEdit.checked) return;

                if (!audisiGlobal || !audisiGlobal.accounts_komunitas) {
                    alert('Data sosial media mitra tidak ditemukan.');
                    checkboxEdit.checked = false;
                    return;
                }

                let added = false;

                audisiGlobal.accounts_komunitas.forEach(item => {
                    const exists = hiddenDataEdit.some(d => d.platformId === item.platformId);
                    if (exists) return;

                    added = true;

                    const draftItem = document.createElement('div');
                    draftItem.classList.add('draft-item');
                    draftItem.setAttribute('data-platform-id', item.platformId);

                    draftItem.innerHTML = `
                <span title="${item.platformName}">${item.platformName}</span> - 
                <span title="${item.account}">${item.account}</span>
                <button type="button" class="delete-draft-btn delete-sosmed-btn">x</button>
            `;

                    draftAccountEdit.appendChild(draftItem);

                    hiddenDataEdit.push({
                        platformId: item.platformId,
                        platformName: item.platformName,
                        account: item.account
                    });

                    draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                        draftItem.remove();
                        hiddenDataEdit = hiddenDataEdit.filter(d => !(d.account === item.account && d.platformName === item.platformName));
                        hiddenAccountEdit.value = JSON.stringify(hiddenDataEdit);
                    });
                });

                hiddenAccountEdit.value = JSON.stringify(hiddenDataEdit);

                if (!added) {
                    alert('Semua sosial media mitra sudah ada dalam draft.');
                    checkboxEdit.checked = false;
                }
            });

            // Tombol tambah akun manual
            addAccountBtn.addEventListener('click', function() {
                const platformId = platformSelect.value;
                const platformName = platformSelect.options[platformSelect.selectedIndex].getAttribute('data-nama');
                const accountName = accountInput.value.trim();

                if (!platformId || !accountName) {
                    alert('Pilih platform dan isi nama akun!');
                    return;
                }

                const exists = hiddenDataEdit.some(d => d.account === accountName && d.platformName === platformName);
                if (exists) {
                    alert('Akun ini sudah ada dalam daftar untuk platform yang sama!');
                    return;
                }

                const draftItem = document.createElement('div');
                draftItem.classList.add('draft-item');
                draftItem.setAttribute('data-platform-id', platformId);

                draftItem.innerHTML = `
            <span title="${platformName}">${platformName}</span> - 
            <span title="${accountName}">${accountName}</span>
            <button type="button" class="delete-draft-btn delete-sosmed-btn">x</button>
        `;

                draftAccountEdit.appendChild(draftItem);

                hiddenDataEdit.push({
                    platformId: platformId,
                    platformName: platformName,
                    account: accountName
                });

                hiddenAccountEdit.value = JSON.stringify(hiddenDataEdit);

                draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    draftItem.remove();
                    hiddenDataEdit = hiddenDataEdit.filter(d => !(d.account === accountName && d.platformName === platformName));
                    hiddenAccountEdit.value = JSON.stringify(hiddenDataEdit);
                });

                accountInput.value = '';
            });

            let hiddenWebEdit = [];
            let deletedWebs = [];

            const draftWebEdit = document.getElementById('draft-web-edit');
            const hiddenInputWeb = document.getElementById('hidden_web_edit');
            const deletedInputWeb = document.getElementById('deleted_webs');
            const addWebBtn = document.getElementById('add-web-btn-edit');
            const judulInput = document.getElementById('judul_web_edit');
            const urlInput = document.getElementById('url_web_edit');

            // Fungsi render ulang
            function renderWebDrafts() {
                draftWebEdit.innerHTML = '';
                hiddenWebEdit.forEach((item, index) => {
                    const draftItem = document.createElement('div');
                    draftItem.classList.add('draft-item');
                    draftItem.innerHTML = `
            <span title="${item.title}">${item.title}</span> - 
            <a href="${item.url}" target="_blank">${item.url}</a>
            <button type="button" class="delete-draft-btn delete-web-btn">x</button>
        `;

                    draftItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                        // Simpan ke deleted jika ada ID dari DB
                        if (item.id) {
                            deletedWebs.push(item.id);
                            deletedInputWeb.value = JSON.stringify(deletedWebs);
                        }

                        // Hapus dari array dan DOM
                        hiddenWebEdit.splice(index, 1);
                        renderWebDrafts(); // render ulang
                    });

                    draftWebEdit.appendChild(draftItem);
                });

                hiddenInputWeb.value = JSON.stringify(hiddenWebEdit);
            }

            // Tombol tambah
            addWebBtn.addEventListener('click', function() {
                const title = judulInput.value.trim();
                const url = urlInput.value.trim();

                if (!title || !url) {
                    alert('Harap isi judul dan URL web!');
                    return;
                }

                const exists = hiddenWebEdit.some(d => d.title === title && d.url === url);
                if (exists) {
                    alert('Website ini sudah ada dalam daftar!');
                    return;
                }

                hiddenWebEdit.push({
                    title,
                    url
                }); // ID tidak ada karena baru ditambah
                renderWebDrafts();

                // Reset input
                judulInput.value = '';
                urlInput.value = '';
            });

            function togglePeriodeFieldsEdit() {
                const periodeCheckboxEdit = document.getElementById('aturPeriodeCheckboxEdit');
                const periodeFieldsEdit = document.getElementById('periodeManualFieldsEdit');
                const infoOtomatisEdit = document.getElementById('infoOtomatisEdit');

                if (periodeCheckboxEdit.checked) {
                    periodeFieldsEdit.style.display = 'block';
                    infoOtomatisEdit.style.display = 'none';
                } else {
                    periodeFieldsEdit.style.display = 'none';
                    infoOtomatisEdit.style.display = 'block';
                }

                // Event listener saat checkbox edit diubah
                periodeCheckboxEdit.addEventListener('change', togglePeriodeFieldsEdit);
            }

            // Panggil saat pertama kali modal edit ditampilkan
            togglePeriodeFieldsEdit();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("popupTiketTerjual");
            const closeBtn = modal.querySelector(".closePopup");

            // Tombol buka modal
            document.querySelectorAll(".openPopupTiketTerjual").forEach(button => {
                button.addEventListener("click", function() {
                    const showId = button.getAttribute("data-id");
                    const tipe = button.getAttribute("data-tipe");

                    modal.style.display = "flex"; // tampilkan modal
                    fetchData(showId, tipe);
                });
            });

            // Tombol tutup
            closeBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Tutup modal jika klik di luar kontennya
            modal.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });

            function fetchData(showId, tipe) {
                const tbody = document.querySelector("#tableBookingList tbody");
                const totalEl = document.getElementById("totalTiketTerjual");

                tbody.innerHTML = '<tr><td colspan="7" class="text-center">Memuat data...</td></tr>';
                totalEl.textContent = "";

                fetch(`get-booking/${tipe}/${showId}`)
                    .then(res => res.json())
                    .then(result => {
                        if (!result || !result.data) throw new Error("Format data salah");

                        const data = result.data;
                        const count = result.tiket_terjual ?? 0;

                        if (data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada audiens yang mendaftar.</td></tr>';
                        } else {
                            tbody.innerHTML = '';
                            data.forEach(row => {
                                tbody.innerHTML += `
                            <tr>
                                <td>${row.nama}</td>
                                <td>${row.email}</td>
                                <td>${row.tanggal_lahir}</td>
                                <td>${row.jenis_kelamin}</td>
                                <td>${row.status}</td>
                                <td>${row.bukti_pembayaran}</td>
                                <td>${row.tanggal_daftar}</td>
                            </tr>`;
                            });
                        }

                        totalEl.textContent = `Total Tiket Terjual: ${count}`;
                    })
                    .catch(err => {
                        console.error(err);
                        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>';
                    });
            }
        });
    </script>

    </script>
</body>

</html>