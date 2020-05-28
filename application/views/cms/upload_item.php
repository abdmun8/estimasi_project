<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once 'vendor/autoload.php';

$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if (isset($_FILES['FilePart']['name']) && in_array($_FILES['FilePart']['type'], $file_mimes)) {

    $arr_file = explode('.', $_FILES['FilePart']['name']);
    $extension = end($arr_file);

    if ('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }

    $spreadsheet = $reader->load($_FILES['FilePart']['tmp_name']);

    // $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $numberSheet = $spreadsheet->getSheetCount(); // Get Total of Worksheet
    $listWorkSheet = $spreadsheet->getSheetNames(); // Get list Name of Worksheet
    $message = "";
    for ($i = 0; $i < $numberSheet; $i++) {
        var_dump('Sheet ke' . $i . ' adalah ' . $listWorkSheet[$i]);
        $spreadsheet->setActiveSheetIndexByName($listWorkSheet[$i]);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        uploadData($sheetData, $listWorkSheet[$i]);
        // var_dump($sheetData);
    }
    
    echo json_encode(['message' => $message]);

    // header("Location: form_upload.html"); 
}

function uploadData($data = [], $sheetName = '')
{
    $idHeader = $_POST['idHeader'];
    $idParent = $_POST['idParent'];
    $dwgNumber = substr($sheetName, 0, 12);
    $dwgName = trim(substr($sheetName, 13, 100));
    $idUser = $_POST['idUser'];

    $sql = "SELECT `value` FROM config WHERE `key`='MATL_TYPE'";
    // $material = ['SS400','MILD STEEL','S45C',''];

    if ($dwgNumber == '00-00-00-000') {
        for ($i = 10; $i < count($data); $i++) {
            $desc = $data[$i]['3'];
            $matlName = $data[$i]['4'];
            $matlSize = $data[$i]['5'];
            $matrialOrBrand =  $data[$i]['6'];
            $qty =  $data[$i]['7'];
            $unit =  $data[$i]['8'];
            $mass =  $data[$i]['9'];

            $sql = "INSERT INTO bom_part_jasa 
            (id_header,id_parent,tipe_item,tipe_id,tipe_name,item_code,item_name,spec,satuan,qty,users) values 
            ({$idHeader},{$idParent},'item','','','','{$matlName}','{$matlSize}','{$unit}','{$qty}','{$idUser}')";
            var_dump($sql);
            die;
            $insert = $this->db->query($sql);
        }
        // if ($insert) {
        //     $message = "DATA BERHASIL DISIMPAN";
        // } else {
        //     $message = "DATA GAGAL DISIMPAN";
        // }
    }
}
