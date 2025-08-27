document.addEventListener("DOMContentLoaded", function () {
    let categorySelect = document.getElementById("searchCategory");
    let inputContainer = document.getElementById("searchInputContainer");

    function updateSearchInput(selected) {
        let newInput = "";

        if (selected === "tanggal") {
    newInput = `<input type="date" class="form-control w-50" id="searchInput" name="searchTanggal">`;
} else if (selected === "waktu") {
    newInput = `<input type="time" class="form-control w-50" id="searchInput" name="searchWaktu">`;
} else if (selected === "kota") {
    newInput = `
        <select class="form-select w-50" id="searchInput" name="searchKota">
            <option value="">Pilih Kota</option>
            <option value="Jakarta">Jakarta</option>
            <option value="Bogor">Bogor</option>
            <option value="Depok">Depok</option>
            <option value="Tangerang">Tangerang</option>
            <option value="Bekasi">Bekasi</option>
        </select>
    `;
} else if (selected === "harga") {
    newInput = `
        <div class="d-flex gap-2">
            <input type="number" class="form-control w-25" id="minHarga" name="minHarga" placeholder="Min Harga">
            <input type="number" class="form-control w-25" id="maxHarga" name="maxHarga" placeholder="Max Harga">
        </div>
    `;
} else if (selected === "durasi") {
    newInput = `
        <div class="d-flex gap-2">
            <input type="number" class="form-control w-25" id="minDurasi" name="minDurasi" placeholder="Min Durasi (menit)">
            <input type="number" class="form-control w-25" id="maxDurasi" name="maxDurasi" placeholder="Max Durasi (menit)">
        </div>
    `;
} else if (selected === "rating") {
    newInput = `
        <select class="form-select w-50" id="searchInput" name="searchRating">
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
    updateSearchInput("");
    
    // ✅ Tangani submit form sama seperti penampilan
    const form = document.querySelector('#searchForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const queryString = new URLSearchParams(new FormData(form)).toString();

            if (typeof searchUrl === "undefined" || !searchUrl) {
                window.location.href = window.location.pathname + (queryString ? `?${queryString}` : "");
            } else {
                window.location.href = `${searchUrl}${queryString ? `?${queryString}` : ""}`;
            }
        });
    }
});
