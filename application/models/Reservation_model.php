<?php
class Reservation_model extends CI_Model {
    public function save_reservation($data) {
        $this->db->insert('reservations', $data);
        return $this->db->insert_id();
    }

    public function get_reservation_by_id($id) {
        return $this->db->select('r.*, u.name as penyewa_name')
                        ->from('reservations r')
                        ->join('users u', 'r.user_id = u.id')
                        ->where('r.id', $id)
                        ->get()
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
    public function get_active_tenants($owner_id) {
        $current_date = date('Y-m-d');
        $tenants = $this->db->select('r.id, r.kosan_id, r.user_id, r.move_in_date, r.duration, k.name as kosan_name, u.name, u.phone')
                            ->from('reservations r')
                            ->join('kosans k', 'r.kosan_id = k.id')
                            ->join('users u', 'r.user_id = u.id')
                            ->where('k.owner_id', $owner_id)
                            ->where('r.status', 'approved')
                            ->get()
                            ->result();

        $active_tenants = [];
        foreach ($tenants as $tenant) {
            // Hitung tanggal akhir sewa berdasarkan move_in_date dan duration
            $move_in = new DateTime($tenant->move_in_date);
            $move_in->modify("+{$tenant->duration} months");
            $end_date = $move_in->format('Y-m-d');

            // Cek apakah ada perpanjangan yang disetujui
            $extension = $this->db->select('extended_duration, extension_start_date')
                                  ->from('extensions')
                                  ->where('reservation_id', $tenant->id)
                                  ->where('status', 'approved_extension')
                                  ->get()
                                  ->row();
            if ($extension) {
                $extension_start = new DateTime($extension->extension_start_date);
                $extension_start->modify("+{$extension->extended_duration} months");
                $end_date = $extension_start->format('Y-m-d');
            }

            // Jika tanggal saat ini masih dalam periode sewa
            if ($current_date <= $end_date) {
                $tenant->end_date = $end_date;
                $active_tenants[] = $tenant;
            }
        }

        return $active_tenants;
    }
}