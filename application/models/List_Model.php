<?php

class List_Model extends CI_Model
{
    public function __construct() {
        $this->load->database();
    }

    public function getPurchases() {
        $query = $this->db->query('SELECT * FROM purchases');
        return $query->result();
    }

    public function getCategories() {
        $query = $this->db->query('SELECT * FROM categories');
        return $query->result();
    }

    public function getPurchasesByStatus($status) {
        $query = $this->db->query('SELECT * FROM purchases WHERE status='.(int)$status);
        return $query->result();
    }
}