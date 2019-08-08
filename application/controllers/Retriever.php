<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retriever extends CI_Controller {
	private $activeSession; // store session

	public function __construct() {
		parent::__construct();
		$this->activeSession = $this->session->userdata('identity');
	}

	public function index() {
		redirect(site_url('view/home'));
	}

	/*
	* read object
	*/
	public function record($specific = null) {
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
					$query['table'] = 'v_header';
					break;
				case 'karyawan':
					$query['table'] = 'v_karyawan';
					if( $this->input->get('type') == 1){
						$query['where'] = ['status_karyawan <>'=>'Non-Aktif'];
					}else{
						$query['where'] = ['status_karyawan'=>'Non-Aktif'];
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
	public function records($table, $key = 'null', $value = 'null', $picker = 'no') {
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
						$query['table'] = 'v_header';
						break;
					case 'karyawan':
						$query['table'] = 'v_karyawan';
						if( $this->input->get('type') == 1){
							$query['where'] = ['status_karyawan <>'=>'Non-Aktif'];
						}else{
							$query['where'] = ['status_karyawan'=>'Non-Aktif'];
						}
						break;
					default:
						$query['table'] = $table;
						break;
				}

				$records = $this->model->getList($query);

				$inner = '_' . $table;
				$data = $this->$inner($records, $picker);
			}
		}

		echo json_encode(array('data' => $data));
	}

	/*
	* inner data generator
	* ===================================== write your custom function here =====================================
	*/


	function _karyawan($records, $picker = 'no') {
		$data = array();
		$no = 0;
		foreach ($records as $record) {

			$no++;

			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id_personalia . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = ' <a href="#' . $record->id_personalia . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id_personalia . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
			}

			$kont1 = ($record->kontrak1_mulai != '0000-00-00') ? date('d-m-Y', strtotime($record->kontrak1_mulai)) : '00-00-0000';
			$kont1s = ($record->kontrak1_selesai != '0000-00-00') ? date('d-m-Y', strtotime($record->kontrak1_selesai)) : '00-00-0000';
			$kont2 = ($record->kontrak2_mulai != '0000-00-00') ? date('d-m-Y', strtotime($record->kontrak2_mulai)) : '00-00-0000';
			$kont2s = ($record->kontrak2_selesai != '0000-00-00') ? date('d-m-Y', strtotime($record->kontrak2_selesai)) : '00-00-0000';

			$data[] = array(
				'no' => $no,
				'id_personalia' => $record->id_personalia,
				'nama' => $record->nama,
				'tgl_masuk' => ($record->tgl_masuk != '0000-00-00') ? date('d-m-Y', strtotime($record->tgl_masuk)) : '00-00-0000',
				'jen_kel' => $record->jen_kel,
				'identitas' => $record->identitas,
				'no_identitas' => $record->no_identitas,
				'tempat_lhr' => $record->tempat_lhr,
				'tgl_lhr' => ($record->tgl_lhr != '0000-00-00') ? date('d-m-Y', strtotime($record->tgl_lhr)) : '00-00-0000',
				'sts_kawin' => $record->sts_kawin,
				'agama' => $record->agama,
				'gol_darah' => $record->gol_darah,
				'pdkn_akhir' => $record->pdkn_akhir,
				'institusi_pdkn' => $record->institusi_pdkn,
				'prog_studi' => $record->prog_studi,
				'no_hp' => $record->no_hp,
				'email' => $record->email,
				'almt_rumah' => $record->almt_rumah,
				'nm_kntk_darurat' => $record->nm_kntk_darurat,
				'tlpn_darurat' => $record->tlpn_darurat,
				'nm_bank' => $record->nm_bank,
				'nm_ibu' => $record->nm_ibu,
				'nm_bpk' => $record->nm_bpk,
				'nm_pasangan' => $record->nm_pasangan,
				'nm_anak1' => $record->nm_anak1,
				'nm_anak2' => $record->nm_anak2,
				'nm_anak3' => $record->nm_anak3,
				'pdkn_akhir' => $record->pdkn_akhir,
				'institusi_pdkn' => $record->institusi_pdkn,
				'prog_studi' => $record->prog_studi,
				'no_hp' => $record->no_hp,
				'email' => $record->email,
				'almt_rumah' => $record->almt_rumah,
				'almt_saat_ini' => $record->almt_saat_ini,
				'nm_kntk_darurat' => $record->nm_kntk_darurat,
				'tlpn_darurat' => $record->tlpn_darurat,
				'nm_bank' => $record->nm_bank,
				'nm_rekening' => $record->nm_rekening,
				'no_rek' => $record->no_rek,
				'kntr_cab_bank' => $record->kntr_cab_bank,
				'npwp' => $record->npwp,
				'sts_pajak' => $record->sts_pajak,
				'no_bpjstk' => $record->no_bpjstk,
				'prd_bpjstk' => $record->prd_bpjstk,
				'no_bpjsks' => $record->no_bpjsks,
				'prd_bpjsks' => $record->prd_bpjsks,
				'departemen' => $record->departemen,
				'jabatan' => $record->jabatan,
				'atasan' => $record->atasan,
				'kontrak1' => $kont1.' - '.$kont1s,
				'kontrak2' => $kont2.' - '.$kont2s,
				'kontrak1_mulai' => $kont1,
				'kontrak1_selesai' => $kont1s,
				'kontrak2_mulai' => $kont2,
				'kontrak2_selesai' => $kont2s,
				'status_karyawan' => $record->status_karyawan,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}

	function _users($records, $picker = 'no') {
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

	function _groups($records, $picker = 'no') {
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

	function _header($records, $picker = 'no') {
		$data = array();
		$no = 0;
		foreach ($records as $record) {
			$pc_ce = @($record->pc_company_experience * 0.15);
			$p_pl  = @($record->pc_complexity_level * 0.15);
			$pc_pw = @($record->pc_working_duration * 0.1);
			$pc_vw = @($record->pc_variant_workpiece * 0.1);
			$pc_al = @($record->pc_automation_level * 0.1);
			$pc_ms = @($record->pc_machine_speed * 0.05);
			$pc_sc = @($record->pc_duty_cycle * 0.05);

			$pc_is = @($record->pc_installation_schedile * 0.05);
			$pc_tl = @($record->pc_technology_level * 0.05);

			if ($pc_environment_for_material = 31) {
				$pc_efm1 = 3;
			} elseif ($pc_environment_for_material = 51 || $pc_environment_for_material = 52) {
				$pc_efm1 = 5;
			} else {
				$pc_efm1 = $pc_environment_for_material;
			}
			$pc_efm = @($pc_efm1 * 0.05);

			if ($pc_environment_for_installation = 31 || $pc_environment_for_installation = 32) {
				$pc_efi1 = 3;
			} else {
				$pc_efi1 = $pc_environment_for_installation;
			}
			$pc_efi = @($pc_efi1 * 0.05);
			$pc_esr = @($record->pc_equipment_spesification_requirement * 0.05);

			if ($pc_purpose_for_project = 31 || $pc_purpose_for_project = 32) {
				$pc_pfp1 = 3;
			} else {
				$pc_pfp1 = $pc_purpose_for_project;
			}
			$pc_pfp = @($pc_pfp1 * 0.05);

			$recomen = @($pc_ce + $p_pl + $pc_pw + $pc_vw + $pc_al + $pc_ms + $pc_sc + $pc_is + $pc_tl + $pc_efm + $pc_efi + $pc_esr + $pc_pfp);
			
			$ax = cek_risk($recomen);

			$no++;

			if ($picker == 'yes') {
				$linkBtn = '<a href="#' . $record->id . '" class="btn btn-xs btn-info pickBtn" title="Pilih"><i class="fa fa-thumb-tack"></i> Pilih</a>';
			} else if ($picker == 'no') {
				$linkBtn = '  <a href="#' . $record->id . '" class="btn btn-xs btn-primary editBtn" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editAllowanceBtn" title="Edit Overrage Sect"><i class="fa fa-edit"></i> Overrage</a>';
				$linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-primary editQtyBtn" title="Edit Qty Sect"><i class="fa fa-edit"></i> Qty Sect</a>';
				// $linkBtn .= ' <a href="#' . $record->id . '" class="btn btn-xs btn-danger removeBtn" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a>';
				$linkBtn .= ' <a onclick="printQuotation('.$record->id.',\''.$ax.'\'); return false;" href="#" class="btn btn-xs btn-success " title="Print"><i class="fa fa-print"></i> Print</a>';
			}
			
			
			if ($ax == "PROJECT NORMAL") {
				$color_r = "#3AE375";
			} elseif ($ax == "PROJECT MEDIUM RISK") {
				$color_r = "#E0F457";
			} elseif ($ax == "PROJECT HIGH RISK") {
				$color_r = "#F06275";
			} elseif ($ax == "PROJECT VERY HIGH RISK") {
				$color_r = "#940014";
			} else {
				$color_r = "#55B1ED";
			}

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
				'risk' => $ax,
				'duration' => '',
				'color' => $color_r,
				'aksi' => $linkBtn
			);
		}

		return $data;
	}

	function _mrawmaterial($records, $picker = 'no') {
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
