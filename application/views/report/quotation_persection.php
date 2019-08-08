<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

require_once 'vendor/autoload.php';

function cellMerge($activeSheet, $cellNo)
{
    $activeSheet->mergeCells($cellNo);
}

function numberFormat($activeSheet, $cellNo)
{
    $activeSheet->getStyle($cellNo)->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}

function addSpaceName($tipe_item)
{
    if ($tipe_item == 'object') {
        return '  ';
    } else if ($tipe_item == 'sub_object') {
        return '    ';
    }
    return '';
}

$arrHeaderPart = [
    'No',
    'Item Code',
    'Item Name',
    'Spec',
    '',
    'Maker',
    'Units',
    'Category',
    '',
    'Remark',
    '',
    'Unit Price',
    'Qty',
    'Total',

];

$arrHeaderMaterial = [
    'No',
    'Item Code',
    'Item Name',
    'Units',
    'Qty',
    'Materials',
    'Diameter/Ketebalan',
    'Length',
    'Width',
    'Height',
    'Density',
    'Weight Total',
    'Price',
    'Total',
];

$arrHeaderLabour = [
    'Id',
    'Name',
    'Id labour',
    'Aktifitas',
    'Hour',
    'Rate',
    'Total',
];

$sectionStyle = [
    'font'  => [
        'bold'  => true,
        'size' => 12
    ]
];

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
        'size' => 9
    ]
];

// $dataPart = $this->reporter->getStructure($dataPart, 'findChildPart');

// $itemPart = $this->reporter->getItemOnly();
// print_r($dataPart);
// die;
$spreadsheet = new Spreadsheet();
$n = 0;
foreach ($sectionPart as $key => $section) {
    // create new sheet
    $myWorkSheet = new Worksheet($spreadsheet, $section['tipe_name']);
    // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
    $spreadsheet->addSheet($myWorkSheet, $n);
    $spreadsheet->setActiveSheetIndex($n);
    $activeSheet = $spreadsheet->getActiveSheet();

    $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
    $activeSheet->setCellValue('A1', 'No');
    $activeSheet->setCellValue('B1', 'Nama Section');
    $activeSheet->setCellValue('A2', $n + 1);
    $activeSheet->setCellValue('B2', $section['tipe_name']);
    // set section style
    $activeSheet->getStyle('A1:B1')->applyFromArray($sectionStyle);
    $activeSheet->getStyle('A2:B2')->applyFromArray($sectionStyle);

    // set column a dimension
    $activeSheet->getColumnDimension('A')->setWidth(5);
    $activeSheet->getColumnDimension('B')->setWidth(5);
    $activeSheet->getColumnDimension('C')->setWidth(10);
    $activeSheet->getColumnDimension('D')->setWidth(30);
    $activeSheet->getColumnDimension('E')->setWidth(20);
    // $activeSheet->getColumnDimension('H')->setWidth(15);
    $activeSheet->getColumnDimension('L')->setWidth(10);
    $activeSheet->getColumnDimension('M')->setWidth(15);
    $activeSheet->getColumnDimension('O')->setWidth(15);

    $row = 5;
    $activeSheet->fromArray($arrHeaderPart, NULL, "B$row");
    $activeSheet->getStyle("B$row:O$row")->applyFromArray($headerStyle);
    cellMerge($activeSheet, "E$row:F$row");
    cellMerge($activeSheet, "I$row:J$row");
    cellMerge($activeSheet, "K$row:L$row");

    // query part jasa
    // $this->db->select('p.*,p.qty * p.harga AS `total`,a.desc as nama_kategori', false);
    // $this->db->where(['deleted <>' => 1]);
    // // $this->db->where(['deleted <>' => 1, 'tipe_item' => 'item']);
    // $this->db->where_in('id', $parentPart[$n]);
    // $this->db->or_where_in('id_parent', $parentPart[$n]);
    // $this->db->join('sgedb.akunbg a', 'p.kategori = a.accno', 'left');
    // $this->db->order_by('nama_kategori', 'ASC');
    // $itemPart1 = $this->db->get("{$this->db->database}.part_jasa p")->result_array();
    // echo $this->db->last_query();
    // $itemPart = array_merge($)
    $itemPart = $this->reporter->getStructure($dataPart, 'findChildPart');
    $newPart = [];
    foreach ($itemPart as $key => $item) {
        if (in_array($item['id'], $parentPart[$n])) {
            array_push($newPart, $item);
        } else {
            if (in_array($item['id_parent'], $parentPart[$n])) {
                array_push($newPart, $item);
            }
        }
    }

    $activeSheet->setCellValue('B4', 'Part & Jasa');
    $activeSheet->getStyle('B4')->getFont()->setSize(11);
    $activeSheet->getStyle('B4')->getFont()->setBold(true);

    $row += 1;
    $noitem = 0;
    $sub_total = 0;
    $grand_total = 0;
    foreach ($newPart as $key => $part) {
        $noitem++;
        $total = (int) $part['qty'] * (int) $part['harga'];
        $sub_total += $total;

        $activeSheet->getCell('B' . $row)->setValueExplicit($part['tipe_item'] == 'item' ? '' : strval($part['tipe_id']), DataType::TYPE_STRING);
        $activeSheet->setCellValue('C' . $row, $part['item_code']);
        $activeSheet->setCellValue('D' . $row, $part['tipe_item'] == 'item' ? $part['item_name'] : addSpaceName($part['tipe_item']).$part['tipe_name']);
        $activeSheet->setCellValue('E' . $row, $part['spec']);
        $activeSheet->setCellValue('G' . $row, $part['merk']);
        $activeSheet->setCellValue('H' . $row, $part['satuan']);
        $activeSheet->setCellValue('I' . $row, isset($part['nama_kategori']) ? $part['nama_kategori'] : '');
        $activeSheet->setCellValue('K' . $row, $part['remark_harga']);
        $activeSheet->setCellValue('M' . $row, $part['tipe_item'] == 'item' ? $part['harga'] : '');
        $activeSheet->setCellValue('N' . $row, $part['tipe_item'] == 'item' ? $part['qty'] : '');
        $activeSheet->setCellValue('O' . $row, $part['tipe_item'] == 'item' ? $total : '');

        /* Number format */
        numberFormat($activeSheet, "M$row");
        numberFormat($activeSheet, "O$row");
        // $activeSheet->getStyle("H$row")->getNumberFormat()
        //     ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $activeSheet->getStyle("J$row")->getNumberFormat()
        //     ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        /* Merge */
        cellMerge($activeSheet, "E$row:F$row");
        cellMerge($activeSheet, "I$row:J$row");
        cellMerge($activeSheet, "K$row:L$row");
        // $activeSheet->mergeCells("E$row:F$row");
        $activeSheet->getStyle("B$row:O$row")->applyFromArray(
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        // 'startColor' => ['argb' => '00000000'],
                    ],
                ],
                'font'  => [
                    'size' => 9,
                    'name' => 'Calibri',
                ]
            ]
        );
        $row++;
    }

    // sub total material
    // $row += 1;
    $tot_alw = ($allowance / 100 * $sub_total);
    $sub = $sub_total + $tot_alw;
    $grand_total += $sub;

    $activeSheet->setCellValue("M$row", "Overrage $allowance %");
    $activeSheet->setCellValue("O$row", $tot_alw);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);
    $row += 1;
    $activeSheet->setCellValue("M$row", "Sub Total");
    $activeSheet->setCellValue("O$row", $sub);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);

    // query material
    // $this->db->select('m.*', false);
    // $this->db->where(['deleted <>' => 1, 'tipe_item' => 'item']);
    // $this->db->where_in('id_parent', $parentMaterial[$n]);
    // $itemMaterial = $this->db->get("v_rawmaterial m")->result_array();
    $tempMaterial = $this->reporter->getStructure($dataMaterial, 'findChildMaterial');
    $itemMaterial = [];
    foreach ($tempMaterial as $key => $item) {
        if (in_array($item['id'], $parentMaterial[$n])) {
            array_push($itemMaterial, $item);
        } else {
            if (in_array($item['id_parent'], $parentMaterial[$n])) {
                array_push($itemMaterial, $item);
            }
        }
    }


    $row += 2;
    $activeSheet->setCellValue("B$row", "Raw Material");
    $activeSheet->getStyle("B$row")->getFont()->setSize(11);
    $activeSheet->getStyle("B$row")->getFont()->setBold(true);

    $row += 1;
    $activeSheet->fromArray($arrHeaderMaterial, NULL, "B$row");
    $activeSheet->getStyle("B$row:O$row")->applyFromArray($headerStyle);

    $row += 1;
    $noitem = 0;
    $sub_total = 0;
    foreach ($itemMaterial as $key => $material) {
        $noitem++;
        $weight = isset($material['weight']) ? $material['weight'] : 0;
        $price = isset($material['price']) ? $material['price'] : 0;
        $total = (float) $weight * (float) $price;
        $sub_total += $total;

        // $activeSheet->setCellValue('B' . $row, $noitem);
        $activeSheet->getCell('B' . $row)->setValueExplicit($material['tipe_item'] == 'item' ? '' : strval($material['tipe_id']), DataType::TYPE_STRING);
        $activeSheet->setCellValue('C' . $row, $material['tipe_item'] == 'item' ? $material['item_code'] : '');
        $activeSheet->setCellValue('D' . $row, $material['tipe_item'] == 'item' ? $material['part_name'] : addSpaceName($material['tipe_item']).$material['tipe_name']);
        // $activeSheet->setCellValue('D' . $row, isset($material['part_name']) ? $material['part_name'] : '');
        $activeSheet->setCellValue('E' . $row, isset($material['units']) ? $material['units'] : '');
        $activeSheet->setCellValue('F' . $row, isset($material['qty']) ? $material['qty'] : '');
        $activeSheet->setCellValue('G' . $row, isset($material['materials']) ? $material['materials'] : '');
        $activeSheet->setCellValue('H' . $row, isset($material['t']) ? $material['t'] : '');
        $activeSheet->setCellValue('I' . $row, isset($material['l']) ? $material['l'] : '');
        $activeSheet->setCellValue('J' . $row, isset($material['w']) ? $material['w'] : '');
        $activeSheet->setCellValue('K' . $row, isset($material['h']) ? $material['h'] : '');
        $activeSheet->setCellValue('L' . $row, isset($material['density']) ? $material['density'] : '');
        $activeSheet->setCellValue('M' . $row, isset($material['weight']) ? $material['weight'] : '');
        $activeSheet->setCellValue('N' . $row, isset($material['price']) ? $material['price'] : '');
        $activeSheet->setCellValue('O' . $row, $total);
        $activeSheet->getStyle("N$row")->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $activeSheet->getStyle("O$row")->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $activeSheet->getStyle("B$row:O$row")->applyFromArray(
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        // 'startColor' => ['argb' => '00000000'],
                    ],
                ],
                'font'  => [
                    'size' => 9,
                    'name' => 'Calibri',
                ]
            ]
        );

        $row++;
    }

    // sub total material
    $tot_alw = 0;
    $tot_alw = ($allowance / 100 * $sub_total);
    $sub = $sub_total + $tot_alw;
    $grand_total += $sub;

    $activeSheet->setCellValue("M$row", "Overrage $allowance %");
    $activeSheet->setCellValue("O$row", $tot_alw);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);
    $row += 1;
    $activeSheet->setCellValue("M$row", "Sub Total");
    $activeSheet->setCellValue("O$row", $sub);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);

    // query labour
    $this->db->select('l.*,a.desc as nama_kategori', false);
    $this->db->where(['l.deleted <>' => 1]);
    $this->db->where_in('l.id_parent', $parentLabour[$n]);
    $this->db->or_where_in('l.id', $parentLabour[$n]);
    $this->db->join('sgedb.akunbg a', 'l.id_labour = a.accno');
    $itemLabour = $this->db->get("{$this->db->database}.v_labour l")->result_array();

    $row += 2;
    $activeSheet->setCellValue("B$row", "Labour");
    $activeSheet->getStyle("B$row")->getFont()->setSize(11);
    $activeSheet->getStyle("B$row")->getFont()->setBold(true);

    $row += 1;
    $activeSheet->fromArray($arrHeaderLabour, NULL, "B$row");
    $activeSheet->getStyle("B$row:O$row")->applyFromArray($headerStyle);
    cellMerge($activeSheet, "C$row:E$row");
    cellMerge($activeSheet, "F$row:H$row");
    cellMerge($activeSheet, "I$row:L$row");

    $row += 1;
    $noitem = 0;
    $sub_total = 0;
    foreach ($itemLabour as $key => $labour) {
        if ($labour['hour'] > 0) {
            $noitem++;
            $total = $labour['hour'] * $labour['rate'];
            $sub_total += $total;

            $activeSheet->getCell('B' . $row)->setValueExplicit(strval($labour['tipe_id']), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeSheet->setCellValue('C' . $row, $labour['tipe_name']);
            $activeSheet->setCellValue('F' . $row, $labour['nama_kategori']);
            $activeSheet->setCellValue('I' . $row, $labour['aktivitas']);
            $activeSheet->setCellValue('M' . $row, $labour['hour']);
            $activeSheet->setCellValue('N' . $row, $labour['rate']);
            $activeSheet->setCellValue('O' . $row, $total);

            /* Number format */
            numberFormat($activeSheet, "O$row");

            /* Merge Cells */
            cellMerge($activeSheet, "C$row:E$row");
            cellMerge($activeSheet, "F$row:H$row");
            cellMerge($activeSheet, "I$row:L$row");
            $activeSheet->getStyle("B$row:O$row")->applyFromArray(
                [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            // 'startColor' => ['argb' => '00000000'],
                        ],
                    ],
                    'font'  => [
                        'size' => 9,
                        'name' => 'Calibri',
                    ]
                ]
            );

            $row++;
        }
    }

    // sub total material
    $sub = $sub_total;
    $grand_total += $sub;

    $activeSheet->setCellValue("M$row", "Sub Total");
    $activeSheet->setCellValue("O$row", $sub);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);

    $row++;
    $activeSheet->setCellValue("M$row", "Grand Total");
    $activeSheet->setCellValue("O$row", $grand_total);
    $activeSheet->getStyle("O$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setSize(9);
    $activeSheet->getStyle("M$row:O$row")->getFont()->setBold(true);


    // no section
    $n++;
}

$spreadsheet->setActiveSheetIndex(0);

// var_dump($spreadsheet);die;
// Create new Spreadsheet object


// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
header("Access-Control-Allow-Origin: *");
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
