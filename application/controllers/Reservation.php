<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation extends CI_Controller {
    public function create($kosan_id) {
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') != 'penyewa') {
            $this->session->set_flashdata('error', 'Silakan login sebagai penyewa');
            redirect('login');
        }
        $data['kosan'] = $this->Kosan_model->get_kosan($kosan_id);
        if (!$data['kosan']) {
            show_404();
        }
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('phone', 'Telepon', 'required');
        $this->form_validation->set_rules('move_in_date', 'Tanggal Menempati', 'required');
        $this->form_validation->set_rules('duration', 'Durasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('reservation/form', $data);
            $this->load->view('templates/footer');
        } else {
            $reservation_data = [
                'user_id' => $this->session->userdata('user_id'),
                'kosan_id' => $kosan_id,
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'move_in_date' => $this->input->post('move_in_date'),
                'duration' => $this->input->post('duration'),
                'status' => 'pending'
            ];
            $reservation_id = $this->Reservation_model->save_reservation($reservation_data);
            redirect('reservation/confirmation/'.$reservation_id);
        }
    }

    public function confirmation($id) {
        $data['reservation'] = $this->Reservation_model->get_reservation($id);
        if (!$data['reservation']) {
            show_404();
        }
        $this->load->view('templates/header', $data);
        $this->load->view('reservation/confirmation', $data);
        $this->load->view('templates/footer');
    }
}