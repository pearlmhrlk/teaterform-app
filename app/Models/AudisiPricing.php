<?php

namespace App\Models;

use CodeIgniter\Model;

class AudisiPricing extends Model
{
    protected $table = 'r_audisi_schedule';
    protected $primaryKey = 'id_audisi_schedule';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_schedule',
        'id_pricing_audisi'
    ];

    protected $useTimestamps = false;

    // File: app/Models/AudisiScheduleModel.php
    public function getScheduleWithPrice($id_audisi_schedule)
    {
        return $this->db->table('r_audisi_schedule')
            ->select('r_audisi_schedule.*, m_audisi_schedule.harga')
            ->join('m_audisi_schedule', 'r_audisi_schedule.id_pricing_audisi = m_audisi_schedule.id_pricing_audisi')
            ->where('r_audisi_schedule.id_audisi_schedule', $id_audisi_schedule)
            ->get()
            ->getRowArray();
    }
}
