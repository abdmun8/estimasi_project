<?php
//default value
$id = null;
if ($param != null) {
    $header = $this->model->getRecord(array(
        'table' => 'header', 'where' => array('id' => $param)
        ));
    if ($header) {
        $id  = $header->id;
    }
}

?>
<!DOCTYPE html>
<html  lang="id_ID">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo $this->config->item('app_name'); ?> | <?php echo (isset($_TITLE))?$_TITLE:'';?></title>
    <meta name="keywords" content="bootstrap-treetable">
    <meta name="description" content="bootstrap-treetable">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/bootstrap-treetable/bootstrap-treetable.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/skins/skin-black.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/swal/sweet-alert.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/animate.css/animate.css">
    <style type="text/css">
    	.padding20 {
    		padding: 20px;
    	}
    	.nav-tabs .active {
    		font-weight: bolder;
    	}

    </style>
    <script>var base_url = '<?php echo base_url();?>';</script>
</head>
<body class="">
	<!-- Contaent -->
	<div style="margin-top: 10px;"></div>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#general_info_tab">General Info</a></li>
		<li><a data-toggle="tab" href="#std_part_tab">Part & Jasa</a></li>
		<li><a data-toggle="tab" href="#labour_tab">Labour</a></li>
	</ul>

	<div class="tab-content">

		<div id="general_info_tab" class="tab-pane fade in active">
			<section class="content padding20">
                <div class="row">
                    <!-- left column -->
                    <form class="form-horizontal" role="form" id="form-general-info">
                        <div class="col-md-6">
                            <!-- Left Form -->                                    
                            <div class="form-group">
                                <label for="inquiry_no" class="col-sm-3 control-label">Inquiry No.</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="inquiry_no" id="inquiry_no" placeholder="Inquiry No">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_name" class="col-sm-3 control-label">Project Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="project_name" id="project_name" placeholder="Project name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qty_general" class="col-sm-3 control-label">QTY</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="qty_general" id="qty_general" placeholder="Qty">
                                </div>
                                <div class="col-sm-7">
                                    <select class="form-control" id="lot_general" name="lot_general">
                                        <option value="" selected>Pilih Lot</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer" class="col-sm-3 control-label">Customer</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="customer" name="customer" style="width: 100%;">                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic_marketing" class="col-sm-3 control-label">PIC marketing</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="pic_marketing" name="pic_marketing" style="width: 100%;">                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Right Form -->
                            <div class="form-group">
                                <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control select_date" name="start_date" id="start_date" placeholder="Start Date" onchange="calcDate()">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="finish_date" class="col-sm-3 control-label">Finish Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control select_date" name="finish_date" id="finish_date" placeholder="Finish Date" onchange="calcDate()">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="duration" class="col-sm-3 control-label">Duration</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="duration" id="duration" placeholder="0" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_type" class="col-sm-3 control-label">Project Type</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="project_type" name="project_type">
                                        <option value="" selected>Project Type</option>
                                        <option value="Raku-Raku">Raku-Raku</option>
                                        <option value="Robot">Robot</option>
                                        <option value="Conveyor">Conveyor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="difficulty" class="col-sm-3 control-label">Difficulty</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="difficulty" name="difficulty">
                                        <option value="" selected>Pilih Difficulty</option>
                                        <option value="Easy">Easy</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>
                            </div>                                   
                            <button type="button" class="btn btn-success pull-right" onclick="saving();"><i class="fa fa-save"></i> Save</button>
                            <button type="button" class="btn btn-default pull-right" onclick="newForm();" style="margin-right: 10px;"><i class="fa fa-plus"></i> New</button>
                            <input type="hidden" id="id_header" name="id_header" value="" />
                            <input type="hidden" id="action" name="action" value="1" />
                        </div>
                    </form>
                </div>                
            </section>
		</div>

		<div id="std_part_tab" class="tab-pane fade">
			<section class="">
				<div id="demo-toolbar" class="btn-group" role="group" aria-label="...">
				<button id="addBtn" type="button" class="btn btn-default" onclick="showModalInput('section')">Add Section</button>
                <button id="expandAllBtn" type="button" class="btn btn-default">Expand/Collapse All</button>
			</div>
			<table id="demo"></table>	
			</section>
			
		</div>

		<div id="labour_tab" class="tab-pane fade">
		  <section class="">
            <div id="labour-toolbar" class="btn-group" role="group" aria-label="...">
                <!-- <button id="addBtn" type="button" class="btn btn-default" onclick="showModalInput('section')">Add Section</button> -->
                <button id="expandAllBtnLabour" type="button" class="btn btn-default">Expand/Collapse All</button>
            </div>
            <table id="labour_table"></table>   
            </section>
		</div>
	</div>
	<!-- /End Content -->

    <!-- Modal Part -->
    <div id="modal-input-item" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Manage <span class="modal-title-input"></span></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-input-item">
                    <div class="box-body">
                        <div class="col-md-6">
                            <!-- <input type="hidden" id="tipe_item-item" name="tipe_item-item" value="section" /> -->    
                            <div class="form-group">
                                <label>Tipe Item</label>
                                <select class="form-control select2 input-sm" name="tipe_item-item" id="tipe_item-item">
                                </select>
                            </div>                     
                            <div class="form-group only_item">
                                <label for="item_code-item">Item Code</label>
                                <input type="text" class="form-control input-sm" id="item_code-item" name="item_code-item" placeholder="Item Code" data-provide="typeahead">
                            </div> 
                            <div class="form-group only_item">
                                <label for="spec-item">Spec</label>
                                <input type="text" class="form-control input-sm" id="spec-item" name="spec-item" placeholder="Spec">
                            </div>
                            <div class="form-group only_item">
                                <label for="satuan-item">Satuan</label>
                                <input type="text" class="form-control input-sm" id="satuan-item" name="satuan-item" placeholder="Satuan">
                            </div>
                            <div class="form-group only_item">
                                <label for="qty-item">Qty</label>
                                <input type="text" class="form-control input-sm" id="qty-item" name="qty-item" placeholder="Qty">
                            </div>
                            
                            <input type="hidden" id="item_code" name="item_code" />
                            <input type="hidden" id="id_parent-item" name="id_parent-item" value="0" />
                            <input type="hidden" id="id_header-item" name="id_header-item" value="0" />
                            <input type="hidden" id="action-item" name="action-item" value="1" />
                            <input type="hidden" id="id-item" name="id-item" value="1" />
                        </div>

                        <div class="col-md-6">
                            <div class="form-group except_item">
                                <label for="tipe_id-item">Id <span class="modal-title-input"></span></label>
                                <input type="text" class="form-control input-sm" id="tipe_id-item" name="tipe_id-item" placeholder="Id">                                
                            </div> 
                            <div class="form-group except_item">
                                <label for="tipe_name-item"><span class="modal-title-input"></span> Name</label>
                                <input type="text" class="form-control input-sm" id="tipe_name-item" name="tipe_name-item" placeholder="Name">
                            </div>
                            <div class="form-group only_item">
                                <label for="item_name-item">Item Name</label>
                                <input type="text" class="form-control input-sm" id="item_name-item" name="item_name-item" placeholder="Item Name">
                            </div> 
                            <div class="form-group only_item">
                                <label for="merk-item">Merk</label>
                                <input type="text" class="form-control input-sm" id="merk-item" name="merk-item" placeholder="Merk">
                            </div> 
                            <div class="form-group only_item">
                                <label for="harga-item">Harga</label>
                                <input type="text" class="form-control input-sm" id="harga-item" name="harga-item" placeholder="Price">
                            </div> 
                            <div class="form-group only_item">
                                <label>Kategori</label>
                                <select class="form-control select2 input-sm" id="kategori-item" name="kategori-item" style="width: 100%;">
                                    <option value="" selected="">Pilih Kategori</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- ./End modal content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveItem()">Save</button>
            </div>
        </div>

      </div>
    </div>
    <!-- ./End Modal Part -->

    <!-- Modal Labour -->
    <div id="modal-input-labour" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Manage Labour</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-input-labour">
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group only-item-labour">
                                <label for="id_labour-labour">Id Labour</label>
                                <input type="text" class="form-control input-sm" id="id_labour-labour" name="id_labour-labour" placeholder="Id">                                
                            </div>
                            <div class="form-group">
                                <label for="aktivitas-labour">Aktivitas</label>
                                <input type="text" class="form-control input-sm" id="aktivitas-labour" name="aktivitas-labour" placeholder="Aktivitas">
                            </div> 
                            <div class="form-group">
                                <label for="sub_aktivitas-labour">Sub Aktivitas</label>
                                <input type="text" class="form-control input-sm" id="sub_aktivitas-labour" name="sub_aktivitas-labour" placeholder="Sub Aktivitas">
                            </div>
                            
                            <input type="hidden" id="tipe_item-labour" name="tipe_item-labour" value="item" />
                            <input type="hidden" id="id_parent-labour" name="id_parent-labour" value="0" />
                            <input type="hidden" id="id_header-labour" name="id_header-labour" value="0" />
                            <input type="hidden" id="action-labour" name="action-labour" value="1" />
                            <input type="hidden" id="id-labour" name="id-labour" value="1" />
                        </div>

                        <div class="col-md-6">
                            
                            <div class="form-group only-item-labour">
                                <label for="hour-labour">Hour</label>
                                <input type="text" class="form-control input-sm" id="hour-labour" name="hour-labour" placeholder="Hour">
                            </div> 
                            <div class="form-group only-item-labour">
                                <label for="rate-labour">Rate</label>
                                <input type="text" class="form-control input-sm" id="rate-labour" name="rate-labour" placeholder="Rate">
                            </div>
                        </div>
                    </div>
                </form>
                <!-- ./End modal content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveLabour()">Save</button>
            </div>
        </div>

      </div>
    </div>
    <!-- ./End Modal Labour -->
		
<!-- 全局js -->
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/bootstrap-treetable.js"></script>
<script src="<?php echo base_url();?>assets/cms/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/swal/sweet-alert.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/function.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url();?>assets/cms/typehead.js/bootstrap3-typeahead.js"></script>
<script src="<?php echo base_url();?>assets/cms/bootstrap-notify/bootstrap-notify.js"></script>
<script type="text/javascript">

    var id_header_tree = '';
    var data_select = [];
    // var $selTipe = $("#tipe_item-item").select2();
    $(document).ready(function () {        

        $('.total_harga').mask("#,##0", {reverse: true});
        $('#harga-item').mask("#,##0", {reverse: true});
        $('#rate-labour').mask("#,##0", {reverse: true});

        $('.select_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2018,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        /* get item code */
        $.get( base_url + "quotation/get_item_code", function(data){
            $("#item_code-item").typeahead({ 
                source: data,
                minLength: 1,
                order: "asc",
                afterSelect: function(o){
                    $("#item_code").val(o.stcd);
                    $("#spec-item").val(o.spek);
                    $("#merk-item").val(o.maker);
                    $("#satuan-item").val(o.uom);
                    $("#item_name-item").val(o.nama);
                    $("#harga-item").val(parseInt(o.harga));
                }
            });
        }, 'json');
        
        $("#tipe_item-item").change(function(){
            if(this.value == 'item'){
                $(".except_item").hide();
                $(".only_item").show();
            }else{
                $(".only_item").hide();
                $(".except_item").show();            
            }
        });


        /* get kategori barang */
        $.get( base_url+'quotation/get_kategori', function( data ) {
            $("#kategori-item").select2({
                placeholder: 'Pilih Kategori',
                data: data
            });
        }, 'json');

        /* get Customer */
        $.get( base_url+'quotation/get_customer', function( data ) {
            $("#customer").select2({
                placeholder: 'Pilih Customer',
                data: data
            });
        }, 'json');

        /* get PIC */
        $.get( base_url+'quotation/get_pic', function( data ) {
            $("#pic_marketing").select2({
                placeholder: 'Pilih PIC',
                data: data
            });
        }, 'json');

        <?php
            if($param != null) {
                echo 'getDataHeader("'. $param .'");';
                echo '$("#action").val(2);';
                echo '$("#id_header").val("'. $param .'");';
                echo '$("#id_header-labour").val("'. $param .'");';
            }
        ?>

    });

    function notify(type, msg, delay = 100){
        $.notify({
            // options
            message: msg ,
            icon: 'fa fa-info-circle',
        },{
            // settings
            type: type,
            delay: delay,
        });
    }


    /* 
        tree table part jasa
    */
    <?php echo ($param != null) ? 'id_header_tree='. $param.';' : ''; ?>
    var treeTable = $('#demo').bootstrapTreeTable({
        toolbar: "#demo-toolbar",    //顶部工具条
        expandColumn : 1,    
        expandAll: true,
        height:480,
        type: 'get',
        parentId: 'id_parent',
        url: base_url + 'quotation/get_data_part/'+ id_header_tree,
        columns: [
            {
                checkbox: true
            },   
            {
                title: 'Opsi',
                width: '160',
                align: "center",
                fixed: true,
                formatter: function(value,row, index) {
                    var actions = [];
                    if(row.tipe_item !== 'item'){
                        actions.push('<a class="btn btn-info btn-xs " title="Tambah Sub" onclick="showModalInput(\''+row.tipe_item+'\','+row.id+','+row.id_parent+','+true+')" href="#"><i class="fa fa-plus"></i></a> ');
                    }
                    actions.push('<a class="btn btn-success btn-xs btnEdit" title="Edit" onclick="showModalInput(\''+row.tipe_item+'\','+row.id+','+row.id_parent+','+false+',\'edit\')"><i class="fa fa-edit"></i></a> ');
                    actions.push('<a class="btn btn-danger btn-xs " title="Hapus" onclick="confirmDelete('+row.id+',\'part_jasa\',\''+row.tipe_item+'\')"><i class="fa fa-remove"></i></a>');
                    return actions.join('');
                }
            },
            {
                title: 'Section & Object',
                field: 'tipe_id',
                width: '200',
                formatter: function(value,row, index) {
                    if (row.tipe_item == 'section') {
                        return '<span class="label label-success">'+value+'</span>';
                    }
                    else if (row.tipe_item == 'object') {
                        return '<span class="label label-primary">'+value+'</span>';
                    }
                    else if (row.tipe_item == 'sub_object') {
                        return '<span class="label label-warning">'+value+'</span>';
                    }else{
                        return '';
                    }
                }
            },
            {
                field: 'tipe_name',
                title: 'Name',
                width: '300',
                align: "left",
                visible: true
            },
            {
                field: 'item_code',
                title: 'Item Code',
                width: '150',
                align: "left",
                visible: true
            },
            {
                field: 'item_name',
                title: 'Item Name',
                width: '300',
                align: "left",
                visible: true
            },
            {
                field: 'spec',
                title: 'Spec',
                width: '100',
                align: "left",
                visible: true,
            },
            {
                field: 'merk',
                title: 'Merk',
                width: '100',
                align: "left",
                visible: true,
            },
            {
                field: 'satuan',
                title: 'Satuan',
                width: '100',
                align: "left",
                visible: true,
            },
            {
                field: 'harga',
                title: 'Harga',
                width: '100',
                align: "right",
                visible: true,
                formatter: function(value,row, index) {
                    if( row.tipe_item != 'item' ){
                        return '';
                    }else{
                        return '<span class="total_harga">'+value+'</span>';
                    }
                }
            },
            {
                field: 'qty',
                title: 'Qty',
                width: '100',
                align: "right",
                formatter: function(value,row, index) {
                    if( row.tipe_item != 'item' ){
                        return '';
                    }else{
                        return value;
                    }
                }
            },
            {
                field: 'total',
                title: 'Total',
                width: '150',
                align: "right",
                formatter: function(value,row, index) {
                    if(row.tipe_item != 'item'){
                        return '<span class="total_harga text-bold">'+value+'</span>';
                    }
                    return '<span class="total_harga">'+value+'</span>';
                }
            },
            {
                field: 'nama_kategori',
                title: 'Kategori',
                width: '150',
                align: "left",
            }
            
        ],
        onAll: function(data) {
            // console.log("onAll");
            return false;
        },
        onLoadSuccess: function(data) {
            // console.log("onLoadSuccess");
            return false;
        },
        onLoadError: function(status) {
            console.log("onLoadError");
            return false;
        },
        onClickCell: function(field, value, row, $element) {
            return false;
        },
        onDblClickCell: function(field, value, row, $element) {
            console.log("onDblClickCell",row);
            return false;
        },
        onClickRow: function(row, $element) {
            // console.log("onClickRow",row);
            return false;
        },
        onDblClickRow: function(row, $element) {
            console.log("onDblClickRow",row);
            return false;
        },
        // data:[]
    });

    $("#selectBtn").click(function(){
        var selecteds = $('#demo').bootstrapTreeTable('getSelections');
        $.each(selecteds,function(_i,_item){
            console.log(_item);
        });
        //alert("看console");
    });

    var _expandFlag_all = false;
    $("#expandAllBtn").click(function(){
        if(_expandFlag_all){
            $('#demo').bootstrapTreeTable('expandAll');
        }else{
            $('#demo').bootstrapTreeTable('collapseAll');
        }
        _expandFlag_all = _expandFlag_all?false:true;
    });

    /* 
        tree table labour
    */

    var treeTable = $('#labour_table').bootstrapTreeTable({
        toolbar: "#labour-toolbar",    //顶部工具条
        expandColumn : 1,   
        expandAll: false,
        height:480,
        type: 'get',
        parentId: 'id_parent',
        url: base_url + 'quotation/get_data_labour/'+ id_header_tree,
        columns: [
            {
                checkbox: true
            },   
            // {
            //     title: 'Opsi',
            //     width: '140',
            //     align: "center",
            //     fixed: true,
            //     formatter: function(value,row, index) {
            //         var actions = [];             
            //         if(row.tipe_item === 'item'){ 
            //             actions.push('<a class="btn btn-success btn-xs btnEdit" title="Edit" onclick="showModalLabour('+row.id+','+row.id_parent+','+false+',\'edit\')"><i class="fa fa-edit"></i></a> ');        
            //         }
            //         return actions.join('');
            //     }
            // },
            {
                title: 'Section & Object',
                field: 'tipe_id',
                width: '150',
                fixed: true,
                formatter: function(value,row, index) {
                    if (row.tipe_item == 'section') {
                        return '<span class="label label-success">'+value+'</span>';
                    }
                    else if (row.tipe_item == 'object') {
                        return '<span class="label label-primary">'+value+'</span>';
                    }
                    else if (row.tipe_item == 'sub_object') {
                        return '<span class="label label-warning">'+value+'</span>';
                    }else{
                        return '';
                    }
                }
            },
            
            
            {
                field: 'tipe_name',
                title: 'Name',
                width: '300',
                align: "left",
                visible: true
            },
            {
                field: 'id_labour',
                title: 'Id Labour',
                width: '150',
                align: "left",
                visible: true
            },
            {
                field: 'aktivitas',
                title: 'Aktivitas',
                width: '150',
                align: "left",
                visible: true
            },
            {
                field: 'sub_aktivitas',
                title: 'Sub Aktivitas',
                width: '150',
                align: "left",
                visible: true
            },
            {
                field: 'hour',
                title: 'Hour',
                width: '100',
                align: "right",
                formatter: function(value,row, index) {

                    if( row.tipe_item != 'item' ){
                        return '';
                    }else{
                        var actions = [];
                        actions.push('<span id="valHour'+row.id+'">'+value+'</span>&nbsp;&nbsp;');
                        actions.push('<button id="btnEdit'+row.id+'"class="btn btn-success btn-xs btnEdit" title="Edit" onclick="editHour('+row.id+')"><span id="iconHour'+row.id+'"><i class="fa fa-edit"></i></span></button> ');

                        actions.push('<button style="display:none;" id="btnSave'+row.id+'"class="btn btn-info btn-xs btnEdit" title="Save" onclick="saveHour('+row.id+','+row.id_parent+')"><i class="fa fa-check"></i></button> ');
                        return actions.join('');   
                    }
                }
            },
            {
                field: 'rate',
                title: 'Rate',
                width: '100',
                align: "right",
                visible: true,
                formatter: function(value,row, index) {
                    if( row.tipe_item != 'item' ){
                        return '';
                    }else{
                        return '<span id="rateValue'+row.id+'" class="total_harga">'+value+'</span>';
                    }
                }
            },            
            {
                field: 'total',
                title: 'Total',
                width: '150',
                align: "right",
                formatter: function(value,row, index) {
                    if(row.tipe_item != 'item'){
                        return '<span id="totalValue'+row.id+'" class="total_harga text-bold">'+value+'</span>';
                    }
                    return '<span id="totalValue'+row.id+'" class="total_harga">'+value+'</span>';
                }
            }
            
        ],
        onAll: function(data) {
            // console.log("onAll");
            return false;
        },
        onLoadSuccess: function(data) {
            // console.log("onLoadSuccess");
            return false;
        },
        onLoadError: function(status) {
            console.log("onLoadError");
            return false;
        },
        onClickCell: function(field, value, row, $element) {
            return false;
        },
        onDblClickCell: function(field, value, row, $element) {
            console.log("onDblClickCell",row);
            return false;
        },
        onClickRow: function(row, $element) {
            // console.log("onClickRow",row);
            return false;
        },
        onDblClickRow: function(row, $element) {
            console.log("onDblClickRow",row);
            return false;
        },
        // data:[]
    });

    var _expandFlag_all_labour = false;
    $("#expandAllBtnLabour").click(function(){
        if(_expandFlag_all_labour){
            $('#labour_table').bootstrapTreeTable('expandAll');
        }else{
            $('#labour_table').bootstrapTreeTable('collapseAll');
        }
        _expandFlag_all_labour = _expandFlag_all_labour?false:true;
    });


    function newForm(){
        window.open( base_url + 'quotation', '_self');
    }

    function getDataHeader(idx) {
        $.ajax({
            url: base_url + 'quotation/get_data_header/'+idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    notify('danger','Terjadi kesalahan!');

                } else {

                    $("#inquiry_no").val(json.data.object.inquiry_no);
                    $("#project_name").val(json.data.object.project_name);
                    $("#customer").val(json.data.object.customer);
                    $('#customer').trigger('change');
                    $("#qty_general").val(json.data.object.qty);
                    $("#lot_general").val(json.data.object.lot);
                    $("#pic_marketing").val(json.data.object.pic_marketing);
                    $('#pic_marketing').trigger('change');
                    $("#start_date").val(convertDateIndo(json.data.object.start_date));
                    $("#finish_date").val(convertDateIndo(json.data.object.finish_date));
                    $("#project_type").val(json.data.object.project_type);
                    $("#difficulty").val(json.data.object.difficulty);
                    $("#duration").val(json.data.object.duration);
                    $("#action-input").val('2');
                    $("#value-input").val(json.data.object.id);
                    $("#id_header-item").val(json.data.object.id);
                    calcDate();
                }
            }
        });
    }

    function confirmDelete(n, table, tipe_item = ''){
        swal({
            title: "Konfirmasi Hapus",
            text: "Apakah anda yakin akan menghapus data ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: " Ya",
            closeOnConfirm: false
        },
        function(){
            loading('loading',true);
            setTimeout(function() {
                $.ajax({
                    url: base_url + 'quotation/del_item',
                    data: 'id='+n+'&table='+table+'&tipe_item='+tipe_item,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json){
                        loading('loading',false);
                        if (json.data.code === 1) {
                            notify('success', 'Hapus data berhasil');

                            if(table == 'part_jasa'){
                                $('#demo').bootstrapTreeTable('refresh');
                            }else{
                                $('#labour_table').bootstrapTreeTable('refresh');
                            }

                        } else if(json.data.code === 2){
                            notify('danger', 'Hapus data gagal!');

                        } else{
                            notify('warning', json.data.message);
                        }
                    },
                    error: function () {
                        loading('loading',false);
                        notify('danger','Tidak dapat hapus data!','error', 'Error');
                    }
                });
            }, 100);
        });
    }

    /* General Function */
    /*
        Check if header is saved
    */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href").substr(1) // activated tab
        if(target != 'general_info_tab' && id_header_tree == ''){
            notify('danger', 'Simpan General Info Terlebih dahulu!');
            setActiveTab("general_info_tab");
        }else{
            /* if header saved load tree table*/
            if(target == 'std_part_tab'){
                $('#demo').bootstrapTreeTable('refresh');
            }else{
                $('#labour_table').bootstrapTreeTable('refresh');
            }
        }
    });

    /* Hitung perbedaan bulan*/
    function calcDate(){
        var start = $("#start_date").val();
        var end = $("#finish_date").val();
        var diff = calcDiffDateToMonth(start,end);
        $("#duration").val(diff + ' MONTH');
    }

    /* Tampilkan modal tambah & edit
    * @title : title modal & label input
    * @id : id item self
    * @id_parent : id parent item
    * @sub : if sub true = add sub
    * @action : add = 1 || edit = 2
    */
    function showModalInput(title, id='', id_parent='', sub=false, action = 'add'){
        $("#tipe_id-item").parent().removeClass('has-error');
        $("#harga-item").parent().removeClass('has-error');
        $("#qty-item").parent().removeClass('has-error');
        $("#tipe_item-item").parent().show();

        var title_self = title;    
        var title_text = title;
        

        if(title == 'item'){
            $(".except_item").hide();
            $(".only_item").show();
        }else{
            $(".only_item").hide();
            $(".except_item").show();            
        }

        if(sub == true){
            switch(title){
                case 'section':
                    title_text = 'Object';
                    title = 'object';
                    data_select = [
                        {id: 'object',text: 'Object'},
                        {id: 'sub_object',text: 'Sub Object'},
                        {id: 'item',text: 'Item'}
                    ];
                break;
                case 'object':
                    title_text = 'Sub Object';
                    title = 'sub_object';
                    data_select = [
                        {id: 'sub_object',text: 'Sub Object'},
                        {id: 'item',text: 'Item'}
                    ];
                break;
                case 'sub_object':
                    title_text = 'Item';
                    title = 'item';
                    data_select = [
                        {id: 'item',text: 'Item'}
                    ];
                    /* if add but title = item*/
                    $(".except_item").hide();
                    $(".only_item").show();
                break;
            }
        }else{
            if(title == 'section'){
                data_select = [
                        {id: 'section',text: 'Section'}
                    ];
            }
        }

        $("#tipe_item-item").children().remove();
        $.each(data_select, function(index, value){
            $("#tipe_item-item").append('<option value="'+value.id+'">'+value.text+'</option>')
        });

        /* Set default Form value*/

        if(action == 'edit'){
            data_select = [
                {id: 'section',text: 'Section'},
                {id: 'object',text: 'Object'},
                {id: 'sub_object',text: 'Sub Object'},
                {id: 'item',text: 'Item'}
            ];

            $("#tipe_item-item").children().remove();
            $.each(data_select, function(index, value){
                $("#tipe_item-item").append('<option value="'+value.id+'">'+value.text+'</option>')
            });

            $.ajax({
                url: base_url + 'quotation/get_data_part/'+id_header_tree+'/'+id,
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    $("#tipe_id-item").val(json.tipe_id);
                    $("#harga-item").val(parseInt(json.harga));
                    $("#item_code-item").val(json.item_code);
                    $("#item_name-item").val(json.item_name);
                    $("#kategori-item").val(json.kategori);
                    $('#kategori-item').trigger('change');
                    $("#merk-item").val(json.merk);
                    $("#qty-item").val(json.qty);
                    $("#satuan-item").val(json.satuan);
                    $("#spec-item").val(json.spec);
                    $("#tipe_name-item").val(json.tipe_name);
                    $("#tipe_item-item").val(json.tipe_item);
                    $("#id-item").val(json.id);
                }
            });

            $("#id_parent-item").val(id_parent);
            $("#action-item").val(2);
            $("#tipe_item-item").val(title);   
            $("#tipe_item-item").parent().hide();         

        }else{


            $("#form-input-item")[0].reset();

            if( title != 'section' ){
                $("#id_parent-item").val(id);
            }else{
                $("#id_parent-item").val(0);
            }

            if(sub == true){
                $("#tipe_item-item").val(title);
            }else{
                $("#tipe_item-item").val(title_self);
            }
            $("#action-item").val(1);
            $("#kategori-item").val("");
            $('#kategori-item').trigger('change');
        }

        title_text = title_text.replace('_',' ');
        
        $(".modal-title-input").text(ucFirst(title_text));
        $('#modal-input-item').modal('show');
    }


    function saving() {
        // CKupdate();
        loading('loading',true);
        var form = $("#form-general-info").serialize();
        var data = form +'&start_date-general='+ formatDate( $("#start_date").val(), false) +'&finish_date-general='+ formatDate( $("#finish_date").val(), false);
        setTimeout(function() {
            $.ajax({
                url: base_url + 'quotation/save_gen_info',
                data: data,
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    loading('loading',false);
                    if (json.data.code === 0) {
                        if (json.data.message == '') {
                            notify('danger', 'Penyimpanan data gagal!');
                        } else {
                            notify('warning', json.data.message);
                        }
                    } else {
                        notify('success', 'Penyimpanan data berhasil');
                        setTimeout(function(){
                            window.open(base_url + 'quotation/' + json.last_id, '_self');
                        },1000);
                    }
                }, error: function () {
                    loading('loading',false);
                    notify('danger', 'Terjadi kesalahan!');
                }
            });
        }, 100);
    }

    // function count

    function saveItem() {
        loading('loading',true);
        var harga = ($("#harga-item").cleanVal() * 1);
        var qty = $("#qty-item").val();
        var tipe_id = $("#tipe_id-item").val();       


        if( $("#tipe_item-item").val() == 'item' ){
            /* validate item */
            if(harga == '' || (harga) == 0){
                $("#harga-item").parent().addClass('has-error');
                alert('Input Harga!');
                return;
            }else{
                $("#harga-item").parent().removeClass('has-error');
            }

            if(qty == '' || (qty) == 0){
                $("#qty-item").parent().addClass('has-error');
                alert('Input Qty!');
                return;
            }else{
                $("#qty-item").parent().removeClass('has-error');
            }

        }else{

            if(tipe_id == ''){
                $("#tipe_id-item").parent().addClass('has-error');
                alert('Input '+ $("#tipe_item-item").val() +'!');
                return;
            }else{
                $("#tipe_id-item").parent().removeClass('has-error');
            }
        }
        

        var data = $("#form-input-item").serialize() +'&harga-item-clean='+harga;
        setTimeout(function() {
            $.ajax({
                url: base_url + 'quotation/save_item',
                data: data,
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    loading('loading',false);
                    if (json.data.code === 0) {
                        if (json.data.message == '') {
                            notify('danger', 'Penyimpanan data gagal!');
                        } else {
                            notify('warning', json.data.message);
                        }
                    } else {
                        notify('success', 'Penyimpanan data berhasil');
                        $('#modal-input-item').modal('hide');
                        $('#demo').bootstrapTreeTable('refresh');
                        
                    }
                }, error: function () {
                    loading('loading',false);
                    notify('danger', 'Terjadi kesalahan!!');
                }
            });
        }, 100);
    }

    function formatDate(date, to_indo = true){
        var m,d,y,formatted;
        if(to_indo == true){
            y = date.substr(0, 4);
            m = date.substr(5, 2);
            d = date.substr(8, 2);
            formatted = d +'-'+ m +'-'+ y;
        }else{
            y = date.substr(6, 4);
            m = date.substr(3, 2);
            d = date.substr(0, 2);
            formatted = y +'-'+ m +'-'+ d;
        }
        return formatted;

    }

    function editHour(id){
        // console.log(val, id)
        var val = $("#valHour"+id+"").text();
        var html = '<input class="" style="width:50px;height:23px;border:1px solid #ccc;padding:0px;margin:0px;" type="number" id="inputHour'+id+'" value="'+val+'" min="0" />';
        $("#valHour"+id+"").html(html);
        $("#inputHour"+id+"").focus();      
        $("#btnEdit"+id+"").css("display","none");      
        $("#btnSave"+id+"").css("display","inline");      
    }

    function saveHour(id, id_parent = 0){
        var hour = $("#inputHour"+id+"").val() * 1;        
        if(hour == ''){
            notify('warning', 'Input Hour!');
            return;
        }

        var total_old = $("#totalValue"+id+"").cleanVal() * 1;
        var rate = $("#rateValue"+id+"").cleanVal() * 1;
        var total = hour * rate;
        var total_parent_old = $("#totalValue"+id_parent+"").cleanVal() * 1;
        var total_parent = (total - total_old)  + total_parent_old;
        
        $("#valHour"+id+"").html(hour);
        $("#totalValue"+id+"").text(total);
        var masked = $("#totalValue"+id+"").masked(total);
        $("#totalValue"+id+"").text(masked);

        $("#totalValue"+id_parent+"").text(total_parent);
        var masked = $("#totalValue"+id_parent+"").masked(total_parent);

        $("#totalValue"+id_parent+"").text(masked);
        $("#btnEdit"+id+"").css("display","inline");      
        $("#btnSave"+id+"").css("display","none");

        $.ajax({
            url: base_url + 'quotation/save_hour/',
            dataType: 'json',
            type: 'POST',
            data: {id:id, hour:hour},
            cache: false,
            success: function(json) {
                console.log(json);
                if(json.code == 1){
                    notify('success', 'Sukses');
                    if(json.tipe_parent != 'section'){

                            // console.log(total_parent);
                        if(json.tipe_parent == 'object'){
                            var total_section_old = $("#totalValue"+json.id_section+"").cleanVal() * 1;
                            var total_section = (total - total_old) + total_section_old;
                            var masked_section = $("#totalValue"+json.id_section+"").masked(total_section);
                            $("#totalValue"+json.id_section+"").text(masked_section);                            

                        }else{
                            var total_object_old = $("#totalValue"+json.id_object+"").cleanVal() * 1;
                            var total_object = (total - total_old) + total_object_old;
                            var masked_object = $("#totalValue"+json.id_object+"").masked(total_object);
                            $("#totalValue"+json.id_object+"").text(masked_object);

                            var total_section_old = $("#totalValue"+json.id_section+"").cleanVal() * 1;
                            var total_section = (total - total_old) + total_section_old;
                            var masked_section = $("#totalValue"+json.id_section+"").masked(total_section);
                            $("#totalValue"+json.id_section+"").text(masked_section);
                                
                        }

                    }
                }else{
                    notify('danger', 'Gagal!');
                    var parent_masked = $("#totalValue"+id_parent+"").masked(total_parent_old);
                    $("#totalValue"+id_parent+"").text(parent_masked);

                    var total_masked = $("#totalValue"+id+"").masked(total_old);
                    $("#totalValue"+id+"").text(total_masked);
                }
            }
        });

    }

</script>
</body>
</html>
