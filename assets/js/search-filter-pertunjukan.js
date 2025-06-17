document.addEventListener("DOMContentLoaded", function () {
    let categorySelect = document.getElementById("searchCategory");
    let inputContainer = document.getElementById("searchInputContainer");

    function updateSearchInput(selected) {
        let newInput = "";

        if (selected === "tanggal") {
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
        } else if (selected === "durasi") {
            newInput = `
                <div class="d-flex gap-2">
                    <input type="number" class="form-control w-25" id="minDurasi" placeholder="Min Durasi (menit)">
                    <input type="number" class="form-control w-25" id="maxDurasi" placeholder="Max Durasi (menit)">
                </div>
            `;
        } else if (selected === "rating") {
            newInput = `
                <select class="form-select w-50" id="searchInput">
                    <option value="">Pilih Rating Umur</option>
                    <option value="SU">Semua Umur (SU)</option>
                    <option value="R13">Remaja (13+)</option>
                    <option value="D17">Dewasa (17+)</option>
                </select>
            `;
        } else if (selected === "gaji") {
            newInput = `
                <div class="d-flex gap-2">
                    <input type="number" class="form-control w-25" id="minGaji" placeholder="Min Gaji">
                    <input type="number" class="form-control w-25" id="maxGaji" placeholder="Max Gaji">
                </div>
            `;
        } else {
            // Default: input teks biasa untuk "judul" dan "aktor"
            newInput = `<input type="text" class="form-control w-50" id="searchInput" placeholder="Cari...">`;
        }

        // Ganti isi dari inputContainer
        inputContainer.innerHTML = newInput;
    }

    // Jalankan perubahan saat dropdown berubah
    categorySelect.addEventListener("change", function () {
        updateSearchInput(this.value);
    });

    // Set default input pertama kali
    updateSearchInput(categorySelect.value);

    const baseUrl = window.location.origin + "/CodeIgniter4/public";

    document.getElementById("filterShowBtn").addEventListener("click", function () {
        let category = document.getElementById("searchCategory").value;
        let queryString = "";

        if (category === "tanggal") {
            let tanggal = document.getElementById("searchInput").value;
            queryString = `searchTanggal=${tanggal}`;
        } else if (category === "waktu") {
            let waktu = document.getElementById("searchInput").value;
            queryString = `searchWaktu=${waktu}`;
        } else if (category === "kota") {
            let kota = document.getElementById("searchInput").value;
            queryString = `searchKota=${kota}`;
        } else if (category === "harga") {
            let minHarga = document.getElementById("minHarga").value;
            let maxHarga = document.getElementById("maxHarga").value;
            queryString = `minHarga=${minHarga}&maxHarga=${maxHarga}`;
        } else if (category === "durasi") {
            let minDurasi = document.getElementById("minDurasi").value;
            let maxDurasi = document.getElementById("maxDurasi").value;
            queryString = `minDurasi=${minDurasi}&maxDurasi=${maxDurasi}`;
        } else if (category === "rating") {
            let rating = document.getElementById("searchInput").value;
            queryString = `searchRating=${rating}`;
        }

        if (queryString) {
            window.location.href = "<?= base_url('User/searchPenampilan') ?>?" + queryString;
        }
    });

    let filterBtn = document.getElementById("filterAuditionBtn");
    console.log("Filter Button:", filterBtn); // Harus tampil di console

    if (filterBtn) {
        filterBtn.addEventListener("click", function (event) {
            console.log('Tombol diklik!');

            if (event.target && event.target.id === "filterAuditionBtn") {

                let category = document.getElementById("searchCategory").value;

                if (!category) {
                    alert("Pilih kategori pencarian terlebih dahulu!");
                    return;
                }

                let queryString = "";

                if (category === "tanggal") {
                    let tanggal = document.getElementById("searchInput").value;
                    queryString = `searchTanggal=${tanggal}`;
                } else if (category === "waktu") {
                    let waktu = document.getElementById("searchInput").value;
                    queryString = `searchWaktu=${waktu}`;
                } else if (category === "kota") {
                    let kota = document.getElementById("searchInput").value;
                    queryString = `searchKota=${kota}`;
                } else if (category === "harga") {
                    let minHarga = document.getElementById("minHarga").value;
                    let maxHarga = document.getElementById("maxHarga").value;
                    queryString = `minHarga=${minHarga}&maxHarga=${maxHarga}`;
                } else if (category === "gaji") {
                    let minGaji = document.getElementById("minGaji").value;
                    let maxGaji = document.getElementById("maxGaji").value;
                    queryString = `minGaji=${minGaji}&maxGaji=${maxGaji}`;
                }

                if (queryString) {
                    window.location.href = `${baseUrl}/User/searchAudisi?${queryString}`;
                }
            }
        });

    } else {
        console.error("Tombol filter tidak ditemukan!");
    }
});
