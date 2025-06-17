<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriAudisi extends Model
{
    protected $table = 'm_kategori_audisi';
    protected $primaryKey = 'id_kategori';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nama_kategori',
        'keterangan_kategori',
        'tgl_dibuat',
        'tgl_dimodif'
    ];
}
