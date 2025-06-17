<?php
// Tentukan role berdasarkan session
$role = 'user'; // Default jika user belum login

if (isset($_SESSION['id_role'])) {
    if ($_SESSION['id_role'] == 3) {
        $role = 'admin';  // Admin
    } elseif ($_SESSION['id_role'] == 2) {
        $role = 'mitra';  // Mitra Teater
    } elseif ($_SESSION['id_role'] == 1) {
        $role = 'audiens';   // Audiens
    }
}

// Tentukan base URL berdasarkan role
function getDetailMitraUrl($id_mitra, $role)
{
    if ($role === 'admin') {
        return base_url('Admin/detailMitraTeater/' . $id_mitra);
    } else if ($role === 'audiens') {
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
                            // Simpan file di dalam public/uploads/
                            $logoRelativePath = 'uploads/' . $mitra['logo'];
                            $logoFullPath = FCPATH . $logoRelativePath;

                            // Jika file ada dan tidak kosong, gunakan file itu
                            $logoSrc = file_exists($logoFullPath) && !empty($mitra['logo'])
                                ? base_url($logoRelativePath)
                                : base_url('assets/images/logos/mitra1.jpg');
                            ?>

                            <div class="logo">
                                <img src="<?= esc($logoSrc) ?>" alt="<?= esc($m['nama_teater']) ?>" class="mitra-logo">
                                <div class="card-body">
                                    <h4><?= esc($m['nama_teater']) ?></h4>
                                    <p>Berdiri sejak: <?= esc($m['berdiri_sejak']) ?></p>
                                    <a href="<?= getDetailMitraUrl($m['id_mitra'], $role) ?>" class="btn-detail">Lihat Profil</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
            </div>
</body>