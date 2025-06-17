<!DOCTYPE html>
<html lang="en">

<body>
    <div class="list-audisi-mitra">
        <div class="container">
            <div class="header-actions">
                <div class="title">List Audisi Teater</div>

                <!-- Search and Filter -->
                <div class="search-filter">
                    <select class="form-select w-25" id="searchCategory">
                        <option value="" selected disabled>Cari berdasarkan</option>
                        <option value="kategori">Cari berdasarkan Kategori Audisi</option>
                        <option value="tanggal">Cari berdasarkan Tanggal</option>
                        <option value="waktu">Cari berdasarkan Waktu</option>
                        <option value="kota">Cari berdasarkan Kota</option>
                        <option value="harga">Cari berdasarkan Rentang Harga</option>
                        <option value="gaji">Cari berdasarkan Rentang Gaji</option>
                    </select>

                    <!-- Input pencarian yang akan berubah sesuai pilihan -->
                    <div id="searchInputContainer">
                        <input type="text" class="form-control w-50" id="searchInput" placeholder="Cari...">
                    </div>
                    <button class="btn btn-primary" id="filterBtn">Cari</button>
                </div>

                <div class="add-audition-buttons">
                    <button class="btn btn-primary" id="addAuditionActorBtn">Tambah Audisi Aktor</button>
                    <button class="btn btn-primary" id="addAuditionStaffBtn">Tambah Audisi Staff</button>
                </div>
            </div>

            <?php foreach ($dataAudisi as $audition) : ?>
                <div class="audition-item">
                    <div class="poster">
                        <?php
                        $posterPath = $audition['teater']['poster'];
                        $posterFullPath = FCPATH . $posterPath;

                        $posterSrc = file_exists($posterFullPath) && !empty($posterPath)
                            ? base_url($posterPath)
                            : base_url('assets/img/default-poster.png');
                        ?>
                        <img class="poster"
                            src="<?= $posterSrc ?>"
                            alt="<?= $audition['teater']['judul'] ?>">
                    </div>
                    <div class="audition-info">
                        <span class="badge badge-category"><?= $audition['audisi']['nama_kategori'] ?></span>

                        <h4><?= $audition['teater']['judul'] ?></h4>
                        <div class="details">
                            <p><span class="label">Komunitas/Perusahaan Teater:</span> <?= $audition['namaKomunitas']['nama'] ?></p>

                            <?php if ($audition['audisi']['id_kategori'] == '2') : ?>
                                <p><span class="label">Jenis Staff:</span> <?= $audition['staff']['jenis_staff'] ?: 'Terbuka untuk semua staff' ?></p>
                                <p><span class="label">Deskripsi Pekerjaan:</span> <?= $audition['staff']['jobdesc_staff'] ?: '-' ?></p>
                                <p><span class="label">Persyaratan Staff:</span> <?= $audition['audisi']['syarat'] ?></p>
                                <p><span class="label">Gaji Staff:</span> Rp<?= number_format($audition['audisi']['gaji'], 0, ',', '.') ?></p>
                            <?php elseif ($audition['audisi']['id_kategori'] == '1') : ?>
                                <p><span class="label">Terbuka untuk karakter:</span> <?= $audition['aktor']['karakter_audisi'] ?: 'Terbuka untuk semua karakter' ?></p>
                                <p><span class="label">Deskripsi Karakter:</span> <?= $audition['aktor']['deskripsi_karakter'] ?: '-' ?></p>
                                <p><span class="label">Persyaratan Aktor:</span> <?= $audition['audisi']['syarat'] ?></p>
                                <p><span class="label">Gaji Aktor:</span> Rp<?= number_format($audition['audisi']['gaji'], 0, ',', '.') ?>,-</p>
                            <?php endif; ?>

                            <p><span class="label">Persyaratan Dokumen:</span> <?= $audition['audisi']['syarat_dokumen'] ?: '-' ?></p>
                            <p><span class="label">Sutradara:</span> <?= $audition['teater']['sutradara'] ?></p>
                            <p><span class="label">Penulis:</span> <?= $audition['teater']['penulis'] ?></p>
                            <p><span class="label">Staff:</span> <?= $audition['teater']['staff'] ?: '-' ?></p>
                            <p><span class="label">Komitmen:</span> <?= $audition['audisi']['komitmen'] ?: '-' ?></p>
                            <p><span class="label">Sinopsis:</span> <?= $audition['teater']['sinopsis'] ?: '-' ?></p>
                            <p><span class="label">Sosial Media:</span> <?= $audition['sosial_media'] ?: '-' ?></p>
                            <p><span class="label">Website:</span> <?= $audition['website'] ?: '-' ?></p>
                        </div>

                        <!-- Tabel Jadwal Audisi -->
                        <div class="schedule-table">
                            <h5>Jadwal Audisi Aktor</h5>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kota</th>
                                        <th>Tempat</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($audition['grouped_schedule'] as $kota => $tempatList): ?>
                                        <?php $firstKota = true; ?>
                                        <?php foreach ($tempatList as $tempat => $tanggalList): ?>
                                            <?php $firstTempat = true; ?>
                                            <?php foreach ($tanggalList as $tanggal => $waktuList): ?>
                                                <?php foreach ($waktuList as $index => $item): ?>
                                                    <tr>
                                                        <?php if ($firstKota): ?>
                                                            <td rowspan="<?= array_sum(array_map(fn($t) => array_reduce($t, fn($carry, $val) => $carry + count($val), 0), $tempatList)); ?>">
                                                                <?= $kota; ?>
                                                            </td>
                                                            <?php $firstKota = false; ?>
                                                        <?php endif; ?>

                                                        <?php if ($firstTempat): ?>
                                                            <td rowspan="<?= array_reduce($tanggalList, fn($carry, $val) => $carry + count($val), 0); ?>">
                                                                <?= $tempat; ?>
                                                            </td>
                                                            <?php $firstTempat = false; ?>
                                                        <?php endif; ?>

                                                        <?php if ($index === 0): ?>
                                                            <td rowspan="<?= count($waktuList); ?>">
                                                                <?= date('d F Y', strtotime($tanggal)); ?>
                                                            </td>
                                                        <?php endif; ?>

                                                        <td><?= $item['waktu']; ?></td>
                                                        <td><?= $item['harga_display']; ?></td>
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
                        <button class="editBtn" data-id="<?= $audition['teater']['id_teater'] ?>" data-kategori="<?= $audition['audisi']['id_kategori'] ?>">
                            Edit Audisi
                        </button>
                        <button class="deleteBtn" data-id="<?= $audition['teater']['id_teater'] ?>">Hapus Audisi</button>
                        <button><i class="fas fa-eye"></i> </?= $audition['tiket_terjual'] ?> Tiket Terjual</button>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="audition-count">2 Shows</div>
        </div>

        <!-- Popup Form -->
        <div id="auditionPopupAktor" class="popup">
            <div class="popup-content">
                <h3 id="popupTitleAktor">Tambah Audisi Aktor</h3>
                <form id="auditionFormAktor" class="" action="<?= base_url('MitraTeater/saveAuditionAktor') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="tipe_teater" id="tipe_teater_aktor" value="audisi">
                        <input type="hidden" name="id_kategori" id="id_kategori_aktor" value="1">
                        <!-- Left Side (Form Fields) -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul_aktor">Judul</label>
                                <input type="text" name="judul" id="judul_aktor" class="form-control" placeholder="Masukkan judul pertunjukan yang diaudisikan" required>
                            </div>
                            <div class="form-group">
                                <label for="poster_aktor">Poster Audisi</label>
                                <input type="file" name="poster" id="poster_aktor" class="form-control" accept="image/*" required>
                            </div>
                            <div class="form-group">
                                <label for="sinopsis_aktor">Sinopsis (opsional)</label>
                                <textarea id="sinopsis_aktor" name="sinopsis" class="form-control" placeholder="Masukkan sinopsis pertunjukan yang diaudisikan"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="karakter_audisi">Karakter yang diaudisikan</label>
                                <input type="checkbox" id="all-chara"> Audisi terbuka untuk semua tokoh<br>

                                <div id="aktor-input-section" margin-top: 1rem;>
                                    <input type="text" name="karakter_audisi" id="karakter_audisi" class="form-control" placeholder="Masukkan nama karakter" required>
                                </div>
                            </div>
                            <div id="deskripsi-karakter-wrapper">
                                <div class="form-group" data-karakter="default">
                                    <label for="deskripsi_karakter">Deskripsi Karakter</label>
                                    <textarea id="deskripsi_karakter" name="deskripsi_karakter" class="form-control" placeholder="Masukkan deskripsi karakter yang diaudisikan"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_aktor" class="form-label">Jadwal Audisi</label>
                                <div id="schedule-aktor-input">
                                    <div class="schedule-audition">
                                        <input type="date" name="tanggal" id="tanggal_aktor" class="form-control" placeholder="Masukkan Tanggal Audisi" required>
                                        <input type="time" name="waktu_mulai" id="waktu_mulai_aktor" class="form-control" placeholder="Masukkan Waktu Audisi" required>
                                        <input type="time" name="waktu_selesai" id="waktu_selesai_aktor" class="form-control" placeholder="Masukkan Waktu Audisi" required>

                                        <!-- Pilihan Harga -->
                                        <select name="tipe_harga" id="tipe_harga_aktor" class="form-control" required>
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <!-- Toggle Harga (Muncul saat pilih "Bayar") -->
                                        <div id="nominal-harga-aktor" style="display:none;">
                                            <input type="number" name="harga" id="harga_aktor" class="form-control" placeholder="Masukkan harga">
                                        </div>

                                        <!-- <select name="kota[]" id="kota-select" class="form-control" required onchange="toggleLainnya(this)"> -->
                                        <select name="kota[]" id="kota-select-aktor" class="form-control" required>
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <!-- Input kota tambahan -->
                                        <div id="lainnya-container-aktor" style="display: none;">
                                            <!-- <input type="text" name="kota[]" id="kota-input" placeholder="Masukkan kota" class="form-control" oninput="updateKotaValue(this)"> -->
                                            <input type="text" name="kota[]" id="kota-input-aktor" placeholder="Masukkan kota lainnya" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota-aktor" name="kota_real" />

                                        <textarea name="tempat" id="tempat_aktor" class="form-control" placeholder="Masukkan alamat tempat pertunjukan" required></textarea>
                                    </div>
                                    <button type="button" id="addScheduleAktor">Tambah Jadwal Audisi</button>
                                </div>
                                <div id="draft-schedule-aktor"></div>
                                <input type="hidden" name="hidden_schedule" id="hidden_schedule_aktor" value="">
                            </div>
                            <div class="form-group">
                                <label for="penulis_aktor">Penulis</label>
                                <input type="text" id="penulis_aktor" name="penulis" class="form-control" placeholder="Masukkan nama penulis" required>
                            </div>
                        </div>

                        <!-- Right Side (Form Fields) -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran_aktor">URL Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran_aktor" class="form-control" placeholder="Masukkan url web">
                            </div>
                            <div class="form-group">
                                <label for="syarat_aktor">Persyaratan aktor</label>
                                <textarea id="syarat_aktor" name="syarat" class="form-control" placeholder="Masukkan persyaratan aktor" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="syarat_dokumen_aktor">Persyaratan dokumen (opsional)</label>
                                <textarea id="syarat_dokumen_aktor" name="syarat_dokumen" class="form-control" placeholder="Masukkan persyaratan dokumen"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sutradara_aktor">Sutradara</label>
                                <input type="text" name="sutradara" id="sutradara_aktor" class="form-control" placeholder="Masukkan nama sutradara" required>
                            </div>
                            <div class="form-group">
                                <label for="staff_aktor">Staff (opsional)</label>
                                <input type="text" name="staff" id="staff_aktor" class="form-control" placeholder="Masukkan nama staff">
                            </div>
                            <div class="form-group">
                                <label for="gaji_aktor">Gaji Aktor (opsional)</label>
                                <input type="checkbox" id="gaji_dirahasiakan_aktor" name="gaji_dirahasiakan"> Gaji tidak disebutkan nominalnya (dirahasiakan)
                                <input type="number" class="form-control" id="gaji_aktor" name="gaji" placeholder="Masukkan nominal gaji">
                            </div>
                            <div class="form-group">
                                <label for="komitmen_aktor">Komitmen sebagai Aktor (opsional)</label>
                                <textarea name="komitmen" id="komitmen_aktor" class="form-control" placeholder="Masukkan komitmen aktor"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="mitra_teater_aktor">Mitra Teater</label>
                                <select id="mitra_teater_aktor" name="mitra_teater" class="form-control" required>
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="platform_name_aktor" class="form-label">Sosial Media Teater</label>
                                <div>
                                    <!-- <input type="checkbox" id="same-sosmed" onchange="copySosmed()"> Sama dengan sosial media komunitas -->
                                    <input type="checkbox" id="same-sosmed_aktor"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-input-aktor">
                                    <div class="social-media-mitra">
                                        <select name="id_platform_sosmed[]" id="platform_name_aktor" class="form-control" aria-label="Platform">
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

                                        <input type="text" name="acc_name[]" id="acc_name_aktor" class="form-control" placeholder="Masukkan nama akun sosmed">
                                        <?= !empty(\Config\Services::validation()->getError('acc_name')) ? \Config\Services::validation()->getError('acc_name') : \Config\Services::validation()->getError('hidden_accounts') ?>
                                    </div>
                                    <button id="add-account-btn-aktor" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                </div>
                                <div id="draft-accounts-aktor"></div>
                                <input type="hidden" name="hidden_accounts" id="hidden_accounts_aktor" value="[]">
                            </div>
                            <div class="form-group">
                                <label for="judul_web_aktor" class="form-label">Website Teater (opsional)</label>
                                <div id="website-input-aktor">
                                    <div class="website-teater">
                                        <input type="text" name="judul_web[]" id="judul_web_aktor" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web_aktor" class="form-control" placeholder="Masukkan url web">
                                    </div>
                                    <button id="add-web-btn-aktor" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web-aktor"></div>
                                <input type="hidden" name="hidden_web" id="hidden_web_aktor" value="">
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="aturPeriodeCheckboxAktor" name="atur_periode" value="1"
                                    <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                Atur periode pemesanan secara manual
                            </div>

                            <div id="periodeManualFieldsAktor" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai_aktor">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai_aktor" name="daftar_mulai" class="form-control"
                                        value="<?= isset($teater['daftar_mulai']) ? $teater['daftar_mulai'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir_aktor">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir_aktor" name="daftar_berakhir" class="form-control"
                                        value="<?= isset($teater['daftar_berakhir']) ? $teater['daftar_berakhir'] : '' ?>">
                                </div>
                            </div>

                            <div id="infoOtomatisAktor" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal audisi.
                            </div>
                        </div>
                    </div>

                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success" id="submitBtnAktor">Simpan</button>
                            <button type="button" id="cancelBtnAktor" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="auditionPopupStaff" class="popup">
            <div class="popup-content">
                <h3 id="popupTitleStaff">Tambah Audisi Staff</h3>
                <form id="auditionFormStaff" action="<?= base_url('MitraTeater/saveAuditionStaff') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="tipe_teater" id="tipe_teater_staff" value="audisi">
                        <input type="hidden" name="id_kategori" id="id_kategori_staff" value="2">

                        <!-- ✅ LEFT SIDE -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul_staff">Judul</label>
                                <input type="text" id="judul_staff" class="form-control" name="judul" placeholder="Masukkan judul pertunjukan yang diaudisikan" required>
                            </div>

                            <div class="form-group">
                                <label for="poster_staff">Poster Audisi</label>
                                <input type="file" id="poster_staff" class="form-control" name="poster" accept="image/*" required>
                            </div>

                            <div class="form-group">
                                <label for="sinopsis_staff">Sinopsis (opsional)</label>
                                <textarea id="sinopsis_staff" name="sinopsis" class="form-control" placeholder="Masukkan sinopsis pertunjukan yang diaudisikan"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="jenis_staff">Staff yang diaudisikan</label>
                                <input type="checkbox" id="all-staff"> Audisi terbuka untuk semua staff<br>

                                <div id="staff-input-section" margin-top: 1rem;>
                                    <input type="text" id="jenis_staff" class="form-control" name="jenis_staff" placeholder="Contoh: Tata Lampu" required>
                                </div>
                            </div>

                            <div id="jobdesc-staff-wrapper">
                                <div class="form-group" data-staff="default">
                                    <label for="jobdesc_staff">Deskripsi Pekerjaan Staff</label>
                                    <textarea id="jobdesc_staff" name="jobdesc_staff" class="form-control" placeholder="Masukkan deskripsi pekerjaan staff yang diaudisikan"></textarea>
                                </div>
                            </div>

                            <!-- ✅ Jadwal -->
                            <div class="form-group">
                                <label for="tanggal_staff">Jadwal Audisi</label>
                                <div id="schedule-staff-input">
                                    <div class="schedule-audition">
                                        <input type="date" name="tanggal" id="tanggal_staff" class="form-control" required>
                                        <input type="time" name="waktu_mulai" id="waktu_mulai_staff" class="form-control" required>
                                        <input type="time" name="waktu_selesai" id="waktu_selesai_staff" class="form-control" required>

                                        <select name="tipe_harga" id="tipe_harga_staff" class="form-control" required>
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <div id="nominal-harga-staff" style="display:none;">
                                            <input type="number" name="harga" id="harga_staff" class="form-control" placeholder="Masukkan harga">
                                        </div>

                                        <select name="kota[]" id="kota-select-staff" class="form-control" required>
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <div id="lainnya-container-staff" style="display: none;">
                                            <input type="text" name="kota[]" id="kota-input-staff" placeholder="Masukkan kota lainnya" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota-staff" name="kota_real" />

                                        <textarea name="tempat" id="tempat_staff" class="form-control" placeholder="Masukkan alamat tempat pertunjukan" required></textarea>
                                    </div>
                                    <button type="button" id="addScheduleStaff">Tambah Jadwal Audisi</button>
                                </div>
                                <div id="draft-schedule-staff"></div>
                                <input type="hidden" name="hidden_schedule" id="hidden_schedule_staff" value="">
                            </div>

                            <div class="form-group">
                                <label for="penulis_staff">Penulis</label>
                                <input type="text" id="penulis_staff" name="penulis" class="form-control" placeholder="Masukkan nama penulis" required>
                            </div>
                        </div>

                        <!-- ✅ RIGHT SIDE -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran_staff">URL Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran_staff" class="form-control" placeholder="Masukkan URL Web">
                            </div>

                            <div class="form-group">
                                <label for="syarat_staff">Persyaratan Staff</label>
                                <textarea id="syarat_staff" name="syarat" class="form-control" placeholder="Masukkan persyaratan staff" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="syarat_dokumen_staff">Persyaratan Dokumen (opsional)</label>
                                <textarea id="syarat_dokumen_staff" name="syarat_dokumen" class="form-control" placeholder="Masukkan persyaratan dokumen"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="sutradara_staff">Sutradara</label>
                                <input type="text" id="sutradara_staff" name="sutradara" class="form-control" placeholder="Masukkan nama sutradara" required>
                            </div>

                            <div class="form-group">
                                <label for="gaji_staff">Gaji Staff (opsional)</label>
                                <input type="checkbox" id="gaji_dirahasiakan_staff" name="gaji_dirahasiakan"> Gaji tidak disebutkan nominalnya (dirahasiakan)
                                <input type="number" id="gaji_staff" name="gaji" class="form-control" placeholder="Masukkan nominal gaji" required>
                            </div>

                            <div class="form-group">
                                <label for="komitmen_staff">Komitmen sebagai Staff (opsional)</label>
                                <textarea id="komitmen_staff" name="komitmen" class="form-control" placeholder="Masukkan komitmen staff"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="mitra_teater_staff">Mitra Teater</label>
                                <select id="mitra_teater_staff" name="mitra_teater" class="form-control" required>
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>

                            <!-- ✅ Sosial Media -->
                            <div class="form-group">
                                <label for="platform_name_staff" class="form-label">Sosial Media Teater</label>
                                <div>
                                    <input type="checkbox" id="same-sosmed-staff"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-input-staff">
                                    <div class="social-media-mitra">
                                        <select name="id_platform_sosmed[]" id="platform_name_staff" class="form-control" aria-label="Platform">
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

                                        <input type="text" name="acc_name[]" id="acc_name_staff" class="form-control" placeholder="Masukkan nama akun sosmed">
                                        <?= !empty(\Config\Services::validation()->getError('acc_name')) ? \Config\Services::validation()->getError('acc_name') : \Config\Services::validation()->getError('hidden_accounts') ?>
                                    </div>
                                    <button id="add-account-btn-staff" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                </div>
                                <div id="draft-accounts-staff"></div>
                                <input type="hidden" name="hidden_accounts" id="hidden_accounts_staff" value="[]">
                            </div>

                            <!-- ✅ Website -->
                            <div class="form-group">
                                <label for="judul_web_staff" class="form-label">Website Teater</label>
                                <div id="website-input-staff">
                                    <div class="website-teater">
                                        <input type="text" name="judul_web[]" id="judul_web_staff" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web_staff" class="form-control" placeholder="Masukkan URL web">
                                    </div>
                                    <button id="add-web-btn-staff" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web-staff"></div>
                                <input type="hidden" name="hidden_web" id="hidden_web_staff" value="">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="aturPeriodeCheckboxStaff" name="atur_periode" value="1"
                                        <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                    Atur periode pemesanan secara manual
                                </label>
                            </div>

                            <div id="periodeManualFieldsStaff" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai_staff">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai_staff" name="daftar_mulai" class="form-control"
                                        value="<?= isset($teater['daftar_mulai']) ? $teater['daftar_mulai'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir_staff">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir_staff" name="daftar_berakhir" class="form-control"
                                        value="<?= isset($teater['daftar_berakhir']) ? $teater['daftar_berakhir'] : '' ?>">
                                </div>
                            </div>

                            <div id="infoOtomatisStaff" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal pertunjukan.
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Footer -->
                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success" id="submitBtnStaff">Simpan</button>
                            <button type="button" id="cancelBtnStaff" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Popup Form -->
        <div id="auditionPopupAktorEdit" class="popup">
            <div class="popup-content">
                <h3 id="popupTitleAktorEdit">Edit Audisi Aktor</h3>
                <form id="auditionFormAktorEdit" class="" action="<?= base_url('MitraTeater/saveAuditionAktor') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="id_teater" id="id_teater_aktor_edit" value="">
                        <input type="hidden" name="tipe_teater" id="tipe_teater_aktor_edit" value="">
                        <input type="hidden" name="id_kategori" id="id_kategori_aktor_edit" value="">
                        <input type="hidden" name="id_aktor_audisi" id="id_aktor_audisi_edit" value="">
                        <input type="hidden" name="id_user" value="">
                        <input type="hidden" name="id_audisi" value="">

                        <!-- Left Side (Form Fields) -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul_aktor_edit">Judul</label>
                                <input type="text" name="judul" id="judul_aktor_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="poster_aktor_edit">Poster Audisi</label>
                                <input type="file" name="poster" id="poster_aktor_edit" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="sinopsis_aktor_edit">Sinopsis (opsional)</label>
                                <textarea id="sinopsis_aktor_edit" name="sinopsis" class="form-control" value=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="karakter_audisi_edit">Karakter yang diaudisikan</label>
                                <input type="checkbox" id="all-chara-edit"> Audisi terbuka untuk semua tokoh<br>

                                <div id="aktor-edit-section" margin-top: 1rem;>
                                    <input type="text" name="karakter_audisi" id="karakter_audisi_edit" class="form-control" value="">
                                </div>
                            </div>
                            <div id="deskripsi-karakter-edit-wrapper">
                                <div class="form-group" data-karakter="default">
                                    <label for="deskripsi_karakter_edit">Deskripsi Karakter</label>
                                    <textarea id="deskripsi_karakter_edit" name="deskripsi_karakter" class="form-control" value=""></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_aktor_edit" class="form-label">Jadwal Audisi</label>
                                <div id="schedule-aktor-edit">
                                    <div class="schedule-audition">
                                        <input type="hidden" name="id_schedule[]" id="id_schedule_aktor_edit" value="">

                                        <input type="date" name="tanggal" id="tanggal_aktor_edit" class="form-control">
                                        <input type="time" name="waktu_mulai" id="waktu_mulai_aktor_edit" class="form-control">
                                        <input type="time" name="waktu_selesai" id="waktu_selesai_aktor_edit" class="form-control">

                                        <select name="tipe_harga" id="tipe_harga_aktor_edit" class="form-control">
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <!-- Toggle Harga (Muncul saat pilih "Bayar") -->
                                        <div id="nominal-harga-aktor-edit" style="display:none;">
                                            <input type="number" name="harga" id="harga_aktor_edit" class="form-control" placeholder="Masukkan harga">
                                        </div>

                                        <select name="kota[]" id="kota-select-aktor-edit" class="form-control">
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <!-- Input kota tambahan -->
                                        <div id="lainnya-container-aktor-edit" style="display: none;">
                                            <input type="text" name="kota[]" id="kota-edit-aktor" placeholder="Masukkan kota lainnya" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota-aktor-edit" name="kota_real" />

                                        <textarea name="tempat" id="tempat_aktor_edit" class="form-control" placeholder="Masukkan alamat tempat audisi"></textarea>
                                    </div>
                                    <button type="button" id="editScheduleAktor">Tambah Jadwal Audisi</button>
                                </div>
                                <div id="draft-schedule-aktor-edit"></div>
                                <input type="hidden" name="hidden_schedule" id="hidden_schedule_aktor_edit">
                                <input type="hidden" name="deleted_schedules" id="deleted_schedules_aktor_edit" value="[]">
                            </div>
                            <div class="form-group">
                                <label for="penulis_aktor_edit">Penulis</label>
                                <input type="text" id="penulis_aktor_edit" name="penulis" class="form-control" value="">
                            </div>
                        </div>

                        <!-- Right Side (Form Fields) -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran_aktor_edit">URL Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran_aktor_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="syarat_aktor_edit">Persyaratan aktor</label>
                                <textarea id="syarat_aktor_edit" name="syarat" class="form-control" value=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="syarat_dokumen_aktor_edit">Persyaratan dokumen (opsional)</label>
                                <textarea id="syarat_dokumen_aktor_edit" name="syarat_dokumen" class="form-control" value=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sutradara_aktor_edit">Sutradara</label>
                                <input type="text" name="sutradara" id="sutradara_aktor_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="staff_aktor_edit">Staff (opsional)</label>
                                <input type="text" name="staff" id="staff_aktor_edit" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="gaji_aktor_edit">Gaji Aktor (opsional)</label>
                                <input type="checkbox" id="gaji_dirahasiakan_aktor_edit" name="gaji_dirahasiakan"> Gaji tidak disebutkan nominalnya (dirahasiakan)
                                <input type="number" class="form-control" id="gaji_aktor_edit" name="gaji" placeholder="Masukkan nominal gaji" value="">
                            </div>
                            <div class="form-group">
                                <label for="komitmen_aktor_edit">Komitmen sebagai Aktor (opsional)</label>
                                <textarea name="komitmen" id="komitmen_aktor_edit" class="form-control" value=""></textarea>
                            </div>
                            <div class="form-group">
                                <label for="mitra_teater_aktor_edit">Mitra Teater</label>
                                <select id="mitra_teater_aktor_edit" name="mitra_teater" class="form-control">
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="platform_name_aktor_edit" class="form-label">Sosial Media Teater</label>
                                <input type="hidden" name="id_teater_sosmed[]" id="id_sosmed_aktor_edit" value="">

                                <div>
                                    <input type="checkbox" id="same-sosmed-aktor-edit"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-edit-aktor">
                                    <div class="social-media-mitra">
                                        <select name="id_platform_sosmed[]" id="platform_name_aktor_edit" class="form-control" aria-label="Platform">
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

                                        <input type="text" name="acc_name[]" id="acc_name_aktor_edit" class="form-control" placeholder="Masukkan nama akun sosmed">
                                    </div>
                                    <button id="add-account-btn-aktor-edit" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                </div>
                                <div id="draft-accounts-aktor-edit"></div>
                                <input type="hidden" name="hidden_accounts" id="hidden_accounts_aktor_edit">
                            </div>
                            <div class="form-group">
                                <label for="judul_web_aktor_edit" class="form-label">Website Teater (opsional)</label>
                                <div id="website-edit-aktor">
                                    <div class="website-teater">
                                        <input type="hidden" name="id_teater_web[]" id="id_web_aktor_edit" value="">
                                        <input type="text" name="judul_web[]" id="judul_web_aktor_edit" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web_aktor_edit" class="form-control" placeholder="Masukkan url web">
                                    </div>
                                    <button id="add-web-btn-aktor-edit" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web-aktor-edit"></div>
                                <input type="hidden" name="hidden_web" id="hidden_web_aktor_edit">
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="aturPeriodeCheckboxAktorEdit" name="atur_periode" value="1"
                                    <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                Atur periode pemesanan secara manual
                            </div>

                            <div id="periodeManualFieldsAktorEdit" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai_aktor_edit">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai_aktor_edit" name="daftar_mulai" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir_aktor_edit">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir_aktor_edit" name="daftar_berakhir" class="form-control" value="">
                                </div>
                            </div>

                            <div id="infoOtomatisAktorEdit" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal audisi.
                            </div>
                        </div>
                    </div>

                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success" id="editBtnAktor">Simpan</button>
                            <button type="button" id="cancelEditBtnAktor" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="auditionPopupStaffEdit" class="popup">
            <div class="popup-content">
                <h3 id="popupTitleStaffEdit">Edit Audisi Staff</h3>
                <form id="auditionFormStaffEdit" action="<?= base_url('MitraTeater/saveAuditionStaff') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="popup-body">
                        <input type="hidden" name="id_teater" value="">
                        <input type="hidden" name="tipe_teater" id="tipe_teater_staff_edit" value="">
                        <input type="hidden" name="id_kategori" id="id_kategori_staff_edit" value="">
                        <input type="hidden" name="id_staff_audisi" id="id_staff_audisi_edit" value="">

                        <!-- ✅ LEFT SIDE -->
                        <div class="popup-left">
                            <div class="form-group">
                                <label for="judul_staff_edit">Judul</label>
                                <input type="text" id="judul_staff_edit" class="form-control" name="judul" value="">
                            </div>

                            <div class="form-group">
                                <label for="poster_staff_edit">Poster Audisi</label>
                                <input type="file" id="poster_staff_edit" class="form-control" name="poster" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label for="sinopsis_staff_edit">Sinopsis (opsional)</label>
                                <textarea id="sinopsis_staff_edit" name="sinopsis" class="form-control" placeholder="Masukkan sinopsis teater yang diaudisikan" value=""></textarea>
                            </div>

                            <div class="form-group">
                                <label for="jenis_staff_edit">Staff yang diaudisikan</label>
                                <input type="checkbox" id="all-staff-edit"> Audisi terbuka untuk semua staff<br>

                                <div id="staff-edit-section" margin-top: 1rem;>
                                    <input type="text" id="jenis_staff_edit" class="form-control" name="jenis_staff" value="">
                                </div>
                            </div>

                            <div id="jobdesc-staff-edit-wrapper">
                                <div class="form-group" data-staff="default">
                                    <label for="jobdesc_staff_edit">Deskripsi Pekerjaan Staff</label>
                                    <textarea id="jobdesc_staff_edit" name="jobdesc_staff" class="form-control" value=""></textarea>
                                </div>
                            </div>

                            <!-- ✅ Jadwal -->
                            <div class="form-group">
                                <label for="tanggal_staff_edit" class="form-label">Jadwal Audisi</label>
                                <div id="schedule-staff-edit">
                                    <div class="schedule-audition">
                                        <input type="hidden" name="id_schedule[]" id="id_schedule_staff_edit" value="">

                                        <input type="date" name="tanggal" id="tanggal_staff_edit" class="form-control">
                                        <input type="time" name="waktu_mulai" id="waktu_mulai_staff_edit" class="form-control">
                                        <input type="time" name="waktu_selesai" id="waktu_selesai_staff_edit" class="form-control">

                                        <select name="tipe_harga" id="tipe_harga_staff_edit" class="form-control">
                                            <option selected disabled>Harga tiket</option>
                                            <option value="Bayar">Bayar</option>
                                            <option value="Gratis">Gratis</option>
                                        </select>

                                        <div id="nominal-harga-staff-edit" style="display:none;">
                                            <input type="number" name="harga" id="harga_staff_edit" class="form-control" placeholder="Masukkan harga">
                                        </div>

                                        <select name="kota[]" id="kota-select-staff-edit" class="form-control">
                                            <option selected disabled>Pilih Kota</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Bogor">Bogor</option>
                                            <option value="Depok">Depok</option>
                                            <option value="Tangerang">Tangerang</option>
                                            <option value="Bekasi">Bekasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>

                                        <div id="lainnya-container-staff-edit" style="display: none;">
                                            <input type="text" name="kota[]" id="kota-edit-staff" placeholder="Masukkan kota lainnya" class="form-control">
                                        </div>

                                        <input type="hidden" id="hidden-kota-staff-edit" name="kota_real" />

                                        <textarea name="tempat" id="tempat_staff_edit" class="form-control" placeholder="Masukkan alamat tempat audisi"></textarea>
                                    </div>
                                    <button type="button" id="editScheduleStaff">Tambah Jadwal Audisi</button>
                                </div>
                                <div id="draft-schedule-staff-edit"></div>
                                <input type="hidden" name="hidden_schedule" id="hidden_schedule_staff_edit">
                                <input type="hidden" name="deleted_schedules" id="deleted_schedules_staff_edit">
                            </div>

                            <div class="form-group">
                                <label for="penulis_staff_edit">Penulis</label>
                                <input type="text" id="penulis_staff_edit" name="penulis" class="form-control" value="">
                            </div>
                        </div>

                        <!-- ✅ RIGHT SIDE -->
                        <div class="popup-right">
                            <div class="form-group">
                                <label for="url_pendaftaran_staff_edit">URL Pendaftaran</label>
                                <input type="text" name="url_pendaftaran" id="url_pendaftaran_staff_edit" class="form-control" value="">
                            </div>

                            <div class="form-group">
                                <label for="syarat_staff_edit">Persyaratan Staff</label>
                                <textarea id="syarat_staff_edit" name="syarat" class="form-control" value=""></textarea>
                            </div>

                            <div class="form-group">
                                <label for="syarat_dokumen_staff_edit">Persyaratan Dokumen (opsional)</label>
                                <textarea id="syarat_dokumen_staff_edit" name="syarat_dokumen" class="form-control" placeholder="Masukkan persyaratan dokumen" value=""></textarea>
                            </div>

                            <div class="form-group">
                                <label for="sutradara_staff_edit">Sutradara</label>
                                <input type="text" id="sutradara_staff_edit" name="sutradara" class="form-control" value="">
                            </div>

                            <div class="form-group">
                                <label for="gaji_staff_edit">Gaji Staff (opsional)</label>
                                <input type="checkbox" id="gaji_dirahasiakan_staff_edit" name="gaji_dirahasiakan"> Gaji tidak disebutkan nominalnya (dirahasiakan)
                                <input type="number" id="gaji_staff_edit" name="gaji" class="form-control" placeholder="Masukkan nominal gaji" value="">
                            </div>

                            <div class="form-group">
                                <label for="komitmen_staff_edit">Komitmen sebagai Staff (opsional)</label>
                                <textarea id="komitmen_staff_edit" name="komitmen" class="form-control" placeholder="Masukkan komitmen staff" value=""></textarea>
                            </div>

                            <div class="form-group">
                                <label for="mitra_teater_staff_edit">Mitra Teater</label>
                                <select id="mitra_teater_staff_edit" name="mitra_teater" class="form-control">
                                    <option value="" selected disabled>Pilih Mitra Teater</option>
                                </select>
                            </div>

                            <!-- ✅ Sosial Media -->
                            <div class="form-group">
                                <label for="platform_name_staff_edit" class="form-label">Sosial Media Teater</label>
                                <input type="hidden" name="id_teater_sosmed[]" id="id_sosmed_staff_edit" value="">

                                <div>
                                    <input type="checkbox" id="same-sosmed-staff-edit"> Sama dengan sosial media komunitas
                                </div>
                                <div id="social-media-edit-staff">
                                    <div class="social-media-mitra">
                                        <select name="id_platform_sosmed[]" id="platform_name_staff_edit" class="form-control" aria-label="Platform">
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

                                        <input type="text" name="acc_name[]" id="acc_name_staff_edit" class="form-control" placeholder="Masukkan nama akun sosmed">
                                        </?= !empty(\Config\Services::validation()->getError('acc_name')) ? \Config\Services::validation()->getError('acc_name') : \Config\Services::validation()->getError('hidden_accounts') ?>
                                    </div>
                                    <button id="add-account-btn-staff-edit" type="button" class="btn btn-danger add-item">Add Another Account</button>
                                </div>
                                <div id="draft-accounts-staff-edit"></div>
                                <input type="hidden" name="hidden_accounts" id="hidden_accounts_staff_edit">
                            </div>

                            <!-- ✅ Website -->
                            <div class="form-group">
                                <label for="judul_web_staff_edit" class="form-label">Website Teater</label>
                                <div id="website-edit-staff">
                                    <div class="website-teater">
                                        <input type="hidden" name="id_teater_web[]" id="id_web_staff_edit" value="">

                                        <input type="text" name="judul_web[]" id="judul_web_staff_edit" class="form-control" placeholder="Masukkan judul web">
                                        <input type="text" name="url_web[]" id="url_web_staff_edit" class="form-control" placeholder="Masukkan URL web">
                                    </div>
                                    <button id="add-web-btn-staff-edit" type="button" class="btn btn-danger add-item">Tambah Website</button>
                                </div>
                                <div id="draft-web-staff-edit"></div>
                                <input type="hidden" name="hidden_web" id="hidden_web_staff_edit">
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="aturPeriodeCheckboxStaffEdit" name="atur_periode" value="1"
                                    <?= (!empty($teater['daftar_mulai']) && !empty($teater['daftar_berakhir'])) ? 'checked' : '' ?>>
                                Atur periode pemesanan secara manual
                            </div>

                            <div id="periodeManualFieldsStaffEdit" style="display: none;">
                                <div class="form-group">
                                    <label for="daftar_mulai_staff_edit">Tanggal Mulai Pemesanan</label>
                                    <input type="date" id="daftar_mulai_staff_edit" name="daftar_mulai" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label for="daftar_berakhir_staff_edit">Tanggal Akhir Pemesanan</label>
                                    <input type="date" id="daftar_berakhir_staff_edit" name="daftar_berakhir" class="form-control" value="">
                                </div>
                            </div>

                            <div id="infoOtomatisStaffEdit" style="display: none; font-style: italic;">
                                Pemesanan akan dibuka otomatis dan berakhir pada hari terakhir jadwal pertunjukan.
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Footer -->
                    <div class="popup-footer">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success" id="editBtnStaff">Simpan</button>
                            <button type="button" id="cancelEditBtnStaff" class="btn btn-danger">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>