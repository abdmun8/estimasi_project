<?php
//default value
$id = null;
if ($param != null) {
    $groups = $this->model->getRecord(array(
        'table' => 'groups', 'where' => array('id' => $param)
        ));
    if ($groups) {
        $id  = $groups->id;
    }
}

$atasan = $this->db->select('id_personalia,nama', false)->get('personal')->result();
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
        <li class="pull-left header"><i class="fa fa-file-text"></i>Data User Groups</li>
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
                        <th>Nama Group</th>
                        <th>Deskripsi</th>
                        <th>Group leader</th>
                        <th>Active</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="data-form-tab" class="tab-pane fade">
            <form class="form-horizontal" role="form" id="data-form">
                <div class="form-group">
                    <label for="name-input" class="col-md-3 control-label">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="name-input" name="name-input" placeholder="Name" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="description-input" class="col-md-3 control-label">Description</label>
                    <div class="col-md-9">
                       <textarea class="form-control" id="description-input" name="description-input"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_leader-input" class="col-md-3 control-label">Group Leader</label>
                    <div class="col-md-9">
                        <select class="form-control" name="group_leader-input" id="group_leader-input">
                            <option value="" selected="selected"> - Pilih - </option>
                            <?php foreach( $atasan as $key => $v ): ?>
                            <option value="<?=$v->id_personalia; ?>"><?=$v->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="active-input" class="col-md-3 control-label">Status</label>
                    <div class="col-md-9">
                        <select class="form-control" name="active-input" id="active-input">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                        <input type="hidden" id="model-input" name="model-input" value="groups" >
                        <input type="hidden" id="action-input" name="action-input" value="1" >
                        <input type="hidden" id="key-input" name="key-input" value="id" >
                        <input type="hidden" id="value-input" name="value-input" value="0" >
                        <button type="button" id="btn-save" class="btn btn-success"  onclick="saving(); return false;"><i class="fa fa-save"></i> Save</button>
                        <button type="reset" class="btn btn-default" onclick="setActiveTab('bidang-table-tab');"><i class="fa fa-undo"></i> Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // CKEDITOR.replace('alamat-input');
        getBidang();
        <?php
        if($param != null) {
            echo 'getData("'. $param .'");';
            echo 'setActiveTab("data-form-tab");';
        }
        ?>

        sel = $("#group_leader-input").selectize();
        leader = sel[0].selectize;

      
 });
    function newForm() {
        loadContent(base_url + "view/_user_groups", function () {
            setActiveTab("data-form-tab");
        });
    }

    function getBidang() {
        if ($.fn.dataTable.isDataTable('#table-data')) {
            tableData = $('#table-data').DataTable();
        } else {
            tableData = $('#table-data').DataTable({
                "ajax": base_url + 'objects/groups',
                "columns": [
                   {"data": "no"},
                   {"data": "name"},
                   {"data": "description"},
                   {"data": "nama_group_leader"},
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
            loadContent(base_url + 'view/_user_groups/' + $(this).attr('href').substring(1));
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
                        var page ='_user_groups/';
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
            data: 'model-input=groups&key-input=id&value-input=' + idx,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(json) {
                if (json.data.code === 0) {
                    loginAlert('Akses tidak sah');
                } else {

                    $("#name-input").val(json.data.object.name);
                    $("#description-input").val(json.data.object.description);
                    leader.setValue(json.data.object.group_leader);
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
                    data: 'model-input=groups&action-input=3&key-input=id&value-input='+n,
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
</script>