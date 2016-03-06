<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/scores.class.php");
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

// setting up properties
$scores = new scores();
$scores->setUsername($_SESSION['username']);
$scores->setLabel("Score");
$scores->setPage("scores");
$scores->setStartDate($startdate);
$scores->setEndDate($enddate);
$scores->setCompetition($_POST['competition']);
$scores->setCourse($_POST['course']);
$scores->setDbObj(new statsController());
$scores->setDateObj($df);
$scores->setCompareWith($_POST['friendid']);
$scores->setCompareWithSelect($select);
$scores->setFullFriendName($fullname);

//get the form
echo $scores->getForm();

//do the dirty work
$overall = $scores->getStatsOverall();
$course = $scores->getStatsByCourse();
$competition = $scores->getStatsByCompetition();
$year = $scores->getStatsByYear();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Score Overview</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Scores By Course</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Scores By Competition</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Scores By Year</h3>
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