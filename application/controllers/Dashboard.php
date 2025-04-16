<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kosan_model');
        $this->load->model('Reservation_model');
        // Pastikan pengguna sudah login
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu');
            redirect('login');
        }
    }

    public function penyewa() {
        if ($this->session->userdata('role') != 'penyewa') {
            show_404();
        }
        $data['title'] = 'Dashboard Penyewa';
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/penyewa', $data);
        $this->load->view('templates/footer');
    }

    public function owner() {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Dashboard Pemilik';
        // Ambil data kosan milik pemilik
        $data['kosans'] = $this->Kosan_model->get_owner_kosans($user_id);
        // Ambil reservasi pending untuk kosan milik pemilik
        $data['reservations'] = $this->Reservation_model->get_owner_reservations($user_id);
        // Ambil perpanjangan pending untuk kosan milik pemilik
        $data['extensions'] = $this->Reservation_model->get_owner_extensions($user_id);
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/owner', $data);
        $this->load->view('templates/footer');
    }

    public function approve_reservation($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        // Update status reservasi ke approved
        $updated = $this->Reservation_model->update_reservation_status($id, 'approved', $user_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'Reservasi diterima');
        } else {
            $this->session->set_flashdata('error', 'Gagal menerima reservasi');
        }
        redirect('dashboard/owner');
    }

    public function reject_reservation($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        // Update status reservasi ke rejected
        $updated = $this->Reservation_model->update_reservation_status($id, 'rejected', $user_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'Reservasi ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak reservasi');
        }
        redirect('dashboard/owner');
    }

    public function approve_extension($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        // Update status perpanjangan ke approved_extension
        $updated = $this->Reservation_model->update_extension_status($id, 'approved_extension', $user_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'Perpanjangan diterima');
        } else {
            $this->session->set_flashdata('error', 'Gagal menerima perpanjangan');
        }
        redirect('dashboard/owner');
    }

    public function reject_extension($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        // Update status perpanjangan ke rejected_extension
        $updated = $this->Reservation_model->update_extension_status($id, 'rejected_extension', $user_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'Perpanjangan ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak perpanjangan');
        }
        redirect('dashboard/owner');
    }

    public function admin() {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $data['title'] = 'Dashboard Admin';
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/admin', $data);
        $this->load->view('templates/footer');
    }

    public function extend($reservation_id) {
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') != 'penyewa') {
            redirect('login');
        }
        $reservation = $this->Reservation_model->get_reservation($reservation_id);
        if (!$reservation || $reservation->status != 'completed') {
            show_404();
        }
        $this->form_validation->set_rules('extended_duration', 'Durasi', 'required');
        $this->form_validation->set_rules('extension_start_date', 'Tanggal Mulai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['reservation'] = $reservation;
            $this->load->view('templates/header', $data);
            $this->load->view('reservation/extend', $data);
            $this->load->view('templates/footer');
        } else {
            $extension_data = [
                'reservation_id' => $reservation_id,
                'user_id' => $this->session->userdata('user_id'),
                'kosan_id' => $reservation->kosan_id,
                'extended_duration' => $this->input->post('extended_duration'),
                'extension_start_date' => $this->input->post('extension_start_date'),
                'status' => 'pending_extension'
            ];
            $this->Reservation_model->save_extension($extension_data);
            $this->session->set_flashdata('success', 'Perpanjangan diajukan');
            redirect('dashboard/penyewa');
        }
    }
}