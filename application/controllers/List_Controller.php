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

    public function status($status, $category) {
        if($status == 'all' && $category == 'all') {
            $purchases = $this->list_model->getPurchases();
        } else if($category == 'all') {
            $purchases = $this->list_model->getPurchasesByStatus($status);
        } else if($status == 'all') {
            $purchases = $this->list_model->getPurchasesByCategory($category);
        } else {
            $purchases = $this->list_model->getPurchasesByStatusAndCategory($status, $category);
        }
        $categories = $this->list_model->getCategories();
        $data['purchases'] = $purchases;
        $data['categories'] = $categories;
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        http_response_code(200);
        echo $json;
    }

    public function create_purchase() {
        $json_data = file_get_contents('php://input');
        $json_data = json_decode($json_data);
        $name = $json_data->name ? $json_data->name : null;
        $category = $json_data->category ? $json_data->category : null;
        $date = $json_data->date ? $json_data->date : null;
        $validation  = preg_match('/[\pL]/u', $name) ? true : (bool)preg_match('/\d/', $name);
        $insert_id = $validation && $category && $date ? $this->list_model->createPurchase($name, $category, $date) : false;
        $data['status'] = true;
        $data['id'] = $insert_id;
        $data['name'] = $name;
        if(!$validation) {
            $data['message'] = 'purchase must contains at least one letter or number';
        } else if(!$insert_id) {
            $error = $this->list_model->db->error();
            $data['message'] = 'something went wrong, please try again later';
        }
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        http_response_code($insert_id ? 200 : 400);
        echo $json;
    }

    public function create_category() {
        $json_data = file_get_contents('php://input');
        $json_data = json_decode($json_data);
        $name = $json_data->name ? $json_data->name : null;
        $validation  = preg_match('/[\pL]/u', $name) ? true : (bool)preg_match('/\d/', $name);
        $insert_id = $validation ? $this->list_model->createCategory($name) : false;
        $data['status'] = $insert_id ? true : false;
        $data['id'] = $insert_id;
        $data['name'] = $name;
        if(!$validation) {
            $data['message'] = 'category must contains at least one letter or number';
        } else if(!$insert_id) {
            $error = $this->list_model->db->error();
            $data['message'] = $error['code'] === 1062 ? 'this category already exists' : 'something went wrong, please try again later';
        }
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        http_response_code($insert_id ? 200 : 400);
        echo $json;
    }

    public function toggle($id, $status) {
        $respond = $this->list_model->toggleStatus($id, $status);
        $data['status'] = true;
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        http_response_code(200);
        echo $json;
    }

    public function delete_purchase($id) {
        $respond = $this->list_model->deletePurchase($id);
        $data['status'] = true;
        $json = json_encode($data);
        header('Content-type: application/json; charset=utf-8');
        http_response_code(200);
        echo $json;
    }
}
