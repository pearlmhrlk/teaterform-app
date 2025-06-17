<?php

namespace App\Models;

use CodeIgniter\Model;

class DenahSeatModel extends Model
{
    protected $table            = 'm_denah_seat';
    protected $primaryKey       = 'id_denah';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'id_teater',
        'id_location',
        'denah_seat'
    ];
}
