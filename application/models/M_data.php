<?php
class M_data extends CI_Model {

    function get_barang(){
        return $this->db->get('barang')->result();
    }

    function get_kurir(){
        return $this->db->get('kurir')->result();
    }

}