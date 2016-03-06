<?php

require_once 'mysqlExecutor.class.php';

class statsController extends mysqlExecutor
{
	function returnRounds($username, $startdate, $enddate, $competition, $course)
	{	
        if ($username) {
            $usernamesql = " AND username = '$username' ";   
        }
        
        if ($startdate) {
            $startdatesql = " AND date >= '$startdate' ";   
        }
        
        if ($enddate) {
            $enddatesql = " AND date <= '$enddate' ";   
        }
        
        if ($competition) {
            $competitionsql = " AND competition = '$competition' ";   
        }
        
        if ($course) {
            $coursesql = " AND roundinfo.course = '$course' ";   
        }
        
		$sql=<<<EOSQL
		SELECT *
		FROM roundinfo, scoreinfo, holes
		WHERE roundinfo.roundid = scoreinfo.roundid 
        AND roundinfo.course = holes.course
        AND roundinfo.tees = holes.tees
        AND scoreinfo.hole = holes.hole
        $usernamesql
        $startdatesql
        $enddatesql
        $competitionsql
        $coursesql
        ORDER BY roundinfo.date ASC, scoreinfo.hole ASC
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function returnRoundScoreTotal($username, $startdate, $enddate, $competition, $course)
	{	        
        if ($startdate) {
            $startdatesql = " AND date >= '$startdate' ";   
        }
        
        if ($enddate) {
            $enddatesql = " AND date <= '$enddate' ";   
        }
        
        if ($competition) {
            $competitionsql = " AND competition = '$competition' ";   
        }
        
        if ($course) {
            $coursesql = " AND course = '$course' ";   
        }
        
		$sql=<<<EOSQL
		SELECT *,
        (SELECT SUM(score) FROM roundinfo as r1,scoreinfo WHERE roundinfo.roundid = r1.roundid AND scoreinfo.roundid = r1.roundid) as score,
        (SELECT SUM(par) FROM holes WHERE roundinfo.course = holes.course AND roundinfo.tees = holes.tees) as par
		FROM roundinfo
		WHERE username = '$username' 
        $startdatesql
        $enddatesql
        $competitionsql
        $coursesql
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function returnRoundPuttsTotal($username, $startdate, $enddate, $competition, $course)
	{	        
        if ($startdate) {
            $startdatesql = " AND date >= '$startdate' ";   
        }
        
        if ($enddate) {
            $enddatesql = " AND date <= '$enddate' ";   
        }
        
        if ($competition) {
            $competitionsql = " AND competition = '$competition' ";   
        }
        
        if ($course) {
            $coursesql = " AND course = '$course' ";   
        }
        
		$sql=<<<EOSQL
		SELECT *,
        (SELECT SUM(putts) FROM roundinfo as r1,scoreinfo WHERE roundinfo.roundid = r1.roundid AND scoreinfo.roundid = r1.roundid) as putts
		FROM roundinfo
		WHERE username = '$username' 
        $startdatesql
        $enddatesql
        $competitionsql
        $coursesql
EOSQL;
		return $this->prepareQuery($sql);
	}
}

?>