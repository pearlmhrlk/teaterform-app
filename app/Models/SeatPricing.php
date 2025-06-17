<?php

namespace App\Models;

use CodeIgniter\Model;

class SeatPricing extends Model
{
    protected $table = 'm_seat_pricing';
    protected $primaryKey = 'id_pricing';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_penampilan',
        'tipe_harga',
        'harga',
        'nama_kategori'
    ];

    protected $useTimestamps = false;
}
