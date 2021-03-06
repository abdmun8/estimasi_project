<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retriever extends CI_Controller
{
	private $activeSession; // store session

	public function __construct()
	{
		parent::__construct();
		$this->activeSession = $this->session->userdata('identity');
	}

	public function index()
	{
		redirect(site_url('view/home'));
	}

	/*
	* read object
	*/
	public function record($specific = null)
	{
		/*
		* code info:
		*	- 0 = akses tidak sah & data tidak perlu di tampilkan
		*	- 1 = akses sah & data di tampilkan
		*/
		$code = 0;
		$object = null;

		if ($this->activeSession != null) {
			switch ($this->input->post('model-input')) {
				case 'users':
					$query['table'] = 'v_users';
					break;
				case 'groups':
					$query['table'] = 'v_groups';
					break;
					// Master Data
					//Quotation
				case 'header':
					if(isset($_GET['show_closed']) && $_GET['show_closed'] == 0 ){
						$query['where'] = ['approve' => 'A', 'status <>' => 'close'];
					}else{
						$query['where'] = ['approve' => 'A'];
					}
					$query['table'] = 'v_header';					
					break;
				case 'karyawan':
					$query['table'] = 'v_karyawan';
					if ($this->input->get('type') == 1) {
						$query['where'] = ['status_karyawan <>' => 'Non-Aktif'];
					} else {
						$query['where'] = ['status_karyawan' => 'Non-Aktif'];
					}
					break;
				default:
					$query['table'] = $this->input->post('model-input');
					break;
			}

			$query['where'] = array($this->input->post('key-input') => $this->input->post('value-input'));

			$object = $this->model->getRecord($query);

			$code = 1;
		}

		echo json_encode(array('data' => array(
			'code' => $code,
			'object' => $object
		)));
	}

	/* |||||||||||||||||||||||||||||||||||| DATATABLES |||||||||||||||||||||||||||||||||||| */
	/*
	* read objects - DataTables
	*/
	public function records($table, $key = 'null', $value = 'null', $picker = 'no')
	{
		$data = array();

		if ($this->activeSession != null) {
			if (isset($table)) {
				if ($key != 'null' && $value != 'null') {
					$query['where'] = array($key => $value);
				}

				switch ($table) {
					case 'users':
						$query['table'] = 'v_users';
						break;
					case 'groups':
						$query['table'] = 'v_groups';
						break;
						// Master Data
						// Quotation
					case 'header':
						if(isset($_GET['show_closed']) && $_GET['show_closed'] == 0 ){
							$query['where'] = ['approve' => 'A', 'status <>' => 'close'];
						}else{
							$query['where'] = ['approve' => 'A'];
						}
						$query['table'] = 'v_header';	
						break;
					case 'headerMaterial':
						if(isset($_GET['show_closed']) && $_GET['show_closed'] == 0 ){
							$query['where'] = ['complete' => '1899-12-30'];
						} 
						$query['table'] = 'v_wo_bom';	
						break;
					case 'karyawan':
						$query['table'] = 'v_karyawan';
						if ($this->input->get('type') == 1) {
							$query['where'] = ['status_karyawan <>' => 'Non-Aktif'];
						} else {
							$query['where'] = ['status_karyawan' => 'Non-Aktif'];
						}
						break;
					default:
						$query['table'] = $table;
						break;
						
				}
				
				$records = $this->model->getList($query);
				
				$inner = '_' . $table;
				// var_dump($inner);
				// die;
				$data = $this->$inner($records, $picker);
			}
		}

		echo json_encode(array('data' => $data));
	}

	/*
	* inner data generator
	* ===================================== write your custom function here =====================================
	*/


	function _users($records, $picker = 'no')
	{
		$data = array();
		$no = 0;
		foreach ($records as $record) {

			$no++;

			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
			}

			$data[] = array(
				'no' => $no,
				'username' => $record->username,
				'nama' => $record->nama,
				'jabatan' => $record->jabatan,
				'departemen' => $record->departemen,
				'email' => $record->email,
				'active' => $record->active,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}

	function _groups($records, $picker = 'no')
	{
		$data = array();
		$no = 0;
		foreach ($records as $record) {

			$no++;

			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
			}

			$data[] = array(
				'no' => $no,
				'name' => $record->name,
				'description' => $record->description,
				'group_leader' => $record->group_leader,
				'nama_group_leader' => $record->nama_group_leader,
				'active' => $record->active,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}

	function _header($records, $picker = 'no')
	{
		$data = array();
		$no = 0;
		foreach ($records as $record) {

			$reccomendation = cek_risk($record->recomen);
			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = '  <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editAllowanceBtn" title="Edit Overrage Sect"><i class="fa fa-edit"></i> Overrage</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editQtyBtn" title="Edit Qty Sect"><i class="fa fa-edit"></i> Qty Sect</a>';
				// $linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
				$linkBtn .= ' <a onclick="checkQuotationHasItem(' . $record->id . ',\'' . $reccomendation . '\'); return false;" href="#" class="btn btn-xs btn-success " title="Print"><i class="fa fa-print"></i> Print</a>';
			}

			$color_r = '';
			if ($reccomendation == "PROJECT NORMAL") {
				$color_r = "#3AE375";
			} elseif ($reccomendation == "PROJECT MEDIUM RISK") {
				$color_r = "#E0F457";
			} elseif ($reccomendation == "PROJECT HIGH RISK") {
				$color_r = "#F06275";
			} elseif ($reccomendation == "PROJECT VERY HIGH RISK") {
				$color_r = "#940014";
			} else {
				$color_r = "#55B1ED";
			}
			$no++;
			$data[] = array(
				'no' => $no,
				'project_name' => $record->project_name,
				'qty' => $record->qty,
				'satuan' => $record->satuan,
				'inquiry_no' => $record->inquiry_no,
				'nama_estimator' => $record->nama_estimator,
				'priority' => $record->prioritas,
				'r_f_estimation' => $record->r_f_estimation,
				'customer' => $record->customer,
				'pic_marketing' => $record->pic_marketing,
				'nama' => $record->nama,
				'unit' => $record->unit,
				'allowance' => $record->allowance,
				'start_date' => $record->start_date,
				'finish_date' => $record->finish_date,
				'deptname' => $record->deptname,
				'risk' => $reccomendation,
				'duration' => '',
				'color' => $color_r,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}
	function _headerMaterial($records, $picker = 'no')
	{
		// var_dump($records);
		// die;
		$data = array();
		$no = 0;
		foreach ($records as $record) {
			// var_dump($record);
			// die;

			$reccomendation = 'FALSE';
			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = '  <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a onclick="checkBillHasItem(' . $record->id . ',\'' . $reccomendation . '\'); return false;" href="#" class="btn btn-xs btn-success " title="Print"><i class="fa fa-print"></i> Print</a>';
				// $linkBtn .= ' <a onclick="uploadPart(' . $record->id . '); return false;" href="#" class="btn btn-xs btn-danger" title="Print"><i class="fa fa-upload"></i>U.Part</a>';
				// $linkBtn .= ' <a onclick="checkBillHasItem(' . $record->id . ',\'' . $reccomendation . '\'); return false;" href="#" class="btn btn-xs btn-warning" title="Print"><i class="fa fa-upload"></i>U.Material</a>';
			}

			$color_r = '';
			// if ($reccomendation == "PROJECT NORMAL") {
			// 	$color_r = "#3AE375";
			// } elseif ($reccomendation == "PROJECT MEDIUM RISK") {
			// 	$color_r = "#E0F457";
			// } elseif ($reccomendation == "PROJECT HIGH RISK") {
			// 	$color_r = "#F06275";
			// } elseif ($reccomendation == "PROJECT VERY HIGH RISK") {
			// 	$color_r = "#940014";
			// } else {
			// 	$color_r = "#55B1ED";
			// }
			$no++;
			$data[] = array(
				'no' => $no,
				'wono' => $record->wono,
				'desc' => $record->desc,
				'date' => $record->date,
				'selesai' => $record->selesai,
				'complete' => $record->complete,
				'left' => $record->left,
				'customer' => $record->customer,
				'mkt' => $record->mkt,
				'pl' => $record->pl,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}

	function _mrawmaterial($records, $picker = 'no')
	{
		$data = array();
		$no = 0;
		foreach ($records as $record) {

			$no++;

			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
			}

			$data[] = array(
				'no' => $no,
				'item_code' => $record->item_code,
				'part_name' => $record->part_name,
				'units' => $record->units,
				'materials' => $record->materials,
				'density' => $record->density,
				'price' => $record->price,
				'type' => $record->type,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}
}
