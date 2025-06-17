<?php

namespace App\Controllers;

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
use App\Models\UserTeater;
use App\Models\DenahSeatModel;
use App\Models\Booking;

use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Database\RawSql;

class MitraTeater extends BaseController
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
    protected $userTeaterModel;
    protected $denahModel;
    protected $bookingModel;

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
        $this->userTeaterModel = new UserTeater();
        $this->denahModel = new DenahSeatModel();
        $this->bookingModel = new Booking();

        $this->db = \Config\Database::connect();

        helper('session'); // Pastikan helper session dimuat
        session(); // Pastikan session berjalan
    }

    // Fungsi untuk registrasi akun Mitra Teater
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

        if ($this->request->getMethod() === 'POST') {
            // Ambil semua data dari form
            $data = $this->request->getPost();

            $rules = [
                'username'      => 'required|min_length[3]|max_length[15]',
                'nama'          => 'required',
                'email'         => 'required|valid_email',
                'password'      => 'required|min_length[6]',
                'alamat'        => 'required',
                'berdiri_sejak' => 'required|valid_date',
                'deskripsi'     => 'required',
                'id_role'       => 'required|in_list[2]', // Role Mitra Teater (id_role = 2)
                'hidden_accounts' => 'required',
            ];

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

            // Simpan data user ke tabel m_user
            $userData = [
                'id_role' => 2, // Pastikan role sesuai dengan mitra teater
                'username' => $data['username'],
                'nama' => $data['nama'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'email' => $data['email'],
                'login_attempt' => 0,
                'tgl_dibuat' => date('Y-m-d H:i:s'),
                'tgl_dimodif' => date('Y-m-d H:i:s'),
            ];

            if (!$this->userModel->save($userData)) {
                session()->setFlashdata('error', 'Gagal menyimpan data pengguna. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }

            // Ambil ID user yang baru disimpan
            $idUser = $this->userModel->getInsertID();

            if (!$idUser) {
                session()->setFlashdata('error', 'Gagal mendapatkan ID pengguna.');
                return redirect()->back()->withInput();
            }

            $logo = $this->request->getFile('logo');

            // Periksa apakah file valid
            if (!$logo->isValid()) {
                log_message('error', 'Logo file upload error: ' . $logo->getErrorString());
                return redirect()->back()->withInput()->with('errors', ['logo' => 'File logo tidak valid atau gagal diunggah.']);
            }

            // Periksa tipe file (opsional, untuk memastikan hanya gambar yang diunggah)
            if (!$logo->isValid() || !$logo->hasMoved()) {
                if (!in_array($logo->getMimeType(), ['image/png', 'image/jpeg', 'image/jpg'])) {
                    return redirect()->back()->withInput()->with('errors', ['logo' => 'Format file logo tidak didukung.']);
                }
            }

            // Periksa apakah file valid
            if ($logo->isValid() && !$logo->hasMoved()) {
                $newName = $logo->getRandomName(); // Buat nama unik
                $logo->move('public/uploads/logo/', $newName); // Pindahkan ke folder public
                $logoUrl = 'uploads/logo/' . $newName; // Simpan path relatif
                log_message('debug', 'Logo name: ' . $logoUrl);
            }

            // Simpan data mitra ke tabel m_mitra
            $mitraData = [
                'id_user' => $idUser,
                'alamat' => $data['alamat'],
                'berdiri_sejak' => $data['berdiri_sejak'],
                'deskripsi' => $data['deskripsi'],
                'logo' => $logoUrl,
                'history_show' => $data['history_show'],
                'prestasi' => $data['prestasi'],
                'approval_status' => 'pending', // Default approval status
                'tgl_approved' => null,
                'alasan' => null,
            ];

            if (!$this->mitraModel->save($mitraData)) {
                session()->setFlashdata('error', 'Gagal menyimpan data pengguna. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }

            log_message('debug', 'Form data: ' . json_encode($data));

            $idMitra = $this->mitraModel->getInsertID();
            if (!$idMitra) {
                session()->setFlashdata('error', 'Gagal mendapatkan ID mitra.');
                return redirect()->back()->withInput();
            }

            //Proses media sosial
            $hiddenAccounts = $this->request->getPost('hidden_accounts');
            log_message('debug', 'Hidden Accounts Data: ' . print_r($hiddenAccounts, true));

            if (empty($hiddenAccounts)) {
                log_message('error', 'Hidden Accounts Kosong.');
                return redirect()->back()->withInput()->with('error', 'Harap tambahkan setidaknya satu akun media sosial.');
            }

            log_message('debug', 'Raw hidden_accounts from request: ' . print_r($hiddenAccounts, true));

            // Decode the JSON safely
            $accountsData = json_decode($hiddenAccounts, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'JSON Decoding Error: ' . json_last_error_msg());
                return redirect()->back()->withInput()->with('error', 'Data media sosial tidak valid.');
            }

            log_message('debug', 'Hidden Accounts (Decoded): ' . print_r($accountsData, true));

            if (!is_array($accountsData) || empty($accountsData)) {
                log_message('error', 'Decoded Hidden Accounts is not an array or empty.');
                return redirect()->back()->withInput()->with('error', 'Harap isi setidaknya satu akun media sosial.');
            }

            $dataSosmed = [];
            foreach ($accountsData as $index => $account) {

                // Periksa key "account" di data JSON
                if (
                    !isset($account['platformId']) || trim($account['platformId']) === '' ||
                    !isset($account['account']) || trim($account['account']) === ''
                ) {
                    session()->setFlashdata('error', "Platform atau nama akun pada entri ke-" . ($index + 1) . " tidak boleh kosong.");
                    return redirect()->back();
                }

                $accountName = trim($account['account']);
                log_message('debug', 'Account Name Length: ' . strlen($accountName));
                if (strlen($accountName) > 50) {
                    return redirect()->back()->withInput()->with('error', "Nama akun pada entri ke-" . ($index + 1) . " melebihi 50 karakter.");
                }

                log_message('debug', "Processing platformId: {$account['platformId']}, account: {$accountName}");

                $dataSosmed[] = [
                    'id_mitra' => $idMitra,
                    'id_platform_sosmed' => (int) $account['platformId'],
                    'acc_mitra' => $accountName,
                ];
            }

            var_dump($dataSosmed, 'id_platform_sosmed');

            $platformIds = array_column($dataSosmed, 'id_platform_sosmed');
            $validPlatforms = db_connect()->table('m_platform_sosmed')
                ->select('id_platform_sosmed')
                ->get()
                ->getResultArray();

            $validPlatformIds = array_column($validPlatforms, 'id_platform_sosmed');

            // Filter hanya yang memiliki id_platform_sosmed valid
            $dataSosmed = array_filter($dataSosmed, function ($entry) use ($validPlatformIds) {
                return in_array($entry['id_platform_sosmed'], $validPlatformIds);
            });

            if (empty($dataSosmed)) {
                return redirect()->back()->withInput()->with('error', 'Platform media sosial tidak valid.');
            }

            log_message('debug', 'Final Data to Insert: ' . print_r($dataSosmed, true));

            if (!empty($dataSosmed) && !$this->mitraSosmedModel->insertBatch($dataSosmed)) {
                session()->setFlashdata('error', 'Gagal menyimpan data media sosial.');
                return redirect()->back()->withInput();
            }

            log_message('debug', 'All POST Data: ' . print_r($this->request->getPost(), true));

            session()->setFlashdata('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
            return redirect()->to(base_url('Audiens/confirmation')); // Arahkan ke halaman konfirmasi
        }

        return view('templates/headerRegist') .
            view('templates/bodyRegistMitra');
    }

    public function homepageAfterLogin()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Kirim data user ke view
        return view('templates/headerMitra', ['title' => 'Homepage Theaterform', 'user' => $user]) .
            view('templates/bodyHomepageMitra') .
            view('templates/footer');
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

        return view('templates/headerMitra',  ['title' => 'List Penampilan Teater', 'user' => $user]) .
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

    // public function searchPenampilan()
    // {
    //     $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
    //     $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

    //     // Ambil filter dari request
    //     $durasiMin = $this->request->getGet('minDurasi');
    //     $durasiMax = $this->request->getGet('maxDurasi');
    //     $rating = $this->request->getGet('searchInput');
    //     $tanggal = $this->request->getGet('searchInput');
    //     $waktuMulai = $this->request->getGet('searchInput');
    //     $kota = $this->request->getGet('searchInput');
    //     $hargaMin = $this->request->getGet('minHarga');
    //     $hargaMax = $this->request->getGet('maxHarga');

    //     // Query pencarian
    //     $queryPenampilan = $this->penampilanModel->select('*');
    //     $querySchedule = $this->showScheduleModel->select('*');
    //     $queryLokasi = $this->lokasiTeaterModel->select('*');
    //     $queryHarga = $this->seatPricingModel->select('*');

    //     // Filter berdasarkan durasi
    //     if (!empty($durasiMin) && !empty($durasiMax)) {
    //         $queryPenampilan->where('durasi >=', $durasiMin)
    //             ->where('durasi <=', $durasiMax);
    //     }

    //     // Filter berdasarkan rating umur
    //     if (!empty($rating)) {
    //         $queryPenampilan->where('rating_umur', $rating);
    //     }

    //     // Filter berdasarkan tanggal pertunjukan
    //     if (!empty($tanggal)) {
    //         $querySchedule->where('tanggal', $tanggal);
    //     }

    //     // Filter berdasarkan waktu mulai pertunjukan
    //     if (!empty($waktuMulai)) {
    //         $querySchedule->where('waktu_mulai', $waktuMulai);
    //     }

    //     // Filter berdasarkan kota
    //     if (!empty($kota)) {
    //         $queryLokasi->where('kota', $kota);
    //     }

    //     // Filter berdasarkan harga
    //     if (!empty($hargaMin) && !empty($hargaMax)) {
    //         $queryHarga->where('harga >=', $hargaMin)
    //             ->where('harga <=', $hargaMax);
    //     }

    //     // Jalankan query
    //     $penampilan = $queryPenampilan->findAll();
    //     $jadwal = $querySchedule->findAll();
    //     $lokasi = $queryLokasi->findAll();
    //     $harga = $queryHarga->findAll();

    //     return view('templates/headerMitra', ['title' => 'List Penampilan Admin', 'user' => $user]) .
    //         view('templates/bodyPenampilanMitra', [
    //             'penampilan' => $penampilan,
    //             'jadwal' => $jadwal,
    //             'lokasi' => $lokasi,
    //             'harga' => $harga
    //         ]) .
    //         view('templates/footerPenampilanMitra');
    // }

    public function penampilan()
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        $relasiTeater = $this->userTeaterModel
            ->where('id_user', $userId)
            ->findAll();

        $teaterIds = array_column($relasiTeater, 'id_teater');

        $teaterList = $this->teaterModel
            ->whereIn('id_teater', $teaterIds)
            ->where('tipe_teater', 'penampilan')
            ->findAll();

        //dd($teaterList); // untuk pastikan ini benar-benar ada isinya

        $dataPenampilan = [];

        foreach ($teaterList as $teater) {
            $penampilans = $this->penampilanModel
                ->select('m_penampilan.*, m_teater.*, m_user.nama')
                ->join('m_teater', 'm_penampilan.id_teater = m_teater.id_teater')
                ->join('r_user_teater', 'm_teater.id_teater = r_user_teater.id_teater')
                ->join('m_user', 'r_user_teater.id_user = m_user.id_user')
                ->where('m_penampilan.id_teater', $teater['id_teater'])
                ->findAll();

            foreach ($penampilans as $penampilan) {
                $penampilanId = $penampilan['id_penampilan'];

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

                $sosmed = $this->sosmedModel
                    ->select('m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
                    ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
                    ->where('r_teater_sosmed.id_teater', $teater['id_teater'])
                    ->findAll();

                $sosmedFormatted = $this->formatSosmed($sosmed);

                $website = $this->teaterWebModel
                    ->where('id_teater', $teater['id_teater'])
                    ->findAll();

                $formattedWeb = $this->formatWeb($website);

                $userTeater = $this->userTeaterModel
                    ->where('id_teater', $teater['id_teater'])
                    ->first();

                $namaKomunitas = $this->userModel
                    ->where('id_user', $userTeater['id_user'])
                    ->first();

                $mitra = $this->mitraModel
                    ->where('id_user', $namaKomunitas['id_user'])
                    ->first();

                $dataPenampilan[] = [
                    'penampilan' => $penampilan,
                    'groupedSchedule' => $groupedSchedule,
                    'teater' => $teater,
                    'sosial_media' => $sosmedFormatted,
                    'website' => $formattedWeb,
                    'namaKomunitas' => $namaKomunitas,
                    'mitra' => $mitra
                ];
            }
        }

        //dd($dataPenampilan); // akan tampil di browser dan menghentikan eksekusi

        return view('templates/headerMitra', ['title' => 'List Penampilan Mitra Teater', 'user' => $user]) .
            view('templates/bodyPenampilanMitra',  ['dataPenampilan' => $dataPenampilan]) .
            view('templates/footerPenampilanMitra');
    }

    public function saveShow()
    {
        try {
            $db = \Config\Database::connect(); // Pastikan ini ada di awal
            $query = $db->getLastQuery();
            log_message('debug', 'Last Query: ' . ($query ? $query : 'NULL'));

            $db->transBegin();

            // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            // header("Cache-Control: post-check=0, pre-check=0", false);
            // header("Pragma: no-cache");

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

            $data = $this->request->getPost();

            $isEdit = isset($data['id_teater']) && !empty($data['id_teater']);
            $teater = $isEdit ? $this->teaterModel->find($data['id_teater']) : null;

            // Cek apakah user punya relasi ke teater (r_user_teater)
            $hasAccess = $isEdit ? $this->userTeaterModel
                ->where('id_user', $userId)
                ->where('id_teater', $data['id_teater'])
                ->first() : true;

            if ($isEdit && (!$teater || !$hasAccess)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data teater tidak ditemukan atau Anda tidak memiliki akses.'
                ]);
            }

            if ($this->request->getMethod() === 'POST') {
                $rules = [
                    'tipe_teater'     => 'required|in_list[penampilan,audisi]',
                    'judul'           => 'required',
                    'sinopsis'        => 'required',
                    'penulis'         => 'required',
                    'sutradara'       => 'required',
                    'durasi'          => 'required|integer',
                    'rating_umur'     => 'required',
                ];

                if (!$isEdit) {
                    $rules['poster'] = 'uploaded[poster]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]';
                }

                $validation->setRules($rules);

                if (!$validation->withRequest($this->request)->run()) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validation->getErrors()
                    ]);
                }

                $poster = $this->request->getFile('poster');
                $posterUrl = $isEdit ? $teater['poster'] : null;

                log_message('debug', 'Poster name: ' . $poster->getName());
                log_message('debug', 'Poster valid: ' . ($poster->isValid() ? 'yes' : 'no'));

                if ($poster->getError() == UPLOAD_ERR_INI_SIZE) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Ukuran file melebihi batas PHP (php.ini).'
                    ]);
                }

                // Periksa apakah file sudah diproses sebelumnya
                if ($poster->hasMoved()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'File sudah diproses sebelumnya.'
                    ]);
                }

                if ($poster && $poster->isValid()) {
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
                }

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
                    'dimodif_oleh' => $isEdit ? $user['nama'] : null,
                    'url_pendaftaran' => $data['url_pendaftaran']
                ];

                if ($this->request->getPost('atur_periode')) {
                    $teaterData['daftar_mulai'] = $this->request->getPost('daftar_mulai');
                    $teaterData['daftar_berakhir'] = $this->request->getPost('daftar_berakhir');
                } else {
                    $teaterData['daftar_mulai'] = null;
                    $teaterData['daftar_berakhir'] = null;
                }

                log_message('debug', 'Request data: ' . json_encode($this->request->getPost()));

                if ($isEdit) {
                    $teaterData['id_teater'] = $data['id_teater']; // diperlukan agar update, bukan insert
                }

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

                $idTeater = $isEdit ? $data['id_teater'] : $this->teaterModel->getInsertID();

                if (!$idTeater) {
                    $db->transRollback();
                    log_message('error', 'Gagal mendapatkan ID teater setelah insert.');

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mendapatkan ID teater.'
                    ]);
                }

                log_message('debug', 'ID Teater yang dibuat: ' . $idTeater);

                // Simpan relasi user dengan teater ke r_user_teater
                $userTeaterData = [
                    'id_user' => $userId,
                    'id_teater' => $idTeater
                ];

                if (!$isEdit) {
                    $idUserTeater = $this->userTeaterModel->insert($userTeaterData);
                    if (!$idUserTeater) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal menyimpan data relasi user dengan teater.'
                        ]);
                    }

                    log_message('debug', 'ID relasi user dengan teater yang dibuat: ' . $idUserTeater);
                }

                // Simpan data penampilan ke m_penampilan
                $penampilanData = [
                    'id_teater'   => $idTeater,
                    'aktor'       => $data['aktor'],
                    'durasi'      => $data['durasi'],
                    'rating_umur' => $data['rating_umur'],
                ];

                // Tambahkan ID saat edit agar `save()` menjadi update
                if ($isEdit) {
                    $audisiData['id_penampilan'] = $data['id_penampilan']; // pastikan 'id_audisi' ada di form saat edit
                }

                if (!$this->penampilanModel->save($penampilanData)) {
                    $db->transRollback();
                    log_message('error', 'Gagal menyimpan ke database: ' . json_encode($this->penampilanModel->errors()));

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->penampilanModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($penampilanData));

                // Ambil ID audisi hanya jika mode tambah
                if (!$isEdit) {
                    $idPenampilan = $this->penampilanModel->getInsertID();

                    if (!$idPenampilan) {
                        $db->transRollback();
                        log_message('error', 'Gagal mendapatkan ID Penampilan setelah insert.');

                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal mendapatkan ID penampilan.'
                        ]);
                    }

                    log_message('debug', 'ID Penampilan yang dibuat: ' . $idPenampilan);
                } else {
                    $idPenampilan = $data['id_penampilan'];
                    log_message('debug', 'Update data Penampilan ID: ' . $idPenampilan);
                }

                $hiddenSchedule = json_decode($data['hidden_schedule'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format jadwal penampilan tidak valid.']);
                }

                if (empty($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal penampilan tidak boleh kosong.']);
                }

                // =============================
                // Hapus semua data lama terlebih dahulu
                // =============================

                // 1. Ambil semua relasi yang berhubungan dengan audisi ini
                $oldRelations = $this->db->table('r_show_schedule rs')
                    ->select('rs.id_schedule, rs.id_pricing')
                    ->join('m_seat_pricing msp', 'rs.id_pricing = msp.id_pricing')
                    ->where('msp.id_penampilan', $idPenampilan)
                    ->get()
                    ->getResult();

                foreach ($oldRelations as $relasi) {
                    // Hapus relasi
                    $this->db->table('r_show_schedule')
                        ->where('id_schedule', $relasi->id_schedule)
                        ->delete();

                    // Hapus jadwal penampilan
                    $this->showScheduleModel->delete($relasi->id_schedule);
                }

                // 2. Hapus semua harga/tipe audisi terkait
                $this->db->table('m_seat_pricing')
                    ->where('id_penampilan', $idPenampilan)
                    ->delete();

                // =============================
                // Simpan ulang semua data baru
                // =============================

                $denahMapping = [];
                $denahNames = [];

                // Ambil file dari form (multiple upload)
                $mappingIds = $this->request->getPost('denah_mapping');
                $allDenah = $this->request->getFileMultiple('denah_seat');

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

                    $existingLocation = $this->lokasiTeaterModel
                        ->where('tempat', $tempat)
                        ->where('kota', $kota)
                        ->first();

                    if ($existingLocation) {
                        $idLocation = $existingLocation['id_location'];
                    } else {
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
                        $namaKategori = isset($schedule['nama_kategori']) ? trim($schedule['nama_kategori']) : null;
                        $hargaKategori = trim($schedule['harga']);

                        if (empty($hargaKategori)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Harga tidak boleh kosong.']);
                        }

                        $kategoriArray = $namaKategori ? array_map('trim', explode(',', $namaKategori)) : [];
                        $hargaArray = array_map('trim', explode(',', $hargaKategori));

                        if (!empty($kategoriArray) && count($kategoriArray) !== count($hargaArray)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah kategori dan harga tidak sesuai.']);
                        }

                        $key = $idTeater . '|' . $idLocation;
                        $scheduleId = $schedule['id'];

                        // 📌 Cek dan upload denah HANYA jika ada kategori
                        if (!empty($kategoriArray)) {
                            if (!is_array($mappingIds) || !is_array($allDenah)) {
                                return $this->response->setJSON([
                                    'status' => 'error',
                                    'message' => 'Data denah seat tidak valid.'
                                ]);
                            }

                            $denahIndex = array_search($scheduleId, $mappingIds);
                            if ($denahIndex === false) {
                                log_message('error', "Schedule ID $scheduleId tidak ditemukan dalam mapping denah.");
                                continue;
                            }

                            if (!isset($allDenah[$denahIndex])) {
                                log_message('error', "File denah tidak ditemukan di index $denahIndex.");
                                continue;
                            }

                            if (empty($kategoriArray)) {
                                $denahMapping[$key] = null;
                            }

                            $uploadedDenah = $allDenah[$denahIndex];

                            if ($uploadedDenah && $uploadedDenah->isValid()) {
                                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                                if (!in_array($uploadedDenah->getMimeType(), $allowedTypes)) {
                                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format file denah seat tidak didukung.']);
                                }

                                if ($uploadedDenah->hasMoved()) {
                                    return $this->response->setJSON(['status' => 'error', 'message' => 'File denah sudah diproses.']);
                                }

                                $uploadPath = ROOTPATH . 'public/uploads/denah/';
                                if (!is_dir($uploadPath)) {
                                    mkdir($uploadPath, 0777, true);
                                }

                                $newName = $uploadedDenah->getRandomName();
                                if ($uploadedDenah->move($uploadPath, $newName)) {
                                    $denah_seat = 'uploads/denah/' . $newName;

                                    $this->denahModel->save([
                                        'id_teater'    => $idTeater,
                                        'id_location'  => $idLocation,
                                        'denah_seat'   => $denah_seat
                                    ]);

                                    $idDenah = $this->denahModel->getInsertID();
                                    $denahMapping[$key] = $idDenah;
                                    $denahNames[$index] = $denah_seat;
                                }
                            }
                        }

                        // 🚧 Simpan harga dan relasi tetap jalan walaupun tidak ada kategori
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
                                    'id_penampilan' => $idPenampilan,
                                    'tipe_harga' => 'Bayar',
                                    'harga' => $harga,
                                    'nama_kategori' => $kategori_seat
                                ];
                                $this->seatPricingModel->save($seatPricingData);
                                $idSeatPricing = $this->seatPricingModel->getInsertID();
                            }

                            if ($idSchedule && $idSeatPricing) {
                                $this->db->table('r_show_schedule')->insert([
                                    'id_schedule' => $idSchedule,
                                    'id_pricing' => $idSeatPricing,
                                    'id_denah'    => (!empty($kategoriArray) ? ($denahMapping[$key] ?? null) : null)
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
                                'id_denah'  => null
                            ]);
                        }
                    }

                    log_message('debug', 'Data jadwal diterima: ' . json_encode($hiddenSchedule));
                }

                // Validasi: jika ada lebih dari 1 denah untuk kombinasi idTeater|idLocation
                $keyCount = array_count_values(array_keys($denahMapping));

                foreach ($keyCount as $key => $count) {
                    if ($count > 1) {
                        // Hapus semua file denah yang sudah diupload
                        foreach ($denahNames as $path) {
                            if (file_exists(ROOTPATH . 'public/' . $path)) {
                                unlink(ROOTPATH . 'public/' . $path);
                            }
                        }

                        // Hapus entri denah dari database
                        foreach ($denahMapping as $dupKey => $idDenah) {
                            if (isset($idDenah)) {
                                $this->denahModel->delete($idDenah);
                            }
                        }

                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => "Terdapat lebih dari satu denah yang diupload untuk kombinasi teater dan lokasi yang sama ($key). Proses dibatalkan."
                        ]);
                    }
                }

                //7. Simpan sosial media teater ke r_teater_sosmed
                $deletedAccounts = json_decode($data['deleted_accounts'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($deletedAccounts)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format data deleted_accounts tidak valid.']);
                }

                if (is_array($deletedAccounts)) {
                    foreach ($deletedAccounts as $id) {
                        $this->sosmedModel->delete($id); // atau ->where('id_teater_sosmed', $id)->delete();
                    }
                }

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

                $deletedWebs = json_decode($data['deleted_webs'], true);
                if (is_array($deletedWebs)) {
                    foreach ($deletedWebs as $id) {
                        $this->teaterWebModel->delete($id);
                    }
                }

                // **8. Simpan data website teater ke m_teater_web**
                $websites = json_decode($this->request->getPost('hidden_web'), true);
                foreach ($websites as $website) {
                    // Pastikan keduanya tidak kosong
                    if (!empty($website['title']) && !empty($website['url'])) {
                        $this->teaterWebModel->insert([
                            'id_teater' => $idTeater,
                            'judul_web' => $website['title'],
                            'url_web' => $website['url']
                        ]);
                    }
                }

                $db->transCommit();
                return $this->response->setJSON([
                    'success'  => true,
                    'message' => $isEdit ? 'Pertunjukan Teater berhasil diperbarui!' : 'Pertunjukan Teater berhasil ditambahkan!',
                    'id_teater' => $idTeater,
                    'denah_names' => $denahNames,
                    'redirect' => base_url('MitraTeater/crudPenampilan') // Tambahkan URL redirect
                ]);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'errors'  => $e->getMessage(), // Debug untuk melihat validasi gagal
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function deleteDenah()
    {
        $fileParam = $this->request->getGet('file');

        if ($fileParam) {
            $filename = basename($fileParam);
            $path = ROOTPATH . 'public/uploads/denah/' . $filename;

            if (file_exists($path)) {
                unlink($path);
                return $this->response->setJSON(['status' => 'success', 'message' => 'File deleted']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File not found']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No file specified']);
    }

    public function editPertunjukan($id_teater)
    {
        if (!session()->has('id_user')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi login Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        // Cek user dari session
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

        // 1. Ambil data teater pertunjukan
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->where('tipe_teater', 'penampilan')
            ->first();

        if (!$teater) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Teater pertunjukan tidak ditemukan.'
            ]);
        }

        $penampilan = $this->penampilanModel
            ->where('id_teater', $teater['id_teater'])
            ->first();

        $jadwalPenampilan = [];
        $sosmed = [];
        $website = [];

        if ($penampilan) {
            $id_penampilan = $penampilan['id_penampilan'];

            // 2. Ambil data penampilan (harga, jadwal, lokasi)
            $jadwalPenampilan = $this->showSeatPricingModel
                ->select('
            r_show_schedule.id_schedule as id,
            m_show_schedule.tanggal,
            m_show_schedule.waktu_mulai,
            m_show_schedule.waktu_selesai,
            m_lokasi_teater.kota,
            m_lokasi_teater.tempat,
            p.harga,
            p.nama_kategori,
            p.tipe_harga,
            m_denah_seat.denah_seat
        ')
                ->join('m_show_schedule', 'r_show_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
                ->join('m_seat_pricing p', 'r_show_schedule.id_pricing = p.id_pricing')
                ->join('m_denah_seat', 'r_show_schedule.id_denah = m_denah_seat.id_denah')
                ->join('m_penampilan', 'p.id_penampilan = m_penampilan.id_penampilan')
                ->where('m_penampilan.id_penampilan', $id_penampilan)
                ->orderBy('m_show_schedule.tanggal ASC')
                ->orderBy('m_show_schedule.waktu_mulai ASC')
                ->findAll();

            // 3. Sosmed
            $sosmed = $this->sosmedModel
                ->select('m_platform_sosmed.id_platform_sosmed, m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
                ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
                ->where('r_teater_sosmed.id_teater', $id_teater)
                ->findAll();

            $websiteRaw = $this->teaterWebModel
                ->where('id_teater', $teater['id_teater'])
                ->findAll();

            $website = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['judul_web'],
                    'url' => $item['url_web']
                ];
            }, $websiteRaw);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'mode' => 'edit',
            'data' => [
                'user' => $user,
                'teater' => $teater,
                'penampilan' => $penampilan,
                'jadwal' => $jadwalPenampilan,
                'sosmed' => $sosmed,
                'websites' => $website
            ]
        ]);
    }

    public function deleteShowByTeater()
    {
        $idTeater = $this->request->getPost('id_teater');

        // 1. Ambil semua id_penampilan dari teater
        $idPenampilanList = $this->penampilanModel
            ->where('id_teater', $idTeater)
            ->findColumn('id_penampilan');

        if (!empty($idPenampilanList)) {
            // 2. Ambil semua id_pricing dari seat pricing
            $pricingIds = $this->seatPricingModel
                ->whereIn('id_penampilan', $idPenampilanList)
                ->findColumn('id_pricing');

            if (!empty($pricingIds)) {
                // 3. Ambil semua id_denah dari relasi r_show_schedule
                $denahIds = $this->showSeatPricingModel
                    ->whereIn('id_pricing', $pricingIds)
                    ->findColumn('id_denah');

                // 4. Hapus data dari r_show_schedule
                $this->showSeatPricingModel
                    ->whereIn('id_pricing', $pricingIds)
                    ->delete();

                // 5. Hapus denah seat jika ada
                if (!empty($denahIds)) {
                    $this->denahModel
                        ->whereIn('id_denah', $denahIds)
                        ->delete();
                }

                // 6. Hapus seat pricing
                $this->seatPricingModel
                    ->whereIn('id_pricing', $pricingIds)
                    ->delete();
            }

            // 7. Hapus data penampilan
            $this->penampilanModel
                ->whereIn('id_penampilan', $idPenampilanList)
                ->delete();
        }

        // 8. Hapus show schedule berdasarkan teater
        $this->showScheduleModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 9. Hapus relasi sosial media
        $this->sosmedModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 10. Hapus data website teater
        $this->teaterWebModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 11. Hapus relasi user-teater
        $this->userTeaterModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 12. Hapus data teater utama
        $this->teaterModel->delete($idTeater);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Seluruh data pertunjukan dan teater berhasil dihapus.'
        ]);
    }

    public function getBookingBySchedule($tipe, $id)
    {
        $bookingModel = $this->bookingModel;

        // Join dasar untuk semua tipe
        $bookingModel
            ->select('
            m_user.nama, 
            m_user.email, 
            m_audiens.tgl_lahir AS tanggal_lahir, 
            m_audiens.gender AS jenis_kelamin,
            m_show_schedule.tanggal, 
            m_show_schedule.waktu_mulai,
            m_show_schedule.waktu_selesai,
            t_booking.status,
            t_booking.is_free,
            t_booking.bukti_pembayaran,
            t_booking.created_at
        ')
            ->join('m_audiens', 'm_audiens.id_audiens = t_booking.id_audiens')
            ->join('m_user', 'm_user.id_user = m_audiens.id_user')
            ->join('r_show_schedule', 'r_show_schedule.id_schedule_show = t_booking.id_jadwal')
            ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_show_schedule.id_schedule');

        if ($tipe === 'penampilan') {
            // Tambahan join untuk lacak id_penampilan
            $bookingModel
                ->join('m_seat_pricing', 'm_seat_pricing.id_pricing = r_show_schedule.id_pricing')
                ->where('m_seat_pricing.id_penampilan', $id);
        } elseif ($tipe === 'audisi') {
            $bookingModel
                ->join('m_audisi_schedule', 'm_audisi_schedule.id_pricing_audisi = r_audisi_schedule.id_pricing_audisi')
                ->where('m_audisi_schedule.id_audisi', $id);
        }

        // Filter status cancel
        //$bookingModel->where('t_booking.status !=', 'cancel');

        // Ambil datanya
        $data = $bookingModel->findAll();

        // Format untuk frontend
        foreach ($data as &$row) {
            $row['tanggal_lahir'] = date('d-m-Y', strtotime($row['tanggal_lahir']));
            $row['jadwal'] = date('d-m-Y', strtotime($row['tanggal'])) . ', ' . substr($row['waktu_mulai'], 0, 5) . ' - ' . substr($row['waktu_selesai'], 0, 5);
            $row['bukti_pembayaran'] = $row['is_free'] ? 'Gratis' : ($row['bukti_pembayaran'] ?? '-');
            $row['tanggal_daftar'] = date('d-m-Y H:i', strtotime($row['created_at']));
        }

        log_message('debug', 'Booking Data: ' . print_r($data, true));

        // Hitung jumlah tiket dengan status success
        $tiketQuery = $this->bookingModel
            ->where('status', 'success')
            ->where('tipe_jadwal', $tipe);

        if ($tipe === 'penampilan') {
            $tiketQuery
                ->join('m_show_schedule', 'm_show_schedule.id_schedule = t_booking.id_jadwal')
                ->join('r_show_schedule', 'r_show_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_seat_pricing', 'm_seat_pricing.id_pricing = r_show_schedule.id_pricing')
                ->where('m_seat_pricing.id_penampilan', $id);
        } elseif ($tipe === 'audisi') {
            $tiketQuery
                ->join('m_show_schedule', 'm_show_schedule.id_schedule = t_booking.id_jadwal')
                ->join('r_audisi_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_audisi_schedule', 'm_audisi_schedule.id_pricing_audisi = r_audisi_schedule.id_pricing_audisi')
                ->where('m_audisi_schedule.id_audisi', $id);
        }

        $tiketTerjual = $tiketQuery->countAllResults();

        return $this->response->setJSON([
            'data' => $data,
            'tiket_terjual' => $tiketTerjual
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

        return view('templates/headerMitra',  ['title' => 'List Audisi Teater', 'user'  => $user]) .
            view('templates/bodyAudisi', [
                'audisiAktor' => $finalAudisiAktor,
                'audisiStaff' => $finalAudisiStaff
            ]) .
            view('templates/footerListPenampilan', ['needsDropdown' => true]);
    }

    private function formatAudisi($audisi)
    {
        return [
            'id_teater' => $audisi['id_teater'],
            'judul' => $audisi['judul'],
            'komunitas_teater' => $audisi['komunitas_teater'],
            'lokasi_teater' => $audisi['tempat'],
            'tanggal' => $this->formatTanggalIndoLengkap($audisi['tanggal']),
            'waktu' => $this->formatJam($audisi['waktu_mulai']) . ' - ' . $this->formatJam($audisi['waktu_selesai']),
            'poster' => $audisi['poster'],
        ];
    }

    // public function searchAudisi()
    // {
    //     $userId = session()->get('id_user'); // Ambil ID user dari session
    //     $user = $this->userModel->find($userId); // Ambil data user berdasarkan ID

    //     // Ambil filter dari request
    //     $hargaMin = $this->request->getGet('minHarga');
    //     $hargaMax = $this->request->getGet('maxHarga');
    //     $tanggal = $this->request->getGet('searchTanggal');
    //     $waktuMulai = $this->request->getGet('searchWaktu');
    //     $kota = $this->request->getGet('searchKota');
    //     $gajiMin = $this->request->getGet('minGaji');
    //     $gajiMax = $this->request->getGet('maxGaji');

    //     // Query untuk `m_show_schedule`
    //     $queryShowSchedule = $this->showScheduleModel;

    //     if (!empty($tanggal)) {
    //         $queryShowSchedule = $queryShowSchedule->where('tanggal', $tanggal);
    //     }

    //     if (!empty($waktuMulai)) {
    //         $queryShowSchedule = $queryShowSchedule->where('waktu_mulai', $waktuMulai);
    //     }

    //     // Query untuk `m_lokasi_teater`
    //     $queryLokasi = $this->lokasiTeaterModel;

    //     if (!empty($kota)) {
    //         $queryLokasi = $queryLokasi->where('kota', $kota);
    //     }

    //     // Query untuk `m_audisi_schedule` (harga)
    //     $queryAudisiSchedule = $this->audisiScheduleModel;

    //     if (!empty($hargaMin) && !empty($hargaMax)) {
    //         $queryAudisiSchedule = $queryAudisiSchedule
    //             ->where('harga >=', $hargaMin)
    //             ->where('harga <=', $hargaMax);
    //     }

    //     // Query untuk `m_audisi` (gaji)
    //     $queryAudisi = $this->audisiModel;

    //     if (!empty($gajiMin) && !empty($gajiMax)) {
    //         $queryAudisi = $queryAudisi
    //             ->where('gaji >=', $gajiMin)
    //             ->where('gaji <=', $gajiMax);
    //     }

    //     // Eksekusi query
    //     $showScheduleResults = $queryShowSchedule->findAll();
    //     $lokasiResults = $queryLokasi->findAll();
    //     $audisiScheduleResults = $queryAudisiSchedule->findAll();
    //     $audisiResults = $queryAudisi->findAll();

    //     return view('templates/headerMitra', ['title' => 'List Audisi Admin', 'user' => $user]) .
    //         view('templates/bodyAudisiMitra', [
    //             'showScheduleResults' => $showScheduleResults,
    //             'lokasiResults' => $lokasiResults,
    //             'audisiScheduleResults' => $audisiScheduleResults,
    //             'audisiResults' => $audisiResults
    //         ]) .
    //         view('templates/footerAudisiMitra');
    // }

    public function audisi()
    {
        if (!session()->has('id_user')) {
            session()->setFlashdata('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
            return redirect()->to(base_url('User/login'));
        }

        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        $relasiTeater = $this->userTeaterModel->where('id_user', $userId)->findAll();
        $teaterIds = array_column($relasiTeater, 'id_teater');

        $teaterList = $this->teaterModel
            ->whereIn('id_teater', $teaterIds)
            ->where('tipe_teater', 'audisi')
            ->findAll();

        $dataAudisi = [];

        foreach ($teaterList as $teater) {
            $auditions = $this->audisiModel
                ->select('m_audisi.*, m_teater.*, m_user.nama, m_kategori_audisi.*')
                ->join('m_teater', 'm_audisi.id_teater = m_teater.id_teater')
                ->join('r_user_teater', 'm_teater.id_teater = r_user_teater.id_teater')
                ->join('m_user', 'r_user_teater.id_user = m_user.id_user')
                ->join('m_kategori_audisi', 'm_audisi.id_kategori = m_kategori_audisi.id_kategori')
                ->where('m_teater.id_teater', $teater['id_teater'])
                ->findAll();

            foreach ($auditions as $audisi) {
                // Ambil data aktor jika ada
                $aktor = $this->audisiAktorModel
                    ->where('id_audisi', $audisi['id_audisi'])
                    ->first();

                // Ambil data staff jika ada
                $staff = $this->audisiStaffModel
                    ->where('id_audisi', $audisi['id_audisi'])
                    ->first();

                $audisiPricing = $this->audisiScheduleModel
                    ->where('id_audisi', $audisi['id_audisi'])
                    ->findAll();

                $jadwalAudisi = $this->audisiPricingModel
                    ->select('
                        m_show_schedule.tanggal,
                        CONCAT(DATE_FORMAT(m_show_schedule.waktu_mulai, "%H:%i"), " - ", DATE_FORMAT(m_show_schedule.waktu_selesai, "%H:%i")) AS waktu,
                        m_lokasi_teater.kota,
                        m_lokasi_teater.tempat,
                        m_audisi_schedule.harga, 
                        m_audisi_schedule.tipe_harga
                    ')
                    ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
                    ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
                    ->join('m_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
                    ->where('m_audisi_schedule.id_audisi', $audisi['id_audisi'])
                    ->findAll();

                $groupedSchedule = [];
                foreach ($jadwalAudisi as $row) {
                    $hargaFormatted = $row['harga'] == 0 ? '-' : number_format($row['harga'], 0, ',', '.');
                    $groupedSchedule[$row['kota']][$row['tempat']][$row['tanggal']][] = [
                        'waktu' => $row['waktu'],
                        'harga_display' => $hargaFormatted
                    ];
                }

                $sosmed = $this->sosmedModel
                    ->select('m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
                    ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
                    ->where('r_teater_sosmed.id_teater', $teater['id_teater'])
                    ->findAll();
                $sosmedFormatted = $this->formatSosmed($sosmed);

                $website = $this->teaterWebModel->where('id_teater', $teater['id_teater'])->findAll();
                $formattedWeb = $this->formatWeb($website);

                $userTeater = $this->userTeaterModel->where('id_teater', $teater['id_teater'])->first();
                $namaKomunitas = $this->userModel->where('id_user', $userTeater['id_user'])->first();
                $mitra = $this->mitraModel->where('id_user', $namaKomunitas['id_user'])->first();

                $dataAudisi[] = [
                    'audisi' => $audisi,
                    'aktor' => $aktor,
                    'staff' => $staff,
                    'audisiPricing' => $audisiPricing,
                    'grouped_schedule' => $groupedSchedule,
                    'teater' => $teater,
                    'sosial_media' => $sosmedFormatted,
                    'website' => $formattedWeb,
                    'namaKomunitas' => $namaKomunitas,
                    'mitra' => $mitra
                ];
            }
        }

        return view('templates/headerMitra', ['title' => 'List Audisi Mitra Teater', 'user' => $user]) .
            view('templates/bodyAudisiMitra', ['dataAudisi' => $dataAudisi]) .
            view('templates/footerAudisiMitra');
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
            $account = $sosmed['acc_teater'];

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

    private function formatWeb($webData)
    {
        if (empty($webData) || !is_array($webData)) {
            return '-';
        }

        $formatted = [];

        foreach ($webData as $web) {
            $url = $web['url_web'];
            $judul = $web['judul_web']; // pastikan nama kolom di DB memang `judul_web`

            if ($url && $judul) {
                $formatted[] = "$url ($judul)";
            } elseif ($url) {
                $formatted[] = $url;
            }
        }

        return implode(", ", $formatted);
    }

    public function saveAuditionAktor()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->getLastQuery();
            log_message('debug', 'Last Query: ' . ($query ? $query : 'NULL'));

            $db->transBegin(); // Mulai transaksi

            //header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            //header("Cache-Control: post-check=0, pre-check=0", false);
            //header("Pragma: no-cache");

            $validation = \Config\Services::validation();

            // Cek user dari session
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

            $data = $this->request->getPost();

            $isEdit = isset($data['id_teater']) && !empty($data['id_teater']);
            $teater = $isEdit ? $this->teaterModel->find($data['id_teater']) : null;

            // Cek apakah user punya relasi ke teater (r_user_teater)
            $hasAccess = $isEdit ? $this->userTeaterModel
                ->where('id_user', $userId)
                ->where('id_teater', $data['id_teater'])
                ->first() : true;

            if ($isEdit && (!$teater || !$hasAccess)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data teater tidak ditemukan atau Anda tidak memiliki akses.'
                ]);
            }

            if ($this->request->getMethod() === 'POST') {
                $rules = [
                    'tipe_teater'     => 'required|in_list[penampilan,audisi]',
                    'judul'           => 'required',
                    'penulis'         => 'required',
                    'sutradara'       => 'required'
                ];

                if (!$isEdit) {
                    $rules['poster'] = 'uploaded[poster]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]';
                }

                $validation->setRules($rules);

                if (!$validation->withRequest($this->request)->run()) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validation->getErrors()
                    ]);
                }

                $poster = $this->request->getFile('poster');
                $posterUrl = $isEdit ? $teater['poster'] : null;

                log_message('debug', 'Poster name: ' . $poster->getName());
                log_message('debug', 'Poster valid: ' . ($poster->isValid() ? 'yes' : 'no'));

                // Periksa apakah file sudah diproses sebelumnya
                if ($poster->hasMoved()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'File sudah diproses sebelumnya.'
                    ]);
                }

                if ($poster && $poster->isValid()) {
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
                }

                // **1. Simpan data ke m_teater**
                $teaterData = [
                    'tipe_teater'  => $data['tipe_teater'],
                    'judul'        => $data['judul'],
                    'poster'       => $posterUrl,
                    'sinopsis'     => $data['sinopsis'] ?? null,
                    'penulis'      => $data['penulis'],
                    'sutradara'    => $data['sutradara'],
                    'staff'        => $data['staff'] ?? null,
                    'dibuat_oleh'  => $user['nama'],
                    'dimodif_oleh' => $isEdit ? $user['nama'] : null,
                    'url_pendaftaran' => $data['url_pendaftaran']
                ];

                if ($this->request->getPost('atur_periode')) {
                    $teaterData['daftar_mulai'] = $this->request->getPost('daftar_mulai');
                    $teaterData['daftar_berakhir'] = $this->request->getPost('daftar_berakhir');
                } else {
                    $teaterData['daftar_mulai'] = null;
                    $teaterData['daftar_berakhir'] = null;
                }

                log_message('debug', 'Request data: ' . json_encode($this->request->getPost()));

                if ($isEdit) {
                    $teaterData['id_teater'] = $data['id_teater']; // diperlukan agar update, bukan insert
                }

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

                $idTeater = $isEdit ? $data['id_teater'] : $this->teaterModel->getInsertID();

                if (!$idTeater) {
                    $db->transRollback();
                    log_message('error', 'Gagal mendapatkan ID teater setelah insert.');

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mendapatkan ID teater.'
                    ]);
                }

                log_message('debug', 'ID Teater yang dibuat: ' . $idTeater);

                // Simpan relasi user dengan teater ke r_user_teater
                $userTeaterData = [
                    'id_user' => $userId,
                    'id_teater' => $idTeater
                ];

                if (!$isEdit) {
                    $idUserTeater = $this->userTeaterModel->insert($userTeaterData);
                    if (!$idUserTeater) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal menyimpan data relasi user dengan teater.'
                        ]);
                    }

                    log_message('debug', 'ID relasi user dengan teater yang dibuat: ' . $idUserTeater);
                }

                // Ambil input gaji dan status checkbox
                $gajiInput = $this->request->getPost('gaji');
                $isGajiDirahasiakan = $this->request->getPost('gaji_dirahasiakan');

                if ($isGajiDirahasiakan) {
                    $gaji = null;
                    $statusGaji = 'secret';
                } elseif (!empty($gajiInput)) {
                    $gaji = $gajiInput;
                    $statusGaji = 'shown';
                } else {
                    $gaji = null;
                    $statusGaji = 'no';
                }

                // **2. Simpan data ke m_audisi**
                $audisiData = [
                    'id_teater'   => $idTeater,
                    'id_kategori' => $data['id_kategori'],
                    'syarat'      => $data['syarat'],
                    'syarat_dokumen' => $data['syarat_dokumen'] ?? null,
                    'gaji'           => $gaji,
                    'status_gaji'   => $statusGaji,
                    'komitmen'  => $data['komitmen'] ?? null
                ];

                // Tambahkan ID saat edit agar `save()` menjadi update
                if ($isEdit) {
                    $audisiData['id_audisi'] = $data['id_audisi']; // pastikan 'id_audisi' ada di form saat edit
                }

                if (!$this->audisiModel->save($audisiData)) {
                    $db->transRollback();
                    log_message('error', 'Gagal menyimpan ke database: ' . json_encode($this->audisiModel->errors()));

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->audisiModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($audisiData));

                // Ambil ID audisi hanya jika mode tambah
                if (!$isEdit) {
                    $idAudisi = $this->audisiModel->getInsertID();

                    if (!$idAudisi) {
                        $db->transRollback();
                        log_message('error', 'Gagal mendapatkan ID Audisi setelah insert.');

                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Gagal mendapatkan ID Audisi.'
                        ]);
                    }

                    log_message('debug', 'ID Audisi yang dibuat: ' . $idAudisi);
                } else {
                    $idAudisi = $data['id_audisi']; // ID yang diedit
                    log_message('debug', 'Update data Audisi ID: ' . $idAudisi);
                }

                $aktorData = [
                    'id_audisi'        => $idAudisi,
                    'karakter_audisi'  => $data['karakter_audisi'] ?? null,
                    'deskripsi_karakter' => $data['deskripsi_karakter'] ?? null
                ];

                // Tambahkan ID saat edit agar save() menjadi update
                if ($isEdit) {
                    $aktorData['id'] = $data['id_aktor_audisi']; // ID primary key dari m_audisi_aktor
                }

                if (!$this->audisiAktorModel->save($aktorData)) {
                    $db->transRollback();

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->audisiAktorModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($aktorData));

                // Ambil ID hanya saat tambah
                if (!$isEdit) {
                    $idAktor = $this->audisiAktorModel->getInsertID();

                    if (!$idAktor) {
                        $db->transRollback();
                        log_message('error', 'Gagal mendapatkan ID Aktor setelah insert.');

                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Gagal mendapatkan ID Aktor.'
                        ]);
                    }

                    log_message('debug', 'ID Aktor yang dibuat: ' . $idAktor);
                } else {
                    $idAktor = $data['id_aktor_audisi'];
                    log_message('debug', 'Update data Audisi Aktor ID: ' . $idAktor);
                }

                $hiddenSchedule = json_decode($data['hidden_schedule'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format jadwal penampilan tidak valid.']);
                }

                if (empty($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal audisi tidak boleh kosong.']);
                }

                // =============================
                // Hapus semua data lama terlebih dahulu
                // =============================

                // 1. Ambil semua relasi yang berhubungan dengan audisi ini
                $oldRelations = $this->db->table('r_audisi_schedule ra')
                    ->select('ra.id_schedule, ra.id_pricing_audisi')
                    ->join('m_audisi_schedule mas', 'ra.id_pricing_audisi = mas.id_pricing_audisi')
                    ->where('mas.id_audisi', $idAudisi)
                    ->get()
                    ->getResult();

                foreach ($oldRelations as $relasi) {
                    // Hapus relasi
                    $this->db->table('r_audisi_schedule')
                        ->where('id_schedule', $relasi->id_schedule)
                        ->delete();

                    // Hapus jadwal penampilan
                    $this->showScheduleModel->delete($relasi->id_schedule);
                }

                // 2. Hapus semua harga/tipe audisi terkait
                $this->db->table('m_audisi_schedule')
                    ->where('id_audisi', $idAudisi)
                    ->delete();

                // =============================
                // Simpan ulang semua data baru
                // =============================
                foreach ($hiddenSchedule as $index => $schedule) {
                    $tanggal = $schedule['tanggal'];
                    $waktu_mulai = $schedule['waktu_mulai'];
                    $waktu_selesai = $schedule['waktu_selesai'];
                    $tempat = $schedule['tempat'];
                    $kota = $schedule['kota'];
                    $tipe_harga = isset($schedule['tipe_harga']) && $schedule['tipe_harga'] === 'Gratis' ? 'Gratis' : 'Bayar';
                    $harga = isset($schedule['harga']) ? $schedule['harga'] : null;

                    $locationData = [
                        'tempat' => $tempat,
                        'kota' => $kota,
                    ];

                    $existingLocation = $this->lokasiTeaterModel
                        ->where('tempat', $tempat)
                        ->where('kota', $kota)
                        ->first();

                    if ($existingLocation) {
                        $idLocation = $existingLocation['id_location'];
                    } else {
                        if (!$this->lokasiTeaterModel->save($locationData)) {
                            return $this->response->setJSON([
                                'status' => 'error',
                                'message' => 'Gagal menyimpan lokasi pertunjukan teater.',
                                'errors'  => $this->lokasiTeaterModel->errors()
                            ]);
                        }

                        $idLocation = $this->lokasiTeaterModel->getInsertID();

                        if (!$idLocation) {
                            return $this->response->setJSON([
                                'status' => 'error',
                                'message' => 'Gagal mendapatkan ID Location setelah insert.'
                            ]);
                        }
                    }

                    // Simpan ke m_show_schedule
                    $scheduleData = [
                        'id_teater'     => $idTeater,
                        'id_location'   => $idLocation,
                        'tanggal'       => $tanggal,
                        'waktu_mulai'   => $waktu_mulai,
                        'waktu_selesai' => $waktu_selesai,
                    ];

                    if (!$this->showScheduleModel->save($scheduleData)) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data jadwal pertunjukan teater.']);
                    }

                    $idSchedule = $this->showScheduleModel->getInsertID();

                    if (!$idSchedule) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mendapatkan ID Schedule penampilan teater.']);
                    }

                    // Simpan ke m_audisi_schedule (harga)
                    $auditionPricingData = [
                        'id_audisi'   => $idAudisi,
                        'tipe_harga'  => $tipe_harga,
                        'harga'       => $tipe_harga === 'Bayar' ? $harga : null,
                    ];

                    if (!$this->audisiScheduleModel->save($auditionPricingData)) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data harga audisi.']);
                    }

                    $idAuditionPricing = $this->audisiScheduleModel->getInsertID();

                    // Simpan ke r_audisi_schedule
                    if (!$this->db->table('r_audisi_schedule')->insert([
                        'id_schedule'         => $idSchedule,
                        'id_pricing_audisi'   => $idAuditionPricing
                    ])) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan relasi jadwal dan harga.']);
                    }
                }

                // **7. Simpan sosial media teater ke r_teater_sosmed**
                //$deletedAccounts = json_decode($data['deleted_accounts'], true);

                //if (json_last_error() !== JSON_ERROR_NONE || !is_array($deletedAccounts)) {
                //    return $this->response->setJSON(['status' => 'error', 'message' => 'Format data deleted_accounts tidak valid.']);
                //}

                //foreach ($deletedAccounts as $id) {
                //    $this->sosmedModel->delete($id); // atau ->where('id_teater_sosmed', $id)->delete();
                //}

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

                //$deletedWebs = json_decode($data['deleted_webs'], true);
                //foreach ($deletedWebs as $id) {
                //$this->teaterWebModel->delete($id);
                //}

                // **8. Simpan data website teater ke m_teater_web**
                $websites = json_decode($this->request->getPost('hidden_web'), true);
                if (is_array($websites)) {
                    foreach ($websites as $website) {
                        if (!empty($website['title']) && !empty($website['url'])) {
                            $this->teaterWebModel->insert([
                                'id_teater' => $idTeater,
                                'judul_web' => $website['title'],
                                'url_web' => $website['url']
                            ]);
                        }
                    }
                }

                $db->transCommit();
                return $this->response->setJSON([
                    'success'  => true,
                    'message' => $isEdit ? 'Audisi Aktor Teater berhasil diperbarui!' : 'Audisi Aktor Teater berhasil ditambahkan!',
                    'id_teater' => $idTeater,
                    'redirect' => base_url('MitraTeater/crudAudisi') // Tambahkan URL redirect
                ]);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'errors'  => $e->getMessage(), // Debug untuk melihat validasi gagal
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function saveAuditionStaff()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->getLastQuery();
            log_message('debug', 'Last Query: ' . ($query ? $query : 'NULL'));

            $db->transBegin();

            $validation = \Config\Services::validation();

            // ✅ Ambil user login dari session
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

            $data = $this->request->getPost();

            $isEdit = isset($data['id_teater']) && !empty($data['id_teater']);
            $teater = $isEdit ? $this->teaterModel->find($data['id_teater']) : null;

            if ($isEdit && (!$teater || $teater['id_user'] != $userId)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data teater tidak ditemukan atau Anda tidak memiliki akses.'
                ]);
            }

            if ($this->request->getMethod() === 'POST') {
                $rules = [
                    'tipe_teater'     => 'required|in_list[penampilan,audisi]',
                    'judul'           => 'required',
                    'penulis'         => 'required',
                    'sutradara'       => 'required',
                    'syarat'          => 'required',
                    'url_pendaftaran' => [
                        'rules' => 'required|regex_match[/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/]',
                        'errors' => [
                            'regex_match' => 'Format URL tidak valid.'
                        ]
                    ]
                ];

                if (!$isEdit) {
                    $rules['poster'] = 'uploaded[poster]|is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]';
                }

                $validation->setRules($rules);

                if (!$validation->withRequest($this->request)->run()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Validasi gagal',
                        'errors'  => $validation->getErrors()
                    ]);
                }

                // ✅ Upload Poster
                $poster = $this->request->getFile('poster');
                $posterUrl = $isEdit ? $teater['poster'] : null;

                log_message('debug', 'Poster name: ' . $poster->getName());

                if ($poster->hasMoved()) {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'File sudah diproses sebelumnya.'
                    ]);
                }

                if ($poster && $poster->isValid()) {
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
                }

                $teaterData = [
                    'tipe_teater'     => $data['tipe_teater'],
                    'judul'           => $data['judul'],
                    'poster'          => $posterUrl,
                    'sinopsis'        => $data['sinopsis'] ?? null,
                    'penulis'         => $data['penulis'],
                    'sutradara'       => $data['sutradara'],
                    'dibuat_oleh'     => $user['nama'],
                    'dimodif_oleh'    => $isEdit ? $user['nama'] : null,
                    'url_pendaftaran' => $data['url_pendaftaran']
                ];

                if ($this->request->getPost('atur_periode')) {
                    $teaterData['daftar_mulai'] = $this->request->getPost('daftar_mulai');
                    $teaterData['daftar_berakhir'] = $this->request->getPost('daftar_berakhir');
                } else {
                    $teaterData['daftar_mulai'] = null;
                    $teaterData['daftar_berakhir'] = null;
                }

                log_message('debug', 'Request data: ' . json_encode($this->request->getPost()));

                if ($isEdit) {
                    $teaterData['id_teater'] = $data['id_teater']; // diperlukan agar update, bukan insert
                }

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

                $idTeater = $isEdit ? $data['id_teater'] : $this->teaterModel->getInsertID();

                if (!$idTeater) {
                    $db->transRollback();
                    log_message('error', 'Gagal mendapatkan ID teater setelah insert.');

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mendapatkan ID teater.'
                    ]);
                }

                log_message('debug', 'ID Teater yang dibuat: ' . $idTeater);

                // Simpan relasi user dengan teater ke r_user_teater
                $userTeaterData = [
                    'id_user' => $userId,
                    'id_teater' => $idTeater
                ];

                if (!$isEdit) {
                    $idUserTeater = $this->userTeaterModel->insert($userTeaterData);
                    if (!$idUserTeater) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal menyimpan data relasi user dengan teater.'
                        ]);
                    }
                }

                log_message('debug', 'ID relasi user dengan teater yang dibuat: ' . $idUserTeater);

                // Ambil input gaji dan status checkbox
                $gajiInput = $this->request->getPost('gaji');
                $isGajiDirahasiakan = $this->request->getPost('gaji_dirahasiakan');

                if ($isGajiDirahasiakan) {
                    $gaji = null;
                    $statusGaji = 'secret';
                } elseif (!empty($gajiInput)) {
                    $gaji = $gajiInput;
                    $statusGaji = 'shown';
                } else {
                    $gaji = null;
                    $statusGaji = 'no';
                }

                // ✅ Simpan ke m_audisi
                $audisiData = [
                    'id_teater'   => $idTeater,
                    'id_kategori' => $data['id_kategori'],
                    'syarat'      => $data['syarat'],
                    'syarat_dokumen' => $data['syarat_dokumen'] ?? null,
                    'gaji'           => $gaji,
                    'status_gaji'   => $statusGaji,
                    'komitmen'  => $data['komitmen'] ?? null
                ];

                // Tambahkan ID saat edit agar `save()` menjadi update
                if ($isEdit) {
                    $audisiData['id_audisi'] = $data['id_audisi']; // pastikan 'id_audisi' ada di form saat edit
                }

                if (!$this->audisiModel->save($audisiData)) {
                    $db->transRollback();
                    log_message('error', 'Gagal menyimpan ke database: ' . json_encode($this->audisiModel->errors()));

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->audisiModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($audisiData));

                // Ambil ID audisi hanya jika mode tambah
                if (!$isEdit) {
                    $idAudisi = $this->audisiModel->getInsertID();

                    if (!$idAudisi) {
                        $db->transRollback();
                        log_message('error', 'Gagal mendapatkan ID Audisi setelah insert.');

                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Gagal mendapatkan ID Audisi.'
                        ]);
                    }

                    log_message('debug', 'ID Audisi yang dibuat: ' . $idAudisi);
                } else {
                    $idAudisi = $data['id_audisi']; // ID yang diedit
                    log_message('debug', 'Update data Audisi ID: ' . $idAudisi);
                }

                // ✅ Simpan ke m_audisi_staff
                $staffData = [
                    'id_audisi'     => $idAudisi,
                    'jenis_staff'  => $data['jenis_staff'] ?? null,
                    'jobdesc_staff' => $data['jobdesc_staff'] ?? null
                ];

                // Tambahkan ID saat edit agar save() menjadi update
                if ($isEdit) {
                    $staffData['id'] = $data['id_staff_audisi']; // ID primary key dari m_audisi_aktor
                }

                if (!$this->audisiStaffModel->save($staffData)) {
                    $db->transRollback();

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors'  => $this->audisiStaffModel->errors()
                    ]);
                }

                log_message('debug', 'Data yang diterima: ' . json_encode($staffData));

                if (!$isEdit) {
                    $idStaff = $this->audisiStaffModel->getInsertID();

                    if (!$idStaff) {
                        $db->transRollback();
                        log_message('error', 'Gagal mendapatkan ID Staff setelah insert.');

                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Gagal mendapatkan ID Staff.'
                        ]);
                    }

                    log_message('debug', 'ID Staff yang dibuat: ' . $idStaff);
                } else {
                    $idStaff = $data['id_staff_audisi'];
                    log_message('debug', 'Update data Audisi Staff ID: ' . $idStaff);
                }

                // ✅ Simpan Jadwal Audisi ke m_show_schedule
                $hiddenSchedule = json_decode($data['hidden_schedule'], true);
                $deleted = json_decode($data['deleted_schedules'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($deleted)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format data deleted_schedules tidak valid.']);
                }

                foreach ($deleted as $id_schedule) {
                    $relasi = $this->db->table('r_audisi_schedule')
                        ->where('id_schedule', $id_schedule)
                        ->get()
                        ->getRow();

                    if ($relasi) {
                        $pricingId = $relasi->id_pricing_audisi;

                        // Hapus relasi dulu
                        $this->db->table('r_audisi_schedule')
                            ->where('id_schedule', $id_schedule)
                            ->delete();

                        // Hapus jadwalnya
                        $this->showScheduleModel->delete($id_schedule);

                        // Cek apakah pricing masih dipakai
                        $stillUsed = $this->db->table('r_audisi_schedule')
                            ->where('id_pricing_audisi', $pricingId)
                            ->countAllResults();

                        if ($stillUsed === 0) {
                            // Sudah tidak dipakai, aman dihapus
                            $this->db->table('m_audisi_schedule')
                                ->where('id', $pricingId)
                                ->delete();
                        }
                    }
                }

                if (empty($hiddenSchedule)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal audisi tidak boleh kosong.']);
                }

                foreach ($hiddenSchedule as $index => $schedule) {
                    $tanggal = $schedule['tanggal'];
                    $waktu_mulai = $schedule['waktu_mulai'];
                    $waktu_selesai = $schedule['waktu_selesai'];
                    $tempat = $schedule['tempat'];
                    $kota = $schedule['kota'];
                    $tipe_harga = isset($schedule['tipe_harga']) && $schedule['tipe_harga'] === 'Gratis' ? 'Gratis' : 'Bayar';
                    $harga = isset($schedule['harga']) ? $schedule['harga'] : null;

                    log_message('debug', "Jadwal ke-$index: Tanggal: $tanggal, Mulai: $waktu_mulai, Selesai: $waktu_selesai, Kota: $kota, Tempat: $tempat, Harga: $harga");

                    $locationData = [
                        'tempat' => $tempat,
                        'kota' => $kota,
                    ];

                    log_message('debug', 'Data lokasi yang akan disimpan: ' . json_encode($locationData));

                    $existingLocation = $this->lokasiTeaterModel
                        ->where('tempat', $tempat)
                        ->where('kota', $kota)
                        ->first();

                    if ($existingLocation) {
                        $idLocation = $existingLocation['id_location'];
                    } else {
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
                        if (!isset($schedule['harga']) || !is_numeric($schedule['harga'])) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Data harga tidak valid.']);
                        }

                        $auditionPricingData = [
                            'id_audisi'    => $idAudisi,
                            'tipe_harga'       => 'Bayar',
                            'harga'            => $harga,
                        ];

                        $this->audisiScheduleModel->save($auditionPricingData);
                        $idAuditionPricing = $this->audisiScheduleModel->getInsertID();

                        if ($idSchedule && $idAuditionPricing) {
                            if (!$this->db->table('r_audisi_schedule')->insert([
                                'id_schedule' => $idSchedule,
                                'id_pricing_audisi'  => $idAuditionPricing
                            ])) {
                                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan relasi jadwal dan harga.']);
                            }
                        }
                    } elseif ($schedule['tipe_harga'] === 'Gratis') {
                        $auditionPricingData = [
                            'id_audisi'    => $idAudisi,
                            'tipe_harga'       => 'Gratis',
                            'harga'            => null
                        ];

                        if (!$this->audisiScheduleModel->save($auditionPricingData)) {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan harga gratis.']);
                        }

                        $idAuditionPricing = $this->audisiScheduleModel->getInsertID();

                        if ($idSchedule && $idAuditionPricing) {
                            if (!$this->db->table('r_audisi_schedule')->insert([
                                'id_schedule' => $idSchedule,
                                'id_pricing_audisi'  => $idAuditionPricing
                            ])) {
                                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan relasi jadwal dan harga.']);
                            }
                        }
                    }

                    log_message('debug', 'Data jadwal diterima: ' . json_encode($hiddenSchedule));
                }

                // **7. Simpan sosial media teater ke r_teater_sosmed**
                $deletedAccounts = json_decode($data['deleted_accounts'], true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($deletedAccounts)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format data deleted_accounts tidak valid.']);
                }

                foreach ($deletedAccounts as $id) {
                    $this->sosmedModel->delete($id); // atau ->where('id_teater_sosmed', $id)->delete();
                }

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
                $deletedWebs = json_decode($data['deleted_webs'], true);
                foreach ($deletedWebs as $id) {
                    $this->teaterWebModel->delete($id);
                }

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

                $db->transCommit();
                return $this->response->setJSON([
                    'success'  => true,
                    'message' => $isEdit ? 'Audisi Staff Teater berhasil diperbarui!' : 'Audisi Staff Teater berhasil ditambahkan!',
                    'id_teater' => $idTeater,
                    'redirect' => base_url('MitraTeater/crudAudisi') // Tambahkan URL redirect
                ]);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'errors'  => $e->getMessage(), // Debug untuk melihat validasi gagal
                'trace' => $e->getTraceAsString()
            ]);
        }
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

    public function editAudisiAktor($id_teater)
    {
        if (!session()->has('id_user')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi login Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        // Cek user dari session
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

        // 1. Ambil data teater audisi
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->where('tipe_teater', 'audisi')
            ->first();

        if (!$teater) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Teater audisi tidak ditemukan.'
            ]);
        }

        // 2. Ambil data audisi utama
        $audisi = $this->audisiModel
            ->where('id_teater', $teater['id_teater'])
            ->first();

        $aktorAudisi = null;
        $jadwalAudisi = [];
        $sosmed = [];
        $website = [];

        if ($audisi) {
            $id_audisi = $audisi['id_audisi'];

            // Step 3: karakter
            $aktorAudisi = $this->audisiAktorModel
                ->where('id_audisi', $audisi['id_audisi'])
                ->first();

            // 4. Sosmed
            $sosmed = $this->sosmedModel
                ->select('m_platform_sosmed.id_platform_sosmed, m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
                ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
                ->where('r_teater_sosmed.id_teater', $id_teater)
                ->findAll();

            // 5. Website
            $website = $this->teaterWebModel
                ->where('id_teater', $teater['id_teater'])
                ->findAll();

            // 6. Jadwal + harga
            $jadwalAudisi = $this->audisiPricingModel
                ->select('
            r_audisi_schedule.id_schedule as id,
            m_show_schedule.tanggal,
            m_show_schedule.waktu_mulai,
            m_show_schedule.waktu_selesai,
            m_lokasi_teater.kota,
            m_lokasi_teater.tempat,
            m_audisi_schedule.harga,
            m_audisi_schedule.tipe_harga
        ')
                ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
                ->join('m_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
                ->join('m_audisi', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
                ->where('m_audisi.id_audisi', $id_audisi)
                ->orderBy('m_show_schedule.tanggal ASC')
                ->orderBy('m_show_schedule.waktu_mulai ASC')
                ->findAll();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'mode' => 'edit',
            'data' => [
                'user' => $user,
                'teater' => $teater,
                'audisi' => $audisi,
                'aktorAudisi' => $aktorAudisi,
                'sosmed' => $sosmed,
                'website' => $website,
                'jadwal' => $jadwalAudisi
            ]
        ]);
    }

    public function deleteAudisiByTeater()
    {
        $idTeater = $this->request->getPost('id_teater');

        // 1. Ambil semua id_audisi dari teater
        $idAudisiList = $this->audisiModel
            ->where('id_teater', $idTeater)
            ->findColumn('id_audisi');

        if (!empty($idAudisiList)) {
            // 2. Ambil semua id_pricing_audisi dari m_audisi_schedule
            $pricingIds = $this->audisiScheduleModel
                ->whereIn('id_audisi', $idAudisiList)
                ->findColumn('id_pricing_audisi');

            if (!empty($pricingIds)) {
                // 3. Ambil semua id_schedule dari relasi r_audisi_schedule
                $scheduleIds = $this->audisiPricingModel // ini model r_audisi_schedule
                    ->whereIn('id_pricing_audisi', $pricingIds)
                    ->findColumn('id_schedule');

                // 4. Hapus data di r_audisi_schedule
                $this->audisiPricingModel
                    ->whereIn('id_pricing_audisi', $pricingIds)
                    ->delete();

                // 5. Hapus data di m_audisi_schedule
                $this->audisiScheduleModel
                    ->whereIn('id_pricing_audisi', $pricingIds)
                    ->delete();
            }

            // 6. Hapus data karakter audisi (jika ada)
            $this->audisiAktorModel
                ->whereIn('id_audisi', $idAudisiList)
                ->delete();

            $this->audisiStaffModel
                ->whereIn('id_audisi', $idAudisiList)
                ->delete();

            // 7. Hapus data utama audisi
            $this->audisiModel
                ->whereIn('id_audisi', $idAudisiList)
                ->delete();
        }

        // 8. Hapus show_schedule berdasarkan teater
        $this->showScheduleModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 9. Hapus data lain terkait teater
        $this->teaterWebModel
            ->where('id_teater', $idTeater)
            ->delete();

        $this->sosmedModel
            ->where('id_teater', $idTeater)
            ->delete();

        $this->userTeaterModel
            ->where('id_teater', $idTeater)
            ->delete();

        // 10. Hapus data teater utama
        $this->teaterModel
            ->delete($idTeater);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Seluruh data audisi dan teater berhasil dihapus.'
        ]);
    }

    public function editAudisiStaff($id_teater)
    {
        if (!session()->has('id_user')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi login Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        // Cek user dari session
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

        // 1. Ambil data teater audisi
        $teater = $this->teaterModel
            ->where('id_teater', $id_teater)
            ->where('tipe_teater', 'audisi')
            ->first();

        if (!$teater) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Teater audisi tidak ditemukan.'
            ]);
        }

        // 2. Ambil data audisi utama
        $audisi = $this->audisiModel
            ->where('id_teater', $teater['id_teater'])
            ->first();

        $staffAudisi = null;
        $jadwalAudisi = [];
        $sosmed = [];
        $website = [];

        if ($audisi) {
            $id_audisi = $audisi['id_audisi'];

            // Step 3: karakter
            $staffAudisi = $this->audisiStaffModel
                ->where('id_audisi', $audisi['id_audisi'])
                ->first();

            // 4. Sosmed
            $sosmed = $this->sosmedModel
                ->select('m_platform_sosmed.id_platform_sosmed, m_platform_sosmed.platform_name, r_teater_sosmed.acc_teater')
                ->join('m_platform_sosmed', 'r_teater_sosmed.id_platform_sosmed = m_platform_sosmed.id_platform_sosmed')
                ->where('r_teater_sosmed.id_teater', $id_teater)
                ->findAll();

            // 5. Website
            $website = $this->teaterWebModel
                ->where('id_teater', $teater['id_teater'])
                ->findAll();

            // 6. Jadwal + harga
            $jadwalAudisi = $this->audisiPricingModel
                ->select('
            r_audisi_schedule.id_schedule as id,
            m_show_schedule.tanggal,
            m_show_schedule.waktu_mulai,
            m_show_schedule.waktu_selesai,
            m_lokasi_teater.kota,
            m_lokasi_teater.tempat,
            m_audisi_schedule.harga,
            m_audisi_schedule.tipe_harga
        ')
                ->join('m_show_schedule', 'r_audisi_schedule.id_schedule = m_show_schedule.id_schedule')
                ->join('m_lokasi_teater', 'm_show_schedule.id_location = m_lokasi_teater.id_location')
                ->join('m_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
                ->join('m_audisi', 'm_audisi_schedule.id_audisi = m_audisi.id_audisi')
                ->where('m_audisi.id_audisi', $id_audisi)
                ->orderBy('m_show_schedule.tanggal ASC')
                ->orderBy('m_show_schedule.waktu_mulai ASC')
                ->findAll();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'mode' => 'edit',
            'data' => [
                'teater' => $teater,
                'audisi' => $audisi,
                'staffAudisi' => $staffAudisi,
                'sosmed' => $sosmed,
                'website' => $website,
                'jadwal' => $jadwalAudisi
            ]
        ]);
    }

    // public function cekStatusView()
    // {
    //     return view('templates/headerUser', ['title' => 'Cek Status Akun Mitra Teater']) .
    //         view('templates/cekStatus') .
    //         view('templates/footer');
    // }

    // public function cekStatus()
    // {
    //     $email = $this->request->getPost('email');

    //     if (!$email) {
    //         return redirect()->to(base_url('MitraTeater/cekStatusView'))
    //             ->with('error', 'Silakan masukkan email.');
    //     }

    //     $mitra = $this->mitraModel
    //         ->select('m_mitra.approval_status, m_mitra.alasan')
    //         ->join('m_user', 'm_user.id_user = m_mitra.id_user')
    //         ->where('m_user.email', $email)
    //         ->where('m_user.id_role', 2)
    //         ->first();

    //     if ($mitra) {
    //         $message = match ($mitra['approval_status']) {
    //             'approved' => 'Akun Anda telah disetujui!',
    //             'rejected' => 'Akun Anda ditolak. Alasan: ' . $mitra['alasan'],
    //             default => 'Akun Anda masih dalam proses verifikasi.',
    //         };

    //         $alertClass = match ($mitra['approval_status']) {
    //             'approved' => 'alert-success',
    //             'rejected' => 'alert-danger',
    //             default => 'alert-warning',
    //         };

    //         return redirect()->to(base_url('MitraTeater/cekStatusView'))
    //             ->with('status', $message)
    //             ->with('class', $alertClass);
    //     }

    //     return redirect()->to(base_url('MitraTeater/cekStatusView'))
    //         ->with('error', 'Email tidak ditemukan atau bukan akun mitra.');
    // }

    public function listMitraTeater()
    {
        // Ambil data user dari session (misal data user disimpan di session setelah login)
        $userId = session()->get('id_user'); // Misalnya user_id disimpan di session setelah login
        $user = $this->userModel->find($userId); // Ambil data user berdasarkan user_id

        // Ambil data mitra teater dengan informasi user (nama)
        $mitraList = $this->mitraModel->getApprovedMitraWithUser();

        // Kirim data ke view
        return view('templates/headerMitra', ['title' => 'Daftar Mitra Teater', 'user' => $user]) .
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
        return view('templates/headerMitra', ['title' => 'Detail Mitra Teater', 'user' => $user]) .
            view('templates/detailMitraTeater', [
                'mitra' => $mitra,
                'sosial_media' => $sosial_media
            ]) .
            view('templates/footer');
    }

    public function profile()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return  view('templates/headerMitra', ['title' => 'Profile Mitra Teater', 'user' => $user]) .
            view('templates/profileUser', ['user' => $user]) .
            view('templates/footer');
    }

    public function aboutUs()
    {
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);

        return view('templates/headerMitra', ['title' => 'Tentang Kami - Theaterform', 'user' => $user]) .
            view('templates/aboutUs', ['user' => $user]) .
            view('templates/footer');
    }
}
