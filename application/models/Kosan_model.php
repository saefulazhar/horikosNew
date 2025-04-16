<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kosan_model extends CI_Model {
    public function get_kosans() {
        return $this->db->where('status', 'active')
                        ->get('kosans')
                        ->result();
    }

    public function get_facilities() {
        return $this->db->get('facilities')->result();
    }

    public function search_kosans($filters) {
        $this->db->where('status', 'active');
        if (!empty($filters['location'])) {
            $this->db->like('address', $filters['location']);
        }
        if (!empty($filters['personality'])) {
            $this->db->where('personality_category', $filters['personality']);
        }
        if (!empty($filters['price_min'])) {
            $this->db->where('price >=', $filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $this->db->where('price <=', $filters['price_max']);
        }
        if (!empty($filters['facilities'])) {
            $this->db->join('kosan_facilities kf', 'kosans.id = kf.kosan_id');
            $this->db->where_in('kf.facility_id', $filters['facilities']);
        }
        return $this->db->get('kosans')->result();
    }

    public function get_kosan($id) {
        return $this->db->where('id', $id)
                        ->where('status', 'active')
                        ->get('kosans')
                        ->row();
    }

    public function get_photos($kosan_id) {
        return $this->db->where('kosan_id', $kosan_id)
                        ->get('photos')
                        ->result();
    }

    public function get_owner_kosans($owner_id) {
        return $this->db->where('owner_id', $owner_id)
                        ->get('kosans')
                        ->result();
    }
}