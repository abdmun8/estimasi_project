<?php
// $filename = 'report_part_jasa'.date('d-m-Y');
// header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
// header('Content-Type: application/vnd.ms-excel');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

require_once 'vendor/autoload.php';

$arrHeaderLabour = [
    'Section / Object',
    'Name',
    'Id labour',
    'Aktifitas',
    'Hour',
    'Rate',
    'Total',
];

// function getDateWiseScore($data) {
//     $groups = array();
//     foreach ($data as $item) {
//         $key = $item['evaluation_category_id'];
//         if (!array_key_exists($key, $groups)) {
//             $groups[$key] = array(
//                 'id' => $item['evaluation_category_id'],
//                 'score' => $item['score'],
//                 'itemMaxPoint' => $item['itemMaxPoint'],
//             );
//         } else {
//             $groups[$key]['score'] = $groups[$key]['score'] + $item['score'];
//             $groups[$key]['itemMaxPoint'] = $groups[$key]['itemMaxPoint'] + $item['itemMaxPoint'];
//         }
//     }
//     return $groups;
// }
// echo $this->db->last_query();die;

$dataParent = array_filter($labour, function($item){
    return $item['tipe_item'] != 'item';
});

$dataSort = $this->reporter->getStructure($dataParent, 'findChildLabour');

$newData = [];

foreach (array_values($dataSort) as $key => $value) {
    array_push($newData, $value);
    $sql = "SELECT
            `l`.*, sum(l.`hour`) as total_hour, id AS opsi, 
            (
        SELECT
            tipe_item
        FROM
            labour b
        WHERE
            b.id = l.id_parent) AS tipe_parent, sum(l.`hour` * l.rate) AS total
        FROM
            `labour` `l`
        WHERE
            l.id_header ='{$value['id_header']}' AND l.id_parent = '{$value['id']}' AND l.tipe_item = 'item' AND l.deleted = '0' GROUP BY id_labour ORDER BY l.tipe_name";

    $data = $this->db->query($sql)->result_array();
    // echo $this->db->last_query();
    if(count($data) > 0){
        foreach ($data as $key => $item) {
            $item['hour'] = $item['total_hour'];
            $item['tipe_id'] = $item['tipe_item'] == 'item' ? '' : $item['tipe_id'];
            array_push($newData, $item);
        }
    }
}


// print_r($newData);
// echo $this->db->last_query();
// die;
// $dataLabour = $this->reporter->getStructure($newData, 'findChildLabour');

// print_r($arrData);
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle('Labour');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$activeSheet->setCellValue('A1', 'Detail Labour');
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
$activeSheet->fromArray($arrHeaderLabour, NULL, 'A3');
$activeSheet->getStyle('A3:G3')->applyFromArray($headerStyle);

// Looping part & jasa

$row = 0;
foreach ($newData as $key => $part) {
    $row = (int) $key + 4;
    $color = $this->reporter->typeCheck($part['tipe_item']);

    $activeSheet->getCell('A' . $row)->setValueExplicit($part['tipe_item'] == 'item' ? '' : strval($part['tipe_id']), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $activeSheet->setCellValue('B' . $row, $part['tipe_name']);
    $activeSheet->setCellValue('C' . $row, $part['id_labour']);
    $activeSheet->setCellValue('D' . $row, $part['aktivitas']);
    $activeSheet->setCellValue('E' . $row, $part['hour']);
    $activeSheet->setCellValue('F' . $row, $part['rate']);
    $activeSheet->setCellValue('G' . $row, $part['total']);
    $activeSheet->getStyle("G$row")->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("A$row:G$row")->applyFromArray(
        [
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
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
header("Access-Control-Allow-Origin: *");
header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
