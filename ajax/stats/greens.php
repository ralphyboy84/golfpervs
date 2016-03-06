<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/greens.class.php");
require_once("../../coreClasses/statsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$df = new dateFormat();

if ($_POST['startdate']) {
    $startdate = $df->formatDateToDatabaseOther($_POST['startdate']);   
}

if ($_POST['enddate']) {
    $enddate = $df->formatDateToDatabaseOther($_POST['enddate']);   
}

$girs = new greens();
$girs->setUsername($_SESSION['username']);
$girs->setLabel("GIR");
$girs->setPage("greens");
$girs->setStartDate($startdate);
$girs->setEndDate($enddate);
$girs->setCompetition($_POST['competition']);
$girs->setCourse($_POST['course']);
$girs->setDbObj(new statsController());
$girs->setDateObj($df);
echo $girs->getForm();

$course = $girs->getStatsByCourse();
$competition = $girs->getStatsByCompetition();
$year = $girs->getStatsByYear();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> GIR Overview</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
                  <table class='table table-bordered table-striped dataTable' id='greenshitoveralltable'>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6 col-xs-12 text-center">
                  <canvas id="greenshit" style="height: 260px; width: 521px;" width="521" height="260"></canvas>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> GIR By Course</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> GIR By Competition</h3>
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
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> GIR By Year</h3>
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