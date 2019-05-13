<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'Quotation.php');

class Report extends Quotation
{
    function __construct()
    {
        parent::__construct();
        $this->load->add_package_path( APPPATH . 'third_party/fpdf');
        $this->load->library('pdf');
    }

	public function quotationReport($id)
	{
        $header = $this->getDataHeader($id, false);
        $data = $this->getDataPart($id, NULL, false);

        // order planning
        $grup_kategori = $this->db->select('j.*, SUM(j.qty * j.harga) as total, TRIM(a.desc) as `desc`',false)
            ->where(['j.id_header' => $id, 'tipe_item' => 'item'])
            ->join('akunbg a','j.kategori = a.accno')
            ->group_by('j.kategori')
            ->get('part_jasa j')
            ->result();

        // $total_rwm = $this->db->selec

        $val_rm = 0; //raw material 10001
        $val_jsp = 0; //jasa spesial proses 20001
        $val_oe = 0; //onsite expenses 40006
        $val_pm = 0; //part mechanic 10003
        $val_pe = 0; //part electric 10002
        $val_pp = 0; //part pneumatic 10004
        foreach ($grup_kategori as $key => $value) {
            if($value->kategori == '10001')
                $val_rm = $value->total;

            if($value->kategori == '10002')
                $val_pe = $value->total;

            if($value->kategori == '10003')
                $val_pm = $value->total;

            if($value->kategori == '10004')
                $val_pp = $value->total;

            if($value->kategori == '20001')
                $val_jsp = $value->total;

            if($value->kategori == '40006')
                $val_oe = $value->total;
        }

        $val_rm = $this->db->select('SUM(r.weight * m.price) as total', false)
            ->join('mrawmaterial m', 'm.item_code = r.item_code', 'left')
            ->where(['id_header' => $id])
            ->get('rawmaterial r')
            // echo $this->db->last_query();
            ->row()->total;

        $section_name_temp = [];
        $section_qty_temp = [];
        $section_total_temp = [];
        $total_raw_material = [];
        foreach($data as $key => $value){
            if($value['tipe_item'] == 'section'){
                array_push($section_name_temp, $value['tipe_name']);
                array_push($section_qty_temp, $value['qty']);
                array_push($section_total_temp, $value['total']);
            }
        }

        $this->pdf = new Pdf();
        $this->pdf->Add_Page('L','A4',0);

        /* Tittle Report */
        $this->pdf->SetFont('Arial','B','13');
        $this->pdf->Cell(275,-34,'COST OF GOODS MANUFACTURE',0,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(215);
        $this->pdf->Cell(50,-50,'INQUIRY No # '.$header->inquiry_no,0,0);
        /* End Title Report*/

        /* General Info */
        /* Row 1*/
        $this->pdf->SetFont('Arial','','7');
        $this->pdf->Ln(-10);
        $this->pdf->Cell(50,4,'PROJECT NAME',1 ,0);
        $this->pdf->Cell(80,4,$header->project_name,1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'START DATE',1 ,0);
        $this->pdf->Cell(30,4,date('d F Y', strtotime($header->start_date)),1 ,0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30,4,'PROJECT TYPE',1 ,0);
        $this->pdf->Cell(0,4,$header->project_type,1 ,0);

        /* row 2*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'QUANTITY',1 ,0);
        $this->pdf->Cell(80,4,$header->qty.' '.$header->satuan,1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'FINISH DATE',1 ,0);
        $this->pdf->Cell(30,4,date('d F Y', strtotime($header->finish_date)),1 ,0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30,4,'DIFFICULTY',1 ,0);
        $this->pdf->Cell(0,4,$header->difficulty,1 ,0);

        /* row 3*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'CUSTOMER',1 ,0);
        $this->pdf->Cell(80,4,$header->customer_name,1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'DURATION',1 ,0);
        $this->pdf->Cell(30,4,calcDiffDate($header->start_date, $header->finish_date). ' MONTH(S)',1 ,0);

        /* row 4*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'PIC MARKETING',1 ,0);
        $this->pdf->Cell(80,4,$header->pic_name,1 ,0);

        /* Line */
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->Line(10,43,287,43);
        $this->pdf->Line(10,43.5,287,43.5);

        /* Detail */
        $this->pdf->Ln(8);
        $this->pdf->Cell(50,4,'SUMMARY OF ESTIMATED COST',0 ,0);
        $this->pdf->SetLineWidth(0.1);
        /* Line center */
        $this->pdf->Line(142.5,46,142.5,189);
        $this->pdf->Line(143,46,143,189);
        $this->pdf->Cell(85);
        $this->pdf->Cell(50,4,'DETAILS OF ESTIMATED COST',0 ,0);

        /*Row 1*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'NO',1 ,0,'C');
        $this->pdf->Cell(50,4,'ITEM',1 ,0,'C');
        $this->pdf->Cell(10,4,'QTY',1 ,0,'C');
        $this->pdf->Cell(30,4,'PRICE',1 ,0,'C');
        $this->pdf->Cell(30,4,'TOTAL PRICE',1 ,0,'C');

        /* Content*/

        /*Row 2*/
        /* Row 2 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'1',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[0]) ? $section_name_temp[0] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[0]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[0]) ? number_format($section_total_temp[0]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[0]) ? number_format($section_total_temp[0]) : '',1 ,0,'R');

        /* Row 2 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'ORDER PLANNING',0 ,0);

        // Row 2 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /* ./Content */

         /*Row 3*/
        /* Row 3 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'2',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[1]) ? $section_name_temp[1] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[1]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[1]) ? number_format($section_total_temp[1]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[1]) ? number_format($section_total_temp[1]) : '',1 ,0,'R');

        /* Row 3 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'RAW MATERIAL',1 ,0);
        $this->pdf->Cell(30,4,number_format($val_rm),1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF MECH',1 ,0);
        $this->pdf->Cell(0,4, number_format($val_pm),1 ,0,'R');

        // Row 3 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(355,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

         /*Row 4*/
        /* Row 4 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'3',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[2]) ? $section_name_temp[2] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[2]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[2]) ? number_format($section_total_temp[2]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[2]) ? number_format($section_total_temp[2]) : '',1 ,0,'R');

        /* Row 4 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'JASA SPECIAL PROCESS',1 ,0);
        $this->pdf->Cell(30,4,number_format($val_jsp),1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF ELEC',1 ,0);
        $this->pdf->Cell(0,4, number_format($val_pe) ,1 ,0,'R');

        // Row 4 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(355,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 5*/
        /* Row 5 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'4',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[3]) ? $section_name_temp[3] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[3]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[3]) ? number_format($section_total_temp[3]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[3]) ? number_format($section_total_temp[3]) : '',1 ,0,'R');

        /* Row 5 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'ONSITE EXPENSES',1 ,0);
        $this->pdf->Cell(30,4,number_format($val_oe),1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF PNEU',1 ,0);
        $this->pdf->Cell(0,4,number_format($val_pp),1 ,0,'R');

        // Row 5 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(355,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 6*/
        /* Row 6 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'5',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[4]) ? $section_name_temp[4] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[4]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[4]) ? number_format($section_total_temp[4]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[4]) ? number_format($section_total_temp[4]) : '',1 ,0,'R');

        /* Row 6 Right*/
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 7*/
        /* Row 7 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'6',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[5]) ? $section_name_temp[5] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[5]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[5]) ? number_format($section_total_temp[5]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[5]) ? number_format($section_total_temp[5]) : '',1 ,0,'R');

        /* Row 7 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'INTERNAL LABOUR',0 ,0);
        // Row 7 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        // get budget internal labour
        $qIL = $this->db->select('TRIM(accno) as accno, TRIM(`desc`) as `desc`, 0 as total', true)
            ->get_where('akunbg', ['accno >' => '3000', 'accno <' => '4000']);
        $qILCount = $qIL->num_rows();
        $qILRes = $qIL->result_array();

        $sqlLabour = "SELECT a.accno, a.`desc`, lg.hour, lg.rate, lg.total
            FROM akunbg a
            LEFT JOIN
            (
                SELECT id_labour, SUM(`hour`) as `hour`, `rate`, SUM(`hour` * rate) AS total
                FROM `labour`
                WHERE `id_header` = '7' AND `tipe_item` = 'item'
                GROUP BY `id_labour`) AS lg
            ON a.accno = lg.id_labour
            where a.accno > 30000 and a.accno < 40000 order by total desc, a.accno asc";
        $qLabour = $this->db->query($sqlLabour);
        $rLabour = $qLabour->result_array();

        // var_dump($rLabour);
        // die;
        /*Row 8*/
        /* Row 8 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'7',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[6]) ? $section_name_temp[6] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[6]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[6]) ? number_format($section_total_temp[6]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[6]) ? number_format($section_total_temp[6]) : '',1 ,0,'R');

        /* Row 8 Right*/
        if(isset($rLabour[0]) && (int) $rLabour[0]['total'] > 0)
            $this->createRowIL($rLabour[0], 5);
        $val_pl = 0;
        $val_me = 0;
        $val_ee = 0;
        $val_fbr = 0; //30003
        $val_mach = 0; //30004
        $val_paint = 0; //30010
        $val_ma = 0;
        $val_ea = 0;
        $val_fc = 0;
        $val_sft = 0;
        // $this->pdf->Cell(5);
        // $this->pdf->Cell(50,4,'PROJECT LEADER',1 ,0);
        // $this->pdf->Cell(20,4,$val_pl,1 ,0,'R');
        // $this->pdf->Cell(10);
        // $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        // $this->pdf->Cell(0,4,number_format($val_pl *125000),1 ,0,'R');


        // Row 8 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        // $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        // $this->pdf->Ln(0);
        // $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        // $this->pdf->Ln(0);
        // $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        // $this->pdf->Ln(0);
        // $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 9*/
        /* Row 9 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'8',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[7]) ? $section_name_temp[7] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[7]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[7]) ? number_format($section_total_temp[7]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[7]) ? number_format($section_total_temp[7]) : '',1 ,0,'R');

        /* Row 9 Right*/
        if(isset($rLabour[1]) && (int) $rLabour[1]['total'] > 0)
            $this->createRowIL($rLabour[1], 5);

        // Row 9 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 10*/
        /* Row 10 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'9',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[8]) ? $section_name_temp[8] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[8]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[8]) ? number_format($section_total_temp[8]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[8]) ? number_format($section_total_temp[8]) : '',1 ,0,'R');

        /* Row 10 Right*/
        if(isset($rLabour[2]) && (int) $rLabour[2]['total'] > 0)
            $this->createRowIL($rLabour[2], 5);

        // Row 10 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);

        /*Row 11*/
        /* Row 11 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'10',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[9]) ? $section_name_temp[9] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[9]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[9]) ? number_format($section_total_temp[9]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[9]) ? number_format($section_total_temp[9]) : '',1 ,0,'R');

        /* Row 11 Right*/
        if(isset($rLabour[3]) && (int) $rLabour[3]['total'] > 0)
            $this->createRowIL($rLabour[3], 5);

        // Row 11 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 12*/
        /* Row 12 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'11',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[10]) ? $section_name_temp[10] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[10]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[10]) ? number_format($section_total_temp[10]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[10]) ? number_format($section_total_temp[10]) : '',1 ,0,'R');

        /* Row 12 Right*/
        if(isset($rLabour[4]) && (int) $rLabour[4]['total'] > 0)
            $this->createRowIL($rLabour[4], 5);

        // Row 12 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');


        /*Row 13*/
        /* Row 13 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'12',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[11]) ? $section_name_temp[11] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[11]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[11]) ? number_format($section_total_temp[11]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[11]) ? number_format($section_total_temp[11]) : '',1 ,0,'R');

        /* Row 13 Right*/
        if(isset($rLabour[5]) && (int) $rLabour[5]['total'] > 0)
            $this->createRowIL($rLabour[5], 5);

        // Row 13 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 14*/
        /* Row 14 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'13',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[12]) ? $section_name_temp[12] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[12]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[12]) ? number_format($section_total_temp[12]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[12]) ? number_format($section_total_temp[12]) : '',1 ,0,'R');

        /* Row 14 Right*/
        if(isset($rLabour[6]) && (int) $rLabour[6]['total'] > 0)
            $this->createRowIL($rLabour[6], 5);

        // Row 14 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 15*/
        /* Row 15 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'14',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[13]) ? $section_name_temp[13] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[13]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[13]) ? number_format($section_total_temp[13]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[13]) ? number_format($section_total_temp[13]) : '',1 ,0,'R');

        /* Row 15 Right*/
        if(isset($rLabour[7]) && (int) $rLabour[7]['total'] > 0)
            $this->createRowIL($rLabour[7], 5);

        // Row 15 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 15*/
        /* Row 15 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'15',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[14]) ? $section_name_temp[14] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[14]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[14]) ? number_format($section_total_temp[14]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[14]) ? number_format($section_total_temp[14]) : '',1 ,0,'R');

        /* Row 15 Right*/
        if(isset($rLabour[8]) && (int) $rLabour[8]['total'] > 0)
            $this->createRowIL($rLabour[8], 5);

        // Row 15 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 16*/
        /* Row 16 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'16',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[15]) ? $section_name_temp[15] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[15]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[15]) ? number_format($section_total_temp[15]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[15]) ? number_format($section_total_temp[15]) : '',1 ,0,'R');

        /* Row 16 Right*/
        if(isset($rLabour[9]) && (int) $rLabour[9]['total'] > 0)
            $this->createRowIL($rLabour[9], 5);
        // Row 16 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 17*/
        /* Row 17 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'17',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[16]) ? $section_name_temp[16] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[16]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[16]) ? number_format($section_total_temp[16]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[16]) ? number_format($section_total_temp[16]) : '',1 ,0,'R');

        /* Row 17 Right*/
        if(isset($rLabour[10]) && (int) $rLabour[10]['total'] > 0)
            $this->createRowIL($rLabour[10], 5);

        // Row 17 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 18*/
        /* Row 18 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'18',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[17]) ? $section_name_temp[17] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[17]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[17]) ? number_format($section_total_temp[17]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[17]) ? number_format($section_total_temp[17]) : '',1 ,0,'R');

        /* Row 18 Right*/
        if(isset($rLabour[11]) && (int) $rLabour[11]['total'] > 0)
            $this->createRowIL($rLabour[11], 5);

        // $this->createRowIL($val_fc);

        // Row 18 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 19*/
        /* Row 19 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'19',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[18]) ? $section_name_temp[18] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[18]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[18]) ? number_format($section_total_temp[18]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[18]) ? number_format($section_total_temp[18]) : '',1 ,0,'R');

        /* Row 19 Right*/
        if(isset($rLabour[12]) && (int) $rLabour[12]['total'] > 0)
            $this->createRowIL($rLabour[12], 5);
        // Row 19 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 20*/
        /* Row 20 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'20',1 ,0);
        $this->pdf->Cell(50,4, isset($section_name_temp[19]) ? $section_name_temp[19] : '',1 ,0);
        $this->pdf->Cell(10,4,isset($section_name_temp[19]) ? 1 : '',1 ,0, 'C');
        $this->pdf->Cell(30,4,isset($section_total_temp[19]) ? number_format($section_total_temp[19]) : '',1 ,0,'R');
        $this->pdf->Cell(30,4,isset($section_total_temp[19]) ? number_format($section_total_temp[19]) : '',1 ,0,'R');

        /* Row 20 Right*/
        if(isset($rLabour[13]) && (int) $rLabour[13]['total'] > 0)
            $this->createRowIL($rLabour[13], 5);
        // Row 20 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');


        /* Grand Total*/
        $this->pdf->SetFont('Arial','B','10');
        $this->pdf->Ln(8);
        $this->pdf->Cell(60,8,'GRAND TOTAL',1 ,0,'C');
        $this->pdf->Cell(70,8,number_format(array_sum($section_total_temp)),1 ,0,'R');

        /* Row 20 Right*/

        // Row 20 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(130,8,'Rp',0 ,0,'C');

        /* Catatan*/
        $this->pdf->Ln(12);
        $this->pdf->SetFont('Arial','','7');
        $this->pdf->Cell(130,40,'',1 ,0,'L');

        $this->pdf->Ln(0);
        $this->pdf->Cell(10,4,'NOTE',0 ,0,'R');
        $this->pdf->Ln(0);
        $this->pdf->Cell(10,10,'- Perbedaan model/dimensi benda kerja',0 ,0);
        $this->pdf->Ln(0);
        $this->pdf->Cell(10,15,'- Perbedaan kapasitas pallet di dalam mesin.',0 ,0);

        /* Approval */
        $this->pdf->Ln(18);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20,4,'APPROVED',1 ,0,'C');
        $this->pdf->Cell(20,4,'APPROVED',1 ,0,'C');
        $this->pdf->Cell(20,4,'APPROVED',1 ,0,'C');
        $this->pdf->Cell(4);
        $this->pdf->Cell(20,4,'CHECKED',1 ,0,'C');
        $this->pdf->Cell(20,4,'CHECKED',1 ,0,'C');
        $this->pdf->Cell(20,4,'CHECKED',1 ,0,'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20,14,'',1 ,0);
        $this->pdf->Cell(20,14,'',1 ,0);
        $this->pdf->Cell(20,14,'',1 ,0);
        $this->pdf->Cell(4);
        $this->pdf->Cell(20,14,'',1 ,0);
        $this->pdf->Cell(20,14,'',1 ,0);
        $this->pdf->Cell(20,14,'',1 ,0);

        $this->pdf->Ln(14);
        $this->pdf->Cell(153);
        $this->pdf->Cell(20,4,'',1 ,0);
        $this->pdf->Cell(20,4,'',1 ,0);
        $this->pdf->Cell(20,4,'',1 ,0);
        $this->pdf->Cell(4);
        $this->pdf->Cell(20,4,'',1 ,0);
        $this->pdf->Cell(20,4,'',1 ,0);
        $this->pdf->Cell(20,4,'',1 ,0);


        // $this->pdf->AliasNbPages();

        $this->pdf->Output( 'quotation_report.pdf' , 'I' );
	}

    function createRowIL($arr, $offset = 0){
        $this->pdf->Cell($offset);
        $this->pdf->Cell(50,4,strtoupper($arr['desc']),1 ,0);
        $this->pdf->Cell(20,4,$arr['hour'],1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,number_format((int) $arr['rate']),1 ,0,'R');
        $this->pdf->Cell(0,4,number_format($arr['total']),1 ,0,'R');

        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');
    }
}

/*
* application/controllers/Report.php
*/

// UNTUK ROW INTERNAL LABOUR DISEDIAKAN SPACE SEBANYAK 13 LINE @abdmun8
