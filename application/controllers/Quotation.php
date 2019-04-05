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

    /* Get data for part jasa tab*/
    public function getDataPart($id_header = NULL, $id_part = NULL)
    {
        $object = [];
        $data = [];
        if($id_header != NULL){
            if($id_part == NULL){
                $object = $this->db->query('SELECT 
                            *,
                            (SELECT 
                                    tipe_item
                                FROM
                                    part_jasa p
                                WHERE
                                    p.id = j.id_parent) AS tipe_parent,
                            k.desc AS nama_kategori
                        FROM
                            `part_jasa` j
                                LEFT JOIN
                            `akunbg` k ON j.kategori = k.accno
                        WHERE
                            j.id_header ="'.$id_header.'"')->result_array();
                $data = $this->countTotal($object);
            }else{
                $data = $this->db->get_where('part_jasa', ['id_header' => $id_header, 'id' => $id_part])->row_array();
                // $data['harga'] = $data['harga'];
            }
        }

        echo json_encode($data);
    }

    /* Get data for labour tab*/
    public function getDataLabour($id_header = NULL, $id_labour = NULL)
    {
        $object = [];
        $data = [];
        if($id_header != NULL){
            if($id_labour == NULL){
                // $object = $this->db->get_where('v_labour', ['id_header' => $id_header])->result_array();
                $object = $this->db->query('SELECT 
                    `l`.*,
                            (SELECT 
                                    tipe_item
                                FROM
                                    labour b
                                WHERE
                                    b.id = l.id_parent) AS tipe_parent
                        FROM
                            `labour` `l`
                        WHERE
                            l.id_header ="'.$id_header.'"')->result_array();

                $data = $this->countTotal($object, 'labour');
            }else{
                $data = $this->db->get_where('v_labour', ['id_header' => $id_header, 'id' => $id_labour])->row_array();
                // $data['rate'] = $data['rate'];
            }
        }

        echo json_encode($data);
    }

    function getDataMaterial($id_header = NULL, $id_material = NULL)
    {
        $object = [];
        $data = [];
        if($id_header != NULL){
            if($id_material == NULL){
                $object = $this->db->get_where('v_rawmaterial', ['id_header' => $id_header])->result_array();
                $data = $this->countTotal($object, 'rawmaterial');
            }else{
                $data = $this->db->get_where('v_rawmaterial', ['id_header' => $id_header, 'id' => $id_material])->row_array();
            }
        }
        // print_r($data);
        echo json_encode($data);
    }

    /* count item to parent */
    private function countTotal($array, $tipe = 'part')
    {
        // print_r($array);
        // die;
        $col = [];
        $data_item = [];
        $data_sub_obj = [];
        $temp_sub_obj = [];
        $data_obj = [];
        $temp_obj = [];
        $data_section = [];
        $temp_section = [];
        $new_data = [];
        $temp_parent_section = [];
        $temp_parent_object = [];

        if(count($array) > 0){


            if($tipe == 'part'){
                $col = ['qty', 'harga'];
            }elseif($tipe == 'labour'){
                $col = ['hour', 'rate'];
            }else{
                $col = ['price', 'weight'];
            }

            foreach ($array as $key => $item) {
                $item['total'] = 0;
                if($item['tipe_item'] == 'item'){

                    $item['total'] = ($item[$col[0]] * $item[$col[1]]);
                    $item[$col[0]] = intval($item[$col[0]]);
                    if($col[1] == 'weight'){
                        $item[$col[1]] = $item[$col[1]];
                    }else{
                        $item[$col[1]] = intval($item[$col[1]]);
                    }

                    if($item['tipe_parent'] == 'section'){
                        array_push($temp_parent_section, ['id_parent' => $item['id_parent'], 'total' => $item['total']]);
                    }

                    if($item['tipe_parent'] == 'object'){
                        array_push($temp_parent_object, ['id_parent' => $item['id_parent'], 'total' => $item['total']]);
                    }
                    
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

            $new_object = [];
            foreach ($data_obj as $key => $object) {
                // $key = array_search($object['id'], array_column($temp_parent_object, 'id_parent'));
                // if($key !== false){
                //     $object['total'] += $temp_parent_object[$key]['total'];
                // }
                foreach ($temp_parent_object as  $value) {
                    if($value['id_parent'] == $object['id']){
                        $object['total'] += $value['total'];
                    }
                }
                $new_object[] = $object;
            }

            foreach ($temp_section as $key => $section) {
                $section['total'] = 0;
                foreach ($new_object as $key => $object) {
                    if($section['tipe_item'] == 'section'){
                        if($object['id_parent'] == $section['id']){
                            $section['total'] += $object['total'];
                        }
                    }
                }
                $data_section[] = $section;
            }   

            $new_section = [];    
            foreach ($data_section as $key => $section) {

                foreach ($temp_parent_section as  $value) {
                    if($value['id_parent'] == $section['id']){
                        $section['total'] += $value['total'];
                    }
                }
                $new_section[] = $section;
            }        

            $new_data = array_merge($data_item, $data_sub_obj, $new_object, $new_section);

            }
        // print_r($new_data);
        return $new_data;

    }

    /* saving general info */
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
        $default_dept_labour = ['ENGINEERING','PRODUCTION'];
        $action = $this->input->post('action-item');
        // $this->db->trans_begin();
        if($action == 1){
            if( $this->quotation->insertDetailPart() == TRUE){
                $code = 1;
                $tipe_item = '';
                $id_parent_lb = 0;
                $id_parent_rm = 0;
                $id_temp = 0;
                $last_id = $this->db->insert_id();
                if( $this->input->post('tipe_item-item') != 'item'){
                    if( $this->input->post('tipe_item-item') == 'section' ){
                        $tipe_item = 'section';
                    }elseif ( $this->input->post('tipe_item-item') == 'object' ) {
                        $tipe_item = 'object';
                        $id_temp = $this->db->get_where('part_jasa', ['id' => $last_id])->row()->id_parent;
                        $id_parent_lb = $this->db->get_where('labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    }else{
                        $tipe_item = 'sub_object';
                        $id_temp = $this->db->get_where('part_jasa', ['id' => $last_id])->row()->id_parent;
                        $id_parent_lb = $this->db->get_where('labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    }

                    $this->db->insert('labour', 
                        [
                            'id_header' => $this->input->post('id_header-item'),
                            'tipe_item' => $tipe_item,
                            'tipe_id' => $this->input->post('tipe_id-item'),
                            'tipe_name' => $this->input->post('tipe_name-item'),
                            'id_parent' => $id_parent_lb,
                            'id_part_jasa' => $last_id
                        ]
                    );

                    $last_id_labour = $this->db->insert_id();

                    $this->db->insert('rawmaterial',
                        [
                            'id_header' => $this->input->post('id_header-item'),
                            'tipe_item' => $tipe_item,
                            'tipe_id' => $this->input->post('tipe_id-item'),
                            'tipe_name' => $this->input->post('tipe_name-item'),
                            'id_parent' => $id_parent_rm,
                            'id_part_jasa' => $last_id
                        ]
                    );

                    $last_id_material = $this->db->insert_id();

                    // $this->db->trans_rollback();
                    $default = $this->db->get('default_labour')->result_array();
                    for ($i=0; $i < count($default_dept_labour); $i++) { 
                        foreach ($default as $key => $value) {
                            if($value['name'] == $default_dept_labour[$i]){
                                $field = [
                                    'id_header' => $this->input->post('id_header-item'),
                                    'tipe_item' => 'item',
                                    'id_parent' => $last_id_labour,
                                    'id_part_jasa' => $last_id,
                                    'tipe_id' => $this->input->post('tipe_id-item'),
                                    'tipe_name' => $default_dept_labour[$i],
                                    'id_labour' => $value['budget_id'],
                                    'rate' => $value['rate'],
                                    'aktivitas' => $value['aktivitas'],
                                    'sub_aktivitas' => $value['sub_aktivitas']
                                ];

                                $this->db->insert('labour', $field);
                            }                                
                        }
                    }
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

    public function getKategori(){
        $obj = $this->db->select('accno as id, TRIM(`desc`) as text', false)->where_in('header', ['10000','20000'])->get('akunbg')->result();
        echo json_encode($obj);
    }

    public function getCustomer(){
        $obj = $this->db->select('custid as id, TRIM(`nama`) as text', false)->get('customer')->result();
        echo json_encode($obj);
    }

    public function getPIC(){
        $obj = $this->db->select('id_personalia as id, TRIM(`nama`) as text', false)->like('departemen','MKTG')->get('personal')->result();
        echo json_encode($obj);
    }

    public function delItem(){
        $code = 0;
        $msg = 'Terjadi kesalahan!';
        $post = $this->input->post();


        if($post['tipe_item'] != 'item'){
            $child = $this->db->get_where($post['table'], ['id_parent' => $post['id']])->num_rows();

            if($child > 0){

                $msg = 'Hapus sub terlebih dahulu';
                echo json_encode(['data'=>['code'=>$code,'message'=>$msg]]);
                return;

            }else{

                $this->db->trans_begin();
                $del = $this->db->delete($post['table'], ['id' => $post['id']]);

                if($del){
                    // cek total hour labour
                    $parent_labour = $this->db->get_where('labour', ['id_part_jasa' => $post['id']])->row();
                    $total_labour = $this->db->select('SUM(hour) as total', false)->get_where('labour', ['id_parent' => $parent_labour->id])->row()->total;

                    // cek child material
                    $parent_material = $this->db->get_where('rawmaterial', ['id_part_jasa' => $post['id']])->row();
                    $child_material = $this->db->get_where('rawmaterial', ['id_parent' => $parent_material->id])->num_rows();

                    if(intval($total_labour) > 0 || $child_material > 0){
                        $this->db->trans_rollback();
                        $msg = 'Set jumlah hour (labour) menjadi 0 dahulu <br> dan hapus sub material!';
                        $code = 0;
                    }else{
                        $this->db->where('id', $parent_labour->id);
                        $this->db->or_where('id_parent', $parent_labour->id);
                        $delLabour = $this->db->delete('labour');

                        $this->db->where('id', $parent_material->id);
                        $this->db->or_where('id_parent', $parent_material->id);
                        $dellMaterial = $this->db->delete('rawmaterial');
                        // echo $this->db->last_query();
                        if($delLabour && $dellMaterial){
                            $this->db->trans_commit();
                            $code = 1;
                            $msg = '';
                        }else{
                            $this->db->trans_rollback();
                            $code = 0;
                            $msg = 'gagal Hapus data!';
                        }
                    }
                }
            }

        }else{

            $this->db->trans_begin();
            $del = $this->db->delete($post['table'], ['id' => $post['id']]);
            // echo $this->db->last_query();
            if($del){
                $this->db->trans_commit();
                $code = 1;
                $msg = '';
            }else{
                $this->db->trans_rollback();
                $code = 0;
                $msg = 'Tidak dapat menghapus data!';
            }
        }
        

        echo json_encode(['data'=>['code'=>$code,'message'=>$msg]]);
    }

    public function saveHourLabour(){
        $code = 0;
        $last_value = 0;
        $id_object = 0;
        $id_section = 0;
        $this->db->update('labour', ['hour' => $this->input->post('hour')], ['id' => $this->input->post('id')]);
        $item = $this->db->get_where('labour', ['id' => $this->input->post('id')])->row();
        if( $this->db->affected_rows() > 0){
            $code = 1;
        }else{
            $last_value = $item->hour;
        }

        $parent = $this->db->get_where('labour', ['id' => $item->id_parent])->row();

        if( $parent->tipe_item != 'section'){

            if($parent->tipe_item == 'object'){
                $id_section = $this->db->get_where('labour', ['id' => $item->id_parent])->row()->id_parent;
            }else{
                $id_object = $this->db->get_where('labour', ['id' => $item->id_parent])->row()->id_parent;
                $id_section = $this->db->get_where('labour', ['id' => $id_object])->row()->id_parent;
            }
        }
        echo json_encode([
            "code" => $code, 
            "last_value" => $last_value,
            "tipe_parent" => $parent->tipe_item,
            "id_object" => $id_object,
            "id_section" => $id_section
        ]);
    }

    public function getMaterialCode()
    {
        $obj = $this->db->select('mrawmaterial.*, CONCAT( TRIM(part_name)," (",item_code,")" ) as name', false)->get('mrawmaterial')->result();
        
        echo json_encode($obj);
    }

    public function saveMaterial(){
        $code = 0;
        $message = '';
        $action = $this->input->post('action-material');
        if($action == 1){
                
                if( $this->quotation->insertMaterial() == TRUE){
                    $code = 1;
                }

            }else{
                if( $this->quotation->udpateMaterial() == TRUE ){
                    $code = 1;
                }
            }
        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }
    
}