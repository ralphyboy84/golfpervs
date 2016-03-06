<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/course.class.php");
require_once("../../coreClasses/courseController.class.php");

$courseid = $_POST['course'];

$course = new course();
$course->setCourseId($_POST['course']);
$info = $course->getCourseInfo();
$rounds = $course->returnAllRoundsPlayedStats();

//course info
$label = $info['label'];
$location = $info['location'];
$website = $info['website'];
$phoneno = $info['phoneno'];

//round stats
$num = $rounds['num'];
$high = $rounds['high'];
$low = $rounds['low'];
$av = $rounds['av'];
$lowinfo = $rounds['lowinfo'];

if ($website) {
	$website = "<a href='http://".$website."' target='_blank'>$website</a>";
}


//we need to check and see if the user has already ranked this course. if so then we want to replace the existing ranking
//we will also want to give the user their review back as well for editing.
$ratingArgs = array (
	'courseid' => $_POST['course'],
	'username' => $_SESSION['username'],
);

$courseDb = new courseController();
$ratingCheck = $courseDb->getUserRatingForCourse($ratingArgs);

if ($ratingCheck['res']) {
	$selectDefault = $ratingCheck['res'][0]['score'];
	$alreadyRanked = 1;
} else {
	$alreadyRanked = 0;
}

$reviewCheck = $courseDb->getUserReviewForCourse($ratingArgs);

if ($reviewCheck['res']) {
	$textDefault = $reviewCheck['res'][0]['review'];
	$alreadyReviewed = 1;
} else {
	$alreadyReviewed = 0;
}


//set up select box for rating score
for ($x=0;$x<=100;$x++) {
	if ($x == $selectDefault) {
		$selected = "selected='selected'";
	} else {
		$selected = "";
	}
	
	$optionArgs[] = "<option value='".$x."' $selected>".$x."</option>";
}

if ($optionArgs) {
	$courseRatingSelect = "<select id='courseRating_select' name='courseRating_select' class='form-control'>".implode ( $optionArgs )."</select>";
}


echo<<<EODIV
<h3>$label, $location</h3>
<h4>Info</h4>
<p>
<ul>
<li>Club Website: $website</li>
<li>Phone Number: $phoneno</li>
</ul>
</p>
<h4>Course Stats</h4>
<p>
<ul>
<li>Number of Rounds played: $num</li>
<li>Highest Score: $high</li>
<li>Lowest Score: $low - $lowinfo</li>
<li>Average Score: $av</li>
</ul>
</p>
<hr />
<h4>Rate $label</h4>
<form class="form-horizontal" role="form" id="enterCourseReviewForm">
	<div id="courseNameForm" class="form-group">
		<label for="selectRating" class="col-sm-2 control-label">Select Rating:</label>
		<div class="col-sm-5">
			$courseRatingSelect
		</div>
	</div> 
	
	<div id="courseNameForm" class="form-group">
		<label for="selectRating" class="col-sm-2 control-label">Enter Review (if you want):</label>
		<div class="col-sm-5">
			<textarea id='courseReview' rows='4' cols='30' class='form-control'>$textDefault</textarea>
		</div>
	</div> 

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button id="addCourseRatingButton" type="button" class="btn btn-primary">Add Rating</button>
		</div>
	</div>
	
	<input type='hidden' id='courseid' value='$courseid' />
	<input type='hidden' id='alreadyRanked' value='$alreadyRanked' />
	<input type='hidden' id='alreadyReviewed' value='$alreadyReviewed' />
</form>
EODIV;

?>