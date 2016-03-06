<?php

session_start();

require_once("../../globals/globals.php");
require_once("../../coreClasses/roundInfo.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$roundInfo = new roundInfo();
$roundInfo->setRoundId($_POST['id']);
$roundInfo->setDateObj(new dateFormat());
$scorecard = $roundInfo->buildScoreCard();
$info = $roundInfo->returnInfoForRound();

//if we have a posted notifid then we know this page is being loaded from a notifications option so makr the notification as confirmed
if ($_POST['notifid']) {
    require_once("../../coreClasses/notificationsController.class.php");
    $nc = new notificationsController();
    $dbArgs['id'] = $_POST['notifid'];
    $nc->confirmNotification($dbArgs);   
}

if (!$info) {
    echo "Woops we do not appear to have this round anymore.";
    die();
}
?>


<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-map-o"></i> Scorecard</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <?php
            echo $scorecard;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Snapshot</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
            <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
            <?php

            $score = $info['score'];
            $putts = $info['putts'];
            $fairways = $info['fairwayspercentage'];
            $greens = $info['greenspercentage'];

            echo<<<EOTABLE
              <table class='table table-bordered table-striped dataTable roundsummaryTable'>
                  <tbody>
                        <tr>
                            <td>Score:</td>
                            <td>$score</td>
                        </tr>
                        <tr>
                            <td>Putts:</td>
                            <td>$putts</td>
                        </tr>
                        <tr>
                            <td>Fairways Hit:</td>
                            <td>$fairways%</td>
                        </tr>
                        <tr>
                            <td>Greens Hit:</td>
                            <td>$greens%</td>
                        </tr>
                  </tbody>
              </table>    
EOTABLE;
?>
              </div>
              <div class="col-md-6 col-xs-12 text-center">

                              <?php

            $puttholedlength = $info['puttholedlength'];
            $upndownpercentage = $info['upndownpercentage'];
            $sandsavepercentage = $info['sandsavepercentage'];
            $greens = $info['greenspercentage'];

            echo<<<EOTABLE
              <table class='table table-bordered table-striped dataTable roundsummaryTable'>
                  <tbody>
                        <tr>
                            <td>Putt Holed Length:</td>
                            <td>$puttholedlength feet</td>
                        </tr>
                        <tr>
                            <td>Up n Downs:</td>
                            <td>$upndownpercentage%</td>
                        </tr>
                        <tr>
                            <td>Sand Saves:</td>
                            <td>$sandsavepercentage%</td>
                        </tr>
                  </tbody>
              </table>    
EOTABLE;
?>
                  
              </div>
            </div>

      </div><!-- /.box -->
    </div>
  </div>
</div>


<!-- BAR CHART -->
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-line-chart"></i> Fairways Hit</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
                  <table class='table table-bordered table-striped dataTable roundsummaryTable' id='fairwayshittable'>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6 col-xs-12 text-center">
                  <canvas id="pieChart" style="height: 260px; width: 521px;" width="521" height="260"></canvas>
              </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>

<!-- BAR CHART -->
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Greens Hit</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
                  <table class='table table-bordered table-striped dataTable roundsummaryTable' id='greenshittable'>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6 col-xs-12 text-center">
                  <canvas id="greenshit" style="height: 260px; width: 521px;" width="521" height="260"></canvas>
              </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bar-chart"></i> Putt Breakdown</h3>
          <div class="box-tools pull-right text-center">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div id='puttbreakdowntable'></div>
          <div class="chart">
            <canvas id="barChart" style="height:230px"></canvas>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>


<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bar-chart"></i> Putt Length Holed</h3>
          <div class="box-tools pull-right text-center">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
              <div class='row'>
              <div class="col-md-6 col-xs-12 text-center">
                  <table class='table table-bordered table-striped dataTable roundsummaryTable' id='puttlenghholedtable'>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              <div class="col-md-6 col-xs-12 text-center">
                  <canvas id="puttlengthholedchart" style="height:230px"></canvas>
              </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>