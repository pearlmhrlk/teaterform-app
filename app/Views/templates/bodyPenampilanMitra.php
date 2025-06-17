<!DOCTYPE html>
<html lang="en">

<body>
    <div class="list-penampilan-mitra">
        <div class="container">
            <div class="header-actions">
                <div class="title">List Pertunjukan Teater</div>

                <!-- Search and Filter -->
                <div class="search-filter">
                    <select class="form-select w-25" id="searchCategory">
                        <option value="" selected disabled>Cari berdasarkan</option>
                        <option value="tanggal">Cari berdasarkan Tanggal</option>
                        <option value="waktu">Cari berdasarkan Waktu</option>
                        <option value="kota">Cari berdasarkan Kota</option>
                        <option value="harga">Cari berdasarkan Rentang Harga</option>
                        <option value="durasi">Cari berdasarkan Rentang Durasi</option>
                        <option value="rating">Cari berdasarkan Rating Umur</option>
                    </select>

                    <!-- Input pencarian yang akan berubah sesuai pilihan -->
                    <div id="searchInputContainer">
                        <input type="text" class="form-control w-50" id="searchInput" placeholder="Cari...">
                    </div>
                    <button class="btn btn-primary" id="filterShowBtn">Cari</button>
                </div>
                <button class="btn btn-primary" id="addShowBtn">Tambah Pertunjukan</button>
            </div>

            <!-- Show -->
            <?php foreach ($dataPenampilan as $show): ?>
                <!-- Show -->
                <div class="show-item">
                    <div class="poster">
                        <?php
                        $posterPath = $show['teater']['poster'];
                        $posterFullPath = FCPATH . $posterPath;
                        $posterSrc = file_exists($posterFullPath) && !empty($posterPath)
                            ? base_url($posterPath)
                            : base_url('assets/img/default-poster.png');
                        ?>
                        <img class="poster" src="<?= $posterSrc ?>" alt="<?= $show['teater']['judul'] ?>">
                    </div>
                    <div class="show-info">
                        <h4><?= esc($show['teater']['judul']) ?></h4>
                        <div class="details">
                            <p><span class="label">Komunitas/Perusahaan Teater:</span> <?= esc($show['namaKomunitas']['nama']) ?></p>
                            <p><span class="label">Aktor:</span> <?= esc($show['penampilan']['aktor']) ?></p>
                            <p><span class="label">Sutradara:</span> <?= esc($show['penampilan']['sutradara']) ?></p>
                            <p><span class="label">Penulis:</span> <?= esc($show['penampilan']['penulis']) ?></p>
                            <p><span class="label">Staff:</span> <?= esc($show['penampilan']['staff']) ?></p>
                            <p><span class="label">Durasi:</span> <?= esc($show['penampilan']['durasi']) ?> menit</p>
                            <p><span class="label">Rating Umur:</span> <?= esc($show['penampilan']['rating_umur']) ?></p>
                            <p><span class="label">Sinopsis:</span> <?= esc($show['penampilan']['sinopsis']) ?></p>
                            <p><span class="label">Sosial Media:</span> <?= esc($show['sosial_media']) ?: '-' ?></p>
                            <p><span class="label">Web:</span> <?= esc($show['website']) ?: '-' ?></p>
                        </div>

                        <!-- Tabel Jadwal Pertunjukan -->
                        <div class="schedule-table">
                            <h5>Jadwal Pertunjukan</h5>
                            <?php $addedHarga = []; // Reset setiap kali tabel Jadwal Pertunjukan dimulai 
                            ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kota</th>
                                        <th>Tempat</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Harga</th>
                                        <th>Denah Seat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($show['groupedSchedule']) as $kota => $tempatList): ?>
                                        <?php $firstKota = true; ?>
                                        <?php foreach ($tempatList as $tempat => $tanggalList): ?>
                                            <?php $firstTempat = true; ?>
                                            <?php foreach ($tanggalList as $tanggal => $waktuList): ?>
                                                <?php $rowspanTanggal = count($waktuList); ?>
                                                <?php $firstTanggal = true; ?>
                                                <?php foreach ($waktuList as $waktu => $info): ?>
                                                    <tr>
                                                        <?php if ($firstKota): ?>
                                                            <td rowspan="<?= array_sum(array_map(function ($t) {
                                                                                return array_sum(array_map('count', $t));
                                                                            }, $tempatList)); ?>"><?= $kota; ?></td>
                                                            <?php $firstKota = false; ?>
                                                        <?php endif; ?>

                                                        <?php if ($firstTempat): ?>
                                                            <td rowspan="<?= array_sum(array_map('count', $tanggalList)); ?>"><?= $tempat; ?></td>
                                                            <?php $firstTempat = false; ?>
                                                        <?php endif; ?>

                                                        <?php if ($firstTanggal): ?>
                                                            <td rowspan="<?= $rowspanTanggal; ?>"><?= date('d F Y', strtotime($tanggal)); ?></td>
                                                            <?php $firstTanggal = false; ?>
                                                        <?php endif; ?>

                                                        <td><?= $waktu; ?></td>

                                                        <?php
                                                        $hargaList = $info['harga'];
                                                        $tipe = $hargaList[0]['tipe_harga'] ?? '';
                                                        if (strtolower($tipe) === 'gratis') {
                                                            $hargaFormatted = "-";
                                                            $denahFormatted = "-";
                                                        } elseif (empty($hargaList[0]['nama_kategori'])) {
                                                            $hargaFormatted = number_format($hargaList[0]['harga'], 0, ',', '.');
                                                            $denahFormatted = "-";
                                                        } else {
                                                            $hargaFormatted = '';
                                                            foreach ($hargaList as $h) {
                                                                $hargaFormatted .= "<b>{$h['nama_kategori']}</b>: " . number_format($h['harga'], 0, ',', '.') . "<br>";
                                                            }
                                                            $denahFormatted = '<a href="#" class="openSeatMap" data-image="' . base_url('' . $info['denah']) . '">Lihat Denah</a>';
                                                        }
                                                        ?>

                                                        <td><?= $hargaFormatted; ?></td>
                                                        <td><?= $denahFormatted; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="editBtn" data-id="<?= $show['teater']['id_teater'] ?>">Edit Pertunjukan</button>
                        <button class="deleteBtn" data-id="<?= $show['teater']['id_teater'] ?>">Hapus Pertunjukan</button>
                        <button type="button" class="btn btn-info openPopupTiketTerjual" data-id="<?= $show['penampilan']['id_penampilan'] ?>" data-tipe="penampilan">
                            <i class="fas fa-eye"></i> <?= $show['tiket_terjual'] ?? 0 ?> Tiket Terjual
                        </button>

                    </div>
                </div>
            <?php endforeach; ?>

            <div class="show-count">2 Shows</div>
        </div>

        <!-- Popup Denah Seat -->
        <div id="seatMapModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="seatMapImage" src="" alt="Denah Seat" style="width: 100%;">
            </div>
        </div>

        <!-- Popup Form -->
        <div id="showPopup" class="popup">
            <div class="popup-content">
                <h3 id="popupTitle">Tambah Pertunjukan</h3>
                <form id="showForm" class="" action="<?= base_url('MitraTeater/saveShow') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="id_teater" id="id_teater">
                        <input type="hidden" name="tipe_teater" id="tipe_teater" value="penampilan">
                        <!-- Left Side (Form Fields) -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan judul pertunjukan" required>
                            </div>
                            <div class="form-group">
                                <label for="poster">Poster Pertunjukan</label>
                                <input type="file" name="poster" id="poster" class="form-control" accept="image/*" required>
                            </div>
                            <div class="form-group">
                                <label for="sinopsis">Sinopsis</label>
                                <textarea name="sinopsis" id="sinopsis" class="form-control" placeholder="Masukkan sinopsis pertunjukan" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal" class="form-label">Jadwal Pertunjukan</label>
                                <div id="schedule-show-input">
                                    <div class="schedule-show">
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Masukkan Tanggal Pertunjukan" required>
                                        <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required>
                                        <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required>

                                        <!-- Pilihan Harga -->
                                        <select name="tipe_harga" id="tipe_harga" class="form-control" required>
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <!-- Toggle Harga (Muncul saat pilih "Bayar") -->
                                        <div id="nominal-harga" style="display:none;">
                                            <input type="text" name="harga" id="harga" class="form-control" placeholder="Masukkan harga">

                                            <!-- Checkbox untuk Atur Seat (Muncul di dalam toggle harga) -->
                                            <input type="checkbox" id="seat-option"> Atur berdasarkan seat teater

                                            <!-- Toggle Kategori Seat (Muncul jika checkbox dicentang) -->
                                            <div id="seat-config" style="display:none;">
                                                <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Masukkan nama kategori seat (VIP, Premium, dll.)">
                                                <button type="button" id="addSeatCategory">Tambah Harga per Kategori</button>

                                                <!-- Container untuk menampilkan draft kategori -->
                                                <div id="draft-seats"></div>

                                                <input type="file" name="denah_seat[]" id="denah_seat" data-draft-index="0" class="form-control" accept="image/*" multiple>
                                                <div id="denah-file-inputs"></div>
                                            </div>
                                        </div>

                                        <select name="kota[]" id="kota-select" class="form-control" required>
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <!-- Input kota tambahan -->
                                        <div id="lainnya-container" style="display: none;">
                                            <input type="text" name="kota[]" id="kota-input" placeholder="Masukkan kota" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota" name="kota_real" />

                                        <textarea name="tempat" id="tempat" class="form-control" placeholder="Masukkan alamat tempat pertunjukan" required></textarea>
                                    </div>
                                    <button type="button" id="addSchedule">Tambah Jadwal Pertunjukan</button>
                                </div>
                                <div id="draft-schedule"></div>
                                <input type="hidden" name="hidden_schedule" value="">
                            </div>
                            <div class="form-group">
                                <label for="penulis">Penulis</label>
                                <input type="text" name="penulis" id="penulis" class="form-control" placeholder="Masukkan nama penulis" required>
                            </div>
                        </div>

                        <!-- Right Side (Form Fields) -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran">Link Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran" class="form-control" placeholder="Masukkan url web" required>
                            </div>
                            <div class="form-group">
                                <label for="sutradara">Sutradara</label>
                                <input type="text" name="sutradara" id="sutradara" class="form-control" placeholder="Masukkan nama sutradara" required>
                            </div>
                            <div class="form-group">
                                <label for="staff">Staff</label>
                                <input type="text" name="staff" id="staff" class="form-control" placeholder="Masukkan nama staff" required>
                            </div>
                            <div class="form-group">
                                <label for="aktor">Aktor</label>
                                <input type="text" name="aktor" id="aktor" class="form-control" placeholder="Masukkan nama aktor" required>
                            </div>
                            <div class="form-group">
                                <label for="durasi">Durasi (menit)</label>
                                <input type="number" name="durasi" id="durasi" class="form-control" placeholder="Masukkan durasi pertunjukan" required>
                            </div>
                            <div class="form-group">
                                <label for="rating_umur[]">Rating Umur</label>
                                <select name="rating_umur[]" id="rating_umur" class="form-control" required>
                                    <option selected disabled>Pilih Rating Umur</option>
                                    <option>Semua Umur (SU)</option>
                                    <option>13+</option>
                                    <option>17+</option>
                                    <option>21+</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="mitra_teater">Pilih Mitra Teater</label>
                                <select id="mitra_teater" name="mitra_teater" class="form-control" required>
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_platform_sosmed[]" class="form-label">Sosial Media Teater</label>
                                <div>
                                    <input type="checkbox" id="same-sosmed"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-input">
                                    <div class="social-media-teater">
                                        <select name="id_platform_sosmed[]" id="platform_name" class="form-control" aria-label="Platform" required>
                                            <option value="" selected disabled>Choose Platform</option>
                                            <option value="1" data-nama="instagram">Instagram</option>
                                            <option value="2" data-nama="twitter">Twitter</option>
                                            <option value="3" data-nama="facebook">Facebook</option>
                                            <option value="4" data-nama="threads">Threads</option>
                                            <option value="5" data-nama="tiktok">Tiktok</option>
                                            <option value="7" data-nama="telegram">Telegram</option>
                                            <option value="8" data-nama="discord">Discord</option>
                                            <option value="10" data-nama="line">LINE</option>
                                            <option value="9" data-nama="whatsapp">Whatsapp</option>
                                            <option value="6" data-nama="youtube">Youtube</option>
                                        </select>

                                        <input type="text" name="acc_name[]" id="acc_name" class="form-control" placeholder="Enter your account name">
                                    </div>
                                    <button id="add-account-btn" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                </div>
                                <div id="draft-accounts"></div>
                                <input type="hidden" name="hidden_accounts" value="">
                            </div>
                            <div class="form-group">
                                <label for="judul_web[]" class="form-label">Website Teater</label>
                                <div id="website-input">
                                    <div class="website-teater">
                                        <input type="text" name="judul_web[]" id="judul_web" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web" class="form-control" placeholder="Masukkan url web">
                                    </div>
                                    <button id="add-web-btn" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web"></div>
                                <input type="hidden" name="hidden_web" value="">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="aturPeriodeCheckbox" name="atur_periode" value="1"
                                        <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                    Atur periode pemesanan secara manual
                                </label>
                            </div>

                            <div id="periodeManualFields" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai" name="daftar_mulai" class="form-control"
                                        value="<?= isset($teater['daftar_mulai']) ? $teater['daftar_mulai'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir" name="daftar_berakhir" class="form-control"
                                        value="<?= isset($teater['daftar_berakhir']) ? $teater['daftar_berakhir'] : '' ?>">
                                </div>
                            </div>

                            <div id="infoOtomatis" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal pertunjukan.
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" id="submitBtn" class="btn btn-success">Simpan</button>
                            <button type="button" id="cancelBtn" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Popup Edit Form -->
        <div id="editPopup" class="popup">
            <div class="popup-content">
                <h3 id="popupTitleEdit">Edit Pertunjukan</h3>
                <form id="editForm" method="post" class="" action="<?= base_url('MitraTeater/saveShow') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="id_teater" id="id_teater_edit" value="">
                        <input type="hidden" name="tipe_teater" id="tipe_teater_edit" value="">
                        <input type="hidden" name="id_user" value="">
                        <input type="hidden" name="id_penampilan" value="">

                        <!-- Left Side (Form Fields) -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul_edit">Judul</label>
                                <input type="text" name="judul" id="judul_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="poster_edit">Poster Pertunjukan</label>
                                <input type="file" name="poster" id="poster_edit" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="sinopsis_edit">Sinopsis</label>
                                <textarea name="sinopsis" id="sinopsis_edit" class="form-control" value=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_edit" class="form-label">Jadwal Pertunjukan</label>
                                <div id="schedule-show-edit">
                                    <div class="schedule-show">
                                        <input type="hidden" name="id_schedule[]" id="id_schedule_edit" value="">

                                        <input type="date" name="tanggal" id="tanggal_edit" class="form-control">
                                        <input type="time" name="waktu_mulai" id="waktu_mulai_edit" class="form-control">
                                        <input type="time" name="waktu_selesai" id="waktu_selesai_edit" class="form-control">

                                        <!-- Pilihan Harga -->
                                        <select name="tipe_harga" id="tipe_harga_edit" class="form-control">
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <!-- Toggle Harga (Muncul saat pilih "Bayar") -->
                                        <div id="nominal-harga-edit" style="display:none;">
                                            <input type="number" name="harga" id="harga_edit" class="form-control" placeholder="Masukkan harga">

                                            <input type="checkbox" id="seat-option-edit"> Atur berdasarkan seat teater

                                            <div id="seat-config-edit" style="display:none;">
                                                <input type="text" name="nama_kategori" id="nama_kategori_edit" class="form-control" placeholder="ex: VIP, Premium, dll.">
                                                <button type="button" id="addSeatCategoryEdit">Tambah Harga per Kategori</button>

                                                <div id="draft-seats-edit"></div>

                                                <input type="file" name="denah_seat[]" id="denah_seat_edit" data-draft-index="0" class="form-control" accept="image/*">
                                                <div id="denah-file-inputs-edit"></div>

                                                <input type="hidden" id="edit-mode-flag" value="1">
                                                <input type="hidden" id="prefill_seat_kategori" value='<?= json_encode($seatKategoris ?? []) ?>'>
                                                <input type="hidden" id="hidden_denah_edit" value="<?= $denahFilename ?? '' ?>">
                                            </div>
                                        </div>

                                        <select name="kota[]" id="kota-select-edit" class="form-control">
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <!-- Input kota tambahan -->
                                        <div id="lainnya-container-edit" style="display: none;">
                                            <input type="text" name="kota[]" id="kota-edit" placeholder="Masukkan kota lainnya" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota-edit" name="kota_real" />

                                        <textarea name="tempat" id="tempat_edit" class="form-control" placeholder="Masukkan alamat tempat pertunjukan"></textarea>
                                    </div>
                                    <button type="button" id="editSchedule">Tambah Jadwal Pertunjukan</button>
                                </div>
                                <div id="draft-schedule-edit"></div>
                                <input type="hidden" name="hidden_schedule" id="hidden_schedule_edit">
                                <input type="hidden" name="deleted_schedules" id="deleted_schedules_edit" value="[]">
                            </div>
                            <div class="form-group">
                                <label for="penulis_edit">Penulis</label>
                                <input type="text" name="penulis" id="penulis_edit" class="form-control" value="">
                            </div>
                        </div>

                        <!-- Right Side (Form Fields) -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran_edit">Url Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="sutradara_edit">Sutradara</label>
                                <input type="text" name="sutradara" id="sutradara_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="staff_edit">Staff</label>
                                <input type="text" name="staff" id="staff_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="aktor_edit">Aktor</label>
                                <input type="text" name="aktor" id="aktor_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="durasi_edit">Durasi (menit)</label>
                                <input type="number" name="durasi" min="0" id="durasi_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="rating_umur_edit">Rating Umur</label>
                                <select name="rating_umur" id="rating_umur_edit" class="form-control">
                                    <option selected disabled>Pilih Rating Umur</option>
                                    <option>Semua Umur (SU)</option>
                                    <option>13+</option>
                                    <option>17+</option>
                                    <option>21+</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="mitra_teater_edit">Pilih Mitra Teater</label>
                                <select id="mitra_teater_edit" name="mitra_teater" class="form-control">
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="platform_name_edit" class="form-label">Sosial Media Teater</label>
                                <input type="hidden" name="id_teater_sosmed[]" id="id_sosmed_edit" value="">

                                <div>
                                    <input type="checkbox" id="same-sosmed-edit"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-edit">
                                    <div class="social-media-mitra">
                                        <select name="id_platform_sosmed[]" id="platform_name_edit" class="form-control"
                                            aria-label="Platform">
                                            <option value="" selected disabled>Choose Platform</option>
                                            <option value="1" data-nama="instagram">Instagram</option>
                                            <option value="2" data-nama="twitter">Twitter</option>
                                            <option value="3" data-nama="facebook">Facebook</option>
                                            <option value="4" data-nama="threads">Threads</option>
                                            <option value="5" data-nama="tiktok">Tiktok</option>
                                            <option value="7" data-nama="telegram">Telegram</option>
                                            <option value="8" data-nama="discord">Discord</option>
                                            <option value="10" data-nama="line">LINE</option>
                                            <option value="9" data-nama="whatsapp">Whatsapp</option>
                                            <option value="6" data-nama="youtube">Youtube</option>
                                        </select>

                                        <input type="text" name="acc_name[]" id="acc_name_edit" class="form-control" placeholder="Enter your account name">
                                    </div>
                                    <button id="add-account-btn-edit" type="button" class="btn btn-danger add-item">Add
                                        Another Account</button>
                                </div>
                                <div id="draft-accounts-edit"></div>
                                <input type="hidden" name="hidden_accounts" id="hidden_accounts_edit">
                            </div>
                            <div class="form-group">
                                <label for="judul_web_edit" class="form-label">Website Teater</label>
                                <div id="website-edit">
                                    <div class="website-teater">
                                        <input type="hidden" name="id_teater_web[]" id="id_web_edit" value="">
                                        <input type="text" name="judul_web[]" id="judul_web_edit" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web_edit" class="form-control" placeholder="Masukkan url web">
                                    </div>
                                    <button id="add-web-btn-edit" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web-edit"></div>
                                <input type="hidden" name="hidden_web" id="hidden_web_edit">
                                <input type="hidden" name="deleted_webs" id="deleted_webs">
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="aturPeriodeCheckboxEdit" name="atur_periode" value="1"
                                    <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                Atur periode pemesanan secara manual
                            </div>

                            <div id="periodeManualFieldsEdit" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai_edit">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai_edit" name="daftar_mulai" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir_edit">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir_edit" name="daftar_berakhir" class="form-control" value="">
                                </div>
                            </div>

                            <div id="infoOtomatisEdit" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal audisi.
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" id="submitBtnEdit" class="btn btn-success">Simpan</button>
                            <button type="button" id="cancelEditBtn" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="deletePopup" class="popup-overlay">
            <div class="popup-box">
                <h3>Konfirmasi Penghapusan</h3>
                <p>Apakah Anda yakin ingin menghapus pertunjukan teater ini?</p>
                <div class="popup-actions">
                    <button id="confirmDelete" class="confirm-btn">Ya, Hapus</button>
                    <button id="cancelDelete" class="cancel-btn">Batal</button>
                </div>
            </div>
        </div>

        <!-- Modal Popup -->
        <div id="popupTiketTerjual" class="custom-modal" style="display: none;">
            <div class="custom-modal-content">
                <button class="closePopup">&times;</button>
                <h5>Daftar Audiens yang Mendaftar</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableBookingList">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal Mendaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">Klik tombol untuk melihat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="totalTiketTerjual" class="mt-3 fw-bold text-end"></div>
            </div>
        </div>
    </div>
</body>

</html>