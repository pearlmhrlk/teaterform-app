<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table = 'm_mitra';
    protected $primaryKey = 'id_mitra';
    protected $allowedFields = [
        'id_user',
        'alamat',
        'berdiri_sejak',
        'deskripsi',
        'logo',
        'history_show',
        'prestasi',
        'approval_status',
        'tgl_approved',
        'alasan'
    ];

    protected $validationRules = [
        'alamat'        => 'required',
        'berdiri_sejak' => 'required|valid_date',
        'deskripsi'     => 'required',
    ];

    public function getPendingMitra($limit, $offset)
    {
        return $this->select("m_mitra.*, 
                                m_user.nama, 
                                m_user.username, 
                                m_user.email")
            ->join('m_user', 'm_user.id_user = m_mitra.id_user')
            ->where('m_mitra.approval_status', 'pending')
            ->groupBy('m_mitra.id_mitra')
            ->limit($limit, $offset)
            ->find();
    }

    // Fungsi untuk menghitung total akun mitra yang masih pending
    public function countPendingMitra()
    {
        return $this->where('approval_status', 'pending')->countAllResults();
    }

    // Fungsi untuk menyetujui akun
    public function approveMitra($id_mitra)
    {
        return $this->update($id_mitra, [
            'approval_status' => 'approved',
            'tgl_approved'    => date('Y-m-d H:i:s')
        ]);
    }

    public function rejectMitra($id_mitra, $alasan)
    {

        log_message('debug', 'Reject Mitra: id_mitra=' . $id_mitra . ', alasan=' . $alasan);

        $update = $this->update($id_mitra, [
            'approval_status' => 'rejected',
            'alasan' => $alasan
        ]);

        if (!$update) {
            log_message('error', 'Gagal update mitra dengan id: ' . $id_mitra);
        }

        return $update;
    }

    public function getApprovedMitraWithUser()
    {
        return $this->db->table('m_mitra')
            ->select('m_mitra.*, m_user.nama AS nama_teater')
            ->join('m_user', 'm_user.id_user = m_mitra.id_user')
            ->where('m_mitra.approval_status', 'approved')
            ->get()
            ->getResultArray();
    }

    public function getMitraDetail($id)
    {
        return $this->db->table('m_mitra')
            ->select('m_mitra.*, m_user.nama AS nama_teater, m_user.email')
            ->join('m_user', 'm_user.id_user = m_mitra.id_user', 'left')
            ->where('m_mitra.id_mitra', $id)
            ->get()
            ->getRowArray();
    }

    public function getMitraSosmed($id_mitra)
    {
        return $this->db->table('r_mitra_sosmed')
            ->select('r_mitra_sosmed.acc_mitra, m_platform_sosmed.platform_name')
            ->join('m_platform_sosmed', 'm_platform_sosmed.id_platform_sosmed = r_mitra_sosmed.id_platform_sosmed')
            ->where('r_mitra_sosmed.id_mitra', $id_mitra)
            ->get()
            ->getResultArray();
    }
}
