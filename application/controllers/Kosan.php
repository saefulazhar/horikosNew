<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kosan extends CI_Controller {
    public function detail($id) {
        $data['kosan'] = $this->Kosan_model->get_kosan($id);
        $data['photos'] = $this->Kosan_model->get_photos($id);
        $data['reviews'] = $this->Review_model->get_reviews($id);
        if (!$data['kosan']) {
            show_404();
        }
        $this->load->view('templates/header', $data);
        $this->load->view('kosan/detail', $data);
        $this->load->view('templates/footer');
    }
}