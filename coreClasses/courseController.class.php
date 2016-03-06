<?php

require_once 'mysqlExecutor.class.php';

class courseController extends mysqlExecutor
{
	function returnCourseInfo($args)
	{
		$name = $args['name'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM course
		WHERE name = '$name'
EOSQL;
		return $this->prepareQuery($sql);
	}	
	
	
	function returnTeeInfo($args)
	{
		$course = $args['course'];
		$tee = $args['tee'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM tees
		WHERE course = '$course'
		AND tee = '$tee'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	function returnTeeInfoForCourse($args)
	{
		$course = $args['course'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM tees
		WHERE course = '$course'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	function returnHolesForCourseAndTee($args)
	{
		$course = $args['course'];
		$tees = $args['tees'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM holes
		WHERE course = '$course'
		AND tees = '$tees'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	function returnSingleHoleForCourseAndTee($args)
	{
		$course = $args['course'];
		$tees = $args['tees'];
		$hole = $args['hole'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM holes
		WHERE course = '$course'
		AND tees = '$tees'
		AND hole = '$hole'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	function returnAllRoundsPlayed($args)
	{
		$name = $args['name'];
	
		$sql=<<<EOSQL
		SELECT *
		FROM roundinfo, scoreinfo
		WHERE roundinfo.roundid = scoreinfo.roundid
		AND roundinfo.course = '$name'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	//function to insert a course rating
	function insertUserCourseRating ( $args )
	{
		$sql = " INSERT into usercourserating set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '".mysql_real_escape_string($vals, $this->db_connect())."' ";
			}
		}
		
		$sql.= implode ( "," , $params  ) .", dateadded = now() ";
		
		return $this->insertQuery($sql);
	}
	
	
	//function to insert a course review
	function insertUserCourseReview ( $args )
	{
		$sql = " INSERT into usercoursereview set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '".mysql_real_escape_string($vals, $this->db_connect())."' ";
			}
		}
		
		$sql.= implode ( "," , $params  ) .", dateadded = now() ";
		
		return $this->insertQuery($sql);
	}
	
	
	//function to get a user rating for a course
	function getUserRatingForCourse($args)
	{
		$courseid = $args['courseid'];
		$username = $args['username'];
	
		$sql=<<<EOSQL
		SELECT *
		FROM usercourserating
		WHERE courseid = '$courseid'
		AND username = '$username'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	//function to get a user rating for a course
	function getAllUserRatings($args)
	{
		$username = $args['username'];
	
		$sql=<<<EOSQL
		SELECT *
		FROM usercourserating
		WHERE username = '$username'
		ORDER BY score DESC
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	//function to get a users review for a course
	function getUserReviewForCourse($args)
	{
		$courseid = $args['courseid'];
		$username = $args['username'];
	
		$sql=<<<EOSQL
		SELECT *
		FROM usercoursereview
		WHERE courseid = '$courseid'
		AND username = '$username'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	//function to update a course ranking
	function updateUserCourseRating($args)
	{
		$score = $args['score'];
		$username = $args['username'];
		$courseid = $args['courseid'];
		
		$sql = " UPDATE usercourserating SET dateadded = now(), score='".$score."' WHERE username = '$username' AND courseid = '$courseid' ";

		return $this->updateQuery($sql);
	}
	
	
	//function to update a course review
	function updateUserCourseReview($args)
	{
		$review = $args['review'];
		$username = $args['username'];
		$courseid = $args['courseid'];
		
		$sql = " UPDATE usercoursereview SET dateadded = now(), review='".$review."' WHERE username = '$username' AND courseid = '$courseid' ";

		return $this->updateQuery($sql);
	}
	
	
	//function to get a course rating
	function returnCourseRating($args)
	{
		$courseid = $args['courseid'];
	
		$sql=<<<EOSQL
		SELECT *
		FROM usercourserating
		WHERE courseid = '$courseid'
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	//function get all courses rankings
	function returnCourseRankingAllCourse()
	{
		$sql=<<<EOSQL
		SELECT AVG(score) as avscore, courseid
		FROM usercourserating
		GROUP BY courseid
		ORDER BY avscore DESC
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	//function to add a hole
	function insertHole ( $args )
	{
		$sql = " INSERT into holes set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
	}
	
	
	//function to add a tee
	function insertTee ( $args )
	{
		$sql = " INSERT into tees set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
	}
	
	
	//function to add a course
	function insertCourse ( $args )
	{
		$sql = " INSERT into course set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
	}
	
	//function to update a course
	function updateCourse($args)
	{
		$sql = "UPDATE course SET ";
		
		if ($args) {
			foreach ($args as $key => $val) {
				if ($val) {
					$sqlArgs[] = " $key = '".mysql_real_escape_string($val, $this->db_connect())."' ";
				}
			}
			
			if ($sqlArgs) {
				$sql.= implode (" , ", $sqlArgs);
			}
		}
		
		$sql.= " WHERE name = '".$args['name']. "' ";

		return $this->updateQuery($sql);
	}
	
	//function to update a hole
	function updateHole($args)
	{

		$sql = "UPDATE holes SET ";
		
		if ($args) {
			foreach ($args as $key => $val) {
				if ($val) {
					$sqlArgs[] = " $key = '".mysql_real_escape_string($val, $this->db_connect())."' ";
				}
			}
			
			if ($sqlArgs) {
				$sql.= implode (" , ", $sqlArgs);
			}
		}
		
		$sql.= " WHERE course = '".$args['course']. "' AND tees='".$args['tees']."' AND hole='".$args['hole']."' ";

		return $this->updateQuery($sql);
	}
	
	//function to update a tee
	function updateTee($args)
	{

		$sql = "UPDATE tees SET ";
		
		if ($args) {
			foreach ($args as $key => $val) {
				if ($val) {
					$sqlArgs[] = " $key = '".mysql_real_escape_string($val, $this->db_connect())."' ";
				}
			}
			
			if ($sqlArgs) {
				$sql.= implode (" , ", $sqlArgs);
			}
		}
		
		$sql.= " WHERE course = '".$args['course']. "' AND tee='".$args['tee']."' ";

		return $this->updateQuery($sql);
	}
	
    function returnCourseTee($args)
    {
        $course = $args['course'];
        $tee = $args['tee'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM course, tees
        WHERE course.name = tees.course
        AND course.name = '$course'
        AND tees.tee = '$tee'
EOSQL;
        return $this->prepareQuery($sql);
    }
    
    function returnCourseTeeHole($args)
    {
        $course = $args['course'];
        $tee = $args['tee'];
        $hole = $args['hole'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM holes
        WHERE course = '$course'
        AND tees = '$tee'
        AND hole = '$hole'
EOSQL;
        return $this->prepareQuery($sql);
    }
}
?>