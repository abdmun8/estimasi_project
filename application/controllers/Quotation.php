<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {
	private $identity; // store session

	public function __construct() {
	    parent::__construct();
	    $this->identity = $this->session->userdata('identity');
        $this->load->model('Quotation_model', 'quotation');
	}

	public function index() {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
		
        $this->load->view('cms/_detail_quotation');
	}

	public function viewFormQuotation(){

    }
}