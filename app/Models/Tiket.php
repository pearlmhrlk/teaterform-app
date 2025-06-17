<?php

namespace App\Models;

use CodeIgniter\Model;

class Tiket extends Model
{
    protected $table = 'm_tiket';
    protected $primaryKey = 'id_tiket';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_user',
        'id_teater',
        'issue_date',
        'nama_audiens',
        'judul',
        'waktu',
        'tempat',
        'jenis_teater'
    ];
}