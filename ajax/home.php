<?php

session_start();
require_once("../globals/globals.php");
require_once("../coreClasses/rounds.class.php");
require_once("../coreClasses/eventsCalendar.class.php");

$rounds = new rounds();
$rounds->setUser($_SESSION['username']);
$rds = $rounds->getRounds();

$numofrounds = $rounds->getNumOfRounds();
$avScore = $rounds->getAverageScore();

$events = new eventsCalendar();
$numofevents = $events->getNumOfFutureEvents();

?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $numofrounds; ?></h3>
          <p>Round<?php if ($numofrounds != 1) { echo "s"; } ?> Added</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-football-outline"></i>
        </div>
        <a href="#" class="small-box-footer homeTop" data-page="roundsummary" data-directory="stats">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green homeTop">
        <div class="inner">
          <h3><?php echo $numofevents; ?></h3>
          <p>Upcoming Event<?php if ($numofevents != 1) { echo "s"; } ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-calendar"></i>
        </div>
        <a href="#" class="small-box-footer homeTop" data-page="schedule" data-directory="schedule">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow homeTop">
        <div class="inner">
          <h3>
          
            <?php
            require_once("../coreClasses/friends.class.php");
            require_once("../coreClasses/friendsController.class.php");

            $fr = new friends();
            $fr->setUserName($_SESSION['username']);
            $fr->setDBObj(new friendsController());
            $fr->setDateObj(new dateFormat());
            echo $fr->getNumberOfFriends();
            ?>
            
          </h3>
          <p>Friends</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="#" class="small-box-footer homeTop" data-page="pervs" data-directory="profile">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red homeTop">
        <div class="inner">
          <h3><?php echo $avScore; ?></h3>
          <p>Average Score</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer homeTop" data-page="scores" data-directory="stats">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
</div>

<div class='row'>
    <div class="col-lg-4 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Recently Active Friends</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php

            echo $fr->getRecentlyActiveFriends();

            ?>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- ./col -->
</div>
