<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-show">
        <div class="show-container">
            <div class="show-item">
                <div class="poster">
                    <?php
                    $posterPath = $teater['poster'];
                    $posterFullPath = FCPATH . $posterPath;

                    $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                        ? base_url($posterPath)
                        : base_url('assets/img/default-poster.png');
                    ?>
                    <img class="poster"
                        src="<?= esc($posterSrc) ?>"
                        alt="<?= esc($teater['judul']) ?>">
                    <p><a href="<?= base_url('MitraTeater/detailMitraTeater/' . $mitraTeater['id_mitra']) ?>">
                            <?= esc($namaKomunitas['nama']) ?>
                        </a></p>
                </div>
                <div class="show-info">
                    <h6>Pertunjukan Teater</h6>
                    <h3><?= esc($teater['judul']) ?></h3>
                    <div class="details">
                        <p><span class="label">Aktor:</span> <?= esc($penampilan['aktor']) ?></p>
                        <p><span class="label">Sutradara:</span> <?= esc($teater['sutradara']) ?></p>
                        <p><span class="label">Penulis:</span> <?= esc($teater['penulis']) ?></p>
                        <p><span class="label">Staff:</span> <?= esc($teater['staff']) ?></p>
                        <p><span class="label">Durasi:</span> <?= esc($penampilan['durasi']) ?> menit</p>
                        <p><span class="label">Rating Umur:</span> <?= esc($penampilan['rating_umur']) ?></p>
                        <p><span class="label">Sinopsis: </span> <?= esc($teater['sinopsis']) ?></p>
                    </div>
                </div>
            </div>

            <div class="extra-info">
                <div class="social-media">
                    <p><span class="label">Sosial Media:</span></p>
                    <?php foreach ($sosmed as $s): ?>
                        <div class="platform">
                            <p><i class="fa-brands fa-<?= esc(strtolower($sosmed['platform_name'])) ?>"></i>
                                <?= esc($sosmed['acc_teater']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="web">
                    <?php if (isset($website['url_web'], $website['judul_web'])): ?>
                        <p><span class="label">Web:</span>
                            <a href="<?= esc($website['url_web']) ?>" target="_blank"><?= esc($website['judul_web']) ?></a>
                        </p>
                    <?php endif; ?>
                </div>
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
                        <?php foreach ($groupedSchedule as $kota => $tempatList): ?>
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
                                                $denahFormatted = '<a href="#" class="openSeatMap" data-image="' . base_url('assets/images/' . $info['denah']) . '">Lihat Denah</a>';
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
            <div class="actions">
                <button id="btnPesan" data-id="<?= $teater['id_teater'] ?>" data-tipe="penampilan">Pesan Tiket</button>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>

        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popupKonfirmasi">
            <h2>Konfirmasi Pendaftaran</h2>
            <p>Pilih jadwal pertunjukan sebelum lanjut:</p>

            <select id="selectJadwal">
                <option value="">-- Pilih Jadwal --</option>
                <!-- Diisi lewat JS -->
            </select>

            <p>Apakah Anda yakin ingin mendaftar? Setelah klik "Ya", Anda akan diarahkan ke situs pendaftaran.</p>

            <button id="btnYa">Ya</button>
            <button id="btnTidak">Batal</button>
        </div>

        <div class="popup" id="popupUpload">
            <h2>Upload Bukti Pembayaran</h2>
            <p>Silakan upload bukti pembayaran.</p>
            <input type="file" id="buktiPembayaran">
            <button id="btnUpload" class="btn-primary">Upload</button>
            <button id="btnBatalUpload" class="btn-secondary">Batal</button>
        </div>

        <div class="popup" id="popupGratis">
            <h2>Konfirmasi Gratis</h2>
            <p>Apakah anda sudah menyelesaikan pendaftaran hingga akhir?</p>
            <button id="btnKonfirmasiGratis">Konfirmasi Pendaftaran</button>
            <button id="btnBatalGratis" class="btn-secondary">Batal</button>
        </div>

        <!-- Popup Denah Seat -->
        <div id="seatMapModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="seatMapImage" src="" alt="Denah Seat" style="width: 100%;">
            </div>
        </div>
    </div>
</body>

</html>