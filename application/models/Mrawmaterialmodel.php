<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mrawmaterialmodel extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'mrawmaterial';
        $this->isNew = false;
    }

    public function getField($inputs = array()) {
        $fields = array(
            'part_name' => $inputs['part_name-input'],
            'units' => $inputs['units-input'],
            'materials' => $inputs['materials-input'],
            'density' => $inputs['density-input'],
            'price' => $inputs['price-input'],
            'type' => $inputs['type-input'],
        );
        if($this->isNew){
            $qSeq = $this->db->select('MAX(item_code) as max', false)
                ->from('mrawmaterial')
                ->get();
            $max = 0;
            if( $qSeq->num_rows() > 0){
                $code = 'RMT';
                $lastSeq = substr( $qSeq->row()->max, strlen($code) );
                $max = (int) $lastSeq;
            }
            $current = $max + 1;
            // create sequence code
            $fields['item_code'] = createSequence($code, 5, $current);
        }

        return $fields;
    }

    public function getRules() {
        // $newRule = ($this->isNew) ? '|is_unique[' . $this->table . '.part_name]' : '';

        $part_name = array(
            'field' => 'part_name-input',
            'label' => 'Part name',
            'rules' => 'trim|required|max_length[250]'
        );

        $units = array(
            'field' => 'units-input',
            'label' => 'Units',
            'rules' => 'trim|required|max_length[250]'
        );

        $materials = array(
            'field' => 'materials-input',
            'label' => 'Materials',
            'rules' => 'trim|required|max_length[250]'
        );

        $density = array(
            'field' => 'density-input',
            'label' => 'Density',
            'rules' => 'decimal|required|max_length[15]'
        );

        $price = array(
            'field' => 'price-input',
            'label' => 'Price',
            'rules' => 'integer|trim|required|max_length[250]'
        );

        $type = array(
            'field' => 'type-input',
            'label' => 'Tipe Rumus',
            'rules' => 'trim|required|max_length[250]'
        );

        return array($part_name, $units, $materials, $density, $price, $type);
    }
}
