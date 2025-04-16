<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            $this->redirect_to_dashboard();
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user = $this->User_model->login($email, $password);
            if ($user) {
                $this->session->set_userdata(['user_id' => $user->id, 'role' => $user->role]);
                $this->redirect_to_dashboard();
            } else {
                $this->session->set_flashdata('error', 'Email atau password salah');
                redirect('login');
            }
        }
    }

    private function redirect_to_dashboard() {
        $role = $this->session->userdata('role');
        if ($role == 'penyewa') {
            redirect('dashboard/penyewa');
        } elseif ($role == 'pemilik') {
            redirect('dashboard/owner');
        } elseif ($role == 'admin') {
            redirect('dashboard/admin');
        } else {
            redirect('home');
        }
    }

    public function register() {
        if ($this->session->userdata('user_id')) {
            $this->redirect_to_dashboard();
        }
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Telepon', 'required|numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Peran', 'required|in_list[penyewa,pemilik]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/register');
            $this->load->view('templates/footer');
        } else {
            $user_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role')
            ];
            $user_id = $this->User_model->register($user_data);
            if ($user_id) {
                $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
                redirect('login');
            } else {
                $this->session->set_flashdata('error', 'Registrasi gagal. Coba lagi.');
                redirect('register');
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata(['user_id', 'role']);
        redirect('login');
    }
}