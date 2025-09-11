<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelaporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('Laporan_model'); // load model laporan
    }

    // 🔹 Default: halaman awal (dashboard 1 / landing page)
    public function index() {
        $this->dashboard();
    }

    // 🔹 Halaman dashboard utama (landing page)
    public function dashboard() {
        $this->load->view('dashboard');  // ini file view landing page
    }

    // 🔹 Form buat laporan
    public function form() {
        $data['success'] = $this->session->flashdata('success');
        $this->load->view('form_laporan', $data);
    }

    // 🔹 Simpan laporan dari form
    public function simpan() {
        $data = [
            'kategori'  => $this->input->post('kategori'),
            'lokasi'    => $this->input->post('lokasi'),
            'deskripsi' => $this->input->post('deskripsi'),
            'prioritas' => $this->input->post('prioritas'),
            'status'    => 'Menunggu Review',
            'created_at'=> date('Y-m-d H:i:s')
        ];

        // upload file bukti (opsional)
        if (!empty($_FILES['bukti']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|png|mp4';
            $config['max_size']      = 10240; // 10MB
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti')) {
                $uploadData = $this->upload->data();
                $data['bukti'] = $uploadData['file_name'];
            }
        }

        $this->Laporan_model->insert($data);

        $this->session->set_flashdata('success', 'Laporan berhasil dikirim!');
        redirect('pelaporan/dashboard2'); // balik ke dashboard monitoring
    }

    // 🔹 Dashboard monitoring laporan
    public function dashboard2() {
        $data['total']   = $this->Laporan_model->getTotalLaporan();
        $data['aktif']   = $this->Laporan_model->countByStatus('Menunggu Review');
        $data['selesai'] = $this->Laporan_model->countByStatus('Diselesaikan');
        $data['review']  = $this->Laporan_model->countByStatus('Menunggu Review');
        $this->Laporan_model->getLatest();


        $this->load->view('dashboard2', $data);
    }

    // 🔹 Halaman semua reports
    public function reports() {
        $data['reports'] = $this->Laporan_model->getAllLaporan();
        $this->load->view('reports', $data);
    }
}
