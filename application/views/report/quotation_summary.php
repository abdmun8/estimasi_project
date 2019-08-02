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

// print_r($data);die;

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

    $formatNum = ['C','D','E','F','G','H','I','J','K'];
    $activeSheet->setCellValue('A' . $row, $value['no']);
    $activeSheet->setCellValue('B' . $row, $value['tipe_id']);
    $activeSheet->setCellValue('C' . $row, $value['tipe_name']);
    $activeSheet->setCellValue('D' . $row, $total_rm + ($allowance / 100 * $total_rm ));
    $activeSheet->setCellValue('E' . $row, $total_mch + ($allowance / 100 * $total_mch));
    $activeSheet->setCellValue('F' . $row, $total_elc + ($allowance / 100 * $total_elc));
    $activeSheet->setCellValue('G' . $row, $total_pnu + ($allowance / 100 * $total_pnu));
    $activeSheet->setCellValue('H' . $row, $total_hyd + ($allowance / 100 * $total_hyd));
    $activeSheet->setCellValue('I' . $row, $total_sub + ($allowance / 100 * $total_sub));
    $activeSheet->setCellValue('J' . $row, $value['total_eng']);
    $activeSheet->setCellValue('K' . $row, $value['total_prod'] * $qty);
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
