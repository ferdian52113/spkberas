<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {
        parent::__construct();
        
        $this->load->helper('text');
        $this->load->helper('form');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
        $this->load->model('user_model');
        
        date_default_timezone_set("Asia/Jakarta");
    }

	public function index()
	{
		$this->login();
	}

	public function login() {

        if ($this->session->userdata('lembaga')) {

            redirect('user');

        } else {
            $this->load->view('user/login_view');    
        }

    }

    public function proses() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $status = $this->user_model->checkAccount($username,$password);
        if ($status != FALSE) {
            $setting = $this->user_model->get_setting1();
            $session_data = array(
                'username' => $status[0]->username,
                'lembaga' => $status[0]->lembaga,
                'id_user' => $status[0]->id_user,
                'role' => $status[0]->role,
                'prov' => $status[0]->provinsi,
                'status_HET' => $setting[0]['status_HET']
            );
            $this->session->set_userdata($session_data);
            redirect('user');
        } else {
            $data['err'] = 'ada';
            $this->load->view('user/login_view', $data);
        }
    }
    
}
