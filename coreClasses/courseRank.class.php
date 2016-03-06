<?php

require_once("course.class.php");

class courseRank extends course
{
	//function to get a courses rankings
	public function getUserCourseRankings()
	{
		$ranks = $this->_getCourseRankComparisonForUserFromDB();
		
		if ($ranks) {
			$count=1;
			foreach ($ranks as $vals) {
				$course = $vals['courseid'];
				$score = $vals['score'];
				
				$row[]=<<<EOROW
				<tr>
					<td>$count</td>
					<td>$course</td>
					<td>$score</td>
				</tr>
EOROW;
				$count++;
			}
			
			if ($row) {
				return "<table id='userCourseRankings' class='table table-bordered table-striped dataTable'><thead><tr><th>Rank</th><th>Course</th><th>Rating</th></tr></thead><tbody>".implode($row)."</tbody></table>";
			}
		}
	}
	
	
	//function to get course ranking info from the db
	private function _getCourseRankComparisonForUserFromDB()
	{
		$dbArgs = array (
			'username' => $this->getUsername(),
		);
		
		$courseDb = new courseController();
		$info = $courseDb->getAllUserRatings($dbArgs);
		
		if ($info['res']) {
			return $info['res'];
		}
	}
	
	

	
	
	//function to get a courses rankings
	public function getCourseRankings()
	{
		$ranks = $this->_getCourseRankComparisonFromDB();
		
		if ($ranks) {
			$count=1;
			foreach ($ranks as $vals) {
				$course = $vals['courseid'];
				$score = $vals['avscore'];
				
				$row[]=<<<EOROW
				<tr>
					<td>$count</td>
					<td>$course</td>
					<td>$score</td>
				</tr>
EOROW;
				$count++;
			}
			
			if ($row) {
				return "<table id='allCourseRankings' class='table table-bordered table-striped dataTable'><thead><tr><th>Rank</th><th>Course</th><th>Rating</th></tr></thead><tbody>".implode($row)."</tbody></table>";
			}
		}
	}
}
?>