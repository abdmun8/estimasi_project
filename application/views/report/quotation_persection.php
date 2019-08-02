<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

require_once 'vendor/autoload.php';

$arrHeaderPart = [
    'No',
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

$arrHeaderMaterial = [
    'No',
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
// print_r($itemPart);
// die;
$spreadsheet = new Spreadsheet();
$n = 0;
foreach ($sectionPart as $key => $section) {
    // var_dump($section);die;
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
    $activeSheet->getColumnDimension('C')->setWidth(20);
    $activeSheet->getColumnDimension('D')->setWidth(30);
    $activeSheet->getColumnDimension('E')->setWidth(20);
    $activeSheet->getColumnDimension('H')->setWidth(15);
    $activeSheet->getColumnDimension('J')->setWidth(12);
    $activeSheet->getColumnDimension('K')->setWidth(20);
    $activeSheet->getColumnDimension('L')->setWidth(20);

    $activeSheet->fromArray($arrHeaderPart, NULL, 'B5');
    $activeSheet->getStyle('B5:L5')->applyFromArray($headerStyle);

    // query part jasa
    $this->db->select('p.*,a.desc as nama_kategori', false);
    $this->db->where(['deleted <>' => 1, 'tipe_item' => 'item']);
    $this->db->where_in('id_parent', $parentPart[$n]);
    $this->db->join('sgedb.akunbg a', 'p.kategori = a.accno');
    $this->db->order_by('nama_kategori', 'ASC');
    $itemPart = $this->db->get("{$this->db->database}.part_jasa p")->result_array();
    // echo $this->db->last_query();
    // var_dump($itemPart);
    // die;

    $activeSheet->setCellValue('B4', 'Part & Jasa');
    $activeSheet->getStyle('B4')->getFont()->setSize(11);
    $activeSheet->getStyle('B4')->getFont()->setBold(true);

    $row += 5;
    $noitem = 0;
    $sub_total = 0;
    $grand_total = 0;
    foreach ($itemPart as $key => $part) {
        $noitem++;
        $total = $part['qty'] * $part['harga'];
        $sub_total += $total;

        $activeSheet->setCellValue('B' . $row, $noitem);
        $activeSheet->setCellValue('C' . $row, $part['item_code']);
        $activeSheet->setCellValue('D' . $row, $part['item_name']);
        $activeSheet->setCellValue('E' . $row, $part['spec']);
        $activeSheet->setCellValue('F' . $row, $part['merk']);
        $activeSheet->setCellValue('G' . $row, $part['satuan']);
        $activeSheet->setCellValue('H' . $row, $part['harga']);
        $activeSheet->setCellValue('I' . $row, $part['qty']);
        $activeSheet->setCellValue('J' . $row, $total);
        $activeSheet->setCellValue('K' . $row, $part['nama_kategori']);
        $activeSheet->setCellValue('L' . $row, $part['remark_harga']);
        $activeSheet->getStyle("H$row")->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $activeSheet->getStyle("J$row")->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $activeSheet->getStyle("B$row:L$row")->applyFromArray(
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

    $activeSheet->setCellValue("H$row", "Overrage $allowance %");
    $activeSheet->setCellValue("J$row", $tot_alw);
    $activeSheet->getStyle("J$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("H$row:J$row")->getFont()->setSize(9);
    $activeSheet->getStyle("H$row:J$row")->getFont()->setBold(true);
    $row += 1;
    $activeSheet->setCellValue("H$row", "Sub Total");
    $activeSheet->setCellValue("J$row", $sub);
    $activeSheet->getStyle("J$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("H$row:J$row")->getFont()->setSize(9);
    $activeSheet->getStyle("H$row:J$row")->getFont()->setBold(true);

    // query material
    $this->db->select('m.*', false);
    $this->db->where(['deleted <>' => 1, 'tipe_item' => 'item']);
    $this->db->where_in('id_parent', $parentMaterial[$n]);
    $itemMaterial = $this->db->get("v_rawmaterial m")->result_array();

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
        $total = $material['weight'] * $material['price'];
        $sub_total += $total;

        $activeSheet->setCellValue('B' . $row, $noitem);
        $activeSheet->setCellValue('C' . $row, $material['item_code']);
        $activeSheet->setCellValue('D' . $row, $material['part_name']);
        $activeSheet->setCellValue('E' . $row, $material['units']);
        $activeSheet->setCellValue('F' . $row, $material['qty']);
        $activeSheet->setCellValue('G' . $row, $material['materials']);
        $activeSheet->setCellValue('H' . $row, $material['l']);
        $activeSheet->setCellValue('I' . $row, $material['w']);
        $activeSheet->setCellValue('J' . $row, $material['h']);
        $activeSheet->setCellValue('K' . $row, $material['t']);
        $activeSheet->setCellValue('L' . $row, $material['density']);
        $activeSheet->setCellValue('M' . $row, $material['weight']);
        $activeSheet->setCellValue('N' . $row, $material['price']);
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

    // var_dump($itemLabour);
    // die;


    $row += 2;
    $activeSheet->setCellValue("B$row", "Labour");
    $activeSheet->getStyle("B$row")->getFont()->setSize(11);
    $activeSheet->getStyle("B$row")->getFont()->setBold(true);

    $row += 1;
    $activeSheet->fromArray($arrHeaderLabour, NULL, "B$row");
    $activeSheet->getStyle("B$row:H$row")->applyFromArray($headerStyle);

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
            $activeSheet->setCellValue('D' . $row, $labour['nama_kategori']);
            $activeSheet->setCellValue('E' . $row, $labour['aktivitas']);
            $activeSheet->setCellValue('F' . $row, $labour['hour']);
            $activeSheet->setCellValue('G' . $row, $labour['rate']);
            $activeSheet->setCellValue('H' . $row, $total);
            $activeSheet->getStyle("H$row")->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $activeSheet->getStyle("B$row:H$row")->applyFromArray(
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

    $activeSheet->setCellValue("F$row", "Sub Total");
    $activeSheet->setCellValue("H$row", $sub);
    $activeSheet->getStyle("H$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("F$row:H$row")->getFont()->setSize(9);
    $activeSheet->getStyle("F$row:H$row")->getFont()->setBold(true);

    $row++;
    $activeSheet->setCellValue("F$row", "Grand Total");
    $activeSheet->setCellValue("H$row", $grand_total);
    $activeSheet->getStyle("H$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("F$row:H$row")->getFont()->setSize(9);
    $activeSheet->getStyle("F$row:H$row")->getFont()->setBold(true);


    // no section
    $n++;
}
// var_dump($spreadsheet);die;
$spreadsheet->setActiveSheetIndex(0);
// Create new Spreadsheet object


// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
