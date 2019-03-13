<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewer extends CI_Controller {
	private $identity; 

	public function __construct() {
	    parent::__construct();
	    $this->identity = $this->session->userdata('identity');
	}

	public function index() {
		if ($this->identity == null) {
			$this->load->view('login');
		} else {
			redirect(site_url('view/home'));
		}
	}

	public function pathGuide($page = 'home', $param = null) {
		if ($this->identity == null) {
			$this->load->view('redirect');
		} else {
			$this->load->view('cms/'.$page, array('param' => $param));
		}
	}

	public function registrasi()
	{
		if ($this->identity == null) {
			$this->load->view('register');
		} else {
			redirect(site_url('view/home'));
		}
	}

	public function form_login()
	{
		if ($this->identity == null) {
			$this->load->view('login');
		} else {
			redirect(site_url('view/home'));
		}
	}
}