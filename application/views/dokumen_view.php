<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZI-WBKWBBM</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
   <!-- <link href="<?php//echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head> 
<body>
    <div class="container">
        <h1 style="font-size:20pt">Monitoring Dokumen Persiapan Menuju ZI-WBKWBBM </h1>

        <h3>KPPN Sumbawa Besar</h3>
        <br />
        <button class="btn btn-success" onclick="add_dokumen()"><i class="glyphicon glyphicon-plus"></i> Tambah Dokumen</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-hover table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Komponen</th>
                    <th>Bagian Komponen</th>
                    <th>Kegiatan</th>
                    <th>Seksi</th>
                    <th>ID Dokumen</th>
                    <th>Nama Dokumen</th>
                    <th>Link File</th>
                    <th style="width:125px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <!--
            <tfoot>
            <tr>
                    <th>Komponen</th>
                    <th>Bagian Komponen</th>
                    <th>Kegiatan</th>
                    <th>Seksi</th>
                    <th>ID Dokumen</th>
                    <th>Nama Dokumen</th>
                    <th>Link File</th>
                    <th>Action</th>
            </tr>
            </tfoot> -->
        </table>
    </div>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('dokumen/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_dokumen()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Dokumen'); // Set Title to Bootstrap modal title
}

function edit_dokumen(idoutput)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('dokumen/ajax_edit/')?>/" + idoutput,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="idkomp"]').val(data.idkomp);
            $('[name="idbagkomp"]').val(data.idbagkomp);
            $('[name="idkeg"]').val(data.idkeg);
            $('[name="idpic"]').val(data.idpic);
            $('[name="idoutput"]').val(data.idoutput);
            $('[name="outputkeg"]').val(data.outputkeg);
            $('[name="filedok"]').val(data.filedok);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Dokumen'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('dokumen/ajax_add')?>";
    } else {
        url = "<?php echo site_url('dokumen/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_dokumen(idoutput)
{
    if(confirm('Yakin akan menghapus data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('dokumen/ajax_delete')?>/"+idoutput,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Dokumen</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                   <!--<input type="hidden" value="" name="idkomp"/> -->
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Komponen</label>
                            <div class="col-md-9">
                                <input name="idkomp" placeholder="Nama Komponen" class="form-control" type="text">
                                 <span class="help-block"></span>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bagian Komponen</label>
                            <div class="col-md-9">
                                <input name="idbagkomp" placeholder="Nama Bagian Komponen" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Kegiatan</label>
                            <div class="col-md-9">
                                <input name="idkeg" placeholder="Nama Kegiatan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Seksi</label>
                            <div class="col-md-9">
                                <input name="idpic" placeholder="nama Subbagian/Seksi" class="form-control"></input>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">ID Dokumen</label>
                            <div class="col-md-9">
                                <input name="idoutput" placeholder="ID Dokumen" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Dokumen</label>
                            <div class="col-md-9">
                                <input name="outputkeg" placeholder="Nama Dokumen" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Link File</label>
                            <div class="col-md-9">
                                <input name="filedok" placeholder="Link File Dokumen" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>