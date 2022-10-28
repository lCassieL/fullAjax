<?php

class List_model extends CI_Model
{
    public function __construct() {
        $this->load->database();
    }

    public function getPurchases() {
        $query = $this->db->query('SELECT purchases.*, categories.name as category FROM purchases, categories WHERE purchases.category_id = categories.id');
        return $query->result();
    }

    public function getCategories() {
        $query = $this->db->query('SELECT * FROM categories');
        return $query->result();
    }

    public function getPurchasesByStatus($status) {
        $query = $this->db->query('SELECT purchases.*, categories.name as category FROM purchases, categories WHERE purchases.category_id = categories.id AND status='.(int)$status);
        return $query->result();
    }

    public function getPurchasesByCategory($category) {
        $query = $this->db->query('SELECT purchases.*, categories.name as category FROM purchases, categories WHERE purchases.category_id = categories.id AND category_id='.(int)$category);
        return $query->result();
    }

    public function getPurchasesByStatusAndCategory($status, $category) {
        $query = $this->db->query('SELECT purchases.*, categories.name as category FROM purchases, categories WHERE purchases.category_id = categories.id AND status='.(int)$status.' AND category_id='.(int)$category);
        return $query->result();
    }

    public function createPurchase($name, $category, $date) {
        $name = $this->db->escape($name);
        $date = $this->db->escape($date);
        $category = (int)$category;
        $query = $this->db->query("INSERT INTO purchases(name, status, category_id, created) VALUES($name, 0, $category, $date)");
        $insert_id = $this->db->insert_id();
        return $query ? $insert_id : false;
    }

    public function createCategory($name) {
        $name = $this->db->escape($name);
        $query = $this->db->query("INSERT INTO categories(name) VALUES($name)");
        $insert_id = $this->db->insert_id();
        return $query ? $insert_id : false;
    }

    public function toggleStatus($id, $status) {
        $status = (int)$status;
        $query = $this->db->query("UPDATE purchases SET status='$status' WHERE id=".(int)$id);
        return $query;
    }

    public function deletePurchase($id) {
        $query = $this->db->query("DELETE FROM purchases WHERE id=".(int)$id);
        return $query;
    }
}