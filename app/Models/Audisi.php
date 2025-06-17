<?php

namespace App\Models;

use CodeIgniter\Model;

class Audisi extends Model
{
    protected $table = 'm_audisi';
    protected $primaryKey = 'id_audisi';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_teater',
        'id_kategori',
        'syarat',
        'syarat_dokumen',
        'gaji',
        'status_gaji',
        'komitmen'
    ];

    protected $useTimestamps = false; // Tidak ada kolom created_at atau updated_at
}
