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
                                    <input type="text" class="form-control" name="customer" id="customer" placeholder="Customer">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic_marketing" class="col-sm-3 control-label">PIC marketing</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pic_marketing" id="pic_marketing" placeholder="PIC Marketing">
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
                            <button class="btn btn-success pull-right" onclick="saving();">Simpan</button>
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
		<h3>Menu 2</h3>
		<p>Some content in menu 2.</p>
		</div>
	</div>
	<!-- /End Content -->

    <!-- Modal -->
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
                            <div class="form-group except_item">
                                <label for="tipe_id-item">Id <span class="modal-title-input"></span></label>
                                <input type="text" class="form-control input-sm" id="tipe_id-item" name="tipe_id-item" placeholder="Id">                                
                            </div>
                            <div class="form-group only_item">
                                <label for="item_code-item">Item Code</label>
                                <input type="text" class="form-control input-sm" id="item_code-item" name="item_code-item" placeholder="Item Code">
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
                            
                            
                            <input type="hidden" id="tipe_item-item" name="tipe_item-item" value="section" />
                            <input type="hidden" id="id_parent-item" name="id_parent-item" value="0" />
                            <input type="hidden" id="action-item" name="action-item" value="1" />
                            <input type="hidden" id="id-item" name="id-item" value="1" />
                        </div>

                        <div class="col-md-6">
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
                                <label for="kategori-item">Kategori</label>
                                <input type="text" class="form-control input-sm" id="kategori-item" name="kategori-item" placeholder="Kategori">
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
    <!-- ./End Modal -->
		
<!-- 全局js -->
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/bootstrap-treetable.js"></script>
<script src="<?php echo base_url();?>assets/cms/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/swal/sweet-alert.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/function.js"></script>
<script type="text/javascript">

    var id_header_tree = '';
    $(document).ready(function () {
        <?php
        if($param != null) {
            echo 'getDataHeader("'. $param .'");';
        }
        ?>

    });

    <?php echo ($param != null) ? 'id_header_tree='. $param.';' : ''; ?>
    var treeTable = $('#demo').bootstrapTreeTable({
        toolbar: "#demo-toolbar",    //顶部工具条
        expandColumn : 1,            // 在哪一列上面显示展开按钮
        height:400,
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
                    actions.push('<a class="btn btn-danger btn-xs " title="Hapus" onclick="confirmDelete('+row.id+')"><i class="fa fa-remove"></i></a>');
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
                width: '150',
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
                align: "left",
                visible: true,
            },
            {
                field: 'qty',
                title: 'Qty',
                width: '100',
                align: "center",
            },
            {
                field: 'qty',
                title: 'Total',
                width: '150',
                align: "center",
            },
            {
                field: 'kategori',
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
            // $('#demo').bootstrapTreeTable('registerRefreshBtnClickEvent');
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

    function getDataHeader(idx) {
        $.ajax({
            url: base_url + 'quotation/get_data_header/'+idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    genericAlert('Terjadi kesalahan!', 'error','Error');
                } else {

                    $("#inquiry_no").val(json.data.object.inquiry_no);
                    $("#project_name").val(json.data.object.project_name);
                    $("#customer").val(json.data.object.customer);
                    $("#qty_general").val(json.data.object.qty);
                    $("#lot_general").val(json.data.object.lot_general);
                    $("#pic_marketing").val(json.data.object.pic_marketing);
                    $("#start_date").val(convertDateIndo(json.data.object.start_date));
                    $("#finish_date").val(convertDateIndo(json.data.object.finish_date));
                    $("#project_type").val(json.data.object.project_type);
                    $("#difficulty").val(json.data.object.difficulty);
                    $("#duration").val(json.data.object.duration);
                    $("#action-input").val('2');
                    $("#value-input").val(json.data.object.id);
                    calcDate();
                }
            }
        });
    }

    function confirmDelete(n){
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
                    url: base_url + 'manage',
                    data: 'model-input=users&action-input=3&key-input=id&value-input='+n,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json){
                        loading('loading',false);
                        if (json.data.code === 1) {
                            genericAlert('Hapus data berhasil','success','Sukses');
                            refreshTable();
                        } else if(json.data.code === 2){
                            genericAlert('Hapus data gagal!','error','Error');
                        } else{
                            genericAlert(json.data.message,'warning','Perhatian');
                        }
                    },
                    error: function () {
                        loading('loading',false);
                        genericAlert('Tidak dapat hapus data!','error', 'Error');
                    }
                });
            }, 100);
        });
    }

    /* General Function */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href").substr(1) // activated tab
        if(target != 'general_info_tab' && id_header_tree == ''){
            genericAlert('Simpan General Info Terlebih dahulu!', 'error','Error');
            setActiveTab("general_info_tab");
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
                break;
                case 'object':
                    title_text = 'Sub Object';
                    title = 'sub_object';
                break;
                case 'sub_object':
                    title_text = 'Item';
                    title = 'item';
                    /* if add but title = item*/
                    $(".except_item").hide();
                    $(".only_item").show();
                break;
            }
        }

        /* Set default Form value*/

        if(action == 'edit'){
            $.ajax({
                url: base_url + 'quotation/get_data_part/'+id,
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    $("#tipe_id-item").val(json.tipe_id);
                    $("#harga-item").val(json.harga);
                    $("#item_code-item").val(json.item_code);
                    $("#item_name-item").val(json.item_name);
                    $("#kategori-item").val(json.kategori);
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
        }

        title_text = title_text.replace('_',' ');
        
        $(".modal-title-input").text(ucFirst(title_text));
        $('#modal-input-item').modal('show');
    }

    function saving() {
        // CKupdate();
        loading('loading',true);
        setTimeout(function() {
            $.ajax({
                url: base_url + 'quotation/save_gen_info',
                data: $("#form-general-info").serialize(),
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    loading('loading',false);
                    if (json.data.code === 0) {
                        if (json.data.message == '') {
                            genericAlert('Penyimpanan data gagal!', 'error','Error');
                        } else {
                            genericAlert(json.data.message, 'warning','Peringatan');
                        }
                    } else {
                        var page ='_users/';
                        page += json.data.last_id;
                        genericAlert('Penyimpanan data berhasil', 'success','Sukses');
                        loadContent(base_url + 'view/' + page);
                    }
                }, error: function () {
                    loading('loading',false);
                    genericAlert('Terjadi kesalahan!', 'error','Error');
                }
            });
        }, 100);
    }

    function saveItem() {
        loading('loading',true);
        setTimeout(function() {
            $.ajax({
                url: base_url + 'quotation/save_item',
                data: $("#form-input-item").serialize(),
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    loading('loading',false);
                    if (json.data.code === 0) {
                        if (json.data.message == '') {
                            genericAlert('Penyimpanan data gagal!', 'error','Error');
                        } else {
                            genericAlert(json.data.message, 'warning','Peringatan');
                        }
                    } else {
                        // var page ='_users/';
                        // page += json.data.last_id;
                        genericAlert('Penyimpanan data berhasil', 'success','Sukses');
                        $('#modal-input-item').modal('hide');
                        // loadContent(base_url + 'view/' + page);
                    }
                }, error: function () {
                    loading('loading',false);
                    genericAlert('Terjadi kesalahan!', 'error','Error');
                }
            });
        }, 100);
    }

</script>
</body>
</html>
