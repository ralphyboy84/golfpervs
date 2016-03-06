<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseController.class.php");

if ($_POST['courseid']) {
	$courseDb = new courseController();
	
	if ($_POST['courseRating']) {
		$ratingArgs = array (
			'username' => $_SESSION['username'],
			'courseid' => $_POST['courseid'],
			'score' => $_POST['courseRating'],
		);
		
		//if we have already ranked this course then update the existing value
		if ($_POST['alreadyRanked']) {
			$courseDb->updateUserCourseRating($ratingArgs);
		} else {
			$courseDb->insertUserCourseRating($ratingArgs);
		}
	}
	
	if ($_POST['review']) {
		$reviewArgs = array (
			'username' => $_SESSION['username'],
			'courseid' => $_POST['courseid'],
			'review' => $_POST['review'],
		);
		
		//if we have already ranked this course then update the existing value
		if ($_POST['alreadyReviewed']) {
			$courseDb->updateUserCourseReview($reviewArgs);
		} else {
			$courseDb->insertUserCourseReview($reviewArgs);
		}
	}
}

echo "Your rating has been submitted. Thanks!";
?>