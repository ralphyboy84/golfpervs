<?php

require_once("courseController.class.php");

class course
{
	private $_courseid;	
	private $_username;
    private $_tee;
	private $_courseteeinfo;
    
	public function setCourseId($val)
	{
		$this->_courseid = $val;
	}	
	
	public function getCourseId()
	{
		return $this->_courseid;
	}
    
    public function setTee($val)
	{
		$this->_tee = $val;
	}	
	
	public function getTee()
	{
		return $this->_tee;
	}
	
	public function setUsername($val)
	{
		$this->_username = $val;
	}	
	
	public function getUsername()
	{
		return $this->_username;
	}
	
	public function getCourseInfo()
	{		
		$dbArgs = array (
			'name' => $this->getCourseId(),
		);
		
		$courseDb = new courseController();
		$courseInfo = $courseDb->returnCourseInfo($dbArgs);
		
		if ($courseInfo['res']) {
			return $courseInfo['res'][0];
		}
	}
	
	public function getTeeInfo($teeToFind)
	{
		
	}
	
	
	//function to find all tee sets for a course
	public function findAllTees()
	{
		$dbArgs = array (
			'name' => $this->getCourseId(),
		);
		
		$courseDb = new courseController();
		$teeInfo = $courseDb->returnAllTeeInfo($dbArgs);
		
		
	}
	
	
	//find all rounds played for this course
	private function _findAllRoundsPlayed()
	{
		$dbArgs = array (
			'name' => $this->getCourseId(),
		);
		
		$courseDb = new courseController();
		$teeInfo = $courseDb->returnAllRoundsPlayed($dbArgs);
		
		if ($teeInfo['res']) {
			return $teeInfo['res'];
		}
	}
	
	
	//function to format all rounds played stats
	public function returnAllRoundsPlayedStats()
	{
		$info = $this->_findAllRoundsPlayed();
		
		if ($info) {
			foreach ($info as $vals) {
				$array[$vals['roundid']]['username'] = $vals['username'];
				$array[$vals['roundid']]['date'] = $vals['date'];
				$array[$vals['roundid']]['tees'] = $vals['tees'];
				$array[$vals['roundid']]['competition'] = $vals['competition'];
				$array[$vals['roundid']]['score'] = $array[$vals['roundid']]['score'] + $vals['score'];
			}
			
			if ($array) {
				$count=0;
				$runningTotal=0;
				foreach ($array as $key => $vals) {
					$scoreArray[$key] = $vals['score'];
					$runningTotal = $vals['score'] + $runningTotal;
					$count++;
				}
				
				$highVals = $this->_doublemax($scoreArray);
				$high = $highVals['m'];
				
				$lowVals = $this->_doublemin($scoreArray);
				$low = $lowVals['m'];
				$lowindex = $lowVals['i'];
				
				$av = round($runningTotal/$count,2);
			
				return array (
					'high' => $high,
					'num' => $count,
					'low' => $low,
					'lowinfo' => $array[$lowindex]['date'],
					'av' => $av,
				);
			}
		}
	}
	
	
	//function to get us the max num and index
	private function _doublemax($mylist)
	{ 
		$maxvalue=max($mylist); 
		while(list($key,$value)=each($mylist)){ 
			if($value==$maxvalue)$maxindex=$key; 
		} 
		return array("m"=>$maxvalue,"i"=>$maxindex); 
	}
	
	
	//function to get us the moin num and index
	private function _doublemin($mylist)
	{ 
		$minvalue=min($mylist); 
		while(list($key,$value)=each($mylist)){ 
			if($value==$minvalue)$minindex=$key; 
		} 
		return array("m"=>$minvalue,"i"=>$minindex); 
	}
	
	
	//function to get a courses rating
	public function getCourseRating()
	{
		$courseInfo = $this->_getCourseRatingFromDB();
		
		if ($courseInfo) {
			
			$count=0;
			$runningTotal=0;
			
			foreach ($courseInfo as $vals) {
				$runningTotal = $vals['score'] + $runningTotal;
				$count++;
			}
			
			return array (
				'av' => round($runningTotal/$count,2),
				'num' => $count,
			);
		}
	}
	
	
	//function to do the database call on getting course rating
	private function _getCourseRatingFromDB()
	{
		$dbArgs = array (
			'courseid' => $this->getCourseId(),
		);
		
		$courseDb = new courseController();
		$info = $courseDb->returnCourseRating($dbArgs);
		
		if ($info['res']) {
			return $info['res'];
		}
	}
	
	
	//function to get a courses rank in comparison to other courses
	public function getCourseRankComparison()
	{
		$ranks = $this->_getCourseRankComparisonFromDB();
		
		if ($ranks) {
			$count=0;
			foreach ($ranks as $vals) {
				if ($vals['courseid'] == $this->getCourseId()) {
					$ranking = $count+1;
				}
				$count++;
			}
			
			return array (
				'ranked' => $ranking,
				'total' => $count,
			);
		}
	}
	
	
	//function to get course ranking info from the db
	protected function _getCourseRankComparisonFromDB()
	{
		$courseDb = new courseController();
		$info = $courseDb->returnCourseRankingAllCourse();
		
		if ($info['res']) {
			return $info['res'];
		}
	}
    
    public function setCourseTeeInfo($val)
    {
        $this->_courseteeinfo = $val;
    }
    
    public function getCourseTeeInfo()
    {
        if (!$this->_courseteeinfo) {
            $args = array (
                'course' => $this->getCourseID(),
                'tee' => $this->getTee()
            );

            $courseDb = new courseController();
            $info = $courseDb->returnCourseTee($args);
            $this->setCourseTeeInfo($info);            
        }
        
        return $this->_courseteeinfo;
    }
    
    public function getCourseTeeString()
    {
        $info = $this->getCourseTeeInfo();
        
        if ($info['res']) {
            return $info['res'][0]['label']." > ".$info['res'][0]['teelabel'];   
        }
    }
}

?>