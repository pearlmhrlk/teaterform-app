<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-audition">
        <div class="audition-container">
            <div class="audition-item">
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
                    <p><a href="<?= base_url('MitraTeater/detailMitraTeater/' . $mitra['id_mitra']) ?>">
                            <?= esc($namaKomunitas['nama']) ?>
                        </a></p>
                </div>
                <div class="audition-info">
                    <h6>Audisi Aktor Teater</h6>
                    <h3><?= esc($teater['judul']) ?></h3>
                    <div class="details">
                        <p><span class="label">Terbuka untuk karakter:</span> <?= esc($aktorAudisi['karakter_audisi']) ?></p>
                        <p><span class="label">Deskripsi Karakter:</span> <?= esc($aktorAudisi['deskripsi_karakter']) ?></p>
                        <p><span class="label">Persyaratan Aktor:</span> <?= esc($audisi['syarat']) ?></p>
                        <p><span class="label">Persyaratan Dokumen:</span><?= esc($audisi['syarat_dokumen']) ?></p>
                        <p><span class="label">Gaji Aktor:</span> <?= esc($audisi['gaji']) ?></p>
                        <p><span class="label">Sutradara:</span> <?= esc($teater['sutradara']) ?></p>
                        <p><span class="label">Penulis:</span> <?= esc($teater['penulis']) ?></p>
                        <p><span class="label">Staff:</span> <?= esc($teater['staff']) ?></p>
                    </div>
                </div>
            </div>
            <div class="extra-info">
                <p><span class="label">Komitmen:</span> <?= esc($audisi['komitmen']) ?></p>
                <p><span class="label">Sinopsis: </span> <?= esc($teater['sinopsis']) ?></p>

                <div class="social-media">
                    <p><span class="label">Sosial Media:</span></p>
                    <?php foreach ($sosmed as $s): ?>
                        <div class="platform">
                            <p><i class="fa-brands fa-<?= esc(strtolower($s['platform_name'])) ?>"></i>
                                <?= esc($s['acc_teater']) ?></p>
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
                        <?php foreach ($groupedSchedule as $kota => $tempatList): ?>
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
            <div class="actions">
                <button id="btnPesan">Pesan Tiket</button>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>

        <div class="popup" id="popupKonfirmasi">
            <h2>Konfirmasi Pendaftaran</h2>
            <p>Apakah anda yakin ingin melakukan pendaftaran audisi aktor teater ini?</p>
            <p>Harap upload bukti pembayaran setelah berhasil mendaftar di website tujuan.</p>
            <button id="btnYa" class="btn-primary">Ya</button>
            <button id="btnTidak" class="btn-secondary">Batal</button>
        </div>

        <div class="popup" id="popupUpload">
            <h2>Upload Bukti Pembayaran</h2>
            <p>Silakan upload bukti pembayaran.</p>
            <input type="file" id="buktiPembayaran">
            <button id="btnUpload" class="btn-primary">Upload</button>
            <button id="btnBatalUpload" class="btn-secondary">Batal</button>
        </div>
    </div>
</body>

</html>