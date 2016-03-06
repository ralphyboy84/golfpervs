<?php

session_start();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Golf Pervs</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="template/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="template/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="template/plugins/iCheck/square/blue.css">
    <!-- my styles -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page bgimage">
    <div class="login-box">
      <div class="login-logo">
        <b>Golf</b> Pervs
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
          
            <?php
            if ($_SESSION['loginFailed']) {
                echo '<p class="login-box-msg">Error logging into Golf Pervs!</p>';
            }
            ?>
          
        <form action="login.php" method="post">
          <div class="form-group has-feedback">
            <input id="inputUsername" name="inputUsername" type="text" class="form-control" placeholder="Username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input id="inputPassword" name="inputPassword" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="#">I forgot my password</a><br>

      </div><!-- /.login-box-body -->
        
        <br /><br />
        
      <div class="login-box-body">
        <p class="login-box-msg">New members sign up here!</p>  
          
          <?php
            if ($_SESSION['registerFailed']) {
                echo '<p class="login-box-msg">The username you have chosen has already been selected.</p>';
            }
            ?>
        <form id="registerform" method="post" action="register.php">
          <div class="form-group has-feedback" id="inputUsernameRegisterRow">
            <input id="inputUsernameRegister" name="inputUsernameRegister" type="text" class="form-control" placeholder="Username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="inputForenameRow">
            <input id="inputForename" name="inputForename" type="text" class="form-control" placeholder="Forename">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="inputSurnameRow">
            <input id="inputSurname" name="inputSurname" type="text" class="form-control" placeholder="Surname">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="inputEmailRow">
            <input id="inputEmail" name="inputEmail" type="text" class="form-control" placeholder="Email Address">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="inputPasswordRegisterRow">
            <input id="inputPasswordRegister" name="inputPasswordRegister" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" id="confirmPasswordRow">
            <input id="confirmPassword" name="confirmPassword" type="password" class="form-control" placeholder="Confirm Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <div id='confirmPasswordDiv'>
                <br />
                <div class="alert alert-danger alert-dismissible">
                    <h4><i class="icon fa fa-info"></i> Alert!</h4>
                    Passwords do not match!
                </div>
            </div>
              
            <div id='showErrorsDiv'>
                <br />
                <div class="alert alert-danger alert-dismissible">
                    <h4><i class="icon fa fa-info"></i> Alert!</h4>
                    The fields above in red have not been filled in!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-offset-8 col-xs-4">
              <button type="button" class="btn btn-primary btn-block btn-flat" id="registerButton">Register</button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="template/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="template/plugins/iCheck/icheck.min.js"></script>
      
    <script src="js/index.js"></script>
  </body>
</html>
