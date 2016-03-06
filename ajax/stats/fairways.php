<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/fairways.class.php");
require_once("../../coreClasses/statsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$df = new dateFormat();

if ($_POST['startdate']) {
    $startdate = $df->formatDateToDatabaseOther($_POST['startdate']);   
}

if ($_POST['enddate']) {
    $enddate = $df->formatDateToDatabaseOther($_POST['enddate']);   
}

$fairways = new fairways();
$fairways->setUsername($_SESSION['username']);
$fairways->setLabel("Fairway");
$fairways->setPage("fairways");
$fairways->setStartDate($startdate);
$fairways->setEndDate($enddate);
$fairways->setCompetition($_POST['competition']);
$fairways->setCourse($_POST['course']);
$fairways->setDbObj(new statsController());
$fairways->setDateObj($df);
echo $fairways->getForm();

$course = $fairways->getStatsByCourse();
$competition = $fairways->getStatsByCompetition();
$year = $fairways->getStatsByYear();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Fairway Overview</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
                  <table class='table table-bordered table-striped dataTable' id='fairwayshitoveralltable'>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6 col-xs-12 text-center">
                  <canvas id="fairwayshit" style="height: 260px; width: 521px;" width="521" height="260"></canvas>
              </div>
            </div>
      </div><!-- /.box -->
    </div>
  </div>
</div>


<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Fairways By Course</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Fairways By Competition</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Fairways By Year</h3>
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