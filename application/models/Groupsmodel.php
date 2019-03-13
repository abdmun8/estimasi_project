<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groupsmodel extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'groups';
        $this->isNew = false;
    }

    public function getField($inputs = array()) {
        $fields = array(
            'name' => $inputs['name-input'],
            'group_leader' => $inputs['group_leader-input'],
            'description' => $inputs['description-input'],
            'active' => $inputs['active-input']
        );     

        return $fields;
    }

    public function getRules() {
        $newRule = ($this->isNew) ? '|is_unique[' . $this->table . '.name]' : '';
        $name = array(
            'field' => 'name-input',
            'label' => 'Name',
            'rules' => 'trim|required|max_length[25]' . $newRule
        );

        $group_leader = array(
            'field' => 'group_leader-input',
            'label' => 'Group Leader',
            'rules' => 'trim|required|max_length[255]' 
        );

        $active = array(
            'field' => 'active-input',
            'label' => 'Active',
            'rules' => 'trim|required|max_length[1]' 
        );
        
        return array($name, $group_leader,$active);
    }
}