<?php
class User_model extends CI_Model {

    public function register($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
    public function login($email, $password) {
        $user = $this->db->get_where('users', ['email' => $email])->row();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function get_user($id) {
        return $this->db->where('id', $id)->get('users')->row();
    }

    public function count_users($role = null) {
        if ($role) {
            $this->db->where('role', $role);
        }
        return $this->db->count_all_results('users');
    }

    public function get_all_users() {
        return $this->db->get('users')->result();
    }
    
    public function update_user($id, $data) {
        $this->db->where('id', $id)->update('users', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function delete_user($id) {
        $this->db->where('id', $id)->delete('users');
        return $this->db->affected_rows() > 0;
    }
}