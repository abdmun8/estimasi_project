<?php
//default value
$id = null;
if ($param != null) {
    $user = $this->model->getRecord(array(
        'table' => 'users', 'where' => array('id' => $param)
        ));
    if ($user) {
        $id  = $user->id;
    }
}

?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="active">
            <a data-toggle="tab" href="#bidang-table-tab" title="Table View">
                <i class="fa fa-table"></i>
            </a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#data-form-tab" title="Form View">
                <i class="fa fa-edit"></i>
            </a>
        </li>
        <li class="pull-left header"><i class="fa fa-file-text"></i>Quotation</li>
        <div id="loading"></div>
    </ul>
    <div class="tab-content">
        <div id="bidang-table-tab" class="tab-pane fade active in">
            <div style="padding-bottom:10px;">
                <button type="button" class="btn btn-success btn-sm" onclick="newForm();">Tambah Data</button>
                <li class="dropdown btn btn-default btn-float pull-right btn-sm">
                    <a style="color:#000" class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Export File <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="alert(1)">CSV</a></li>
                    </ul>
                </li>
            </div>
            <table id="table-data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Departement</th>
                        <th>Active</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="data-form-tab" class="tab-pane fade">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">General Info</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Part & Jasa</a></li>
                    <li><a href="#tab_3" data-toggle="tab">Labour</a></li>
                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <section class="content">
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
                                                    <option value="" selected>Pilih Lot</option>
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
                                                    <option value="" selected>Pilih Lot</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                            </div>
                                        </div>                                   
                                    </div>
                                </form>
                            </div>

                            
                        </section>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                        <section class="content">
                            <div class="row">
                                <!-- content -->
                                <div class="col-md-12">
                                    <div style="padding-bottom:10px;">
                                        <button type="button" class="btn btn-default btn-sm" onclick="">Print</button>
                                        <button type="button" class="btn btn-success btn-sm pull-right" onclick="">Tambah Item</button>

                                        <button style="margin-right: 10px;" type="button" class="btn btn-default btn-sm pull-right" onclick="">Enable/ Disable Edit</button>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12 table-responsive">
                                    <!-- <table id="users" class="table table-hover table-bordered">
                                        <tr class="active">
                                          <th>No</th>
                                          <th>Section & Object</th>
                                          <th>Name</th>
                                          <th>Item Code</th>
                                          <th>Name</th>
                                          <th>Spec</th>
                                          <th>Merk</th>
                                          <th>Satuan</th>
                                          <th>Harga</th>
                                          <th>Qty</th>
                                          <th>Total</th>
                                          <th>Kategori</th>
                                        </tr>
                                        <?php for ($i=1; $i < 10; $i++) : ?> 
                                            <tr>
                                              <td><?=$i?></td>
                                              <td><a href="#" class="username">superuser</a></td>
                                              <td>11-7-2014</td>
                                              <td><span class="label label-success">Approved</span></td>
                                              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                            </tr>
                                        <?php endfor ?>
                                        <tr>
                                              <td><?=$i?></td>
                                              <td><a href="#" id="username" data-type="text" data-placement="right" data-title="Enter username">superuser</a></td>
                                              <td>11-7-2014</td>
                                              <td><span class="label label-success">Approved</span></td>
                                              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                              <td>John Doe</td>
                                            </tr>                                                         
                                    </table> -->
                                <!-- /.content -->
                                <table id="users" class="table table-bordered table-condensed">
        <tr><th>#</th><th>name</th><th>age</th></tr>
        <tr>
            <td>1</td>
            <td><a href="#" data-pk="1">Mike</a></td>
            <td>21</td>       
        </tr>
        
        <tr>
            <td>2</td>
            <td><a href="#" data-pk="2">John</a></td>
            <td>28</td>       
        </tr>        
        
        <tr>
            <td>3</td>
            <td><a href="#" data-pk="3">Mary</a></td>
            <td>24</td>       
        </tr>        
        
    </table> 
                                </div>
                        </section>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // CKEDITOR.replace('alamat-input');
        // getBidang();
        <?php
        if($param != null) {
            echo 'getData("'. $param .'");';
            echo 'setActiveTab("data-form-tab");';
        }
        ?>

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

        /* Selectize */
        sel = $("#lot_general").selectize();
        lot_general = sel[0].selectize;

        /* Editable */
        $('#users a').editable({
            type: 'text',
            name: 'username',
            // url: '/post',
            title: 'Enter username'
        });
      
    });
    function newForm() {
        loadContent(base_url + "view/_users", function () {
            setActiveTab("data-form-tab");
        });
    }

    function getBidang() {
        if ($.fn.dataTable.isDataTable('#table-data')) {
            tableData = $('#table-data').DataTable();
        } else {
            tableData = $('#table-data').DataTable({
                "ajax": base_url + 'objects/users',
                "columns": [
                   {"data": "no"},
                   {"data": "username"},
                   {"data": "nama"},
                   {"data": "email"},
                   {"data": "jabatan"},
                   {"data": "departemen"},
                   {"data": "active"},
                   {"data": "aksi", "width": "15%"}
               ],
                "ordering": true,
                "deferRender": true,
                "order": [[0, "asc"]],
                "fnDrawCallback": function (oSettings) {
                    utilsBidang();
                }
            });
        }
    }

    function utilsBidang() {
        $("#table-data .editBtn").on("click",function() {
            loadContent(base_url + 'view/_users/' + $(this).attr('href').substring(1));
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
                data: $("#data-form").serialize(),
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

    function getData(idx) {
        $.ajax({
            url: base_url + 'object',
            data: 'model-input=users&key-input=id&value-input=' + idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    loginAlert('Akses tidak sah');
                } else {

                    $("#username-input").val(json.data.object.username);
                    $("#action-input").val('2');
                    $("#value-input").val(json.data.object.id);
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

    function refreshTable(){
        tableData.ajax.url(base_url + '/objects/users').load();
    }

    function CKupdate(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }

    function calcDate(){
        var start = $("#start_date").val();
        var end = $("#finish_date").val();
        var diff = calcDiffDateToMonth(start,end);
        $("#duration").val(diff + ' MONTH');
    }
</script>