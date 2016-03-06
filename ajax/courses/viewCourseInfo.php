<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/course.class.php");

$course = new course();
$course->setCourseId($_POST['course']);
$info = $course->getCourseInfo();
$rounds = $course->returnAllRoundsPlayedStats();
$rating = $course->getCourseRating();
$ranking = $course->getCourseRankComparison();


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

//rating info
$avCourseRating = $rating['av'];
$avCourseRatingUserNums = $rating['num'];

if ($avCourseRatingUserNums == 1) {
	$userGrammar = "user";
} else {
	$userGrammer = "users";
}

//ranking info
$courseRankingRank = $ranking['ranked'];
$courseRankingTotalCourses = $ranking['total'];

echo<<<EODIV
<hr />
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
<h4>Course Ranking</h4>
<p>
<ul>
	<li>This course is ranked #$courseRankingRank out of $courseRankingTotalCourses rated courses.</li>
	<li>This course has an average rating of $avCourseRating from $avCourseRatingUserNums $userGrammar.</li>
</ul>	
</p>
EODIV;

?>