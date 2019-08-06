 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $this->config->item('app_name'); ?> | <?php echo (isset($_TITLE))?$_TITLE:'';?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/bootstrap.min.css">
    <!-- Bootstrap tree table -->
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/select2/css/select2.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/selectize/css/selectize.bootstrap3.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/AdminLTE-2.4.9/css/skins/skin-black.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/swal/sweet-alert.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/js/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/fileinput.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/js/plugins/iCheck/all.css">

    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/cms/animate.css/animate.css">


    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        <script>var base_url = '<?php echo base_url();?>';</script>
    </head>
    <body class="hold-transition skin-black sidebar-mini sidebar-collapse">
        <div class="wrapper">
          <header class="main-header">
            <a href="<?php echo base_url('view');?>" class="logo">
              <span class="logo-mini"><b><?php echo $this->config->item('app_logo_mini'); ?></b></span>
              <span class="logo-lg"><b><?php echo $this->config->item('app_logo_lg'); ?></b></span>
          </a>
          <nav class="navbar navbar-static-top" role="navigation">
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo base_url();?>assets/images/logo/logo_sekawan_square.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $this->session->userdata('identity'); ?></span>
                        
                    </a>
                    <ul class="dropdown-menu">
                      <!-- User image -->
                      <li class="user-header">
                          <img src="<?php echo base_url();?>assets/images/logo/logo_sekawan_square.png" class="img-circle" alt="User Image">
                          <p>
                              <?php echo $this->session->userdata('identity'); ?>
                              <small><?php echo $this->session->userdata('_LEVEL');?></small>
                          </p>
                      </li>
                      <li class="user-footer">
                        <div class="pull-right">
                          <a href="<?php echo site_url('auth/logout');?>" class="btn btn-default btn-flat" >Sign out</a>
                      </div>
                      <div class="pull-left">
                          <a class="btn btn-default btn-flat" href="#" onclick="loadContent('<?php echo site_url('view/_profil');?>'); return false;">My Profile</a>
                      </div>
                  </li>
              </ul>
          </li>
      </ul>
  </div>
</nav>
</header>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo base_url();?>assets/images/logo/logo_sekawan.png" class="img-responsive" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('_NAME'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
  </div>
  <!-- /.search form -->
  <ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <?php echo (isset($_MENU))?$_MENU:'';	//menu current user?>
</ul>
</section>
<!-- /.sidebar -->
</aside>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div id="dinamic-content">
            <?php echo (isset($_CONTENT))?$_CONTENT:'';?>
        </div>
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>assets/cms/js/jQuery-2.1.4.min.js"></script>
<!-- <script src="<?php echo base_url();?>assets/cms/js/jquery-3.3.1.min.js"></script> -->
<!-- <script src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/jquery.min.js"></script> -->
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo base_url();?>assets/cms/js/bootstrap.min.js"></script>
<!-- Bootstrap tree table -->
<!-- <script src="<?php echo base_url();?>assets/cms/bootstrap-treetable/libs/v3/bootstrap.min.js"></script> -->

<script src="<?php echo base_url();?>assets/cms/js/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/plugins/fastclick/fastclick.min.js"></script>

<script src="<?php echo base_url();?>assets/cms/moment/min/moment.min.js"></script>

<script src="<?php echo base_url();?>assets/cms/js/function.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/cms/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url();?>assets/cms/swal/sweet-alert.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/fileinput.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/ckeditor_full/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.metadata.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/jquery.media.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url();?>assets/cms/selectize/js/standalone/selectize.min.js"></script>
<!-- <script src="<?php echo base_url();?>assets/cms/select2/dist/js/select2.full.min.js"></script> -->

<!-- Select2 -->

<!-- InputMask -->
<script src="<?php echo base_url();?>assets/cms/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url();?>assets/cms/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url();?>assets/cms/input-mask/jquery.inputmask.extensions.js"></script>

<script src="<?php echo base_url(); ?>assets/cms/bootstrap-notify/bootstrap-notify.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/app.min.js"></script>
<!-- Sheet JS-->
<script src="<?php echo base_url();?>assets/cms/js/app.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/sheet-js/shim.min.js"></script>
<script src="<?php echo base_url();?>assets/cms/js/sheet-js/xlsx.full.min.js"></script>
<script>
    let statemenu="dashboard";
    $(document).ready(function(){


    //for error keypress space
        $(":input, textarea").keypress(function(e) {
            if (e.keyCode === 0 || e.keyCode === 32) {
                $(this).focus();
            }
        });

        $(".menu").on('click',function(){
            var el = document.getElementById(this.id);
            var oldel = document.getElementById(statemenu);
            oldel.classList.remove('active');
            el.classList.add('active');
            statemenu = this.id;
        });
        
        //for extra js...
        <?php echo (isset($_EXTRA_JS))?$_EXTRA_JS:'';?>
    });

    function goToSomewhere(url){
        window.open(url);
    }
    
</script>
</body>
</html>
