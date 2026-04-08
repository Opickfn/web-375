<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Tambah laporan
    public function insert($data) {
        return $this->db->insert('laporan', $data);
    }

    // Update laporan
    public function update($id, $data) {
        return $this->db->where('id', $id)->update('laporan', $data);
    }

    // Hapus laporan
    public function delete($id) {
        return $this->db->where('id', $id)->delete('laporan');
    }

    // Ambil 1 laporan by ID
    public function getById($id) {
        return $this->db->where('id', $id)->get('laporan')->row();
    }

    // Ambil semua laporan dengan filter
    public function getAllLaporan($filter = [], $limit = 20, $offset = 0) {
        $this->db->from('laporan');

        if (!empty($filter['kategori'])) {
            $this->db->where('kategori', $filter['kategori']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('status', $filter['status']);
        }

        if (!empty($filter['q'])) {
            $this->db->group_start()
                     ->like('deskripsi', $filter['q'])
                     ->or_like('lokasi', $filter['q'])
                     ->group_end();
        }

       $this->db->order_by('"tanggal"', 'DESC');
        $this->db->limit($limit);
$this->db->offset($offset);

        return $this->db->get()->result();
    }

    // Hitung total laporan
    public function getTotalLaporan($filter = []) {
        $this->db->from('laporan');

        if (!empty($filter['kategori'])) {
            $this->db->where('kategori', $filter['kategori']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('status', $filter['status']);
        }

        if (!empty($filter['q'])) {
            $this->db->group_start()
                     ->like('deskripsi', $filter['q'])
                     ->or_like('lokasi', $filter['q'])
                     ->group_end();
        }

        return $this->db->count_all_results();
    }

    // Hitung laporan berdasarkan status
    public function countByStatus($status) {
        return $this->db->where('status', $status)
                        ->from('laporan')
                        ->count_all_results();
    }

    // Ambil laporan terbaru
    public function getLatest($limit = 5) {
return $this->db->order_by('"tanggal"', 'DESC')
                ->limit($limit)
                ->get('laporan')
                ->result();
    }
}
