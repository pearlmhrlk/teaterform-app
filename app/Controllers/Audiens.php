<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\AudiensModel;
use App\Models\MitraModel;
use App\Models\ShowSchedule;
use App\Models\LokasiTeater;
use App\Models\SeatPricing;
use App\Models\AudisiSchedule;
use App\Models\Audisi;
use App\Models\Teater;
use App\Models\Penampilan;
use App\Models\TeaterSosmed;
use App\Models\TeaterWeb;
use App\Models\UserTeater;
use App\Models\AudisiAktor;
use App\Models\AudisiPricing;
use App\Models\ShowSeatPricing;
use App\Models\Booking;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
use Config\Database;

class Audiens extends BaseController
{

    protected $userModel;
    protected $user;
    protected $audiensModel;
    protected $mitraModel;
    protected $penampilanModel;
    protected $showScheduleModel;
    protected $lokasiTeaterModel;
    protected $seatPricingModel;
    protected $audisiScheduleModel;
    protected $audisiModel;
    protected $teaterModel;
    protected $teaterSosmedModel;
    protected $teaterWebModel;
    protected $userTeaterModel;
    protected $audisiAktorModel;
    protected $audisiPricingModel;
    protected $showSeatPricingModel;
    protected $bookingModel;

    protected $db;

    public function __construct()
    {
        helper('session'); // Pastikan session helper dipanggil

        $this->userModel = new User(); // Pastikan UserModel sudah ada
        $this->audiensModel = new AudiensModel(); // Instance model Audiens
        $this->mitraModel = new MitraModel();
        $this->penampilanModel = new Penampilan();
        $this->showScheduleModel = new ShowSchedule();
        $this->lokasiTeaterModel = new LokasiTeater();
        $this->seatPricingModel = new SeatPricing();
        $this->audisiScheduleModel = new AudisiSchedule();
        $this->audisiModel = new Audisi();
        $this->teaterModel = new Teater();
        $this->teaterSosmedModel = new TeaterSosmed();
        $this->teaterWebModel = new TeaterWeb();
        $this->userTeaterModel = new UserTeater();
        $this->audisiAktorModel = new AudisiAktor();
        $this->audisiPricingModel = new AudisiPricing();
        $this->showSeatPricingModel = new ShowSeatPricing();
        $this->bookingModel = new Booking();

        $this->db = Database::connect();

        $this->user = session()->get(); // Ambil semua data dari session
    }

    public function homepage()
    {
        return view('templates/headerUser',  ['title' => 'Homepage Theaterform']) .
            view('templates/bodyHomepage') .
            view('templates/footer');
    }

    public function register()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        try {
            // Coba membuat koneksi database
            $db = \Config\Database::connect();

            // Periksa apakah koneksi berhasil atau tidak
            if (!$db instanceof \CodeIgniter\Database\ConnectionInterface) {
                throw new \RuntimeException('Koneksi ke database gagal.');
            }

            // Tidak perlu menampilkan pesan apa pun jika koneksi berhasil
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        // Jika form disubmit
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();

            $rules = [
                'username' => 'required|min_length[3]|max_length[15]',
                'nama'     => 'required',
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]',
                'tgl_lahir' => 'required|valid_date',
                'gender'   => 'required|in_list[male,female]',
                'id_role'  => 'required|in_list[1]', // Role Audiens (id_role = 1)
            ];

            // Gunakan validasi bawaan terlebih dahulu
            // Gunakan metode dari model untuk memeriksa keunikan username dan email
            if (!$this->userModel->isUniqueUsername($data['username'], $data['id_role'])) {
                return redirect()->back()->withInput()->with('errors', ['username' => 'Username sudah digunakan untuk role ini.']);
            }

            if (!$this->userModel->isUniqueEmail($data['email'], $data['id_role'])) {
                return redirect()->back()->withInput()->with('errors', ['email' => 'Email sudah digunakan untuk role ini.']);
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userData = [
                'id_role' => 1, // Role Audiens
                'username' => $data['username'],
                'nama' => $data['nama'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'email' => $data['email'],
                'login_attempt' => 0,
                'tgl_dibuat' => date('Y-m-d H:i:s'),
                'tgl_dimodif' => date('Y-m-d H:i:s')
            ];

            if (!$this->userModel->save($userData)) {
                session()->setFlashdata('error', 'Gagal menyimpan data pengguna. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }

            // Simpan user ke tabel m_user
            $userId = $this->userModel->getInsertID(); // Ambil ID User yang baru disimpan

            // Input data audiens ke tabel m_audiens
            $audiensData = [
                'id_user' => $userId, // Menghubungkan audiens dengan user
                'tgl_lahir' => $data['tgl_lahir'],
                'gender' => $data['gender'],
            ];

            // Simpan audiens ke tabel m_audiens
            $this->audiensModel->save($audiensData); // id_audiens dihasilkan otomatis oleh database         

            session()->setFlashdata('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
            return redirect()->to(base_url('Audiens/confirmation')); // Arahkan ke halaman konfirmasi
        }

        return view('templates/headerRegist') .
            view('templates/bodyRegist');
    }

    public function confirmation()
    {
        return view('templates/audiensConfirmation');
    }

    public function homepageAfterLogin()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Kirim data user ke view
        return view('templates/headerAudiens', ['title' => 'Homepage Audiens', 'user'  => $this->user]) .
            view('templates/bodyHomepage') .
            view('templates/footer');
    }

    public function listPenampilan()
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');

        // Ambil semua penampilan yang masih relevan (hari ini atau masa depan)
        $allPenampilan = $this->db->table('m_teater')
            ->select('
        m_teater.id_teater,
        m_penampilan.id_penampilan,
        m_teater.judul,
        m_teater.poster,
        m_user.nama AS komunitas_teater,
        m_lokasi_teater.tempat,
        m_lokasi_teater.kota,
        m_show_schedule.tanggal,
        m_show_schedule.waktu_mulai,
        m_show_schedule.waktu_selesai,
        m_penampilan.rating_umur
    ')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_show_schedule', 'm_show_schedule.id_teater = m_teater.id_teater')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->join('m_penampilan', 'm_penampilan.id_teater = m_teater.id_teater')
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $sedangTayang = [];
        $akanTayang = [];

        $grouped = [];

        // Gunakan array untuk menyimpan ID penampilan yang sudah dimasukkan
        $seenSedang = [];
        $seenAkan = [];

        foreach ($allPenampilan as $p) {
            if (!isset($p['id_penampilan'])) continue;

            $id = $p['id_penampilan'];
            $grouped[$id][] = $p; // hanya kelompokkan, klasifikasi nanti
        }

        foreach ($grouped as $idPenampilan => $jadwals) {
            $adaYangLewat = false;
            $adaYangAkanDatang = false;
            usort($jadwals, function ($a, $b) {
                return strtotime($a['tanggal'] . ' ' . $a['waktu_mulai']) - strtotime($b['tanggal'] . ' ' . $b['waktu_mulai']);
            });

            foreach ($jadwals as $j) {
                $waktuFull = strtotime($j['tanggal'] . ' ' . $j['waktu_mulai']);
                $nowFull = strtotime($today . ' ' . $now);

                if ($waktuFull < $nowFull) $adaYangLewat = true;
                if ($waktuFull >= $nowFull) $adaYangAkanDatang = true;
            }

            if ($adaYangLewat && $adaYangAkanDatang) {
                // ambil jadwal TERDEKAT (bisa sudah lewat atau sebentar lagi) → gunakan yang paling dekat dengan waktu sekarang
                $jadwalTerdekat = null;
                $selisihTerkecil = PHP_INT_MAX;

                foreach ($jadwals as $j) {
                    $jadwalWaktu = strtotime($j['tanggal'] . ' ' . $j['waktu_mulai']);
                    $selisih = abs($jadwalWaktu - $nowFull);

                    if ($selisih < $selisihTerkecil) {
                        $selisihTerkecil = $selisih;
                        $jadwalTerdekat = $j;
                    }
                }

                if ($jadwalTerdekat) {
                    $sedangTayang[] = $this->formatPenampilan($jadwalTerdekat);
                }
            } elseif ($adaYangAkanDatang && !$adaYangLewat) {
                // akan tayang
                $akanTayang[] = $this->formatPenampilan($jadwals[0]); // jadwal terdekat
            }
            // kalau semua sudah lewat, abaikan
        }

        return view('templates/headerUser',  ['title' => 'List Penampilan Teater']) .
            view('templates/bodyListPenampilan', compact('sedangTayang', 'akanTayang')) .
            view('templates/footerListPenampilan', ['needsDropdown' => true]);
    }

    private function formatPenampilan($penampilan)
    {
        return [
            'id_teater' => $penampilan['id_teater'],
            'judul' => $penampilan['judul'],
            'komunitas_teater' => $penampilan['komunitas_teater'],
            'lokasi_teater' => $penampilan['tempat'],
            'tanggal' => $this->formatTanggalIndoLengkap($penampilan['tanggal']),
            'waktu' => $this->formatJam($penampilan['waktu_mulai']) . ' - ' . $this->formatJam($penampilan['waktu_selesai']),
            'rating_umur' => $penampilan['rating_umur'],
            'poster' => $penampilan['poster'],
        ];
    }

    private function formatTanggalIndoLengkap($tanggal)
    {
        return Time::parse($tanggal)->toLocalizedString('eeee, d MMMM yyyy');
    }

    private function formatJam($waktu)
    {
        return date('H:i', strtotime($waktu)) . ' WIB';
    }

    public function ListAudisi()
    {
        $audisiAktor = [
            [
                'judul' => 'The Princess from Knowhere',
                'karakter_audisi' => 'Putri Liliput',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
            [
                'judul' => 'Tung Tang Ting',
                'karakter_audisi' => 'Lily Tulalit',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 April 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster1.jpg')
            ],
            [
                'judul' => 'The Prince of Konoha',
                'karakter_audisi' => 'Bangsawan Ethan',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster4.jpeg')
            ],
            [
                'judul' => 'Bajak Sambal dan Laut',
                'karakter_audisi' => 'Kapten Hulahoop',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '7 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster3.jpeg')
            ],
            [
                'judul' => 'Hutang Piutang',
                'karakter_audisi' => 'Mak Sukinem',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Februari 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
        ];

        $audisiStaff = [
            [
                'judul' => 'The Princess from Knowhere',
                'jenis_staff' => 'Tata Lampu',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
            [
                'judul' => 'Tung Tang Ting',
                'jenis_staff' => 'Tata Busana',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 April 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster1.jpg')
            ],
            [
                'judul' => 'The Prince of Konoha',
                'jenis_staff' => 'Tata Panggung',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster4.jpeg')
            ],
            [
                'judul' => 'Bajak Sambal dan Laut',
                'jenis_staff' => 'Asisten Sutradara',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '7 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster3.jpeg')
            ],
            [
                'judul' => 'Hutang Piutang',
                'jenis_staff' => 'Tata Properti',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Februari 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
        ];

        return view('templates/headerUser',  ['title' => 'List Audisi Teater']) .
            view('templates/bodyAudisi', compact('audisiAktor', 'audisiStaff')) .
            view('templates/footerListPenampilan');
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

    public function DetailPenampilan($id_teater)
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        // 1. Ambil data umum teater
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->first();

        // 2. Ambil data penampilan (aktor, durasi, rating)
        $penampilan = $this->penampilanModel
            ->where('id_teater', $id_teater)
            ->first();

        // 3. Ambil data sosial media (IG & FB)
        $sosmed = $this->teaterSosmedModel
            ->select('m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
            ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
            ->where('r_teater_sosmed.id_teater', $id_teater)
            ->findAll();

        // 4. Ambil website teater
        $website = $this->teaterWebModel
            ->where('id_teater', $id_teater)
            ->first();

        // 5. Ambil nama komunitas teater dari user (pemilik teater)
        $userTeater = $this->userTeaterModel
            ->where('id_teater', $id_teater)
            ->first();

        $namaKomunitas = $this->userModel
            ->where('id_user', $userTeater['id_user'])
            ->first();

        $mitra = $this->mitraModel
            ->where('id_user', $namaKomunitas['id_user'])
            ->first();

        $penampilanId = $penampilan['id_penampilan'];

        // 6. Ambil semua jadwal show + lokasi
        $jadwal = $this->showSeatPricingModel
            ->select('
        s.tanggal,
        CONCAT(DATE_FORMAT(s.waktu_mulai, "%H:%i"), " - ", DATE_FORMAT(s.waktu_selesai, "%H:%i")) AS waktu,
        m_lokasi_teater.kota,
        m_lokasi_teater.tempat,
        m_seat_pricing.harga,
        m_seat_pricing.tipe_harga,
        m_seat_pricing.nama_kategori,
        m_denah_seat.denah_seat
    ')
            ->join('m_show_schedule AS s', 'r_show_schedule.id_schedule = s.id_schedule')
            ->join('m_lokasi_teater', 's.id_location = m_lokasi_teater.id_location')
            ->join('m_seat_pricing', 'r_show_schedule.id_pricing = m_seat_pricing.id_pricing')
            ->join('m_denah_seat', 'r_show_schedule.id_denah = m_denah_seat.id_denah', 'left') // <-- tambahkan ini
            ->where('m_seat_pricing.id_penampilan', $penampilanId)
            ->findAll();

        $groupedSchedule = [];

        foreach ($jadwal as $row) {
            $kota = $row['kota'];
            $tempat = $row['tempat'];
            $tanggal = $row['tanggal'];
            $waktu = $row['waktu'];

            // Kelompokkan berdasarkan kota → tempat → tanggal → waktu
            $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['harga'][] = [
                'nama_kategori' => $row['nama_kategori'],
                'harga' => $row['harga'],
                'tipe_harga' => $row['tipe_harga'],
            ];
            $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['denah'] = $row['denah_seat'];
        }

        return view('templates/headerAudiens',  ['title' => 'Detail Penampilan Teater', 'user'  => $user]) .
            view('templates/bodyDetailPenampilan', [
                'teater' => $teater,
                'penampilan' => $penampilan,
                'sosmed' => $sosmed,
                'website' => $website,
                'namaKomunitas' => $namaKomunitas,
                'mitraTeater' => $mitra,
                'groupedSchedule' => $groupedSchedule,
            ]) .
            view('templates/footerListPenampilan');
    }

    public function showBookingPopup($tipe, $id)
    {
        if ($tipe === 'penampilan') {
            $teater = $this->teaterModel->find($id);

            $jadwal = $this->db->table('r_show_schedule')
                ->select('r_show_schedule.id_schedule_show AS id_jadwal, m_show_schedule.tanggal, m_show_schedule.waktu_mulai, m_show_schedule.waktu_selesai')
                ->join('m_show_schedule', 'r_show_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_teater', 'm_show_schedule.id_teater = m_teater.id_teater')
                ->where('m_teater.id_teater', $id)
                ->groupBy('r_show_schedule.id_schedule')
                ->get()->getResultArray();

            return $this->response->setJSON([
                'jadwal' => $jadwal,
                'url_pendaftaran' => $teater['url_pendaftaran']
            ]);
        } elseif ($tipe === 'audisi') {
            $audisi = $this->teaterModel->find($id);

            $jadwal = $this->db->table('r_audisi_schedule')
                ->select('r_audisi_schedule.id_audisi_schedule AS id_jadwal, m_show_schedule.tanggal, m_show_schedule.waktu_mulai, m_show_schedule.waktu_selesai')
                ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_teater', 'm_show_schedule.id_teater = m_teater.id_teater')
                ->where('m_teater.id_teater', $id)
                ->groupBy('r_audisi_schedule.id_schedule')
                ->get()->getResultArray();

            return $this->response->setJSON([
                'jadwal' => $jadwal,
                'url_pendaftaran' => $audisi['url_pendaftaran']
            ]);
        }
    }

    public function simpanBooking()
    {
        $data = $this->request->getJSON();
        $idJadwal = $data->id_jadwal;
        $tipeJadwal = $data->tipe_jadwal;

        if ($tipeJadwal === 'penampilan') {
            $jadwal = $this->showSeatPricingModel->getScheduleWithPrice($idJadwal);
            $isFree = isset($jadwal['harga']) && $jadwal['harga'] == 0 ? 1 : 0;
        } elseif ($tipeJadwal === 'audisi') {
            $jadwal = $this->audisiScheduleModel->getScheduleWithPrice($idJadwal);
            $isFree = isset($jadwal['harga']) && $jadwal['harga'] == 0 ? 1 : 0;
        }

        // Ambil user dari session
        $idUser = session()->get('id_user');

        // Cari id_audiens dari m_audiens berdasarkan id_user
        $audiens = $this->audiensModel
            ->where('id_user', $idUser)
            ->first();

        if (!$audiens) {
            return $this->response->setJSON(['success' => false, 'message' => 'Audiens tidak ditemukan.']);
        }

        $idAudiens = $audiens['id_audiens'];

        // Cek apakah user sudah booking jadwal ini sebelumnya
        $bookingLama = $this->bookingModel
            ->where('id_audiens', $idAudiens)
            ->where('id_jadwal', $idJadwal)
            ->where('tipe_jadwal', $tipeJadwal)
            ->first();

        if ($bookingLama) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kamu sudah melakukan booking untuk jadwal ini.'
            ]);
        }

        // Simpan ke t_booking
        $this->bookingModel->save([
            'id_audiens' => $idAudiens,
            'id_jadwal' => $idJadwal,
            'tipe_jadwal' => $tipeJadwal,
            'is_free' => $isFree,
            'status' => 'pending'
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function konfirmasiUploadBukti($id_booking)
    {
        // Ambil data file upload
        $file = $this->request->getFile('bukti_pembayaran');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/bukti/', $newName);

            // Update data
            $this->bookingModel->update($id_booking, [
                'bukti_pembayaran' => $newName,
                'status' => 'success'
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Upload bukti pembayaran gagal.');
    }

    public function hapusBookingPending($id_jadwal)
    {
        $idUser = session()->get('id_user');

        $audiens = $this->audiensModel->where('id_user', $idUser)->first();
        if (!$audiens) {
            return $this->response->setJSON(['success' => false, 'message' => 'Audiens tidak ditemukan.']);
        }

        $this->bookingModel->where([
            'id_audiens' => $audiens['id_audiens'],
            'id_jadwal' => $id_jadwal,
            'status' => 'pending'
        ])->delete();

        return $this->response->setJSON(['success' => true]);
    }

    public function ubahStatusSuccess($idJadwal)
    {
        $idUser = session()->get('id_user');

        // Cari id_audiens
        $audiens = $this->audiensModel
            ->where('id_user', $idUser)
            ->first();

        if (!$audiens) {
            return $this->response->setJSON(['success' => false, 'message' => 'Audiens tidak ditemukan.']);
        }

        $idAudiens = $audiens['id_audiens'];

        // Update status booking
        $this->bookingModel->where([
            'id_jadwal' => $idJadwal,
            'id_audiens' => $idAudiens,
            'status' => 'pending'
        ])->set(['status' => 'success'])->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function penampilanAfterLogin()
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $today = date('Y-m-d');
        $now = date('H:i:s');

        $whereManual = "(m_teater.daftar_mulai IS NOT NULL AND m_teater.daftar_berakhir IS NOT NULL AND CURDATE() BETWEEN m_teater.daftar_mulai AND m_teater.daftar_berakhir)";
        $whereOtomatis = "(m_teater.daftar_mulai IS NULL AND m_teater.daftar_berakhir IS NULL)";
        $whereAll = "($whereManual OR $whereOtomatis)";

        // Ambil semua penampilan yang masih relevan (hari ini atau masa depan)
        $allPenampilan = $this->db->table('m_teater')
            ->select('
    m_teater.id_teater,
    m_penampilan.id_penampilan,
    m_teater.judul,
    m_teater.poster,
    m_user.nama AS komunitas_teater,
    m_lokasi_teater.tempat,
    m_lokasi_teater.kota,
    m_show_schedule.tanggal,
    m_show_schedule.waktu_mulai,
    m_show_schedule.waktu_selesai,
    m_penampilan.rating_umur
')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_show_schedule', 'm_show_schedule.id_teater = m_teater.id_teater')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->join('m_penampilan', 'm_penampilan.id_teater = m_teater.id_teater')
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $sedangTayang = [];
        $akanTayang = [];

        $grouped = [];

        // Gunakan array untuk menyimpan ID penampilan yang sudah dimasukkan
        $seenSedang = [];
        $seenAkan = [];

        foreach ($allPenampilan as $p) {
            if (!isset($p['id_penampilan'])) continue;

            $id = $p['id_penampilan'];
            $grouped[$id][] = $p; // hanya kelompokkan, klasifikasi nanti
        }

        foreach ($grouped as $idPenampilan => $jadwals) {
            $adaYangLewat = false;
            $adaYangAkanDatang = false;
            usort($jadwals, function ($a, $b) {
                return strtotime($a['tanggal'] . ' ' . $a['waktu_mulai']) - strtotime($b['tanggal'] . ' ' . $b['waktu_mulai']);
            });

            foreach ($jadwals as $j) {
                $waktuFull = strtotime($j['tanggal'] . ' ' . $j['waktu_mulai']);
                $nowFull = strtotime($today . ' ' . $now);

                if ($waktuFull < $nowFull) $adaYangLewat = true;
                if ($waktuFull >= $nowFull) $adaYangAkanDatang = true;
            }

            if ($adaYangLewat && $adaYangAkanDatang) {
                // ambil jadwal TERDEKAT (bisa sudah lewat atau sebentar lagi) → gunakan yang paling dekat dengan waktu sekarang
                $jadwalTerdekat = null;
                $selisihTerkecil = PHP_INT_MAX;

                foreach ($jadwals as $j) {
                    $jadwalWaktu = strtotime($j['tanggal'] . ' ' . $j['waktu_mulai']);
                    $selisih = abs($jadwalWaktu - $nowFull);

                    if ($selisih < $selisihTerkecil) {
                        $selisihTerkecil = $selisih;
                        $jadwalTerdekat = $j;
                    }
                }

                if ($jadwalTerdekat) {
                    $sedangTayang[] = $this->formatPenampilan($jadwalTerdekat);
                }
            } elseif ($adaYangAkanDatang && !$adaYangLewat) {
                // akan tayang
                $akanTayang[] = $this->formatPenampilan($jadwals[0]); // jadwal terdekat
            }
            // kalau semua sudah lewat, abaikan
        }

        return view('templates/headerAudiens',  ['title' => 'List Penampilan Teater', 'user' => $user]) .
            view('templates/bodyListPenampilan', compact('sedangTayang', 'akanTayang')) .
            view('templates/footerListPenampilan', ['needsDropdown' => true]);
    }

    public function AudisiAfterLogin()
    {
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $audisiAktor = [
            [
                'judul' => 'The Princess from Knowhere',
                'karakter_audisi' => 'Putri Liliput',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
            [
                'judul' => 'Tung Tang Ting',
                'karakter_audisi' => 'Lily Tulalit',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 April 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster1.jpg')
            ],
            [
                'judul' => 'The Prince of Konoha',
                'karakter_audisi' => 'Bangsawan Ethan',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster4.jpeg')
            ],
            [
                'judul' => 'Bajak Sambal dan Laut',
                'karakter_audisi' => 'Kapten Hulahoop',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '7 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster3.jpeg')
            ],
            [
                'judul' => 'Hutang Piutang',
                'karakter_audisi' => 'Mak Sukinem',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Februari 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
        ];

        $audisiStaff = [
            [
                'judul' => 'The Princess from Knowhere',
                'jenis_staff' => 'Tata Lampu',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
            [
                'judul' => 'Tung Tang Ting',
                'jenis_staff' => 'Tata Busana',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '17 April 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster1.jpg')
            ],
            [
                'judul' => 'The Prince of Konoha',
                'jenis_staff' => 'Tata Panggung',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster4.jpeg')
            ],
            [
                'judul' => 'Bajak Sambal dan Laut',
                'jenis_staff' => 'Asisten Sutradara',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '7 Maret 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster3.jpeg')
            ],
            [
                'judul' => 'Hutang Piutang',
                'jenis_staff' => 'Tata Properti',
                'komunitas_teater' => 'Pria Bercerita Production',
                'lokasi_teater' => 'Ciputra Artpreneur Theater',
                'hari' => 'Sabtu',
                'tanggal' => '27 Februari 2025',
                'jam' => '18:00 WIB',
                'poster' => base_url('assets/images/poster/poster2.jpeg')
            ],
        ];

        return view('templates/headerAudiens',  ['title' => 'List Audisi Teater', 'user' => $user]) .
            view('templates/bodyAudisi', compact('audisiAktor', 'audisiStaff')) .
            view('templates/footerListPenampilan');
    }

    public function DetailAudisiAktor($id_teater)
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        // 1. Ambil data umum teater
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->where('tipe_teater', 'audisi')
            ->first();

        // 2. Ambil data audisi utama
        $audisi = $this->audisiModel
            ->where('id_teater', $teater['id_teater'])
            ->first();

        // 3. Ambil karakter dan deskripsi audisi aktor
        $aktorAudisi = $this->audisiAktorModel
            ->where('id_audisi', $audisi['id_audisi'])
            ->first();

        // 4. Ambil harga audisi dari m_audisi_schedule
        $audisiPricing = $this->audisiScheduleModel
            ->where('id_audisi', $audisi['id_audisi'])
            ->findAll();

        // 6. Ambil data sosial media teater
        $sosmed = $this->teaterSosmedModel
            ->select('m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
            ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
            ->where('r_teater_sosmed.id_teater', $id_teater)
            ->findAll();

        // 7. Ambil website teater
        $website = $this->teaterWebModel
            ->where('id_teater', $id_teater)
            ->first();

        // 8. Ambil nama komunitas teater dari user (pemilik teater)
        $userTeater = $this->userTeaterModel
            ->where('id_teater', $id_teater)
            ->first();

        $namaKomunitas = $this->userModel
            ->where('id_user', $userTeater['id_user'])
            ->first();

        $mitra = $this->mitraModel
            ->where('id_user', $namaKomunitas['id_user'])
            ->first();

        // 5. Ambil jadwal audisi (gabung dari r_audisi_schedule dan m_show_schedule)
        $jadwalAudisi = $this->audisiPricingModel
            ->select('
    m_show_schedule.tanggal,
    CONCAT(DATE_FORMAT(m_show_schedule.waktu_mulai, "%H:%i"), " - ", DATE_FORMAT(m_show_schedule.waktu_selesai, "%H:%i")) AS waktu,
    m_lokasi_teater.kota,
    m_lokasi_teater.tempat,
    m_audisi_schedule.harga, m_audisi_schedule.tipe_harga
')
            ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
            ->join('m_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_audisi', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
            ->where('m_audisi.id_teater', $id_teater)
            ->orderBy('m_lokasi_teater.kota')
            ->findAll();

        $groupedSchedule = [];

        foreach ($jadwalAudisi as $row) {
            $formattedHarga = $row['harga'] == 0 ? '-' : number_format($row['harga'], 0, ',', '.');

            $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = [
                'waktu' => $row['waktu'],
                'harga_display' => $formattedHarga
            ];
        }

        return view('templates/headerAudiens',  ['title' => 'Detail Audisi Aktor Teater', 'user'  => $this->user]) .
            view('templates/bodyDetailAudisiAktor', [
                'teater' => $teater,
                'audisi' => $audisi,
                'aktorAudisi' => $aktorAudisi,
                'sosmed' => $sosmed,
                'website' => $website,
                'namaKomunitas' => $namaKomunitas,
                'mitra' => $mitra,
                'groupedSchedule' => $groupedSchedule
            ]) .
            view('templates/footerListPenampilan');
    }

    public function detailAudisiStaff()
    {

        $session = session();

        // Cek apakah user sudah login
        if (!$session->has('id_user')) {
            return redirect()->to(base_url('User/login'))->with('error', 'Silakan login untuk melihat detail.');
        }

        // Data dummy untuk jadwal pertunjukan
        $scheduleData = [
            [
                'kota' => 'Jakarta',
                'tempat' => 'Aula Teater Garuda Krisna',
                'tanggal' => '2024-09-12',
                'waktu' => ['Sesi I 15:00 - 17:00 WIB', 'Sesi II 19:00 - 21:00 WIB'],
            ],
            [
                'kota' => 'Bandung',
                'tempat' => 'Teater Budaya',
                'tanggal' => '2024-09-13',
                'waktu' => ['Sesi I 16:00 - 18:00 WIB', 'Sesi II 19:30 - 21:30 WIB'],
            ],
            [
                'kota' => 'Bandung',
                'tempat' => 'Teater Pusaka',
                'tanggal' => '2024-09-14',
                'waktu' => ['19:30 - 21:30 WIB'],
            ],
        ];

        $groupedSchedule = [];

        foreach ($scheduleData as $row) {
            foreach ($row['waktu'] as $waktu) {
                $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = $waktu;
            }
        }

        return view('templates/headerAudiens',  ['title' => 'Detail Audisi Staff Teater', 'user'  => $this->user]) .
            view('templates/bodyDetailAudisiStaff', ['groupedSchedule' => $groupedSchedule]) .
            view('templates/footerListPenampilan', ['needsDropdown' => true]);
    }

    public function tentangKami()
    {
        return view('templates/headerUser', ['title' => 'Tentang Kami - Theaterform']) .
            view('templates/aboutUs') .
            view('templates/footer');
    }

    public function aboutUs()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return view('templates/headerAudiens', ['title' => 'Tentang Kami - Theaterform', 'user' => $user]) .
            view('templates/aboutUs', ['user' => $user]) .
            view('templates/footer');
    }



    public function mitraTeater()
    {
        // Ambil data mitra teater dengan informasi user (nama)
        $mitraList = $this->mitraModel->getApprovedMitraWithUser();

        // Kirim data ke view
        return view('templates/headerUser', ['title' => 'Daftar Mitra Teater']) .
            view('templates/listMitraTeater', ['mitra' => $mitraList]) .
            view('templates/footer');
    }

    public function listMitraTeater()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil data mitra teater dengan informasi user (nama)
        $mitraList = $this->mitraModel->getApprovedMitraWithUser();

        // Kirim data ke view
        return view('templates/headerAudiens', ['title' => 'Daftar Mitra Teater', 'user' => $user]) .
            view('templates/listMitraTeater', ['mitra' => $mitraList]) .
            view('templates/footer');
    }

    public function detailMitra($id)
    {
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil detail mitra berdasarkan ID
        $mitra = $this->mitraModel->getMitraDetail($id);

        // Ambil data sosial media mitra
        $sosial_media = $this->mitraModel->getMitraSosmed($id);

        // Kirim data ke view
        return view('templates/headerAudiens', ['title' => 'Detail Mitra Teater', 'user' => $user]) .
            view('templates/detailMitraTeater', ['mitra' => $mitra, 'sosial_media' => $sosial_media]) .
            view('templates/footer');
    }

    public function profile()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return  view('templates/headerAudiens', ['title' => 'Profile Audiens', 'user' => $user]) .
            view('templates/profileUser', ['user' => $user]) .
            view('templates/footer');
    }

    public function searchPenampilan()
    {
        // Ambil filter dari request
        $category = $this->request->getGet('category'); // Ambil kategori pencarian
        $searchValue = $this->request->getGet('searchInput'); // Untuk kategori yang pakai input tunggal
        $durasiMin = $this->request->getGet('minDurasi');
        $durasiMax = $this->request->getGet('maxDurasi');
        $hargaMin = $this->request->getGet('minHarga');
        $hargaMax = $this->request->getGet('maxHarga');

        // Query utama menggunakan JOIN
        $query = $this->penampilanModel
            ->select('
        m_teater.judul, 
        m_teater.poster, 
        m_teater.sinopsis, 
        m_teater.penulis, 
        m_teater.sutradara, 
        m_teater.staff,

        m_teater_website.judul_web, 
        m_teater_website.url_web,

        m_show_schedule.tanggal, 
        m_show_schedule.waktu_mulai, 
        m_show_schedule.waktu_selesai,

        m_lokasi_teater.tempat, 
        m_lokasi_teater.kota,

        m_penampilan.aktor, 
        m_penampilan.durasi, 
        m_penampilan.rating_umur,

        MIN(m_seat_pricing.harga) as harga_terendah, 
        MAX(m_seat_pricing.harga) as harga_tertinggi,

        m_seat_category.nama_kategori, 
        m_seat_category.denah_seat,

        m_sosmed_platform.platform_name, 

        r_teater_sosmed.acc_teater, 
        r_mitra_sosmed.acc_mitra, 

        m_user.nama as nama_creator
    ')

            // **Relasi Penampilan dan Teater**
            ->join('m_teater', 'm_teater.id_teater = m_penampilan.id_teater')

            // **Relasi Show Schedule & Lokasi**
            ->join('m_show_schedule', 'm_show_schedule.id_teater = m_teater.id_teater')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->join('m_seat_pricing', 'm_seat_pricing.id_penampilan = m_penampilan.id_penampilan')

            // **Relasi Penampilan dan Harga Seat**
            ->join('r_show_schedule', 'r_show_schedule.id_schedule = m_show_schedule.id_schedule')
            ->join('m_seat_pricing', 'm_seat_pricing.id_pricing = r_show_schedule.id_pricing')
            ->join('m_seat_category', 'm_seat_category.id_kategori_seat = m_seat_pricing.id_kategori_seat')

            // **Relasi Website Teater**
            ->join('m_teater_website', 'm_teater_website.id_teater = m_teater.id_teater', 'left')

            // **Relasi Sosial Media Teater**
            ->join('r_teater_sosmed', 'r_teater_sosmed.id_teater = m_teater.id_teater', 'left')
            ->join('m_sosmed_platform', 'm_sosmed_platform.id_platform_sosmed = r_teater_sosmed.id_platform_sosmed', 'left')

            // **Relasi Sosial Media Mitra**
            ->join('r_teater_mitra_sosmed', 'r_teater_mitra_sosmed.id_teater_sosmed = r_teater_sosmed.id_teater_sosmed', 'left')
            ->join('r_mitra_sosmed', 'r_mitra_sosmed.id_mitra_sosmed = r_teater_mitra_sosmed.id_mitra_sosmed', 'left')

            // **Relasi User & Mitra**
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater', 'left')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user', 'left')

            ->groupBy('m_penampilan.id_penampilan'); // Mencegah duplikasi hasil

        // Filter berdasarkan kategori yang dipilih
        switch ($category) {
            case 'tanggal':
                if (!empty($searchValue)) {
                    $query->where('m_show_schedule.tanggal', $searchValue);
                }
                break;

            case 'waktu':
                if (!empty($searchValue)) {
                    $query->where('m_show_schedule.waktu_mulai', $searchValue);
                }
                break;

            case 'kota':
                if (!empty($searchValue)) {
                    $query->where('m_lokasi_teater.kota', $searchValue);
                }
                break;

            case 'harga':
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $query->having('harga_terendah <=', $hargaMax);
                    // Tidak perlu filter harga_min karena teater tetap harus ditampilkan
                }
                break;

            case 'durasi':
                if (!empty($durasiMin) && !empty($durasiMax)) {
                    $query->where('m_penampilan.durasi >=', $durasiMin)
                        ->where('m_penampilan.durasi <=', $durasiMax);
                }
                break;

            case 'rating':
                if (!empty($searchValue)) {
                    $query->where('m_penampilan.rating_umur', $searchValue);
                }
                break;
        }

        // Jalankan query
        $penampilan = $query->findAll();

        return view('templates/headerUser', ['title' => 'List Penampilan Teater']) .
            view('templates/bodyListPenampilan', ['penampilan' => $penampilan]) .
            view('templates/footerListPenampilan');
    }

    public function searchAudisi()
    {
        // Ambil filter dari request
        $category = $this->request->getGet('category');
        $searchInput = $this->request->getGet('searchInput');
        $minHarga = $this->request->getGet('minHarga');
        $maxHarga = $this->request->getGet('maxHarga');
        $minGaji = $this->request->getGet('minGaji');
        $maxGaji = $this->request->getGet('maxGaji');

        // Query dasar dengan JOIN
        $query = $this->showScheduleModel
            ->select('
        m_teater.judul, 
        m_teater.poster, 
        m_teater.sinopsis, 
        m_teater.penulis, 
        m_teater.sutradara, 
        m_teater.staff,

        m_teater_website.judul_web, 
        m_teater_website.url_web,

        m_show_schedule.tanggal, 
        m_show_schedule.waktu_mulai, 
        m_show_schedule.waktu_selesai,

        m_lokasi_teater.tempat, 
        m_lokasi_teater.kota,

        m_audisi.syarat, 
        m_audisi.syarat_dokumen, 
        m_audisi.gaji,
        m_audisi.komitmen,

        m_kategori_audisi.nama_kategori,

        m_audisi_aktor.karakter_audisi,
        m_audisi_aktor.deskripsi_karakter,

        m_audisi_staff.jenis_staff,
        m_audisi_staff.jobdesc_staff,

        m_audisi_schedule.harga,

        m_sosmed_platform.platform_name, 

        r_teater_sosmed.acc_teater, 
        r_mitra_sosmed.acc_mitra, 

        m_user.nama as nama_creator
    ')

            ->join('m_teater', 'm_teater.id_teater = m_audisi.id_teater')
            ->join('m_kategori_audisi', 'm_audisi.id_kategori = m_kategori_audisi.id_kategori')
            ->join('m_audisi_aktor', 'm_audisi_aktor.id_audisi = m_audisi.id_audisi', 'left')
            ->join('m_audisi_staff', 'm_audisi_staff.id_audisi = m_audisi.id_audisi', 'left')

            // **Relasi Show Schedule & Lokasi**
            ->join('m_show_schedule', 'm_show_schedule.id_teater = m_teater.id_teater')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')

            // **Relasi Penampilan dan Harga Seat**
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
            ->join('m_audisi_schedule', 'm_audisi_schedule.id_pricing_audisi = r_audisi_schedule.id_pricing_audisi')

            // **Relasi Website Teater**
            ->join('m_teater_website', 'm_teater_website.id_teater = m_teater.id_teater', 'left')

            // **Relasi Sosial Media Teater**
            ->join('r_teater_sosmed', 'r_teater_sosmed.id_teater = m_teater.id_teater', 'left')
            ->join('m_sosmed_platform', 'm_sosmed_platform.id_platform_sosmed = r_teater_sosmed.id_platform_sosmed', 'left')

            // **Relasi Sosial Media Mitra**
            ->join('r_teater_mitra_sosmed', 'r_teater_mitra_sosmed.id_teater_sosmed = r_teater_sosmed.id_teater_sosmed', 'left')
            ->join('r_mitra_sosmed', 'r_mitra_sosmed.id_mitra_sosmed = r_teater_mitra_sosmed.id_mitra_sosmed', 'left')

            // **Relasi User & Mitra**
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater', 'left')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user', 'left')

            ->join('m_audisi', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi', 'left');

        // Terapkan filter berdasarkan kategori
        switch ($category) {
            case 'tanggal':
                if (!empty($searchInput)) {
                    $query->where('m_show_schedule.tanggal', $searchInput);
                }
                break;
            case 'waktu':
                if (!empty($searchInput)) {
                    $query->where('m_show_schedule.waktu_mulai', $searchInput);
                }
                break;
            case 'kota':
                if (!empty($searchInput)) {
                    $query->where('m_lokasi_teater.kota', $searchInput);
                }
                break;
            case 'harga':
                if (!empty($minHarga) && !empty($maxHarga)) {
                    $query->where('m_audisi_schedule.harga >=', $minHarga)
                        ->where('m_audisi_schedule.harga <=', $maxHarga);
                }
                break;
            case 'gaji':
                if (!empty($minGaji) && !empty($maxGaji)) {
                    $query->where('m_audisi.gaji >=', $minGaji)
                        ->where('m_audisi.gaji <=', $maxGaji);
                }
                break;
        }

        // Eksekusi query
        $results = $query->groupBy('m_show_schedule.id_schedule')->findAll();

        return view('templates/headerUser', ['title' => 'List Audisi Teater']) .
            view('templates/bodyAudisi', ['results' => $results]) .
            view('templates/footerListPenampilan');
    }
}
