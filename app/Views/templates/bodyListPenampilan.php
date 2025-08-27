<!DOCTYPE html>
<html lang="en">

<body>
    <div class="list-penampilan-audiens">
        <div class="container">
            <div class="header-actions">
                <div class="title">List Pertunjukan Teater</div>

                <!-- Search and Filter -->
                <form id="searchForm" class="search-filter">
                    <select class="form-select w-25" id="searchCategory">
                        <option value="" selected disabled>Cari berdasarkan</option>
                        <option value="tanggal">Cari berdasarkan Tanggal</option>
                        <option value="waktu">Cari berdasarkan Waktu</option>
                        <option value="kota">Cari berdasarkan Kota</option>
                        <option value="harga">Cari berdasarkan Rentang Harga</option>
                        <option value="durasi">Cari berdasarkan Rentang Durasi</option>
                        <option value="rating">Cari berdasarkan Rating Umur</option>
                    </select>

                    <div id="searchInputContainer"></div>

                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>

            <?php if ($page === 'home'): ?>
                <section class="teater-section">
                    <h2>Sedang Tayang</h2>
                    <div class="teater-list">
                        <?php if (!empty($sedangTayang)): ?>
                            <?php
                            $carouselClassSedang = 'poster-carousel';
                            ?>
                            <div class="<?= $carouselClassSedang ?>">
                                <?php foreach ($sedangTayang as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailPenampilan/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>
                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

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
                                            <div class="detail">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><?= esc($teater['rating_umur']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">Belum ada Teater yang sedang tayang.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="teater-section">
                    <h2>Akan Tayang</h2>
                    <div class="teater-list">
                        <?php if (!empty($akanTayang)): ?>
                            <?php
                            $carouselClassAkan = 'poster-carousel';
                            ?>
                            <div class="<?= $carouselClassAkan ?>">
                                <?php foreach ($akanTayang as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailPenampilan/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>
                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

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
                                            <div class="detail">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><?= esc($teater['rating_umur']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">Belum ada Teater yang akan tayang.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($page === 'search'): ?>
                <section class="teater-section">
                    <h2>Hasil Pencarian</h2>
                    <div class="teater-list">
                        <?php if (!empty($penampilan)): ?>
                            <?php $carouselClass = 'poster-carousel'; ?>
                            <div class="<?= $carouselClass ?>">
                                <?php foreach ($penampilan as $teater): ?>
                                    <div class="teater-item" <?php if (session()->has('id_user')): ?>
                                        onclick="window.location.href='<?= base_url('Audiens/detailPenampilan/' . $teater['id_teater']) ?>'"
                                        <?php else: ?> onclick="showLoginPopup()" <?php endif; ?>>

                                        <?php
                                        $posterPath = 'public/' . $teater['poster'];
                                        $posterFullPath = FCPATH . $posterPath;

                                        $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                                            ? base_url($posterPath)
                                            : base_url('public/assets/img/default-poster.png');
                                        ?>
                                        <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">

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
                                            <div class="detail">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><?= esc($teater['rating_umur']) ?></span>
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
                    <p>Silakan login atau registrasi untuk melihat detail penampilan.</p>
                    <button onclick="window.location.href='<?= base_url('User/login') ?>'">Login</button>
                    <button onclick="window.location.href='<?= base_url('Audiens/registration') ?>'">Registrasi</button>
                    <button onclick="closeLoginPopup()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>