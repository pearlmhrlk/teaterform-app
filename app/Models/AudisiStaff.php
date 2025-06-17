<?php

namespace App\Models;

use CodeIgniter\Model;

class AudisiStaff extends Model
{
    protected $table = 'm_audisi_staff';
    protected $primaryKey = 'id_staff_audisi';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_audisi',
        'jenis_staff',
        'jobdesc_staff'
    ];

    protected $useTimestamps = false;
}