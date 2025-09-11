<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard2 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        // Hitung total laporan
        $data['total'] = $this->db->count_all('laporan');

    // Hitung laporan dengan status tertentu
        $data['aktif']   = $this->db->where('status', 'Aktif')->count_all_results('laporan');
        $data['selesai'] = $this->db->where('status', 'Selesai')->count_all_results('laporan');
        $data['review']  = $this->db->where('status', 'Menunggu Review')->count_all_results('laporan');


        // Ambil 5 laporan terbaru
        $data['latest'] = $this->db
            ->order_by('created_at', 'DESC')
            ->limit(5)
            ->get('laporan')
            ->result();

        // Kirim data ke view
        $this->load->view('dashboard2', $data);
    }
}
