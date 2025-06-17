<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatformSosmedModel extends Model
{
    protected $table = 'm_platform_sosmed';
    protected $primaryKey = 'id_platform_sosmed';
    protected $allowedFields = ['id_platform_sosmed', 'platform_name']; // Data yang bisa diinsert  
}
