<?php

namespace App\Models;

use CodeIgniter\Model;

class TeaterSosmed extends Model
{
    protected $table = 'r_teater_sosmed';
    protected $primaryKey = 'id_teater_sosmed';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_teater',
        'id_platform_sosmed',
        'acc_teater'
    ];

    protected $useTimestamps = false;
}
