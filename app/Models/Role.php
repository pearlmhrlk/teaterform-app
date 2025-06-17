<?php

namespace App\Models;

use CodeIgniter\Model;

class Role extends Model
{
    protected $table = 'm_role';
    protected $primaryKey = 'id_role';
    protected $allowedFields = ['role_name', 'keterangan_role'];

    // Jika kamu ingin menggunakan validasi tambahan
    protected $validationRules = [
        'id_role' => 'required|in_list[1,2,3]',
        'role_name' => 'required|string|max_length[25]',
        'keterangan_role' => 'required|string|max_length[100]'
    ];
}