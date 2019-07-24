<?php
// $filename = 'report_part_jasa'.date('d-m-Y');
// header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
// header('Content-Type: application/vnd.ms-excel');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

require_once 'vendor/autoload.php';

function typeCheck($v)
{
    if ($v == 'section') {
        return 'CCFFE8';
    } else if ($v == 'object') {
        return 'C5DEED';
    } else if ($v == 'sub_object') {
        return 'FBE1B6';
    } else {
        return 'FFFFFF';
    }
}

function getKategori($k)
{
    if ($k == '')
        return '';
    $kategori = [
        "10001" => "RAW MATERIAL",
        "10002" => "ELECTRIC STD PART",
        "10003" => "MECHANIC STD PART",
        "10004" => "PNEUMATIC STD PART",
        "10005" => "HYDRAULIC STD PART",
        "20001" => "JASA SPECIAL PROCESS",
        "40003" => "Import"
    ];
    return $kategori[$k];
}

function addSpace($v, $value)
{
    $num_space = 0;
    if ($v == 'item') {
        $num_space = 9;
    } else if ($v == 'object') {
        $num_space = 3;
    } else if ($v == 'sub_object') {
        $num_space = 6;
    } else {
        $num_space = 0;
    }
    $space = '';
    for ($i = 0; $i < $num_space; $i++) {
        $space .= ' ';
    }
    return $space . $value;
}

function findChild($v)
{
    $new = [];
    $harga = $v['tipe_item'] !== 'item' ? '' : $v['harga'];
    $qty = $v['tipe_item'] != 'item' ? '' : $v['qty'];
    $item_code = $v['tipe_item'] != 'item' ? '' : $v['item_code'];
    $new = [
        'tipe_id' =>  $v['tipe_id'],
        'tipe_name' =>  $v['tipe_name'],
        'item_code' =>  $item_code,
        'item_name' =>  $v['item_name'],
        'spec' =>  $v['spec'],
        'merk' =>  $v['merk'],
        'satuan' =>  $v['satuan'],
        'harga' =>  $harga,
        'qty' =>  $qty,
        'total' =>  $v['total'],
        'kategori' =>  getKategori($v['kategori']),
        'tipe_item' =>  $v['tipe_item']
    ];
    return $new;
}

$arrHeader = [
    'tipe_id' => 'Section / Object',
    'tipe_name' => 'Name',
    'item_code' => 'Item Code',
    'item_name' => 'Item Name',
    'spec' => 'Spec',
    'merk' => 'Merk',
    'satuan' => 'Satuan',
    'harga' => 'Harga',
    'qty' => 'Qty',
    'total' => 'Total',
    'kategori' => 'Kategori'
];

$arrId = [];
$arrData = [];
foreach ($data as $key => $s) {
    if ($s['tipe_item'] == 'section') {
        $arrData[] = findChild($s);
        array_push($arrId, $s['id']);
        foreach ($data as $key => $o) {
            if ($o['id_parent'] == $s['id'] && !in_array($o['id'], $arrId)) {
                $arrData[] = findChild($o);
                array_push($arrId, $o['id']);
                foreach ($data as $key => $so) {
                    if ($so['id_parent'] == $o['id'] && !in_array($so['id'], $arrId)) {
                        $arrData[] = findChild($so);
                        array_push($arrId, $so['id']);
                        foreach ($data as $key => $i) {
                            if ($i['id_parent'] == $so['id'] && !in_array($i['id'], $arrId)) {
                                $arrData[] = findChild($i);
                                array_push($arrId, $i['id']);
                            }
                        }
                    }
                }
            }
        }
    }
}


// print_r($arrData);
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle('Part & Jasa');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$activeSheet->setCellValue('A1', 'Detail Part & Jasa');
$activeSheet->getStyle("A1")->getFont()->setSize(16);

//output headers
$activeSheet->fromArray($arrHeader, NULL, 'A3');
$activeSheet->getStyle('A3:K3')->applyFromArray(
    [
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
    ]
);

foreach ($arrData as $key => $domain) {
    $row = (int) $key + 4;
    $color = typeCheck($domain['tipe_item']);

    $activeSheet->setCellValue('A' . $row, addSpace($domain['tipe_item'], $domain['tipe_id']));
    $activeSheet->setCellValue('B' . $row, addSpace($domain['tipe_item'], $domain['tipe_name']));
    $activeSheet->setCellValue('C' . $row, $domain['item_code']);
    $activeSheet->setCellValue('D' . $row, $domain['item_name']);
    $activeSheet->setCellValue('E' . $row, $domain['spec']);
    $activeSheet->setCellValue('F' . $row, $domain['merk']);
    $activeSheet->setCellValue('G' . $row, $domain['satuan']);
    $activeSheet->setCellValue('H' . $row, $domain['harga']);
    $activeSheet->setCellValue('I' . $row, $domain['qty']);
    $activeSheet->setCellValue('J' . $row, $domain['total']);
    $activeSheet->setCellValue('K' . $row, $domain['kategori']);
    $activeSheet->getStyle("H$row")->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("J$row")->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("A$row:K$row")->applyFromArray(
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



// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="part_jasa.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
