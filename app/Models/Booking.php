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
        'created_at',
        'isValid'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // kosongkan karena tidak ada kolom 'updated_at'

    public function getBookingDataAndCount($tipe, $id)
    {
        $builder = $this->db->table('t_booking')
            ->select('
            m_user.nama, 
            m_user.email, 
            m_audiens.tgl_lahir AS tanggal_lahir, 
            m_audiens.gender AS jenis_kelamin,
            m_show_schedule.tanggal, 
            m_show_schedule.waktu_mulai,
            m_show_schedule.waktu_selesai,
            t_booking.id_booking,
            t_booking.status,
            t_booking.is_free,
            t_booking.bukti_pembayaran,
            t_booking.created_at
        ')
            ->join('m_audiens', 'm_audiens.id_audiens = t_booking.id_audiens')
            ->join('m_user', 'm_user.id_user = m_audiens.id_user');

        if ($tipe === 'penampilan') {
            $builder
                ->join('r_show_schedule', 'r_show_schedule.id_schedule_show = t_booking.id_jadwal')
                ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_show_schedule.id_schedule')
                ->join('m_seat_pricing', 'm_seat_pricing.id_pricing = r_show_schedule.id_pricing')
                ->where('m_seat_pricing.id_penampilan', $id);
        } elseif ($tipe === 'audisi') {
            $builder
                ->join('r_audisi_schedule', 'r_audisi_schedule.id_audisi_schedule = t_booking.id_jadwal')
                ->join('m_show_schedule', 'm_show_schedule.id_schedule = r_audisi_schedule.id_schedule')
                ->join('m_audisi_schedule', 'm_audisi_schedule.id_pricing_audisi = r_audisi_schedule.id_pricing_audisi')
                ->where('m_audisi_schedule.id_audisi', $id);
        }

        $builder->where('t_booking.tipe_jadwal', $tipe);
        $data = $builder->get()->getResultArray();

        // Format data
        foreach ($data as &$row) {
            $row['tanggal_lahir'] = date('d-m-Y', strtotime($row['tanggal_lahir']));
            $row['jadwal'] = date('d-m-Y', strtotime($row['tanggal'])) . ', ' . substr($row['waktu_mulai'], 0, 5) . ' - ' . substr($row['waktu_selesai'], 0, 5);
            $row['bukti_pembayaran'] = $row['is_free'] ? 'Gratis' : ($row['bukti_pembayaran'] ?? '-');
            $row['tanggal_daftar'] = date('d-m-Y H:i', strtotime($row['created_at']));
        }

        // Hitung tiket sukses
        $countBuilder = $this->db->table('t_booking')
            ->where('t_booking.status', 'success')
            ->where('t_booking.tipe_jadwal', $tipe);

        if ($tipe === 'penampilan') {
            $countBuilder
                ->join('r_show_schedule', 'r_show_schedule.id_schedule_show = t_booking.id_jadwal')
                ->join('m_seat_pricing', 'm_seat_pricing.id_pricing = r_show_schedule.id_pricing')
                ->where('m_seat_pricing.id_penampilan', $id);
        } elseif ($tipe === 'audisi') {
            $countBuilder
                ->join('r_audisi_schedule', 'r_audisi_schedule.id_audisi_schedule = t_booking.id_jadwal')
                ->join('m_audisi_schedule', 'm_audisi_schedule.id_pricing_audisi = r_audisi_schedule.id_pricing_audisi')
                ->where('m_audisi_schedule.id_audisi', $id);
        }

        $tiket_terjual = $countBuilder->countAllResults();

        return [
            'data' => $data,
            'tiket_terjual' => $tiket_terjual
        ];
    }
}
