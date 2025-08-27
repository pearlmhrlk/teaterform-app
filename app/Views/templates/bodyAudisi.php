<!DOCTYPE html>
<html lang="en">

<body>
    <div class="list-audisi-audiens">
        <div class="container">
            <div class="header-actions">
                <div class="title">List Audisi Teater</div>

                <!-- Search and Filter -->
                <form id="searchForm" class="search-filter">
                    <select class="form-select w-25" id="searchCategory">
                        <option value="" selected disabled>Cari berdasarkan</option>
                        <option value="tanggal">Cari berdasarkan Tanggal</option>
                        <option value="waktu">Cari berdasarkan Waktu</option>
                        <option value="kota">Cari berdasarkan Kota</option>
                        <option value="gaji">Cari berdasarkan Rentang Gaji</option>
                    </select>

                    <div id="searchInputContainer">
                        <!-- Default input untuk audisi -->
                        <input type="text" class="form-control w-50" id="searchInputAudisi" placeholder="Cari...">
                    </div>

                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>

            <?php if ($page === 'home'): ?>
                <section class="teater-section">
                    <h2>Audisi Aktor</h2>
                    <div class="teater-list">
                        <?php if (!empty($audisiAktor)): ?>
                            <?php
                            $carouselClassAktor = 'poster-carousel';
                            ?>
                            <div class="<?= $carouselClassAktor ?>">
                                <?php foreach ($audisiAktor as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailAudisiAktor/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>
                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

                                        <h3 class="karakter_audisi"><?= esc($teater['karakter_audisi']) ?></h3>
                                        <h3 class="judul"><?= esc($teater['judul']) ?></h3>
                                        <p class="name"><?= esc($teater['komunitas_teater']) ?></p>

                                        <div class="teater-details">
                                            <div class="detail">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <span><?= esc($teater['lokasi_teater']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-calendar"></i>
                                                <span><?= esc($teater['tanggal']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-clock"></i>
                                                <span><?= esc($teater['waktu']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">Belum ada Audisi aktor.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="teater-section">
                    <h2>Audisi Staff</h2>
                    <div class="teater-list">
                        <?php if (!empty($audisiStaff)): ?>
                            <?php
                            $carouselClassStaff = 'poster-carousel';
                            ?>
                            <div class="<?= $carouselClassStaff ?>">
                                <?php foreach ($audisiStaff as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailAudisiStaff/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>
                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

                                        <h3 class="jenis_staff"><?= esc($teater['jenis_staff']) ?></h3>
                                        <h3 class="judul"><?= esc($teater['judul']) ?></h3>
                                        <p class="name"><?= esc($teater['komunitas_teater']) ?></p>

                                        <div class="teater-details">
                                            <div class="detail">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <span><?= esc($teater['lokasi_teater']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-calendar"></i>
                                                <span><?= esc($teater['tanggal']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-clock"></i>
                                                <span><?= esc($teater['waktu']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">Belum ada Audisi Staff.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($page === 'search'): ?>
                <section class="teater-section">
                    <h2>Hasil Pencarian</h2>
                    <div class="teater-list">
                        <?php if (!empty($results)): ?>
                            <?php $carouselClass = 'poster-carousel'; ?>
                            <div class="<?= $carouselClass ?>">
                                <?php foreach ($results as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailAudisiAktor/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>
                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

                                        <div class="teater-content">
                                            <?php if (!empty($teater['role'])): ?>
                                                <h3 class="role">
                                                    <?= esc($teater['role']) ?> (<?= esc($teater['role_type']) ?>)
                                                </h3>
                                            <?php endif; ?>

                                            <h3 class="judul"><?= esc($teater['judul']) ?></h3>
                                            <p class="name"><?= esc($teater['komunitas_teater']) ?></p>
                                        </div>

                                        <div class="teater-details">
                                            <div class="detail">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <span><?= esc($teater['lokasi_teater']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-calendar"></i>
                                                <span><?= esc($teater['tanggal']) ?></span>
                                            </div>
                                            <div class="detail">
                                                <i class="fa-regular fa-clock"></i>
                                                <span><?= esc($teater['waktu']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">Tidak ada hasil pencarian.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Modal Pop-up -->
            <div id="loginPopup" class="popup-container">
                <div class="popup-content">
                    <p>Silakan login atau registrasi untuk melihat detail audisi.</p>
                    <button onclick="window.location.href='<?= base_url('User/login') ?>'">Login</button>
                    <button onclick="window.location.href='<?= base_url('Audiens/registration') ?>'">Registrasi</button>
                    <button onclick="closeLoginPopup()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>