<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->boot_database();
    }

    public function login($email, $password)
    {
        if ($this->db === NULL || empty($this->db->database) || ! $this->db->table_exists('users')) {
            return NULL;
        }

        $user = $this->db->where('email', $email)->get('users')->row_array();

        if ( ! $user) {
            return NULL;
        }

        $stored_password = isset($user['password']) ? $user['password'] : '';

        if (
            $stored_password === md5($password) ||
            $stored_password === $password ||
            (function_exists('password_verify') && password_verify($password, $stored_password))
        ) {
            return $user;
        }

        return NULL;
    }

    private function boot_database()
    {
        require APPPATH . 'config/database.php';

        if (empty($db[$active_group]['database'])) {
            return NULL;
        }

        return $this->load->database($active_group, TRUE);
    }
}
