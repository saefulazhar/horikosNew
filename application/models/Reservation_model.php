<?php
class Reservation_model extends CI_Model {
    public function save_reservation($data) {
        $this->db->insert('reservations', $data);
        return $this->db->insert_id();
    }

    public function get_reservation($id) {
        return $this->db->where('id', $id)
                        ->get('reservations')
                        ->row();
    }
    public function get_user_reservations($user_id) {
        return $this->db->select('r.*, k.name as kosan_name, e.extended_duration, e.extension_start_date, e.status as extension_status')
                        ->from('reservations r')
                        ->join('kosans k', 'r.kosan_id = k.id')
                        ->join('extensions e', 'r.id = e.reservation_id', 'left')
                        ->where('r.user_id', $user_id)
                        ->get()
                        ->result();
    }
    
    public function save_extension($data) {
        $this->db->insert('extensions', $data);
        return $this->db->insert_id();
    }

    public function get_owner_reservations($owner_id) {
        return $this->db->select('r.*, k.name as kosan_name')
                        ->from('reservations r')
                        ->join('kosans k', 'r.kosan_id = k.id')
                        ->where('k.owner_id', $owner_id)
                        ->where('r.status', 'pending')
                        ->get()
                        ->result();
    }

    public function get_owner_extensions($owner_id) {
        return $this->db->select('e.*, k.name as kosan_name, r.name as penyewa_name')
                        ->from('extensions e')
                        ->join('kosans k', 'e.kosan_id = k.id')
                        ->join('reservations r', 'e.reservation_id = r.id')
                        ->where('k.owner_id', $owner_id)
                        ->where('e.status', 'pending_extension')
                        ->get()
                        ->result();
    }

    public function update_reservation_status($id, $status, $owner_id) {
        // Pastikan hanya reservasi untuk kosan milik pemilik yang diupdate
        $this->db->select('r.id')
                 ->from('reservations r')
                 ->join('kosans k', 'r.kosan_id = k.id')
                 ->where('r.id', $id)
                 ->where('k.owner_id', $owner_id);
        if ($this->db->get()->num_rows() == 0) {
            return false;
        }
        $this->db->where('id', $id)
                 ->update('reservations', ['status' => $status]);
        return $this->db->affected_rows() > 0;
    }

    public function update_extension_status($id, $status, $owner_id) {
        // Pastikan hanya perpanjangan untuk kosan milik pemilik yang diupdate
        $this->db->select('e.id')
                 ->from('extensions e')
                 ->join('kosans k', 'e.kosan_id = k.id')
                 ->where('e.id', $id)
                 ->where('k.owner_id', $id);
        if ($this->db->get()->num_rows() == 0) {
            return false;
        }
        $this->db->where('id', $id)
                 ->update('extensions', ['status' => $status]);
        return $this->db->affected_rows() > 0;
    }
}