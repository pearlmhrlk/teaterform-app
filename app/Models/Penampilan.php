<?php

namespace App\Models;

use CodeIgniter\Model;

class Penampilan extends Model
{
    protected $table = 'm_penampilan';
    protected $primaryKey = 'id_penampilan';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_teater',
        'aktor',
        'durasi',
        'rating_umur'
    ];

    protected $useTimestamps = false;
}