
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sekawan | Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" type="image/png" href="http://server/sis/images/logo.png">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/font-awesome/css/font-awesome.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/cms/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?=base_url();?>assets/cms/icheck/skins/square/blue.css">
    </head>
    
    <body class="hold-transition login-page">

        <div class="login-box">
            <div id="infoMessage" <?php if(!$this->session->flashdata()) echo 'style="display:none;"';?>>
                <div class="alert alert-danger" role="alert"><?php echo $message;?></div>                
            </div>
            <div class="login-logo">

            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <div align="center"><img src="<?=base_url();?>assets/images/logo/logo_sekawan.png" class="img-rounded" width="150" height="150"></div>
                <p></p>
                <p class="login-box-msg">Sign in to start your session</p>
                <p align="center" style="color: red;"></p>

                <form action="login" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>    
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                          <div class="checkbox icheck">
                            <label>
                              <input type="checkbox" name="remember" id="remember" type="checkbox"> Remember Me
                            </label>
                          </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                          <button type="submit" name="submit" value="Login" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>

                    <!-- <a href="#">I forgot my password</a><br> -->
                    <br>
                </form>
                <div class="text-center">
                  <small>Copyright © Sekawan <?=date('Y');?></small>
                </div>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 3 -->
        <script src="<?=base_url();?>assets/cms/js/jquery-3.3.1.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?=base_url();?>assets/cms/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?=base_url();?>assets/cms/icheck/icheck.min.js"></script>
        <script>
          $(function () {
            $('input').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%' /* optional */
            });
          });
        </script>
    </body>
</html>
