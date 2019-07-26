<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once 'vendor/autoload.php';

$arrHeaderSummary = [
    'Raw Material',
    'Mechanic',
    'Electric',
    'Pneumatic',
    'Hydraulic',
    'Subcont',
    'ENGINEERING',
    'PRODUCTION',
];

$dataSectionPart = array_filter($part, function ($item) {
    return $item['tipe_item'] == 'section';
});

$parentPart = $this->reporter->getStructureTree($part);
$storedPart = [];
$n = 0;
foreach ($dataSectionPart as $key => $value) {
    $parent = implode("','",array_values($parentPart[$n]));
    $sql = "SELECT `id`,
    `id_header`,
    `id_parent`,
    `tipe_item`,
    `tipe_id`,
    `tipe_name`,
    `item_code`,
    `item_name`,
    `spec`,
    `merk`,
    `satuan`,
    `harga`,
    `qty`,
    `kategori`,
    `updated_datetime`,
    `tipe_parent`,
    `nama_kategori`, SUM(rm) AS total_rm,SUM(elc) AS total_elc, SUM(pnu) AS total_pnu, SUM(hyd) AS total_hyd, SUM(mch) AS total_mch, SUM(sub) as total_sub, {$value['id']} AS id_section FROM (SELECT
        j.*,
        (SELECT
                tipe_item
            FROM
                quotation.part_jasa p
            WHERE
                p.id = j.id_parent) AS tipe_parent,
        k.`desc` AS nama_kategori, 
        if(j.kategori = '10001',j.harga * qty,0) AS rm,
        if(j.kategori = '10002',j.harga * qty,0) AS elc,
        if(j.kategori = '10004',j.harga * qty,0) AS pnu,
        if(j.kategori = '10005',j.harga * qty,0) AS hyd,
        if(j.kategori = '10003',j.harga * qty,0) AS mch,
        if(j.kategori = '20001',j.harga * qty,0) AS sub
    FROM
        quotation.`part_jasa` j
            LEFT JOIN
        `sgedb`.`akunbg` k ON j.kategori = k.accno
    WHERE
        
    j.id_header = '{$value['id_header']}' AND j.id_parent IN('$parent') AND j.tipe_item = 'item') AS grouping GROUP BY id_section";

    $data = $this->db->query($sql)->result_array();
    if (count($data) > 0) {
        foreach ($data as $key => $item) {
            $item['tipe_id'] = $item['tipe_item'] == 'item' ? '' : $item['tipe_id'];
            array_push($storedPart, $item);
        }
    }
    $n++;
}
// output $sortedPart

// dataMaterial
$dataMaterial = $this->reporter->getStructure($material, 'findChildMaterial');
$sectionMaterial = array_filter($dataMaterial, function ($item) {
    return $item['tipe_item'] == 'section';
});

// data Material
$dataParentMaterial = array_filter($labour, function ($item) {
    return $item['tipe_item'] != 'item';
});

$dataSort = $this->reporter->getStructure($dataParentMaterial, 'findChildLabour');
$storedMaterial = array_values($sectionMaterial);
// output $sortedMaterial

// data Labour
$dataSectionLabour = array_filter($labour, function ($item) {
    return $item['tipe_item'] == 'section';
});
// print_r($dataSectionLabour);die;
$parentLabour = $this->reporter->getStructureTree($labour);
$storedLabour = [];
$n = 0;
foreach ($dataSectionLabour as $key => $value) {
    $parent = implode("','",array_values($parentLabour[$n]));
    $sql = "SELECT 
    `id`,
    `id_parent`,
    `id_header`,
    `id_part_jasa`,
    `tipe_id`,
    `tipe_item`,
    `tipe_name`,
    `id_labour`,
    `aktivitas`,
    `sub_aktivitas`,
    `hour`,
    `rate`,
    `updated_datetime`,
    `opsi`,
    `tipe_parent`,
    `id_section`, SUM(eng) AS total_eng, SUM(prod) AS total_prod FROM (SELECT
    `l`.*,id AS opsi, 
        (
    SELECT
        tipe_item
    FROM
        labour b
    WHERE
        b.id = l.id_parent) AS tipe_parent, if(l.tipe_name = 'ENGINEERING', (l.`hour` * l.rate),0) AS eng,if(l.tipe_name = 'PRODUCTION', (l.`hour` * l.rate),0) AS prod, {$value['id_part_jasa']} AS id_section
    FROM
        `labour` `l`
    WHERE
        l.id_header ='{$value['id_header']}' AND l.id_parent IN ('$parent') AND l.tipe_item = 'item') AS grouping GROUP BY id_section";
    // echo $sql;die;
    $data = $this->db->query($sql)->result_array();
    if (count($data) > 0) {
        foreach ($data as $key => $item) {
            $item['tipe_id'] = $item['tipe_item'] == 'item' ? '' : $item['tipe_id'];
            array_push($storedLabour, $item);
        }
    }
    $n++;
}

$data = [];
$no = 0;
foreach ($dataSectionPart as $key => $value) {
    $no = ++$no;
    $temp = [];
    $temp['no'] = $no;
    $temp['id'] = $value['id'];
    $temp['tipe_id'] = $value['tipe_id'];
    $temp['tipe_name'] = $value['tipe_name'];
    foreach ($storedPart as $key => $part) {
        if($value['id'] == $part['id_section']){
            $temp['total_rm'] = $part['total_rm'];
            $temp['total_elc'] = $part['total_elc'];
            $temp['total_pnu'] = $part['total_pnu'];
            $temp['total_hyd'] = $part['total_hyd'];
            $temp['total_mch'] = $part['total_mch'];
            $temp['total_sub'] = $part['total_sub'];
        }
    }

    foreach ($storedMaterial as $key => $material) {
        if($value['id'] == $material['id_part_jasa']){
            $temp['total_rm'] += $material['total'];
        }
    }

    foreach ($storedLabour as $key => $labour) {
        // var_dump($labour);die;
        if($value['id'] == $labour['id_part_jasa']){
            $temp['total_eng'] = $labour['total_eng'];
            $temp['total_prod'] = $labour['total_prod'];
        }
    }
    $data[] = $temp;
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle('Labour');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$activeSheet->setCellValue('A1', "Summary Inquiry # $inquiry_no");
$activeSheet->getStyle("A1")->getFont()->setSize(16);

//output headers
$headerStyle = [
    'fill' => [
        'fillType' => Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'EEEEEEEE',
        ],
        'endColor' => [
            'argb' => 'EEEEEEEE',
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
    'font'  => [
        'bold'  =>  true,
        'size' => 10
    ]
];

$activeSheet->getStyle('A3:K3')->applyFromArray($headerStyle);
$activeSheet->getStyle('A4:K4')->applyFromArray($headerStyle);
$activeSheet->setCellValue('A' . 3, 'No');
$activeSheet->setCellValue('B' . 3, 'Section');
$activeSheet->setCellValue('C' . 3, 'Name');
$activeSheet->setCellValue('D' . 3, 'Budget');
$activeSheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$activeSheet->getStyle('C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$activeSheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$activeSheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeSheet->mergeCells('D3:K3');
$activeSheet->mergeCells('A3:A4');
$activeSheet->mergeCells('B3:B4');
$activeSheet->mergeCells('C3:C4');
$activeSheet->fromArray($arrHeaderSummary, NULL, 'D4');
// $activeSheet->getStyle('A4:J4')->applyFromArray($headerStyle);

// Looping part & jasa

$row = 0;
foreach ($data as $key => $value) {
    $row = (int) $key + 5;
    $formatNum = ['C','D','E','F','G','H','I','J','K'];
    $activeSheet->setCellValue('A' . $row, $value['no']);
    $activeSheet->setCellValue('B' . $row, $value['tipe_id']);
    $activeSheet->setCellValue('C' . $row, $value['tipe_name']);
    $activeSheet->setCellValue('D' . $row, $value['total_rm']);
    $activeSheet->setCellValue('E' . $row, $value['total_mch']);
    $activeSheet->setCellValue('F' . $row, $value['total_elc']);
    $activeSheet->setCellValue('G' . $row, $value['total_pnu']);
    $activeSheet->setCellValue('H' . $row, $value['total_hyd']);
    $activeSheet->setCellValue('I' . $row, $value['total_sub']);
    $activeSheet->setCellValue('J' . $row, $value['total_eng']);
    $activeSheet->setCellValue('K' . $row, $value['total_prod']);
    foreach ($formatNum as $v) {
        $activeSheet->getStyle("$v$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);        
    }    
    $activeSheet->getStyle("A$row:K$row")->applyFromArray(
        [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'startColor' => ['argb' => '00000000'],
                ],
            ],
            'font'  => [
                'size' => 9,
                'name' => 'Calibri',
            ]
        ]
    );
}

// print_r($spreadsheet);die;

// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
