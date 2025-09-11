<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // tampilkan form login
    public function login() {
        $this->load->view('auth/login');
    }

    // tampilkan form register
    public function register() {
        $this->load->view('auth/register');
    }

    // proses login
    public function proses_login() {
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('users', ['email' => $email])->row();

        if ($user) {
            if (password_verify($password, $user->password)) {
                // login sukses → simpan session
                $this->session->set_userdata([
                    'user_id'   => $user->id,
                    'email'     => $user->email,
                    'logged_in' => true
                ]);

                // arahkan ke dashboard2
                redirect('dashboard2');
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth/login');
            }
        } else {
            $this->session->set_flashdata('error', 'Akun belum terdaftar, silakan registrasi.');
            redirect('auth/register');
        }
    } // ← PASTIKAN fungsi ini ditutup di sini

    // proses register
    public function proses_register() {
        $nama     = $this->input->post('nama');
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        // cek kalau email sudah ada
        $cek = $this->db->get_where('users', ['email' => $email])->row();
        if ($cek) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar, silakan login.');
            redirect('auth/login');
        }

        // insert user baru
        $data = [
            'full_name' => $nama,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
        ];
        $this->db->insert('users', $data);

        $this->session->set_flashdata('success', 'Akun berhasil dibuat, silakan login.');
        redirect('auth/login');
    }

    // logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
