<?php
	//type akun?
	// $type = $this->session->userdata('_LEVEL');
	$data = array(
		'_TITLE'=> 'Sekawan Group',
		'_CONTENT'=>'',
		'_MENU'=> $this->load->view('cms/template/menu', '', TRUE),
		'_EXTRA_JS'=>'loadContent(base_url + "view/dashboard");'

	);

	$this->load->view('cms/template/index', $data);	//file template
	
	// $data = array(
	// 	'_TITLE'=>'Bapenda Versi 1.0',
	// 	'_CONTENT'=>'',
	// 	'_MENU'=>$this->load->view('cms/template/_menu','',TRUE),
	// 	'_EXTRA_JS'=>'loadContent(base_url + "view/_dashboard");'
	// );

	// $this->load->view('cms/template/index', $data);	//file template
?>