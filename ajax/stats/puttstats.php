<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/putts.class.php");
require_once("../../coreClasses/statsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");
require_once("../../coreClasses/friends.class.php");
require_once("../../coreClasses/friendsController.class.php");
require_once("../../coreClasses/user.class.php");

$uc = new user();
$uc->setUsername($_POST['friendid']);
$fullname = $uc->getUserInfoForPageHeader();

$friend = new friends();
$friend->setDbObj(new friendsController());
$friend->setUserName($_SESSION['username']);
$friend->setSelectDefault($_POST['friendid']);
$select = $friend->getPervsInSelect();

$df = new dateFormat();

if ($_POST['startdate']) {
    $startdate = $df->formatDateToDatabaseOther($_POST['startdate']);   
}

if ($_POST['enddate']) {
    $enddate = $df->formatDateToDatabaseOther($_POST['enddate']);   
}

$putts = new putts();
$putts->setUsername($_SESSION['username']);
$putts->setLabel("Putt");
$putts->setPage("puttstats");
$putts->setStartDate($startdate);
$putts->setEndDate($enddate);
$putts->setCompetition($_POST['competition']);
$putts->setCourse($_POST['course']);
$putts->setDbObj(new statsController());
$putts->setDateObj($df);
$putts->setCompareWith($_POST['friendid']);
$putts->setCompareWithSelect($select);
$putts->setFullFriendName($fullname);

//get the form
echo $putts->getForm();

//do the dirty work
$overall = $putts->getStatsOverall();
$course = $putts->getStatsByCourse();
$competition = $putts->getStatsByCompetition();
$year = $putts->getStatsByYear();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Putt Overview</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php echo $overall; ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>


<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Putts By Course</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php
            echo $course;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Putts By Competition</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php
            echo $competition;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Putts By Year</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php
            echo $year;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>