<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\Controller;

class Login extends BaseController
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User(); // Pastikan UserModel sudah ada
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $usernameOrEmail = trim($this->request->getPost('username')); // Bisa username atau email
            $password = trim($this->request->getPost('password'));

            // Validasi input tidak boleh kosong
            if (empty($usernameOrEmail) || empty($password)) {
                session()->setFlashdata('error', 'Username/Email dan Password harus diisi.');
                return redirect()->to(base_url('User/login'));
            }

            // Cari user berdasarkan username atau email
            $user = $this->userModel->where('username', $usernameOrEmail)
                ->orWhere('email', $usernameOrEmail)
                ->first();

            if ($user) {
                // Jika pengguna adalah mitra teater, cek statusnya di tabel m_mitra
                if ($user['id_role'] == 2) { // id_role 2 = Mitra Teater
                    $mitraModel = new \App\Models\MitraModel();
                    $mitra = $mitraModel->where('id_user', $user['id_user'])->first();

                    if ($mitra) {
                        if ($mitra['approval_status'] == 'pending') {
                            session()->setFlashdata('error', 'Akun Anda masih dalam proses verifikasi oleh admin.');
                            return redirect()->to(base_url('User/login'));
                        } elseif ($mitra['approval_status'] == 'rejected') {
                            session()->setFlashdata('error', 'Akun Anda ditolak. Alasan: ' . $mitra['alasan']);
                            return redirect()->to(base_url('User/login'));
                        }
                    } else {
                        session()->setFlashdata('error', 'Akun Mitra tidak ditemukan.');
                        return redirect()->to(base_url('User/login'));
                    }
                }

                // Verifikasi password
                if (password_verify($password, $user['password'])) {

                    $this->userModel->update($user['id_user'], [
                        'is_logged_in' => 1,
                        'login_attempt' => $user['login_attempt'] + 1
                    ]);

                    // Set session berdasarkan id_role
                    $sessionData = [
                        'id_user'       => $user['id_user'],
                        'username'      => $user['username'],
                        'id_role'       => $user['id_role'],
                        'nama'          => $user['nama'],
                        'email'         => $user['email'],
                        'is_logged_in'  => 1
                    ];
                    session()->set($sessionData);

                    // Redirect berdasarkan role
                    switch ($user['id_role']) {
                        case '1': // Audiens
                            return redirect()->to(base_url('Audiens/homepageAudiens'));
                        case '2': // Mitra Teater
                            return redirect()->to(base_url('Mitra/homepage'));
                        case '3': // Admin
                            return redirect()->to(base_url('Admin/homepage'));
                        default:
                            session()->setFlashdata('error', 'Role tidak dikenali.');
                            return redirect()->to(base_url('User/login'));
                    }
                } else {
                    session()->setFlashdata('error', 'Password salah.');
                    return redirect()->to(base_url('User/login'));
                }
            } else {
                session()->setFlashdata('error', 'Username tidak ditemukan.');
                return redirect()->to(base_url('User/login'));
            }
        }

        return view('templates/headerLogin') .
            view('templates/bodyLogin');
    }

    public function logout()
    {
        $id_user = session()->get('id_user');
        if ($id_user) {
            $user = $this->userModel->find($id_user);

            if ($user) { // Pastikan user ada sebelum update
                $this->userModel->update($id_user, ['is_logged_in' => 0]);
            }
        }

        session()->destroy();
        return redirect()->to(base_url('User/login'));
    }
}
