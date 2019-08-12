<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends Model {

    public function __construct()
    {
        parent::__construct();

    }

    public function getRules($action)
    {
        $newRule = ($action === 1) ? '|is_unique[' . $this->table . '.inquiry_no]' : '';
        $inquiry_no = array(
            'field' => 'inquiry_no',
            'label' => 'Inquiry No',
            'rules' => 'trim|required|max_length[25]' . $newRule
        );

        $project_name = array(
            'field' => 'project_name',
            'label' => 'Project Name',
            'rules' => 'trim|required|max_length[100]'
        );

        $customer = array(
            'field' => 'customer',
            'label' => 'Customer',
            'rules' => 'trim|required|max_length[100]'
        );

        return array($inquiry_no, $project_name, $customer);
    }

    public function getFieldHeader()
    {
        $post = $this->input->post();
        $data = [
            "inquiry_no" => $post['inquiry_no'],
            "project_name" => $post['project_name'],
            "qty" => $post['qty_general'],
            "satuan" => $post['lot_general'],
            "customer" => $post['customer'],
            "pic_marketing" => $post['pic_marketing'],
            "start_date" => $post['start_date-general'],
            "finish_date" => $post['finish_date-general'],
            "project_type" => $post['project_type'],
            "difficulty" => $post['difficulty']
        ];

        return $data;
    }

    public function getFieldPart()
    {
        $post = $this->input->post();
        $data = [
            "id_header" => $post['id_header-item'],
            "tipe_id" => $post['tipe_id-item'],
            "item_code" => $post['item_code'],
            "item_name" => $post['item_name-item'],
            "spec" => $post['spec-item'],
            "satuan" => $post['satuan-item'],
            "qty" => $post['qty-item'],
            "tipe_item" => $post['tipe_item-item'],
            "id_parent" => $post['id_parent-item'],
            "tipe_name" => $post['tipe_name-item'],
            "merk" => $post['merk-item'],
            "harga" => $post['harga-item-clean'],
            "remark_harga" => $post['remark-harga'],
            "kategori" => $post['kategori-item'],
            "group" => isset($post['group-item']) ? $post['group-item'] : 0
        ];

        return $data;
    }

    public function getFieldLabour()
    {
        $post = $this->input->post();
        $data = [
            "id_labour" => $post['id_labour-labour'],
            "aktivitas" => $post['aktivitas-labour'],
            "sub_aktivitas" => $post['sub_aktivitas-labour'],
            "tipe_item" => $post['tipe_item-labour'],
            "id_parent" => $post['id_parent-labour'],
            "id_header" => $post['id_header-labour'],
            "hour" => $post['hour-labour'],
            "rate" => $post['rate-labour-clean'],
        ];

        return $data;
    }

    public function getFieldMaterial()
    {
        $inputs = $this->input->post();
        $data = [
            'id_parent' => $inputs['id_parent-material'],
            'id_header' => $inputs['id_header-material'],
            'id_part_jasa' => 0,
            'tipe_id' => '',
            'tipe_name' => '',
            'tipe_item' => 'item',
            'item_code' => $inputs['item_code-save-material'],
            'qty' => $inputs['qty-material'],
            'l' => $inputs['length-material'],
            'w' => $inputs['width-material'],
            'h' => $inputs['height-material'],
            't' => $inputs['diameter-material'],
            'weight' => $inputs['weight-material']
        ];

        return $data;
    }


    // public

    public function insertGeneralInfo()
    {
        $this->input->post();
        $data = $this->getFieldHeader();
        $this->db->insert( 'header', $data );

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function udpateGeneralInfo()
    {
        $this->input->post();
        $data = $this->getFieldHeader();

        $this->db->update( 'header', $data, ['id'=>$this->input->post('id_header')]);
        if( $this->db->affected_rows() >= 0){
            return TRUE;
        }
        return FALSE;
    }

    public function insertDetailPart()
    {
        $this->input->post();
        $data = $this->getFieldPart();
        $this->db->insert( 'part_jasa', $data );

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function udpateDetailPart()
    {
        $post = $this->input->post();
        // var_dump($post);die;
        $data = $this->getFieldPart();
        $this->db->update( 'part_jasa', $data, ['id'=>$this->input->post('id-item')]);
        $this->db->update( 'labour', ['tipe_name' => $post['tipe_name-item']], ['id_part_jasa'=>$this->input->post('id-item')]);
        $this->db->update( 'rawmaterial', ['tipe_name' => $post['tipe_name-item']], ['id_part_jasa'=>$this->input->post('id-item')]);

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function insertDetailLabour()
    {
        $this->input->post();
        $data = $this->getFieldLabour();
        $this->db->insert( 'labour', $data );

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function udpateDetailLabour()
    {
        $this->input->post();
        $data = $this->getFieldLabour();
        $this->db->update( 'labour', $data, ['id'=>$this->input->post('id-labour')]);

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function insertMaterial()
    {
        $this->input->post();
        $data = $this->getFieldMaterial();
        $this->db->insert( 'rawmaterial', $data );

        if( $this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function udpateMaterial()
    {
        $this->input->post();
        // var_dump($this->input->post);
        $data = $this->getFieldMaterial();
        $this->db->update( 'rawmaterial', $data, ['id'=>$this->input->post('id-material')]);
        // echo $this->db->last_query();

        return TRUE;
        // if( $this->db->affected_rows() > 0){
        //     return TRUE;
        // }
        // return FALSE;
    }

    public function getDataReport(){
        $this->db->get_where();
    }


}
