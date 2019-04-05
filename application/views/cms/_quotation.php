
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
        <!-- <li class="">
            <a data-toggle="tab" href="#data-form-tab" title="Form View">
                <i class="fa fa-edit"></i>
            </a>
        </li> -->
        <li class="pull-left header"><i class="fa fa-file-text"></i>Quotation</li>
        <div id="loading"></div>
    </ul>
    <div class="tab-content">
        <div id="bidang-table-tab" class="tab-pane fade active in">
            <div style="padding-bottom:10px;">
                <button type="button" class="btn btn-success btn-sm" onclick="openWindow(base_url + 'quotation');">Tambah Data</button>
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
                            <th>Inquiry No</th>
                            <th>Project name</th>
                            <th>Qty</th>
                            <th>Customer</th>
                            <th>PIC Marketing</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                            <th>Duration</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>




    $(document).ready(function () {
        // CKEDITOR.replace('alamat-input');
        getDataTable();
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
       
      
    });

    function printQuotation(){
        window.open(base_url + 'report/quotationreport')
    }


    function newForm() {
        loadContent(base_url + "view/_quotation", function () {
            setActiveTab("data-form-tab");
        });
    }

    function getDataTable() {
        if ($.fn.dataTable.isDataTable('#table-data')) {
            tableData = $('#table-data').DataTable();
        } else {
            tableData = $('#table-data').DataTable({
                "ajax": base_url + 'objects/header',
                "columns": [
                   {"data": "no"},
                   {"data": "inquiry_no"},
                   {"data": "project_name"},
                   {"data": "qty"},
                   {"data": "customer"},
                   {"data": "pic_marketing"},
                   {"data": "start_date"},
                   {"data": "finish_date"},
                   {"data": "duration"},
                   {"data": "aksi", "width": "15%"}
               ],
                "ordering": true,
                "deferRender": true,
                "columnDefs" : [
                    {
                        "targets" : 8,
                        "render" : function( data, type, row, meta ){
                            var diff = calcDiffDateToMonth(formatDate(row.start_date), formatDate(row.finish_date))
                            return diff + ' MONTH';
                        },
                    },
                    {"targets" : 9, "className" : "text-center"}
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
            openWindow(base_url + 'quotation/' + $(this).attr('href').substring(1));
            // loadContent(base_url + 'view/_quotation/' + $(this).attr('href').substring(1));
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
                        var page ='_quotation/';
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
            url: base_url + 'get_data_header/'+idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    loginAlert('Akses tidak sah');
                } else {

                    $("#inquiry_no").val(json.data.object.inquiry_no);
                    $("#project_name").val(json.data.object.project_name);
                    $("#customer").val(json.data.object.customer);
                    $("#qty_general").val(json.data.object.qty_general);
                    $("#lot_general").val(json.data.object.lot_general);
                    $("#pic_marketing").val(json.data.object.pic_marketing);
                    $("#start_date").val(json.data.object.start_date);
                    $("#finish_date").val(json.data.object.finish_date);
                    $("#duration").val(json.data.object.duration);
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
                    data: 'model-input=quotation&action-input=3&key-input=id&value-input='+n,
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
        tableData.ajax.url(base_url + '/objects/header').load();
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

    

    function openWindow(url){
        var params = [
            'height='+screen.height,
            'width='+screen.width,
            'fullscreen=yes' // only works in IE, but here for completeness
        ].join(',');
             // and any other options from
             // https://developer.mozilla.org/en/DOM/window.open

        var popup = window.open(url, 'popup_window', params); 
        popup.moveTo(0,0);
    }
</script>
