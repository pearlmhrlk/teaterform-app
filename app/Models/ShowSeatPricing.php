<?php

namespace App\Models;

use CodeIgniter\Model;

class ShowSeatPricing extends Model
{
    protected $table = 'r_show_schedule';
    protected $primaryKey = 'id_schedule_show';
    protected $allowedFields = ['id_schedule', 'id_pricing', 'id_denah']; // Data yang bisa diinsert  

    public function getScheduleWithPrice($idShowSchedule)
    {
        return $this->db->table('r_show_schedule rss')
            ->select('s.*, p.harga, p.nama_kategori')
            ->join('m_show_schedule s', 's.id_schedule = rss.id_schedule')
            ->join('m_seat_pricing p', 'rss.id_pricing = p.id_pricing')
            ->where('rss.id_schedule_show', $idShowSchedule)
            ->get()
            ->getRowArray();
    }
}
