<?php
class Review_model extends CI_Model {
    public function get_reviews($kosan_id) {
        return $this->db->where('kosan_id', $kosan_id)
                        ->get('reviews')
                        ->result();
    }
}