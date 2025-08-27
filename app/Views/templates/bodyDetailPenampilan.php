<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-show">
        <div class="show-container">
            <div class="show-item">
                <div class="poster">
                    <?php
                    $posterPath = 'public/' . $teater['poster'];
                    $posterFullPath = FCPATH . $posterPath;

                    $posterSrc = file_exists($posterFullPath) && !empty($teater['poster'])
                        ? base_url($posterPath)
                        : base_url('public/assets/img/default-poster.png');
                    ?>
                    <img class="poster" src="<?= esc($posterSrc) ?>" alt="<?= esc($teater['judul']) ?>">
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
                        <?php
                        $url = $website['url_web'];
                        if (!preg_match('/^https?:\/\//i', $url)) {
                            $url = 'https://' . $url; // default pake https
                        }
                        ?>
                        <p><span class="label">Web:</span>
                            <a href="<?= esc($url) ?>" target="_blank"><?= esc($website['judul_web']) ?></a>
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
                            <th>Status Booking</th>
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
                                                $denahFormatted = '<a href="#" class="openSeatMap" data-image="' . base_url('public/assets/images/' . $info['denah']) . '">Lihat Denah</a>';
                                            }
                                            ?>

                                            <td><?= $hargaFormatted; ?></td>
                                            <td><?= $denahFormatted; ?></td>

                                            <td>
                                                <?php
                                                $statuses = $info['status'] ?? [];
                                                if (empty($statuses)) {
                                                    echo "-";
                                                } else {
                                                    echo implode(', ', $statuses);
                                                }
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
                <button id="btnPesan" data-id="<?= $teater['id_teater'] ?>" data-tipe="penampilan">Pesan Tiket</button>
            </div>
        </div>

        <?php if (!empty($tiketPenampilan)) : ?>
            <div id="ticket-section" class="mt-5">
                <h4 class="mb-3">Tiket Digital</h4>
                <?php foreach ($tiketPenampilan as $tiket) : ?>
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
        <div class="popupQRCode" id="modalQRCode" style="display:none;">
            <div class="popup-content-qrcode">
                <h3>QR Code Pembayaran</h3>
                <?php
                $QRPath = 'public/' . $penampilan['qrcode_bayar'];
                $QRFullPath = FCPATH . $QRPath;

                $QRSrc = file_exists($QRFullPath) && !empty($penampilan['qrcode_bayar'])
                    ? base_url($QRPath)
                    : base_url('public/assets/img/default-poster.png');
                ?>
                <img class="qrcode_bayar" src="<?= esc($QRSrc) ?>" alt="QR Pembayaran">
                <button id="btnTutupQRCode">Tutup</button>
            </div>
        </div>

        <div class="popup" id="popupGratis">
            <h2>Konfirmasi Gratis</h2>
            <p>Apakah anda sudah menyelesaikan pendaftaran hingga akhir?</p>
            <button id="btnKonfirmasiGratis">Konfirmasi Pendaftaran</button>
            <button id="btnBatalGratis">Batal</button>
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