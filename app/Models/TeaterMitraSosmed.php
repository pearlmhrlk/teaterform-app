<?php

namespace App\Models;

use CodeIgniter\Model;

class TeaterMitraSosmed extends Model
{
    protected $table = 'm_teater_mitra_sosmed';
    protected $primaryKey = 'id_teater_mitra_sosmed';
    protected $allowedFields = ['id_mitra_sosmed', 'id_teater_sosmed']; // Data yang bisa diinsert  
}
