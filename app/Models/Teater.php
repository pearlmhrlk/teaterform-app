<?php

namespace App\Models;

use CodeIgniter\Model;

class Teater extends Model
{
    protected $table = 'm_teater';
    protected $primaryKey = 'id_teater';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'tipe_teater',
        'judul',
        'poster',
        'sinopsis',
        'penulis',
        'sutradara',
        'staff',
        'dibuat_oleh',
        'tgl_dibuat',
        'dimodif_oleh',
        'tgl_dimodif',
        'daftar_mulai',
        'daftar_berakhir'
    ];
}
