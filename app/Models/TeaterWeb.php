<?php

namespace App\Models;

use CodeIgniter\Model;

class TeaterWeb extends Model
{
    protected $table = 'm_teater_web';
    protected $primaryKey = 'id_teater_web';
    protected $allowedFields = ['id_teater', 'judul_web', 'url_web']; // Data yang bisa diinsert  
}
