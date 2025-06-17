<?php

namespace App\Models;

use CodeIgniter\Model;

class ShowSchedule extends Model
{
    protected $table = 'm_show_schedule';
    protected $primaryKey = 'id_schedule';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_teater',
        'id_location',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai'
    ];

    protected $useTimestamps = false;
}
