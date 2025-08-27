<?php
// Tentukan role berdasarkan session
$role = 'user'; // Default jika user belum login

if (isset($_SESSION['id_role'])) {
    if ($_SESSION['id_role'] == 2) {
        $role = 'mitra';  // Mitra Teater
    } elseif ($_SESSION['id_role'] == 1) {
        $role = 'audiens';   // Audiens
    }
}

// Tentukan base URL berdasarkan role
function getDetailMitraUrl($id_mitra, $role)
{
    if ($role === 'audiens') {
        return base_url('Audiens/detailMitraTeater/' . $id_mitra);
    } else if ($role === 'mitra') {
        return base_url('MitraTeater/detailMitraTeater/' . $id_mitra);
    } else {
        return base_url('User/detailMitraTeater/' . $id_mitra);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <div class="list-mitrateater">
        <div class="container">
            <h2>Daftar Mitra Teater</h2>
            <div class="grid-container">
                <?php foreach ($mitra as $m) : ?>
                    <div class="card">
                        <div class="logo">
                            <?php
                            // Ambil nilai logo dari database
                            $logoRelativePath = 'public/' . $m['logo']; // misalnya: uploads/logo/1740821416_xxx.jpeg
                            $logoFullPath = FCPATH . $logoRelativePath;

                            // Cek apakah file tersebut ada dan bukan kosong
                            $logoSrc = (file_exists($logoFullPath) && !empty($m['logo']))
                                ? base_url($logoRelativePath)
                                : base_url('public/assets/images/logos/mitra1.jpg'); // fallback jika tidak ada
                            ?>

                            <img src="<?= esc($logoSrc) ?>" alt="<?= esc($m['nama_teater']) ?>" class="mitra-logo">
                            <div class="card-body">
                                <h4><?= esc($m['nama_teater']) ?></h4>
                                <p>Berdiri sejak: <?= esc($m['berdiri_sejak']) ?></p>
                                <a href="<?= getDetailMitraUrl($m['id_mitra'], $role) ?>" class="btn-detail">Lihat Profil</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>