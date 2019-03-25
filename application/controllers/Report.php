<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->add_package_path( APPPATH . 'third_party/fpdf');
        $this->load->library('pdf');
    }

	public function quotationReport()
	{
        $this->pdf = new Pdf();
        $this->pdf->Add_Page('L','A4',0);

        /* Tittle Report */
        $this->pdf->SetFont('Arial','B','13');
        $this->pdf->Cell(275,-34,'COST OF GOODS MANUFACTURE',0,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(215);
        $this->pdf->Cell(50,-50,'INQUIRY No # 113456',0,0);
        /* End Title Report*/

        /* General Info */
        /* Row 1*/
        $this->pdf->SetFont('Arial','','7');
        $this->pdf->Ln(-10);
        $this->pdf->Cell(50,4,'PROJECT NAME',1 ,0);
        $this->pdf->Cell(80,4,'AUTOCLAVE',1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'START DATE',1 ,0);
        $this->pdf->Cell(30,4,'02 Jan 2019',1 ,0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30,4,'PROJECT TYPE',1 ,0);
        $this->pdf->Cell(0,4,'SPM',1 ,0);

        /* row 2*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'QUANTITY',1 ,0);
        $this->pdf->Cell(80,4,'1 LOT',1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'FINISH DATE',1 ,0);
        $this->pdf->Cell(30,4,'28 Jan 2019',1 ,0);
        $this->pdf->Cell(20);
        $this->pdf->Cell(30,4,'DIFFICULTY',1 ,0);
        $this->pdf->Cell(0,4,'MEDIUM',1 ,0);

        /* row 3*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'CUSTOMER',1 ,0);
        $this->pdf->Cell(80,4,'PT. ASAHIMAS - AUTOMOTIVE DIVISION',1 ,0);
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'DURATION',1 ,0);
        $this->pdf->Cell(30,4,'2 MONTH',1 ,0);

        /* row 4*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(50,4,'PIC MARKETING',1 ,0);
        $this->pdf->Cell(80,4,'BPK. FEDI',1 ,0);

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
        $this->pdf->Cell(50,4,'MODIFY DESIGN',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

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
        $this->pdf->Cell(50,4,'PALLET LOADING CONVEYOR',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'33.577.858',1 ,0,'R');
        $this->pdf->Cell(30,4,'33.577.858',1 ,0,'R');

        /* Row 3 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'RAW MATERIAL',1 ,0);
        $this->pdf->Cell(30,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF MECH',1 ,0);
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

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
        $this->pdf->Cell(50,4,'ENTRY CONVEYOR',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'248.438.797',1 ,0,'R');
        $this->pdf->Cell(30,4,'248.438.797',1 ,0,'R');

        /* Row 4 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'JASA SPECIAL PROCESS',1 ,0);
        $this->pdf->Cell(30,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF ELEC',1 ,0);
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

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
        $this->pdf->Cell(50,4,'CROSSOVER BRIDGE (ENTRY SIDE)',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

        /* Row 5 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(40,4,'ONSITE EXPENSES',1 ,0);
        $this->pdf->Cell(30,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'STD PART OF PNEU',1 ,0);
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

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
        $this->pdf->Cell(50,4,'INTERNAL CONVEYOR',1 ,0);
        $this->pdf->Cell(10,4,'0',1 ,0, 'C');
        $this->pdf->Cell(30,4,'0',1 ,0,'R');
        $this->pdf->Cell(30,4,'0',1 ,0,'R');

        /* Row 6 Right*/
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 7*/
        /* Row 7 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'6',1 ,0);
        $this->pdf->Cell(50,4,'CROSSOVER BRIDGE (EXIT SIDE)',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

        /* Row 7 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(30,4,'INTERNAL LABOUR',0 ,0);
        // Row 7 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 8*/
        /* Row 8 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'7',1 ,0);
        $this->pdf->Cell(50,4,'EXIT CONVEYOR',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

        /* Row 8 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'PROJECT LEADER',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 8 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 9*/
        /* Row 9 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'8',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 9 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'MECHANICAL ENGINEERING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 9 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 10*/
        /* Row 10 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'9',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 10 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'ELECTRICAL ENGGINEERING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 10 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 11*/
        /* Row 11 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'10',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 11 Right*/

        // Row 11 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 12*/
        /* Row 12 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'11',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 12 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'FABRICATION',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'75.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 12 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');


        /*Row 13*/
        /* Row 13 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'12',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 13 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'MACHINING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'75.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 13 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 14*/
        /* Row 14 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'13',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 14 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'PAINTING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'75.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 14 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');
        
        /*Row 15*/
        /* Row 15 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'14',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 15 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'MECHANICAL ASSEMBLING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'75.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 15 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 15*/
        /* Row 15 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'15',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 15 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'ELECTRICAL ASSEMBLING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'75.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 15 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 16*/
        /* Row 16 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'16',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 16 Right*/

        // Row 16 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 17*/
        /* Row 17 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'17',1 ,0);
        $this->pdf->Cell(50,4,'',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 17 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'FARO CHECKING',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 17 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 18*/
        /* Row 18 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'18',1 ,0);
        $this->pdf->Cell(50,4,'DELIVERY',1 ,0);
        $this->pdf->Cell(10,4,'',1 ,0, 'C');
        $this->pdf->Cell(30,4,'',1 ,0,'R');
        $this->pdf->Cell(30,4,'',1 ,0,'R');

        /* Row 18 Right*/
        $this->pdf->Cell(5);
        $this->pdf->Cell(50,4,'SAFETY',1 ,0);
        $this->pdf->Cell(20,4,'330.795.268',1 ,0,'R');
        $this->pdf->Cell(10);
        $this->pdf->Cell(30,4,'125.000',1 ,0,'R');
        $this->pdf->Cell(0,4,'268.170.000',1 ,0,'R');

        // Row 18 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(415,4,'MH',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(435,4,'{x}',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(450,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(495,4,'Rp',0 ,0,'C');

        /*Row 19*/
        /* Row 19 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'19',1 ,0);
        $this->pdf->Cell(50,4,'INSTALLATION',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

        /* Row 19 Right*/

        // Row 19 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');

        /*Row 20*/
        /* Row 20 Left*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(10,4,'20',1 ,0);
        $this->pdf->Cell(50,4,'ONSITE EXPENSES',1 ,0);
        $this->pdf->Cell(10,4,'1',1 ,0, 'C');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');
        $this->pdf->Cell(30,4,'30.000.000',1 ,0,'R');

        /* Row 20 Right*/

        // Row 20 Rp
        $this->pdf->Ln(0);
        $this->pdf->Cell(145,4,'Rp',0 ,0,'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(205,4,'Rp',0 ,0,'C');


        /* Grand Total*/
        $this->pdf->SetFont('Arial','B','10');
        $this->pdf->Ln(8);
        $this->pdf->Cell(60,8,'GRAND TOTAL',1 ,0,'C');
        $this->pdf->Cell(70,8,'30.000.000',1 ,0,'R');

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
}

/*
* application/controllers/Report.php
*/
