<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTeater extends Model
{
    protected $table = 'r_user_teater';
    protected $primaryKey = 'id_user_teater';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_teater',
        'id_user',
        'tgl_akses'
    ];
}
