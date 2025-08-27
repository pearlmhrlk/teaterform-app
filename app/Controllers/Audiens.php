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
use App\Models\AudisiStaff;
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
    protected $audisiStaffModel;
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
        $this->audisiStaffModel = new AudisiStaff();
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
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

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

        return view('templates/headerUser',  ['title' => 'List Penampilan Teater']) .
            view('templates/bodyListPenampilan', [
                'sedangTayang' => $sedangTayang,
                'akanTayang'   => $akanTayang,
                'searchUrl'    => base_url('user/searchPenampilan'),
                'page' => 'home'
            ]) .
            view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl'     => base_url('user/searchPenampilan')
            ]);
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
        $today = date('Y-m-d');
        $now = date('H:i:s');

        $whereManual = "(m_teater.daftar_mulai IS NOT NULL AND m_teater.daftar_berakhir IS NOT NULL AND CURDATE() BETWEEN m_teater.daftar_mulai AND m_teater.daftar_berakhir)";
        $whereOtomatis = "(m_teater.daftar_mulai IS NULL AND m_teater.daftar_berakhir IS NULL)";
        $whereAll = "($whereManual OR $whereOtomatis)";

        $audisiAktor = $this->db->table('m_teater')
            ->select('
        m_teater.id_teater,
        m_audisi.id_audisi,
        m_teater.judul,
        m_teater.poster,
        m_user.nama AS komunitas_teater,
        m_lokasi_teater.tempat,
        m_lokasi_teater.kota,
        m_show_schedule.tanggal,
        m_show_schedule.waktu_mulai,
        m_show_schedule.waktu_selesai,
        m_audisi_aktor.karakter_audisi
    ')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_audisi', 'm_audisi.id_teater = m_teater.id_teater')
            ->join('m_kategori_audisi', 'm_kategori_audisi.id_kategori = m_audisi.id_kategori')
            ->join('m_audisi_aktor', 'm_audisi_aktor.id_audisi = m_audisi.id_audisi')
            ->join('m_audisi_schedule', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_audisi_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->where('m_teater.tipe_teater', 'audisi')
            ->where('m_show_schedule.tanggal >=', $today)
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $audisiStaff = $this->db->table('m_teater')
            ->select('
                    m_teater.id_teater,
                    m_audisi.id_audisi,
                    m_teater.judul,
                    m_teater.poster,
                    m_user.nama AS komunitas_teater,
                    m_lokasi_teater.tempat,
                    m_lokasi_teater.kota,
                    m_show_schedule.tanggal,
                    m_show_schedule.waktu_mulai,
                    m_show_schedule.waktu_selesai,
                    m_audisi_staff.jenis_staff
                ')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_audisi', 'm_audisi.id_teater = m_teater.id_teater')
            ->join('m_kategori_audisi', 'm_kategori_audisi.id_kategori = m_audisi.id_kategori')
            ->join('m_audisi_staff', 'm_audisi_staff.id_audisi = m_audisi.id_audisi')
            ->join('m_audisi_schedule', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_audisi_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->where('m_teater.tipe_teater', 'audisi')
            ->where('m_show_schedule.tanggal >=', $today)
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $nowFull = strtotime(date('Y-m-d H:i:s'));
        $filteredAudisiAktor = [];

        foreach ($audisiAktor as $audisi) {
            if (!empty($audisi['daftar_mulai']) && !empty($audisi['daftar_berakhir'])) {
                $filteredAudisiAktor[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
            } else {
                $jadwalAkhir = strtotime($audisi['tanggal'] . ' ' . $audisi['waktu_mulai']);

                if ($nowFull <= $jadwalAkhir) {
                    $filteredAudisiAktor[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
                }
            }
        }

        $finalAudisiAktor = [];

        foreach ($filteredAudisiAktor as $audisi) {
            $id = $audisi['id_teater'];

            // Pecah tanggal dan waktu
            $jadwalTimestamp = strtotime($audisi['tanggal'] . ' ' . explode(' - ', $audisi['waktu'])[0]);

            if (!isset($finalAudisiAktor[$id]) || $jadwalTimestamp < strtotime($finalAudisiAktor[$id]['tanggal'] . ' ' . explode(' - ', $finalAudisiAktor[$id]['waktu'])[0])) {
                $finalAudisiAktor[$id] = $audisi;
            }
        }

        $filteredAudisiStaff = [];

        foreach ($audisiStaff as $audisi) {
            if (!empty($audisi['daftar_mulai']) && !empty($audisi['daftar_berakhir'])) {
                $filteredAudisiStaff[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
            } else {
                $jadwalAkhir = strtotime($audisi['tanggal'] . ' ' . $audisi['waktu_mulai']);

                if ($nowFull <= $jadwalAkhir) {
                    $filteredAudisiStaff[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
                }
            }
        }

        $finalAudisiStaff = [];

        foreach ($filteredAudisiStaff as $audisi) {
            $id = $audisi['id_teater'];

            $jadwalTimestamp = strtotime($audisi['tanggal'] . ' ' . explode(' - ', $audisi['waktu'])[0]);

            if (!isset($finalAudisiStaff[$id]) || $jadwalTimestamp < strtotime($finalAudisiStaff[$id]['tanggal'] . ' ' . explode(' - ', $finalAudisiStaff[$id]['waktu'])[0])) {
                $finalAudisiStaff[$id] = $audisi;
            }
        }

        return view('templates/headerUser',  ['title' => 'List Audisi Teater']) .
            view('templates/bodyAudisi', [
                'audisiAktor' => $finalAudisiAktor,
                'audisiStaff' => $finalAudisiStaff,
                'searchUrl'  => base_url('User/searchAudisi'),
                'page' => 'home'
            ]) .
            view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl' => base_url('User/searchAudisi')
            ]);
    }

    private function formatAudisi($audisi)
    {
        return [
            'id_teater' => $audisi['id_teater'],
            'karakter_audisi' => $audisi['karakter_audisi'] ?? null,
            'jenis_staff' => $audisi['jenis_staff'] ?? null,
            'role' => $audisi['role'] ?? null, // gabungan karakter_audisi / jenis_staff
            'role_type' => $audisi['role_type'] ?? null, // "aktor" atau "staff"
            'judul' => $audisi['judul'],
            'komunitas_teater' => $audisi['komunitas_teater'],
            'lokasi_teater' => $audisi['tempat'],
            'tanggal' => $this->formatTanggalIndoLengkap($audisi['tanggal']),
            'waktu' => $this->formatJam($audisi['waktu_mulai']) . ' - ' . $this->formatJam($audisi['waktu_selesai']),
            'poster' => $audisi['poster'],
        ];
    }

    public function getApprovedMitra()
    {
        $data = $this->mitraModel
            ->select('m_mitra.id_mitra, m_user.nama')
            ->join('m_user', 'm_user.id_user = m_mitra.id_user')
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
        $audiens = $this->audiensModel->where('id_user', $userId)->first();
        $sessionAudiensId = $audiens ? $audiens['id_audiens'] : null;

        // 1. Ambil data umum teater
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->where('tipe_teater', 'penampilan')
            ->first();

        // 2. Ambil data penampilan (aktor, durasi, rating)
        $penampilan = $this->penampilanModel
            ->where('id_teater', $teater['id_teater'])
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

        $tiketBookingAll = [];
        if ($sessionAudiensId) {
            $tiketBookingAll = $this->bookingModel
                ->select('
            t_booking.id_booking,
            t_booking.status,
            s.tanggal,
            s.waktu_mulai,
            s.waktu_selesai,
            m_lokasi_teater.tempat,
            m_lokasi_teater.kota
        ')
                ->join('r_show_schedule', 't_booking.id_jadwal = r_show_schedule.id_schedule_show')
                ->join('m_show_schedule AS s', 'r_show_schedule.id_schedule = s.id_schedule')
                ->join('m_lokasi_teater', 's.id_location = m_lokasi_teater.id_location')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'penampilan')
                ->where('s.id_teater', $id_teater)
                ->findAll();
        }

        $groupedSchedule = [];

        foreach ($jadwal as $row) {
            $kota = $row['kota'];
            $tempat = $row['tempat'];
            $tanggal = $row['tanggal'];
            $waktu = $row['waktu'];

            // Kelompokkan harga dan denah seperti sebelumnya
            $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['harga'][] = [
                'nama_kategori' => $row['nama_kategori'],
                'harga' => $row['harga'],
                'tipe_harga' => $row['tipe_harga'],
            ];
            $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['denah'] = $row['denah_seat'];

            // Default status null, nanti diisi kalau audiens punya tiket
            $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['status'] = [];
        }

        $tiketPenampilan = [];
        if ($sessionAudiensId) {
            $tiketPenampilan = $this->bookingModel
                ->select('
        t_booking.id_booking,
        t_booking.created_at AS issue_date,
        t_booking.status,
        user_audiens.nama AS nama_audiens,
        m_teater.judul,
        m_teater.tipe_teater AS jenis_teater,
        user_mitra.nama AS nama_mitra,
        m_lokasi_teater.tempat,
        m_lokasi_teater.kota,
        s.tanggal,
        s.waktu_mulai,
        s.waktu_selesai
    ')
                ->join('m_audiens', 't_booking.id_audiens = m_audiens.id_audiens')
                ->join('m_user AS user_audiens', 'm_audiens.id_user = user_audiens.id_user')
                ->join('r_show_schedule', 't_booking.id_jadwal = r_show_schedule.id_schedule_show')
                ->join('m_show_schedule AS s', 'r_show_schedule.id_schedule = s.id_schedule')
                ->join('m_lokasi_teater', 's.id_location = m_lokasi_teater.id_location')
                ->join('m_teater', 's.id_teater = m_teater.id_teater')
                ->join('r_user_teater', 'm_teater.id_teater = r_user_teater.id_teater')
                ->join('m_user AS user_mitra', 'r_user_teater.id_user = user_mitra.id_user')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'penampilan')
                ->where('t_booking.status', 'success')
                ->where('s.id_teater', $id_teater)   // 🔥 filter tambahan
                ->findAll();
        }

        // Loop tiket audiens untuk mengisi status
        if (!empty($tiketBookingAll)) {
            foreach ($tiketBookingAll as $tiket) {
                $kota = $tiket['kota'];
                $tempat = $tiket['tempat'];
                $tanggal = $tiket['tanggal'];
                $waktu = date('H:i', strtotime($tiket['waktu_mulai'])) . ' - ' . date('H:i', strtotime($tiket['waktu_selesai']));

                if (isset($groupedSchedule[$kota][$tempat][$tanggal][$waktu])) {
                    $groupedSchedule[$kota][$tempat][$tanggal][$waktu]['status'][] = $tiket['status'];
                }
            }
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
                'tiketPenampilan' => $tiketPenampilan
            ]) .
            view('templates/footerListPenampilan');
    }

    public function showBookingPopup($tipe, $id)
    {
        if ($tipe === 'penampilan') {
            // Ambil semua jadwal untuk teater ini
            $jadwal = $this->db->table('r_show_schedule rs')
                ->select('
                rs.id_schedule_show AS id_jadwal,
                rs.id_schedule,
                ms.tanggal,
                ms.waktu_mulai,
                ms.waktu_selesai
            ')
                ->join('m_show_schedule ms', 'rs.id_schedule = ms.id_schedule')
                ->where('ms.id_teater', $id)
                ->get()
                ->getResultArray();

            foreach ($jadwal as &$j) {
                $j['is_free'] = "1"; // default gratis
                $j['qrcode_bayar'] = '';

                // Ambil semua id_pricing yang terkait dengan jadwal ini
                $pricings = $this->db->table('r_show_schedule')
                    ->select('id_pricing')
                    ->where('id_schedule', $j['id_schedule'])
                    ->get()
                    ->getResultArray();

                foreach ($pricings as $pricing) {
                    // Ambil penampilan dari id_pricing
                    $penampilan = $this->db->table('m_seat_pricing sp')
                        ->select('p.id_penampilan, p.qrcode_bayar, sp.tipe_harga')
                        ->join('m_penampilan p', 'sp.id_penampilan = p.id_penampilan')
                        ->where('sp.id_pricing', $pricing['id_pricing'])
                        ->get()
                        ->getRowArray();

                    if ($penampilan) {
                        // Jika ada tipe_harga Bayar → jadwal bayar
                        if ($penampilan['tipe_harga'] === 'Bayar') {
                            $j['is_free'] = "0";
                            $j['qrcode_bayar'] = $penampilan['qrcode_bayar'] ?? '';
                            break; // cukup 1 tipe bayar → jadwal bayar
                        }
                    }
                }
            }

            return $this->response->setJSON([
                'jadwal' => $jadwal
            ]);
        } elseif ($tipe === 'audisi') {
            // Audisi selalu gratis
            $jadwal = $this->db->table('r_audisi_schedule')
                ->select('r_audisi_schedule.id_audisi_schedule AS id_jadwal, ms.tanggal, ms.waktu_mulai, ms.waktu_selesai')
                ->join('m_show_schedule ms', 'r_audisi_schedule.id_schedule = ms.id_schedule')
                ->join('m_teater t', 'ms.id_teater = t.id_teater')
                ->where('t.id_teater', $id)
                ->groupBy('r_audisi_schedule.id_schedule')
                ->get()
                ->getResultArray();

            foreach ($jadwal as &$j) {
                $j['is_free'] = "1";
            }

            return $this->response->setJSON([
                'jadwal' => $jadwal
            ]);
        }
    }

    public function simpanBooking()
    {
        $data = $this->request->getJSON();
        $idJadwal = $data->id_jadwal;
        $tipeJadwal = $data->tipe_jadwal;

        // Ambil user dari session
        $idUser = session()->get('id_user');

        // Cari id_audiens
        $audiens = $this->audiensModel->where('id_user', $idUser)->first();
        if (!$audiens) {
            return $this->response->setJSON(['success' => false, 'message' => 'Audiens tidak ditemukan.']);
        }
        $idAudiens = $audiens['id_audiens'];

        // Tentukan apakah gratis
        if ($tipeJadwal === 'penampilan') {
            $jadwal = $this->showSeatPricingModel->getScheduleWithPrice($idJadwal);

            if (!$jadwal) {
                $isFree = 1; // default gratis kalau tidak ada harga
            } else {
                $isFree = ($jadwal['harga'] == 0 || $jadwal['harga'] === null) ? 1 : 0; // **gunakan $isFree**
            }
        } else { // audisi selalu gratis
            $isFree = 1;
        }

        // Cek booking lama
        $bookingLama = $this->bookingModel
            ->where('id_audiens', $idAudiens)
            ->where('id_jadwal', $idJadwal)
            ->where('tipe_jadwal', $tipeJadwal)
            ->first();

        if ($bookingLama) {
            if ($bookingLama['isValid'] == 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking sudah valid, tidak bisa ulang.'
                ]);
            } elseif ($bookingLama['isValid'] == 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Booking sedang diverifikasi.'
                ]);
            } elseif ($bookingLama['isValid'] == 2) {
                // Ditolak, hapus dulu
                $this->bookingModel->delete($bookingLama['id_booking']);
            }
        }

        // Tentukan status & isValid berdasarkan gratis / bayar
        if ($isFree) {
            $status = 'success';
            $isValid = 1;
        } else {
            $status = 'pending';
            $isValid = 0;
        }

        $this->bookingModel->insert([
            'id_audiens' => $idAudiens,
            'id_jadwal' => $idJadwal,
            'tipe_jadwal' => $tipeJadwal,
            'is_free' => $isFree,
            'status' => $status,
            'isValid' => $isValid
        ]);

        $idBookingBaru = $this->bookingModel->getInsertID();

        return $this->response->setJSON([
            'success' => true,
            'is_free' => $isFree,
            'id_booking' => $idBookingBaru
        ]);
    }

    public function konfirmasiUploadBukti($id_booking)
    {
        $file = $this->request->getFile('bukti_pembayaran');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/bukti/', $newName);

            $this->bookingModel->update($id_booking, [
                'bukti_pembayaran' => $newName,
                'isValid' => 0 // bukti baru diupload, perlu diverifikasi
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Upload bukti pembayaran gagal.'
        ]);
    }

    public function hapusBookingPending($id_booking)
    {
        $idUser = session()->get('id_user');

        $audiens = $this->audiensModel->where('id_user', $idUser)->first();
        if (!$audiens) {
            return $this->response->setJSON(['success' => false, 'message' => 'Audiens tidak ditemukan.']);
        }

        $booking = $this->bookingModel
            ->where('id_audiens', $audiens['id_audiens'])
            ->where('id_booking', $id_booking)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data booking tidak ditemukan atau bukan status pending.']);
        }

        $this->bookingModel->delete($booking['id_booking']);

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

        $booking = $this->bookingModel->where([
            'id_jadwal' => $idJadwal,
            'id_audiens' => $idAudiens,
            'status' => 'pending',
            'isValid' => 0
        ])->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking yang valid tidak ditemukan.']);
        }

        // Update hanya 1 booking
        $this->bookingModel->update($booking['id_booking'], [
            'status' => 'success',
            'isValid' => 1
        ]);

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

        return view('templates/headerAudiens', [
            'title' => 'List Penampilan Teater',
            'user'  => $user
        ]) .
            view('templates/bodyListPenampilan', [
                'sedangTayang' => $sedangTayang,
                'akanTayang'   => $akanTayang,
                'searchUrl'    => base_url('Audiens/searchPenampilan'),
                'page' => 'home'
            ]) .
            view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl'     => base_url('Audiens/searchPenampilan')
            ]);
    }

    public function AudisiAfterLogin()
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $today = date('Y-m-d');
        $now = date('H:i:s');

        $whereManual = "(m_teater.daftar_mulai IS NOT NULL AND m_teater.daftar_berakhir IS NOT NULL AND CURDATE() BETWEEN m_teater.daftar_mulai AND m_teater.daftar_berakhir)";
        $whereOtomatis = "(m_teater.daftar_mulai IS NULL AND m_teater.daftar_berakhir IS NULL)";
        $whereAll = "($whereManual OR $whereOtomatis)";

        $audisiAktor = $this->db->table('m_teater')
            ->select('
        m_teater.id_teater,
        m_audisi.id_audisi,
        m_teater.judul,
        m_teater.poster,
        m_user.nama AS komunitas_teater,
        m_lokasi_teater.tempat,
        m_lokasi_teater.kota,
        m_show_schedule.tanggal,
        m_show_schedule.waktu_mulai,
        m_show_schedule.waktu_selesai,
        m_audisi_aktor.karakter_audisi
    ')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_audisi', 'm_audisi.id_teater = m_teater.id_teater')
            ->join('m_kategori_audisi', 'm_kategori_audisi.id_kategori = m_audisi.id_kategori')
            ->join('m_audisi_aktor', 'm_audisi_aktor.id_audisi = m_audisi.id_audisi')
            ->join('m_audisi_schedule', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_audisi_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->where('m_teater.tipe_teater', 'audisi')
            ->where('m_show_schedule.tanggal >=', $today)
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $audisiStaff = $this->db->table('m_teater')
            ->select('
                    m_teater.id_teater,
                    m_audisi.id_audisi,
                    m_teater.judul,
                    m_teater.poster,
                    m_user.nama AS komunitas_teater,
                    m_lokasi_teater.tempat,
                    m_lokasi_teater.kota,
                    m_show_schedule.tanggal,
                    m_show_schedule.waktu_mulai,
                    m_show_schedule.waktu_selesai,
                    m_audisi_staff.jenis_staff
                ')
            ->join('r_user_teater', 'r_user_teater.id_teater = m_teater.id_teater')
            ->join('m_user', 'm_user.id_user = r_user_teater.id_user')
            ->join('m_audisi', 'm_audisi.id_teater = m_teater.id_teater')
            ->join('m_kategori_audisi', 'm_kategori_audisi.id_kategori = m_audisi.id_kategori')
            ->join('m_audisi_staff', 'm_audisi_staff.id_audisi = m_audisi.id_audisi')
            ->join('m_audisi_schedule', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_audisi_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_lokasi_teater.id_location = m_show_schedule.id_location')
            ->where('m_teater.tipe_teater', 'audisi')
            ->where('m_show_schedule.tanggal >=', $today)
            ->orderBy('m_show_schedule.tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $nowFull = strtotime(date('Y-m-d H:i:s'));
        $filteredAudisiAktor = [];

        foreach ($audisiAktor as $audisi) {
            if (!empty($audisi['daftar_mulai']) && !empty($audisi['daftar_berakhir'])) {
                $filteredAudisiAktor[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
            } else {
                $jadwalAkhir = strtotime($audisi['tanggal'] . ' ' . $audisi['waktu_mulai']);

                if ($nowFull <= $jadwalAkhir) {
                    $filteredAudisiAktor[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
                }
            }
        }

        $finalAudisiAktor = [];

        foreach ($filteredAudisiAktor as $audisi) {
            $id = $audisi['id_teater'];

            // Pecah tanggal dan waktu
            $jadwalTimestamp = strtotime($audisi['tanggal'] . ' ' . explode(' - ', $audisi['waktu'])[0]);

            if (!isset($finalAudisiAktor[$id]) || $jadwalTimestamp < strtotime($finalAudisiAktor[$id]['tanggal'] . ' ' . explode(' - ', $finalAudisiAktor[$id]['waktu'])[0])) {
                $finalAudisiAktor[$id] = $audisi;
            }
        }

        $filteredAudisiStaff = [];

        foreach ($audisiStaff as $audisi) {
            if (!empty($audisi['daftar_mulai']) && !empty($audisi['daftar_berakhir'])) {
                $filteredAudisiStaff[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
            } else {
                $jadwalAkhir = strtotime($audisi['tanggal'] . ' ' . $audisi['waktu_mulai']);

                if ($nowFull <= $jadwalAkhir) {
                    $filteredAudisiStaff[] = $this->formatAudisi($audisi); // ✅ format sebelum simpan
                }
            }
        }

        $finalAudisiStaff = [];

        foreach ($filteredAudisiStaff as $audisi) {
            $id = $audisi['id_teater'];

            $jadwalTimestamp = strtotime($audisi['tanggal'] . ' ' . explode(' - ', $audisi['waktu'])[0]);

            if (!isset($finalAudisiStaff[$id]) || $jadwalTimestamp < strtotime($finalAudisiStaff[$id]['tanggal'] . ' ' . explode(' - ', $finalAudisiStaff[$id]['waktu'])[0])) {
                $finalAudisiStaff[$id] = $audisi;
            }
        }

        return view('templates/headerAudiens', [
            'title' => 'List Audisi Teater',
            'user'  => $user
        ]) .
            view('templates/bodyAudisi', [
                'audisiAktor' => $finalAudisiAktor,
                'audisiStaff' => $finalAudisiStaff,
                'searchUrl'    => base_url('Audiens/searchAudisi'),
                'page' => 'home'
            ]) .
            view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl'     => base_url('Audiens/searchAudisi')
            ]);
    }

    public function DetailAudisiAktor($id_teater)
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);
        $audiens = $this->audiensModel->where('id_user', $userId)->first();
        $sessionAudiensId = $audiens ? $audiens['id_audiens'] : null;

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

        // 4. Ambil sosial media, website, nama komunitas, mitra
        $sosmed = $this->teaterSosmedModel
            ->select('m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
            ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
            ->where('r_teater_sosmed.id_teater', $id_teater)
            ->findAll();

        $website = $this->teaterWebModel->where('id_teater', $id_teater)->first();
        $userTeater = $this->userTeaterModel->where('id_teater', $id_teater)->first();
        $namaKomunitas = $this->userModel->where('id_user', $userTeater['id_user'])->first();
        $mitra = $this->mitraModel->where('id_user', $namaKomunitas['id_user'])->first();

        // 5. Ambil jadwal audisi (gabungan r_audisi_schedule → m_show_schedule)
        $jadwalAudisi = $this->audisiScheduleModel
            ->select('
            m_show_schedule.id_schedule,
            m_show_schedule.tanggal,
            CONCAT(DATE_FORMAT(m_show_schedule.waktu_mulai, "%H:%i"), " - ", DATE_FORMAT(m_show_schedule.waktu_selesai, "%H:%i")) AS waktu,
            m_lokasi_teater.kota,
            m_lokasi_teater.tempat
        ')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
            ->where('m_audisi_schedule.id_audisi', $audisi['id_audisi'])
            ->orderBy('m_lokasi_teater.kota')
            ->findAll();

        // 6. Ambil semua booking audisi audiens (untuk status di jadwal)
        $tiketBookingAll = [];
        if ($sessionAudiensId) {
            $tiketBookingAll = $this->bookingModel
                ->select('t_booking.id_jadwal, t_booking.status, r_audisi_schedule.id_schedule AS schedule_id')
                ->join('r_audisi_schedule', 't_booking.id_jadwal = r_audisi_schedule.id_audisi_schedule')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'audisi')
                ->findAll();
        }

        // 7. Ambil tiket digital (hanya success)
        $tiketAudisi = [];
        if ($sessionAudiensId) {
            $tiketAudisi = $this->bookingModel
                ->select('
                t_booking.id_booking,
                t_booking.created_at AS issue_date,
                user_audiens.nama AS nama_audiens,
                m_teater.judul,
                m_teater.tipe_teater AS jenis_teater,
                user_mitra.nama AS nama_mitra,
                m_lokasi_teater.tempat,
                m_lokasi_teater.kota,
                s.tanggal,
                s.waktu_mulai,
                s.waktu_selesai
            ')
                ->join('m_audiens', 't_booking.id_audiens = m_audiens.id_audiens')
                ->join('m_user AS user_audiens', 'm_audiens.id_user = user_audiens.id_user')
                ->join('r_audisi_schedule', 't_booking.id_jadwal = r_audisi_schedule.id_audisi_schedule')
                ->join('m_show_schedule AS s', 'r_audisi_schedule.id_schedule = s.id_schedule')
                ->join('m_lokasi_teater', 's.id_location = m_lokasi_teater.id_location')
                ->join('m_teater', 's.id_teater = m_teater.id_teater')
                ->join('r_user_teater', 'm_teater.id_teater = r_user_teater.id_teater')
                ->join('m_user AS user_mitra', 'r_user_teater.id_user = user_mitra.id_user')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'audisi')
                ->where('t_booking.status', 'success')
                ->where('s.id_teater', $id_teater)
                ->findAll();
        }

        // 8. Bangun groupedSchedule dengan status booking
        $groupedSchedule = [];
        foreach ($jadwalAudisi as $row) {
            $statusBooking = '-';
            foreach ($tiketBookingAll as $tiket) {
                if ($tiket['schedule_id'] == $row['id_schedule']) {
                    $statusBooking = $tiket['status'];
                    break;
                }
            }

            $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = [
                'waktu' => $row['waktu'],
                'status' => $statusBooking
            ];
        }

        return view('templates/headerAudiens',  ['title' => 'Detail Audisi Aktor Teater', 'user'  => $this->user]) .
            view('templates/bodyDetailAudisiAktor', [
                'teater'         => $teater,
                'audisi'         => $audisi,
                'aktorAudisi'    => $aktorAudisi,
                'sosmed'         => $sosmed,
                'website'        => $website,
                'namaKomunitas'  => $namaKomunitas,
                'mitra'          => $mitra,
                'groupedSchedule' => $groupedSchedule,
                'tiketAudisi'    => $tiketAudisi
            ]) .
            view('templates/footerListPenampilan');
    }

    public function detailAudisiStaff($id_teater)
    {

        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);
        $audiens = $this->audiensModel->where('id_user', $userId)->first();
        $sessionAudiensId = $audiens ? $audiens['id_audiens'] : null;

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
        $staffAudisi = $this->audisiStaffModel
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

        // 5. Ambil jadwal audisi (gabungan r_audisi_schedule → m_show_schedule)
        $jadwalAudisi = $this->audisiScheduleModel
            ->select('
            m_show_schedule.id_schedule,
            m_show_schedule.tanggal,
            CONCAT(DATE_FORMAT(m_show_schedule.waktu_mulai, "%H:%i"), " - ", DATE_FORMAT(m_show_schedule.waktu_selesai, "%H:%i")) AS waktu,
            m_lokasi_teater.kota,
            m_lokasi_teater.tempat
        ')
            ->join('r_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
            ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
            ->where('m_audisi_schedule.id_audisi', $audisi['id_audisi'])
            ->orderBy('m_lokasi_teater.kota')
            ->findAll();

        // 6. Ambil semua booking audisi audiens (untuk status di jadwal)
        $tiketBookingAll = [];
        if ($sessionAudiensId) {
            $tiketBookingAll = $this->bookingModel
                ->select('t_booking.id_jadwal, t_booking.status, r_audisi_schedule.id_schedule AS schedule_id')
                ->join('r_audisi_schedule', 't_booking.id_jadwal = r_audisi_schedule.id_audisi_schedule')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'audisi')
                ->findAll();
        }

        // 7. Ambil tiket digital (hanya success)
        $tiketAudisi = [];
        if ($sessionAudiensId) {
            $tiketAudisi = $this->bookingModel
                ->select('
                t_booking.id_booking,
                t_booking.created_at AS issue_date,
                user_audiens.nama AS nama_audiens,
                m_teater.judul,
                m_teater.tipe_teater AS jenis_teater,
                user_mitra.nama AS nama_mitra,
                m_lokasi_teater.tempat,
                m_lokasi_teater.kota,
                s.tanggal,
                s.waktu_mulai,
                s.waktu_selesai
            ')
                ->join('m_audiens', 't_booking.id_audiens = m_audiens.id_audiens')
                ->join('m_user AS user_audiens', 'm_audiens.id_user = user_audiens.id_user')
                ->join('r_audisi_schedule', 't_booking.id_jadwal = r_audisi_schedule.id_audisi_schedule')
                ->join('m_show_schedule AS s', 'r_audisi_schedule.id_schedule = s.id_schedule')
                ->join('m_lokasi_teater', 's.id_location = m_lokasi_teater.id_location')
                ->join('m_teater', 's.id_teater = m_teater.id_teater')
                ->join('r_user_teater', 'm_teater.id_teater = r_user_teater.id_teater')
                ->join('m_user AS user_mitra', 'r_user_teater.id_user = user_mitra.id_user')
                ->where('t_booking.id_audiens', $sessionAudiensId)
                ->where('t_booking.tipe_jadwal', 'audisi')
                ->where('t_booking.status', 'success')
                ->where('s.id_teater', $id_teater)
                ->findAll();
        }

        // 8. Bangun groupedSchedule dengan status booking
        $groupedSchedule = [];
        foreach ($jadwalAudisi as $row) {
            $statusBooking = '-';
            foreach ($tiketBookingAll as $tiket) {
                if ($tiket['schedule_id'] == $row['id_schedule']) {
                    $statusBooking = $tiket['status'];
                    break;
                }
            }

            $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = [
                'waktu' => $row['waktu'],
                'status' => $statusBooking
            ];
        }

        return view('templates/headerAudiens',  ['title' => 'Detail Audisi Staff Teater', 'user'  => $this->user]) .
            view('templates/bodyDetailAudisiStaff', [
                'teater' => $teater,
                'audisi' => $audisi,
                'staffAudisi' => $staffAudisi,
                'sosmed' => $sosmed,
                'website' => $website,
                'namaKomunitas' => $namaKomunitas,
                'mitra' => $mitra,
                'groupedSchedule' => $groupedSchedule,
                'tiketAudisi' => $tiketAudisi
            ]) .
            view('templates/footerListPenampilan');
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
        $mitraList = $this->mitraModel->getMitraWithUser();

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
        $mitraList = $this->mitraModel->getMitraWithUser();

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

    public function detail($id)
    {
        // Ambil detail mitra berdasarkan ID
        $mitra = $this->mitraModel->getMitraDetail($id);

        // Ambil data sosial media mitra
        $sosial_media = $this->mitraModel->getMitraSosmed($id);

        // Kirim data ke view
        return view('templates/headerUser', ['title' => 'Detail Mitra Teater']) .
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
        // Ambil filter dari request (langsung sesuai JS)
        $searchTanggal = $this->request->getGet('searchTanggal');
        $searchWaktu   = $this->request->getGet('searchWaktu');
        $searchKota    = $this->request->getGet('searchKota');
        $searchRating  = $this->request->getGet('searchRating');
        $durasiMin     = $this->request->getGet('minDurasi');
        $durasiMax     = $this->request->getGet('maxDurasi');
        $hargaMin      = $this->request->getGet('minHarga');
        $hargaMax      = $this->request->getGet('maxHarga');

        // Query utama
        $query = $this->penampilanModel
            ->select('
            m_penampilan.id_penampilan,
            t.id_teater,
            t.judul,
            t.poster,
            m_user.nama AS komunitas_teater,
            ml.tempat,
            ml.kota,
            m_penampilan.rating_umur,
            m_penampilan.durasi,
            s.tanggal,
            s.waktu_mulai,
            s.waktu_selesai
        ', false)
            ->join('m_teater t', 't.id_teater = m_penampilan.id_teater')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user', 'm_user.id_user = rut.id_user', 'left')
            ->join('m_show_schedule s', 's.id_teater = t.id_teater', 'left')
            ->join('m_lokasi_teater ml', 'ml.id_location = s.id_location', 'left')
            ->join('m_seat_pricing mp1', 'mp1.id_penampilan = m_penampilan.id_penampilan', 'left')
            ->where('t.tipe_teater', 'penampilan');

        // Filter sesuai param dari JS
        if (!empty($searchTanggal)) {
            $query->where('s.tanggal', $searchTanggal);
        }

        if (!empty($searchWaktu)) {
            $query->where('s.waktu_mulai', $searchWaktu);
        }

        if (!empty($searchKota)) {
            $query->where('ml.kota', $searchKota);
        }

        if (!empty($searchRating)) {
            $query->where('m_penampilan.rating_umur', $searchRating);
        }

        if (!empty($durasiMin) && !empty($durasiMax)) {
            $query->where('m_penampilan.durasi >=', $durasiMin)
                ->where('m_penampilan.durasi <=', $durasiMax);
        }

        if (!empty($hargaMin) && !empty($hargaMax)) {
            $query->where('mp1.harga >=', $hargaMin)
                ->where('mp1.harga <=', $hargaMax);
        }

        // Jalankan query
        $penampilanRaw = $query->groupBy('m_penampilan.id_penampilan')->findAll();

        // Format hasil dengan fungsi bantu
        $penampilan = array_map(function ($row) {
            return $this->formatPenampilan($row);
        }, $penampilanRaw);

        // Kirim ke view
        return view('templates/headerUser', ['title' => 'Hasil Pencarian']) .
            view('templates/bodyListPenampilan', [
                'penampilan' => $penampilan,
                'searchUrl'  => base_url('user/searchPenampilan'),
                'page' => 'search'
            ]) .
            view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl' => base_url('user/searchPenampilan')
            ]);
    }

    public function searchPenampilanAfterLogin()
    {

        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil filter dari request (langsung sesuai JS)
        $searchTanggal = $this->request->getGet('searchTanggal');
        $searchWaktu   = $this->request->getGet('searchWaktu');
        $searchKota    = $this->request->getGet('searchKota');
        $searchRating  = $this->request->getGet('searchRating');
        $durasiMin     = $this->request->getGet('minDurasi');
        $durasiMax     = $this->request->getGet('maxDurasi');
        $hargaMin      = $this->request->getGet('minHarga');
        $hargaMax      = $this->request->getGet('maxHarga');

        // Query utama
        $query = $this->penampilanModel
            ->select('
            m_penampilan.id_penampilan,
            t.id_teater,
            t.judul,
            t.poster,
            m_user.nama AS komunitas_teater,
            ml.tempat,
            ml.kota,
            m_penampilan.rating_umur,
            m_penampilan.durasi,
            s.tanggal,
            s.waktu_mulai,
            s.waktu_selesai
        ', false)
            ->join('m_teater t', 't.id_teater = m_penampilan.id_teater')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user', 'm_user.id_user = rut.id_user', 'left')
            ->join('m_show_schedule s', 's.id_teater = t.id_teater', 'left')
            ->join('m_lokasi_teater ml', 'ml.id_location = s.id_location', 'left')
            ->join('m_seat_pricing mp1', 'mp1.id_penampilan = m_penampilan.id_penampilan', 'left')
            ->where('t.tipe_teater', 'penampilan');

        // Filter sesuai param dari JS
        if (!empty($searchTanggal)) {
            $query->where('s.tanggal', $searchTanggal);
        }

        if (!empty($searchWaktu)) {
            $query->where('s.waktu_mulai', $searchWaktu);
        }

        if (!empty($searchKota)) {
            $query->where('ml.kota', $searchKota);
        }

        if (!empty($searchRating)) {
            $query->where('m_penampilan.rating_umur', $searchRating);
        }

        if (!empty($durasiMin) && !empty($durasiMax)) {
            $query->where('m_penampilan.durasi >=', $durasiMin)
                ->where('m_penampilan.durasi <=', $durasiMax);
        }

        if (!empty($hargaMin) && !empty($hargaMax)) {
            $query->where('mp1.harga >=', $hargaMin)
                ->where('mp1.harga <=', $hargaMax);
        }

        // Jalankan query
        $penampilan = $query->groupBy('m_penampilan.id_penampilan')->findAll();

        // Kirim ke view
        return view('templates/headerAudiens',  ['title' => 'List Penampilan Teater', 'user' => $user]) .
            view('templates/bodyListPenampilan', ['penampilan' => $penampilan, 'searchUrl'  => base_url('Audiens/searchPenampilan'), 'page' => 'search']) .
            view('templates/footerListPenampilan', ['needsDropdown' => true, 'searchUrl' => base_url('Audiens/searchPenampilan')]);
    }

    public function searchAudisi()
    {
        $today = date('Y-m-d');

        $searchTanggal = $this->request->getGet('searchTanggal');
        $searchWaktu   = $this->request->getGet('searchWaktu');
        $searchKota    = $this->request->getGet('searchKota');
        $minGaji       = $this->request->getGet('minGaji');
        $maxGaji       = $this->request->getGet('maxGaji');

        // ==== BASE BUILDER AKTOR ====
        $builderAktor = $this->db->table('m_teater t')
            ->select("t.id_teater, a.id_audisi, a.id_kategori, 
                  ak.karakter_audisi AS role, 'aktor' AS role_type, 
                  a.gaji, l.kota, l.tempat, 
                  ss.tanggal, ss.waktu_mulai, ss.waktu_selesai, 
                  t.judul, u.nama AS komunitas_teater, t.poster")
            ->join('m_audisi a', 'a.id_teater = t.id_teater')
            ->join('m_audisi_aktor ak', 'ak.id_audisi = a.id_audisi')
            ->join('m_audisi_schedule asch', 'asch.id_audisi = a.id_audisi', 'left')
            ->join('r_audisi_schedule ras', 'ras.id_pricing_audisi = asch.id_pricing_audisi', 'left')
            ->join('m_show_schedule ss', 'ss.id_schedule = ras.id_schedule', 'left')
            ->join('m_lokasi_teater l', 'l.id_location = ss.id_location', 'left')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user u', 'u.id_user = rut.id_user', 'left')
            ->where('t.tipe_teater', 'audisi');

        // ==== BASE BUILDER STAFF ====
        $builderStaff = $this->db->table('m_teater t')
            ->select("t.id_teater, a.id_audisi, a.id_kategori, 
                  sff.jenis_staff AS role, 'staff' AS role_type, 
                  a.gaji, l.kota, l.tempat, 
                  ss.tanggal, ss.waktu_mulai, ss.waktu_selesai, 
                  t.judul, u.nama AS komunitas_teater, t.poster")
            ->join('m_audisi a', 'a.id_teater = t.id_teater')
            ->join('m_audisi_staff sff', 'sff.id_audisi = a.id_audisi')
            ->join('m_audisi_schedule asch', 'asch.id_audisi = a.id_audisi', 'left')
            ->join('r_audisi_schedule ras', 'ras.id_pricing_audisi = asch.id_pricing_audisi', 'left')
            ->join('m_show_schedule ss', 'ss.id_schedule = ras.id_schedule', 'left')
            ->join('m_lokasi_teater l', 'l.id_location = ss.id_location', 'left')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user u', 'u.id_user = rut.id_user', 'left')
            ->where('t.tipe_teater', 'audisi');

        // ==== FILTERS ====
        if ($searchTanggal) {
            $builderAktor->where('ss.tanggal', $searchTanggal);
            $builderStaff->where('ss.tanggal', $searchTanggal);
        }
        if ($searchWaktu) {
            $builderAktor->where('ss.waktu_mulai', $searchWaktu);
            $builderStaff->where('ss.waktu_mulai', $searchWaktu);
        }
        if ($searchKota) {
            $builderAktor->like('l.kota', $searchKota);
            $builderStaff->like('l.kota', $searchKota);
        }
        if ($minGaji && $maxGaji) {
            $builderAktor->groupStart()
                ->where('a.gaji >=', $minGaji)
                ->where('a.gaji <=', $maxGaji)
                ->orWhere('a.gaji IS NULL')
                ->groupEnd();
            $builderStaff->groupStart()
                ->where('a.gaji >=', $minGaji)
                ->where('a.gaji <=', $maxGaji)
                ->orWhere('a.gaji IS NULL')
                ->groupEnd();
        }

        // ==== UNION ====
        $query = $builderAktor->unionAll($builderStaff)->get();
        $results = $query->getResultArray();

        // ==== FORMAT ====
        $formattedResults = [];
        foreach ($results as $a) {
            $formattedResults[$a['id_audisi']] = $this->formatAudisi($a);
        }
        $formattedResults = array_values($formattedResults);

        return view('templates/headerUser', ['title' => 'List Audisi Teater'])
            . view('templates/bodyAudisi', [
                'results' => $formattedResults,
                'searchUrl' => base_url('User/searchAudisi'),
                'page' => 'search'
            ])
            . view('templates/footerListPenampilan', [
                'needsDropdown' => true,
                'searchUrl' => base_url('User/searchAudisi')
            ]);
    }

    public function searchAudisiAfterLogin()
    {
        $today = date('Y-m-d');

        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $searchTanggal = $this->request->getGet('searchTanggal');
        $searchWaktu   = $this->request->getGet('searchWaktu');
        $searchKota    = $this->request->getGet('searchKota');
        $minGaji       = $this->request->getGet('minGaji');
        $maxGaji       = $this->request->getGet('maxGaji');

        // ==== BASE BUILDER AKTOR ====
        $builderAktor = $this->db->table('m_teater t')
            ->select("t.id_teater, a.id_audisi, a.id_kategori, 
                  ak.karakter_audisi AS role, 'aktor' AS role_type, 
                  a.gaji, l.kota, l.tempat, 
                  ss.tanggal, ss.waktu_mulai, ss.waktu_selesai, 
                  t.judul, u.nama AS komunitas_teater, t.poster")
            ->join('m_audisi a', 'a.id_teater = t.id_teater')
            ->join('m_audisi_aktor ak', 'ak.id_audisi = a.id_audisi')
            ->join('m_audisi_schedule asch', 'asch.id_audisi = a.id_audisi', 'left')
            ->join('r_audisi_schedule ras', 'ras.id_pricing_audisi = asch.id_pricing_audisi', 'left')
            ->join('m_show_schedule ss', 'ss.id_schedule = ras.id_schedule', 'left')
            ->join('m_lokasi_teater l', 'l.id_location = ss.id_location', 'left')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user u', 'u.id_user = rut.id_user', 'left')
            ->where('t.tipe_teater', 'audisi');

        // ==== BASE BUILDER STAFF ====
        $builderStaff = $this->db->table('m_teater t')
            ->select("t.id_teater, a.id_audisi, a.id_kategori, 
                  sff.jenis_staff AS role, 'staff' AS role_type, 
                  a.gaji, l.kota, l.tempat, 
                  ss.tanggal, ss.waktu_mulai, ss.waktu_selesai, 
                  t.judul, u.nama AS komunitas_teater, t.poster")
            ->join('m_audisi a', 'a.id_teater = t.id_teater')
            ->join('m_audisi_staff sff', 'sff.id_audisi = a.id_audisi')
            ->join('m_audisi_schedule asch', 'asch.id_audisi = a.id_audisi', 'left')
            ->join('r_audisi_schedule ras', 'ras.id_pricing_audisi = asch.id_pricing_audisi', 'left')
            ->join('m_show_schedule ss', 'ss.id_schedule = ras.id_schedule', 'left')
            ->join('m_lokasi_teater l', 'l.id_location = ss.id_location', 'left')
            ->join('r_user_teater rut', 'rut.id_teater = t.id_teater', 'left')
            ->join('m_user u', 'u.id_user = rut.id_user', 'left')
            ->where('t.tipe_teater', 'audisi');

        // ==== FILTERS ====
        if ($searchTanggal) {
            $builderAktor->where('ss.tanggal', $searchTanggal);
            $builderStaff->where('ss.tanggal', $searchTanggal);
        }
        if ($searchWaktu) {
            $builderAktor->where('ss.waktu_mulai', $searchWaktu);
            $builderStaff->where('ss.waktu_mulai', $searchWaktu);
        }
        if ($searchKota) {
            $builderAktor->like('l.kota', $searchKota);
            $builderStaff->like('l.kota', $searchKota);
        }
        if ($minGaji && $maxGaji) {
            $builderAktor->groupStart()
                ->where('a.gaji >=', $minGaji)
                ->where('a.gaji <=', $maxGaji)
                ->orWhere('a.gaji IS NULL')
                ->groupEnd();
            $builderStaff->groupStart()
                ->where('a.gaji >=', $minGaji)
                ->where('a.gaji <=', $maxGaji)
                ->orWhere('a.gaji IS NULL')
                ->groupEnd();
        }

        // ==== UNION ====
        $query = $builderAktor->unionAll($builderStaff)->get();
        $results = $query->getResultArray();

        // ==== FORMAT ====
        $formattedResults = [];
        foreach ($results as $a) {
            $formattedResults[$a['id_audisi']] = $this->formatAudisi($a);
        }
        $formattedResults = array_values($formattedResults);

        return view('templates/headerAudiens',  ['title' => 'List Audisi Teater', 'user' => $user]) .
            view('templates/bodyAudisi', ['results' => $formattedResults, 'searchUrl'  => base_url('Audiens/searchAudisi'), 'page' => 'search']) .
            view('templates/footerListPenampilan', ['needsDropdown' => true, 'searchUrl' => base_url('Audiens/searchAudisi')]);
    }
}
