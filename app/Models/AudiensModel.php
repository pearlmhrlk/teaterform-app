<?php

namespace App\Models;

use CodeIgniter\Model;

class AudiensModel extends Model
{
    protected $table = 'm_audiens';
    protected $primaryKey = 'id_audiens'; // Kolom ID untuk Audiens
    protected $allowedFields = ['id_user', 'tgl_lahir', 'gender']; // Data yang bisa diinsert
    
    // Jika kamu ingin menggunakan validasi tambahan
    protected $validationRules = [
        'gender' => 'required|in_list[male,female]',
        'tgl_lahir' => 'required|valid_date',
    ];
}