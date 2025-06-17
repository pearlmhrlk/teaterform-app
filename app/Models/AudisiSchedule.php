<?php

namespace App\Models;

use CodeIgniter\Model;

class AudisiSchedule extends Model
{
    protected $table = 'm_audisi_schedule';
    protected $primaryKey = 'id_pricing_audisi';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_audisi',
        'harga',
        'tipe_harga',
    ];

    protected $useTimestamps = false;
}
