<?php
// $filename = 'report_part_jasa'.date('d-m-Y');
// header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
// header('Content-Type: application/vnd.ms-excel');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

require_once 'vendor/autoload.php';

$arrHeaderPart = [
    'Section / Object',
    'Name',
    'Item Code',
    'Item Name',
    'Spec',
    'Maker',
    'Units',
    'Unit Price',
    'Qty',
    'Total',
    'Category',
    'Remark'
];

$dataPart = $this->reporter->getStructure($part, 'findChildPart');

// print_r($dataPart);die;
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle('Part & Jasa');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$activeSheet->setCellValue('A1', 'Detail Part & Jasa');
$activeSheet->getStyle("A1")->getFont()->setSize(16);

//output headers
$headerStyle = [
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
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
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
    'font'  => [
        'bold'  =>  true,
        'size' => 10
    ]
];
$activeSheet->fromArray($arrHeaderPart, NULL, 'A3');
$activeSheet->getStyle('A3:L3')->applyFromArray($headerStyle);

// Looping part & jasa

$row = 0;
foreach ($dataPart as $key => $part) {
    $row = (int) $key + 4;
    $color = $this->reporter->typeCheck($part['tipe_item']);

    $activeSheet->getCell('A' . $row)->setValueExplicit($part['tipe_item'] == 'item' ? '' : strval($part['tipe_id']), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $activeSheet->setCellValue('B' . $row, $part['tipe_name']);
    $activeSheet->setCellValue('C' . $row, $part['item_code']);
    $activeSheet->setCellValue('D' . $row, $part['item_name']);
    $activeSheet->setCellValue('E' . $row, $part['spec']);
    $activeSheet->setCellValue('F' . $row, $part['merk']);
    $activeSheet->setCellValue('G' . $row, $part['satuan']);
    $activeSheet->setCellValue('H' . $row, $part['harga']);
    $activeSheet->setCellValue('I' . $row, $part['qty']);
    $activeSheet->setCellValue('J' . $row, $part['total']);
    $activeSheet->setCellValue('K' . $row, $part['kategori']);
    $activeSheet->setCellValue('L' . $row, $part['remark_harga']);
    $activeSheet->getStyle("H$row")->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("J$row")->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("A$row:L$row")->applyFromArray(
        [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => $color,
                ],
                'endColor' => [
                    'argb' => $color,
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
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

// print_r($material);die;
// Material
$arrHeaderMaterial = [
    'Section / Object',
    'Name',
    'Item Code',
    'Item Name',
    'Units',
    'Qty',
    'Materials',
    'Length',
    'Weight',
    'Height',
    'Diameter',
    'Density',
    'Weight Total',
    'Total',
];
$row += 3;
$activeSheet->setCellValue("A$row", 'Detail Raw Material');
$activeSheet->getStyle("A$row")->getFont()->setSize(16);
$dataMaterial = $this->reporter->getStructure($material, 'findChildMaterial');
// Header Material

$row+=2;
$activeSheet->fromArray($arrHeaderMaterial, NULL, "A$row");
$activeSheet->getStyle("A$row:N$row")->applyFromArray($headerStyle);

foreach ($dataMaterial as $key => $part) {
    // var_dump($part);die;
    $row ++;
    $color = $this->reporter->typeCheck($part['tipe_item']);

    $activeSheet->getCell('A' . $row)->setValueExplicit($part['tipe_item'] == 'item' ? '' : strval($part['tipe_id']), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $activeSheet->setCellValue('B' . $row, $part['tipe_name']);
    $activeSheet->setCellValue('C' . $row, $part['item_code']);
    $activeSheet->setCellValue('D' . $row, $part['part_name']);
    $activeSheet->setCellValue('E' . $row, $part['units']);
    $activeSheet->setCellValue('F' . $row, $part['qty']);
    $activeSheet->setCellValue('G' . $row, $part['materials']);
    $activeSheet->setCellValue('H' . $row, $part['l']);
    $activeSheet->setCellValue('I' . $row, $part['w']);
    $activeSheet->setCellValue('J' . $row, $part['h']);
    $activeSheet->setCellValue('K' . $row, $part['t']);
    $activeSheet->setCellValue('L' . $row, $part['density']);
    $activeSheet->setCellValue('M' . $row, $part['weight']);
    $activeSheet->setCellValue('N' . $row, $part['total']);
    $activeSheet->getStyle("N$row")->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    // $activeSheet->getStyle("J$row")->getNumberFormat()
    //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("A$row:N$row")->applyFromArray(
        [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => $color,
                ],
                'endColor' => [
                    'argb' => $color,
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
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

// print_r($dataMaterial);die;

// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
