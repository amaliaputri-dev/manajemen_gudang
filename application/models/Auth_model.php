<?php
class Auth_model extends CI_Model {
    public function login($email, $password) {
        // Menggunakan MD5 sesuai dengan format password di SQL yang kamu kirim
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        return $this->db->get('users')->row_array();
    }
}