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
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/skins/skin-black.min.css">
    <style type="text/css">
    	.padding20 {
    		padding: 20px;
    	}
    	.nav-tabs .active {
    		font-weight: bolder;
    	}

    </style>
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
                    <form class="form-horizontal" role="form">
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
                                    <!-- <input type="text" class="form-control" name="lot_general" id="lot_general" placeholder="Lot"> -->
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
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="difficulty" class="col-sm-3 control-label">Difficulty</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="difficulty" name="difficulty">
                                        <option value="" selected>Pilih Difficulty</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>                                   
                            <button class="btn btn-success pull-right">Simpan</button>
                            <input type="hidden" id="id_header" name="id_header" action="1" value="" />
                        </div>
                    </form>
                </div>                
            </section>
		</div>

		<div id="std_part_tab" class="tab-pane fade">
			<section class="">
				<div id="demo-toolbar" class="btn-group" role="group" aria-label="...">
				<button id="addBtn" type="button" class="btn btn-default">Add Section</button>
				<!-- <button id="selectBtn" type="button" class="btn btn-default">测试选中</button> -->
				<!-- <button id="expandRowBtn" type="button" class="btn btn-default">展开/折叠【系统管理】</button> -->
				<button id="expandAllBtn" type="button" class="btn btn-default">Expand/Collapse All</button>
				<!-- <button id="showColumnBtn" type="button" class="btn btn-default">Show All Column</button> -->
				<!-- <button id="destroyBtn" type="button" class="btn btn-default">销毁</button> -->
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
		
<!-- 全局js -->
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/cms/bootstrap-treetable/bootstrap-treetable.js"></script>
<script type="text/javascript">
	var allData = [];
/**
    rootIdValue: null,//设置根节点id值----可指定根节点，默认为null,"",0,"0"
    id : "id",               // 选取记录返回的值,用于设置父子关系
    parentId : "parentId",       // 用于设置父子关系
    type: 'get',                   // 请求方式（*）
    url: "./data.json",             // 请求后台的URL（*）
    ajaxParams : {},               // 请求数据的ajax的data属性
    expandColumn : 0,            // 在哪一列上面显示展开按钮
    expandAll : false,                // 是否全部展开
    expandFirst : true, // 是否默认第一级展开--expandAll为false时生效
    toolbar: null,//顶部工具条
    height: 0,
    expanderExpandedClass : 'glyphicon glyphicon-chevron-down',// 展开的按钮的图标
    expanderCollapsedClass : 'glyphicon glyphicon-chevron-right',// 缩起的按钮的图标
**/
    var treeTable = $('#demo').bootstrapTreeTable({
        toolbar: "#demo-toolbar",    //顶部工具条
        expandColumn : 1,            // 在哪一列上面显示展开按钮
        height:500,
        columns: [
        {
            checkbox: true
            // field: 'selectItem',
            // radio: fals,
         },
         {
            title: 'Section & Object',
            field: 'menuName',
            fixed: true,
            width: '200',
            formatter: function(value,row, index) {
                if (row.icon == null || row == "") {
                    return row.menuName;
                } else {
                    return '<i class="' + row.icon + '"></i> <span class="nav-label">' + row.menuName + '</span>';
                }
            }
        },
        {
            field: 'orderNum',
            title: 'Name',
            width: '500',
            align: "left",
            valign: "bottom",
            visible: true
        },
        {
            field: 'url',
            title: 'Item Code',
            width: '150',
            align: "left",
            visible: true
        },
        {
            title: 'Name',
            field: 'menuType',
            width: '100',
            fixed: true,
            align: "center",
            valign: "top",
            formatter: function(value,item, index) {
                if (item.tipeItem == 'section') {
                    return '<span class="label label-success">Section</span>';
                }
                else if (item.tipeItem == 'object') {
                    return '<span class="label label-primary">Object</span>';
                }
                else if (item.tipeItem == 'sub_object') {
                    return '<span class="label label-warning">Sub Object</span>';
                }else{
                	return '';
                }
            }
        },
        {
            field: 'visible',
            title: 'Spec',
            width: '100',
            align: "center",
            visible: true,
            formatter: function(value,row, index) {
                return value;
            }
        },
        {
            field: 'perms',
            title: 'Merk',
            width: '150',
            align: "center",
        },
        {
            title: 'Satuan',
            width: '250',
            align: "center",
            formatter: function(value,row, index) {
                var actions = [];
                actions.push('<a class="btn btn-success btn-xs btnEdit" onclick="console.log('+row+')" href="#'+row.id+'"><i class="fa fa-edit"></i>Edit</a> ');
                if(row.tipeItem !== 'item'){
                    actions.push('<a class="btn btn-info btn-xs " onclick="addData()" href="#'+row.id+'"><i class="fa fa-plus"></i>Sub</a> ');
                }
                actions.push('<a class="btn btn-danger btn-xs " onclick="addData('+row+')" href="#'+row.id+'" ><i class="fa fa-remove"></i>Hapus</a>');
                return actions.join('');
            }
        }],
        onAll: function(data) {
            // console.log("onAll");
            console.log(data);
            return false;
        },
        onLoadSuccess: function(data) {
            console.log("onLoadSuccess");
            return false;
        },
        onLoadError: function(status) {
            console.log("onLoadError");
            return false;
        },
        onClickCell: function(field, value, row, $element) {
            // console.log("onClickCell",field);
            // if()
            // console.log(row.parentId);
            addData(row.id,row.tipeItem);
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
        data:[]
    });

    $("#selectBtn").click(function(){
        var selecteds = $('#demo').bootstrapTreeTable('getSelections');
        $.each(selecteds,function(_i,_item){
            console.log(_item);
        });
        //alert("看console");
    })
    $("#addBtn").click(function(){
        var data = [];
	    data.push({
	            "searchValue": null,
	            "createBy": "admin",
	            "createTime": "2018-03-16 11:33:00",
	            "updateBy": null,
	            "updateTime": null,
	            "remark": null,
	            "params": null,
	            "tipeItem": 'section',
	            "id": 1,
	            "menuName": "data baru 1",
	            "parentName": null,
	            "parentId": 0,
	            "orderNum": "1",
	            "url": "#",
	            "menuType": "F",
	            "visible": 0,
	            "perms": "monitor:online:list",
	            "icon": "#"
	        });
	    allData.push({
	            "searchValue": null,
	            "createBy": "admin",
	            "createTime": "2018-03-16 11:33:00",
	            "updateBy": null,
	            "updateTime": null,
	            "remark": null,
	            "params": null,
	            "tipeItem": 'section',
	            "id": 1,
	            "menuName": "data baru 1",
	            "parentName": null,
	            "parentId": 0,
	            "orderNum": "1",
	            "url": "#",
	            "menuType": "F",
	            "visible": 0,
	            "perms": "monitor:online:list",
	            "icon": "#"
	        });
        $('#demo').bootstrapTreeTable('appendData',data);
    })
    $("#expandRowBtn").click(function(){
        $('#demo').bootstrapTreeTable('toggleRow',1);
    })
    var _expandFlag_all = false;
    $("#expandAllBtn").click(function(){
        if(_expandFlag_all){
            $('#demo').bootstrapTreeTable('expandAll');
        }else{
            $('#demo').bootstrapTreeTable('collapseAll');
        }
        _expandFlag_all = _expandFlag_all?false:true;
    })
    var _showFlag = true;
    $("#showColumnBtn").click(function(){
        if(_showFlag){
            $('#demo').bootstrapTreeTable('hideColumn',"orderNum");
        }else{
            $('#demo').bootstrapTreeTable('showColumn',"orderNum");
        }
        _showFlag = _showFlag?false:true;
    })
    $("#destroyBtn").click(function(){
        $('#demo').bootstrapTreeTable('destroy');
    });

    $(".btnEdit").click(function(){
    	console.log(this);
    })

    var idInc = 2;
    
    function addData(parentId,tipeItem){
    	// console.log(this)
    	// return;
    	if(tipeItem == 'item'){
    		alert('Item tidak bisa mempunyai sub!');
    		return;
    	}
    	var tipeItemChild = '';
    	switch(tipeItem){
    		case 'section':
    			tipeItemChild = 'object';
    		break;
    		case 'object':
    			tipeItemChild = 'sub_object';
    		break;
    		case 'sub_object':
    			tipeItemChild = 'item';
    		break;
    	}


    	// console.log(parentId, id);
    	// return;

    	idInc++;
    	var data = [];
    	data.push({
	            "searchValue": null,
	            "createBy": "admin",
	            "createTime": "2018-03-16 11:33:00",
	            "updateBy": null,
	            "updateTime": null,
	            "remark": null,
	            "params": null,
	            "tipeItem": tipeItemChild,
	            "id": idInc,
	            "menuName": "data baru 1",
	            "parentName": null,
	            "parentId": parentId,
	            "orderNum": "1",
	            "url": "#",
	            "menuType": "F",
	            "visible": 0,
	            "perms": "monitor:online:list",
	            "icon": "#"
        });

        allData.push({
	            "searchValue": null,
	            "createBy": "admin",
	            "createTime": "2018-03-16 11:33:00",
	            "updateBy": null,
	            "updateTime": null,
	            "remark": null,
	            "params": null,
	            "tipeItem": tipeItemChild,
	            "id": idInc,
	            "menuName": "data baru 1",
	            "parentName": null,
	            "parentId": parentId,
	            "orderNum": "1",
	            "url": "#",
	            "menuType": "F",
	            "visible": 0,
	            "perms": "monitor:online:list",
	            "icon": "#"
        });
        $('#demo').bootstrapTreeTable('appendData',data);
        console.log(allData);
    }

    /* General Function */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href").substr(1) // activated tab
        if(target != 'general_info_tab'){
            // var action = $("#")
            // if()
        }
    });
</script>
</body>
</html>
