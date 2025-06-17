<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraSosmedModel extends Model
{
    protected $table = 'r_mitra_sosmed';
    protected $primaryKey = 'id_mitra_sosmed';
    protected $allowedFields = [
        'id_mitra',
        'id_platform_sosmed',
        'acc_mitra'
    ];

    public function getSosmedByMitraId($id_mitra)
    {
        return $this->select('m_platform_sosmed.platform_name, r_mitra_sosmed.acc_mitra')
            ->join('m_platform_sosmed', 'm_platform_sosmed.id_platform_sosmed = r_mitra_sosmed.id_platform_sosmed', 'left')
            ->where('r_mitra_sosmed.id_mitra', $id_mitra)
            ->findAll();
    }
}
