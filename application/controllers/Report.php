<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Quotation.php');

class Report extends Quotation
{
    function __construct()
    {
        parent::__construct();
        $this->load->add_package_path(APPPATH . 'third_party/fpdf');
        $this->load->library('pdf');
        $this->load->library('reporter');
    }

    function createLeftRow($no, $item = NULL)
    {
        $qty = NULL;
        $part = NULL;
        $eng = NULL;
        $total = NULL;
        if (isset($item)) {
            $qty = $item['qty'];
            $part = $item['total_rm'] + $item['total_elc'] + $item['total_pnu'] + $item['total_hyd'] + $item['total_mch'] + $item['total_sub'] +  $item['total_prod'];
            // $eng = $item['total_eng'];
            // var_dump($item);
            // die;
            /* Using group enginering */
            $eng = $item['group'] == 1 ? $item['total_eng'] * $qty : $item['total_eng'];
            $total = ($qty * $part) + $eng;
        }
        $this->pdf->Ln(4);
        $this->pdf->Cell(8, 4, $no, 1, 0, 'C');
        $this->pdf->Cell(42, 4, isset($item['tipe_name']) ? $item['tipe_name'] : '', 1, 0);
        $this->pdf->Cell(8, 4, isset($qty) ? $qty : '', 1, 0, 'C');
        $this->pdf->Cell(24, 4, isset($part) ? number_format($part) : '', 1, 0, 'R');
        $this->pdf->Cell(24, 4, isset($eng) ? number_format($eng) : '', 1, 0, 'R');
        $this->pdf->Cell(24, 4, isset($total) ? number_format($total) : '', 1, 0, 'R');
    }

    function createLeftRowRp()
    {
        $this->pdf->Ln(0);
        $this->pdf->Cell(121, 4, 'Rp', 0, 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(169, 4, 'Rp', 0, 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(217, 4, 'Rp', 0, 0, 'C');
    }

    function createRightRowRp()
    {
        $this->pdf->Ln(0);
        $this->pdf->Cell(355, 4, 'Rp', 0, 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495, 4, 'Rp', 0, 0, 'C');
    }

    function countGrandTotal($summary)
    {
        $total = 0;
        foreach ($summary as $key => $item) {
            $qty = $item['qty'];
            $part = $item['total_rm'] + $item['total_elc'] + $item['total_pnu'] + $item['total_hyd'] + $item['total_mch'] + $item['total_sub'] +  $item['total_prod'];
            // $eng = $item['total_eng'];
            $eng = $item['group'] == 1 ? $item['total_eng'] * $qty : $item['total_eng'];
            $total += (($qty * $part) + $eng);
        }
        return $total;
    }

    function countGrandTotalWithoutLabour($summary)
    {
        $total = 0;
        foreach ($summary as $key => $item) {
            $qty = $item['qty'];
            $part = $item['total_rm'] + $item['total_elc'] + $item['total_pnu'] + $item['total_hyd'] + $item['total_mch'] + $item['total_sub'];
            $total += ($qty * $part);
        }
        return $total;
    }

    /* Total sudah dikali qty */
    function sumMaterialPerGroup($summary)
    {
        $data = [
            'total_rm' => 0,
            'total_elc' => 0,
            'total_pnu' => 0,
            'total_hyd' => 0,
            'total_mch' => 0,
            'total_sub' => 0,
        ];
        foreach ($summary as $key => $item) {
            $data['total_rm'] += ($item['total_rm'] * $item['qty']);
            $data['total_elc'] += ($item['total_elc'] * $item['qty']);
            $data['total_pnu'] += ($item['total_pnu'] * $item['qty']);
            $data['total_hyd'] += ($item['total_hyd'] * $item['qty']);
            $data['total_mch'] += ($item['total_mch'] * $item['qty']);
            $data['total_sub'] += ($item['total_sub'] * $item['qty']);
        }
        return $data;
    }

    function createMaterialSummary($arr, $sumMaterial, $alw)
    {
        $allowance = $alw / 100;
        if (isset($arr[0])) {
            $totalLeft = $sumMaterial[$arr[0]['key']] + ($allowance * $sumMaterial[$arr[0]['key']]);
            $this->pdf->Cell(5);
            $this->pdf->Cell(40, 4, $arr[0]['value'], 1, 0);
            $this->pdf->Cell(30, 4, number_format($totalLeft), 1, 0, 'R');
        }

        if (isset($arr[1])) {
            $totalRight = $sumMaterial[$arr[1]['key']] + ($allowance * $sumMaterial[$arr[1]['key']]);
            $this->pdf->Cell(10);
            $this->pdf->Cell(30, 4, $arr[1]['value'], 1, 0);
            $this->pdf->Cell(0, 4, number_format($totalRight), 1, 0, 'R');
        }
    }

    public function quotationReport($id, $risk = 'FALSE')
    {
        /* Header */
        $header = $this->getDataHeader($id, false);
        // var_dump($header);die;
        /* Summary */
        $summary = $this->printSummary($id, TRUE);

        /* Total allowance & grand total */
        /* Grand Total */
        $alw = $this->db->get_where('v_header',['id' =>  $id])->row()->allowance;
        $totwithoutlabour = $this->countGrandTotalWithoutLabour($summary);
        $tot = $this->countGrandTotal($summary);

        $this->pdf = new Pdf();
        $this->pdf->Add_Page('L', 'A4', 0);

        /* Tittle Report */
        $this->pdf->SetFont('Arial', 'B', '13');
        $this->pdf->Cell(275, -34, 'COST OF GOODS MANUFACTURED', 0, 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(215);
        $this->pdf->Cell(50, -50, 'INQUIRY No # ' . $header->inquiry_no, 0, 0);
        /* End Title Report*/

        /* General Info */
        /* Row 1*/
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->Ln(-10);
        $this->pdf->Cell(50, 4, 'PROJECT NAME', 1, 0);
        $this->pdf->Cell(80, 4, $header->project_name, 1, 0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30, 4, 'START DATE', 1, 0);
        $this->pdf->Cell(30, 4, date('d F Y', strtotime($header->start_date)), 1, 0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30, 4, 'ESTIMATOR', 1, 0);
        $this->pdf->Cell(0, 4, $header->nama_estimator, 1, 0);

        /* row 2*/
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->Ln(4);
        $this->pdf->Cell(50, 4, 'QUANTITY', 1, 0);
        $this->pdf->Cell(80, 4, $header->qty . ' ' . $header->satuan, 1, 0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30, 4, 'FINISH DATE', 1, 0);
        $this->pdf->Cell(30, 4, date('d F Y', strtotime($header->finish_date)), 1, 0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30, 4, 'RECOMMENDATION', 1, 0);
        $this->pdf->Cell(0, 4, urldecode($risk), 1, 0);

        /* row 3*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50, 4, 'CUSTOMER', 1, 0);
        $this->pdf->Cell(80, 4, $header->customer_name, 1, 0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30, 4, 'DURATION', 1, 0);
        $this->pdf->Cell(30, 4, calcDiffDate($header->start_date, $header->finish_date) . ' MONTH(S)', 1, 0);
        $this->pdf->Cell(20);
        // $this->pdf->Cell(30, 4, 'DIFFICULTY', 1, 0);
        // $this->pdf->Cell(0, 4, $header->difficulty ? $header->difficulty : 'NORMAL', 1, 0);
        /* row 4*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50, 4, 'PIC MARKETING', 1, 0);
        $this->pdf->Cell(80, 4, $header->pic_name, 1, 0);

        /* Line */
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->Line(10, 43, 287, 43);
        $this->pdf->Line(10, 43.5, 287, 43.5);

        /* Detail */
        $this->pdf->Ln(8);
        $this->pdf->Cell(50, 4, 'SUMMARY OF ESTIMATED COST', 0, 0);
        $this->pdf->SetLineWidth(0.1);
        /* Line center */
        $this->pdf->Line(142.5, 46, 142.5, 189);
        $this->pdf->Line(143, 46, 143, 189);
        $this->pdf->Cell(85);
        $this->pdf->Cell(50, 4, 'DETAILS OF ESTIMATED COST', 0, 0);

        /*Row 1*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(8, 8, 'NO', 1, 0, 'C');
        $this->pdf->Cell(42, 8, 'PART NAME', 1, 0, 'C');
        $this->pdf->Cell(8, 4, 'QTY', 'TR', 0, 'C');
        $this->pdf->Cell(24, 4, 'COST PART', 'TR', 0, 'C');
        $this->pdf->Cell(24, 4, 'COST ENG', 'TR', 0, 'C');
        $this->pdf->Cell(24, 4, 'COST TOTAL', 'TR', 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(8, 8, '', 0, 0, 'C');
        $this->pdf->Cell(42, 8, '', 0, 0, 'C');
        $this->pdf->Cell(8, 4, '(A)', 'R', 0, 'C');
        $this->pdf->Cell(24, 4, '(B)', 'R', 0, 'C');
        $this->pdf->Cell(24, 4, '(C)', 'R', 0, 'C');
        $this->pdf->Cell(24, 4, '(A*B)+C', 'R', 0, 'C');

        /* Content*/       

        /*Row 2*/
        /* Row 2 Left*/
        $item = NULL;
        if (isset($summary[0])) {
            $item = $summary[0];            
        }
        $this->createLeftRow(1, $item);

        /* Row 2 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50, 4, 'ORDER PLANNING', 0, 0);

        // Row 2 Rp
        $this->createLeftRowRp();

        /* ./Content */

        /*Row 3*/
        /* Row 3 Left*/
        $item = NULL;
        if (isset($summary[1])) {
            $item = $summary[1];
        }
        $this->createLeftRow(2, $item);

        /* Row 3 Right*/
        $dataSumMaterial = $this->sumMaterialPerGroup($summary);
        $this->createMaterialSummary([['key' => 'total_rm', 'value' => 'RAW MATERIAL'], ['key' => 'total_mch', 'value' => 'STD PART OF MECH']], $dataSumMaterial, $alw);

        // Row 3 Rp
        $this->createLeftRowRp();
        $this->createRightRowRp();

        /*Row 4*/
        /* Row 4 Left*/
        $item = NULL;
        if (isset($summary[2])) {
            $item = $summary[2];
        }
        $this->createLeftRow(3, $item);
        /* Row 4 Right*/
        $this->createMaterialSummary([['key' => 'total_elc', 'value' => 'STD PART OF ELEC'], ['key' => 'total_pnu', 'value' => 'STD PART OF PNEU']], $dataSumMaterial, $alw);

        // Row 4 Rp
        $this->createLeftRowRp();
        $this->createRightRowRp();

        /*Row 5*/
        /* Row 5 Left*/
        $item = NULL;
        if (isset($summary[3])) {
            $item = $summary[3];
        }
        $this->createLeftRow(4, $item);

        /* Row 5 Right*/
        $this->createMaterialSummary([['key' => 'total_hyd', 'value' => 'STD PART OF HYD'], ['key' => 'total_sub', 'value' => 'SUBCONT']], $dataSumMaterial, $alw);
        
        // Row 5 Rp
        $this->createLeftRowRp();
        $this->createRightRowRp();

        /*Row 6*/
        /* Row 6 Left*/
        $item = NULL;
        if (isset($summary[4])) {
            $item = $summary[4];
        }
        $this->createLeftRow(5, $item);

        /* Row 6 Right*/
        $this->createLeftRowRp();

        /*Row 7*/
        /* Row 7 Left*/
        $item = NULL;
        if (isset($summary[5])) {
            $item = $summary[5];
        }
        $this->createLeftRow(6, $item);

        /* Row 7 Right*/        
        $this->pdf->Cell(5);
        $this->pdf->Cell(30, 4, 'INTERNAL LABOUR ENGINEERING', 0, 0);
        // $sqlEng = " SELECT *, SUM(l.hour) as total_hour, SUM(l.`hour` * l.rate) AS total
        //     FROM labour l
        //     WHERE l.id_header = $id AND l.tipe_name = 'ENGINEERING'
        //     GROUP BY l.aktivitas ";
        $sqlEng = " SELECT gl.id,gl.id_parent,gl.id_header,gl.tipe_name,gl.id_labour,gl.aktivitas, SUM(IF(gl.`group` = 1, (gl.`hour` * gl.qty_section), gl.`hour`)) AS `hour`, gl.rate, SUM(IF(gl.`group` = 1, gl.total * gl.qty_section, gl.total)) AS total
        FROM
        (
        SELECT l.*, SUM(l.hour) AS total_hour, SUM(l.`hour` * l.rate) AS total,
        j.qty AS qty_section,j.`group`
        FROM labour l
        JOIN part_jasa j ON l.id_part_jasa = j.id
        WHERE l.id_header = $id AND l.tipe_name = 'ENGINEERING'
        GROUP BY l.id_part_jasa, l.aktivitas
        ) AS gl
        GROUP BY gl.aktivitas";
        $sumLbEng = $this->db->query($sqlEng)->result_array();     
        // echo $this->db->last_query();
        // var_dump($sumLbEng);
        // die;   

        // Row 7 Rp
        $this->createLeftRowRp();

        // var_dump($rLabour);
        // die;
        /*Row 8*/
        /* Row 8 Left*/
        $item = NULL;
        if (isset($summary[6])) {
            $item = $summary[6];
        }
        $this->createLeftRow(7, $item);

        /* Row 8 Right*/

        $this->createRowIL($sumLbEng, 'Mechanic Design');

        // Row 8 Rp
        $this->createLeftRowRp();
        $this->pdf->Ln(0);

        /*Row 9*/
        /* Row 9 Left*/
        $item = NULL;
        if (isset($summary[7])) {
            $item = $summary[7];
        }
        $this->createLeftRow(8, $item);

        /* Row 9 Right */
        $this->createRowIL($sumLbEng, 'Electric Design');

        // Row 9 Rp
        $this->createLeftRowRp();

        /*Row 10*/
        /* Row 10 Left*/
        $item = NULL;
        if (isset($summary[8])) {
            $item = $summary[8];
        }
        $this->createLeftRow(9, $item);

        /* Row 10 Right*/
        $this->createRowIL($sumLbEng, 'Control Project');

        // Row 10 Rp
        $this->createLeftRowRp();
        $this->pdf->Ln(0);

        /*Row 11*/
        /* Row 11 Left*/
        $item = NULL;
        if (isset($summary[9])) {
            $item = $summary[9];
        }
        $this->createLeftRow(10, $item);

        /* Row 11 Right*/
        

        // Row 11 Rp
        $this->createLeftRowRp();

        /*Row 12*/
        /* Row 12 Left*/
        $item = NULL;
        if (isset($summary[10])) {
            $item = $summary[10];
        }
        $this->createLeftRow(11, $item);

        /* Row 12 Right*/
        $dataLabour = $this->getDataLabour($id, NULL, false, false);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30, 4, 'INTERNAL LABOUR PRODUCTION', 0, 0);
        $sumLbProd = array_filter($dataLabour, function($item){
            return $item['tipe_item'] == 'section';
        });
        // var_dump($dataLabour);
        // die;
        $partJasa = [];
        foreach ($sumLbProd as $key => $value) {
            $partJasa[] = $value['id_part_jasa'];
        }
        $id_part = implode("','",$partJasa);
        $sqlProd = " SELECT l.*
            FROM labour l
            WHERE l.id_part_jasa IN('$id_part')
            AND l.tipe_item = 'item'
            GROUP BY l.id_part_jasa
        ";
        $dataQtyPart = $this->db->query($sqlProd)->result_array();
        // var_dump($dataQtyPart);die;
        // $dataLbProd = [];
        // foreach ($sumLbProd as $s => $lb) {
        //     foreach ($dataQtyPart as $k => $item) {
        //         if($lb['id_part_jasa'] == $item['id']){
        //             $lb['qty'] = $item['id'];
        //             print_r($lb);die;
        //         }
        //     }
        //     $dataLbProd[] = $lb;
        // }
        
        $dataParent = array_filter($dataLabour, function($item){
            return $item['tipe_item'] != 'item';
        });
        // print_r($dataParent);die;
        
        $dataSort = $this->reporter->getStructureTree($dataParent, 'findChildLabour');
        $sql = "SELECT id,id_parent,id_header,id_part_jasa,tipe_id,tipe_item,tipe_name,id_labour,aktivitas,sub_aktivitas,rate, SUM(total_hour) AS `total_hour`, SUM(total_all) AS total FROM (";
        $n = 0;
        foreach ($dataSort as $s => $group) {
            $ids = implode("','",$group);
            if($n > 0 )
                $sql .= " UNION ALL";
            $sql .= " SELECT lab.*, j.qty, (totalm * qty) AS total_all FROM (
                SELECT l.*, SUM(l.`hour`) as total_hour,SUM(l.`hour` * l.`rate`) AS totalm
                FROM `labour` l
                WHERE l.`id_header` = '$id'
                AND l.`tipe_item` = 'item'
                AND l.tipe_name = 'PRODUCTION'
                AND l.`id_parent` IN('{$ids}')
                GROUP BY l.aktivitas) 
            AS lab JOIN part_jasa j ON lab.id_part_jasa = j.id ";
            $n++;
        }
        $sql .= ") AS grp GROUP BY aktivitas";
        $dataLbProd = $this->db->query($sql)->result_array();// var_dump($dataSort);
        // echo $this->db->last_query();
        // print_r($dataLbProd);
        // die;

        // Row 12 Rp
        $this->createLeftRowRp();


        /*Row 13*/
        /* Row 13 Left*/
        $item = NULL;
        if (isset($summary[11])) {
            $item = $summary[11];
        }
        $this->createLeftRow(12, $item);

        /* Row 13 Right*/
        $this->createRowIL($dataLbProd, 'Assembly', 1);

        // Row 13 Rp
        $this->createLeftRowRp();

        /*Row 14*/
        /* Row 14 Left*/
        $item = NULL;
        if (isset($summary[12])) {
            $item = $summary[12];
        }
        $this->createLeftRow(13, $item);

        /* Row 14 Right*/
        $this->createRowIL($dataLbProd, 'Fabrication', 1);

        // Row 14 Rp
        $this->createLeftRowRp();

        /*Row 15*/
        /* Row 15 Left*/
        $item = NULL;
        if (isset($summary[13])) {
            $item = $summary[13];
        }
        $this->createLeftRow(14, $item);

        /* Row 15 Right*/
        $this->createRowIL($dataLbProd, 'Installation', 1);

        // Row 15 Rp
        $this->createLeftRowRp();

        /*Row 15*/
        /* Row 15 Left*/
        $item = NULL;
        if (isset($summary[14])) {
            $item = $summary[14];
        }
        $this->createLeftRow(15, $item);

        /* Row 15 Right*/
        $this->createRowIL($dataLbProd, 'Machining', 1);

        // Row 15 Rp
        $this->createLeftRowRp();

        /*Row 16*/
        /* Row 16 Left*/
        $item = NULL;
        if (isset($summary[15])) {
            $item = $summary[15];
        }
        $this->createLeftRow(16, $item);

        /* Row 16 Right*/
        $this->createRowIL($dataLbProd, 'Painting', 1);
        // Row 16 Rp
        $this->createLeftRowRp();

        /*Row 17*/
        /* Row 17 Left*/
        $item = NULL;
        if (isset($summary[16])) {
            $item = $summary[16];
        }
        $this->createLeftRow(17, $item);

        /* Row 17 Right*/

        // Row 17 Rp
        $this->createLeftRowRp();

        /*Row 18*/
        /* Row 18 Left*/
        $item = NULL;
        if (isset($summary[17])) {
            $item = $summary[17];
        }
        $this->createLeftRow(18, $item);

        /* Row 18 Right*/

        // Row 18 Rp
        $this->createLeftRowRp();

        /*Row 19*/
        /* Row 19 Left*/
        $item = NULL;
        if (isset($summary[18])) {
            $item = $summary[18];
        }
        $this->createLeftRow(19, $item);

        /* Row 19 Right*/

        // Row 19 Rp
        $this->createLeftRowRp();

        /*Row 20*/
        /* Row 20 Left*/
        
        $tot_alw = $alw / 100 * $totwithoutlabour;
        $gt =  $tot + $tot_alw;

        
        $item = NULL;
        if (isset($summary[19])) {
            $item = $summary[19];
        }
        // $this->createLeftRow(20, $item);
        $this->pdf->Ln(4);
        $this->pdf->Cell(8, 4, 20, 1, 0, 'C');
        $this->pdf->Cell(42, 4, 'Overrage', 1, 0);
        $this->pdf->Cell(8, 4, '', 1, 0, 'C');
        $this->pdf->Cell(24, 4, number_format($tot_alw), 1, 0, 'R');
        $this->pdf->Cell(24, 4, 0, 1, 0, 'R');
        $this->pdf->Cell(24, 4, number_format($tot_alw), 1, 0, 'R');

        /* Row 20 Right*/
        $this->createLeftRowRp();


        /* Grand Total*/
        
        $this->pdf->SetFont('Arial', 'B', '10');
        $this->pdf->Ln(8);
        $this->pdf->Cell(60, 8, 'GRAND TOTAL', 1, 0, 'C');
        $this->pdf->Cell(70, 8, number_format($gt), 1, 0, 'R');

        /* Row 20 Right*/

        // Row 20 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(130, 8, 'Rp', 0, 0, 'C');

        /* Catatan*/
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->Cell(130, 40, '', 1, 0, 'L');

        $this->pdf->Ln(0);
        $this->pdf->Cell(10, 4, 'NOTE:', 0, 0, 'R');
        // $this->pdf->Ln(0);
        // $this->pdf->Cell(10, 10, '- Perbedaan model/dimensi benda kerja', 0, 0);
        // $this->pdf->Ln(0);
        // $this->pdf->Cell(10, 15, '- Perbedaan kapasitas pallet di dalam mesin.', 0, 0);

        /* Approval */
        $this->pdf->Ln(18);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20, 4, 'APPROVED', 1, 0, 'C');
        $this->pdf->Cell(20, 4, 'APPROVED', 1, 0, 'C');
        $this->pdf->Cell(20, 4, 'APPROVED', 1, 0, 'C');
        $this->pdf->Cell(4);
        $this->pdf->Cell(20, 4, 'CHECKED', 1, 0, 'C');
        $this->pdf->Cell(20, 4, 'CHECKED', 1, 0, 'C');
        $this->pdf->Cell(20, 4, 'CHECKED', 1, 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20, 14, '', 1, 0);
        $this->pdf->Cell(20, 14, '', 1, 0);
        $this->pdf->Cell(20, 14, '', 1, 0);
        $this->pdf->Cell(4);
        $this->pdf->Cell(20, 14, '', 1, 0);
        $this->pdf->Cell(20, 14, '', 1, 0);
        $this->pdf->Cell(20, 14, '', 1, 0);

        $this->pdf->Ln(14);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20, 4, '', 1, 0);
        $this->pdf->Cell(20, 4, '', 1, 0);
        $this->pdf->Cell(20, 4, '', 1, 0);
        $this->pdf->Cell(4);
        $this->pdf->Cell(20, 4, '', 1, 0);
        $this->pdf->Cell(20, 4, '', 1, 0);
        $this->pdf->Cell(20, 4, '', 1, 0);


        // $this->pdf->AliasNbPages();
        // die;

        $this->pdf->Output('quotation_report.pdf', 'I');
    }

    function createRowIL($labour, $aktivitas, $qty = 0, $qty_section = 1)
    {
        foreach ($labour as $key => $item) {
            if ($item['aktivitas'] == $aktivitas) {
                $total = isset($item['qty']) && $qty != 0 ? ($item['qty'] * $item['total']) : $item['total'];
                $this->pdf->Cell(5);
                $this->pdf->Cell(50, 4, strtoupper($aktivitas), 1, 0);
                $this->pdf->Cell(20, 4, /* $item['total_hour'] */ $item['total'] / (int) $item['rate'], 1, 0, 'R');
                $this->pdf->Cell(10);
                $this->pdf->Cell(30, 4, number_format((int) $item['rate']), 1, 0, 'R');
                $this->pdf->Cell(0, 4, number_format($item['total']), 1, 0, 'R');

                $this->pdf->Ln(0);
                $this->pdf->Cell(415, 4, 'MH', 0, 0, 'C');
                $this->pdf->Ln(0);
                $this->pdf->Cell(435, 4, '{x}', 0, 0, 'C');
                $this->pdf->Ln(0);
                $this->pdf->Cell(450, 4, 'Rp', 0, 0, 'C');
                $this->pdf->Ln(0);
                $this->pdf->Cell(495, 4, 'Rp', 0, 0, 'C');
            }
        }
    }
}

/*
* application/controllers/Report.php
*/
