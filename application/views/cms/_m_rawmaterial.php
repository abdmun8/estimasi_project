
<?php
//default value
$rumus = ['RUMUS-1','RUMUS-2','RUMUS-3'];
$units = $this->db->get('tblsatuan')->result();
$id = null;
if ($param != null) {
    $item = $this->model->getRecord(array(
        'table' => 'mrawmaterial', 'where' => array('id' => $param)
        ));
    if ($item) {
        $id  = $item->item_code;
    }
}

?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="active">
            <a data-toggle="tab" href="#rawmaterial-table-tab" title="Table View">
                <i class="fa fa-table"></i>
            </a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#rawmaterial-form-tab" title="Form View">
                <i class="fa fa-edit"></i>
            </a>
        </li>
        <li class="pull-left header"><i class="fa fa-cube"></i>Raw Material</li>
        <div id="loading"></div>
    </ul>
    <div class="tab-content">
        <div id="rawmaterial-table-tab" class="tab-pane fade active in">
            <div style="padding-bottom:10px;">
                <button type="button" class="btn btn-success btn-sm" onclick="newForm();">Tambah Data</button>
                <!-- <li class="dropdown btn btn-default btn-float pull-right btn-sm">
                    <a style="color:#000" class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Export File <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="alert(1)">CSV</a></li>
                    </ul>
                </li> -->
                <button type="button" class="btn btn-default pull-right btn-sm" style="margin-right: 10px;" onclick="refreshTable()"><i class="fa fa-refresh" ></i> Refresh</button>
            </div>
            <div class="table-responsive">
                <table id="table-data" class="table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Part Name</th>
                            <th>Units</th>
                            <th>Materials</th>
                            <th>Density</th>
                            <th>Harga</th>
                            <th>Tipe Rumus</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div id="rawmaterial-form-tab" class="tab-pane fade">
            <section class="content padding20">
                <div class="row">
                    <!-- left column -->
                    <form class="form-horizontal" role="form" id="form-mrawmaterial">
                        <div class="col-md-6">
                            <!-- Left Form -->
                            <div class="form-group">
                                <label for="part_name-input" class="col-sm-3 control-label">Part Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="part_name-input" id="part_name-input" placeholder="Part Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="units-input" class="col-sm-3 control-label">Units</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="units-input" name="units-input" style="width: 100%;">
                                        <!-- <option value="" selected>Pilih Difficulty</option> -->
                                        <?php foreach($units as $key => $v): ?>
                                        <option value="<?=trim($v->name)?>"><?=trim($v->name)?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price-input" class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="price-input" id="price-input" placeholder="Price">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <!-- Right Form -->
                            <div class="form-group">
                                <label for="materials-input" class="col-sm-3 control-label">Materials</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="materials-input" id="materials-input" placeholder="Materials">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="density-input" class="col-sm-3 control-label">Density</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="density-input" id="density-input" placeholder="Density">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type-input" class="col-sm-3 control-label">Tipe Rumus</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="type-input" name="type-input">
                                        <option value="" disabled selected>Pilih Rumus</option>
                                        <?php foreach( $rumus as $v ): ?>
                                        <option value="<?=$v; ?>"><?=$v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success pull-right" onclick="saving();"><i class="fa fa-save"></i> Save</button>
                            <button type="button" class="btn btn-default pull-right" onclick="newForm();" style="margin-right: 10px;"><i class="fa fa-refresh"></i> Reset</button>
                            <input type="hidden" id="key-input" name="key-input" value="id" >
                            <input type="hidden" id="value-input" name="value-input" value="0" >
                            <input type="hidden" id="action-input" name="action-input" value="1" />
                            <input type="hidden" id="model-input" name="model-input" value="mrawmaterial" >
                        </div>
                    </form>
                </div>
            </section>
            <img style="border:4px solid red;align-items: 'center';" id="img-rumus" src="" />
        </div>

    </div>
</div>
<script>

    var rumus = [
        base_url + 'assets/images/rumus/r1.png',
        base_url + 'assets/images/rumus/r2.png',
        base_url + 'assets/images/rumus/r3.png'
    ];

    $(document).ready(function () {
        // CKEDITOR.replace('alamat-input');
        getDataTable();
        <?php
        if($param != null) {
            echo 'getData("'. $param .'");';
            echo 'setActiveTab("rawmaterial-form-tab");';
        }
        ?>

        units = $("#units-input").selectize();
        units_select = units[0].selectize;


        /* Daterang picker*/
        $('.select_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2018,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        $("#type-input").change(function(){
            setImage(this.value)
        })


    });

    function printBill(id){
        window.open(base_url + 'reportbill/billreport/'+id)
    }

    function setImage(val){
        var idx = val.substring(6);
        $("#img-rumus").attr('src', rumus[idx - 1   ])
    }


    function newForm() {
        loadContent(base_url + "view/_m_rawmaterial", function () {
            setActiveTab("rawmaterial-form-tab");
        });
    }

    function getDataTable() {
        if ($.fn.dataTable.isDataTable('#table-data')) {
            tableData = $('#table-data').DataTable();
        } else {
            tableData = $('#table-data').DataTable({
                "ajax": base_url + 'objects/mrawmaterial',
                "columns": [
                    {'data' :'no'},
                    {'data' :'item_code'},
                    {'data' :'part_name'},
                    {'data' :'units'},
                    {'data' :'materials'},
                    {'data' :'density'},
                    {'data' :'price'},
                    {'data' :'type'},
                    {'data' : "aksi", "width": "15%"}
               ],
                "ordering": true,
                "deferRender": true,
                "columnDefs" : [
                    // {
                    //     "targets" : 8,
                    //     "render" : function( data, type, row, meta ){
                    //         var diff = calcDiffDateToMonth(formatDate(row.start_date), formatDate(row.finish_date))
                    //         return diff + ' MONTH';
                    //     },
                    // },
                    {"targets" : 8, "className" : "text-center"}
                ],
                "order": [[0, "asc"]],
                "fnDrawCallback": function (oSettings) {
                    utilsDataTable();
                }
            });
        }
    }

    function utilsDataTable() {
        $("#table-data .editBtn").on("click",function() {
            loadContent(base_url + 'view/_m_rawmaterial/' + $(this).attr('href').substring(1));
        });

        $("#table-data .removeBtn").on("click",function() {
            confirmDelete($(this).attr('href').substring(1));
        });
    }

    function saving() {
        // CKupdate();
        loading('loading',true);
        setTimeout(function() {
            $.ajax({
                url: base_url + 'manage',
                data: $("#form-mrawmaterial").serialize(),
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
                        var page ='_m_rawmaterial/';
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

    function getData(idx) {
        $.ajax({
            url: base_url + 'object',
            data: 'model-input=mrawmaterial&key-input=id&value-input=' + idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    loginAlert('Akses tidak sah');
                } else {
                    $("#part_name-input").val(json.data.object.part_name);
                    units_select.setValue(json.data.object.units);
                    $("#materials-input").val(json.data.object.materials);
                    $("#density-input").val(json.data.object.density);
                    $("#price-input").val(parseInt(json.data.object.price));
                    $("#type-input").val(json.data.object.type);
                    $("#action-input").val('2');
                    $("#value-input").val(json.data.object.id);
                    setImage(json.data.object.type);
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
                    data: 'model-input=m_rawmaterial&action-input=3&key-input=id&value-input='+n,
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

    function refreshTable(){
        tableData.ajax.url(base_url + '/objects/mrawmaterial').load();
    }

    function CKupdate(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }
</script>
