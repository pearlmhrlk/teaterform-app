<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\MitraModel;
use App\Models\MitraSosmedModel;
use App\Models\PlatformSosmedModel;
use App\Models\ShowSchedule;
use App\Models\Teater;
use App\Models\TeaterSosmed;
use App\Models\Penampilan;
use App\Models\SeatPricing;
use App\Models\Audisi;
use App\Models\LokasiTeater;
use App\Models\TeaterMitraSosmed;
use App\Models\ShowSeatPricing;
use App\Models\TeaterWeb;
use App\Models\KategoriAudisi;
use App\Models\AudisiSchedule;
use App\Models\AudisiAktor;
use App\Models\AudisiPricing;
use App\Models\AudisiStaff;

use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\Request;

class Admin extends BaseController
{
    protected $userModel;
    protected $mitraModel;
    protected $mitraSosmedModel;
    protected $platformSosmedModel;
    protected $teaterModel;
    protected $sosmedModel;
    protected $showScheduleModel;
    protected $penampilanModel;
    protected $seatPricingModel;
    protected $lokasiTeaterModel;
    protected $teaterMitraSosmedModel;
    protected $showSeatPricingModel;
    protected $teaterWebModel;
    protected $audisiModel;
    protected $kategoriAudisiModel;
    protected $audisiScheduleModel;
    protected $audisiAktorModel;
    protected $audisiPricingModel;
    protected $audisiStaffModel;

    protected $db;

    public function __construct()
    {
        $this->userModel = new User(); // Pastikan UserModel sudah ada
        $this->mitraModel = new MitraModel();
        $this->mitraSosmedModel = new MitraSosmedModel();
        $this->platformSosmedModel = new PlatformSosmedModel();
        $this->teaterModel = new Teater();
        $this->sosmedModel = new TeaterSosmed();
        $this->showScheduleModel = new ShowSchedule();
        $this->penampilanModel = new Penampilan();
        $this->seatPricingModel = new SeatPricing();
        $this->lokasiTeaterModel = new LokasiTeater();
        $this->teaterMitraSosmedModel = new TeaterMitraSosmed();
        $this->showSeatPricingModel = new ShowSeatPricing();
        $this->teaterWebModel = new TeaterWeb();
        $this->audisiModel = new Audisi();
        $this->kategoriAudisiModel = new KategoriAudisi();
        $this->audisiScheduleModel = new AudisiSchedule();
        $this->audisiAktorModel = new AudisiAktor();
        $this->audisiPricingModel = new AudisiPricing();
        $this->audisiStaffModel = new AudisiStaff();

        $this->db = \Config\Database::connect();
    }

    public function homepageAfterLogin()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Kirim data user ke view
        return view('templates/headerAdmin', ['title' => 'Homepage Admin', 'user' => $user]) .
            view('templates/homepageAdmin') .
            view('templates/footer');
    }

    // // Update Penampilan 
    // public function updateAuditionAdmin($idTeater)
    // {
    //     $db = \Config\Database::connect();
    //     $db->transBegin();

    //     try {
    //         $userId = session()->get('id_user');
    //         $user = $this->userModel->find($userId);
    //         if (!$user) throw new \Exception('User tidak ditemukan.');

    //         $existingTeater = $this->teaterModel->find($idTeater);
    //         if (!$existingTeater) throw new \Exception('Teater tidak ditemukan.');

    //         // Validasi
    //         $validation = \Config\Services::validation();
    //         $validation->setRules([
    //             'judul' => 'required',
    //             'poster' => 'permit_empty|max_size[poster,2048]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]',
    //             'sinopsis' => 'required',
    //             'penulis' => 'required',
    //             'sutradara' => 'required',
    //             'staff' => 'required',
    //         ]);

    //         if (!$validation->withRequest($this->request)->run()) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Validasi gagal',
    //                 'errors' => $validation->getErrors()
    //             ]);
    //         }

    //         // Upload poster (jika ada perubahan)
    //         $poster = $this->request->getFile('poster');
    //         $posterPath = $existingTeater['poster'];
    //         if ($poster && $poster->isValid() && !$poster->hasMoved()) {
    //             $posterPath = 'uploads/posters/' . $poster->getRandomName();
    //             $poster->move(ROOTPATH . 'public/' . $posterPath);
    //         }

    //         // Update m_teater
    //         $this->teaterModel->update($idTeater, [
    //             'judul' => $this->request->getPost('judul'),
    //             'poster' => $posterPath,
    //             'sinopsis' => $this->request->getPost('sinopsis'),
    //             'penulis' => $this->request->getPost('penulis'),
    //             'sutradara' => $this->request->getPost('sutradara'),
    //             'staff' => $this->request->getPost('staff'),
    //             'dimodif_oleh' => $user['nama'],
    //             'tgl_dimodif' => date('Y-m-d H:i:s'),
    //             'url_pendaftaran' => $this->request->getPost('url_pendaftaran')
    //         ]);

    //         // Cari dan update penampilan
    //         $penampilanRow = $this->penampilanModel->where('id_teater', $idTeater)->first();
    //         if ($penampilanRow) {
    //             $this->penampilanModel->update($penampilanRow['id_penampilan'], [
    //                 'aktor' => $this->request->getPost('aktor'),
    //                 'durasi' => $this->request->getPost('durasi'),
    //                 'rating_umur' => $this->request->getPost('rating_umur')
    //             ]);
    //         }

    //         // ✅ UPDATE seat_category & seat_pricing
    //         $denah_seat = $this->request->getFile('denah_seat');
    //         $denahSeatName = $penampilanRow['denah_seat'] ?? 'assets/images/seat1.jpg';
    //         if ($denah_seat && $denah_seat->isValid() && !$denah_seat->hasMoved()) {
    //             $denahSeatName = 'uploads/posters/' . $denah_seat->getRandomName();
    //             $denah_seat->move(ROOTPATH . 'public/' . $denahSeatName);
    //         }

    //         // Cari kategori seat yang sudah ada
    //         $existingSeatCategory = $this->kategoriSeatModel->where('id_penampilan', $penampilanRow['id_penampilan'])->first();
    //         if ($existingSeatCategory) {
    //             $this->kategoriSeatModel->update($existingSeatCategory['id_kategori_seat'], [
    //                 'nama_kategori' => $this->request->getPost('nama_kategori') ?? 'Kategori Biasa',
    //                 'denah_seat' => $denahSeatName
    //             ]);

    //             $this->seatPricingModel
    //                 ->where('id_penampilan', $penampilanRow['id_penampilan'])
    //                 ->where('id_kategori_seat', $existingSeatCategory['id_kategori_seat'])
    //                 ->set([
    //                     'harga' => $this->request->getPost('harga') ?? 0,
    //                     'tipe_harga' => $this->request->getPost('tipe_harga')
    //                 ])
    //                 ->update();
    //         }

    //         // Update jadwal
    //         $existingSchedules = $this->showScheduleModel->where('id_teater', $idTeater)->findAll();
    //         $existingScheduleIds = array_column($existingSchedules, 'id_schedule');
    //         $scheduleData = json_decode($this->request->getPost('hidden_schedule'), true);
    //         $retainedIds = [];

    //         foreach ($scheduleData ?? [] as $sch) {
    //             $lokasi = [
    //                 'tempat' => $sch['tempat'],
    //                 'kota' => $sch['kota']
    //             ];
    //             $this->lokasiTeaterModel->insert($lokasi);
    //             $idLokasi = $this->lokasiTeaterModel->insertID();

    //             if (!empty($sch['id_schedule'])) {
    //                 $this->showScheduleModel->update($sch['id_schedule'], [
    //                     'tanggal' => $sch['tanggal'],
    //                     'waktu_mulai' => $sch['waktu_mulai'],
    //                     'waktu_selesai' => $sch['waktu_selesai'],
    //                     'id_location' => $idLokasi,
    //                     'id_teater' => $idTeater
    //                 ]);
    //                 $retainedIds[] = $sch['id_schedule'];
    //             } else {
    //                 $this->showScheduleModel->insert([
    //                     'tanggal' => $sch['tanggal'],
    //                     'waktu_mulai' => $sch['waktu_mulai'],
    //                     'waktu_selesai' => $sch['waktu_selesai'],
    //                     'id_location' => $idLokasi,
    //                     'id_teater' => $idTeater
    //                 ]);
    //             }
    //         }

    //         // Hapus jadwal lama yang tidak dipertahankan
    //         $schedulesToDelete = array_diff($existingScheduleIds, $retainedIds);
    //         if (!empty($schedulesToDelete)) {
    //             $this->showScheduleModel->whereIn('id_schedule', $schedulesToDelete)->delete();
    //         }

    //         // Update website
    //         $this->teaterWebModel->where('id_teater', $idTeater)->delete();
    //         $webData = json_decode($this->request->getPost('hidden_web'), true);
    //         foreach ($webData ?? [] as $web) {
    //             $this->teaterWebModel->insert([
    //                 'id_teater' => $idTeater,
    //                 'judul_web' => $web['title'],
    //                 'url_web' => $web['url']
    //             ]);
    //         }

    //         $db->transCommit();
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Data berhasil diperbarui',
    //             'redirect' => base_url('Admin/listPenampilan')
    //         ]);
    //     } catch (\Exception $e) {
    //         $db->transRollback();
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Kesalahan server.',
    //             'debug' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function saveAuditionAktor()
    {
        try {
            $db = \Config\Database::connect();
            $db->transBegin(); // Mulai transaksi

            // Cek user dari session
            $userId = session()->get('id_user');
            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak ditemukan.']);
            }

            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul'      => 'required',
                'sinopsis'   => 'required',
                'penulis'    => 'required',
                'sutradara'  => 'required',
                'syarat'     => 'required',
                'karakter_audisi' => 'required',
                'poster'     => 'uploaded[poster]|max_size[poster,2048]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]',
                'url_pendaftaran' => 'valid_url'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Upload poster
            $poster = $this->request->getFile('poster');
            $posterPath = null;
            if ($poster->isValid() && !$poster->hasMoved()) {
                $posterPath = 'uploads/posters/' . $poster->getRandomName();
                $poster->move(ROOTPATH . 'public/' . $posterPath);
            }

            // **1. Simpan data ke m_teater**
            $teaterData = [
                'tipe_teater' => 'audisi',
                'judul'       => $this->request->getPost('judul'),
                'poster'      => $posterPath,
                'sinopsis'    => $this->request->getPost('sinopsis'),
                'penulis'     => $this->request->getPost('penulis'),
                'sutradara'   => $this->request->getPost('sutradara'),
                'staff'       => $this->request->getPost('staff'),
                'dibuat_oleh' => $user['nama'],
                'tgl_dibuat'  => date('Y-m-d H:i:s'),
                'url_pendaftaran' => $this->request->getPost('url_pendaftaran')
            ];
            $this->teaterModel->insert($teaterData);
            $idTeater = $this->teaterModel->insertID();

            // **2. Simpan data ke m_audisi**
            $audisiData = [
                'id_teater' => $idTeater,
                'id_kategori' => $this->request->getPost('id_kategori'),
                'syarat'    => $this->request->getPost('syarat'),
                'syarat_dokumen' => $this->request->getPost('syarat_dokumen'),
                'gaji'      => $this->request->getPost('gaji'),
                'komitmen'  => $this->request->getPost('komitmen')
            ];
            $this->audisiModel->insert($audisiData);
            $idAudisi = $this->audisiModel->insertID();

            // **3. Simpan data ke m_audisi_aktor**
            $aktorData = [
                'id_audisi'          => $idAudisi,
                'karakter_audisi'    => $this->request->getPost('karakter_audisi'),
                'deskripsi_karakter' => $this->request->getPost('deskripsi_karakter')
            ];
            $this->audisiAktorModel->insert($aktorData);

            // **4. Simpan data lokasi ke m_lokasi_teater**
            $lokasiData = [
                'tempat' => $this->request->getPost('tempat'),
                'kota'   => $this->request->getPost('kota_real')
            ];
            $this->lokasiTeaterModel->insert($lokasiData);
            $idLokasi = $this->lokasiTeaterModel->insertID();

            // **5. Simpan data jadwal audisi ke m_show_schedule**
            $scheduleData = json_decode($this->request->getPost('hidden_schedule'), true);
            if ($scheduleData) {
                foreach ($scheduleData as $schedule) {
                    $this->showScheduleModel->insert([
                        'id_teater' => $idTeater,
                        'id_location' => $idLokasi,
                        'tanggal' => $schedule['tanggal'],
                        'waktu_mulai' => $schedule['waktu_mulai'],
                        'waktu_selesai' => $schedule['waktu_selesai']
                    ]);
                }
            }

            // **6. Simpan harga audisi ke m_audisi_schedule**
            $pricingData = [
                'id_audisi' => $idAudisi,
                'harga' => $this->request->getPost('harga') ?? 0,
                'tipe_harga' => $this->request->getPost('tipe_harga')
            ];
            $this->audisiScheduleModel->insert($pricingData);

            // **7. Simpan sosial media teater ke r_teater_sosmed**
            $accounts = json_decode($this->request->getPost('hidden_accounts'), true);
            if ($accounts) {
                foreach ($accounts as $account) {
                    $this->sosmedModel->insert([
                        'id_teater' => $idTeater,
                        'id_platform_sosmed' => $account['platformId'],
                        'acc_teater' => $account['account']
                    ]);
                }
            }

            // **8. Simpan data website teater ke m_teater_web**
            $websites = json_decode($this->request->getPost('hidden_web'), true);
            if ($websites) {
                foreach ($websites as $website) {
                    $this->teaterWebModel->insert([
                        'id_teater' => $idTeater,
                        'judul_web' => $website['title'],
                        'url_web' => $website['url']
                    ]);
                }
            }

            // **9. Debugging transaksi database**
            if ($this->db->transStatus() === false) {
                $dbError = $this->db->error();
                error_log("Database Error: " . json_encode($dbError));
                throw new \Exception("Transaksi database gagal. Error: " . json_encode($dbError));
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Audisi Aktor berhasil disimpan!',
                'redirect' => base_url('Admin/listAudisi')
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            error_log("Catch Error: " . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function saveAuditionStaff()
    {
        try {
            $db = \Config\Database::connect();
            $db->transBegin();

            // ✅ Ambil user login dari session
            $userId = session()->get('id_user');
            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak ditemukan.']);
            }

            // ✅ Validasi input dasar
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul'           => 'required',
                'penulis'         => 'required',
                'sutradara'       => 'required',
                'syarat'          => 'required',
                'jenis_staff'     => 'required',
                'poster'          => 'uploaded[poster]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]|max_size[poster,2048]',
                'url_pendaftaran' => 'permit_empty|valid_url'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Validasi gagal',
                    'errors'  => $validation->getErrors()
                ]);
            }

            // ✅ Upload Poster
            $poster = $this->request->getFile('poster');
            $posterPath = null;
            if ($poster && $poster->isValid() && !$poster->hasMoved()) {
                $posterPath = 'uploads/posters/' . $poster->getRandomName();
                $poster->move(ROOTPATH . 'public/' . $posterPath);
            }

            // ✅ Simpan ke m_teater
            $teaterData = [
                'tipe_teater'      => 'audisi',
                'judul'            => $this->request->getPost('judul'),
                'poster'           => $posterPath,
                'sinopsis'         => $this->request->getPost('sinopsis') ?? '-',
                'penulis'          => $this->request->getPost('penulis'),
                'sutradara'        => $this->request->getPost('sutradara'),
                'staff'            => $this->request->getPost('staff') ?? 'Tidak ada staff',
                'dibuat_oleh'      => $user['nama'],
                'tgl_dibuat'       => date('Y-m-d H:i:s'),
                'url_pendaftaran'  => $this->request->getPost('url_pendaftaran') ?? null
            ];

            $this->teaterModel->insert($teaterData);
            $idTeater = $this->teaterModel->insertID();
            if (!$idTeater) throw new \Exception("Gagal menyimpan `m_teater`");

            // ✅ Simpan ke m_audisi
            $audisiData = [
                'id_teater'      => $idTeater,
                'id_kategori'    => $this->request->getPost('id_kategori') ?? 2,
                'syarat'         => $this->request->getPost('syarat'),
                'syarat_dokumen' => $this->request->getPost('syarat_dokumen') ?? '-',
                'gaji'           => $this->request->getPost('gaji_staff') ?? 0,
                'komitmen'       => $this->request->getPost('komitmen_staff') ?? '-'
            ];
            $this->audisiModel->insert($audisiData);
            $idAudisi = $this->audisiModel->insertID();
            if (!$idAudisi) throw new \Exception("Gagal menyimpan `m_audisi`");

            // ✅ Simpan ke m_audisi_staff
            $this->audisiStaffModel->insert([
                'id_audisi'     => $idAudisi,
                'jenis_staff'   => $this->request->getPost('jenis_staff'),
                'jobdesc_staff' => $this->request->getPost('jobdesc_staff') ?? '-'
            ]);

            // ✅ Simpan Jadwal Audisi ke m_show_schedule
            $scheduleData = json_decode($this->request->getPost('hidden_schedule_staff'), true);
            if ($scheduleData) {
                foreach ($scheduleData as $schedule) {
                    // Simpan Lokasi
                    $this->lokasiTeaterModel->insert([
                        'tempat' => $schedule['tempat'],
                        'kota'   => $schedule['kota']
                    ]);
                    $idLokasi = $this->lokasiTeaterModel->insertID();

                    $this->showScheduleModel->insert([
                        'id_teater'     => $idTeater,
                        'id_location'   => $idLokasi,
                        'tanggal'       => $schedule['tanggal'],
                        'waktu_mulai'   => $schedule['waktu_mulai'],
                        'waktu_selesai' => $schedule['waktu_selesai'] ?? '00:00:00'
                    ]);
                }
            }

            // ✅ Simpan Harga Audisi ke m_audisi_schedule
            $this->audisiScheduleModel->insert([
                'id_audisi'   => $idAudisi,
                'harga'       => $this->request->getPost('harga_staff') ?? 0,
                'tipe_harga'  => $this->request->getPost('tipe_harga_staff') ?? 'Gratis'
            ]);

            // ✅ Simpan Sosial Media ke r_teater_sosmed
            $sosmed = json_decode($this->request->getPost('hidden_accounts_staff'), true);
            if (!empty($sosmed)) {
                foreach ($sosmed as $item) {
                    $this->sosmedModel->insert([
                        'id_teater'          => $idTeater,
                        'id_platform_sosmed' => $item['platformId'],
                        'acc_teater'         => $item['account']
                    ]);
                }
            }

            // ✅ Simpan Website Teater ke m_teater_web
            $webs = json_decode($this->request->getPost('hidden_web_staff'), true);
            if (!empty($webs)) {
                foreach ($webs as $w) {
                    $this->teaterWebModel->insert([
                        'id_teater'  => $idTeater,
                        'judul_web'  => $w['title'],
                        'url_web'    => $w['url']
                    ]);
                }
            }

            // ✅ Commit transaksi
            if (!$db->transStatus()) {
                throw new \Exception("Transaksi database gagal.");
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Audisi Staff berhasil disimpan!',
                'redirect' => base_url('Admin/listAudisi')
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '❌ Gagal Audisi Staff: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function saveShow()
    {
        try {

            $db = \Config\Database::connect(); // Pastikan ini ada di awal
            $query = $db->getLastQuery();
            log_message('debug', 'Last Query: ' . ($query ? $query : 'NULL'));

            $db->transBegin();

            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            $validation = \Config\Services::validation();

            // Ambil data user dari session
            $userId = session()->get('id_user');
            $user = $this->userModel->find($userId);

            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak ditemukan.']);
            }

            if (!isset($user['nama']) || empty($user['nama'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data pengguna tidak ditemukan.'
                ]);
            }

            if ($this->request->getMethod() === 'POST') {
                // Ambil semua data dari form
                $data = $this->request->getPost();

                // Validasi input
                $validation->setRules([
                    'tipe_teater'  => 'required|in_list[penampilan,audisi]',
                    'judul'        => 'required',
                    'poster'       => 'uploaded[poster]|max_size[poster,2048]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]',
                    'sinopsis'     => 'required',
                    'penulis'      => 'required',
                    'sutradara'    => 'required',
                    'staff'        => 'required',
                    'aktor'        => 'required',
                    'durasi'       => 'required|integer',
                    'rating_umur'  => 'required'
                ]);

                if (!$validation->withRequest($this->request)->run()) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validation->getErrors()
                    ]);
                }

                $poster = $this->request->getFile('poster');

                if (!$poster || !$poster->isValid()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => $poster ? $poster->getErrorString() : 'No file uploaded'
                    ]);
                }

                // Periksa apakah file sudah diproses sebelumnya
                if ($poster->hasMoved()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'File sudah diproses sebelumnya.'
                    ]);
                }

                // Periksa format file yang diizinkan
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                if (!in_array($poster->getMimeType(), $allowedTypes)) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Format file poster tidak didukung.'
                    ]);
                }

                // Pastikan folder tujuan ada
                $uploadPath = ROOTPATH . 'public/uploads/posters/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Buat nama file baru dan pindahkan file
                $newName = $poster->getRandomName();
                if (!$poster->move($uploadPath, $newName)) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Gagal mengunggah poster.'
                    ]);
                }

                // Simpan path relatif
                $posterUrl = 'uploads/posters/' . $newName;
                log_message('debug', 'Poster uploaded: ' . $posterUrl);

                // Simpan data pertunjukan ke tabel m_teater
                $teaterData = [
                    'tipe_teater'  => $data['tipe_teater'],
                    'judul'        => $data['judul'],
                    'poster'       => $posterUrl,
                    'sinopsis'     => $data['sinopsis'],
                    'penulis'      => $data['penulis'],
                    'sutradara'    => $data['sutradara'],
                    'staff'        => $data['staff'],
                    'dibuat_oleh'  => $user['nama'],
                    'dimodif_oleh' => null
                ];

                log_message('debug', 'Request data: ' . json_encode($this->request->getPost()));

                if (!$this->teaterModel->save($teaterData)) {
                    $db->transRollback();
                    log_message('error', 'Gagal menyimpan ke database: ' . json_encode($this->teaterModel->errors()));

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->teaterModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($teaterData));

                $idTeater = $this->teaterModel->getInsertID();

                if (!$idTeater) {
                    $db->transRollback();
                    log_message('error', 'Gagal mendapatkan ID teater setelah insert.');

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mendapatkan ID teater.'
                    ]);
                }

                log_message('debug', 'ID Teater yang dibuat: ' . $idTeater);

                // Simpan data penampilan ke m_penampilan
                $penampilanData = [
                    'id_teater'   => $idTeater,
                    'aktor'       => $data['aktor'],
                    'durasi'      => $data['durasi'],
                    'rating_umur' => $data['rating_umur'],
                ];

                if (!$this->penampilanModel->save($penampilanData)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data penampilan teater.']);
                }

                // Ambil ID user yang baru disimpan
                $idPenampilan = $this->penampilanModel->getInsertID();
                if (!$idPenampilan) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal mendapatkan ID penampilan.'
                    ]);
                }

                log_message('debug', 'ID Penampilan yang dibuat: ' . $idPenampilan);

                $hiddenSchedule = json_decode($data['hidden_schedule'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format jadwal penampilan tidak valid.']);
                }

                if (empty($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal penampilan tidak boleh kosong.']);
                }

                foreach ($hiddenSchedule as $index => $schedule) {
                    $tanggal = $schedule['tanggal'];
                    $waktu_mulai = $schedule['waktu_mulai'];
                    $waktu_selesai = $schedule['waktu_selesai'];
                    $tempat = $schedule['tempat'];
                    $kota = $schedule['kota'];
                    $tipe_harga = isset($schedule['tipe_harga']) && $schedule['tipe_harga'] === 'Gratis' ? 'Gratis' : 'Bayar';
                    $harga = isset($schedule['harga']) ? $schedule['harga'] : null;
                    $nama_kategori = isset($schedule['nama_kategori']) ? (string) $schedule['nama_kategori'] : null;
                    $denah_seat = isset($schedule['denah_seat']) && !empty($schedule['denah_seat']) ? $schedule['denah_seat'] : null;

                    log_message('debug', "Jadwal ke-$index: Tanggal: $tanggal, Mulai: $waktu_mulai, Selesai: $waktu_selesai, Kota: $kota, Tempat: $tempat");

                    $locationData = [
                        'tempat' => $tempat,
                        'kota' => $kota,
                    ];

                    log_message('debug', 'Data lokasi yang akan disimpan: ' . json_encode($locationData));

                    if (!$this->lokasiTeaterModel->save($locationData)) {
                        log_message('error', 'Gagal menyimpan lokasi: ' . json_encode($this->lokasiTeaterModel->errors()));
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal menyimpan lokasi pertunjukan teater.',
                            'errors'  => $this->lokasiTeaterModel->errors()
                        ]);
                    }

                    $idLocation = $this->lokasiTeaterModel->getInsertID();
                    log_message('debug', 'ID Location yang didapat setelah insert: ' . json_encode($idLocation));

                    if (!$idLocation) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal mendapatkan ID Location setelah insert.'
                        ]);
                    }

                    // Simpan data jadwal pertunjukan ke m_show_schedule
                    $scheduleData = [
                        'id_teater'   => $idTeater,
                        'id_location' => $idLocation,
                        'tanggal'     => $tanggal,
                        'waktu_mulai' => $waktu_mulai,
                        'waktu_selesai' => $waktu_selesai,
                    ];

                    if (!$this->showScheduleModel->save($scheduleData)) {
                        log_message('error', 'Error saat menyimpan pertunjukan: ' . json_encode($this->showScheduleModel->errors()));
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data jadwal pertunjukan teater.']);
                    }

                    // Ambil ID user yang baru disimpan
                    $idSchedule = $this->showScheduleModel->getInsertID();

                    if (!$idSchedule) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal mendapatkan ID Schedule penampilan teater.'
                        ]);
                    }

                    // Cek tipe harga
                    if ($schedule['tipe_harga'] === 'Bayar') {
                        if (!isset($schedule['harga'])) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Data harga tidak ditemukan.']);
                        }

                        $namaKategori = isset($schedule['nama_kategori']) ? trim($schedule['nama_kategori']) : null;
                        $hargaKategori = trim($schedule['harga']);

                        if (empty($hargaKategori)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Harga tidak boleh kosong.']);
                        }

                        $kategoriArray = $namaKategori ? array_map('trim', explode(',', $namaKategori)) : [];
                        $hargaArray = array_map('trim', explode(',', $hargaKategori));

                        // Jika tidak ada kategori, harga tetap bisa disimpan
                        if (!empty($kategoriArray) && count($kategoriArray) !== count($hargaArray)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah kategori dan harga tidak sesuai.']);
                        }

                        // Gunakan yang dari hidden_schedule
                        $denah_seat = isset($schedule['denah_seat']) && !empty($schedule['denah_seat']) ? $schedule['denah_seat'] : null;
                        $allDenah = $this->request->getFileMultiple('denah_seat');
                        $uploadedDenah = $allDenah[$index] ?? null;
                        $denahNames = [];

                        if ($uploadedDenah && $uploadedDenah->isValid()) {
                            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                            if (!in_array($uploadedDenah->getMimeType(), $allowedTypes)) {
                                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file denah seat tidak didukung.']);
                            }

                            if ($uploadedDenah->hasMoved()) {
                                log_message('error', 'File denah sudah dipindahkan sebelumnya.');
                                return $this->response->setJSON(['status' => 'error', 'message' => 'File denah sudah diproses.']);
                            }

                            $uploadPath = ROOTPATH . 'public/uploads/denah/';
                            if (!is_dir($uploadPath)) {
                                mkdir($uploadPath, 0777, true);
                            }

                            $newName = $uploadedDenah->getRandomName();
                            if ($uploadedDenah->move($uploadPath, $newName)) {
                                $denah_seat = 'uploads/denah/' . $newName;
                                $denahNames[$index] = $denah_seat; // simpan nama ke array berdasarkan index
                            } else {
                                log_message('error', 'Gagal mengunggah file. Error: ' . $uploadedDenah->getErrorString());
                                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah denah seat.']);
                            }
                        }

                        // Jika `$denah_seat` ternyata string kosong setelah semua proses, anggap NULL
                        if (empty($denah_seat)) {
                            $denah_seat = null;
                        }

                        foreach ($hargaArray as $index => $harga) {
                            $kategori_seat = isset($kategoriArray[$index]) ? $kategoriArray[$index] : null;
                            $harga = is_numeric($harga) ? $harga : null;

                            if ($harga === null) {
                                return $this->response->setJSON(['status' => 'error', 'message' => 'Format harga tidak valid.']);
                            }

                            $existingPricing = $this->seatPricingModel
                                ->where('id_penampilan', $idPenampilan)
                                ->where('harga', $harga)
                                ->where('nama_kategori', $kategori_seat)
                                ->first();

                            if ($existingPricing) {
                                $idSeatPricing = $existingPricing['id_pricing'];
                            } else {
                                $seatPricingData = [
                                    'id_penampilan'    => $idPenampilan,
                                    'tipe_harga'       => 'Bayar',
                                    'harga'            => $harga,
                                    'nama_kategori'    => $kategori_seat
                                ];
                                $this->seatPricingModel->save($seatPricingData);
                                $idSeatPricing = $this->seatPricingModel->getInsertID();
                            }

                            if ($idSchedule && $idSeatPricing) {
                                $this->db->table('r_show_schedule')->insert([
                                    'id_schedule' => $idSchedule,
                                    'id_pricing'  => $idSeatPricing,
                                    'denah_seat'  => $denah_seat
                                ]);
                            }
                        }
                    } elseif ($schedule['tipe_harga'] === 'Gratis') {
                        $seatPricingData = [
                            'id_penampilan'    => $idPenampilan,
                            'tipe_harga'       => 'Gratis',
                            'harga'            => null,
                            'nama_kategori'    => null
                        ];

                        if (!$this->seatPricingModel->save($seatPricingData)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan harga gratis.']);
                        }

                        $idSeatPricing = $this->seatPricingModel->getInsertID();
                        if ($idSchedule && $idSeatPricing) {
                            $this->db->table('r_show_schedule')->insert([
                                'id_schedule' => $idSchedule,
                                'id_pricing'  => $idSeatPricing,
                                'denah_seat'  => null
                            ]);
                        }
                    }

                    log_message('debug', 'Data jadwal diterima: ' . json_encode($hiddenSchedule));
                }

                // Setelah id_teater berhasil didapatkan, baru panggil fungsi sosmed
                if (!empty($data['id_mitra'])) {
                    $this->getMitraSosmed($data['id_mitra'], $idTeater);
                }

                // Jika ada sosial media tambahan dari user, simpan ke r_teater_sosmed
                if (!empty($data['sosmed_platform']) && !empty($data['sosmed_username'])) {
                    log_message('debug', 'Sosmed Data: ' . json_encode($data['sosmed_platform']));
                    log_message('debug', 'Sosmed Username: ' . json_encode($data['sosmed_username']));

                    $this->saveTeaterSosmed($idTeater, $data['id_mitra'], $data['sosmed_platform']);
                }

                $webData = [
                    'id_teater'   => $idTeater,
                    'judul_web'   => $data['judul_web'],
                    'url_web'     => $data['url_web'],
                ];

                if (!empty($data['judul_web']) && !empty($data['url_web'])) {
                    $this->teaterWebModel->save($webData);
                }

                // Ambil ID user yang baru disimpan
                $idTeaterWeb = $this->teaterWebModel->getInsertID();
                log_message('debug', 'ID Web Teater yang diperoleh: ' . json_encode($idTeaterWeb));

                if (!$idTeaterWeb) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal mendapatkan ID web teater.'
                    ]);
                }

                $db->transCommit();
                return $this->response->setJSON([
                    'success'  => true,
                    'message'  => 'Teater berhasil ditambahkan!',
                    'id_teater' => $idTeater,
                    'denah_names' => $denahNames,
                    'redirect' => base_url('Admin/listPenampilan') // Tambahkan URL redirect
                ]);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'errors'  => $e->getMessage() // Debug untuk melihat validasi gagal
            ]);
        }
    }

    public function deleteDenah()
    {
        $filename = $this->request->getGet('file');

        if ($filename) {
            $path = WRITEPATH . 'uploads/denah/' . $filename; // ganti path jika beda

            if (file_exists($path)) {
                unlink($path); // hapus file
                return $this->response->setJSON(['status' => 'success', 'message' => 'File deleted']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File not found']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No file specified']);
    }

    private function saveTeaterSosmed($idTeater, $idMitra, $platforms)
    {
        foreach ($platforms as $platform) {
            if (!empty($platform['id_platform_sosmed']) && !empty($platform['acc_teater'])) {
                // Simpan ke sosial media teater
                $idTeaterSosmed = $this->sosmedModel->insert([
                    'id_teater' => $idTeater,
                    'id_platform_sosmed' => $platform['id_platform_sosmed'],
                    'acc_teater' => $platform['acc_teater'],
                ], true); // Return last insert ID

                // Cek apakah sosial media mitra sudah ada di r_teater_mitra_sosmed
                $mitraSosmed = $this->mitraSosmedModel
                    ->where('id_mitra', $idMitra)
                    ->where('id_platform_sosmed', $platform['id_platform_sosmed'])
                    ->first();

                if ($mitraSosmed) {
                    // Hubungkan sosial media mitra dengan sosial media teater
                    $this->teaterMitraSosmedModel->insert([
                        'id_mitra_sosmed' => $mitraSosmed['id_mitra_sosmed'],
                        'id_teater_sosmed' => $idTeaterSosmed
                    ]);
                }
            }
        }
    }

    public function getApprovedMitra()
    {
        $data = $this->mitraModel
            ->select('m_mitra.id_mitra, m_user.nama')
            ->join('m_user', 'm_user.id_user = m_mitra.id_user')
            ->where('m_mitra.approval_status', 'approved')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getMitraSosmed($id_mitra, $id_teater = null)
    {
        if (!is_numeric($id_mitra)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Mitra tidak valid']);
        }

        // Jika id_teater ada, cek hubungan r_teater_mitra_sosmed
        if (!empty($id_teater) && is_numeric($id_teater)) {
            $isConnected = $this->db->table('r_teater_mitra_sosmed')
                ->where('id_teater', $id_teater)
                ->where('id_mitra', $id_mitra)
                ->countAllResults();

            // Jika belum ada relasi, tambahkan otomatis
            if ($isConnected == 0) {
                $this->db->table('r_teater_mitra_sosmed')->insert([
                    'id_teater' => $id_teater,
                    'id_mitra' => $id_mitra
                ]);
            }
        }

        // Ambil sosial media mitra dari r_mitra_sosmed
        $sosmedList = $this->mitraSosmedModel
            ->select('r_mitra_sosmed.id_platform_sosmed, m_platform_sosmed.platform_name, r_mitra_sosmed.acc_mitra')
            ->join('m_platform_sosmed', 'm_platform_sosmed.id_platform_sosmed = r_mitra_sosmed.id_platform_sosmed', 'left')
            ->where('r_mitra_sosmed.id_mitra', $id_mitra)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $sosmedList
        ]);
    }

    public function addSosmed()
    {
        header("Content-Type: application/json");
        $validation = \Config\Services::validation();

        $validation->setRules([
            'id_platform_sosmed' => 'required|integer',
            'acc_teater' => 'required|min_length[3]|max_length[255]',
            'id_teater' => 'required|integer'
        ]);

        log_message('debug', 'addSosmed function is triggered.');
        log_message('debug', 'Request Data: ' . json_encode($this->request->getPost()));

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $idTeater = $this->request->getPost('id_teater');
        $idPlatformSosmed = $this->request->getPost('id_platform_sosmed');
        $accTeater = $this->request->getPost('acc_teater');

        // Simpan sosial media teater
        $idTeaterSosmed = $this->sosmedModel->insert([
            'id_teater' => $idTeater,
            'id_platform_sosmed' => $idPlatformSosmed,
            'acc_teater' => $accTeater
        ], true);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Sosial media berhasil ditambahkan',
            'data' => [
                'id_teater_sosmed' => $idTeaterSosmed,
                'id_teater' => $idTeater,
                'id_platform_sosmed' => $idPlatformSosmed,
                'acc_teater' => $accTeater
            ]
        ]);
    }

    public function penampilan()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Data dummy untuk jadwal pertunjukan
        $scheduleData = [
            [
                'kota' => 'Jakarta',
                'tempat' => 'Aula Teater Garuda Krisna',
                'tanggal' => '2024-09-12',
                'waktu' => ['15:00 - 17:00 WIB', '19:00 - 21:00 WIB'],
                'harga' => 'Rp100.000 (Premium), Rp75.000 (Reguler)',
                'denah' => 'seat1.jpg'
            ],
            [
                'kota' => 'Bandung',
                'tempat' => 'Teater Budaya',
                'tanggal' => '2024-09-13',
                'waktu' => ['16:00 - 18:00 WIB', '19:30 - 21:30 WIB'],
                'harga' => 'Rp120.000 (VIP), Rp80.000 (Ekonomi)',
                'denah' => 'seat1.jpg'
            ],
            [
                'kota' => 'Bandung',
                'tempat' => 'Teater Pusaka',
                'tanggal' => '2024-09-14',
                'waktu' => ['19:30 - 21:30 WIB'],
                'harga' => 'Rp90.000 (Reguler)',
                'denah' => 'seat1.jpg'
            ],
        ];

        $groupedSchedule = [];
        $placeInfo = []; // Menyimpan harga dan denah untuk tiap tempat

        foreach ($scheduleData as $row) {
            foreach ($row['waktu'] as $waktu) {
                $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = $waktu;
            }

            // Simpan informasi harga dan denah berdasarkan tempat
            $placeInfo[$row['tempat']] = [
                'harga' => $row['harga'],
                'denah' => $row['denah']
            ];
        }

        return view('templates/headerAdmin', ['title' => 'List Penampilan Admin', 'user' => $user]) .
            view('templates/bodyPenampilanAdmin', ['groupedSchedule' => $groupedSchedule, 'placeInfo' => $placeInfo, 'user' => $user]) .
            view('templates/footerPenampilanAdmin');
    }

    public function audisi()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        return view('templates/headerAdmin', ['title' => 'List Audisi Admin', 'user' => $user]) .
            view('templates/bodyAudisiAdmin') .
            view('templates/footerAudisiAdmin');
    }

    // Fungsi helper untuk format sosial media
    private function formatSosmed($sosmedData)
    {
        if (empty($sosmedData) || !is_array($sosmedData)) {
            return '-';
        }

        $grouped = [];

        foreach ($sosmedData as $sosmed) {
            $platform = strtolower($sosmed['platform_name']);
            $account = $sosmed['acc_mitra'];

            if (!isset($grouped[$platform])) {
                $grouped[$platform] = [];
            }
            $grouped[$platform][] = "'$account'";
        }

        $formatted = [];
        foreach ($grouped as $platform => $accounts) {
            $formatted[] = ucfirst($platform) . " " . implode(" ", $accounts);
        }

        return implode(", ", $formatted);
    }

    public function approveMitraList()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $perPage = 5;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $mitra_accounts = $this->mitraModel->getPendingMitra($perPage, $offset);
        $totalMitra = $this->mitraModel->countPendingMitra();
        $totalPages = ceil($totalMitra / $perPage);

        // Format tanggal sebelum dikirim ke view
        foreach ($mitra_accounts as &$mitra) {
            $mitra['berdiri_sejak'] = Time::parse($mitra['berdiri_sejak'])->toLocalizedString('d MMMM yyyy');

            // Ambil data sosial media dari model MitraSosmedModel
            $sosmedData = $this->mitraSosmedModel->getSosmedByMitraId($mitra['id_mitra']);

            // Format sosial media menggunakan fungsi helper
            $mitra['sosial_media'] = $this->formatSosmed($sosmedData);
        }

        return view('templates/headerAdmin', ['title' => 'Approval Akun Mitra Baru', 'user' => $user]) .
            view('templates/approveMitra', compact('mitra_accounts', 'totalMitra', 'totalPages', 'page')) .
            view('templates/footerPenampilanAdmin');
    }

    public function approveMitra($id_mitra)
    {
        if ($this->request->isAJAX()) {
            if (!$id_mitra || !$this->mitraModel->find($id_mitra)) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID mitra tidak valid']);
            }

            $update = $this->mitraModel->approveMitra($id_mitra);

            if ($update) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal update database']);
            }
        }

        return redirect()->to(base_url('Admin/approveMitra'))->with('success', 'Akun telah disetujui.');
    }

    public function rejectMitra()
    {
        $id_mitra = $this->request->getPost('id_mitra');
        $alasan = $this->request->getPost('reason');

        if (!$id_mitra || !$alasan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak terkirim!']);
        }

        $update = $this->mitraModel->rejectMitra($id_mitra, $alasan);

        if ($update) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Akun telah ditolak.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan alasan.']);
        }
    }

    public function mitraTeater()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil data mitra teater dengan informasi user (nama)
        $mitraList = $this->mitraModel->getApprovedMitraWithUser();

        // Kirim data ke view
        return view('templates/headerAdmin', ['title' => 'Daftar Mitra Teater', 'user' => $user]) .
            view('templates/listMitraTeater', ['mitra' => $mitraList]) .
            view('templates/footer');
    }

    public function detail($id)
    {
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil detail mitra berdasarkan ID
        $mitra = $this->mitraModel->getMitraDetail($id);

        // Ambil data sosial media mitra
        $sosial_media = $this->mitraModel->getMitraSosmed($id);

        // Kirim data ke view
        return view('templates/headerAdmin', ['title' => 'Detail Mitra Teater', 'user' => $user]) .
            view('templates/detailMitraTeater', ['mitra' => $mitra, 'sosial_media' => $sosial_media]) .
            view('templates/footer');
    }

    public function profile()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return  view('templates/headerAdmin', ['title' => 'Profile Admin', 'user' => $user]) .
            view('templates/profileUser', ['user' => $user]) .
            view('templates/footer');
    }

    public function aboutUs()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return view('templates/headerAdmin', ['title' => 'Tentang Kami - Theaterform', 'user' => $user]) .
            view('templates/aboutUsAdmin', ['user' => $user]) .
            view('templates/footer');
    }

    public function searchPenampilan()
    {
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil filter dari request
        $durasiMin = $this->request->getGet('minDurasi');
        $durasiMax = $this->request->getGet('maxDurasi');
        $rating = $this->request->getGet('searchInput');
        $tanggal = $this->request->getGet('searchInput');
        $waktuMulai = $this->request->getGet('searchInput');
        $kota = $this->request->getGet('searchInput');
        $hargaMin = $this->request->getGet('minHarga');
        $hargaMax = $this->request->getGet('maxHarga');

        // Query pencarian
        $queryPenampilan = $this->penampilanModel->select('*');
        $querySchedule = $this->showScheduleModel->select('*');
        $queryLokasi = $this->lokasiTeaterModel->select('*');
        $queryHarga = $this->seatPricingModel->select('*');

        // Filter berdasarkan durasi
        if (!empty($durasiMin) && !empty($durasiMax)) {
            $queryPenampilan->where('durasi >=', $durasiMin)
                ->where('durasi <=', $durasiMax);
        }

        // Filter berdasarkan rating umur
        if (!empty($rating)) {
            $queryPenampilan->where('rating_umur', $rating);
        }

        // Filter berdasarkan tanggal pertunjukan
        if (!empty($tanggal)) {
            $querySchedule->where('tanggal', $tanggal);
        }

        // Filter berdasarkan waktu mulai pertunjukan
        if (!empty($waktuMulai)) {
            $querySchedule->where('waktu_mulai', $waktuMulai);
        }

        // Filter berdasarkan kota
        if (!empty($kota)) {
            $queryLokasi->where('kota', $kota);
        }

        // Filter berdasarkan harga
        if (!empty($hargaMin) && !empty($hargaMax)) {
            $queryHarga->where('harga >=', $hargaMin)
                ->where('harga <=', $hargaMax);
        }

        // Jalankan query
        $penampilan = $queryPenampilan->findAll();
        $jadwal = $querySchedule->findAll();
        $lokasi = $queryLokasi->findAll();
        $harga = $queryHarga->findAll();

        return view('templates/headerAdmin', ['title' => 'List Penampilan Admin', 'user' => $user]) .
            view('templates/bodyPenampilanAdmin', [
                'penampilan' => $penampilan,
                'jadwal' => $jadwal,
                'lokasi' => $lokasi,
                'harga' => $harga
            ]) .
            view('templates/footerPenampilanAdmin');
    }

    public function searchAudisi()
    {
        $userId = session()->get('id_user'); // Ambil ID user dari session
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan ID

        // Ambil filter dari request
        $hargaMin = $this->request->getGet('minHarga');
        $hargaMax = $this->request->getGet('maxHarga');
        $tanggal = $this->request->getGet('searchTanggal');
        $waktuMulai = $this->request->getGet('searchWaktu');
        $kota = $this->request->getGet('searchKota');
        $gajiMin = $this->request->getGet('minGaji');
        $gajiMax = $this->request->getGet('maxGaji');

        // Query untuk `m_show_schedule`
        $queryShowSchedule = $this->showScheduleModel;

        if (!empty($tanggal)) {
            $queryShowSchedule = $queryShowSchedule->where('tanggal', $tanggal);
        }

        if (!empty($waktuMulai)) {
            $queryShowSchedule = $queryShowSchedule->where('waktu_mulai', $waktuMulai);
        }

        // Query untuk `m_lokasi_teater`
        $queryLokasi = $this->lokasiTeaterModel;

        if (!empty($kota)) {
            $queryLokasi = $queryLokasi->where('kota', $kota);
        }

        // Query untuk `m_audisi_schedule` (harga)
        $queryAudisiSchedule = $this->audisiScheduleModel;

        if (!empty($hargaMin) && !empty($hargaMax)) {
            $queryAudisiSchedule = $queryAudisiSchedule
                ->where('harga >=', $hargaMin)
                ->where('harga <=', $hargaMax);
        }

        // Query untuk `m_audisi` (gaji)
        $queryAudisi = $this->audisiModel;

        if (!empty($gajiMin) && !empty($gajiMax)) {
            $queryAudisi = $queryAudisi
                ->where('gaji >=', $gajiMin)
                ->where('gaji <=', $gajiMax);
        }

        // Eksekusi query
        $showScheduleResults = $queryShowSchedule->findAll();
        $lokasiResults = $queryLokasi->findAll();
        $audisiScheduleResults = $queryAudisiSchedule->findAll();
        $audisiResults = $queryAudisi->findAll();

        return view('templates/headerAdmin', ['title' => 'List Audisi Admin', 'user' => $user]) .
            view('templates/bodyAudisiAdmin', [
                'showScheduleResults' => $showScheduleResults,
                'lokasiResults' => $lokasiResults,
                'audisiScheduleResults' => $audisiScheduleResults,
                'audisiResults' => $audisiResults
            ]) .
            view('templates/footerAudisiAdmin');
    }
}
