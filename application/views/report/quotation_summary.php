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

// get data from controller
$data = $summary;

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle('Labour');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$activeSheet->setCellValue('A1', "Summary Inquiry # $inquiry_no");
$activeSheet->mergeCells('A1:B1');
$activeSheet->getStyle("A1")->getFont()->setSize(16);

$activeSheet->getColumnDimension('A')->setWidth(6);
$activeSheet->getColumnDimension('B')->setWidth(25);
$activeSheet->getColumnDimension('C')->setWidth(15);
$activeSheet->getColumnDimension('D')->setWidth(15);
$activeSheet->getColumnDimension('E')->setWidth(15);
$activeSheet->getColumnDimension('F')->setWidth(15);
$activeSheet->getColumnDimension('G')->setWidth(15);
$activeSheet->getColumnDimension('H')->setWidth(15);
$activeSheet->getColumnDimension('I')->setWidth(15);
$activeSheet->getColumnDimension('J')->setWidth(15);

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

$activeSheet->getStyle('A3:J3')->applyFromArray($headerStyle);
$activeSheet->getStyle('A4:J4')->applyFromArray($headerStyle);
$activeSheet->setCellValue('A' . 3, 'Section');
$activeSheet->setCellValue('B' . 3, 'Name');
$activeSheet->setCellValue('C' . 3, 'Budget');
$activeSheet->getStyle('A3:B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
// $activeSheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$activeSheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeSheet->getStyle('C4:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeSheet->mergeCells('C3:J3');
$activeSheet->mergeCells('A3:A4');
$activeSheet->mergeCells('B3:B4');
$activeSheet->fromArray($arrHeaderSummary, NULL, 'C4');
// $activeSheet->getStyle('A4:J4')->applyFromArray($headerStyle);

// Looping part & jasa 
// total = value * qty 

$row = 0;
foreach ($data as $key => $value) {
    $row = (int) $key + 5;
    $qty = $value['qty'];

    $total_rm = $value['total_rm'] * $qty;
    $total_mch = $value['total_mch'] * $qty;
    $total_elc = $value['total_elc'] * $qty;
    $total_pnu = $value['total_pnu'] * $qty;
    $total_hyd =  $value['total_hyd'] * $qty;
    $total_sub = $value['total_sub'] * $qty;

    $total_eng = $value['group'] == 1 ? $value['total_eng'] * $qty : $value['total_eng'];

    $formatNum = ['C','D','E','F','G','H','I','J','K'];
    $activeSheet->setCellValue('A' . $row, $value['tipe_id']);
    $activeSheet->setCellValue('B' . $row, $value['tipe_name']);
    $activeSheet->setCellValue('C' . $row, $total_rm + ($allowance / 100 * $total_rm ));
    $activeSheet->setCellValue('D' . $row, $total_mch + ($allowance / 100 * $total_mch));
    $activeSheet->setCellValue('E' . $row, $total_elc + ($allowance / 100 * $total_elc));
    $activeSheet->setCellValue('F' . $row, $total_pnu + ($allowance / 100 * $total_pnu));
    $activeSheet->setCellValue('G' . $row, $total_hyd + ($allowance / 100 * $total_hyd));
    $activeSheet->setCellValue('H' . $row, $total_sub + ($allowance / 100 * $total_sub));
    $activeSheet->setCellValue('I' . $row, $total_eng);
    $activeSheet->setCellValue('J' . $row, $value['total_prod'] * $qty);
    foreach ($formatNum as $v) {
        $activeSheet->getStyle("$v$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);        
    }    
    $activeSheet->getStyle("A$row:J$row")->applyFromArray(
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
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
// header("Access-Control-Allow-Origin: *");
// header('Cache-Control: max-age=0');

// $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
// exit;

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$file = 'temp/'.$title.'.xlsx';
$writer->save($file);
$base_url = base_url();

header("location:$base_url$file");
