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
}