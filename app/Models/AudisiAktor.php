<?php

namespace App\Models;

use CodeIgniter\Model;

class AudisiAktor extends Model
{
    protected $table = 'm_audisi_aktor';
    protected $primaryKey = 'id_aktor_audisi';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_audisi',
        'karakter_audisi',
        'deskripsi_karakter'
    ];

    protected $useTimestamps = false;
}
