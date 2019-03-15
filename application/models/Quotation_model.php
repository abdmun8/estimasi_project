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
            "qty" => $post['qty'],
            "lot" => $post['lot'],
            "customer" => $post['customer'],
            "pic_marketing" => $post['pic_marketing'],
            "start_date" => $post['start_date'],
            "finish_date" => $post['finish_date'],
            "project_type" => $post['project_type'],
            "diffficulty" => $post['diffficulty']
        ];

        return $data;
    }

    public function getFieldPart()
    {
        $post = $this->input->post();
        $data = [
            "tipe_id" => $post['tipe_id-item'],
            "item_code" => $post['item_code-item'],
            "item_name" => $post['item_name-item'],
            "spec" => $post['spec-item'],
            "satuan" => $post['satuan-item'],
            "qty" => $post['qty-item'],
            "tipe_item" => $post['tipe_item-item'],
            "id_parent" => $post['id_parent-item'],
            "tipe_name" => $post['tipe_name-item'],
            "tipe_name" => $post['tipe_name-item'],
            "merk" => $post['merk-item'],
            "harga" => $post['harga-item'],
            "kategori" => $post['kategori-item']
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

        if( $this->db->affected_rows() > 0){            
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
        $this->input->post();
        $data = $this->getFieldPart();
        $this->db->update( 'part_jasa', $data, ['id'=>$this->input->post('id-item')]);

        if( $this->db->affected_rows() > 0){            
            return TRUE;
        }
        return FALSE;
    }


}