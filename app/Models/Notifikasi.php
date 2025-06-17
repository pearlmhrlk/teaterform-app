<?php

namespace App\Models;

use CodeIgniter\Model;

class Notifikasi extends Model
{
    protected $table = 'm_notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_user',
        'id_teater',
        'tipe_notifikasi',
        'status',
        'tgl_dibuat'
    ];
}
