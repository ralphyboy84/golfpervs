<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseRank.class.php");

$courseRank = new courseRank();
$allCourseRankTable = $courseRank->getCourseRankings();

$userCourseRank = new courseRank();
$userCourseRank->setUsername($_SESSION['username']);
$userCourseRankTable = $userCourseRank->getUserCourseRankings();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tree"></i> Course Rankings</h3>
        </div>
        <div class="box-body">
            <div class="panel-body">			
				<ul class="nav nav-tabs">
					<li class="active"><a href="#userankcourse" data-toggle="tab">Your Rankings</a></li>
					<li><a href="#allrankcourses" data-toggle="tab">All User Rankings</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="userankcourse">
						<?php echo $userCourseRankTable; ?>
					</div>
					
					<div class="tab-pane" id="allrankcourses">
						<?php echo $allCourseRankTable; ?>
					</div>
				</div>
			</div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>