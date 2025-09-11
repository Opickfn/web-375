<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    protected $table = 'laporan'; // pastikan nama tabelmu "laporan"

    // ================== AMBIL SEMUA LAPORAN ==================
    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    // ================== AMBIL LAPORAN BY ID ==================
    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    // ================== UPDATE LAPORAN ==================
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // ================== TAMBAH LAPORAN BARU ==================
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // ================== HAPUS LAPORAN ==================
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
