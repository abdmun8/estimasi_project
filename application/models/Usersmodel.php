<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersmodel extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'users';
        $this->isNew = false;
    }

    public function getField($inputs = array()) {
        $fields = array(
            'username' => $inputs['username-input'],
            'active' => $inputs['active-input']
        );

     
        if ($inputs['password-input'] != "") {
            $this->load->model('Ion_auth_model');
            $fields['password'] = $this->Ion_auth_model->_set_encrypt($this->session->userdata('identity'), $inputs['password-input']);

        }

        return $fields;
    }

    public function getRules() {
        $newRule = ($this->isNew) ? '|is_unique[' . $this->table . '.username]' : '';
        $username = array(
            'field' => 'username-input',
            'label' => 'Username',
            'rules' => 'trim|required|max_length[25]' . $newRule
        );

        $active = array(
            'field' => 'active-input',
            'label' => 'Active',
            'rules' => 'trim|required|max_length[1]' 
        );
        
        return array($username, $active);
    }
}