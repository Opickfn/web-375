<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
    }

    // ================== LIST LAPORAN ==================
    public function index()
    {
        $data['laporan'] = $this->Reports_model->get_all();
        $this->load->view('reports/index', $data);
    }

    // ================== DETAIL LAPORAN ==================
    public function detail($id)
    {
        $data['lap'] = $this->Reports_model->get_by_id($id);
        $this->load->view('reports/detail_modal', $data);
    }

    // ================== FORM PENYELESAIAN ==================
    public function resolve_form($id)
    {
        $data['lap'] = $this->Reports_model->get_by_id($id);
        $this->load->view('reports/resolve_view', $data);
    }

    // ================== PROSES PENYELESAIAN ==================
    public function resolve($id)
    {
        $post = $this->input->post();

        $data = [
            'penyelesai'   => $post['penyelesai'],
            'tindakan'     => $post['tindakan'],
            'detail'       => $post['detail'],
            'pencegahan'   => $post['pencegahan'],
            'tanggal_selesai' => $post['tanggal'],
            'verifikasi'   => $post['verifikasi'],
            'status'       => 'Selesai'
        ];

        $this->Reports_model->update($id, $data);

        echo "<script>
            alert('Laporan #LP".str_pad($id,3,'0',STR_PAD_LEFT)." berhasil diselesaikan');
            window.location='".site_url('reports')."';
        </script>";
    }

    // ================== EXPORT LAPORAN ==================
    public function export($id)
    {
        $data['lap'] = $this->Reports_model->get_by_id($id);
        $this->load->view('reports/export', $data);
    }

}
