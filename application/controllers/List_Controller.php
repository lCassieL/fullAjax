<?php

class List_Controller extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('list_model');

    }
	public function index() {
        $purchases = $this->list_model->getPurchases();
        $categories = $this->list_model->getCategories();
        $data['purchases'] = $purchases;
        $data['categories'] = $categories;
        $this->load->helper('url');
		$this->load->view('template', $data);
	}

    public function status($status) {
        $purchases = $this->list_model->getPurchasesByStatus($status);
        $categories = $this->list_model->getCategories();
        $data['purchases'] = $purchases;
        $data['categories'] = $categories;
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        echo $json;
    }
}
