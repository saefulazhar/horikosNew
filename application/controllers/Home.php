<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model Kosan_model
        $this->load->model('Kosan_model');
    }
    public function index() {
        $data['facilities'] = $this->Kosan_model->get_facilities();
        if ($this->input->post()) {
            $filters = [
                'location' => $this->input->post('location'),
                'personality' => $this->input->post('personality'),
                'price_min' => $this->input->post('price_min'),
                'price_max' => $this->input->post('price_max'),
                'facilities' => $this->input->post('facilities')
            ];
            $data['kosans'] = $this->Kosan_model->search_kosans($filters);
        } else {
            $data['kosans'] = $this->Kosan_model->get_kosans();
        }
        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }
}