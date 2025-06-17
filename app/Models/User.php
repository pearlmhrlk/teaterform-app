<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'm_user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['id_role', 'username', 'nama', 'password', 'email', 'login_attempt', 'tgl_dibuat', 'tgl_dimodif', 'is_logged_in'];
    protected $useTimestamps = true; // Untuk mengatur auto timestamp tgl_dibuat dan tgl_dimodif
    protected $createdField  = 'tgl_dibuat';
    protected $updatedField  = 'tgl_dimodif';
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[15]',
        'email' => 'required|valid_email',
        'password' => 'required|min_length[6]',
    ];
    protected $validationMessages = [
        'username' => [
            'min_length' => 'Username harus memiliki minimal 3 karakter',
            'max_length' => 'Username tidak boleh lebih dari 15 karakter',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
        ],
        'password' => [
            'min_length' => 'Password harus memiliki minimal 6 karakter',
        ],
    ];

    /**
     * Periksa apakah username sudah ada
     *
     * @param string $username
     * @return bool
     */
    public function isUniqueUsername($username, $roleId)
    {
        return $this->where(['username' => $username, 'id_role' => $roleId])->countAllResults() === 0;
    }

    public function isUniqueEmail($email, $roleId)
    {
        return $this->where(['email' => $email, 'id_role' => $roleId])->countAllResults() === 0;
    }
}
