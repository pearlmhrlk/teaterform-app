<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-audition">
        <div class="audition-container">
            <div class="audition-item">
                <div class="poster">
                    <?php
                    $posterPath = 'public/' . $teater['poster'];
                    $posterFullPath = FCPATH . $posterPath;

                    $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                        ? base_url($posterPath)
                        : base_url('public/assets/img/default-poster.png');
                    ?>
                    <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">
                    <p><a href="<?= base_url('MitraTeater/detailMitraTeater/' . $mitra['id_mitra']) ?>">
                            <?= esc($namaKomunitas['nama']) ?>
                        </a></p>
                </div>
                <div class="audition-info">
                    <h6>Audisi Staff Teater</h6>
                    <h3><?= esc($teater['judul']) ?></h3>
                    <div class="details">
                        <p><span class="label">Terbuka untuk staff:</span> <?= esc($staffAudisi['jenis_staff']) ?></p>
                        <p><span class="label">Deskripsi Pekerjaan Staff:</span> <?= esc($staffAudisi['jobdesc_staff']) ?></p>
                        <p><span class="label">Persyaratan Staff:</span> <?= esc($audisi['syarat']) ?></p>
                        <p><span class="label">Persyaratan Dokumen:</span><?= esc($audisi['syarat_dokumen']) ?></p>
                        <p><span class="label">Gaji Staff:</span> <?= esc($audisi['gaji']) ?></p>
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
                <h5>Jadwal Audisi Staff</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Kota</th>
                            <th>Tempat</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
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
                                            <td>
                                                <?php
                                                $statusText = [
                                                    'success' => 'Success',
                                                    'pending' => 'Pending',
                                                    'rejected' => 'Rejected'
                                                ];

                                                echo $statusText[$item['status'] ?? ''] ?? '-';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="actions">
                <button id="btnPesan" data-id="<?= $teater['id_teater'] ?>" data-tipe="audisi">Pesan Tiket</button>
            </div>
        </div>

        <?php if (!empty($tiketAudisi)) : ?>
            <div id="ticket-section" class="mt-5">
                <h4 class="mb-3">Tiket Digital</h4>
                <?php foreach ($tiketAudisi as $tiket) : ?>
                    <div class="ticket-box p-3 border rounded mb-3 bg-light">
                        <!-- Bagian Atas: Info Teater -->
                        <div class="mb-2">
                            <h5 class="mb-1"><?= esc($tiket['judul']) ?></h5>
                            <div class="text-muted small">
                                Diselenggarakan oleh <?= esc($tiket['nama_mitra']) ?> &middot;
                                <?= esc($tiket['jenis_teater']) ?>
                            </div>
                        </div>

                        <!-- Bagian Jadwal dan Lokasi -->
                        <div class="mt-2">
                            <p class="mb-1">
                                <strong>Lokasi:</strong> <?= esc($tiket['tempat']) ?>, <?= esc($tiket['kota']) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Waktu:</strong>
                                <?= date('d-m-Y', strtotime($tiket['tanggal'])) ?>,
                                <?= date('H:i', strtotime($tiket['waktu_mulai'])) ?> - <?= date('H:i', strtotime($tiket['waktu_selesai'])) ?>
                            </p>
                            <p class="mb-0 text-muted small">
                                Dipesan oleh <?= esc($tiket['nama_audiens']) ?> &middot;
                                <?= date('d-m-Y H:i', strtotime($tiket['issue_date'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popupKonfirmasi">
            <h2>Konfirmasi Pendaftaran</h2>
            <p>Pilih jadwal pertunjukan sebelum lanjut:</p>
            <select id="selectJadwal">
                <option value="">-- Pilih Jadwal --</option>
            </select>

            <p id="linkQRCode" style="display:none; color:blue; cursor:pointer;">Lihat QR Code Pembayaran</p>

            <div id="divUploadBukti" style="display:none; margin-top:10px;">
                <p>Silakan upload bukti pembayaran:</p>
                <form id="formUploadBukti" enctype="multipart/form-data">
                    <input type="file" id="buktiPembayaran" name="bukti_pembayaran" required>
                </form>
            </div>

            <div style="margin-top:10px;">
                <button id="btnKonfirmasi">Lanjut</button>
                <button id="btnTidak">Batal</button>
            </div>
        </div>

        <!-- Modal QR Code -->
        <div class="popup" id="modalQRCode" style="display:none;">
            <h3>QR Code Pembayaran</h3>
            <img id="imgQRCode" src="" alt="QR Code" style="width:200px;height:200px;">
            <button id="btnTutupQRCode">Tutup</button>
        </div>

        <div class="popup" id="popupGratis">
            <h2>Konfirmasi Gratis</h2>
            <p>Apakah anda sudah menyelesaikan pendaftaran hingga akhir?</p>
            <button id="btnKonfirmasiGratis">Konfirmasi Pendaftaran</button>
            <button id="btnBatalGratis">Batal</button>
        </div>
    </div>
</body>

</html>