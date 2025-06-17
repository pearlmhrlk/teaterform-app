<!DOCTYPE html>
<html lang="en">

<body>
    <div class="approve-admin">
        <div class="container">
            <div class="title">List Akun Mitra Teater Baru</div>

            <?php if (!empty($mitra_accounts)) : ?>
                <?php foreach ($mitra_accounts as $mitra) : ?>
                    <div class="account-card">
                        <div class="logo">
                            <?php
                            // Simpan file di dalam public/uploads/
                            $logoRelativePath = 'uploads/' . $mitra['logo'];
                            $logoFullPath = FCPATH . $logoRelativePath;

                            // Jika file ada dan tidak kosong, gunakan file itu
                            $logoSrc = file_exists($logoFullPath) && !empty($mitra['logo'])
                                ? base_url($logoRelativePath)
                                : base_url('assets/images/logos/mitra1.jpg');
                            ?>

                            <img src="<?= esc($logoSrc) ?>" alt="logo mitra">
                        </div>
                        <div class="acc-info">
                            <h3><?= esc($mitra['nama']) ?></h3>
                            <div class="details">
                                <p><span class="label">Username:</span> <?= esc($mitra['username']) ?></p>
                                <p><span class="label">Email:</span> <?= esc($mitra['email']) ?></p>
                                <p><span class="label">Sosial Media:</span> <?= esc($mitra['sosial_media']) ?></p>
                                <p><span class="label">Alamat Komunitas Teater:</span> <?= esc($mitra['alamat']) ?></p>
                                <p><span class="label">Berdiri Sejak:</span> <?= esc($mitra['berdiri_sejak']) ?></p>
                                <p><span class="label">Deskripsi Komunitas Teater:</span> <?= esc($mitra['deskripsi']) ?></p>
                                <p><span class="label">Pementasan Sebelumnya:</span> <?= trim($mitra['history_show'] ?? '') !== '' ? esc($mitra['history_show']) : '-' ?></p>
                                <p><span class="label">Prestasi:</span> <?= trim($mitra['prestasi'] ?? '') !== '' ? esc($mitra['prestasi']) : '-' ?></p>
                            </div>
                        </div>
                        <div class="actions">
                            <button onclick="confirmApprove(<?= $mitra['id_mitra'] ?>)" class="Approve">Setujui Akun</button>
                            <button type="button" onclick="openRejectPopup(<?= $mitra['id_mitra'] ?>)">Tolak Akun</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Tidak ada akun mitra yang menunggu persetujuan.</p>
            <?php endif; ?>

            <!-- Informasi jumlah akun -->
            <div class="pagination-info">
                <p>Menampilkan <?= count($mitra_accounts) ?> akun dari total <?= $totalMitra ?> akun.</p>
                <p>Halaman <?= $page ?> dari <?= $totalPages ?>.</p>
            </div>

            <!-- Pagination Manual -->
            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="<?= base_url('Admin/approveMitra?page=' . ($page - 1)) ?>">Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="<?= base_url('Admin/approveMitra?page=' . $i) ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="<?= base_url('Admin/approveMitra?page=' . ($page + 1)) ?>">Selanjutnya</a>
                <?php endif; ?>
            </div>

            <!-- Popup Konfirmasi -->
            <div id="approvePopup" class="popup-container">
                <div class="popup-content">
                    <h2>Konfirmasi Persetujuan</h2>
                    <p>Apakah Anda yakin ingin menyetujui akun ini?</p>
                    <div class="popup-actions">
                        <button id="confirmApproveBtn" class="btn-approve" onclick="submitApproved()">Setujui</button>
                        <button id="cancelApproveBtn" class="btn-cancel" onclick="closeApprovePopup()">Batal</button>
                    </div>
                </div>
            </div>

            <!-- Popup Tolak Akun -->
            <div id="rejectionPopup" class="popup-container">
                <div class="popup-content">
                    <h2>Konfirmasi Penolakan Akun</h2>
                    <p>Silakan berikan alasan mengapa akun ini ditolak.</p>
                    <form action="<?= base_url('Admin/rejectMitra') ?>" method="post" onsubmit="console.log('Submitting form with ID:', document.getElementById('id_mitra').value, 'Reason:', document.getElementById('reason').value)">
                        <input type="hidden" id="id_mitra" name="id_mitra">
                        <label for="reason">Alasan Penolakan:</label>
                        <textarea id="reason" name="reason" required></textarea>
                        <div class="popup-buttons">
                            <button type="button" class="btn-primary" onclick="submitRejection()">Kirim</button>
                            <button type="button" class="btn-secondary" onclick="closeRejectPopup()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>