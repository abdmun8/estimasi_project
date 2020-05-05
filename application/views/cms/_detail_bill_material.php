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

$satuan = $this->db->get_where('tblsatuan')->result();


$id_user = $this->session->userdata['id_karyawan']; 



?>
<!DOCTYPE html>
<html lang="id_ID">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo $this->config->item('app_name'); ?> | Sekawan</title>
    <meta name="keywords" content="bootstrap-treetable">
    <meta name="description" content="bootstrap-treetable">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/bootstrap-treetable/bootstrap-treetable.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/AdminLTE-2.4.9/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/AdminLTE-2.4.9/css/skins/skin-black.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/swal/sweet-alert.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/jquery-autocomplete/jquery.auto-complete.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/animate.css/animate.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/jquery.dataTables.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/FixedColumns-3.2.5/css/fixedColumns.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Select-1.3.0/css/select.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Buttons-1.5.6/css/buttons.bootstrap.min.css">
    <style type="text/css">
        .padding20 {
            padding: 20px;
        }

        th,
        td {
            white-space: nowrap;
        }


        .nav-tabs .active {
            font-weight: bolder;
        }

        blink {
            animation: blinker 0.6s linear infinite;
            color: #1c87c9;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .blink-one {
            animation: blinker-one 1s linear infinite;
        }

        @keyframes blinker-one {
            0% {
                opacity: 0;
            }
        }

        .font14 {
            font-size: .8em;
        }

        .section-bg {
            background-color: #CCFFE8;
        }

        .object-bg {
            background-color: #C5DEED;
        }

        .sub_object-bg {
            background-color: #FBE1B6;
        }

        .modal-xl {
            width: 100%;
            /* max-width: 1200px; */
        }

        #table-data-item tbody td {
            width: 100px;
        }

        .force-select-all {
            user-select: all;
        }

        /* #table-data-item { table-layout: fixed; } */
    </style>
    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
</head>

<body class="">
    <!-- Contaent -->
    <div style="margin-top: 10px;"></div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#general_info_tab">General Info Bill of Material</a></li>
        <li><a data-toggle="tab" href="#std_part_tab">Std Part & Jasa</a></li>
        <li><a data-toggle="tab" href="#material_tab">Material</a></li>
        <!-- <li><a data-toggle="tab" href="#labour_tab">Labour</a></li> -->
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
                                <label for="inquiry_no" class="col-sm-3 control-label">WONO</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="wono" id="wono" placeholder="wono">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_name" class="col-sm-3 control-label">Project Name</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="project_name" id="project_name" placeholder="Project name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer" class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="date" id="date" placeholder="Date">  
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic_marketing" class="col-sm-3 control-label">E.Finish Date</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="efDate" id="efDate" placeholder="E.Finish Date">
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <!-- Right Form -->
                            <div class="form-group">
                                <label for="pic_marketing" class="col-sm-3 control-label">Days Left</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="daysLeft" id="daysLeft" placeholder="Days Left">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic_marketing" class="col-sm-3 control-label">Customer</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="customer" id="customer" placeholder="Customer">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="finish_date" class="col-sm-3 control-label">Marketing</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control"  name="marketing" id="marketing" placeholder="Marketing">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="duration" class="col-sm-3 control-label">PL</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" class="form-control" name="pl" id="pl" placeholder="PL">
                                </div>
                            </div>
                            <!-- <button title="Export Summary Detail" id="print-part-section-detail" style="margin-left: 10px;" onclick="exportExcel('detail_summary','<?= $param ?>')" type="button" class="btn btn-default pull-right"><i class="fa fa-file-excel-o"></i> Detail Summary</button>
                            <button title="Export Summary" id="print-part-section" style="margin-left: 10px;" onclick="exportExcel('summary','<?= $param ?>')" type="button" class="btn btn-default pull-right"><i class="fa fa-file-excel-o"></i> Summary</button> -->
                            <!-- <button title="Save Record" type="button" class="btn btn-default pull-right" onclick="saving();"><i class="fa fa-save"></i> Save</button> -->
                            <!-- <button title="New Record" type="button" class="btn btn-default pull-right" onclick="newForm();" style="margin-right: 10px;"><i class="fa fa-plus"></i> New</button> -->
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
                    <button title="Export Part, jasa & Raw Material" onclick="exportExcel('part','<?= $param ?>')" id="btn-print-labour" type="button" class="btn btn-default"><i class="fa fa-file-excel-o"></i> Export</button>
                    <label style="margin-left:1rem;">Show Deleted Item <input onchange="showDeletedPartChange(event)" style="margin-top:1rem;margin-left:1rem;transform: scale(1.5);" type="checkbox" id="hide-deleted-part" /></label>
                </div>
                <table id="demo"></table>
            </section>

        </div>

        <div id="material_tab" class="tab-pane fade">
            <section class="">
                <div id="material-toolbar" class="btn-group" role="group" aria-label="...">
                    <button id="expandAllBtnMaterial" type="button" class="btn btn-default">Expand/Collapse All</button>
                    <button title="Export Part, jasa & Raw Material" onclick="exportExcel('part','<?= $param ?>')" id="btn-print-labour" type="button" class="btn btn-default"><i class="fa fa-file-excel-o"></i> Export</button>
                    <label style="margin-left:1rem;">Show Deleted Item <input onchange="showDeletedMaterialChange(event)" style="margin-top:1rem;margin-left:1rem;transform: scale(1.5);" type="checkbox" id="hide-deleted-material" /></label>
                </div>
                <table id="material_table"></table>
            </section>
        </div>

        <div id="labour_tab" class="tab-pane fade">
            <section class="">
                <div id="labour-toolbar" class="btn-group" role="group" aria-label="...">
                    <button id="expandAllBtnLabour" type="button" class="btn btn-default">Expand/Collapse All</button>
                    <button title="Export Detail Labour" onclick="exportExcel('labour','<?= $param ?>')" id="btn-print-labour" type="button" class="btn btn-default"><i class="fa fa-file-excel-o"></i> Export</button>
                    <label style="margin-left:1rem;">Show Deleted Item <input onchange="showDeletedLabourChange(event)" style="margin-top:1rem;margin-left:1rem;transform: scale(1.5);" type="checkbox" id="hide-deleted-labour" /></label>
                </div>
                <table id="labour_table"></table>
            </section>
        </div>
    </div>
    <!-- /End Content -->

    <!-- Modal Part -->
    <div class="modal fade" id="modal-input-item" role="dialog" aria-labelledby="modal-add-vendor" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Manage <span class="modal-title-input"></span></h4>
                    <?php 
                        // echo($_SESSION);
                        // die;
                    ?>
                </div>
                <div class="modal-body">
                    <!-- Custom Tabs (Pulled to the right) -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li><a id="tab_input_item_exists-a" href="#tab_input_item_exists" data-toggle="tab">Item Exists</a></li>
                            <li class="active"><a id="#tab_input_item_new-a" href="#tab_input_item_new" data-toggle="tab">Item New</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_input_item_new">
                                <!-- Form Start -->
                                <form role="form" id="form-input-item">
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <!-- <input type="hidden" id="tipe_item-item" name="tipe_item-item" value="section" /> -->
                                            <div class="form-group">
                                                <label>Tipe Item</label>
                                                <select class="form-control select2 input-sm" name="tipe_item-item" id="tipe_item-item">
                                                </select>
                                            </div>
                                            <!-- <div class="form-group only_item">
                                                <label>Item Code</label>
                                                <select class="form-control select2 input-sm" style="width:100%;" name="item_code-item" id="item_code-item">
                                                <option value="" selected="">Pilih Item Code</option>
                                                </select>
                                            </div> -->
                                            <div class="form-group only_item">
                                                <label for="item_code-item">Item Code</label>
                                                <input type="text" class="form-control input-sm" id="item_code-item" name="item_code-item" placeholder="Item Code" data-provide="typeahead" disabled>
                                            </div>
                                            <div class="form-group only_item">
                                                <label for="spec-item">Spec</label>
                                                <input type="text" class="form-control input-sm" id="spec-item" name="spec-item" placeholder="Spec">
                                            </div>
                                            <div class="form-group only_item">
                                                <label for="satuan-item">Satuan</label>
                                                <select class="form-control select2 input-sm" style="width:100%;" name="satuan-item" id="satuan-item">
                                                <option value="" selected="">Pilih Satuan</option>
                                                </select>
                                                <!-- <input type="text" class="form-control input-sm" id="satuan-item" name="satuan-item" placeholder="Satuan"> -->
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
                                            <input type="hidden" id="users" name="users" value=<?= $id_user?> />
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group except_item">
                                                <label for="tipe_id-item">Id <span class="modal-title-input"></span></label>
                                                <input type="text" class="form-control input-sm" id="tipe_id-item" name="tipe_id-item" readonly placeholder="Id">
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
                                                <label for="harga-item">Harga <span style="font-size:12px;font-style:italic;font-weight:normal; color:red;" class="blink-one" id="remark-harga"></span></label>
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
                                <div style="margin-top:1rem;">
                                    <button style="margin-right:1rem;" type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                                    <button style="margin-right:1rem;" type="button" class="btn btn-success pull-right" onclick="saveItem()">Save</button>
                                </div>
                                <!-- Form End -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_input_item_exists">
                                <div id="table-data-item-container">
                                    <table id="table-data-item" class="table table-bordered table-striped table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Action</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Stock</th>
                                                <th>Item Name</th>
                                                <th>Spek</th>
                                                <th>Maker</th>
                                                <th>Unit</th>
                                                <th>Remark</th>
                                                <th>Item Code</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Action</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Stock</th>
                                                <th>Item Name</th>
                                                <th>Spek</th>
                                                <th>Maker</th>
                                                <th>Unit</th>
                                                <th>Remark</th>
                                                <th>Item Code</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END CUSTOM TABS -->
            <!-- ./End modal content -->
        </div>
        <!-- <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" onclick="saveItem()">Save</button>
        </div> -->
    </div>

    </div>
    </div>
    <!-- ./End Modal Part -->


    <!-- Modal Material -->
    <div id="modal-input-material" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Manage Material</span></h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="form-input-material">
                        <div class="box-body">
                            <div class="col-md-6">
                                <input type="hidden" id="tipe_rumus-material" name="tipe_rumus-material" value="" />
                                <input type="hidden" id="id_parent-material" name="id_parent-material" />
                                <input type="hidden" id="id-material" name="id-material" />
                                <input type="hidden" id="id_header-material" name="id_header-material" />
                                <input type="hidden" id="item_code-save-material" name="item_code-save-material" />
                                <input type="hidden" id="action-material" name="action-material" value="1" />
                                <input type="hidden" id="users" name="users" value=<?= $id_user?> />

                                <div class="form-group">
                                    <label>Item Code</label>
                                    <input type="text" class="form-control input-sm" id="item_code-material" name="item_code-material" placeholder="Item Code" data-provide="typeahead">
                                </div>
                                <div class="form-group">
                                    <label>Part name</label>
                                    <input type="text" readonly class="form-control input-sm" id="part_name-material" name="part_name-material" placeholder="Part name">
                                </div>
                                <div class="form-group">
                                    <label>Units</label>
                                    <input type="text" readonly class="form-control input-sm" id="units-material" name="units-material" placeholder="Units">
                                </div>
                                <div class="form-group">
                                    <label>Materials</label>
                                    <input type="text" readonly class="form-control input-sm" id="materials-material" name="materials-material" placeholder="Materials">
                                </div>
                                <div class="form-group">
                                    <label>Density</label>
                                    <input type="text" readonly class="form-control input-sm" id="density-material" name="density-material" placeholder="Density">
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" readonly class="form-control input-sm total_harga" id="price-material" name="price-material" placeholder="Price">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control input-sm" id="qty-material" name="qty-material" placeholder="Qty" onkeyup="perhitunganMaterial()">
                                </div>
                                <div class="form-group rumus2 rumus3" style="display:none;">
                                    <label>L</label>
                                    <input type="text" class="form-control input-sm" id="length-material" name="length-material" placeholder="L" onkeyup="perhitunganMaterial()">
                                </div>
                                <div class="form-group rumus1 rumus3" style="display:none;">
                                    <label>W</label>
                                    <input type="text" class="form-control input-sm" id="width-material" name="width-material" placeholder="W" onkeyup="perhitunganMaterial()">
                                </div>
                                <div class="form-group rumus1 rumus3" style="display:none;">
                                    <label>H</label>
                                    <input type="text" class="form-control input-sm" id="height-material" name="height-material" placeholder="H" onkeyup="perhitunganMaterial()">
                                </div>
                                <div class="form-group rumus1 rumus2 rumus3" style="display:none;">
                                    <label>t / &#8960;</label>
                                    <input type="text" class="form-control input-sm" id="diameter-material" name="diameter-material" placeholder="t / &#8960;" onkeyup="perhitunganMaterial()">
                                </div>
                                <div class="form-group">
                                    <label>Weight</label>
                                    <input type="text" readonly class="form-control input-sm" id="weight-material" name="weight-material" placeholder="Weight" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Total</label>
                                    <input type="text" readonly class="form-control input-sm" id="total-material" name="total-material" placeholder="Total">
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- ./End modal content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="saveMaterial()">Save</button>
                </div>
            </div>

        </div>
    </div>
    <!-- ./End Modal Material -->

    <!-- Modal Detail Labour -->
    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Input Labour <span id="labour-input-title">ENGINEERING</span></h4>
                </div>
                <div class="modal-body">
                    <div class="box box-default">
                        <div class="box-body table-responsive">
                            <table id="table-detail-input-pr" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th style="width:100px">No</th>
                                        <th>Id Labour</th>
                                        <th>Aktivitas</th>
                                        <th>Sub Aktivitas</th>
                                        <th>Hour</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /End modal Detail Labour -->

    <!-- 全局js -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/cms/bootstrap-treetable/libs/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/cms/bootstrap-treetable/bootstrap-treetable.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/select2/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/swal/sweet-alert.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/function.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/typehead.js/bloodhound.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/typehead.js/bootstrap3-typeahead.js"></script>
    <!--  -->
    <script src="<?php echo base_url(); ?>assets/cms/jquery-autocomplete/jquery.auto-complete.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/jquery.dataTables.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/FixedColumns-3.2.5/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Select-1.3.0/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Select-1.3.0/js/select.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/plugins/datatables/extensions/Buttons-1.5.6/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cms/js/lodash.min.js"></script>
    <script type="text/javascript">
        /* Tree Table */
        var treeTable;

        /* Global variable */
        <?php echo ($param != null) ? 'var id_header_tree = ' . $param . ';' : ''; ?>
        var data_select = [];
        var tableDetailLabour;
        var tableDataItemExist;
        var editedCellValueQty = [];

        // set show deleted part
        localStorage.setItem('showDeletedPart', 0)
        localStorage.setItem('showDeletedMaterial', 0)
        localStorage.setItem('showDeletedLabour', 0)

        $(document).ready(function() {
            // generate table
            $("#table-data-item tfoot th").each(function() {
                var title = $(this).text();
                $(this).html('<input style="width:auto;" type="text" placeholder="Search ' + title + '" />');
            });

            /* Event change select */
            $("#tipe_item-item").change(function(e) {
                if (e.target.value == 'item') {
                    setActiveTab('tab_input_item_new')
                    $("#tab_input_item_exists-a").css('pointer-events', 'auto')
                } else {
                    setActiveTab('tab_input_item_new')
                    $("#tab_input_item_exists-a").css('pointer-events', 'none')
                }
            })

            $('.total_harga').mask("#,##0", {
                reverse: true
            });
            $('#harga-item').mask("#,##0", {
                reverse: true
            });
            $('#rate-labour').mask("#,##0", {
                reverse: true
            });
            $('#total-material').mask("#,##0.00", {
                reverse: true
            });
            $('#weight-material').mask("#,##0.00", {
                reverse: true
            });
            // $('#total-material').mask("#,##0", {reverse: true});

            $('.select_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2018,
                maxYear: parseInt(moment().format('YYYY'), 10),
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
            $.get(base_url + "bmaterial/get_satuan", function(data){
                $('#satuan-item').select2({
                    placeholder: 'Pilih Satuan',
                    data: data
                });
            }, 'json');
            // $.get(base_url + "bmaterial/get_item_code/1", function(data) {
            //     $('#item_code-item').select2({
            //         placeholder: "Pilih Item Code",
            //         allowClear: true,
            //         multiple: false,
            //         data: data
            //     }).on('select2:select', function(e) {
            //         var o = e.params.data;
            //         let code = o.stcd.substr(0, 3);
            //         let katval = switchCodeToCategory(code)
            //         $("#item_code").val(o.stcd);
            //         $("#spec-item").val(o.spek);
            //         $("#merk-item").val(o.maker);
            //         $("#satuan-item").val(o.uom);
            //         $("#satuan-item").trigger('change')
            //         $("#item_name-item").val(o.nama);
            //         $("#harga-item").val(parseInt(o.harga));
            //         $("#kategori-item").val(katval);
            //         $("#kategori-item").trigger('change')
            //         $("#remark-harga").text(o.remark);
            //     });
            // }, 'json')

            $("#tipe_item-item").change(function() {
                $("#item_code").val('');
                if (this.value == 'item') {
                    $(".except_item").hide();
                    $(".only_item").show();
                } else {
                    $(".only_item").hide();
                    $(".except_item").show();
                }
            });

            /* get kategori barang */
            $.get(base_url + 'bmaterial/get_kategori', function(data) {
                $("#kategori-item").select2({
                    placeholder: 'Pilih Kategori',
                    data: data
                });
            }, 'json');

            /* get Customer */
            // $.get(base_url + 'quotation/get_customer', function(data) {
            //     $("#customer").select2({
            //         placeholder: 'Pilih Customer',
            //         data: data
            //     });
            // }, 'json');

            /* get PIC */
            $.get(base_url + 'bmaterial/get_pic', function(data) {
                $("#pic_marketing").select2({
                    placeholder: 'Pilih PIC',
                    data: data
                });
            }, 'json');

            $.get(base_url + 'bmaterial/getEstimator', function(data) {
                $("#pic_estimator").select2({
                    placeholder: 'Pilih Estimator',
                    data: data
                });
            }, 'json');


            /* get Satuan */
            // $.get(base_url + 'quotation/get_kategori', function(data) {
            //     $("#kategori-item").select2({
            //         placeholder: 'Pilih Satuan',
            //         data: data
            //     });
            // }, 'json');

            <?php
            if ($param != null) {
                echo 'getDataHeader("' . $param . '");';
                echo '$("#action").val(2);';
                echo '$("#id_header").val("' . $param . '");';
                echo '$("#id_header-labour").val("' . $param . '");';
            }
            ?>

            $.get(base_url + "bmaterial/get_material_code", function(data) {
                $("#item_code-material").typeahead({
                    source: data,
                    minLength: 1,
                    order: "asc",
                    afterSelect: function(o) {
                        $("#part_name-material").val(o.part_name);
                        $("#units-material").val(o.units);
                        $("#materials-material").val(o.materials);
                        $("#item_code-save-material").val(o.item_code);
                        $("#density-material").val(o.density);
                        $("#price-material").val(o.price * 1);
                        var masked_price = $("#price-material").masked();
                        $("#price-material").val(masked_price);
                        $("#tipe_rumus-material").val(o.type);
                        $("#id_header-material").val(id_header_tree);
                        if (o.type == 'RUMUS-1') {
                            $(".rumus2, .rumus3").hide();
                            $(".rumus1").show();
                        } else if (o.type == 'RUMUS-2') {
                            $(".rumus1, .rumus3").hide();
                            $(".rumus2").show();
                        } else {
                            $(".rumus1, .rumus2").hide();
                            $(".rumus3").show();
                        }
                        $("#width-material").val('');
                        $("#length-material").val('');
                        $("#height-material").val('');
                        $("#diameter-material").val('');
                        $("#weight-material").val('');
                        $("#qty-material").val('');
                        $("#total-material").val('');
                    }
                });
            }, 'json');

            // get detail labour
            <?php echo ($param != null) ? 'getDetailLabour();' : ''; ?>


            // set local storage for item
            localStorage.setItem('dataModalLabour', JSON.stringify({
                'id_header': '',
                'type_input': ''
            }));

            // adjust table
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                adjustDatatable()
            });

            // generate datatable item
            getDatatableItem();

            /* Generate Bootstrap treetable */
            generatePartTable()
            generateMaterialTable()
            generateLabourTable()
        });

        // Adjust Datatable Column
        function adjustDatatable() {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        }

        /* Generate datatable */
        function getDatatableItem() {
            if ($.fn.dataTable.isDataTable('#table-data-item')) {
                tableDataItemExist = $('#table-data-item').DataTable();
            } else {
                tableDataItemExist = $('#table-data-item').DataTable({
                    "ajax": base_url + 'bmaterial/get_item_code/0',
                    "columns": [{
                            'data': 'no',
                            render: function(data, type, row, meta) {
                                if (type === "sort") {
                                    if (data) {
                                        return data;
                                    } else {
                                        return false;
                                    }
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            'data': 'remark',
                            render: function(data, type, row) {
                                return `<button class="btn btn-success btn-xs" onclick="editQtyItemExists(this)"><i class="fa fa-edit"></i> Edit</button>`;
                            }
                        },
                        {
                            'data': 'qty'
                        },
                        {
                            'data': 'harga',
                            'render': function(data) {
                                let number = parseFloat(data)
                                return new Intl.NumberFormat().format(number)
                            }
                        },
                        {
                            'data': 'stock',
                        //     // 'render': function(data, type, row) {
                        //     //     // let stock1 = row.harga;
                        //     //     // return stock1
                        //     //     let number1x = parseFloat(data)
                        //     //     return number1x
                        //     // }

                        },
                        {
                            'data': 'item_name'
                        },
                        {
                            'data': 'spek'
                        },
                        {
                            'data': 'maker'
                        },
                        {
                            'data': 'uom'
                        },
                        {
                            'data': 'remark'
                        },
                        {
                            'data': 'stcd'
                        },

                    ],
                    "ordering": true,
                    "order": [
                        [0, "asc"]
                    ],
                    autoWidth: false,
                    deferRender: true,
                    bAutoWidth: false,
                    responsive: true,
                    scrollX: true,
                    columnDefs: [{
                        type: "text",
                        className: 'select-checkbox',
                        targets: 0,
                        width: "10px"
                    }],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    // columnDefs: [{
                    //     orderable: true,
                    //     className: 'select-checkbox',
                    //     targets: 0,
                    // }],
                    // select: {
                    //     style: 'multi',
                    // },
                    dom: 'Bfrtip',
                    buttons: [{
                            text: '<i class="fa fa-check-square-o"></i> Select all',
                            action: function() {
                                tableDataItemExist.rows({
                                    search: 'applied'
                                }).select();
                                tableDataItemExist.page.len(-1).draw()
                                tableDataItemExist.page.len(10).draw()
                            }
                        },
                        {
                            text: '<i class="fa fa-square-o"></i> Unselect All',
                            action: function() {
                                tableDataItemExist.rows().deselect();
                            }
                        },
                        {
                            text: '<i class="fa fa-plus"></i> Add Selected Item',
                            action: function() {
                                addItemTotable()
                            }
                        }
                    ],
                    "fnDrawCallback": function(oSettings) {
                        if (tableDataItemExist) {
                            // tableDataItemExist.rows().every(function(rowIdx, tableLoop, rowLoop) {
                            //     if (this.data().exist == 1) {
                            //         this.select();
                            //     }
                            // });
                            adjustDatatable()
                        }
                        // utilsDataTable();
                        // alert(1)
                    }
                });

                tableDataItemExist.columns().every(function() {
                    var that = this;

                    $("input", this.footer()).on("keyup change", function() {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });

                tableDataItemExist.on('select', function(e, dt, type, indexes) {
                    if (type === 'row') {
                        var rows = tableDataItemExist.rows(indexes);
                        rows.every(function(rowIdx, tableLoop, rowLoop) {
                            var data = this.data();
                            data.no = true;
                            this.data(data);
                        });
                    }
                });
                tableDataItemExist.on('deselect', function(e, dt, type, indexes) {
                    if (type === 'row') {
                        var rows = tableDataItemExist.rows(indexes);
                        rows.every(function(rowIdx, tableLoop, rowLoop) {
                            var data = this.data();
                            data.no = false;
                            this.data(data);
                        });
                    }
                });
            }
        }

        // Edit item Qty Exists
        function editQtyItemExists(o) {
            let old = $(o).parent().siblings()[1].innerHTML
            let oldHarga = $(o).parent().siblings()[2].innerHTML.replace(/,/g, '')
            let btn = `<button class="btn btn-primary btn-xs" onclick="saveQtyItemExists(this)"><i class="fa fa-save"></i> Save</button>`;
            option = `<input class="force-select-all" style="width:auto;color:#000000;" type="number" min="0" value="${old}" />`;
            optionHarga = `<input class="force-select-all" style="width:auto;color:#000000;" type="text" min="0" value="${oldHarga}" />`;
            $(o).parent().siblings()[1].innerHTML = option
            $(o).parent().siblings()[2].innerHTML = optionHarga
            $(o).parent().html(btn)
        }

        // Save item Qty
        function saveQtyItemExists(o) {
            let input = $(o).parent().siblings()[1]
            let inputHarga = $(o).parent().siblings()[2]
            let item = $(o).parent().siblings()[9].innerHTML
            let newValue = $(input).children()[0].value
            let newHarga = $(inputHarga).children()[0].value
            let btn = `<button class="btn btn-success btn-xs" onclick="editQtyItemExists(this)"><i class="fa fa-edit"></i> Edit</button>`;
            $(o).parent().siblings()[1].innerHTML = newValue
            $(o).parent().siblings()[2].innerHTML = new Intl.NumberFormat().format(newHarga)
            $(o).parent().html(btn)

            if (editedCellValueQty.length == 0) {
                editedCellValueQty.push({
                    item_code: item,
                    qty: newValue,
                    harga: newHarga
                })
            } else {

                for (let index = 0; index < editedCellValueQty.length; index++) {
                    const element = editedCellValueQty[index];
                    if (element.item_code == item) {
                        editedCellValueQty.pop(index)
                    }
                    editedCellValueQty.push({
                        item_code: item,
                        qty: newValue,
                        harga: newHarga
                    })
                }
            }
        }

        // refresh table add item
        function refreshTableDataItem() {
            tableDataItemExist.ajax.url(base_url + 'bmaterial/get_item_code/0').load();
        }

        // add item to table
        function addItemTotable() {
            let count = tableDataItemExist.rows('.selected').data().count()
            let data = tableDataItemExist.rows('.selected').data()
            let selected = [];
            // console.log(count);
            // console.log(data[0].stock);
            // delete data[0].stock;
            if (editedCellValueQty.length == 0) {
                alert('Input Qty')
                return;
            }

            if (count > 0) {
                for (let index = 0; index < count; index++) {
                    let users = $("#users").val();
                    const element = data[index];
                    element.users = users;
                    delete element.stock;
                    for (let idx = 0; idx < editedCellValueQty.length; idx++) {
                        const elm = editedCellValueQty[idx];
                        console.log(element);
                        console.log(elm);

                            if (element.stcd == elm.item_code) {
                                if (elm.qty == 0 || elm.harga.replace(/,/g, '') == 0) {
                                    alert(`Qty dan Harga item ${element.stcd} - ${element.item_name} tidak boleh 0!`)
                                    return;
                                }
                                element.category = switchCodeToCategory(elm.item_code.substr(0, 3))
                                element.qty = elm.qty
                                element.harga = elm.harga.replace(/,/g, '');
                                // element.stock = element.stock;
                                selected.push(element)
                            }
                        console.log('cccc');
                        // console.log(selected);
                        //return;
                        // delete selected.stock;
                        // console.log('ccc');
                        // console.log(selected);

                    }
                }
                // console.log(selected);
                let id_parent_item = $("#id_parent-item").val()
                let id_header_item = $("#id_header-item").val()
                $.post(base_url + 'bmaterial/saveMultiItem', {
                    id_parent: id_parent_item,
                    id_header: id_header_item,
                    data: JSON.stringify(selected)
                  
                }, res => {
                    if (res.msg_exists != '') {
                        alert(res.message + res.msg_exists)
                    } else {
                        alert(res.message)
                    }

                    if (res.success == true) {
                        $('#demo').bootstrapTreeTable('refresh');
                        refreshTableDataItem()
                    }

                }, 'json')
            } else {
                alert('Pilih data terlebih dahulu!')
            }
        }

        // Show deleted part
        function showDeletedPartChange(e) {
            let show = e.target.checked === true ? 1 : 0;
            localStorage.setItem('showDeletedPart', show)
            $('#demo').bootstrapTreeTable('destroy')
            generatePartTable(show);

        }

        // Show deleted Material 
        function showDeletedMaterialChange(e) {
            let show = e.target.checked === true ? 1 : 0;
            localStorage.setItem('showDeletedMaterial', show)
            $('#material_table').bootstrapTreeTable('destroy')
            generateMaterialTable(show);
        }
        // Show deleted Labour
        function showDeletedLabourChange(e) {
            let show = e.target.checked === true ? 1 : 0;
            localStorage.setItem('showDeletedLabour', show)
            $('#labour_table').bootstrapTreeTable('destroy')
            generateLabourTable(show);
        }

        function notify(type, msg, delay = 100) {
            $.notify({
                // options
                message: msg,
                icon: 'fa fa-info-circle',
            }, {
                // settings
                type: type,
                delay: delay,
            });
        }


        /*
            tree table part jasa
        */
        function generatePartTable(show_deleted = 0) {
            treeTable = $('#demo').bootstrapTreeTable({
                toolbar: "#demo-toolbar", //顶部工具条
                expandColumn: 1,
                expandAll: true,
                height: 480,
                type: 'get',
                parentId: 'id_parent',
                url: base_url + 'bmaterial/get_data_part/' + id_header_tree + '?show-deleted=' + show_deleted,
                columns: [{
                        checkbox: true
                    },
                    {
                        title: 'Opsi',
                        width: '160',
                        align: "center",
                        fixed: true,
                        formatter: function(value, row, index) {
                            var actions = [];
                            if (row.deleted == 1)
                                return '';
                            if (row.tipe_item !== 'item') {
                                actions.push('<a class="btn btn-info btn-xs " title="Tambah Sub" onclick="showModalInput(\'' + row.tipe_item + '\',' + row.id + ',' + row.id_parent + ',' + true + ')" href="#"><i class="fa fa-plus"></i></a> ');
                            }
                            actions.push('<a class="btn btn-success btn-xs btnEdit" title="Edit" onclick="showModalInput(\'' + row.tipe_item + '\',' + row.id + ',' + row.id_parent + ',' + false + ',\'edit\')"><i class="fa fa-edit"></i></a> ');
                            actions.push('<a class="btn btn-danger btn-xs " title="Hapus" onclick="confirmDelete(' + row.id + ',\'bom_part_jasa\',\'' + row.tipe_item + '\')"><i class="fa fa-remove"></i></a>');
                            return actions.join('');
                        }
                    },
                    {
                        title: 'Section & Object',
                        field: 'tipe_id',
                        width: '200',
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return '<strike class="label label-success font14">' + value + '</strike>';
                                return '<span class="label label-success font14">' + value + '</span>';
                            } else if (row.tipe_item == 'object') {
                                if (row.deleted == 1)
                                    return addSpace(3) + '<strike class="label label-primary font14">' + value + '</strike>';
                                return addSpace(3) + '<span class="label label-primary font14">' + value + '</span>';
                            } else if (row.tipe_item == 'sub_object') {
                                if (row.deleted == 1)
                                    return addSpace(6) + '<strike class="label label-warning font14">' + value + '</strike>';
                                return addSpace(6) + '<span class="label label-warning font14">' + value + '</span>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        field: 'tipe_name',
                        title: 'Name',
                        width: '300',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return `<strike class="section-identity">` + value + `</strike>`;
                                return `<span class="section-identity">` + value + `</span>`;
                            } else if (row.tipe_item == `object`) {
                                if (row.deleted == 1)
                                    return `<strike class="object-identity">` + addSpace(3) + value + `</strike>`;
                                return `<span class="object-identity">` + addSpace(3) + value + `</span>`;
                            } else if (row.tipe_item == `sub_object`) {
                                if (row.deleted == 1)
                                    return `<strike class="sub_object-identity">` + addSpace(6) + value + `</strike>`;
                                return `<span class="sub_object-identity">` + addSpace(6) + value + `</span>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        field: 'item_code',
                        title: 'Item Code',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'item_name',
                        title: 'Item Name',
                        width: '300',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'spec',
                        title: 'Spec',
                        width: '200',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'merk',
                        title: 'Merk',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'satuan',
                        title: 'Satuan',
                        width: '100',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'harga',
                        title: 'Harga',
                        width: '120',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            } else {
                                if (row.deleted == 1)
                                    return `<strike class="total_harga">${value}</strike>`;
                                return '<span class="total_harga">' + value + '</span>';
                            }
                        }
                    },
                    {
                        field: 'qty',
                        title: 'Qty',
                        width: '100',
                        align: "right",
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            } else {
                                if (row.deleted == 1)
                                    return `<strike>${value}</strike>`;
                                return value;
                            }
                        }
                    },
                    {
                        field: 'total',
                        title: 'Total',
                        width: '150',
                        align: "right",
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                if (row.deleted == 1)
                                    return '<strike class="total_harga text-bold">' + value + '</strike>';
                                return '<span class="total_harga text-bold">' + value + '</span>';
                            }
                            if (row.deleted == 1)
                                return '<strike class="total_harga">' + value + '</strike>';
                            return '<span class="total_harga">' + value + '</span>';
                        }
                    },
                    {
                        field: 'nama_kategori',
                        title: 'Kategori',
                        width: '180',
                        align: "left",
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'item' && row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    }

                ],
                onAll: function(data) {
                    let gpsection = $(".section-identity").parent().parent();
                    for (let index = 0; index < gpsection.length; index++) {
                        const element = gpsection[index];
                        $(element).addClass('section-bg')
                    }
                    let osection = $(".object-identity").parent().parent();
                    for (let index = 0; index < osection.length; index++) {
                        const element = osection[index];
                        $(element).addClass('object-bg')
                    }
                    let susection = $(".sub_object-identity").parent().parent();
                    for (let index = 0; index < susection.length; index++) {
                        const element = susection[index];
                        $(element).addClass('sub_object-bg')
                    }
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
                    // console.log("onDblClickCell",row);
                    return false;
                },
                onClickRow: function(row, $element) {
                    // console.log("onClickRow",row);
                    return false;
                },
                onDblClickRow: function(row, $element) {
                    // console.log("onDblClickRow",row);
                    return false;
                },
                // data:[]
            });

            $("#selectBtn").click(function() {
                var selecteds = $('#demo').bootstrapTreeTable('getSelections');
                $.each(selecteds, function(_i, _item) {
                    console.log(_item);
                });
                //alert("看console");
            });

            var _expandFlag_all = false;
            $("#expandAllBtn").click(function() {
                if (_expandFlag_all) {
                    $('#demo').bootstrapTreeTable('expandAll');
                } else {
                    $('#demo').bootstrapTreeTable('collapseAll');
                }
                _expandFlag_all = _expandFlag_all ? false : true;
            });
        }

        /*
            tree table labour
        */

        function generateLabourTable(show_deleted = 0) {
            var labourTable = $('#labour_table').bootstrapTreeTable({
                toolbar: "#labour-toolbar", //顶部工具条
                expandColumn: 1,
                expandAll: false,
                height: 480,
                type: 'get',
                parentId: 'id_parent',
                url: base_url + 'bmaterial/get_data_labour/' + id_header_tree + '?show-deleted=' + show_deleted,
                columns: [{
                        checkbox: true
                    },
                    {
                        title: 'Section & Object',
                        field: 'tipe_id',
                        width: '150',
                        fixed: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return '<strike class="label label-success font14">' + value + '</strike>';
                                return '<span class="label label-success font14">' + value + '</span>';
                            } else if (row.tipe_item == 'object') {
                                if (row.deleted == 1)
                                    return addSpace(3) + '<strike class="label label-primary font14">' + value + '</strike>';
                                return addSpace(3) + '<span class="label label-primary font14">' + value + '</span>';
                            } else if (row.tipe_item == 'sub_object') {
                                if (row.deleted == 1)
                                    return addSpace(6) + '<strike class="label label-warning font14">' + value + '</strike>';
                                return addSpace(6) + '<span class="label label-warning font14">' + value + '</span>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        field: 'opsi',
                        title: 'Labour',
                        width: '130',
                        align: "center",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.deleted == 1)
                                return '';
                            return `<button onclick="showModalDetailLabour(${value},'ENGINEERING')" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> ENG</button>
                                <button onclick="showModalDetailLabour(${value},'PRODUCTION')" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> PROD</button>`;
                        }
                    },

                    {
                        field: 'tipe_name',
                        title: 'Name',
                        width: '300',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return `<strike class="section-identity">` + value + `</strike>`;
                                return `<span class="section-identity">` + value + `</span>`;
                            } else if (row.tipe_item == `object`) {
                                if (row.deleted == 1)
                                    return `<strike class="object-identity">` + addSpace(3) + value + `</strike>`;
                                return `<span class="object-identity">` + addSpace(3) + value + `</span>`;
                            } else if (row.tipe_item == `sub_object`) {
                                if (row.deleted == 1)
                                    return `<strike class="sub_object-identity">` + addSpace(6) + value + `</strike>`;
                                return `<span class="sub_object-identity">` + addSpace(6) + value + `</span>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        field: 'total',
                        title: 'Total',
                        width: '150',
                        align: "right",
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                if (row.deleted == 1)
                                    return '<strike class="total_harga text-bold">' + value + '</strike>';
                                return '<span class="total_harga text-bold">' + value + '</span>';
                            }
                            if (row.deleted == 1)
                                return '<strike class="total_harga">' + value + '</strike>';
                            return '<span class="total_harga">' + value + '</span>';
                        }
                    }

                ],
                onAll: function(data) {
                    let gpsection = $(".section-identity").parent().parent();
                    for (let index = 0; index < gpsection.length; index++) {
                        const element = gpsection[index];
                        $(element).addClass('section-bg')
                    }
                    let osection = $(".object-identity").parent().parent();
                    for (let index = 0; index < osection.length; index++) {
                        const element = osection[index];
                        $(element).addClass('object-bg')
                    }
                    let susection = $(".sub_object-identity").parent().parent();
                    for (let index = 0; index < susection.length; index++) {
                        const element = susection[index];
                        $(element).addClass('sub_object-bg')
                    }
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
                    // console.log("onDblClickCell",row);
                    return false;
                },
                onClickRow: function(row, $element) {
                    // console.log("onClickRow",row);
                    return false;
                },
                onDblClickRow: function(row, $element) {
                    // console.log("onDblClickRow",row);
                    return false;
                },
                // data:[]
            });

            var _expandFlag_all_labour = false;
            $("#expandAllBtnLabour").click(function() {
                if (_expandFlag_all_labour) {
                    $('#labour_table').bootstrapTreeTable('expandAll');
                } else {
                    $('#labour_table').bootstrapTreeTable('collapseAll');
                }
                _expandFlag_all_labour = _expandFlag_all_labour ? false : true;
            });
        }

        /*
            tree table material
        */

        function generateMaterialTable(show_deleted = 0) {
            var materialTable = $('#material_table').bootstrapTreeTable({
                toolbar: "#material-toolbar", //顶部工具条
                expandColumn: 1,
                expandAll: false,
                height: 480,
                type: 'get',
                parentId: 'id_parent',
                url: base_url + 'bmaterial/get_data_material/' + id_header_tree + '?show-deleted=' + show_deleted,
                columns: [{
                        checkbox: true
                    },
                    {
                        title: 'Opsi',
                        width: '140',
                        align: "center",
                        fixed: true,
                        formatter: function(value, row, index) {
                            var actions = [];
                            if (row.deleted == 1)
                                return ''
                            if (row.tipe_item === 'item') {
                                actions.push('<a class="btn btn-success btn-xs btnEdit" title="Edit" onclick="addItemMaterial(' + row.id + ',' + row.id_parent + ',\'edit\')"><i class="fa fa-edit"></i></a> ');
                                actions.push('<a class="btn btn-danger btn-xs " title="Hapus" onclick="confirmDelete(' + row.id + ',\'bom_rawmaterial\',\'' + row.tipe_item + '\')"><i class="fa fa-remove"></i></a>');
                            } else {
                                actions.push('<a class="btn btn-info btn-xs " title="Tambah Sub" onclick="addItemMaterial(' + row.id + ',' + row.id_parent + ')" href="#"><i class="fa fa-plus"></i></a> ');
                            }
                            return actions.join('');
                        }
                    },
                    {
                        title: 'Section & Object',
                        field: 'tipe_id',
                        width: '150',
                        fixed: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return '<strike class="label label-success font14">' + value + '</strike>';
                                return '<span class="label label-success font14">' + value + '</span>';
                            } else if (row.tipe_item == 'object') {
                                if (row.deleted == 1)
                                    return addSpace(3) + '<strike class="label label-primary font14">' + value + '</strike>';
                                return addSpace(3) + '<span class="label label-primary font14">' + value + '</span>';
                            } else if (row.tipe_item == 'sub_object') {
                                if (row.deleted == 1)
                                    return addSpace(6) + '<strike class="label label-warning font14">' + value + '</strike>';
                                return addSpace(6) + '<span class="label label-warning font14">' + value + '</span>';
                            } else {
                                return '';
                            }
                        }
                    },


                    {
                        field: 'tipe_name',
                        title: 'Name',
                        width: '300',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item == 'section') {
                                if (row.deleted == 1)
                                    return `<strike class="section-identity">` + value + `</strike>`;
                                return `<span class="section-identity">` + value + `</span>`;
                            } else if (row.tipe_item == `object`) {
                                if (row.deleted == 1)
                                    return `<strike class="object-identity">` + addSpace(3) + value + `</strike>`;
                                return `<span class="object-identity">` + addSpace(3) + value + `</span>`;
                            } else if (row.tipe_item == `sub_object`) {
                                if (row.deleted == 1)
                                    return `<strike class="sub_object-identity">` + addSpace(6) + value + `</strike>`;
                                return `<span class="sub_object-identity">` + addSpace(6) + value + `</span>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        field: 'item_code',
                        title: 'Item Code',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'part_name',
                        title: 'Part Name',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'units',
                        title: 'Units',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'qty',
                        title: 'Qty',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'materials',
                        title: 'Materials',
                        width: '150',
                        align: "left",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'l',
                        title: 'Length',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'w',
                        title: 'Weight',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'h',
                        title: 'Height',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 't',
                        title: 'Diameter',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'density',
                        title: 'Density',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'weight',
                        title: 'Weight',
                        width: '150',
                        align: "right",
                        visible: true,
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                return '';
                            }
                            if (row.deleted == 1)
                                return `<strike>${value}</strike>`;
                            return value;
                        }
                    },
                    {
                        field: 'total',
                        title: 'Total',
                        width: '200',
                        align: "right",
                        formatter: function(value, row, index) {
                            if (row.tipe_item != 'item') {
                                if (row.deleted == 1)
                                    return '<strike id="totalValue' + row.id + '" class="total_harga text-bold">' + value + '</strike>';
                                return '<span id="totalValue' + row.id + '" class="total_harga text-bold">' + value + '</span>';
                            }
                            if (row.deleted == 1)
                                return '<strike id="totalValue' + row.id + '" class="total_harga">' + value + '</strike>';
                            return '<span id="totalValue' + row.id + '" class="total_harga">' + value + '</span>';
                        }
                    }

                ],
                onAll: function(data) {
                    let gpsection = $(".section-identity").parent().parent();
                    for (let index = 0; index < gpsection.length; index++) {
                        const element = gpsection[index];
                        $(element).addClass('section-bg')
                    }
                    let osection = $(".object-identity").parent().parent();
                    for (let index = 0; index < osection.length; index++) {
                        const element = osection[index];
                        $(element).addClass('object-bg')
                    }
                    let susection = $(".sub_object-identity").parent().parent();
                    for (let index = 0; index < susection.length; index++) {
                        const element = susection[index];
                        $(element).addClass('sub_object-bg')
                    }
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
                    // console.log("onDblClickCell",row);
                    return false;
                },
                onClickRow: function(row, $element) {
                    // console.log("onClickRow",row);
                    return false;
                },
                onDblClickRow: function(row, $element) {
                    // console.log("onDblClickRow",row);
                    return false;
                },
                // data:[]
            });

            var _expandFlag_all_material = false;
            $("#expandAllBtnMaterial").click(function() {
                if (_expandFlag_all_material) {
                    $('#material_table').bootstrapTreeTable('expandAll');
                } else {
                    $('#material_table').bootstrapTreeTable('collapseAll');
                }
                _expandFlag_all_material = _expandFlag_all_material ? false : true;
            });
        }


        function newForm() {
            window.open(base_url + 'bmaterial', '_self');
        }

        function getDataHeader(idx) {
            console.log('xxxx');

            $.ajax({
                url: base_url + 'bmaterial/get_data_header/' + idx,
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: function(json) {
                    console.log(json.data.object[0].wono)
                    if (json.data.code === 0) {
                        notify('danger', 'Terjadi kesalahan!');

                    } else {
                        setTimeout(() => {
                            $("#wono").val(json.data.object[0].wono);
                            $("#project_name").val(json.data.object[0].desc);
                            $("#date").val(json.data.object[0].date);
                            $('#efDate').val(json.data.object[0].complete);
                            $("#daysLeft").val(json.data.object[0].left);
                            $("#customer").val(json.data.object[0].customer);
                            $("#marketing").val(json.data.object[0].mkt);
                            $("#pl").val(json.data.object[0].pl);
                            $("#id_header-item").val(json.data.object[0].id);
                        }, 700);


                    }
                }
            });
        }

        function confirmDelete(n, table, tipe_item = '') {
            swal({
                    title: "Konfirmasi Hapus",
                    text: "Apakah anda yakin akan menghapus data ini?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: " Ya",
                    closeOnConfirm: true
                },
                function() {
                    loading('loading', true);
                    setTimeout(function() {
                        $.ajax({
                            url: base_url + 'bmaterial/del_item',
                            data: 'id=' + n + '&table=' + table + '&tipe_item=' + tipe_item,
                            dataType: 'json',
                            type: 'POST',
                            cache: false,
                            success: function(json) {
                                loading('loading', false);
                                if (json.data.code === 1) {
                                    notify('success', 'Hapus data berhasil');
                                    if (table == 'bom_part_jasa' ) {
                                        $('#demo').bootstrapTreeTable('refresh');
                                    } else if (table == 'bom_rawmaterial') {
                                        $('#material_table').bootstrapTreeTable('refresh');
                                    }

                                } else {
                                    notify('warning', json.data.message, 300);
                                }
                            },
                            error: function() {
                                loading('loading', false);
                                notify('danger', 'Tidak dapat hapus data!', 'error', 'Error');
                            }
                        });
                    }, 100);
                });
        }

        /* General Function */
        /*
            Check if header is saved
        */
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var target = $(e.target).attr("href").substr(1) // activated tab
            // console.log(target)
            if (target != 'general_info_tab' && id_header_tree == '') {
                notify('danger', 'Simpan General Info Terlebih dahulu!');
                setActiveTab("general_info_tab");
            } else {
                /* if header saved load tree table*/
                if (target == 'std_part_tab') {
                    $('#demo').bootstrapTreeTable('refresh');
                } else if (target == 'labour_tab') {
                    $('#labour_table').bootstrapTreeTable('refresh');
                } else if (target == 'material_tab') {
                    $('#material_table').bootstrapTreeTable('refresh');
                }
            }
        });

        /* Hitung perbedaan bulan*/
        function calcDate() {
            var start = $("#start_date").val();
            var end = $("#finish_date").val();
            var diff = calcDiffDateToMonth(start, end);
            $("#duration").val(diff + ' MONTH');
        }

        /* Tampilkan modal tambah & edit
         * @title : title modal & label input
         * @id : id item self
         * @id_parent : id parent item
         * @sub : if sub true = add sub
         * @action : add = 1 || edit = 2
         */
        function showModalInput(title, id = '', id_parent = '', sub = false, action = 'add') {
            refreshTableDataItem();
            editedCellValueQty = [];
            $("#remark-harga").text('');
            $("#item_code").val([]).trigger("change");
            $("#item_code-item").val([]).trigger("change");
            $("#tipe_id-item").parent().removeClass('has-error');
            $("#harga-item").parent().removeClass('has-error');
            $("#qty-item").parent().removeClass('has-error');
            $("#tipe_item-item").parent().show();

            var title_self = title;
            var title_text = title;


            if (title == 'item') {
                $(".except_item").hide();
                $(".only_item").show();
            } else {
                $(".only_item").hide();
                $(".except_item").show();
            }

            if (sub == true) {
                switch (title) {
                    case 'section':
                        title_text = 'Object';
                        title = 'object';
                        data_select = [{
                                id: 'object',
                                text: 'Object'
                            },
                            {
                                id: 'item',
                                text: 'Item'
                            }
                        ];
                        break;
                    case 'object':
                        title_text = 'Sub Object';
                        title = 'sub_object';
                        data_select = [{
                                id: 'sub_object',
                                text: 'Sub Object'
                            },
                            {
                                id: 'item',
                                text: 'Item'
                            }
                        ];
                        break;
                    case 'sub_object':
                        title_text = 'Item';
                        title = 'item';
                        data_select = [{
                            id: 'item',
                            text: 'Item'
                        }];
                        /* if add but title = item*/
                        $(".except_item").hide();
                        $(".only_item").show();
                        break;
                }
            } else {
                if (title == 'section') {
                    data_select = [{
                        id: 'section',
                        text: 'Section'
                    }];
                }
            }

            $("#tipe_item-item").children().remove();
            $.each(data_select, function(index, value) {
                $("#tipe_item-item").append('<option value="' + value.id + '">' + value.text + '</option>')
            });

            /* Set active tab new */
            setActiveTab('tab_input_item_new')

            /* Set default Form value*/
            if (action == 'edit') {
                /* disable lick tab */
                $("#tab_input_item_exists-a").css('pointer-events', 'none')

                data_select = [{
                        id: 'section',
                        text: 'Section'
                    },
                    {
                        id: 'object',
                        text: 'Object'
                    },
                    {
                        id: 'sub_object',
                        text: 'Sub Object'
                    },
                    {
                        id: 'item',
                        text: 'Item'
                    }
                ];

                $("#tipe_item-item").children().remove();
                $.each(data_select, function(index, value) {
                    $("#tipe_item-item").append('<option value="' + value.id + '">' + value.text + '</option>')
                });

                $.ajax({
                    url: base_url + 'bmaterial/get_data_part/' + id_header_tree + '/' + id,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json) {
                        var itcode = json.item_code;
                        $("#tipe_id-item").val(json.tipe_id);
                        $("#harga-item").val(parseInt(json.harga));
                        $("#item_code-item").val(json.item_code);
                        $('#item_code-item').trigger('change');
                        $('#item_codeUnassigned-item').trigger('change');
                        $("#item_name-item").val(json.item_name);
                        $("#kategori-item").val(json.kategori);
                        $('#kategori-item').trigger('change');
                        $("#merk-item").val(json.merk);
                        $("#qty-item").val(json.qty);
                        $("#satuan-item").val(json.satuan);
                        $('#satuan-item').trigger('change');
                        $("#spec-item").val(json.spec);
                        $("#tipe_name-item").val(json.tipe_name);
                        $("#tipe_item-item").val(json.tipe_item);
                        $("#id-item").val(json.id);
                        $("#remark-harga").text(json.remark_harga);
                        if(itcode){
                            $("#harga-item").css('pointer-events', 'none');
                            $("#item_code-item").attr("disabled",true);
                            $("#item_name-item").css('pointer-events', 'none');
                            $("#merk-item").css('pointer-events', 'none');
                            $("#satuan-item").attr("disabled",true);
                            $("#spec-item").css('pointer-events', 'none');
                            $("#tipe_name-item").css('pointer-events', 'none');
                            $("#tipe_item-item").css('pointer-events', 'none');
                        }else{
                            $("#harga-item").css('pointer-events', '');
                            // $("#item_code-item").removeAttr('disabled'); 
                            $("#item_name-item").css('pointer-events', '');
                            $("#merk-item").css('pointer-events', '');
                            $("#satuan-item").removeAttr('disabled');
                            $("#spec-item").css('pointer-events', '');
                            $("#tipe_name-item").css('pointer-events', '');
                            $("#tipe_item-item").css('pointer-events', '');
                        }
                    }
                });
                $("#id_parent-item").val(id_parent);
                $("#action-item").val(2);
                $("#tipe_item-item").val(title);
                $("#tipe_item-item").parent().hide();

            } else {
                /* disable click tab */
                if ($("#tipe_item-item").val() != 'item') {
                    $("#tab_input_item_exists-a").css('pointer-events', 'none')
                } else {
                    $("#tab_input_item_exists-a").css('pointer-events', 'auto')
                }

                if (title != 'item')
                    $.get(base_url + 'bmaterial/get_counter_item', {
                        'tipe_item': title,
                        'id_parent': id,
                        'id_header': id_header_tree
                    }, (response) => {
                        $("#tipe_id-item").val(response.data);
                    }, 'json')

                $("#form-input-item")[0].reset();

                if (title != 'section') {
                    $("#id_parent-item").val(id);
                } else {
                    $("#id_parent-item").val(0);
                }

                if (sub == true) {
                    $("#tipe_item-item").val(title);
                } else {
                    $("#tipe_item-item").val(title_self);
                }
                $("#action-item").val(1);
                $("#kategori-item").val("");
                $('#kategori-item').trigger('change');
            }

            title_text = title_text.replace('_', ' ');

            $(".modal-title-input").text(ucFirst(title_text));
            $('#modal-input-item').modal('show');
        }


        function saving() {
            // CKupdate();
            loading('loading', true);
            var form = $("#form-general-info").serialize();
            var data = form + '&start_date-general=' + formatDate($("#start_date").val(), false) + '&finish_date-general=' + formatDate($("#finish_date").val(), false);
            setTimeout(function() {
                $.ajax({
                    url: base_url + 'bmaterial/save_gen_info',
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json) {
                        loading('loading', false);
                        if (json.data.code === 0) {
                            if (json.data.message == '') {
                                notify('danger', 'Penyimpanan data gagal!');
                            } else {
                                notify('warning', json.data.message);
                            }
                        } else {
                            notify('success', 'Penyimpanan data berhasil');
                            setTimeout(function() {
                                window.open(base_url + 'bmaterial/' + json.last_id, '_self');
                            }, 1000);
                        }
                    },
                    error: function() {
                        loading('loading', false);
                        notify('danger', 'Terjadi kesalahan!');
                    }
                });
            }, 100);
        }

        // function count

        function saveItem() {
            
            loading('loading', true);
            // $("#item_code-item").removeAttr('disabled'); 
            $("#satuan-item").removeAttr("disabled",true);
            var harga = ($("#harga-item").cleanVal() * 1);
            var qty = $("#qty-item").val();
            var tipe_id = $("#tipe_id-item").val();
            var id_karyawan = $("#id_karyawan").val();


            if ($("#tipe_item-item").val() == 'item') {
                /* validate item */
                if (harga == '' || (harga) == 0) {
                    $("#harga-item").parent().addClass('has-error');
                    alert('Input Harga!');
                    return;
                } else {
                    $("#harga-item").parent().removeClass('has-error');
                }

                if (qty == '' || (qty) == 0) {
                    $("#qty-item").parent().addClass('has-error');
                    alert('Input Qty!');
                    return;
                } else {
                    $("#qty-item").parent().removeClass('has-error');
                }

            } else {

                if (tipe_id == '') {
                    $("#tipe_id-item").parent().addClass('has-error');
                    alert('Input ' + $("#tipe_item-item").val() + '!');
                    return;
                } else {
                    $("#tipe_id-item").parent().removeClass('has-error');
                }
            }
            if ($("#item_code-item").val() == '') {
                $("#item_code").val('')
            }
            // console.log($("#tipe_item-item").val());
            // console.log($("#item_code-item").val());
            // return;


            var data = $("#form-input-item").serialize() + '&harga-item-clean=' + harga + '&remark-harga=' + $("#remark-harga").text();
            // console.log(data);
            // return;
            setTimeout(function() {
                $.ajax({
                    url: base_url + 'bmaterial/save_item',
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json) {
                        loading('loading', false);
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
                    },
                    error: function() {
                        loading('loading', false);
                        notify('danger', 'Terjadi kesalahan!!');
                    }
                });
            }, 100);
        }

        function formatDate(date, to_indo = true) {
            var m, d, y, formatted;
            if (to_indo == true) {
                y = date.substr(0, 4);
                m = date.substr(5, 2);
                d = date.substr(8, 2);
                formatted = d + '-' + m + '-' + y;
            } else {
                y = date.substr(6, 4);
                m = date.substr(3, 2);
                d = date.substr(0, 2);
                formatted = y + '-' + m + '-' + d;
            }
            return formatted;

        }

        function editHour(id) {
            // console.log(val, id)
            var val = $("#valHour" + id + "").text();
            var html = '<input class="" style="width:50px;height:23px;border:1px solid #ccc;padding:0px;margin:0px;" type="number" id="inputHour' + id + '" value="' + val + '" min="0" />';
            $("#valHour" + id + "").html(html);
            $("#inputHour" + id + "").focus();
            $("#btnEdit" + id + "").css("display", "none");
            $("#btnSave" + id + "").css("display", "inline");
        }

        function saveHour(id, id_parent = 0) {
            var hour = $("#inputHour" + id + "").val();
            if (hour === '') {
                notify('warning', 'Input Hour!');
                return;
            }
            // hour = hour * 1;
            // var total_old = $("#totalValue" + id + "").cleanVal() * 1;
            // var rate = $("#rateValue" + id + "").cleanVal() * 1;
            // var total = hour * rate;
            // var total_parent_old = $("#totalValue" + id_parent + "").cleanVal() * 1;
            // var total_parent = (total - total_old) + total_parent_old;

            // $("#valHour" + id + "").html(hour);
            // $("#totalValue" + id + "").text(total);
            // var masked = $("#totalValue" + id + "").masked(total);
            // $("#totalValue" + id + "").text(masked);

            // $("#totalValue" + id_parent + "").text(total_parent);
            // var masked = $("#totalValue" + id_parent + "").masked(total_parent);

            // $("#totalValue" + id_parent + "").text(masked);
            // $("#btnEdit" + id + "").css("display", "inline");
            // $("#btnSave" + id + "").css("display", "none");

            $.ajax({
                url: base_url + 'bmaterial/save_hour/',
                dataType: 'json',
                type: 'POST',
                data: {
                    id: id,
                    hour: hour
                },
                cache: false,
                success: function(json) {

                    if (json.code == 1) {
                        notify('success', 'Sukses');
                        let dt = JSON.parse(localStorage.getItem('dataModalLabour'))
                        refreshTableDetail(dt.id_header, dt.type_input)
                        $('#labour_table').bootstrapTreeTable('refresh');

                    } else {
                        notify('danger', 'Gagal!');
                        var parent_masked = $("#totalValue" + id_parent + "").masked(total_parent_old);
                        $("#totalValue" + id_parent + "").text(parent_masked);

                        var total_masked = $("#totalValue" + id + "").masked(total_old);
                        $("#totalValue" + id + "").text(total_masked);
                    }
                }
            });

        }

        /* rumus perhitungan material */
        function perhitunganMaterial() {
            var tipe = $("#tipe_rumus-material").val();
            var width = $("#width-material").val() * 1;
            var length = $("#length-material").val() * 1;
            var height = $("#height-material").val() * 1;
            var diameter = $("#diameter-material").val() * 1;
            var qty = $("#qty-material").val() * 1;
            var density = $("#density-material").val() * 1;
            var price = $("#price-material").cleanVal() * 1;
            var weight = 0;
            var wval = 0;
            var total = 0;
            var totval = 0;
            if (tipe == 'RUMUS-1') {
                // WEIGHT = QTY x ( W x H x TB) x Density
                temp_weight = (width * height * diameter) * density
                weight = qty * temp_weight.toFixed(9);
                // weight = qty * (width * height * diameter) * density;
                wval = weight.toFixed(2);
                total = wval * price;
                totval = total.toFixed();
            } else if (tipe == 'RUMUS-2') {
                //WEIGHT = QTY x (( 3.14 x ( DIA / 2) ^ 2) x L) * Density
                temp_weight = ((3.14 * Math.pow((diameter / 2), 2)) * length) * density;
                weight = qty * temp_weight.toFixed(9);
                wval = weight.toFixed(2);
                total = wval * price;
                totval = total.toFixed();
            } else {
                //WEIGHT = QTY x (( 2 x ( H x TB x L)) + (( W - ( 2 x TB)) x TB x L )) x Density
                temp_weight = ((2 * (height * diameter * length)) + ((width - (2 * diameter)) * diameter * length)) * density;
                weight = qty * temp_weight.toFixed(9);
                // weight =  qty * ((2 * (height * diameter * length)) + ((width - (2 * diameter)) * diameter * length)) * density;
                wval = weight.toFixed(2);
                total = wval * price;
                totval = total.toFixed();
            }

            var masked_weight = $("#weight-material").masked(weight.toFixed(2));
            $("#weight-material").val(masked_weight);
            var masked_total = $("#total-material").masked(total.toFixed(2));
            $("#total-material").val(masked_total);
        }

        /* save material item*/
        function saveMaterial() {
            loading('loading', true);

            var data = $("#form-input-material").serialize();
            setTimeout(function() {
                $.ajax({
                    url: base_url + 'bmaterial/save_material',
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    success: function(json) {
                        loading('loading', false);
                        if (json.data.code === 0) {
                            if (json.data.message == '') {
                                notify('danger', 'Penyimpanan data gagal!');
                            } else {
                                notify('warning', json.data.message);
                            }
                        } else {
                            notify('success', 'Penyimpanan data berhasil');
                            $('#modal-input-material').modal('hide');
                            $('#material_table').bootstrapTreeTable('refresh');

                        }
                    },
                    error: function() {
                        loading('loading', false);
                        notify('danger', 'Terjadi kesalahan!!');
                    }
                });
            }, 100);
        }

        function addItemMaterial(id, id_parent, action = 'add') {
            $("#form-input-material")[0].reset();
            if (action == 'edit') {
                $("#id-material").val(id)
                $("#action-material").val(2)
                $("#id_parent-material").val(id_parent)

                setTimeout(function() {
                    $.ajax({
                        url: base_url + 'bmaterial/get_data_material/' + id_header_tree + '/' + id,
                        dataType: 'json',
                        type: 'POST',
                        cache: false,
                        success: function(json) {
                            loading('loading', false);
                            $("#item_code-save-material").val(json.item_code);
                            $("#item_code-material").val(json.item_code);
                            $("#weight-material").val(json.weight);
                            $("#length-material").val(json.l * 1);
                            $("#width-material").val(json.w * 1);
                            $("#height-material").val(json.h * 1);
                            $("#diameter-material").val(json.t * 1);
                            $("#part_name-material").val(json.part_name);
                            $("#materials-material").val(json.materials);
                            $("#density-material").val(json.density * 1);
                            $("#tipe_rumus-material").val(json.type);
                            var masked_price = $("#price-material").masked(json.price * 1);
                            $("#price-material").val(masked_price);
                            $("#qty-material").val(json.qty * 1);
                            $("#units-material").val(json.units);
                            $("#total-material").val(json.weight * json.price);

                            if (json.type == 'RUMUS-1') {
                                $(".rumus2, .rumus3").hide();
                                $(".rumus1").show();
                            } else if (json.type == 'RUMUS-2') {
                                $(".rumus1, .rumus3").hide();
                                $(".rumus2").show();
                            } else {
                                $(".rumus1, .rumus2").hide();
                                $(".rumus3").show();
                            }
                        },
                        error: function() {
                            loading('loading', false);
                            notify('danger', 'Terjadi kesalahan!!');
                        }
                    });
                }, 100);
            } else {
                $("#id-material").val('')
                $("#action-material").val(1)
                $("#id_parent-material").val(id)
            }

            $("#id_header-material").val(id_header_tree);
            $("#modal-input-material").modal('show');
        }

        // group kategori
        function switchCodeToCategory(cd) {
            var codes = [{
                    code: ['ATK', 'CNS', 'RMT', 'PPG', 'OFF', 'INV'],
                    value: '10001'
                },
                {
                    code: ['ELC'],
                    value: '10002'
                },
                {
                    code: ['MCL'],
                    value: '10003'
                },
                {
                    code: ['PNU', 'PNE','PPG'],
                    value: '10004'
                },
                {
                    code: ['SNS'],
                    value: '20001'
                },
               
            ];
            let val = '';
            codes.forEach(element => {
                if (element.code.indexOf(cd) !== -1) {
                    val = element.value
                }
            });
            return val;

        }

        function addSpace(num_of_space = 1) {
            var space = '';
            for (let index = 0; index < num_of_space; index++) {
                space += `&nbsp`
            }
            return space
        }

        // Show Modal Detail
        function showModalDetailLabour(id, title) {
            refreshTableDetail(id, title);
            localStorage.setItem('dataModalLabour', JSON.stringify({
                'id_header': id,
                'type_input': title
            }));
            $("#labour-input-title").text(title)
            $("#modal-detail").modal('show');
        }

        // Datatble Detail Labour
        function getDetailLabour(type = 'ENGINEERING', id_header = 0) {
            if ($.fn.dataTable.isDataTable('#table-detail-input-pr')) {
                tableDetailLabour = $('#table-detail-input-pr').DataTable();
            } else {
                tableDetailLabour = $('#table-detail-input-pr').DataTable({
                    "ajax": base_url + 'bmaterial/get_detail_by_header/' + <?= $param ?> + '/' + id_header + '/' + type,
                    "columns": [{
                            "data": "no"
                        },
                        {
                            "data": "id_labour"
                        },
                        {
                            "data": "aktivitas"
                        },
                        {
                            "data": "sub_aktivitas"
                        },
                        {
                            "data": "hour",
                            "className": 'text-right',
                            "render": function(data, type, row) {
                                if (row.tipe_item != 'item') {
                                    return '';
                                } else {
                                    var actions = [];
                                    actions.push('<span id="valHour' + row.id + '">' + data + '</span>&nbsp;&nbsp;');
                                    actions.push('<button id="btnEdit' + row.id + '"class="btn btn-success btn-xs btnEdit" title="Edit" onclick="editHour(' + row.id + ')"><span id="iconHour' + row.id + '"><i class="fa fa-edit"></i></span></button> ');

                                    actions.push('<button style="display:none;" id="btnSave' + row.id + '"class="btn btn-info btn-xs btnEdit" title="Save" onclick="saveHour(' + row.id + ',' + row.id_parent + ')"><i class="fa fa-check"></i></button> ');
                                    return actions.join('');
                                }
                            }
                        },
                        {
                            "data": "rate",
                            "className": 'text-right',
                        },
                        {
                            "data": "total",
                            "className": 'text-right',
                        }
                    ],
                    "ordering": true,
                    "pageLength": 50,
                    "deferRender": true,
                    "order": [
                        [0, "asc"]
                    ],
                    "select": {
                        "style": 'multi',
                    },
                    "fnDrawCallback": function(oSettings) {
                        // utilsBidang();
                    }
                });
            }
        }

        function refreshTableDetail(id_header, type) {
            tableDetailLabour.ajax.url(base_url + 'bmaterial/get_detail_by_header/' + <?= $param ?> + '/' + id_header + '/' + type, ).load();
        }

        // Export Excel
        function exportExcel(report = 'summary', id_header) {
            window.open(base_url + `bmaterial/print_${report}/` + id_header);
        }

        function mockData() {
            $.get(base_url + "bmaterial/get_item_code/1", function(data) {
                return data;
            }, 'json')
        }
    </script>
</body>

</html>