<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller {
	private $activeSession; // store session

	public function __construct() {
	    parent::__construct();
	    $this->activeSession = $this->session->userdata('identity');
	    $this->load->library('form_validation');
	}

	public function index() {
		redirect(site_url('view/home'));
	}

	/*
	*	login or logout
	*/
	public function identify($action) {
		if ($action == 'acknowledge') { // for login
			/*
			* code info:
			*	- 0 = akses tidak sah
			*	- 1 = user granted
			*	- 2 = username tidak dikenal
			*	- 3 = user password salah
			*/
			$code = 0;
			$message = '';

			if ($this->activeSession == null) {
				$this->load->model('loginmodel');
				$this->form_validation->set_rules($this->loginmodel->getRules());

				if ($this->form_validation->run() == FALSE) {
					$delimiter = '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>';
					$this->form_validation->set_error_delimiters($delimiter, '');
					$message = $this->form_validation->error_array();//validation_errors();
				} else {
					$query['table'] = 'v_user';
					$query['where'] = array(
									'username' => $this->input->post('username-input')
									);
					$actor = $this->loginmodel->getRecord($query);
					// echo $this->db->last_query();die;

					if ($actor == null) {
	    				$code = 2;	//username tidak ada atau tidak aktif
	    			} else {
	    				if($actor->password == md5($this->input->post('password-input'))) {
	    					$st = $this->db->get('setting')->row();
	    					$this->session->set_userdata(array(
	    					    '_ID' => $actor->user_id,
	    					    '_NAMA' => $actor->name,
	    					    '_LEVEL' => $actor->level,
	    					    '_TA' => $st->tahun_ajaran,
	    					    '_SMT' => $st->semester,
	    					    '_ID_KELAS' => $actor->id_kelas,
	    					    //'_IMG' => $actor->img
	    					));
	    					$code = 1;	//login ok
	    				} else {
	    					$code = 2;	//password salah
	    					$message = 'Password salah!';
	    				}
	    			}
					}
	    	}

	    	echo json_encode(array('data' => array(
	    		'code' => $code,
	    		'message' => $message
    		)));
    		
		} else if ($action == 'revoke') { // for logout
			if ($this->activeSession != null) {
	    		$this->session->sess_destroy();
	    	}
	    	redirect(site_url());
		}
	}

	/*
	*	create, update, or delete
	*/
	
	public function process() {
		/*
		* code info:
		*	- 0 = akses tidak sah
		*	- 1 = proses berhasil
		*	- 2 = proses gagal
		*/
		$code = 0;
		$last_id = 0;
		$message = '';
		/* collect request */
		$action = $this->input->post('action-input'); // create, update, delete
		$model = $this->input->post('model-input') . 'model';


		if ($this->activeSession != null) {
			$this->load->model($model);
			$this->$model->isNew(($action == $this->$model->CREATE)); // if action is for creating new data, ignore unique field

			//if delete
			if($action == $this->$model->DELETE) {
				//for file
				if ($this->input->post('model-input') == 'gallery_detail') {
					$dt = $this->model->getRecord(array('table' => $this->$model->getTable(), 'where' => array($this->input->post('key-input') => $this->input->post('value-input'))));
					if ($dt) {
						$pathfile = 'asset/image/gallery/' . $dt->img;
						$pathfile2 = 'asset/image/gallery/thumb/' . $dt->img;
					}
				}elseif ($this->input->post('model-input') == 'apply_cv') {
					$dt = $this->model->getRecord(array('table' => $this->$model->getTable(), 'where' => array($this->input->post('key-input') => $this->input->post('value-input'))));
					if ($dt) {
						$pathfile = 'assets/files/' . $dt->file_name;
					}
				} else {
					$pathfile = null;
				}

				$result = $this->_do_delete($this->$model, $this->input->post(null));
				if ($result) {
					$code = 1;
					//delete file
					if ($pathfile != null) {
						@unlink($pathfile);
						@unlink($pathfile2);
					}
				} else {
					$code = 2;
				}
				
			} else {
				$this->form_validation->set_rules($this->$model->getRules());

				if ($this->form_validation->run() == FALSE) {
					$delimiter = '- ';
					$this->form_validation->set_error_delimiters($delimiter, '');
					$message = validation_errors();
				} else {
					$isExist = '';
					$result = $this->_do($this->$model, $action, $this->input->post(null));

					$last_id = ($action == $this->$model->CREATE) ? $this->$model->getLastID() : $this->input->post('value-input') ;
					$code = ($result) ? 1 : 2;
					
				}
			}
		}

    	echo json_encode(array('data' => array(
    		'code' => $code,
    		'message' => $message,
    		'last_id' => $last_id
		)));
	}

	/*
	* inner process
	*/
	private function _do($model, $action, $inputs) {
		$query = array(
		'table' => $model->getTable(), 'type' => $action,
		'data' => $model->getField($inputs),
			'at' => array(
				$inputs['key-input'] => $inputs['value-input']
			) // clause for model
		);
		return $model->action($query); // do...
	}

	private function _do_delete($model, $inputs) {
		
		$query = array(
			'table' => $model->getTable(), 'type' => 3,
			'data' => 'null',
			'at' => array(
				$inputs['key-input'] => $inputs['value-input']
			) // clause for model
		);
		return $model->action($query); // do...
	}


}
