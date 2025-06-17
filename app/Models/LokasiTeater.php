<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiTeater extends Model
{
    protected $table = 'm_lokasi_teater';
    protected $primaryKey = 'id_location';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'tempat',
        'kota'
    ];

    protected $useTimestamps = false;
}
