<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-mitrateater">
        <div class="container">
            <div class="mitra-item">
                <div class="logo">
                    <?php
                    $logoSrc = isset($mitra['logo']) && file_exists(FCPATH . 'public/' . $mitra['logo'])
                        ? base_url('public/' . $mitra['logo'])
                        : base_url('public/assets/images/logos/mitra1.jpg');
                    ?>
                    <img src="<?= esc($logoSrc) ?>" alt="<?= esc($mitra['nama_teater']) ?>">
                </div>
                <div class="mitra-info">
                    <h6>Profil Komunitas Teater</h6>
                    <h3><?= esc($mitra['nama_teater']) ?></h3>
                    <div class="details">
                        <p><span class="label">Email:</span> <?= esc($mitra['email']) ?></p>
                        <p><span class="label">Alamat:</span> <?= esc($mitra['alamat']) ?></p>
                        <p><span class="label">Berdiri Sejak:</span> <?= esc($mitra['berdiri_sejak']) ?></p>
                        <p><span class="label">Deskripsi:</span> <?= esc($mitra['deskripsi']) ?></p>
                        <p><span class="label">Pementasan Sebelumnya:</span> <?= trim($mitra['history_show'] ?? '') !== '' ? esc($mitra['history_show']) : '-' ?></p>
                        <p><span class="label">Prestasi:</span> <?= trim($mitra['prestasi'] ?? '') !== '' ? esc($mitra['prestasi']) : '-' ?></p>
                        <div class="social-media">
                            <p><span class="label">Sosial Media:</span></p>
                            <?php foreach ($sosial_media as $sosmed) : ?>
                                <div class="platform">
                                    <p><i class="fa-brands fa-<?= esc(strtolower($sosmed['platform_name'])) ?>"></i>
                                        <?= esc($sosmed['acc_mitra']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>