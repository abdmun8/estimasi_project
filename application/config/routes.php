<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* ---------------- CMS routing ---------------- */
//view/page back end
$route['admin'] = 'Auth/index'; // show default page
$route['view'] = 'Viewer/pathGuide'; // show default page
$route['view/(:any)'] = 'Viewer/pathGuide/$1'; // show specific page
$route['view/(:any)/(:any)'] = 'Viewer/pathGuide/$1/$2'; // show specific page with param
$route['view/(:any)/(:any)/(:any)'] = 'Viewer/pathGuide/$1/$2/$3'; // show specific page with param
$route['registrasi'] = 'Viewer/registrasi'; // show default page

//login-logout
$route['login'] = 'Auth/login'; // login
$route['logout'] = 'Auth/logout'; // logout

//manage/retieve data
$route['manage'] = 'Manager/process'; // create, update, or delete
$route['object'] = 'Retriever/record'; // read/retrieve data
$route['object/(:any)'] = 'Retriever/record/$1';
$route['objects/(:any)'] = 'Retriever/records/$1'; // read/retrieve list
$route['objects/(:any)/(:any)/(:any)'] = 'Retriever/records/$1/$2/$3/no'; // read/retrieve list with param
$route['pick/(:any)'] = 'Retriever/records/$1/null/null/yes'; // read/retrieve list with param for picker
$route['pick/(:any)/(:any)/(:any)'] = 'Retriever/records/$1/$2/$3/yes'; // read/retrieve list with param for

/* Routing Quotation Controller*/

/* header */
$route['quotation'] = 'Quotation/index';
$route['quotation/(:num)'] = 'Quotation/index/$1';
$route['quotation/get_data_header'] = 'Quotation/getDataHeader';
$route['quotation/get_data_header/(:num)'] = 'Quotation/getDataHeader/$1';
$route['quotation/get_customer'] = 'Quotation/getCustomer';
$route['quotation/get_pic'] = 'Quotation/getPIC';

/* Part jasa*/
$route['quotation/get_data_part'] = 'Quotation/getDataPart';
$route['quotation/get_data_part/(:num)'] = 'Quotation/getDataPart/$1';
$route['quotation/get_data_part/(:num)/(:num)'] = 'Quotation/getDataPart/$1/$2';
$route['quotation/save_item'] = 'Quotation/saveitem';
$route['quotation/get_kategori'] = 'Quotation/getKategori';
$route['quotation/save_gen_info'] = 'Quotation/saveGeneralInfo';
$route['quotation/get_item_code/(:num)'] = 'Quotation/getItemCode/$1';
$route['quotation/print_part/(:num)'] = 'Quotation/printPart/$1';
$route['quotation/print_labour/(:num)'] = 'Quotation/printLabour/$1';
$route['quotation/print_summary/(:num)'] = 'Quotation/printSummary/$1';
$route['quotation/print_detail_summary/(:num)'] = 'Quotation/printDetailSummary/$1';
$route['quotation/get_satuan'] = 'Quotation/getSatuan';

/* labour */
$route['quotation/get_data_labour/(:num)'] = 'Quotation/getDataLabour/$1';
$route['quotation/get_data_labour/(:num)/(:num)'] = 'Quotation/getDataLabour/$1/$2';
$route['quotation/save_labour'] = 'Quotation/saveLabour';

$route['quotation/get_counter_item'] = 'Quotation/getCounterItem';
$route['quotation/del_item'] = 'Quotation/delItem';
$route['quotation/save_hour'] = 'Quotation/saveHourLabour';
$route['quotation/get_section/(:any)'] = 'Quotation/getSection/$1';
$route['quotation/save_section_qty'] = 'Quotation/saveSectionQty';
$route['quotation/save_allowance'] = 'Quotation/saveAllowance';
$route['quotation/get_amount_allowance'] = 'Quotation/getAmountAllowance';
$route['quotation/get_detail_by_header/(:any)/(:any)/(:any)'] = 'Quotation/getDetailLabourByHeader/$1/$2/$3';
/* Material */
$route['quotation/get_data_material/(:num)'] = 'Quotation/getDataMaterial/$1';
$route['quotation/get_data_material/(:num)/(:num)'] = 'Quotation/getDataMaterial/$1/$2';
$route['quotation/save_material'] = 'Quotation/saveMaterial';
$route['quotation/get_material_code'] = 'Quotation/getMaterialCode';

/* header bill material */
$route['bmaterial'] = 'BMaterial/index';
$route['bmaterial/(:num)'] = 'BMaterial/index/$1';
$route['bmaterial/get_data_header'] = 'BMaterial/getDataHeader';
$route['bmaterial/get_data_header/(:num)'] = 'BMaterial/getDataHeader/$1';
$route['bmaterial/get_customer'] = 'BMaterial/getCustomer';
$route['bmaterial/get_pic'] = 'BMaterial/getPIC';

/* Part jasa bill material*/
$route['bmaterial/get_data_part'] = 'BMaterial/getDataPart';
$route['bmaterial/get_data_part/(:num)'] = 'BMaterial/getDataPart/$1';
$route['bmaterial/get_data_part/(:num)/(:num)'] = 'BMaterial/getDataPart/$1/$2';
$route['bmaterial/save_item'] = 'BMaterial/saveitem';
$route['bmaterial/get_kategori'] = 'BMaterial/getKategori';
$route['bmaterial/save_gen_info'] = 'BMaterial/saveGeneralInfo';
$route['bmaterial/get_item_code/(:num)'] = 'BMaterial/getItemCode/$1';
$route['bmaterial/print_part/(:num)'] = 'BMaterial/printPart/$1';
$route['bmaterial/print_labour/(:num)'] = 'BMaterial/printLabour/$1';
$route['bmaterial/print_summary/(:num)'] = 'BMaterial/printSummary/$1';
$route['bmaterial/print_detail_summary/(:num)'] = 'BMaterial/printDetailSummary/$1';
$route['bmaterial/get_satuan'] = 'BMaterial/getSatuan';
$route['bmaterial/save_upload'] = 'BMaterial/saveUpload';


/* labour bill material */
$route['bmaterial/get_data_labour/(:num)'] = 'BMaterial/getDataLabour/$1';
$route['bmaterial/get_data_labour/(:num)/(:num)'] = 'BMaterial/getDataLabour/$1/$2';
$route['bmaterial/save_labour'] = 'BMaterial/saveLabour';
$route['bmaterial/get_counter_item'] = 'BMaterial/getCounterItem';
$route['bmaterial/del_item'] = 'BMaterial/delItem';
$route['bmaterial/save_hour'] = 'BMaterial/saveHourLabour';
$route['bmaterial/get_section/(:any)'] = 'BMaterial/getSection/$1';
$route['bmaterial/save_section_qty'] = 'BMaterial/saveSectionQty';
$route['bmaterial/save_allowance'] = 'BMaterial/saveAllowance';
$route['bmaterial/get_amount_allowance'] = 'BMaterial/getAmountAllowance';
$route['bmaterial/get_detail_by_header/(:any)/(:any)/(:any)'] = 'BMaterial/getDetailLabourByHeader/$1/$2/$3';
$route['bmaterial/upload_part'] = 'BMaterial/uploadPart';
/* Material bill material */
$route['bmaterial/get_data_material/(:num)'] = 'BMaterial/getDataMaterial/$1';
$route['bmaterial/get_data_material/(:num)/(:num)'] = 'BMaterial/getDataMaterial/$1/$2';
$route['bmaterial/save_material'] = 'BMaterial/saveMaterial';
$route['bmaterial/get_material_code'] = 'BMaterial/getMaterialCode';

// Report



// Master Data




/* Pdf Gen*/
// $route['report'] = 'Pdfgen/index';
