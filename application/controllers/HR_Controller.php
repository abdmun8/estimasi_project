<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HR_controller extends CI_Controller {
	private $identity; // store session

	public function __construct() {
	    parent::__construct();
	    $this->identity = $this->session->userdata('identity');
	    $this->load->model('HR_Model', 'hr');
	}

	public function index() {
		redirect(site_url('view/home'));
	}

	function getDataKandidat()
    {
        $list = $this->hr->getDataKandidat();
        $data = [];
        $no = 0;

        foreach ($list as $key => $value) {
        	$no++;
        	$btn = "<a href='#".$value->id_kandidat."' class='btn' title='Cetak'><i class='fa fa-print'></i></a>";

        	$data[] = [
        		"no" => $no,
        		"id_kandidat" => $value->id_kandidat,
        		"aksi" => $btn,
	            "nama" => $value->nama,
	            "email" => $value->email,
	            "jenis_kelamin" => $value->jenis_kelamin,
	            "status" => $value->status,
	            "agama" => $value->agama,
	            "no_hp" => $value->no_hp,
	            "alamat" => $value->alamat,
	            "kec" => $value->kec,
	            "kab" => $value->kab,
	            "alamat2" => $value->alamat2,
	            "kec2" => $value->kec2,
	            "kab2" => $value->kab2,
	            "nm_photo" => $value->nm_photo,
	            "resume" => $value->resume,
	            "institusi" => $value->institusi,
	            "kualifikasi" => $value->kualifikasi,
	            "thn_lulus" => $value->thn_lulus,
	            "studi" => $value->studi,
	            "jurusan" => $value->jurusan,
	            "nilai" => $value->nilai,
	            "keterangan" => $value->keterangan,
	            "perusahaan" => $value->perusahaan,
	            "posisi" => $value->posisi,
	            "spesial" => $value->spesial,
	            "jabatan" => $value->jabatan,
	            "gaji" => $value->gaji,
	            "jobdesc" => $value->jobdesc,
	            "lama_kerja" => ( $value->lama_kerja == 0 || $value->lama_kerja == '' ) ? "Kurang Dari 1" : $value->lama_kerja,
	            "training" => $value->training,
	            "gaji_harapan" => $value->gaji_harapan,
	            "kelebihan" => $value->kelebihan
        	];
        }

        //output dalam format JSON
        echo json_encode(["data" => $data]);
    }

    function saveDataKaryawan(){

    	parse_str($_POST['data'], $data);
    	$files = $_FILES;
    	$id_personalia = $data['id_personalia-input'];
    	

    	$kontrak1_mulai = substr($data['lama_kontrak1-input'], 0, 10);
    	$kontrak1_selesai = substr($data['lama_kontrak1-input'], 13, 10);
    	$kontrak2_mulai = substr($data['lama_kontrak2-input'], 0, 10);
    	$kontrak2_selesai = substr($data['lama_kontrak2-input'], 13, 10);



        

    	$input =[
		    'nama' => $data['nama-input'],
		    'tgl_masuk' => ($data['tgl_masuk-input'] == '' || $data['tgl_masuk-input'] == '00-00-0000') ? '0000-00-00' : date('Y-m-d', strtotime($data['tgl_masuk-input'])),
		    'jen_kel' => $data['jenis_kelamin-input'],
		    'identitas' => $data['identitas_diri-input'],
		    'no_identitas' => $data['no_identitas-input'],
		    'tempat_lhr' => $data['tempat_lahir-input'],
		    'tgl_lhr' => ($data['tanggal_lahir-input'] == '' || $data['tanggal_lahir-input'] == '00-00-0000') ? '0000-00-00' : date('Y-m-d', strtotime($data['tanggal_lahir-input'])),
		    'sts_kawin' => $data['status_perkawinan-input'],
		    'agama' => $data['agama-input'],
		    'gol_darah' => $data['golongan_darah-input'],
		    'nm_ibu' => $data['nm_ibu-input'],
		    'nm_bpk' => $data['nm_bpk-input'],
		    'nm_pasangan' => $data['pasangan-input'],
		    'nm_anak1' => $data['anak1-input'],
		    'nm_anak2' => $data['anak2-input'],
		    'nm_anak3' => $data['anak3-input'],
		    'pdkn_akhir' => $data['pendidikan_terakhir-input'],
		    'institusi_pdkn' => $data['nm_institu-input'],
		    'prog_studi' => $data['program_studi-input'],
		    'no_hp' => $data['no_hp-input'],
		    'email' => $data['email-input'],
		    'almt_rumah' => $data['alamat-input'],
		    'almt_saat_ini' => $data['alamat_saat_ini-input'],
		    'nm_kntk_darurat' => $data['nm_kntk-input'],
		    'tlpn_darurat' => $data['tlpn_darurat-input'],
		    'nm_bank' => $data['nama_bank-input'],
		    'nm_rekening' => $data['nama_pemegang-input'],
		    'no_rek' => $data['no_rekening-input'],
		    'kntr_cab_bank' => $data['kantor_cbg_bank-input'],
		    'npwp' => $data['npwp-input'],
		    'sts_pajak' => $data['status_wjb_pjk-input'],
		    'no_bpjstk' => $data['no_kpj_bpjs-input'],
		    'prd_bpjstk' => ($data['efektif_sejak-input'] == '' || $data['efektif_sejak-input'] == '00-00-0000') ? '0000-00-00' : date('Y-m-d', strtotime($data['efektif_sejak-input'])),
		    'no_bpjsks' => $data['no_kartu_bpjs-input'],
		    'prd_bpjsks' => ($data['efektif_bpjsks-input'] == '' || $data['efektif_bpjsks-input'] == '00-00-0000') ? '0000-00-00' : date('Y-m-d', strtotime($data['efektif_bpjsks-input'])),
		    'departemen' => $data['departement-input'],
		    'jabatan' => $data['jabatan-input'],
		    'atasan' => $data['atasan-input'],
		    'status_karyawan' => $data['status_karyawan-input'],
		    'tgl_resign' => ($data['tgl_resign-input'] == '' || $data['tgl_resign-input'] == '00-00-0000') ? '0000-00-00' : date('Y-m-d', strtotime($data['tgl_resign-input'])),
		    'kontrak1_mulai' => date('Y-m-d', strtotime($kontrak1_mulai)),
		    'kontrak1_selesai' => date('Y-m-d', strtotime($kontrak1_selesai)),
		    'kontrak2_mulai' => date('Y-m-d', strtotime($kontrak2_mulai)),
		    'kontrak2_selesai' => date('Y-m-d', strtotime($kontrak2_selesai))
		];
        // print_r($input);
        // die;

		$this->db->update('personal', $input, ['id_personalia'=>$id_personalia]);    	

    	if( sizeof($files) > 0 ){

    		if(isset($files['profile'])){
    			$this->proses_upload($files['profile'], 'profile', $id_personalia, 'nm_file_photo');
    		}

    		if(isset($files['cv'])){
    			$this->proses_upload($files['cv'], 'cv', $id_personalia, 'nm_file_cv');
    		}

    		if(isset($files['transkrip'])){
    			$this->proses_upload($files['transkrip'], 'transkrip', $id_personalia, 'nm_file_tn');
    		}

    		if(isset($files['ktp'])){
    			$this->proses_upload($files['ktp'], 'ktp', $id_personalia, 'nm_file_ktp');
    		}

    		if(isset($files['tambahan'])){
    			$this->proses_upload($files['tambahan'], 'tambahan', $id_personalia, 'nm_file_lain');
    		}
    	}

    	echo json_encode(['data'=>['code'=>1,'last_id'=>$id_personalia]]);
    }

    function proses_upload($value, $nama, $id_personalia, $kolom)
    {
        $message = '';
        
        $fld = 'assets/files/karyawan/';
        $config['upload_path'] = $fld;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|PNG|pdf|PDF|doc|docx|txt';
        $config['max_size'] = '20000';
        //$config['max_width']  = '1940';
        //$config['max_height']  = '900';
        // $nm = ;
        $config['file_name']  = $id_personalia.'_'.$nama.'_'.$value['name'];
        //load library
        $this->load->library('upload', $config);
        // $a = $this->input->post('nama_field');
        // print_r($this->input->post('field_name'));die;
        // print_r($this->input->post());die;

        if (!$this->upload->do_upload($nama))
        {
            $message = $this->upload->display_errors();
            echo json_encode(array('error'=>$message));
        } else {
            $result = $this->upload->data();
            if($result !=''){
                foreach ($result as $item => $value){
                    $image_filename = $result['file_name'];
                }

                //build query
                $query = array(
                    'table' => 'personal',
                    'type' => $this->model->UPDATE, 
                    'data' => array($kolom => $image_filename), 
                    'at' => array('id_personalia' => $id_personalia) // clause for model
                );
                $result = $this->model->action($query); // do...
                // if ($result) {
                //     //$message = msgbox('ok', 'Data berhasil disimpan.');
                //     echo json_encode(array());
                // } else {
                //     $message = 'File gagal di upload.';
                //     echo json_encode(array('error' => $message));
                // }
            }
        }
    }
}