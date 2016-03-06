<?php
session_start();

if (!$_SESSION['username']) {
    header("location: index.php");   
}

require_once("globals/globals.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Golf Pervs v3.0</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="template/bootstrap/css/bootstrap.min.css">
      <!-- DataTables -->
    <link rel="stylesheet" href="template/plugins/datatables/dataTables.bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="template/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="template/dist/css/skins/skin-blue.min.css">
    
    <!-- Bootstrap date picker -->
    <link rel="stylesheet" href="template/plugins/datepicker/datepicker3.css">
      
    <!-- my styles -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>G</b>P</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Golf</b> Pervs</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Notifications Menu -->
              <li class="dropdown notifications-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-danger" id="numberofnotifications"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <!-- Inner Menu: contains the notifications -->
                    <ul class="menu" id="notifsdropdown">
                    </ul>
                  </li>
                  <li class="footer" id="notifs" data-directory="profile"><a href="#">View all</a></li>
                </ul>
              </li>
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="template/dist/img/avatar5.png" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
                  <?php
                
                    require_once("coreClasses/user.class.php");

                    $user = new user();
                    $user->setUsername($_SESSION['username']);
                    $userInfo = $user->getUserInfoForPageHeader();

                    echo "$userInfo";

                    ?>  
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="template/dist/img/avatar5.png" class="img-circle" alt="User Image">
                    <p>
                      <?php
                        echo "<p>$userInfo</p>";
                      ?>
                        <small>Member since t be developed</small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="#" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="template/dist/img/avatar5.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <?php

                echo "<p>$userInfo</p>";

                ?>
            </div>
          </div>

          <!-- Sidebar Menu -->
            <?php
            require_once ("coreClasses/menu.class.php");

            $menu = new menu();
            echo $menu->generateMenu();
            ?>
            
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Welcome to Golf Pervs!
            <small>Go long or go home</small>
          </h1>
        </section>
        
        <!-- Main content -->
        <section class="content" id='mainContent'>
            
            
            <?php
//print_r($_SERVER);
?>
            
        </section><!-- /.content -->
          
          
          
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Default to the left -->
        <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="http://www.ralphwardlaw.co.uk">Ralph Wardlaw</a>.</strong> All rights reserved.
      </footer>

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <script src="template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="template/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap DatePicker -->
    <script src="template/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/app.min.js"></script>
      <!-- DataTables -->
    <script src="template/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="template/plugins/datatables/dataTables.bootstrap.min.js"></script>
      
    <!-- ChartJS 1.0.1 -->
    <script src="template/plugins/chartjs/Chart.min.js"></script>
      
    <!-- my scripts -->
    <script src="js/gp.js"></script>  
    <script src="js/schedule.js"></script> 
    <script src="js/rounds.js"></script> 
    <script src="js/profile.js"></script>
    <script src="js/stats.js"></script>
    <script src="js/courses.js"></script>
    <script src="js/areaChartOptionsFunction.js"></script>
    <script src="js/puttbreakdownbarchart.js"></script>
    <script src="js/recentroundslinechart.js"></script>
    <script src="js/fairwayshitchart.js"></script>
    <script src="js/greenshitchart.js"></script>
    <script src="js/greenshitoverallchart.js"></script>
    <script src="js/fairwayshitoverallchart.js"></script>  
    <script src="js/puttlengthholedlinechart.js"></script>
    <script src="js/swipe.js"></script>
  </body>
</html>
