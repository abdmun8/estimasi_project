<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {
	private $identity; // store session

	public function __construct() 
    {
	    parent::__construct();
	    $this->identity = $this->session->userdata('identity');
        $this->load->model('Quotation_model', 'quotation');
        $this->load->library('form_validation');
	}

	public function index($id = NULL) 
    {
        $data = [];
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $data['param'] = $id;

        $this->load->view('cms/_detail_quotation',$data);
	}

	public function getDataHeader($id = NULL)
    {
        $code = 1;
        $object = [];
        $message = '';
        if($id != NULL){
            $object = $this->model->getRecord(['table'=>'header',['where'=>['id'=>$id]]]);
        }else{
            $object = $this->model->getList(['table'=>'header']);
        }

        echo json_encode(array('data' => array(
            'code' => $code,
            'object' => $object,
            'message' => $message
        )));
    }

    /*
    * 1 = Insert
    * 2 = Update
    * 3 = Delete
    */

    public function getDataPart($id_header = NULL, $id_part = NULL){
        $object = [];
        if($id_header != NULL){
            if($id_part == NULL){
                $object = $this->model->getList(['table' => 'part_jasa', 'where' => ['id_header' => $id_header]]);                
            }else{
                $object = $this->model->getRecord(['table'=>'part_jasa','where'=>['id_header' => $id_header, 'id' => $id_part]]);
            }
        }

        echo json_encode($object);
    }

    public function saveGeneralInfo()
    {
        $code = 0;
        $message = '';
        $action = $this->input->post('action');
        $this->form_validation->set_rules($this->quotation->getRules($action));

        if ($this->form_validation->run() == FALSE) {

            $delimiter = '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>';
            $this->form_validation->set_error_delimiters($delimiter, '');
            $message = $this->form_validation->error_array();

        }else{

            if($action == 1){
                
                if( $this->quotation->insertGeneralInfo() == TRUE){
                    $code = 1;
                }

            }else{
                if( $this->quotation->udpateGeneralInfo() == TRUE ){
                    $code = 1;
                }
            }
        }        

        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }

    public function saveItem(){
        // print_r($this->input->post());die;
        $code = 0;
        $message = '';
        $action = $this->input->post('action-item');
        if($action == 1){
                
                if( $this->quotation->insertDetailPart() == TRUE){
                    $code = 1;
                }

            }else{
                if( $this->quotation->udpateDetailPart() == TRUE ){
                    $code = 1;
                }
            }
        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }
}