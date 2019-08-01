<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Quotation extends CI_Controller
{
    private $identity; // store session
    private $sgedb;

    public function __construct()
    {
        parent::__construct();
        $this->identity = $this->session->userdata('identity');
        $this->load->model('Quotation_model', 'quotation');
        $this->load->library('form_validation');
        $this->sgedb = $this->load->database('sgedb', TRUE);
        $this->load->library('reporter');
    }

    public function index($id = NULL)
    {
        $data = [];
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $data['param'] = $id;

        $this->load->view('cms/_detail_quotation', $data);
    }

    public function getDataHeader($id = NULL, $json = true)
    {
        $code = 1;
        $object = [];
        $message = '';
        if ($id != NULL) {
            // $object = $this->model->getRecord(['table'=>'header','where'=>['id'=>$id]]);
            $object = $this->db->select("{$this->db->database}.header.*, sgedb.personal.nama as pic_name, sgedb.customer.nama as customer_name", false)
                ->join('sgedb.customer', "{$this->db->database}.header.customer = sgedb.customer.custid")
                ->join('sgedb.personal', "{$this->db->database}.header.pic_marketing = sgedb.personal.id_personalia")
                ->where('id', $id)
                ->get("{$this->db->database}.header")
                ->row();
        } else {
            // $object = $this->model->getList(['table'=>'header']);
            $object = $this->db->select("{$this->db->database}.header.*, sgedb.personal.nama as pic_name, sgedb.customer.nama as customer_name", false)
                ->join('customer', "{$this->db->database}.header.customer = sgedb.customer.custid")
                ->join('personal', "{$this->db->database}.header.pic_marketing = sgedb.personal.id_personalia")
                ->get("{$this->db->database}.header")
                ->result();
        }

        if (!$json)
            return $object;

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
    public function getDataPart($id_header = NULL, $id_part = NULL, $json = true)
    {
        $object = [];
        $data = [];
        if ($id_header != NULL) {
            if ($id_part == NULL) {
                $object = $this->db->query("SELECT
                            j.*,
                            (SELECT
                                    tipe_item
                                FROM
                                    {$this->db->database}.part_jasa p
                                WHERE
                                    p.id = j.id_parent) AS tipe_parent,
                            k.`desc` AS nama_kategori
                        FROM
                            {$this->db->database}.`part_jasa` j
                                LEFT JOIN
                            `sgedb`.`akunbg` k ON j.kategori = k.accno
                        WHERE
                            j.id_header ='$id_header'")->result_array();
                $data = $this->countTotal($object);
            } else {
                $data = $this->db->get_where('part_jasa', ['id_header' => $id_header, 'id' => $id_part])->row_array();
            }
        }
        // echo $this->db->last_query();die;
        if (!$json)
            return $data;
        echo json_encode($data);
    }

    /* Get data for labour tab*/
    public function getDataLabour($id_header = NULL, $id_labour = NULL, $json = TRUE, $filter = TRUE)
    {
        $object = [];
        $data = [];
        if ($id_header != NULL) {
            if ($id_labour == NULL) {
                // $object = $this->db->get_where('v_labour', ['id_header' => $id_header])->result_array();
                $object = $this->db->query('SELECT
                    `l`.*,id as opsi,
                            (SELECT
                                    tipe_item
                                FROM
                                    labour b
                                WHERE
                                    b.id = l.id_parent) AS tipe_parent
                        FROM
                            `labour` `l`
                        WHERE
                            l.id_header ="' . $id_header . '" ')->result_array();

                // $data = $this->countTotal($object, 'labour');
                $temp = $this->countTotal($object, 'labour');
                if ($filter) {
                    $data = array_filter($temp, function ($v) {
                        if ($v['tipe_item'] != 'item') {
                            return $v;
                        }
                    });
                } else {
                    $data = $temp;
                }
            } else {
                $data = $this->db->get_where('v_labour', ['id_header' => $id_header, 'id' => $id_labour])->row_array();
                // $data['rate'] = $data['rate'];
            }
            // echo $this->db->last_query();die;
        }

        if ($json)
            echo json_encode($data);
        else
            return $data;
    }

    function getDataMaterial($id_header = NULL, $id_material = NULL, $json = TRUE)
    {
        $object = [];
        $data = [];
        if ($id_header != NULL) {
            if ($id_material == NULL) {
                $object = $this->db->get_where('v_rawmaterial', ['id_header' => $id_header])->result_array();
                $data = $this->countTotal($object, 'rawmaterial');
            } else {
                $data = $this->db->get_where('v_rawmaterial', ['id_header' => $id_header, 'id' => $id_material])->row_array();
            }
        }
        // print_r($data);
        if ($json)
            echo json_encode($data);
        else
            return $data;
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

        if (count($array) > 0) {


            if ($tipe == 'part') {
                $col = ['qty', 'harga'];
            } elseif ($tipe == 'labour') {
                $col = ['hour', 'rate'];
            } else {
                $col = ['price', 'weight'];
            }

            foreach ($array as $key => $item) {
                $item['total'] = 0;
                if ($item['tipe_item'] == 'item') {

                    /* Cek deleted */
                    $item['total'] = 0;
                    if (!$item['deleted'])
                        $item['total'] = ($item[$col[0]] * $item[$col[1]]);

                    $item[$col[0]] = floatval($item[$col[0]]);
                    $item[$col[1]] = floatval($item[$col[1]]);

                    if ($item['tipe_parent'] == 'section') {
                        array_push($temp_parent_section, ['id_parent' => $item['id_parent'], 'total' => $item['total']]);
                    }

                    if ($item['tipe_parent'] == 'object') {
                        array_push($temp_parent_object, ['id_parent' => $item['id_parent'], 'total' => $item['total']]);
                    }

                    $data_item[] = $item;
                }

                if ($item['tipe_item'] == 'sub_object') {
                    $temp_sub_obj[] = $item;
                }

                if ($item['tipe_item'] == 'object') {
                    $temp_obj[] = $item;
                }

                if ($item['tipe_item'] == 'section') {
                    $temp_section[] = $item;
                }
            }



            foreach ($temp_sub_obj as $key => $so) {
                $so['total'] = 0;
                foreach ($data_item as $key => $item) {
                    if ($so['tipe_item'] == 'sub_object') {
                        if ($item['id_parent'] == $so['id']) {
                            $so['total'] += $item['total'];
                        }
                    }
                }
                $data_sub_obj[] = $so;
            }



            foreach ($temp_obj as $key => $object) {
                $object['total'] = 0;
                foreach ($data_sub_obj as $key => $so) {
                    if ($object['tipe_item'] == 'object') {
                        if ($so['id_parent'] == $object['id']) {
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
                    if ($value['id_parent'] == $object['id']) {
                        $object['total'] += $value['total'];
                    }
                }
                $new_object[] = $object;
            }

            foreach ($temp_section as $key => $section) {
                $section['total'] = 0;
                foreach ($new_object as $key => $object) {
                    if ($section['tipe_item'] == 'section') {
                        if ($object['id_parent'] == $section['id']) {
                            $section['total'] += $object['total'];
                        }
                    }
                }
                $data_section[] = $section;
            }

            $new_section = [];
            foreach ($data_section as $key => $section) {

                foreach ($temp_parent_section as  $value) {
                    if ($value['id_parent'] == $section['id']) {
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
        } else {

            if ($action == 1) {

                if ($this->quotation->insertGeneralInfo() == TRUE) {
                    $code = 1;
                    $last_id = $this->db->insert_id();
                }
            } else {
                if ($this->quotation->udpateGeneralInfo() == TRUE) {
                    $code = 1;
                    $last_id = $this->input->post('id_header');
                }
            }
        }

        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        ), 'last_id' => $last_id));
    }

    public function saveItem()
    {
        // print_r($this->input->post());die;
        $code = 0;
        $message = '';
        $default_dept_labour = ['ENGINEERING', 'PRODUCTION'];
        $action = $this->input->post('action-item');
        // $this->db->trans_begin();
        if ($action == 1) {
            if ($this->quotation->insertDetailPart() == TRUE) {
                $code = 1;
                $tipe_item = '';
                $id_parent_lb = 0;
                $id_parent_rm = 0;
                $id_temp = 0;
                $last_id = $this->db->insert_id();
                if ($this->input->post('tipe_item-item') != 'item') {
                    if ($this->input->post('tipe_item-item') == 'section') {
                        $tipe_item = 'section';
                    } elseif ($this->input->post('tipe_item-item') == 'object') {
                        $tipe_item = 'object';
                        $id_temp = $this->db->get_where('part_jasa', ['id' => $last_id])->row()->id_parent;
                        $id_parent_lb = $this->db->get_where('labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    } else {
                        $tipe_item = 'sub_object';
                        $id_temp = $this->db->get_where('part_jasa', ['id' => $last_id])->row()->id_parent;
                        $id_parent_lb = $this->db->get_where('labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    }

                    $this->db->insert(
                        'labour',
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

                    $this->db->insert(
                        'rawmaterial',
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
                    for ($i = 0; $i < count($default_dept_labour); $i++) {
                        foreach ($default as $key => $value) {
                            if ($value['name'] == $default_dept_labour[$i]) {
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
        } else {
            if ($this->quotation->udpateDetailPart() == TRUE) {
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
        if ($action == 1) {

            if ($this->quotation->insertDetailLabour() == TRUE) {
                $code = 1;
            }
        } else {
            if ($this->quotation->udpateDetailLabour() == TRUE) {
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
        $obj = $this->sgedb->select('lp.stcd, CONCAT( TRIM(mstchd.nama)," - ",TRIM(mstchd.spek)," - ",TRIM(mstchd.maker)," [",mstchd.stcd,"]" ) as name, TRIM(mstchd.spek) as spek, TRIM(mstchd.maker) as maker, TRIM(mstchd.uom) as uom, TRIM(mstchd.nama) as nama, (lp.mkt) as harga, lp.remark', false)
            ->join('msprice lp', 'mstchd.stcd = lp.stcd')
            ->get('mstchd')->result();
        // echo $this->db->last_query();

        echo json_encode($obj);
    }

    public function getKategori()
    {
        $obj = $this->sgedb->select('accno as id, TRIM(`desc`) as text', false)
            ->where_in('header', ['10000', '20000'])
            // ->having('accno <>', '10001')
            ->having('accno <>', '10006')
            ->get('akunbg')
            ->result();
        echo json_encode($obj);
    }

    public function getUnit()
    {
        $obj = $this->sgedb->select('accno as id, TRIM(`desc`) as text', false)
            ->where_in('header', ['10000', '20000'])
            // ->having('accno <>', '10001')
            ->having('accno <>', '10006')
            ->get('akunbg')
            ->result();
        echo json_encode($obj);
    }

    public function getCustomer()
    {
        $obj = $this->sgedb->select('custid as id, TRIM(`nama`) as text', false)->get('customer')->result();
        echo json_encode($obj);
    }

    public function getPIC()
    {
        $obj = $this->sgedb->select('id_personalia as id, TRIM(`nama`) as text', false)->like('departemen', 'MKTG')->get('personal')->result();
        echo json_encode($obj);
    }

    public function delItem()
    {
        $code = 0;
        $msg = 'Terjadi kesalahan!';
        $post = $this->input->post();


        if ($post['tipe_item'] != 'item') {
            $child = $this->db->get_where($post['table'], ['id_parent' => $post['id'], 'deleted' => 0])->num_rows();

            if ($child > 0) {

                $msg = 'Hapus sub terlebih dahulu';
                echo json_encode(['data' => ['code' => $code, 'message' => $msg]]);
                return;
            } else {

                $this->db->trans_begin();
                /* Current using soft delete */
                // $del = $this->db->delete($post['table'], ['id' => $post['id']]);
                $del = $this->db->update($post['table'], ['deleted' => 1], ['id' => $post['id']]);

                if ($del) {
                    // cek total hour labour
                    $parent_labour = $this->db->get_where('labour', ['id_part_jasa' => $post['id'], 'deleted' => 0])->row();
                    $total_labour = $this->db->select('SUM(hour) as total', false)->get_where('labour', ['id_parent' => $parent_labour->id, 'deleted' => 0])->row()->total;

                    // cek child material
                    $parent_material = $this->db->get_where('rawmaterial', ['id_part_jasa' => $post['id'], 'deleted' => 0])->row();
                    $child_material = $this->db->get_where('rawmaterial', ['id_parent' => $parent_material->id, 'deleted' => 0])->num_rows();
                    // $this->db->trans_rollback();
                    // echo $this->db->last_query();
                    // die;

                    // var_dump(intval($total_labour));
                    // var_dump($child_material);
                    // $this->db->trans_rollback();
                    // die;
                    if (intval($total_labour) > 0 || $child_material > 0) {
                        $this->db->trans_rollback();
                        $msg = 'Set jumlah hour (labour) menjadi 0 dahulu <br> dan hapus sub material!';
                        $code = 0;
                    } else {
                        $this->db->where('id', $parent_labour->id);
                        $this->db->or_where('id_parent', $parent_labour->id);
                        /* Current using soft delete */
                        // $delLabour = $this->db->delete('labour');
                        $delLabour = $this->db->update('labour', ['deleted' => 1]);

                        $this->db->where('id', $parent_material->id);
                        $this->db->or_where('id_parent', $parent_material->id);
                        /* Current using soft delete */
                        // $dellMaterial = $this->db->delete('rawmaterial');
                        $dellMaterial = $this->db->update('rawmaterial', ['deleted' => 1]);
                        // echo $this->db->last_query();
                        if ($delLabour && $dellMaterial) {
                            $this->db->trans_commit();
                            $code = 1;
                            $msg = '';
                        } else {
                            $this->db->trans_rollback();
                            $code = 0;
                            $msg = 'gagal Hapus data!';
                        }
                    }
                }
            }
        } else {

            $this->db->trans_begin();
            /* current using soft delete */
            // $del = $this->db->delete($post['table'], ['id' => $post['id']]);
            $del = $this->db->update($post['table'], ['deleted' => 1], ['id' => $post['id']]);
            // echo $this->db->last_query();
            if ($del) {
                $this->db->trans_commit();
                $code = 1;
                $msg = '';
            } else {
                $this->db->trans_rollback();
                $code = 0;
                $msg = 'Tidak dapat menghapus data!';
            }
        }


        echo json_encode(['data' => ['code' => $code, 'message' => $msg]]);
    }

    public function saveHourLabour()
    {
        $code = 0;
        $last_value = 0;
        $id_object = 0;
        $id_section = 0;
        $this->db->update('labour', ['hour' => $this->input->post('hour')], ['id' => $this->input->post('id')]);
        $item = $this->db->get_where('labour', ['id' => $this->input->post('id')])->row();
        if ($this->db->affected_rows() > 0) {
            $code = 1;
        } else {
            $last_value = $item->hour;
        }

        $parent = $this->db->get_where('labour', ['id' => $item->id_parent])->row();

        if ($parent->tipe_item != 'section') {

            if ($parent->tipe_item == 'object') {
                $id_section = $this->db->get_where('labour', ['id' => $item->id_parent])->row()->id_parent;
            } else {
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

    public function saveMaterial()
    {
        $code = 0;
        $message = '';
        $action = $this->input->post('action-material');
        if ($action == 1) {

            if ($this->quotation->insertMaterial() == TRUE) {
                $code = 1;
            }
        } else {
            if ($this->quotation->udpateMaterial() == TRUE) {
                $code = 1;
            }
        }
        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }

    public function getDetailLabourByHeader($id_header, $id_parent, $type)
    {
        $data = [];
        $temp = $this->db->get_where('v_labour', ['id_header' => $id_header, 'id_parent' => $id_parent, 'tipe_name_view' => $type, 'tipe_item' => 'item'])->result_array();
        $no = 0;
        foreach ($temp as $key => $value) {
            $value['no'] = ++$no;
            $total = $value['hour'] * $value['rate'];
            $value['rate'] = number_format($value['rate']);
            $value['hour'] = intval($value['hour']);
            $value['total'] = number_format($total);
            $data[] = $value;
        }
        // echo $this->db->last_query();
        echo json_encode(['data' => $data]);
    }

    public function getSection($id_header)
    {
        $data = [];
        $result = $this->db->get_where('part_jasa', ['tipe_item' => 'section', 'id_header' => $id_header])->result_array();
        // echo $this->db->last_query();
        $no = 0;
        foreach ($result as $key => $value) {
            $no = ++$no;
            $value['no'] = $no;
            $value['aksi'] = "<button onclick='editQtySection(this, {$value['id']})' class='btn btn-xs btn-primary edit-qty-section{$value['id']}'><i class='fa fa-edit'></i> Edit</button>
            <button style='display:none;' onclick='saveQtySection(this, {$value['id']})' class='btn btn-xs btn-success save-qty-section{$value['id']}'><i class='fa fa-check'></i> Save</button>";
            $data[] = $value;
        }
        echo json_encode(['data' => $data]);
    }

    function saveSectionQty()
    {
        $post = $this->input->post();
        $this->db->update('part_jasa', ['qty' => $post['qty']], ['id' => $post['id']]);
        echo json_encode(['code' => 1, 'success' => true]);
    }

    public function printPart($id_header)
    {
        $rowTitle = $this->db->get_where('header', ['id' => $id_header])->row();
        $title = "Quot-Part-" . $rowTitle->inquiry_no . "-" . $rowTitle->project_name . "-" . $rowTitle->customer . "-" . date('dmY');
        $part = $this->getDataPart($id_header, NULL, false);
        $dataPart = array_filter($part, function($item){
            return $item['deleted'] == 0;
        });
        $material = $this->getDataMaterial($id_header, NULL, false);
        $dataMaterial = array_filter($material, function($item){
            return $item['deleted'] == 0;
        });
        $this->load->view('report/quotation_part', ['part' => $dataPart, 'material' => $dataMaterial, 'title' => $title]);
    }

    public function printLabour($id_header)
    {
        $rowTitle = $this->db->get_where('header', ['id' => $id_header])->row();
        $title = "Quot-Labour-" . $rowTitle->inquiry_no . "-" . $rowTitle->project_name . "-" . $rowTitle->customer . "-" . date('dmY');
        $labour = $this->getDataLabour($id_header, NULL, false, false);
        $dataLabour = array_filter($labour, function($item){
            return $item['deleted'] == 0;
        });
        $this->load->view('report/quotation_labour', ['labour' => $dataLabour, 'title' => $title]);
    }

    public function printSummary($id_header, $return = FALSE)
    {
        $rowTitle = $this->db->get_where('header', ['id' => $id_header])->row();
        $title = "Quot-Summary" . $rowTitle->inquiry_no . "-" . $rowTitle->project_name . "-" . $rowTitle->customer . "-" . date('dmY');
        // $dataPart = $this->getDataPart($id_header, NULL, false);
        // $dataMaterial = $this->getDataMaterial($id_header, NULL, false);
        // $dataLabour = $this->getDataLabour($id_header, NULL, false, false);
        $part = $this->getDataPart($id_header, NULL, false);
        $dataPart = array_filter($part, function($item){
            return $item['deleted'] == 0;
        });
        $material = $this->getDataMaterial($id_header, NULL, false);
        $dataMaterial = array_filter($material, function($item){
            return $item['deleted'] == 0;
        });
        $labour = $this->getDataLabour($id_header, NULL, false, false);
        $dataLabour = array_filter($labour, function($item){
            return $item['deleted'] == 0;
        });
        $summary = $this->reporter->getDataSummary($dataPart, $dataMaterial, $dataLabour);
        if ($return) {
            return $summary;
        } else {
            $this->load->view('report/quotation_summary', ['summary' => $summary, 'title' => $title, 'inquiry_no' => $rowTitle->inquiry_no]);
        }
    }

    public function saveAllowance()
    {
        $post = $this->input->post();
        $this->db->update(
            'header',
            ['allowance' => $post['allowance']],
            ['id' => $post['id']]
        );
        echo json_encode(['code' => 1, 'success' => true]);
    }

    public function getAmountAllowance()
    {
        $get = $this->input->get();
        $amount = $this->db->get_where('header', ['id' => $get['id']])->row()->allowance;
        echo json_encode(['data' => $amount, 'code' => 1, 'success' => true]);
    }

    // get counter except item
    public function getCounterItem()
    {
        $get = $this->input->get();
        $sql = '';
        $no = '';
        switch ($get['tipe_item']) {
            case 'section':
                $sql = "SELECT max(tipe_id) as seq from part_jasa where id_header = '{$get['id_header']}' and tipe_item = '{$get['tipe_item']}' ";
                $last = $this->db->query($sql)->row()->seq;
                $no = (int) $last + 1;
                break;
            default:
                $sql = "SELECT max(j.tipe_id) AS seq, k.tipe_id AS parent_seq from part_jasa j JOIN part_jasa k ON j.id_parent = k.id WHERE j.id_header = '{$get['id_header']}' AND j.tipe_item = '{$get['tipe_item']}' AND j.id_parent = '{$get['id_parent']}'";
                $data = $this->db->query($sql)->row();
                $seq = explode(".", $data->seq);
                $cur = 1;
                if (isset($seq[1]))
                    $cur = (int) $seq[1] + 1;
                $no = $data->parent_seq . '.' . $cur;
                break;
        }

        echo json_encode(['data' => $no]);
    }
}
