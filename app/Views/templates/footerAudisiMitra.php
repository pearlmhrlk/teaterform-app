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

    <!-- back to top area start -->
    <div class="back-to-top">
        <span class="back-top"><i class="fa fa-angle-up"></i></span>
    </div>
    <!-- back to top area end -->

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let categorySelect = document.getElementById("searchCategory");
            let inputContainer = document.getElementById("searchInputContainer");

            function updateSearchInput(selected) {
                let newInput = "";

                if (selected === "kategori") {
                    newInput = `
                            <select class="form-select w-50" id="searchInput">
                                <option value="">Pilih Kategori</option>
                                <option value="Aktor">Aktor</option>
                                <option value="Staff">Staff</option>
                            </select>
                        `;
                } else if (selected === "tanggal") {
                    newInput = `<input type="date" class="form-control w-50" id="searchInput">`;
                } else if (selected === "waktu") {
                    newInput = `<input type="time" class="form-control w-50" id="searchInput">`;
                } else if (selected === "kota") {
                    newInput = `
                            <select class="form-select w-50" id="searchInput">
                                <option value="">Pilih Kota</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Surabaya">Surabaya</option>
                            </select>
                        `;
                } else if (selected === "harga") {
                    newInput = `
                            <div class="d-flex gap-2">
                                <input type="number" class="form-control w-25" id="minHarga" placeholder="Min Harga">
                                <input type="number" class="form-control w-25" id="maxHarga" placeholder="Max Harga">
                            </div>
                        `;
                } else if (selected === "gaji") {
                    newInput = `
                            <div class="d-flex gap-2">
                                <input type="number" class="form-control w-25" id="minGaji" placeholder="Min Gaji">
                                <input type="number" class="form-control w-25" id="maxGaji" placeholder="Max Gaji">
                            </div>
                        `;
                } else {
                    // Default: input teks biasa
                    newInput = `<input type="text" class="form-control w-50" id="searchInput" placeholder="Cari...">`;
                }

                // Ganti isi dari inputContainer
                inputContainer.innerHTML = newInput;
            }

            // Jalankan perubahan saat dropdown berubah
            categorySelect.addEventListener("change", function() {
                updateSearchInput(this.value);
            });

            // Set default input pertama kali
            updateSearchInput(categorySelect.value);
        });
    </script>

    <!-- PopUp Aktor -->
    <script>
        // Buka popup "Tambah Audisi Aktor"
        const baseUrl = window.location.origin + "/CodeIgniter4/public";

        let idTeater = null; // Simpan secara global
        let idAudisi = null;

        document.addEventListener("DOMContentLoaded", function() {
            const popupAktor = document.getElementById("auditionPopupAktor"); // ID Popup
            const popupTitleAktor = document.getElementById("popupTitleAktor"); // Judul Popup
            const formAktor = document.getElementById("auditionFormAktor"); // Form di dalam popup
            const addAuditionActorBtn = document.getElementById("addAuditionActorBtn"); // Tombol untuk membuka popup
            const cancelBtnAktor = document.getElementById("cancelBtnAktor"); // Tombol batal

            if (!formAktor) {
                console.error("Form tidak ditemukan!");
                return;
            }

            // Fungsi untuk mengirim form via AJAX dan mendapatkan id_teater
            formAktor.addEventListener("submit", function(e) {
                e.preventDefault(); // Mencegah reload halaman

                let formData = new FormData(this);

                // Debug isi formData
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }

                fetch("<?= base_url('MitraTeater/saveAuditionAktor') ?>", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server Response:", data); // Debug hasil dari server

                        if (data.success) {
                            alert(data.message);
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        } else {
                            alert("Gagal menyimpan audisi.");
                            console.error(data.errors || "Tidak ada pesan error dari server.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan pada server.");
                    });

                console.log("Form berhasil disubmit via JS!");
            });

            // Buka popup "Tambah Pertunjukan"
            document.getElementById("addAuditionActorBtn").addEventListener("click", () => {
                popupTitleAktor.textContent = "Tambah Audisi Aktor";
                formAktor.reset();
                document.getElementById("id_kategori_aktor").value = "1"; // Pastikan kategori terisi
                popupAktor.style.display = "flex";
            });

            // Tombol "Batal" untuk menutup popup dan mereset ID Teater
            cancelBtnAktor.addEventListener("click", function() {
                formAktor.reset(); // Reset semua input dalam form
                idTeater = null; // Hapus nilai ID Teater
                idAudisi = null;
                popupAktor.style.display = "none"; // Sembunyikan popup
            });

            const allCharaCheckbox = document.getElementById('all-chara');
            const aktorInputSection = document.getElementById('aktor-input-section');
            const karakterInput = document.getElementById('karakter_audisi');
            const deskripsiKarakterWrapper = document.getElementById('deskripsi-karakter-wrapper');
            const deskripsiKarakter = document.getElementById('deskripsi_karakter');

            // Validasi elemen penting
            if (!allCharaCheckbox || !aktorInputSection || !karakterInput || !formAktor) {
                console.warn('Beberapa elemen penting tidak ditemukan.');
                return;
            }

            // Fungsi update tampilan
            function updateCharacterOptions(event) {

                const isAllCharaChecked = allCharaCheckbox.checked;

                if (isAllCharaChecked) {
                    karakterInput.disabled = true;
                    karakterInput.placeholder = '';
                    karakterInput.value = '';
                    karakterInput.classList.add('input-disabled');

                    deskripsiKarakter.disabled = true;
                    deskripsiKarakter.placeholder = '';
                    deskripsiKarakter.classList.add('input-disabled');
                } else {
                    karakterInput.disabled = false;
                    karakterInput.placeholder = 'Masukkan nama karakter';
                    karakterInput.classList.remove('input-disabled');

                    deskripsiKarakter.disabled = false;
                    deskripsiKarakter.placeholder = 'Masukkan deskripsi karakter yang diaudisikan';
                    deskripsiKarakter.classList.remove('input-disabled');
                }
            }

            // Event change
            allCharaCheckbox.addEventListener('change', (e) => updateCharacterOptions(e));
            updateCharacterOptions();

            let tipeHarga = document.getElementById('tipe_harga_aktor');
            let nominalHarga = document.getElementById('nominal-harga-aktor');
            let hargaSebelumnya = null;

            // Tampilkan atau sembunyikan input harga berdasarkan tipe harga
            tipeHarga.addEventListener('change', function() {
                if (this.value === "Bayar") {
                    nominalHarga.style.display = "block";
                    if (hargaSebelumnya !== null) {
                        document.getElementById('harga_aktor').value = hargaSebelumnya; // Kembalikan harga sebelumnya
                    }
                } else {
                    nominalHarga.style.display = "none";
                    hargaSebelumnya = document.getElementById('harga_aktor').value || hargaSebelumnya; // Simpan harga terakhir sebelum diubah
                    document.getElementById('harga_aktor').value = "";
                    // Kosongkan input harga
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

            document.getElementById("kota-select-aktor")?.addEventListener("change", function() {
                const hiddenKota = document.getElementById("hidden-kota-aktor");
                const lainnyaContainer = document.getElementById("lainnya-container-aktor");
                const kotaInput = document.getElementById("kota-input-aktor");

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
            document.getElementById("kota-input-aktor")?.addEventListener("input", function() {
                document.getElementById("hidden-kota-aktor").value = this.value;
            });

            let currentSchedules = [];

            document.getElementById('addScheduleAktor').addEventListener('click', function() {
                let tanggal = document.getElementById('tanggal_aktor').value;
                let waktuMulai = document.getElementById('waktu_mulai_aktor').value;
                let waktuSelesai = document.getElementById('waktu_selesai_aktor').value;
                let tipeHarga = document.getElementById('tipe_harga_aktor').value;
                let harga = document.getElementById('harga_aktor').value.trim();
                let kotaSelect = document.getElementById('kota-select-aktor');
                let kotaInput = document.getElementById('kota-input-aktor');
                let kota = kotaSelect.value === 'lainnya' && kotaInput ? kotaInput.value : kotaSelect.value; // Update kode
                let tempat = document.getElementById('tempat_aktor').value.trim();

                if (!tanggal || !waktuMulai || !waktuSelesai || !kota || !tempat || !tipeHarga) {
                    alert("Mohon lengkapi semua field.");
                    return;
                }

                let scheduleItem = document.createElement('div');
                scheduleItem.classList.add('draft-schedule-item');

                // Validasi harga jika memilih "Bayar"
                if (tipeHarga === "Bayar") {
                    let hargaNominal = parseInt(harga.replace(/,/g, ''), 10);
                    if (!hargaNominal || hargaNominal <= 0) {
                        alert("Harga harus diisi dengan angka yang valid.");
                        return;
                    }
                }

                // Simpan data dalam bentuk JSON
                let newSchedule = {
                    tanggal: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                    tipe_harga: tipeHarga,
                    harga: (tipeHarga === "Gratis" || !harga) ? null : harga,
                    kota: kota,
                    tempat: tempat
                };

                let hiddenInput = document.getElementById('hidden_schedule_aktor');
                currentSchedules = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];
                currentSchedules.push(newSchedule);
                hiddenInput.value = JSON.stringify(currentSchedules);

                let draftSchedule = document.getElementById('draft-schedule-aktor');
                scheduleItem.innerHTML = `
            <p><strong>${newSchedule.tanggal}, ${newSchedule.waktu_mulai} - ${newSchedule.waktu_selesai}</strong></p>
            <p>${newSchedule.kota} - ${newSchedule.tempat}</p>
            <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
        `;

                let draftIndex = currentSchedules.length - 1;
                scheduleItem.setAttribute('data-draft-index', draftIndex);

                draftSchedule.appendChild(scheduleItem);

                console.log("Draft Schedule Item ditambahkan:", scheduleItem.innerHTML);

                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    let draftIndex = scheduleItem.getAttribute('data-draft-index');

                    // Hapus item draft dari tampilan
                    draftSchedule.removeChild(scheduleItem);

                    let updatedSchedules = currentSchedules.filter(schedule => {
                        let expectedHarga = (tipeHarga === "Gratis") ? null : parseInt(harga.replace(/,/g, ''), 10);
                        let isSameHarga = (tipeHarga === "Gratis") ?
                            (schedule.harga === null || schedule.harga === "") :
                            (parseInt(schedule.harga?.replace(/,/g, '') || 0, 10) === expectedHarga);

                        return !(
                            schedule.tanggal === tanggal &&
                            schedule.waktu_mulai === waktuMulai &&
                            schedule.waktu_selesai === waktuSelesai &&
                            schedule.tipe_harga === tipeHarga &&
                            isSameHarga &&
                            schedule.kota === kota &&
                            schedule.tempat === tempat
                        );
                    });

                    hiddenInput.value = JSON.stringify(updatedSchedules);
                    console.log("Updated Hidden Input Value (JSON):", hiddenInput.value);
                });
            });

            document.getElementById("auditionFormAktor").addEventListener("submit", function(event) {
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

            const gajiRahasia = document.getElementById('gaji_dirahasiakan_aktor');
            const gajiInput = document.getElementById('gaji_aktor');

            gajiRahasia.addEventListener('change', function() {
                const isChecked = this.checked;
                gajiInput.disabled = isChecked;

                if (isChecked) {
                    gajiInput.value = '';
                    gajiInput.placeholder = '';
                    gajiInput.classList.add('input-disabled'); // tambahkan gaya nonaktif
                } else {
                    gajiInput.placeholder = 'Masukkan nominal gaji';
                    gajiInput.classList.remove('input-disabled'); // kembalikan ke normal
                }
            });

            const periodeCheckbox = document.getElementById('aturPeriodeCheckboxAktor');
            const periodeFields = document.getElementById('periodeManualFieldsAktor');
            const infoOtomatis = document.getElementById('infoOtomatisAktor');

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

                    let selectMitra = document.getElementById('mitra_teater_aktor');
                    selectMitra.innerHTML = '<option value="">Pilih Mitra Teater</option>'; // Reset opsi

                    data.forEach(mitra => {
                        let option = document.createElement('option');
                        option.value = mitra.id_mitra;
                        option.textContent = mitra.nama;
                        selectMitra.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching mitra:', error));

            const mitraSelect = document.getElementById('mitra_teater_aktor');
            const checkbox = document.getElementById('same-sosmed-aktor');
            const draftAccount = document.getElementById('draft-accounts-aktor');
            const hiddenAccount = document.querySelector('input[name="hidden_accounts"]');
            const addAccountBtn = document.getElementById('add-account-btn-aktor');
            const platformSelect = document.getElementById('platform_name_aktor');
            const accountInput = document.getElementById('acc_name_aktor');

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

            document.getElementById('submitBtnAktor').addEventListener('submit', function(event) {
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

            const submitBtn = document.getElementById('submitBtnAktor');
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

        //PopUp Staff
        document.addEventListener("DOMContentLoaded", function() {
            const popupStaff = document.getElementById("auditionPopupStaff");
            const popupTitleStaff = document.getElementById("popupTitleStaff");
            const formStaff = document.getElementById("auditionFormStaff");
            const addAuditionStaffBtn = document.getElementById("addAuditionStaffBtn"); // Tombol untuk membuka popup
            const cancelBtnStaff = document.getElementById("cancelBtnStaff");

            if (!formStaff) {
                console.error("Form tidak ditemukan!");
                return;
            }

            // Submit form audisi staff
            formStaff.addEventListener("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Debug isi formData
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }

                fetch("<?= base_url('MitraTeater/saveAuditionStaff') ?>", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server Response:", data);
                        if (data.success) {
                            alert(data.message);
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        } else {
                            alert("Gagal menyimpan audisi.");
                            console.error(data.errors || "Tidak ada pesan error dari server.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan pada server.");
                    });

                console.log("Form berhasil disubmit via JS!");
            });

            // Buka popup audisi staff
            document.getElementById("addAuditionStaffBtn")?.addEventListener("click", () => {
                popupTitleStaff.textContent = "Tambah Audisi Staff";
                formStaff.reset();
                document.getElementById("id_kategori_staff").value = "2";
                popupStaff.style.display = "flex";
            });

            // Tombol batal
            cancelBtnStaff.addEventListener("click", function() {
                formStaff.reset();
                idTeater = null;
                idAudisiStaff = null;
                popupStaff.style.display = "none";
            });

            const allStaffCheckbox = document.getElementById('all-staff');
            const staffInputSection = document.getElementById('staff-input-section');
            const staffInput = document.getElementById('jenis_staff');
            const jobdescStaffWrapper = document.getElementById('jobdesc-staff-wrapper');
            const jobdescStaff = document.getElementById('jobdesc_staff');

            // Validasi elemen penting
            if (!allStaffCheckbox || !staffInputSection || !staffInput || !formStaff) {
                console.warn('Beberapa elemen penting tidak ditemukan.');
                return;
            }

            // Fungsi update tampilan
            function updateStaffOptions(event) {

                const isAllStaffChecked = allStaffCheckbox.checked;

                if (isAllStaffChecked) {
                    staffInput.disabled = true;
                    staffInput.placeholder = '';
                    staffInput.value = '';
                    staffInput.classList.add('input-disabled');

                    jobdescStaff.disabled = true;
                    jobdescStaff.placeholder = '';
                    jobdescStaff.classList.add('input-disabled');
                } else {
                    staffInput.disabled = false;
                    staffInput.placeholder = 'Masukkan jenis staff';
                    staffInput.classList.remove('input-disabled');

                    jobdescStaff.disabled = false;
                    jobdescStaff.placeholder = 'Masukkan deskripsi pekerjaan staff';
                    jobdescStaff.classList.remove('input-disabled');
                }
            }

            // Event change
            allStaffCheckbox.addEventListener('change', (e) => updateStaffOptions(e));
            updateStaffOptions();

            let tipeHarga = document.getElementById('tipe_harga_staff');
            let nominalHarga = document.getElementById('nominal-harga-staff');
            let hargaSebelumnya = null;

            // Tampilkan atau sembunyikan input harga berdasarkan tipe harga
            tipeHarga.addEventListener('change', function() {
                if (this.value === "Bayar") {
                    nominalHarga.style.display = "block";
                    if (hargaSebelumnya !== null) {
                        document.getElementById('harga_staff').value = hargaSebelumnya; // Kembalikan harga sebelumnya
                    }
                } else {
                    nominalHarga.style.display = "none";
                    hargaSebelumnya = document.getElementById('harga_staff').value || hargaSebelumnya; // Simpan harga terakhir sebelum diubah
                    document.getElementById('harga_staff').value = "";
                    // Kosongkan input harga
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

            document.getElementById("kota-select-staff")?.addEventListener("change", function() {
                const hiddenKota = document.getElementById("hidden-kota-staff");
                const lainnyaContainer = document.getElementById("lainnya-container-staff");
                const kotaInput = document.getElementById("kota-input-staff");

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
            document.getElementById("kota-input-staff")?.addEventListener("input", function() {
                document.getElementById("hidden-kota-staff").value = this.value;
            });

            let currentSchedules = [];

            document.getElementById('addScheduleStaff').addEventListener('click', function() {
                let tanggal = document.getElementById('tanggal_staff').value;
                let waktuMulai = document.getElementById('waktu_mulai_staff').value;
                let waktuSelesai = document.getElementById('waktu_selesai_staff').value;
                let tipeHarga = document.getElementById('tipe_harga_staff').value;
                let harga = document.getElementById('harga_staff').value.trim();
                let kotaSelect = document.getElementById('kota-select-staff');
                let kotaInput = document.getElementById('kota-input-staff');
                let kota = kotaSelect.value === 'lainnya' && kotaInput ? kotaInput.value : kotaSelect.value; // Update kode
                let tempat = document.getElementById('tempat_staff').value.trim();

                if (!tanggal || !waktuMulai || !waktuSelesai || !kota || !tempat || !tipeHarga) {
                    alert("Mohon lengkapi semua field.");
                    return;
                }

                let scheduleItem = document.createElement('div');
                scheduleItem.classList.add('draft-schedule-item');

                // Validasi harga jika memilih "Bayar"
                if (tipeHarga === "Bayar") {
                    let hargaNominal = parseInt(harga.replace(/,/g, ''), 10);
                    if (!hargaNominal || hargaNominal <= 0) {
                        alert("Harga harus diisi dengan angka yang valid.");
                        return;
                    }
                }

                // Simpan data dalam bentuk JSON
                let newSchedule = {
                    tanggal: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                    tipe_harga: tipeHarga,
                    harga: (tipeHarga === "Gratis" || !harga) ? null : harga,
                    kota: kota,
                    tempat: tempat
                };

                let hiddenInput = document.getElementById('hidden_schedule_staff');
                currentSchedules = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];
                currentSchedules.push(newSchedule);
                hiddenInput.value = JSON.stringify(currentSchedules);

                let draftSchedule = document.getElementById('draft-schedule-staff');
                scheduleItem.innerHTML = `
            <p><strong>${newSchedule.tanggal}, ${newSchedule.waktu_mulai} - ${newSchedule.waktu_selesai}</strong></p>
            <p>${newSchedule.kota} - ${newSchedule.tempat}</p>
            <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
        `;

                let draftIndex = currentSchedules.length - 1;
                scheduleItem.setAttribute('data-draft-index', draftIndex);

                draftSchedule.appendChild(scheduleItem);

                console.log("Draft Schedule Item ditambahkan:", scheduleItem.innerHTML);

                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    let draftIndex = scheduleItem.getAttribute('data-draft-index');

                    // Hapus item draft dari tampilan
                    draftSchedule.removeChild(scheduleItem);

                    let updatedSchedules = currentSchedules.filter(schedule => {
                        let expectedHarga = (tipeHarga === "Gratis") ? null : parseInt(harga.replace(/,/g, ''), 10);
                        let isSameHarga = (tipeHarga === "Gratis") ?
                            (schedule.harga === null || schedule.harga === "") :
                            (parseInt(schedule.harga?.replace(/,/g, '') || 0, 10) === expectedHarga);


                        return !(
                            schedule.tanggal === tanggal &&
                            schedule.waktu_mulai === waktuMulai &&
                            schedule.waktu_selesai === waktuSelesai &&
                            schedule.tipe_harga === tipeHarga &&
                            isSameHarga &&
                            schedule.kota === kota &&
                            schedule.tempat === tempat
                        );
                    });

                    hiddenInput.value = JSON.stringify(updatedSchedules);
                    console.log("Updated Hidden Input Value (JSON):", hiddenInput.value);
                });
            });

            document.getElementById("auditionFormStaff").addEventListener("submit", function(event) {
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

            const gajiRahasia = document.getElementById('gaji_dirahasiakan_staff');
            const gajiInput = document.getElementById('gaji_staff');

            gajiRahasia.addEventListener('change', function() {
                const isChecked = this.checked;
                gajiInput.disabled = isChecked;

                if (isChecked) {
                    gajiInput.value = '';
                    gajiInput.placeholder = '';
                    gajiInput.classList.add('input-disabled'); // tambahkan gaya nonaktif
                } else {
                    gajiInput.placeholder = 'Masukkan nominal gaji';
                    gajiInput.classList.remove('input-disabled'); // kembalikan ke normal
                }
            });

            const periodeCheckbox = document.getElementById('aturPeriodeCheckboxStaff');
            const periodeFields = document.getElementById('periodeManualFieldsStaff');
            const infoOtomatis = document.getElementById('infoOtomatisStaff');

            console.log(periodeCheckbox); // Jika null → HTML belum dimuat saat ini dipanggil

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

                    let selectMitra = document.getElementById('mitra_teater_staff');
                    selectMitra.innerHTML = '<option value="">Pilih Mitra Teater</option>'; // Reset opsi

                    data.forEach(mitra => {
                        let option = document.createElement('option');
                        option.value = mitra.id_mitra;
                        option.textContent = mitra.nama;
                        selectMitra.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching mitra:', error));

            const mitraSelect = document.getElementById('mitra_teater_staff');
            const checkbox = document.getElementById('same-sosmed-staff');
            const draftAccount = document.getElementById('draft-accounts-staff');
            const hiddenAccount = document.querySelector('input[name="hidden_accounts"]');
            const addAccountBtn = document.getElementById('add-account-btn-staff');
            const platformSelect = document.getElementById('platform_name_staff');
            const accountInput = document.getElementById('acc_name_staff');

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

            document.getElementById('submitBtnStaff').addEventListener('submit', function(event) {
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

            const submitBtn = document.getElementById('submitBtnStaff');
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    updateHiddenInput();
                    if (draftWeb.children.length === 0) {
                        hiddenInput.value = '';
                    }
                    console.log('Final Draft Web (Sebelum Submit):', hiddenInput.value);
                });
            }

            document.getElementById("add-account-btn-staff")?.addEventListener("click", function() {
                const platformSelect = document.querySelector('select[name="id_platform_sosmed_staff[]"]');
                const accInput = document.querySelector('input[name="acc_name_staff[]"]');
                const draftContainer = document.getElementById("draft-accounts-staff");
                const hiddenInput = document.getElementById("hidden_accounts_staff");

                const platformId = platformSelect.value;
                const platformName = platformSelect.options[platformSelect.selectedIndex]?.textContent.trim();
                const accName = accInput.value.trim();

                // Validasi input
                if (!platformId || !accName) {
                    alert("Silakan pilih platform dan isi nama akun.");
                    return;
                }

                // Ambil data lama dari hidden input
                let data = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                // Cegah duplikat akun sosial media
                const isDuplicate = data.some(d => d.platformId === platformId && d.account === accName);
                if (isDuplicate) {
                    alert("Sosial media ini sudah ditambahkan.");
                    return;
                }

                // Buat draft item
                const draftItem = document.createElement("div");
                draftItem.classList.add("draft-item");
                draftItem.innerHTML = `
        <span>${platformName}</span> - 
        <span>${accName}</span>
        <button type="button" class="delete-draft-btn">x</button>
    `;

                // Event hapus draft
                draftItem.querySelector(".delete-draft-btn").addEventListener("click", () => {
                    draftItem.remove();
                    data = data.filter(d => !(d.platformId === platformId && d.account === accName));
                    hiddenInput.value = JSON.stringify(data);
                });

                draftContainer.appendChild(draftItem);

                // Update hidden input
                data.push({
                    platformId,
                    platformName,
                    account: accName
                });
                hiddenInput.value = JSON.stringify(data);

                // Reset input
                platformSelect.selectedIndex = 0;
                accInput.value = "";
            });


            // Tambah Website
            document.getElementById("add-web-btn-staff")?.addEventListener("click", () => {
                const titleInput = document.querySelector('input[name="judul_web_staff[]"]');
                const urlInput = document.querySelector('input[name="url_web_staff[]"]');
                const draftContainer = document.getElementById("draft-web-staff");
                const hiddenInput = document.querySelector('input[name="hidden_web_staff"]');

                const title = titleInput.value.trim();
                const url = urlInput.value.trim();

                if ((title && !url) || (!title && url)) {
                    alert("Isi kedua kolom Website (Judul dan URL) atau biarkan kosong.");
                    return;
                }

                if (!title && !url) return;

                const draftItem = document.createElement("div");
                draftItem.classList.add("draft-item");
                draftItem.setAttribute("data-title", title);
                draftItem.setAttribute("data-url", url);
                draftItem.innerHTML = `
            <span>${title}</span> - 
            <span>${url}</span>
            <button type="button" class="delete-draft-btn">x</button>
        `;

                draftItem.querySelector(".delete-draft-btn").addEventListener("click", () => {
                    draftItem.remove();
                    const items = draftContainer.querySelectorAll(".draft-item");
                    const data = Array.from(items).map(item => ({
                        title: item.getAttribute("data-title"),
                        url: item.getAttribute("data-url")
                    }));
                    hiddenInput.value = JSON.stringify(data);
                });

                draftContainer.appendChild(draftItem);

                const allItems = draftContainer.querySelectorAll(".draft-item");
                const webData = Array.from(allItems).map(item => ({
                    title: item.getAttribute("data-title"),
                    url: item.getAttribute("data-url")
                }));
                hiddenInput.value = JSON.stringify(webData);

                titleInput.value = "";
                urlInput.value = "";
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let currentSchedulesEdit = []; // untuk menampung draft jadwal di popup edit
            let deletedSchedules = []; // untuk menyimpan id jadwal yang akan dihapus
            updateDeletedSchedulesInput(); // fungsi untuk update input hidden

            const popupAktorEdit = document.getElementById("auditionPopupAktorEdit");
            const popupStaffEdit = document.getElementById("auditionPopupStaffEdit");
            const popupTitleAktorEdit = document.getElementById("popupTitleAktorEdit");
            const popupTitleStaffEdit = document.getElementById("popupTitleStaffEdit");
            const formAktorEdit = document.getElementById("auditionFormAktorEdit");
            const formStaffEdit = document.getElementById("auditionFormStaffEdit");
            const cancelEditBtnAktor = document.getElementById("cancelEditBtnAktor");
            const cancelEditBtnStaff = document.getElementById("cancelEditBtnStaff");

            // === SUBMIT FORM STAFF ===
            if (formStaffEdit) {
                formStaffEdit.addEventListener("submit", function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    fetch("<?= base_url('MitraTeater/saveAuditionStaff') ?>", {
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
                                alert("Gagal menyimpan audisi staff.");
                                console.error(data.errors || "Tidak ada pesan error dari server.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Terjadi kesalahan pada server.");
                        });
                });
            }

            // === SUBMIT FORM AKTOR ===
            if (formAktorEdit) {
                formAktorEdit.addEventListener("submit", function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);
                    formData.append(
                        'deleted_schedules',
                        document.getElementById('deleted_schedules_aktor_edit')?.value || '[]'
                    );

                    fetch("<?= base_url('MitraTeater/saveAuditionAktor') ?>", {
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
                                alert("Gagal menyimpan audisi aktor.");
                                console.error(data.errors || "Tidak ada pesan error dari server.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Terjadi kesalahan pada server.");
                        });
                });
            }

            // === TOMBOL EDIT (AKTOR / STAFF) ===
            document.querySelectorAll(".editBtn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const idTeater = this.dataset.id;
                    const idAudisi = this.dataset.idaudisi;
                    const idKategori = this.dataset.kategori;

                    // === EDIT AKTOR ===
                    if (idKategori === "1") {
                        if (!formAktorEdit) {
                            console.error("Form edit aktor tidak ditemukan!");
                            return;
                        }

                        popupTitleAktorEdit.textContent = "Edit Audisi Aktor";
                        popupAktorEdit.style.display = "flex";
                        formAktorEdit.reset();

                        // Tambahkan input hidden id_audisi (cek dulu apakah sudah ada)
                        if (idAudisi) {
                            let inputHidden = formAktorEdit.querySelector('input[name="id_audisi"]');
                            if (!inputHidden) {
                                inputHidden = document.createElement("input");
                                inputHidden.type = "hidden";
                                inputHidden.name = "id_audisi";
                                formAktorEdit.appendChild(inputHidden);
                            }
                            inputHidden.value = idAudisi;
                        }

                        fetch(`<?= base_url('MitraTeater/editAudisiAktor/${idTeater}') ?>`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.status === "success") {
                                    const data = result.data;
                                    formAktorEdit.reset();
                                    resetFormAktorEdit();
                                    popupAktorEdit.style.display = "flex";

                                    prefillFormAktor(data.teater, data.audisi, data.aktorAudisi, data.jadwal, data.user);
                                } else {
                                    alert(result.message || "Gagal mengambil data audisi.");
                                    popupAktorEdit.style.display = "none";
                                }
                            })
                            .catch(error => {
                                console.error("Fetch error:", error);
                                alert("Terjadi kesalahan saat mengambil data.");
                                popupAktorEdit.style.display = "none";
                            });

                        // === Prefill data ke form ===
                        function prefillFormAktor(teater, audisi, aktorAudisi, jadwal, user) {

                            // Hidden inputs
                            resetFormAktorEdit();
                            updateDeletedSchedulesInput();

                            document.querySelector('input[name="id_teater"]').value = teater.id_teater;
                            document.getElementById('tipe_teater_aktor_edit').value = 'audisi';
                            document.getElementById('id_kategori_aktor_edit').value = '1';
                            document.getElementById('id_aktor_audisi_edit').value = aktorAudisi.id_aktor_audisi;
                            document.getElementById('id_schedule_aktor_edit').value = jadwal.id_schedule;
                            document.querySelector('input[name="id_user"]').value = user.id_user;
                            document.querySelector('input[name="id_audisi"]').value = audisi.id_audisi;

                            // Input Teks
                            document.getElementById('judul_aktor_edit').value = teater.judul;
                            document.getElementById('sinopsis_aktor_edit').value = teater.sinopsis || '';

                            if (aktorAudisi) {
                                document.getElementById('karakter_audisi_edit').value = aktorAudisi.karakter_audisi || '';
                                document.getElementById('deskripsi_karakter_edit').value = aktorAudisi.deskripsi_karakter || '';

                                if (aktorAudisi.karakter_audisi || aktorAudisi.deskripsi_karakter) {
                                    document.getElementById('all-chara-edit').checked = false;
                                } else {
                                    document.getElementById('all-chara-edit').checked = true;
                                }

                                updateCharacterOptionsEdit();
                            }

                            const tipeHargaSelect = document.getElementById('tipe_harga_aktor_edit');
                            const nominalContainer = document.getElementById('nominal-harga-aktor-edit');
                            const hargaInput = document.getElementById('harga_aktor_edit');

                            if (jadwal.tipe_harga === 'Bayar' && jadwal.harga) {
                                tipeHargaSelect.value = 'Bayar';
                                nominalContainer.style.display = 'block';
                                hargaInput.value = jadwal.harga; // atau audisi.nominal_bayar kalau beda
                            } else {
                                tipeHargaSelect.value = 'Gratis';
                                nominalContainer.style.display = 'none';
                                hargaInput.value = '';
                            }

                            const kotaSelect = document.getElementById("kota-select-aktor");
                            const kotaInput = document.getElementById("kota-input-aktor");
                            const lainnyaContainer = document.getElementById("lainnya-container-aktor");
                            const hiddenKota = document.getElementById("hidden-kota-aktor");

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

                            const draftContainer = document.getElementById('draft-schedule-aktor-edit');
                            const hiddenInput = document.getElementById('hidden_schedule_aktor_edit');

                            // Kosongkan kontainer & array sebelum isi ulang
                            draftContainer.innerHTML = '';
                            currentSchedulesEdit = [];
                            deletedSchedules = [];

                            jadwal.forEach((j) => {
                                const schedule = {
                                    id: j.id || null,
                                    tanggal: j.tanggal,
                                    waktu_mulai: formatTimeToHHMM(j.waktu_mulai),
                                    waktu_selesai: formatTimeToHHMM(j.waktu_selesai),
                                    tipe_harga: j.tipe_harga,
                                    harga: j.harga || null,
                                    kota: j.kota,
                                    tempat: j.tempat
                                };

                                currentSchedulesEdit.push(schedule);

                                const scheduleItem = document.createElement('div');
                                scheduleItem.classList.add('draft-schedule-item');

                                // Pakai ID unik sementara (timestamp atau gabungan data)
                                const uniqueId = `${schedule.tanggal}-${schedule.waktu_mulai}-${schedule.tempat}-${schedule.kota}`;
                                scheduleItem.setAttribute('data-id', uniqueId);

                                scheduleItem.innerHTML = `
                                    <p><strong>${schedule.tanggal}, ${schedule.waktu_mulai} - ${schedule.waktu_selesai}</strong></p>
                                    <p>${schedule.kota} - ${schedule.tempat}</p>
                                    <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
                                `;

                                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                                    draftContainer.removeChild(scheduleItem);

                                    // Hapus item dari currentSchedulesEdit
                                    const indexToRemove = currentSchedulesEdit.findIndex(item =>
                                        `${item.tanggal}-${item.waktu_mulai}-${item.tempat}-${item.kota}` === uniqueId
                                    );
                                    if (indexToRemove > -1) {
                                        // Kalau ada id asli, simpan ke deletedSchedules
                                        if (currentSchedulesEdit[indexToRemove].id) {
                                            deletedSchedules.push(currentSchedulesEdit[indexToRemove].id);
                                        }
                                        currentSchedulesEdit.splice(indexToRemove, 1);
                                    }

                                    // Update hidden inputs
                                    hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                                    document.getElementById('deleted_schedules_aktor_edit').value = JSON.stringify(deletedSchedules);
                                    updateDeletedSchedulesInput();
                                });

                                draftContainer.appendChild(scheduleItem);
                            });

                            hiddenInput.value = JSON.stringify(currentSchedulesEdit);

                            document.getElementById('penulis_aktor_edit').value = teater.penulis;
                            document.getElementById('url_pendaftaran_aktor_edit').value = teater.url_pendaftaran;
                            document.getElementById('syarat_aktor_edit').value = audisi.syarat;
                            document.getElementById('syarat_dokumen_aktor_edit').value = audisi.syarat_dokumen || '';
                            document.getElementById('sutradara_aktor_edit').value = teater.sutradara;
                            document.getElementById('staff_aktor_edit').value = teater.staff || '';

                            const gajiEdit = document.getElementById('gaji_aktor_edit');
                            const gajiCheck = document.getElementById('gaji_dirahasiakan_aktor_edit')

                            gajiCheck.checked = audisi.status_gaji === 'secret';
                            gajiEdit.disabled = audisi.status_gaji === 'secret';

                            if (audisi.status_gaji === 'secret') {
                                gajiEdit.value = '';
                                gajiEdit.placeholder = '';
                                gajiEdit.classList.add('input-disabled');
                            } else if (audisi.status_gaji === 'shown') {
                                gajiEdit.value = audisi.gaji || ''; // kalau ada datanya
                                gajiEdit.placeholder = 'Masukkan nominal gaji';
                                gajiEdit.classList.remove('input-disabled');
                            } else {
                                // status_gaji === 'no'
                                gajiEdit.value = '';
                                gajiEdit.placeholder = 'Masukkan nominal gaji';
                                gajiEdit.classList.remove('input-disabled');
                            }

                            document.getElementById('komitmen_aktor_edit').value = audisi.komitmen || '';

                            // Dropdown Mitra Teater (set selected option)
                            // const mitraSelect = document.getElementById('mitra_teater_aktor_edit');
                            // if (mitraSelect) {
                            //     for (const option of mitraSelect.options) {
                            //         if (parseInt(option.value) === parseInt(audisi.id_mitra_teater)) {
                            //             option.selected = true;
                            //             break;
                            //         }
                            //     }
                            // }

                            togglePeriodeFieldsEdit(); // Panggil agar sinkron
                        }
                    }

                    // === EDIT STAFF ===
                    else if (idKategori === "2") {
                        if (!formStaffEdit) {
                            console.error("Form edit staff tidak ditemukan!");
                            return;
                        }

                        popupTitleStaffEdit.textContent = "Edit Audisi Staff";
                        popupStaffEdit.style.display = "flex";
                        formStaffEdit.reset();

                        // Tambahkan input hidden id_audisi (cek dulu apakah sudah ada)
                        if (idAudisi) {
                            let inputHidden = formStaffEdit.querySelector('input[name="id_audisi"]');
                            if (!inputHidden) {
                                inputHidden = document.createElement("input");
                                inputHidden.type = "hidden";
                                inputHidden.name = "id_audisi";
                                formStaffEdit.appendChild(inputHidden);
                            }
                            inputHidden.value = idAudisi;
                        }

                        fetch(`<?= base_url('MitraTeater/editAudisiStaff/${idTeater}') ?>`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.status === "success") {
                                    const data = result.data;
                                    prefillFormStaff(data.teater, data.audisi, data.staffAudisi, data.jadwal);
                                } else {
                                    alert(result.message || "Gagal mengambil data audisi.");
                                    popupStaffEdit.style.display = "none";
                                }
                            })
                            .catch(error => {
                                console.error("Fetch error:", error);
                                alert("Terjadi kesalahan saat mengambil data.");
                                popupStaffEdit.style.display = "none";
                            });

                        // === Prefill data ke form untuk Audisi Staff ===
                        function prefillFormStaff(teater, audisi, staffAudisi, jadwal) {

                            // Hidden inputs
                            document.querySelector('input[name="id_teater"]').value = teater.id_teater;
                            document.getElementById('tipe_teater_staff_edit').value = 'audisi';
                            document.getElementById('id_kategori_staff_edit').value = '2'; // misal kategori 2 untuk staff
                            document.getElementById('id_staff_audisi_edit').value = staffAudisi.id_staff_audisi;

                            // Input Teks
                            document.getElementById('judul_staff_edit').value = teater.judul;
                            document.getElementById('sinopsis_staff_edit').value = teater.sinopsis || '';

                            if (staffAudisi) {
                                document.getElementById('jenis_staff_edit').value = staffAudisi.jenis_staff || '';
                                document.getElementById('jobdesc_staff_edit').value = staffAudisi.jobdesc_staff || '';

                                if (staffAudisi.jenis_staff || staffAudisi.jobdesc_staff) {
                                    document.getElementById('all-chara-staff-edit').checked = false;
                                } else {
                                    document.getElementById('all-chara-staff-edit').checked = true;
                                }

                                updateCharacterOptionsEditStaff(); // asumsi fungsi ini bisa dipakai juga untuk staff
                            }

                            document.getElementById('id_schedule_staff_edit').value = jadwal.id_schedule;

                            const tipeHargaSelect = document.getElementById('tipe_harga_staff_edit');
                            const nominalContainer = document.getElementById('nominal-harga-staff-edit');
                            const hargaInput = document.getElementById('harga_staff_edit');

                            if (jadwal.tipe_harga === 'Bayar' && jadwal.harga) {
                                tipeHargaSelect.value = 'Bayar';
                                nominalContainer.style.display = 'block';
                                hargaInput.value = jadwal.harga;
                            } else {
                                tipeHargaSelect.value = 'Gratis';
                                nominalContainer.style.display = 'none';
                                hargaInput.value = '';
                            }

                            const kotaSelect = document.getElementById("kota-select-staff");
                            const kotaInput = document.getElementById("kota-input-staff");
                            const lainnyaContainer = document.getElementById("lainnya-container-staff");
                            const hiddenKota = document.getElementById("hidden-kota-staff");

                            const jabodetabek = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi'];
                            const kotaDariDB = jadwal.kota?.trim();

                            if (jabodetabek.includes(kotaDariDB)) {
                                kotaSelect.value = kotaDariDB;
                                kotaInput.value = '';
                                hiddenKota.value = kotaDariDB;
                                lainnyaContainer.style.display = 'none';
                                kotaInput.required = false;
                            } else {
                                kotaSelect.value = 'lainnya';
                                kotaInput.value = kotaDariDB || '';
                                hiddenKota.value = kotaDariDB || '';
                                lainnyaContainer.style.display = 'block';
                                kotaInput.required = true;
                            }

                            const draftContainer = document.getElementById('draft-schedule-staff-edit');
                            const hiddenInput = document.getElementById('hidden_schedule_staff_edit');
                            deletedSchedules = []; // Array untuk simpan id jadwal yang dihapus

                            draftContainer.innerHTML = '';
                            currentSchedulesEdit = [];

                            jadwal.forEach((j, index) => {
                                const schedule = {
                                    id: j.id || null,
                                    tanggal: j.tanggal,
                                    waktu_mulai: j.waktu,
                                    waktu_selesai: j.waktu_selesai || '',
                                    tipe_harga: j.tipe_harga,
                                    harga: j.harga || null,
                                    kota: j.kota,
                                    tempat: j.tempat
                                };

                                currentSchedulesEdit.push(schedule);

                                const scheduleItem = document.createElement('div');
                                scheduleItem.classList.add('draft-schedule-item');

                                const uniqueId = `${j.tanggal}-${j.waktu}-${j.tempat}-${j.kota}`;
                                scheduleItem.setAttribute('data-id', uniqueId);

                                scheduleItem.innerHTML = `
        <p><strong>${schedule.tanggal}, ${schedule.waktu_mulai} - ${schedule.waktu_selesai}</strong></p>
        <p>${schedule.kota} - ${schedule.tempat}</p>
        <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
    `;

                                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                                    draftContainer.removeChild(scheduleItem);

                                    const indexToRemove = currentSchedulesEdit.findIndex(item =>
                                        `${item.tanggal}-${item.waktu_mulai}-${item.tempat}-${item.kota}` === uniqueId
                                    );
                                    if (indexToRemove > -1) {
                                        if (currentSchedulesEdit[indexToRemove].id) {
                                            deletedSchedules.push(currentSchedulesEdit[indexToRemove].id);
                                        }
                                        currentSchedulesEdit.splice(indexToRemove, 1);
                                    }

                                    hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                                    document.getElementById('deleted_schedules_staff_edit').value = JSON.stringify(deletedSchedules);
                                });

                                draftContainer.appendChild(scheduleItem);
                            });

                            hiddenInput.value = JSON.stringify(currentSchedulesEdit);

                            document.getElementById('penulis_staff_edit').value = teater.penulis;
                            document.getElementById('url_pendaftaran_staff_edit').value = teater.url_pendaftaran;
                            document.getElementById('syarat_staff_edit').value = audisi.syarat;
                            document.getElementById('syarat_dokumen_staff_edit').value = audisi.syarat_dokumen || '';
                            document.getElementById('sutradara_staff_edit').value = teater.sutradara;

                            const gajiEdit = document.getElementById('gaji_staff_edit');
                            const gajiCheck = document.getElementById('gaji_dirahasiakan_staff_edit');

                            gajiCheck.checked = audisi.status_gaji === 'secret';
                            gajiEdit.disabled = audisi.status_gaji === 'secret';

                            if (audisi.status_gaji === 'secret') {
                                gajiEdit.value = '';
                                gajiEdit.placeholder = '';
                                gajiEdit.classList.add('input-disabled');
                            } else if (audisi.status_gaji === 'shown') {
                                gajiEdit.value = audisi.gaji || '';
                                gajiEdit.placeholder = 'Masukkan nominal gaji';
                                gajiEdit.classList.remove('input-disabled');
                            } else {
                                gajiEdit.value = '';
                                gajiEdit.placeholder = 'Masukkan nominal gaji';
                                gajiEdit.classList.remove('input-disabled');
                            }

                            document.getElementById('komitmen_staff_edit').value = audisi.komitmen || '';

                            const periodeCheckboxEdit = document.getElementById('aturPeriodeCheckboxStaffEdit');

                            periodeCheckboxEdit.checked = teater.daftar_mulai && teater.daftar_berakhir ? true : false;
                            togglePeriodeFieldsEditStaff();

                            if (periodeCheckboxEdit.checked) {
                                document.getElementById('daftar_mulai_staff_edit').value = teater.daftar_mulai;
                                document.getElementById('daftar_berakhir_staff_edit').value = teater.daftar_berakhir;
                            }
                        }
                    } else {
                        alert("Kategori audisi tidak dikenali.");
                    }
                });
            });

            function resetFormAktorEdit() {

                // Bersihkan draft schedule container & reset array jadwal
                const draftContainer = document.getElementById('draft-schedule-aktor-edit');
                if (draftContainer) draftContainer.innerHTML = '';

                // Reset variabel jadwal (pastikan variabel global/let di scope)
                if (typeof currentSchedulesEdit !== 'undefined') currentSchedulesEdit.length = 0;
                if (typeof deletedSchedules !== 'undefined') deletedSchedules.length = 0;

                // Reset hidden input jadwal
                const hiddenScheduleInput = document.getElementById('hidden_schedule_aktor_edit');
                if (hiddenScheduleInput) hiddenScheduleInput.value = '';

                const deletedSchedulesInput = document.getElementById('deleted_schedules_aktor_edit');
                if (deletedSchedulesInput) deletedSchedulesInput.value = '';

                // Reset checkbox all-chara
                const allCharaCheckbox = document.getElementById('all-chara-edit');
                if (allCharaCheckbox) allCharaCheckbox.checked = true; // sesuaikan defaultnya

                // Reset tipe harga
                const tipeHargaSelect = document.getElementById('tipe_harga_aktor_edit');
                const nominalContainer = document.getElementById('nominal-harga-aktor-edit');
                const hargaInput = document.getElementById('harga_aktor_edit');
                if (tipeHargaSelect) tipeHargaSelect.value = 'Gratis'; // default
                if (nominalContainer) nominalContainer.style.display = 'none';
                if (hargaInput) hargaInput.value = '';

                // Reset kota select & input, dan hidden kota
                const kotaSelect = document.getElementById('kota-select-aktor');
                const kotaInput = document.getElementById('kota-input-aktor');
                const lainnyaContainer = document.getElementById('lainnya-container-aktor');
                const hiddenKota = document.getElementById('hidden-kota-aktor');
                if (kotaSelect) kotaSelect.value = '';
                if (kotaInput) {
                    kotaInput.value = '';
                    kotaInput.required = false;
                }
                if (lainnyaContainer) lainnyaContainer.style.display = 'none';
                if (hiddenKota) hiddenKota.value = '';

                // Reset gaji dan status gaji
                const gajiEdit = document.getElementById('gaji_aktor_edit');
                const gajiCheck = document.getElementById('gaji_check_aktor_edit');
                if (gajiEdit) {
                    gajiEdit.value = '';
                    gajiEdit.disabled = false;
                    gajiEdit.placeholder = 'Masukkan nominal gaji';
                    gajiEdit.classList.remove('input-disabled');
                }
                if (gajiCheck) gajiCheck.checked = false;

                // Reset periode checkbox dan tanggal periode
                const periodeCheckboxEdit = document.getElementById('aturPeriodeCheckboxAktorEdit')
                if (periodeCheckboxEdit) periodeCheckboxEdit.checked = false;
                togglePeriodeFieldsEdit(); // reset tampilan periode

                // Update UI karakter agar sesuai dengan reset (updateCharacterOptionsEdit)
                updateCharacterOptionsEdit();
            }

            if (cancelEditBtnAktor) {
                cancelEditBtnAktor.addEventListener("click", function() {
                    formAktorEdit.reset();
                    popupAktorEdit.style.display = "none";
                    resetFormAktorEdit(); // fungsi reset yang sudah kamu buat
                });
            }

            function resetFormStaffEdit() {
                const draftContainer = document.getElementById('draft-schedule-staff-edit');
                if (draftContainer) draftContainer.innerHTML = '';

                if (typeof currentSchedulesEdit !== 'undefined') currentSchedulesEdit.length = 0;
                if (typeof deletedSchedules !== 'undefined') deletedSchedules.length = 0;

                const hiddenScheduleInput = document.getElementById('hidden_schedule_staff_edit');
                if (hiddenScheduleInput) hiddenScheduleInput.value = '';

                const deletedSchedulesInput = document.getElementById('deleted_schedules_staff_edit');
                if (deletedSchedulesInput) deletedSchedulesInput.value = '';

                const allCharaCheckbox = document.getElementById('all-chara-edit-staff');
                if (allCharaCheckbox) allCharaCheckbox.checked = true;

                const tipeHargaSelect = document.getElementById('tipe_harga_staff_edit');
                const nominalContainer = document.getElementById('nominal-harga-staff-edit');
                const hargaInput = document.getElementById('harga_staff_edit');
                if (tipeHargaSelect) tipeHargaSelect.value = 'Gratis';
                if (nominalContainer) nominalContainer.style.display = 'none';
                if (hargaInput) hargaInput.value = '';

                const kotaSelect = document.getElementById('kota-select-staff-edit');
                const kotaInput = document.getElementById('kota-input-staff-edit');
                const lainnyaContainer = document.getElementById('lainnya-container-staff-edit');
                const hiddenKota = document.getElementById('hidden-kota-staff-edit');
                if (kotaSelect) kotaSelect.value = '';
                if (kotaInput) {
                    kotaInput.value = '';
                    kotaInput.required = false;
                }
                if (lainnyaContainer) lainnyaContainer.style.display = 'none';
                if (hiddenKota) hiddenKota.value = '';

                const gajiEdit = document.getElementById('gaji_staff_edit');
                const gajiCheck = document.getElementById('gaji_check_staff_edit');
                if (gajiEdit) {
                    gajiEdit.value = '';
                    gajiEdit.disabled = false;
                    gajiEdit.placeholder = 'Masukkan nominal gaji';
                    gajiEdit.classList.remove('input-disabled');
                }
                if (gajiCheck) gajiCheck.checked = false;

                const periodeCheckboxEdit = document.getElementById('periode_checkbox_edit_staff');
                if (periodeCheckboxEdit) periodeCheckboxEdit.checked = false;
                togglePeriodeFieldsEditStaff(); // Fungsi ini perlu Anda buat jika belum

                updateCharacterOptionsEditStaff();
            }

            // === TOMBOL BATAL STAFF ===
            if (cancelEditBtnStaff) {
                cancelEditBtnStaff.addEventListener("click", function() {
                    formStaffEdit.reset();
                    popupStaffEdit.style.display = "none";
                    resetFormStaffEdit();
                });
            }

            function updateDeletedSchedulesInput() {
                document.getElementById('deleted_schedules_aktor_edit').value = JSON.stringify(deletedSchedules);
            }

            function formatTimeToHHMM(timeStr) {
                return timeStr ? timeStr.slice(0, 5) : "";
            }

            const allCharaCheckboxEdit = document.getElementById('all-chara-edit');
            const aktorEditSection = document.getElementById('aktor-edit-section');
            const karakterInputEdit = document.getElementById('karakter_audisi_edit');
            const deskripsiKarakterEditWrapper = document.getElementById('deskripsi-karakter-edit-wrapper');
            const deskripsiKarakterEdit = document.getElementById('deskripsi_karakter_edit');

            // Validasi elemen penting
            if (!allCharaCheckboxEdit || !aktorEditSection || !karakterInputEdit || !formAktorEdit) {
                console.warn('Beberapa elemen penting tidak ditemukan.');
                return;
            }

            function updateCharacterOptionsEdit() {
                const isAllCharaChecked = allCharaCheckboxEdit.checked;

                if (isAllCharaChecked) {
                    karakterInputEdit.disabled = true;
                    karakterInputEdit.placeholder = '';
                    karakterInputEdit.classList.add('input-disabled');
                    karakterInputEdit.value = ''; // kalau mau clear

                    deskripsiKarakterEdit.disabled = true;
                    deskripsiKarakterEdit.placeholder = '';
                    deskripsiKarakterEdit.classList.add('input-disabled');
                    deskripsiKarakterEdit.value = ''; // kalau mau clear
                } else {
                    karakterInputEdit.disabled = false;
                    karakterInputEdit.placeholder = 'Masukkan nama karakter';
                    karakterInputEdit.classList.remove('input-disabled');

                    deskripsiKarakterEdit.disabled = false;
                    deskripsiKarakterEdit.placeholder = 'Masukkan deskripsi karakter';
                    deskripsiKarakterEdit.classList.remove('input-disabled');
                }
            }

            if (allCharaCheckboxEdit) {
                allCharaCheckboxEdit.addEventListener('change', updateCharacterOptionsEdit);
            }

            function updateCharacterOptionsEditStaff() {
                const allCharaCheckbox = document.getElementById('all-chara-edit-staff');
                const karakterInput = document.getElementById('jenis_staff_edit');
                const jobdescInput = document.getElementById('jobdesc_staff_edit');

                if (!allCharaCheckbox || !karakterInput || !jobdescInput) return;

                if (allCharaCheckbox.checked) {
                    karakterInput.disabled = true;
                    karakterInput.value = '';
                    karakterInput.classList.add('input-disabled');

                    jobdescInput.disabled = true;
                    jobdescInput.value = '';
                    jobdescInput.classList.add('input-disabled');
                } else {
                    karakterInput.disabled = false;
                    karakterInput.placeholder = 'Masukkan jenis staff';
                    karakterInput.classList.remove('input-disabled');

                    jobdescInput.disabled = false;
                    jobdescInput.placeholder = 'Masukkan jobdesc staff';
                    jobdescInput.classList.remove('input-disabled');
                }
            }

            document.getElementById('all-chara-edit-staff')?.addEventListener('change', updateCharacterOptionsEditStaff);

            document.getElementById('tipe_harga_aktor')?.addEventListener('change', function() {
                const nominalContainer = document.getElementById('nominal-harga-aktor-edit');
                const hargaInput = document.getElementById('harga_aktor_edit');

                if (this.value === 'Bayar') {
                    nominalContainer.style.display = 'block';
                } else {
                    nominalContainer.style.display = 'none';
                    hargaInput.value = '';
                }
            });

            // === Mekanisme Kota Aktor Edit ===
            document.getElementById("kota-select-aktor-edit")?.addEventListener("change", function() {
                const kotaInput = document.getElementById("kota-edit-aktor");
                const hiddenKota = document.getElementById("hidden-kota-aktor-edit");
                const lainnyaContainer = document.getElementById("lainnya-container-aktor-edit");

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

            document.getElementById("kota-edit-aktor")?.addEventListener("input", function() {
                document.getElementById("hidden-kota-aktor-edit").value = this.value;
            });

            document.getElementById("kota-select-staff-edit")?.addEventListener("change", function() {
                const kotaInput = document.getElementById("kota-input-staff-edit");
                const hiddenKota = document.getElementById("hidden-kota-staff-edit");
                const lainnyaContainer = document.getElementById("lainnya-container-staff-edit");

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

            document.getElementById("kota-input-staff-edit")?.addEventListener("input", function() {
                document.getElementById("hidden-kota-staff-edit").value = this.value;
            });

            document.getElementById('editScheduleAktor').addEventListener('click', function() {
                let tanggal = document.getElementById('tanggal_aktor_edit').value;
                let waktuMulai = document.getElementById('waktu_mulai_aktor_edit').value;
                let waktuSelesai = document.getElementById('waktu_selesai_aktor_edit').value;
                let tipeHarga = document.getElementById('tipe_harga_aktor_edit').value;
                let harga = document.getElementById('harga_aktor_edit').value.trim();
                let kotaSelect = document.getElementById('kota-select-aktor-edit');
                let kotaInput = document.getElementById('kota-input-aktor-edit');
                let kota = kotaSelect.value === 'lainnya' && kotaInput ? kotaInput.value : kotaSelect.value;
                let tempat = document.getElementById('tempat_aktor_edit').value.trim();

                if (!tanggal || !waktuMulai || !waktuSelesai || !kota || !tempat || !tipeHarga) {
                    alert("Mohon lengkapi semua field.");
                    return;
                }

                if (tipeHarga === "Bayar") {
                    let hargaNominal = parseInt(harga.replace(/,/g, ''), 10);
                    if (!hargaNominal || hargaNominal <= 0) {
                        alert("Harga harus diisi dengan angka yang valid.");
                        return;
                    }
                }

                let newSchedule = {
                    id: null, // Jadwal baru belum punya ID database
                    uuid: Date.now().toString() + Math.random().toString(36).substring(2), // Unique untuk runtime
                    tanggal: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                    tipe_harga: tipeHarga,
                    harga: (tipeHarga === "Gratis" || !harga) ? null : harga,
                    kota: kota,
                    tempat: tempat
                };

                // Update currentSchedulesEdit dari hidden input, kalau ada
                let hiddenInput = document.getElementById('hidden_schedule_aktor_edit');
                currentSchedulesEdit = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                currentSchedulesEdit.push(newSchedule);
                hiddenInput.value = JSON.stringify(currentSchedulesEdit);

                // Render ke draft container
                let draftSchedule = document.getElementById('draft-schedule-aktor-edit');
                let scheduleItem = document.createElement('div');
                scheduleItem.classList.add('draft-schedule-item');

                scheduleItem.dataset.uuid = newSchedule.uuid;

                scheduleItem.innerHTML = `
        <p><strong>${newSchedule.tanggal}, ${newSchedule.waktu_mulai} - ${newSchedule.waktu_selesai}</strong></p>
        <p>${newSchedule.kota} - ${newSchedule.tempat}</p>
        <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
    `;

                draftSchedule.appendChild(scheduleItem);

                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    draftSchedule.removeChild(scheduleItem);

                    // Cari index berdasarkan unique key untuk hapus dari array
                    const uuidToRemove = scheduleItem.dataset.uuid;
                    const indexToRemove = currentSchedulesEdit.findIndex(item => item.uuid === uuidToRemove);

                    if (indexToRemove > -1) {
                        // Jika jadwal lama (ada ID), simpan ke deletedSchedules
                        if (currentSchedulesEdit[indexToRemove].id) {
                            deletedSchedules.push(currentSchedulesEdit[indexToRemove].id);
                        }

                        currentSchedulesEdit.splice(indexToRemove, 1);

                        // Update hidden input deleted_schedules
                        updateDeletedSchedulesInput();
                    }

                    // Update hidden input jadwal yang tersisa
                    hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                });
            });

            document.getElementById('editScheduleStaff')?.addEventListener('click', function() {
                let tanggal = document.getElementById('tanggal_staff_edit').value;
                let waktuMulai = document.getElementById('waktu_mulai_staff_edit').value;
                let waktuSelesai = document.getElementById('waktu_selesai_staff_edit').value;
                let tipeHarga = document.getElementById('tipe_harga_staff_edit').value;
                let harga = document.getElementById('harga_staff_edit').value.trim();
                let kotaSelect = document.getElementById('kota-select-staff-edit');
                let kotaInput = document.getElementById('kota-input-staff-edit');
                let kota = kotaSelect.value === 'lainnya' && kotaInput ? kotaInput.value : kotaSelect.value;
                let tempat = document.getElementById('tempat_staff_edit').value.trim();

                if (!tanggal || !waktuMulai || !waktuSelesai || !kota || !tempat || !tipeHarga) {
                    alert("Mohon lengkapi semua field.");
                    return;
                }

                if (tipeHarga === "Bayar") {
                    let hargaNominal = parseInt(harga.replace(/,/g, ''), 10);
                    if (!hargaNominal || hargaNominal <= 0) {
                        alert("Harga harus diisi dengan angka yang valid.");
                        return;
                    }
                }

                let newSchedule = {
                    id: null,
                    uuid: Date.now().toString() + Math.random().toString(36).substring(2), // Unique untuk runtime
                    tanggal: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                    tipe_harga: tipeHarga,
                    harga: (tipeHarga === "Gratis" || !harga) ? null : harga,
                    kota: kota,
                    tempat: tempat
                };

                let hiddenInput = document.getElementById('hidden_schedule_staff_edit');
                currentSchedulesEdit = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                currentSchedulesEdit.push(newSchedule);
                hiddenInput.value = JSON.stringify(currentSchedulesEdit);

                let draftSchedule = document.getElementById('draft-schedule-staff-edit');
                let scheduleItem = document.createElement('div');
                scheduleItem.classList.add('draft-schedule-item');

                scheduleItem.dataset.uuid = newSchedule.uuid;

                scheduleItem.innerHTML = `
        <p><strong>${newSchedule.tanggal}, ${newSchedule.waktu_mulai} - ${newSchedule.waktu_selesai}</strong></p>
        <p>${newSchedule.kota} - ${newSchedule.tempat}</p>
        <button type="button" class="delete-draft-btn delete-schedule-btn">x</button>
    `;

                draftSchedule.appendChild(scheduleItem);

                scheduleItem.querySelector('.delete-draft-btn').addEventListener('click', function() {
                    draftSchedule.removeChild(scheduleItem);

                    const uuidToRemove = scheduleItem.dataset.uuid;
                    const indexToRemove = currentSchedulesEdit.findIndex(item => item.uuid === uuidToRemove);

                    if (indexToRemove > -1) {
                        if (currentSchedulesEdit[indexToRemove].id) {
                            deletedSchedules.push(currentSchedulesEdit[indexToRemove].id);
                        }
                        currentSchedulesEdit.splice(indexToRemove, 1);
                        document.getElementById('deleted_schedules_staff_edit').value = JSON.stringify(deletedSchedules);
                    }

                    hiddenInput.value = JSON.stringify(currentSchedulesEdit);
                });
            });

            const gajiRahasiaEdit = document.getElementById('gaji_dirahasiakan_aktor_edit');
            const gajiEdit = document.getElementById('gaji_aktor_edit');

            gajiRahasiaEdit.addEventListener('change', function() {
                const isChecked = this.checked;
                gajiEdit.disabled = isChecked;

                if (isChecked) {
                    gajiEdit.value = '';
                    gajiEdit.placeholder = '';
                    gajiEdit.classList.add('input-disabled');
                } else {
                    gajiEdit.placeholder = 'Masukkan nominal gaji';
                    gajiEdit.classList.remove('input-disabled');
                }
            });

            function togglePeriodeFieldsEdit() {
                const periodeCheckboxEdit = document.getElementById('aturPeriodeCheckboxAktorEdit');
                const periodeFieldsEdit = document.getElementById('periodeManualFieldsAktorEdit');
                const infoOtomatisEdit = document.getElementById('infoOtomatisAktorEdit');

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

            const gajiRahasiaStaffEdit = document.getElementById('gaji_dirahasiakan_staff_edit');
            const gajiStaffEdit = document.getElementById('gaji_staff_edit');

            gajiRahasiaStaffEdit?.addEventListener('change', function() {
                const isChecked = this.checked;
                if (!gajiStaffEdit) return;

                gajiStaffEdit.disabled = isChecked;

                if (isChecked) {
                    gajiStaffEdit.value = '';
                    gajiStaffEdit.placeholder = '';
                    gajiStaffEdit.classList.add('input-disabled');
                } else {
                    gajiStaffEdit.placeholder = 'Masukkan nominal gaji';
                    gajiStaffEdit.classList.remove('input-disabled');
                }
            });

            const periodeCheckboxEditStaff = document.getElementById('aturPeriodeCheckboxStaffEdit');
            const periodeFieldsEditStaff = document.getElementById('periodeManualFieldsStaffEdit');
            const infoOtomatisEditStaff = document.getElementById('infoOtomatisStaffEdit');

            function togglePeriodeFieldsEditStaff() {
                if (!periodeCheckboxEditStaff || !periodeFieldsEditStaff || !infoOtomatisEditStaff) return;

                if (periodeCheckboxEditStaff.checked) {
                    periodeFieldsEditStaff.style.display = 'block';
                    infoOtomatisEditStaff.style.display = 'none';
                } else {
                    periodeFieldsEditStaff.style.display = 'none';
                    infoOtomatisEditStaff.style.display = 'block';
                }
            }

            // Panggil saat pertama kali modal edit staff ditampilkan
            togglePeriodeFieldsEditStaff();

            // Event listener saat checkbox periode diubah
            periodeCheckboxEditStaff?.addEventListener('change', togglePeriodeFieldsEditStaff);
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.deleteBtn').on('click', function() {
                const idTeater = $(this).data('id');

                if (confirm('Yakin ingin menghapus seluruh data audisi dari teater ini?')) {
                    $.ajax({
                        url: '<?= base_url('MitraTeater/deleteAudisiByTeater') ?>',
                        method: 'POST',
                        data: {
                            id_teater: idTeater
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert('Gagal menghapus audisi.');
                            }
                        }
                    });
                }
            });
        });

        // Open popup for "Edit Pertunjukan"
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('editBtn')) {
                    console.log("Tombol Edit ditekan");

                    const teaterId = event.target.getAttribute('data-id');
                    const popup = document.getElementById('editPopup');
                    const popupTitle = document.getElementById('popupTitle');
                    const form = document.getElementById('editForm');
                    const submitBtn = document.getElementById('submitBtn');

                    if (!popup) {
                        console.error("Elemen popup tidak ditemukan!");
                        return;
                    }

                    popupTitle.textContent = 'Edit Pertunjukan';
                    submitBtn.textContent = 'Update';
                    form.setAttribute('action', `<?= base_url('Admin/updateAuditionAdmin') ?>/${teaterId}`);
                    document.getElementById('id_teater').value = teaterId;

                    // Kosongkan semua draft terlebih dahulu
                    document.getElementById('draft-schedule').innerHTML = '';
                    document.getElementById('draft-web').innerHTML = '';
                    document.querySelector('input[name="hidden_schedule"]').value = '';
                    document.querySelector('input[name="hidden_web"]').value = '';

                    fetch(`<?= base_url('Admin/getTeaterData'); ?>?id_teater=${teaterId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const teater = data.data.teater;
                                const penampilan = data.data.penampilan;
                                const schedules = data.data.schedule;
                                const webs = data.data.web;

                                // Isikan data teater
                                document.getElementById('judul').value = teater.judul;
                                document.getElementById('sinopsis').value = teater.sinopsis;
                                document.getElementById('sutradara').value = teater.sutradara;
                                document.getElementById('penulis').value = teater.penulis;
                                document.getElementById('staff').value = teater.staff;
                                document.getElementById('url_pendaftaran').value = teater.url_pendaftaran;

                                // Isikan data penampilan
                                document.getElementById('aktor').value = penampilan.aktor;
                                document.getElementById('durasi').value = penampilan.durasi;
                                document.getElementById('rating_umur').value = penampilan.rating_umur;

                                // Tampilkan semua schedule sebagai card
                                const draftSchedule = document.getElementById('draft-schedule');
                                const hiddenScheduleInput = document.querySelector('input[name="hidden_schedule"]');
                                let scheduleList = [];

                                schedules.forEach(schedule => {
                                    const card = document.createElement('div');
                                    card.classList.add('draft-schedule-item');
                                    card.innerHTML = `
                                        <p><strong>${schedule.tanggal}, ${schedule.waktu_mulai} - ${schedule.waktu_selesai}</strong></p>
                                        <p>${schedule.kota} - ${schedule.tempat}</p>
                                        <button type="button" class="delete-draft-btn delete-schedule-btn" data-id="${schedule.id_schedule}">x</button>
                                    `;
                                    draftSchedule.appendChild(card);

                                    // Simpan dalam array untuk hidden input
                                    scheduleList.push({
                                        id_schedule: schedule.id_schedule,
                                        tanggal: schedule.tanggal,
                                        waktu_mulai: schedule.waktu_mulai,
                                        waktu_selesai: schedule.waktu_selesai,
                                        kota: schedule.kota,
                                        tempat: schedule.tempat
                                    });

                                    card.querySelector('.delete-schedule-btn').addEventListener('click', function() {
                                        const idToDelete = this.getAttribute('data-id');
                                        fetch(`<?= base_url('Admin/deleteSchedule') ?>?id_schedule=${idToDelete}`, {
                                            method: 'DELETE'
                                        }).then(res => res.json()).then(result => {
                                            if (result.status === 'success') {
                                                card.remove();
                                                scheduleList = scheduleList.filter(item => item.id_schedule != idToDelete);
                                                hiddenScheduleInput.value = JSON.stringify(scheduleList);
                                            }
                                        });
                                    });
                                });
                                hiddenScheduleInput.value = JSON.stringify(scheduleList);

                                // Tampilkan website
                                const draftWeb = document.getElementById('draft-web');
                                const hiddenWeb = document.querySelector('input[name="hidden_web"]');
                                let webList = [];

                                webs.forEach(web => {
                                    const webItem = document.createElement('div');
                                    webItem.classList.add('draft-item');
                                    webItem.setAttribute('data-title', web.judul_web);
                                    webItem.setAttribute('data-url', web.url_web);
                                    webItem.innerHTML = `
                                        <span>${web.judul_web}</span> - 
                                        <span>${web.url_web}</span>
                                        <button type="button" class="delete-draft-btn delete-web-btn" data-id="${web.id_teater_web}">x</button>
                                    `;
                                    draftWeb.appendChild(webItem);

                                    webList.push({
                                        title: web.judul_web,
                                        url: web.url_web
                                    });

                                    webItem.querySelector('.delete-web-btn').addEventListener('click', function() {
                                        const idToDelete = this.getAttribute('data-id');
                                        fetch(`<?= base_url('Admin/deleteWeb') ?>?id_teater_web=${idToDelete}`, {
                                                method: 'DELETE'
                                            })
                                            .then(res => res.json())
                                            .then(result => {
                                                if (result.status === 'success') {
                                                    webItem.remove();
                                                    webList = webList.filter(item => item.title !== web.judul_web);
                                                    hiddenWeb.value = JSON.stringify(webList);
                                                }
                                            });
                                    });
                                });
                                hiddenWeb.value = JSON.stringify(webList);



                                popup.style.display = 'flex';
                            } else {
                                alert("Gagal mengambil data.");
                            }
                        })
                        .catch(err => {
                            console.error("Error fetch:", err);
                        });
                }
            });

            // Tombol batal -> reset semua
            document.getElementById('cancelBtn').addEventListener('click', function() {
                const popup = document.getElementById('editPopup');
                popup.style.display = 'none';

                const form = document.getElementById('editForm');
                form.reset();

                form.setAttribute('action', `<?= base_url('Admin/saveAuditionAdmin') ?>`);
                document.getElementById('submitBtn').textContent = 'Simpan';

                // Bersihkan draft dan hidden inputs
                document.getElementById('draft-schedule').innerHTML = '';
                document.querySelector('input[name="hidden_schedule"]').value = '';
                document.getElementById('draft-web').innerHTML = '';
                document.querySelector('input[name="hidden_web"]').value = '';
                document.getElementById('id_teater').value = '';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.deleteBtn').on('click', function() {
                const idTeater = $(this).data('id');

                if (confirm('Yakin ingin menghapus seluruh data pertunjukan dari teater ini?')) {
                    $.ajax({
                        url: '<?= base_url('MitraTeater/deleteShowByTeater') ?>',
                        method: 'POST',
                        data: {
                            id_teater: idTeater
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert('Gagal menghapus pertunjukan.');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat menghubungi server.');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>