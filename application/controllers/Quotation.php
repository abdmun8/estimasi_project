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
            // $object 
            $object = $this->model->getRecord(['table'=>'header','where'=>['id'=>$id]]);
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

    public function getDataPart($id_header = NULL, $id_part = NULL)
    {
        $object = [];
        $data = [];
        if($id_header != NULL){
            if($id_part == NULL){
                $object = $this->db->get_where('part_jasa', ['id_header' => $id_header])->result_array();
                $data = $this->countTotal($object);
            }else{
                $data = $this->db->get_where('part_jasa', ['id_header' => $id_header, 'id' => $id_part])->row_array();
                $data['harga'] = $data['harga'];
            }
        }

        echo json_encode($data);
    }

    public function getDataLabour($id_header = NULL, $id_labour = NULL)
    {
        $object = [];
        $data = [];
        if($id_header != NULL){
            if($id_labour == NULL){
                $object = $this->db->get_where('v_labour', ['id_header' => $id_header])->result_array();
                $data = $this->countTotal($object, 'labour');
            }else{
                $data = $this->db->get_where('v_labour', ['id_header' => $id_header, 'id' => $id_labour])->row_array();
                $data['rate'] = $data['rate'];
            }
        }

        echo json_encode($data);
    }

    private function countTotal($array, $tipe = 'part')
    {
        $col = [];
        $data_item = [];
        $data_sub_obj = [];
        $temp_sub_obj = [];
        $data_obj = [];
        $temp_obj = [];
        $data_section = [];
        $temp_section = [];
        $new_data = [];

        if($tipe == 'part'){
            $col = ['qty', 'harga'];
        }else{
            $col = ['hour', 'rate'];
        }

        foreach ($array as $key => $item) {
            $item['total'] = 0;
            if($item['tipe_item'] == 'item'){
                $item['total'] = ($item[$col[0]] * $item[$col[1]]);
                $item[$col[1]] = intval($item[$col[1]]);
                $item[$col[0]] = intval($item[$col[0]]);
                $data_item[] = $item;
            }

            if($item['tipe_item'] == 'sub_object'){
                $temp_sub_obj[] = $item;
            }

            if($item['tipe_item'] == 'object'){
                $temp_obj[] = $item;
            }

            if($item['tipe_item'] == 'section'){
                $temp_section[] = $item;
            }
        }
    
        foreach ($temp_sub_obj as $key => $so) {
            $so['total'] = 0; 
            foreach ($data_item as $key => $item) {
                if($so['tipe_item'] == 'sub_object'){
                    if($item['id_parent'] == $so['id']){
                        $so['total'] += $item['total'];
                    }
                }
            }
            $data_sub_obj[] = $so;    
        }

        

        foreach ($temp_obj as $key => $object) {
            $object['total'] = 0; 
            foreach ($data_sub_obj as $key => $so) {
                if($object['tipe_item'] == 'object'){
                    if($so['id_parent'] == $object['id']){
                        $object['total'] += $so['total'];
                    }
                }
            }
            $data_obj[] = $object;
        }

        

        foreach ($temp_section as $key => $section) {
            $section['total'] = 0;
            foreach ($data_obj as $key => $object) {
                if($section['tipe_item'] == 'section'){
                    if($object['id_parent'] == $section['id']){
                        $section['total'] += $object['total'];
                    }
                }
            }
            $data_section[] = $section;
        }        

        $new_data = array_merge($data_item, $data_sub_obj, $data_obj, $data_section);
        return $new_data;

    }

    public function saveGeneralInfo()
    {
        // print_r($_POST);
        // die;
        $code = 0;
        $message = '';
        $last_id = NULL;
        $action = $this->input->post('action');
        $this->form_validation->set_rules($this->quotation->getRules($action));

        if ($this->form_validation->run() == FALSE) {
            $delimiter = '- ';
            $this->form_validation->set_error_delimiters($delimiter, '');
            $message = validation_errors();

        }else{

            if($action == 1){
                
                if( $this->quotation->insertGeneralInfo() == TRUE){
                    $code = 1;
                    $last_id = $this->db->insert_id();
                }

            }else{
                if( $this->quotation->udpateGeneralInfo() == TRUE ){
                    $code = 1;
                    $last_id = $this->input->post('id_header');
                }
            }
        }        

        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        ),'last_id'=>$last_id));
    }

    public function saveItem()
    {
        // print_r($this->input->post());die;
        $code = 0;
        $message = '';
        $action = $this->input->post('action-item');
        if($action == 1){
                
                if( $this->quotation->insertDetailPart() == TRUE){
                    $code = 1;
                    if( $this->input->post('tipe_item-item') != 'item'){
                        $this->db->insert('labour', 
                            [
                                'id_header' => $this->input->post('id_header-item'),
                                'tipe_item' => $this->input->post('tipe_item-item'),
                                'id_parent' => ($this->input->post('id_parent-item') == NULL) ? 0 : $this->input->post('id_parent-item'),
                                'id_part_jasa' => $this->db->insert_id()
                            ]
                        );
                    }
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

    public function saveLabour()
    {
        // print_r($this->input->post());
        // die;
        $code = 0;
        $message = '';
        $action = $this->input->post('action-labour');
        if($action == 1){
                
                if( $this->quotation->insertDetailLabour() == TRUE){
                    $code = 1;
                }

            }else{
                if( $this->quotation->udpateDetailLabour() == TRUE ){
                    $code = 1;
                }
            }
        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }

    public function getItemCode()
    {
        $obj = $this->db->select('stcd, CONCAT( TRIM(nama)," (",stcd,")" ) as name, TRIM(spek) as spek, TRIM(maker) as maker, TRIM(uom) as uom, TRIM(nama) as nama, harga', false)->get('v_item')->result();
        
        echo json_encode($obj);
    }

    public function delItem(){
        $code = 0;
        $msg = '';
        $post = $this->input->post();

        $child = $this->db->get_where($post['table'], ['id_parent' => $post['id']])->num_rows();

        if($child > 0){
            $msg = 'Hapus sub terlebih dahulu';
            echo json_encode(['data'=>['code'=>$code,'message'=>$msg]]);
            return;
        }

        if($post['tipe_item'] != 'item' && $post['table'] == 'part_jasa'){
            // $id_sec = 0;
            // $id_obj = 0;
            // $id_subobj = 0;
            // if($post['tipe_item'] == 'section'){
            //     $id_sec = $this->db->select('id')->where(['id' => $post['']])->get()->;

            // }
            // $this->db->delete('labour')
            $id_del = $this->db->get_where('v_labour', ['tipe_item' == null]);
            
        }

        $del = $this->db->delete($post['table'], ['id' => $post['id']]);
        if($del){
            $code = 1;
        }

        echo json_encode(['data'=>['code'=>$code,$msg=>$msg]]);
    }
}