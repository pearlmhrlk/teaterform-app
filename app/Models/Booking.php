<?php

namespace App\Models;

use CodeIgniter\Model;

class Booking extends Model
{
    protected $table = 't_booking';
    protected $primaryKey = 'id_booking';

    protected $allowedFields = [
        'id_audiens',     // sebelumnya id_user
        'id_jadwal',
        'tipe_jadwal',
        'is_free',
        'status',
        'bukti_pembayaran',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // kosongkan karena tidak ada kolom 'updated_at'
}
