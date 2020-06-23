<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Bmaterial extends CI_Controller
{
    private $identity; // store session
    private $sgedb;

    public function __construct()
    {
        parent::__construct();
        $this->identity = $this->session->userdata('identity');
        $this->load->model('Bmaterial_model', 'bmaterial');
        $this->load->library('form_validation');
        $this->sgedb = $this->load->database('sgedb', TRUE);
        $this->load->library('breporter');
    }

    public function index($id = NULL)
    {
        $data = [];
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $data['param'] = $id;
        // $has_item = $this->checkHasItem($id, FALSE);

        /* Insert default section */
        // if (!$has_item)
        //     $this->saveDefaultSection($id);

        $this->load->view('cms/_detail_bill_material', $data);
    }

    public function getDataHeader($id = NULL, $json = true)
    {
        $code = 1;
        $object = [];
        $message = '';
        if ($id != NULL) {
            $object = $this->db->get_where('v_wo_bom', array('id' => $id))->result();
            // $object = $this->model->getRecord(['table'=>'header','where'=>['id'=>$id]]);
            // $object = $this->db->select('*');
            // $object = $this->db->from('v_wo_bom');
            // $object = $this->db->where('id', $id);
            // ->get("{$this->db->database}.v_wo_bom")
            //->row();
        } else {
            // // $object = $this->model->getList(['table'=>'v_header']);
            // $object = $this->db->select("{$this->db->database}.v_header.*, sgedb.personal.nama as pic_name, sgedb.customer.nama as customer_name", false)
            //     ->join('customer', "{$this->db->database}.v_header.customer = sgedb.customer.custid")
            //     ->join('personal', "{$this->db->database}.v_header.pic_marketing = sgedb.personal.id_personalia")
            //     ->get("{$this->db->database}.v_header")
            //     ->result();
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
        // var_dump($id_header);
        // var_dump($id_part);
        // var_dump($json);
        // die;
        $object = [];
        $data = [];
        if ($id_header != NULL) {
            $sql_deleted = isset($_GET['show-deleted']) && $_GET['show-deleted'] == 0 ? " HAVING deleted = '0'" : "";
            if ($id_part == NULL) {
                $object = $this->db->query("SELECT
                            j.*,
                            (SELECT
                                    tipe_item
                                FROM
                                    {$this->db->database}.bom_part_jasa p
                                WHERE
                                    p.id = j.id_parent) AS tipe_parent,
                            trim(k.`desc`) AS nama_kategori
                        FROM
                            {$this->db->database}.`bom_part_jasa` j
                                LEFT JOIN
                            `sgedb`.`akunbg` k ON j.kategori = k.accno
                        WHERE
                            j.id_header ='$id_header' $sql_deleted")->result_array();
                $data = $this->countTotal($object);
            } else {
                $data = $this->db->get_where('bom_part_jasa', ['id_header' => $id_header, 'id' => $id_part])->row_array();
            }
        }
        // echo $this->db->last_query();
        // die;
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
            $sql_deleted = isset($_GET['show-deleted']) && $_GET['show-deleted'] == 0 ? " HAVING deleted = '0'" : "";
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
                            l.id_header ="' . $id_header . '" ' . $sql_deleted)->result_array();

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
            if (isset($_GET['show-deleted']) && $_GET['show-deleted'] == 0) {
                $this->db->having('deleted', 0);
            }
            if ($id_material == NULL) {
                $query = $this->db->get_where('v_bom_rawmaterial', ['id_header' => $id_header]);
                $object = $query->result_array();
                $data = $this->countTotal($object, 'bom_rawmaterial');
            } else {
                $data = $this->db->get_where('v_bom_rawmaterial', ['id_header' => $id_header, 'id' => $id_material])->row_array();
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
                        $item['total'] = round($item[$col[0]] * $item[$col[1]]);

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
        $this->form_validation->set_rules($this->bmaterial->getRules($action));

        if ($this->form_validation->run() == FALSE) {
            $delimiter = '- ';
            $this->form_validation->set_error_delimiters($delimiter, '');
            $message = validation_errors();
        } else {

            if ($action == 1) {

                if ($this->quotation->insertGeneralInfo() == TRUE) {
                    $code = 1;
                    $last_id = $this->db->insert_id();
                    /* INSERT DEFAULT SECTION */
                    // $this->saveDefaultSection($last_id);
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

    public function saveDefaultSection($id_header)
    {


        $arr = [
            ['tipe_name-item' => 'ONSITE', 'tipe_id-item' => '1', 'id_header-item' => $id_header, 'group-item' => 0],
            ['tipe_name-item' => 'INSTALLATION', 'tipe_id-item' => '2', 'id_header-item' => $id_header, 'group-item' => 1]
        ];

        $wrap = [
            'tipe_item-item' => 'section',
            'item_code-item' => '',
            'spec-item' => '',
            'satuan-item' => '',
            'qty-item' => '0',
            'item_code' => '',
            'id_parent-item' => '0',
            'id_header-item' => '',
            'action-item' => '1',
            'id-item' => '1',
            'tipe_id-item' => '19',
            'tipe_name-item' => '',
            'item_name-item' => '',
            'merk-item' => '',
            'harga-item' => '',
            'kategori-item' => '',
            'harga-item-clean' => '0',
            'remark-harga' => ''

        ];

        foreach ($arr as $key => $value) {
            $data = $wrap;
            $data['tipe_name-item'] = $value['tipe_name-item'];
            $data['tipe_id-item'] = $value['tipe_id-item'];
            $data['id_header-item'] = $value['id_header-item'];
            $data['group-item'] = $value['group-item'];
            $_POST = $data;
            $code = $this->saveItem(FALSE);
        }

        if ($code)
            return TRUE;
        return FALSE;
    }

    // save multi item
    public function saveMultiItem()
    {
        // var_dump($_POST['data']);
        // die;
        $post_data = json_decode($_POST['data'], TRUE);
        $id_header = $_POST['id_header'];
        $id_parent = $_POST['id_parent'];
        $item = [];
        $msg_exists = '';
        $success = TRUE;
        $msg = 'Input Data Berhasil';
        $this->db->trans_begin();
        foreach ($post_data as $key => $value) {

            $exist = $this->db->get_where(
                'bom_part_jasa',
                [
                    'id_header' => $id_header,
                    'id_parent' => $id_parent,
                    'item_code' => $value['stcd'],
                    'deleted' => 0
                ]
            )->num_rows();
            /* Check item exist in table */
            if ($exist > 0) {
                $msg_exists .= "\n {$value['item_name']} sudah ada!";
                continue;
            }
            $temp = [];
            $temp['tipe_item-item'] = 'item';
            $temp['item_code-item'] = $value['stcd'];
            $temp['spec-item'] = $value['spek'];
            $temp['satuan-item'] = $value['uom'];
            $temp['qty-item'] = $value['qty'];
            $temp['item_code'] = $value['stcd'];
            $temp['id_parent-item'] = $id_parent;
            $temp['id_header-item'] = $id_header;
            $temp['action-item'] = 1;
            $temp['id-item'] = 1;
            $temp['tipe_id-item'] = '';
            $temp['tipe_name-item'] = '';
            $temp['item_name-item'] = $value['item_name'];
            $temp['merk-item'] = $value['maker'];
            $temp['harga-item'] = $value['harga'];
            $temp['kategori-item'] = $value['category'];
            $temp['harga-item-clean'] = floatval($value['harga']);
            $temp['remark-harga'] = $value['remark'];
            $temp['users'] = $value['users'];

            $_POST = $temp;
            if (!$this->bmaterial->insertDetailPart()) {
                $success = FALSE;
                $msg = 'Input Data Gagal';
                $this->db->trans_rollback();
            }
        }
        // die;
        $this->db->trans_commit();
        echo json_encode(['success' => $success, 'message' => $msg, 'msg_exists' => $msg_exists]);
    }

    // save item
    public function saveItem($json = TRUE)
    {
        // var_dump($_POST);
        // die;
        // var_dump($this->input->post());
        // var_dump('vvvv');
        // die;
        $code = 0;
        $message = '';
        $default_dept_labour = ['ENGINEERING', 'PRODUCTION'];
        $action = $this->input->post('action-item');
        // var_dump($action);
        // die;
        // $this->db->trans_begin();
        if ($action == 1) {
            if ($this->bmaterial->insertDetailPart() == TRUE) {
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
                        $id_temp = $this->db->get_where('bom_part_jasa', ['id' => $last_id])->row()->id_parent;
                        //$id_parent_lb = $this->db->get_where('bom_labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('bom_rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    } else {
                        $tipe_item = 'sub_object';
                        $id_temp = $this->db->get_where('bom_part_jasa', ['id' => $last_id])->row()->id_parent;
                        //$id_parent_lb = $this->db->get_where('bom_labour', ['id_part_jasa' => $id_temp])->row()->id;
                        $id_parent_rm = $this->db->get_where('bom_rawmaterial', ['id_part_jasa' => $id_temp])->row()->id;
                    }

                    //  $this->db->insert(
                    //     'bom_labour',
                    //     [
                    //         'id_header' => $this->input->post('id_header-item'),
                    //         'tipe_item' => $tipe_item,
                    //         'tipe_id' => $this->input->post('tipe_id-item'),
                    //         'tipe_name' => $this->input->post('tipe_name-item'),
                    //         'id_parent' => $id_parent_lb,
                    //         'id_part_jasa' => $last_id
                    //     ]
                    //     );

                    // print_r($tes);
                    // die;
                    $last_id_labour = $this->db->insert_id();


                    $this->db->insert(
                        'bom_rawmaterial',
                        [
                            'id_header' => $this->input->post('id_header-item'),
                            'tipe_item' => $tipe_item,
                            'tipe_id' => $this->input->post('tipe_id-item'),
                            'tipe_name' => $this->input->post('tipe_name-item'),
                            'id_parent' => $id_parent_rm,
                            'id_part_jasa' => $last_id,
                            'users' => $this->input->post('users')
                        ]
                    );
                    // print_r($this->db->last_query());

                    $last_id_material = $this->db->insert_id();

                    // $this->db->trans_rollback();
                    //$default = $this->db->get('default_labour')->result_array();
                    //for ($i = 0; $i < count($default_dept_labour); $i++) {
                    // foreach ($default as $key => $value) {
                    //     if ($value['name'] == $default_dept_labour[$i]) {
                    //         $field = [
                    //             'id_header' => $this->input->post('id_header-item'),
                    //             'tipe_item' => 'item',
                    //             'id_parent' => $last_id_labour,
                    //             'id_part_jasa' => $last_id,
                    //             'tipe_id' => $this->input->post('tipe_id-item'),
                    //             'tipe_name' => $default_dept_labour[$i],
                    //             'id_labour' => $value['budget_id'],
                    //             'rate' => $value['rate'],
                    //             'aktivitas' => $value['aktivitas'],
                    //             'sub_aktivitas' => $value['sub_aktivitas']
                    //         ];

                    //         $this->db->insert('bom_labour', $field);
                    //     }
                    // }
                    // }
                }
            }
        } else {
            if ($this->bmaterial->udpateDetailPart() == TRUE) {
                $code = 1;
            }
        }

        if ($json) {
            echo json_encode(array('data' => array(
                'code' => $code,
                'message' => $message
            )));
        } else {
            return $code;
        }
    }

    public function saveLabour()
    {
        $code = 0;
        $message = '';
        $action = $this->input->post('action-labour');
        if ($action == 1) {

            if ($this->bmaterial->insertDetailLabour() == TRUE) {
                $code = 1;
            }
        } else {
            if ($this->bmaterial->udpateDetailLabour() == TRUE) {
                $code = 1;
            }
        }
        echo json_encode(array('data' => array(
            'code' => $code,
            'message' => $message
        )));
    }
    function getSatuan()
    {
        $data = [];
        $sql = $this->sgedb->select('kode as id, name as text')->get('tblsatuan');
        $sqlData = $sql->result();
        //$data[] = $sqlData;
        echo json_encode($sqlData);
    }

    public function getItemCode($set_null = 1)
    {
        // var_dump(1);
        // die;
        $data = [];
        // $obj = $this->sgedb->select('lp.stcd, lp.stcd as id , TRIM(mstchd.nama) as item_name,
        //     CONCAT( TRIM(mstchd.nama)," - ",TRIM(mstchd.spek)," - ",TRIM(mstchd.maker)," - ",lp.mkt," - "," [",mstchd.stcd,"]" ) as name, 
        //     TRIM(mstchd.nama) as nama,
        //     TRIM(mstchd.spek) as spek, 
        //     TRIM(mstchd.maker) as maker, 
        //     TRIM(mstchd.uom) as uom, 
        //     CONCAT( TRIM(mstchd.nama)," - ",TRIM(mstchd.spek)," - ",TRIM(mstchd.maker)," - ",lp.mkt," - "," [",mstchd.stcd,"]" ) as text, 
        //     (lp.mkt) as harga, lp.remark', false)
        //     ->from('sgedb.mstchd')
        //     ->join('sgedb.msprice lp', 'mstchd.stcd = lp.stcd')
        //     ->not_like('mstchd.stcd', 'OFF', 'after')
        //     ->not_like('mstchd.stcd', 'SNS', 'after')
        //     ->not_like('mstchd.stcd', 'ATK', 'after')
        //     ->not_like('mstchd.stcd', 'INV', 'after')
        $obj = $this->sgedb->select('lp.stcd, lp.stcd as id , TRIM(mstchd.nama) as item_name,
            CONCAT( TRIM(mstchd.nama)," - ",TRIM(mstchd.spek)," - ",TRIM(mstchd.maker)," - ",lp.mkt," - "," [",mstchd.stcd,"]" ) as name, 
            TRIM(mstchd.nama) as nama,
            TRIM(mstchd.spek) as spek, 
            TRIM(mstchd.maker) as maker, 
            TRIM(mstchd.uom) as uom, 
            CONCAT( TRIM(mstchd.nama)," - ",TRIM(mstchd.spek)," - ",TRIM(mstchd.maker)," - ",lp.mkt," - "," [",mstchd.stcd,"]" ) as text, 
            (lp.mkt) as harga, lp.remark,ifnull(stock.balance,0) as stock', false)
            ->from('sgedb.mstchd')
            ->join('sgedb.msprice lp', 'mstchd.stcd = lp.stcd', 'left')
            ->join('sgedb.v_stock stock', 'mstchd.stcd = stock.stcd', 'left')
            ->get()->result_array();
        if ($set_null) {
            array_unshift($obj, [
                'harga' => "",
                'id' => "",
                'maker' => "",
                'name' => "",
                'remark' => "",
                'spek' => "",
                'stcd' => "",
                'text' => "",
                'uom' => ""
            ]);
            $data = $obj;
        } else {
            $data = [];
            $no = 0;
            foreach ($obj as $key => $row) {
                $no++;
                $row['qty'] = '';
                $row['no'] = '';
                $row['action'] = '';
                $row['harga'] =  number_format($row['harga']);
                $data['data'][] = $row;
            }
        }

        echo json_encode($data);
    }

    public function getKategori()
    {
        $obj = $this->sgedb->select('accno as id, TRIM(`desc`) as text', false)
            ->where_in('header', ['10000', '20000'])
            ->or_where('accno', '40006')
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

    public function getEstimator()
    {
        $obj = $this->sgedb->select('id_personalia as id, TRIM(`nama`) as text', false)->get('personal')->result();
        // echo $this->sgedb->last_query();
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
                    //$parent_labour = $this->db->get_where('labour', ['id_part_jasa' => $post['id'], 'deleted' => 0])->row();
                    //$total_labour = $this->db->select('SUM(hour) as total', false)->get_where('labour', ['id_parent' => $parent_labour->id, 'deleted' => 0])->row()->total;

                    // cek child material
                    $parent_material = $this->db->get_where('bom_rawmaterial', ['id_part_jasa' => $post['id'], 'deleted' => 0])->row();
                    $child_material = $this->db->get_where('bom_rawmaterial', ['id_parent' => $parent_material->id, 'deleted' => 0])->num_rows();
                    // echo $this->db->last_query();
                    // die;
                    // $this->db->trans_rollback();

                    // var_dump(intval($total_labour));
                    // var_dump($child_material);
                    // $this->db->trans_rollback();
                    // die;
                    //if (intval($total_labour) > 0 || $child_material > 0) {
                    if ($child_material > 0) {
                        $this->db->trans_rollback();
                        $msg = 'Set jumlah hour (labour) menjadi 0 dahulu <br> dan hapus sub material!';
                        $code = 0;
                    } else {
                        // $this->db->where('id', $parent_labour->id);
                        // $this->db->or_where('id_parent', $parent_labour->id);
                        /* Current using soft delete */
                        // $delLabour = $this->db->delete('labour');
                        // $delLabour = $this->db->update('labour', ['deleted' => 1]);

                        $this->db->where('id', $parent_material->id);
                        $this->db->or_where('id_parent', $parent_material->id);
                        /* Current using soft delete */
                        // $dellMaterial = $this->db->delete('rawmaterial');
                        $dellMaterial = $this->db->update('bom_rawmaterial', ['deleted' => 1]);
                        // echo $this->db->last_query();
                        if ($dellMaterial) {
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

            if ($this->bmaterial->insertMaterial() == TRUE) {
                $code = 1;
            }
        } else {
            if ($this->bmaterial->udpateMaterial() == TRUE) {
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
        $result = $this->db->get_where('bom_part_jasa', ['tipe_item' => 'section', 'id_header' => $id_header])->result_array();
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
        $this->db->update('bom_part_jasa', ['qty' => $post['qty'], 'group' => $post['group']], ['id' => $post['id']]);
        echo json_encode(['code' => 1, 'success' => true]);
    }

    public function printPart($id_header)
    {
        $rowTitle = $this->db->get_where('v_wo_bom', ['id' => $id_header])->row();
        $title = "BOM-Part-" . $rowTitle->wono . "-" . $rowTitle->desc . "-" . $rowTitle->customer . "-" . date('dmY');
        $part = $this->getDataPart($id_header, NULL, false);
        $dataPart = array_filter($part, function ($item) {
            return $item['deleted'] == 0;
        });
        $material = $this->getDataMaterial($id_header, NULL, false);
        $dataMaterial = array_filter($material, function ($item) {
            return $item['deleted'] == 0;
        });
        $this->load->view('report/bom_part', ['part' => $dataPart, 'material' => $dataMaterial, 'title' => $title]);
    }

    public function printLabour($id_header)
    {
        $rowTitle = $this->db->get_where('v_header', ['id' => $id_header])->row();
        $title = "Quot-Labour-" . $rowTitle->inquiry_no . "-" . $rowTitle->project_name . "-" . $rowTitle->customer . "-" . date('dmY');
        $labour = $this->getDataLabour($id_header, NULL, false, false);
        $dataLabour = array_filter($labour, function ($item) {
            return $item['deleted'] == 0;
        });
        $this->load->view('report/quotation_labour', ['labour' => $dataLabour, 'title' => $title]);
    }

    public function printSummary($id_header, $return = FALSE)
    {
        $rowTitle = $this->db->get_where('v_wo_bom', ['id' => $id_header])->row();
        // var_dump($rowTitle);
        // die;
        $title = "BOM-Summary";
        // $dataPart = $this->getDataPart($id_header, NULL, false);
        // $dataMaterial = $this->getDataMaterial($id_header, NULL, false);
        // $dataLabour = $this->getDataLabour($id_header, NULL, false, false);
        $part = $this->getDataPart($id_header, NULL, false);
        $dataPart = array_filter($part, function ($item) {
            return $item['deleted'] == 0;
        });
        $material = $this->getDataMaterial($id_header, NULL, false);
        $dataMaterial = array_filter($material, function ($item) {
            return $item['deleted'] == 0;
        });
        $labour = $this->getDataLabour($id_header, NULL, false, false);
        $dataLabour = array_filter($labour, function ($item) {
            return $item['deleted'] == 0;
        });
        // var_dump($material);
        // die;
        $summary = $this->breporter->getDataSummary($dataPart, $dataMaterial, $dataLabour);
        $_GET['id'] = $id_header;
        if ($return) {
            return $summary;
        } else {
            $this->load->view('report/bom_summary', ['summary' => $summary, 'title' => $title, 'inquiry_no' => $rowTitle->wono, 'allowance' => $this->getAmountAllowance(TRUE)]);
        }
    }

    public function printDetailSummary($id_header)
    {
        $part = $this->getDataPart($id_header, NULL, false);
        $part = array_filter($part, function ($item) {
            return $item['deleted'] == 0;
        });
        $material = $this->getDataMaterial($id_header, NULL, false);
        $material = array_filter($material, function ($item) {
            return $item['deleted'] == 0;
        });
        $labour = $this->getDataLabour($id_header, NULL, false, false);
        $labour = array_filter($labour, function ($item) {
            return $item['deleted'] == 0;
        });

        $sectionLabour = array_filter($labour, function ($item) {
            return $item['tipe_item'] == 'section';
        });

        $parentLabour = $this->breporter->getStructureTree($labour);

        $sectionMaterial = array_filter($material, function ($item) {
            return $item['tipe_item'] == 'section';
        });

        $parentMaterial = $this->breporter->getStructureTree($material);

        $sectionPart = array_filter($part, function ($item) {
            return $item['tipe_item'] == 'section';
        });

        $parentPart = $this->breporter->getStructureTree($part);
        $rowTitle = $this->db->get_where('v_wo_bom', ['id' => $id_header])->row();
        $title = "BOM-Summary-Detail" . $rowTitle->wono . "-" . $rowTitle->dec . "-" . $rowTitle->customer . "-" . date('dmY');
        $_GET['id'] = $id_header;
        $this->load->view(
            'report/bom_persection',
            [
                'dataPart' => $part,
                'parentPart' => $parentPart,
                'sectionPart' => $sectionPart,
                'dataMaterial' => $material,
                'parentMaterial' => $parentMaterial,
                'sectionMaterial' => $sectionMaterial,
                'dataLabour' => $labour,
                'parentLabour' => $parentLabour,
                'sectionLabour' => $sectionLabour,
                'allowance' => $this->getAmountAllowance(TRUE),
                'title' => $title
            ]
        );
    }
    public function uploadPart()
    {
        $post = $this->input->post();
        var_dump($post);
        var_dump($_FILES);
        var_dump($_GET);
    }

    public function saveAllowance()
    {
        $post = $this->input->post();
        $this->db->update(
            'marketing',
            ['allowance' => $post['allowance']],
            ['id_marketing' => $post['id']]
        );
        echo json_encode(['code' => 1, 'success' => true]);
    }

    public function getAmountAllowance($return = FALSE)
    {
        $get = $this->input->get();
        // $amount = $this->db->get_where('marketing', ['id_marketing' => $get['id']])->row()->allowance;
        // if ($return)
        //     return $amount;
        // else
        //     echo json_encode(['data' => $amount, 'code' => 1, 'success' => true]);
    }

    // get counter except item
    public function getCounterItem()
    {
        $get = $this->input->get();
        $sql = '';
        $no = '';
        switch ($get['tipe_item']) {
            case 'section':
                $sql = "SELECT max(tipe_id) as seq from bom_part_jasa where id_header = '{$get['id_header']}' and tipe_item = '{$get['tipe_item']}' ";
                $last = $this->db->query($sql)->row()->seq;
                $no = (int) $last + 1;
                break;
            default:
                $sql = "SELECT max(j.tipe_id) AS seq, k.tipe_id AS parent_seq from bom_part_jasa j JOIN bom_part_jasa k ON j.id_parent = k.id WHERE j.id_header = '{$get['id_header']}' AND j.tipe_item = '{$get['tipe_item']}' AND j.id_parent = '{$get['id_parent']}'";
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

    function checkHasItem($id_header, $json = TRUE)
    {
        $has_item = FALSE;
        $count = $this->db->get_where('bom_part_jasa', ['id_header' => $id_header, 'deleted' => 0])->num_rows();
        if ($count > 0)
            $has_item = TRUE;
        if (!$json)
            return $has_item;
        echo json_encode(['has_item' => $has_item]);
    }

    public function saveUpload()
    {

        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if (isset($_FILES['FilePart']['name']) && in_array($_FILES['FilePart']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['FilePart']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['FilePart']['tmp_name']);

            $numberSheet = $spreadsheet->getSheetCount(); // Get Total of Worksheet
            $listWorkSheet = $spreadsheet->getSheetNames(); // Get list Name of Worksheet
            $message = "";
            for ($i = 0; $i < $numberSheet; $i++) {

                // var_dump('Sheet ke' . $i . ' adalah ' . $listWorkSheet[$i]);
                $spreadsheet->setActiveSheetIndexByName($listWorkSheet[$i]);

                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $idHeader = $_POST['idHeader'];
                $idParent = $_POST['idParent'];
                $tipeId = $_POST['tipeId'];
                $dwgNumber = substr($listWorkSheet[$i], 0, 12);
                $dwgName = trim(substr($listWorkSheet[$i], 13, 100));
                $idUser = $_POST['idUser'];
                $idParentItem = [];

                if ($dwgNumber == '00-00-00-000') {

                    $idRM = $this->db->get_where('bom_rawmaterial', array('id_header' => $idHeader, 'id_part_jasa' => $idParent, 'tipe_item' => 'section', 'tipe_id' => $tipeId))->row()->id;
                    $idParentItem = ['idRM' => $idRM, 'idPJ' => $idParent];

                    $this->uploadItem($sheetData, $idHeader, $idParentItem, $idUser);
                } else {

                    //------- Insert Object / Sub Section -------
                    $tipeId = $tipeId . "." . $i;
                    $sql = "INSERT INTO bom_part_jasa (id_header,id_parent,tipe_item,tipe_id,tipe_name,qty,users) values 
                                       ({$idHeader},{$idParent},'object','{$tipeId}','{$listWorkSheet[$i]}','0','{$idUser}') ";
                    $insert = $this->db->query($sql);

                    //Get id For Parent Object on BOM_Rawmterial
                    $idParentRM = $this->db->get_where('bom_rawmaterial', array('id_header' => $idHeader, 'id_part_jasa' => $idParent, 'tipe_item' => 'section'))->row()->id;

                    //Get id part jasa For column id_part_jasa on BOM_Rawmterial
                    $idPartJasa = $this->db->get_where('bom_part_jasa', array('id_header' => $idHeader, 'id_parent' => $idParent, 'tipe_item' => 'object', 'tipe_id' => $tipeId))->row()->id;

                    $sql = "INSERT INTO bom_rawmaterial (id_header,id_parent,id_part_jasa,tipe_item,tipe_id,tipe_name,qty,users) values 
                    ({$idHeader},{$idParentRM},{$idPartJasa},'object','{$tipeId}','{$listWorkSheet[$i]}','0','{$idUser}') ";
                    $insert = $this->db->query($sql);

                    //------- END Insert Object / Sub Section -------


                    //------- Insert Item -------
                    $idRM = $this->db->get_where('bom_rawmaterial', array('id_header' => $idHeader, 'id_part_jasa' => $idPartJasa, 'tipe_item' => 'object', 'tipe_id' => $tipeId))->row()->id;
                    $idParentItem = ['idRM' => $idRM, 'idPJ' => $idPartJasa];
                    // var_dump($idParentItem);
                    // echo $this->db->last_query();
                    $this->uploadItem($sheetData, $idHeader, $idParentItem, $idUser);
                    //------- END Insert Item -------
                }
            }

            // echo json_encode(['message' => $message]);
        }
    }

    function uploadItem($data = [], $idHeader, $idParentItem = [], $idUser)
    {

        $matlType = $this->db->get_where('config', array('key' => 'MATL_TYPE'))->row()->value;
        $matlType = explode(",", $matlType);

        for ($j = 10; $j < count($data); $j++) {
            $desc = $data[$j]['3'];
            $matlName = strtoupper($data[$j]['4']);
            $matlSize = strtoupper($data[$j]['5']);
            $matlOrBrand =  trim(strtoupper($data[$j]['6']));
            $qty =  $data[$j]['7'];
            $unit =  strtoupper($data[$j]['8']);
            $mass =  $data[$j]['9'];
            $firstMatlName = explode('_', $matlName);
            $firstMatlName = $firstMatlName[0];
            $itemPJ = $matlName . " " . $matlSize . " " . $matlOrBrand;
            $itemRM = $matlName . " " . $matlOrBrand;
            $object = [];

            if ($desc == '' && $matlName != '') {
                if (in_array($matlOrBrand, $matlType)) {
                    $matlSize = str_replace(' ', '', $matlSize);
                    $object = $this->getItemCodenSpec($firstMatlName, $matlSize, $matlOrBrand);
                    $str = ['T', 't', 'Dia', 'DIA'];
                    $rplc = ['', '', '', ''];
                    $tb = str_replace($str, $rplc, $object['t']);
                    $sql = "INSERT INTO bom_rawmaterial
                            (id_header,id_parent,id_part_jasa,tipe_item,item_code,qty,users,`weight`,item_name,l,w,h,t) values 
                            ({$idHeader},{$idParentItem['idRM']},'0','item','{$object['item_code']}','{$qty}','{$idUser}','{$mass}','{$itemRM}','{$object['L']}','{$object['W']}','{$object['H']}','{$tb}')";
                    $insert = $this->db->query($sql);
                } else {

                    $object = $this->getItemCodenSpec($matlName, $matlSize, $matlOrBrand, FALSE);

                    if ($object['item_code'] != '') {
                        $msItem = $this->getMasterItem($object['item_code']);
                    } else {
                        $msItem = ['nama' => $matlName, 'spek' => $matlSize, 'oum' => $unit, 'maker' => $matlOrBrand, 'price' => '0', 'kategori' => ''];
                    }

                    $sql = "INSERT INTO bom_part_jasa 
                            (id_header,id_parent,tipe_item,tipe_id,tipe_name,item_code,item_name,spec,satuan,merk,qty,users,`weight`,harga,kategori,item_name_ori) values 
                            ({$idHeader},{$idParentItem['idPJ']},'item','','','{$object['item_code']}','{$msItem['nama']}','{$msItem['spek']}','{$msItem['uom']}','{$msItem['maker']}','{$qty}','{$idUser}','{$mass}','{$msItem['price']}','{$msItem['kategori']}','{$itemPJ}')";
                    $insert = $this->db->query($sql);
                }
            }
        }
    }

    function getItemCodenSpec($matlName, $matlSize, $matlOrBrand, $rm = TRUE)
    {
        $dt = ['H' => 0, 'W' => 0, 't' => 0, 'L' => 0, 'item_code' => ''];
        $data = [];
        $spec = '';
        if ($rm) {

            $matlSize = str_replace('-', 'X', $matlSize);
            $matlSize = explode('X', $matlSize);

            $numChar = $this->db->get_where('config', array('key' => $matlName))->row()->value;
            if ($numChar > 0) {
                $temp = [];
                for ($i = 0; $i <= $numChar; $i++) {
                    array_push($temp, $matlSize[$i]);
                }
                $spec = implode("X", $temp);
            } else {
                $spec = $matlSize[0];
            }

            $dimension = $this->db->get_where('config', array('key' => $matlName))->row()->optional;
            $dimension = explode('x', $dimension);
            $data = array_combine($dimension, $matlSize);
        } else {
            $spec =  $matlSize;
            $data = [];
        }

        $item = $this->db->get_where('mrawmaterial', array('part_name' => $matlName, 'spec' => $spec, 'maker' => $matlOrBrand))->row_array();
        $data = array_merge($data, ['item_code' => $item['item_code']]);
        $data = array_replace($dt, $data);

        return $data;
    }

    function getMasterItem($itemCode)
    {
        if (isset($itemCode)) {

            $dataItem = [];
            $kategori = [];
            // Get Kategori from item code
            $codes = [
                0 => ['code' => ['ATK', 'CNS', 'RMT', 'PPG', 'OFF', 'INV'], 'value' => '10001'],
                1 => ['code' => ['ELC'], 'value' => '10002'],
                2 => ['code' => ['MCL'], 'value' => '10003'],
                3 => ['code' => ['PNU', 'PNE', 'PPG'], 'value' => '10004'],
                4 => ['code' => ['SNS'], 'value' => '20001']
            ];

            for ($i = 0; $i < count($codes); $i++) {
                if (in_array(substr($itemCode, 0, 3), $codes[$i]['code'])) {
                    $kategori = ['kategori' => $codes[$i]['value']];
                }
            }

            // Get Master Item Name,Spek,Maker & Unit
            $sql = "SELECT 
                                    item.nama, item.spek, item.maker, item.uom, mpc.mkt as price
                                FROM
                                    sgedb.mstchd item
                                        LEFT JOIN
                                    sgedb.msprice mpc ON item.stcd = mpc.stcd
                                WHERE
                                    item.stcd = '{$itemCode}';";

            $dataItem = $this->db->query($sql)->row_array();
            return array_merge($dataItem, $kategori);
        } else {
            return ['nama' => '', 'spek' => '', 'maker' => '', 'uom' => '', 'price' => '', 'kategori' => ''];
        }
    }
}
