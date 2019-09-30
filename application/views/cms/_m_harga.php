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
<style>
    /* No wrap text */
    th,
    td {
        white-space: nowrap;
    }

    #table-data { table-layout: fixed; }
</style>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li class="active">
            <a data-toggle="tab" href="#bidang-table-tab" title="Table View">
                <i class="fa fa-table"></i>
            </a>
        </li>
        <li class="pull-left header"><i class="fa fa-dollar"></i>Master Harga</li>
        <div id="loading"></div>
    </ul>
    <div class="tab-content">
        <div id="bidang-table-tab" class="tab-pane fade active in">
            <div style="padding-bottom:10px;">
                <button type="button" class="btn btn-default btn-sm" style="margin-right: 10px;" onclick="refreshTable()"><i class="fa fa-refresh"></i> Refresh</button>
            </div>
            <table id="table-data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Spek</th>
                        <th>Maker</th>
                        <th>Unit</th>
                        <th>Harga</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Spek</th>
                        <th>Maker</th>
                        <th>Unit</th>
                        <th>Harga</th>
                        <th>Remark</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    var tableData, tableSection;
    $(document).ready(function() {
        // CKEDITOR.replace('alamat-input');

        $("#table-data tfoot th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        });
        getDataTable();

        <?php
        if ($param != null) {
            echo 'getData("' . $param . '");';
            echo 'setActiveTab("data-form-tab");';
        }

        ?>

    });

    function getDataTable() {
        if ($.fn.dataTable.isDataTable('#table-data')) {
            tableData = $('#table-data').DataTable();
        } else {
            tableData = $('#table-data').DataTable({
                "ajax": base_url + 'quotation/get_item_code/0',
                "columns": [{
                        'data': 'no'
                    },
                    {
                        'data': 'stcd'
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
                        'data': 'harga'
                    },
                    {
                        'data': 'remark'
                    },
                ],
                "ordering": true,
                "order": [
                    [0, "asc"]
                ],
                "responsive": true,
                "scrollX": true,
                "fnDrawCallback": function(oSettings) {
                    // utilsDataTable();
                }
            });

            tableData.columns().every(function() {
                var that = this;

                $("input", this.footer()).on("keyup change", function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        }
    }

    function refreshTable() {
        tableData.ajax.url(base_url + 'quotation/get_item_code/0').load();
    }
</script>