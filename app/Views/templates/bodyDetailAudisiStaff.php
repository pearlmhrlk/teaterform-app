<!DOCTYPE html>
<html lang="en">

<body>
    <div class="detail-audition">
        <div class="audition-container">
            <div class="audition-item">
                <div class="poster">
                    <img src="<?= base_url('assets/images/poster/poster3.jpeg') ?>" alt="Poster Audisi">
                    <p>Es Lilin Cilacap Production</p>
                </div>
                <div class="audition-info">
                    <h6>Audisi Staff Teater</h6>
                    <h3>Perahu Kertas</h3>
                    <div class="details">
                        <p><span class="label">Jenis Staff:</span> Tata Lampu</p>
                        <p><span class="label">Deskripsi Pekerjaan:</span> Mengatur Lightning.</p>
                        <p><span class="label">Persyaratan Staff:</span> Perempuan berusia 12 - 16 tahun dan konsisten terhadap perannya.</p>
                        <p><span class="label">Persyaratan Dokumen:</span> -</p>
                        <p><span class="label">Gaji Staff:</span> Rp500,000,- untuk setiap penayangan teater.</p>
                        <p><span class="label">Sutradara:</span> Willy Santoso</p>
                        <p><span class="label">Penulis:</span> Windy Panduwara</p>
                        <p><span class="label">Staff:</span> Bagong Puripurna, Lulu Lunita, Cepri Tagor, Linda Putu.</p>
                    </div>
                </div>
            </div>
            <div class="extra-info">
                <p><span class="label">Komitmen:</span> Tidak boleh telat, bertahan hingga hari terakhir penayangan.</p>
                <p><span class="label">Harga Tiket Audisi:</span> -</p>
                <p><span class="label">Sinopsis: </span> Disebuah hutan, tinggallah bayi kera yang sangat cantik. Saat beranjak remaja, ia berjalan menuju desa manusia.</p>

                <div class="social-media">
                    <p><span class="label">Sosial Media:</span>
                    <div class="platform">
                        <i class="fa-brands fa-instagram"></i> <a href="https://instagram.com/eslilincilacapproduction" target="_blank">@eslilincilacapproduction</a><br>
                    </div>
                    <div class="platform">
                        <i class="fa-brands fa-facebook"></i> <a href="https://facebook.com/EsLilinCilacapProduction" target="_blank">Es Lilin Cilacap Production</a>
                    </div>
                    </p>
                </div>
                <div class="web">
                    <p><span class="label">Web:</span>
                        <a href="https://www.nsi.com" target="_blank">Komunitas Teater Official</a>
                    </p>
                </div>
            </div>

            <!-- Tabel Jadwal Audisi -->
            <div class="schedule-table">
                <h5>Jadwal Audisi</h5>
                <?php $addedHarga = []; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Kota</th>
                            <th>Tempat</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupedSchedule as $kota => $tempatList): ?>
                            <?php $firstKota = true; ?>
                            <?php foreach ($tempatList as $tempat => $tanggalList): ?>
                                <?php $firstTempat = true; ?>
                                <?php foreach ($tanggalList as $tanggal => $waktuList): ?>
                                    <tr>
                                        <?php if ($firstKota): ?>
                                            <td rowspan="<?= array_sum(array_map('count', $tempatList)); ?>"><?= $kota; ?></td>
                                            <?php $firstKota = false; ?>
                                        <?php endif; ?>
                                        <?php if ($firstTempat): ?>
                                            <td rowspan="<?= count($tanggalList); ?>"><?= $tempat; ?></td>
                                            <?php $firstTempat = false; ?>
                                        <?php endif; ?>
                                        <td><?= date('d F Y', strtotime($tanggal)); ?></td>
                                        <td><?= nl2br(implode("\n", $waktuList)); ?></td>
                                    </tr>
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
            <p>Apakah anda yakin ingin melakukan pendaftaran audisi staff teater ini?</p>
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