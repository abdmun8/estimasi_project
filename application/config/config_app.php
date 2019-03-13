<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| FrontEnd Config
|--------------------------------------------------------------------------
*/
$config['app_name']			= 'Quotation';
$config['app_logo_mini']	= 'QT';
$config['app_logo_lg']		= 'Quotation Sekawan';
$config['app_version']		= '0.1';

$config['sys_email_sender']			= 'email@email.com';
$config['sys_email_sender_name']	= 'Admin Quotation';
$config['sys_email_host']			= 'smtp.zoho.com';
$config['sys_email_port']			= '587';	//587 atau 465
$config['sys_email_user']			= 'email@email.com';
$config['sys_email_pass']			= 'xxx';

//seting time zone
date_default_timezone_set("Asia/Jakarta");

/* End of file config_app.php */
/* Location: ./application/config/config_app.php */