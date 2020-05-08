<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
require_once 'vendor/autoload.php';
 
$idHeader = $_POST['idFiles'];

$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if(isset($_FILES['FilePart']['name']) && in_array($_FILES['FilePart']['type'], $file_mimes)) {
 
    $arr_file = explode('.', $_FILES['FilePart']['name']);
    $extension = end($arr_file);
    
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }
    
    $spreadsheet = $reader->load($_FILES['FilePart']['tmp_name']);
    
    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $message = "";
    // var_dump($sheetData);
    // die;
	for($i = 3;$i < count($sheetData);$i++)
	{
        $no = $sheetData[$i]['0'];
        $tipe_id = $sheetData[$i]['1'];
        $item_code = $sheetData[$i]['2'];
        $item_name = $sheetData[$i]['3'];
        $spec = $sheetData[$i]['4'];
        $sql = "insert into bom_part_jasa (id_header,id_parent,tipe_item,tipe_id,tipe_name,item_code,item_name,spec) values 
        ('$idHeader','167','item','$no','$tipe_id','$item_code','$item_name','$spec')";
        $insert = $this->db->query($sql);
    }
    if($insert){
        $message = "DATA BERHASIL DISIMPAN";
    }else{
        $message = "DATA GAGAL DISIMPAN";
    }
    echo json_encode(['message' => $message]);

    // header("Location: form_upload.html"); 
}
?>