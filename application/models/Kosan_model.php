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

    public function get_kosan_by_id($id) {
        return $this->db->where('id', $id)
                        ->get('kosans')
                        ->row();
    }

    public function get_photos($kosan_id) {
        return $this->db->where('kosan_id', $kosan_id)
                        ->get('photos')
                        ->result();
    }

    public function get_kosan_facilities($kosan_id) {
        return $this->db->select('f.id, f.name')
                        ->from('kosan_facilities kf')
                        ->join('facilities f', 'kf.facility_id = f.id')
                        ->where('kf.kosan_id', $kosan_id)
                        ->get()
                        ->result();
    }

    public function get_owner_kosans($owner_id) {
        return $this->db->where('owner_id', $owner_id)
                        ->get('kosans')
                        ->result();
    }

    public function save_kosan($data) {
        $this->db->insert('kosans', $data);
        return $this->db->insert_id();
    }

    public function save_kosan_facilities($kosan_id, $facility_ids) {
        foreach ($facility_ids as $facility_id) {
            $this->db->insert('kosan_facilities', [
                'kosan_id' => $kosan_id,
                'facility_id' => $facility_id
            ]);
        }
    }

    public function save_photos($kosan_id, $photos) {
        foreach ($photos as $photo) {
            $this->db->insert('photos', [
                'kosan_id' => $kosan_id,
                'url' => $photo['url'],
                'is_primary' => $photo['is_primary']
            ]);
        }
    }

    public function get_inactive_kosans() {
        return $this->db->where('status', 'inactive')
                        ->get('kosans')
                        ->result();
    }

    public function get_all_kosans($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get('kosans')->result();
    }

    public function update_kosan_status($id, $status) {
        $this->db->where('id', $id)
                 ->update('kosans', ['status' => $status]);
        return $this->db->affected_rows() > 0;
    }

    public function delete_kosan($id) {
        // Hapus foto fisik
        $photos = $this->get_photos($id);
        foreach ($photos as $photo) {
            unlink(FCPATH . 'assets/uploads/' . $photo->url);
        }
        $this->db->where('id', $id)
                 ->delete('kosans');
        return $this->db->affected_rows() > 0;
    }

    public function count_kosans($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('kosans');
    }
    public function update_kosan($id, $data) {
        $this->db->where('id', $id)
                 ->update('kosans', $data);
        return $this->db->affected_rows() > 0;
    }
}