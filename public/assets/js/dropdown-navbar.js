// Menambahkan event listener untuk dropdown secara manual
document.getElementById('page-header-user-dropdown').addEventListener('click', function (event) {
    // Menargetkan dropdown menu dengan ID yang tepat
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    
    if (!dropdownMenu) {
        console.error('Dropdown menu tidak ditemukan!');
        return;
    }
    
    // Menyembunyikan dropdown yang sedang terbuka jika ada
    if (dropdownMenu.classList.contains('show')) {
        bootstrap.Dropdown.getInstance(dropdownMenu)?.hide(); // Menutup dropdown
    } else {
        bootstrap.Dropdown.getOrCreateInstance(dropdownMenu).show(); // Membuka dropdown
    }
    
    event.preventDefault(); // Gunakan untuk mencegah aksi default seperti membuka tautan
});

