<?php

require_once 'mysqlExecutor.class.php';

class roundsController extends mysqlExecutor
{
	function returnUserRounds($args)
	{
		$username = $args['username']; 
		
		if ($args['year']) {
			$dateSql = " AND roundinfo.date like '".$args['year']."%' ";
		}
		
		$sql=<<<EOSQL
		SELECT roundinfo.roundid, 
        roundinfo.date, 
        course.label, 
        course.location, 
        tees.teelabel, 
        roundinfo.competition, 
        roundinfo.css,
        (SELECT SUM(score) FROM roundinfo as r1,scoreinfo WHERE roundinfo.roundid = r1.roundid AND scoreinfo.roundid = r1.roundid) as score
		FROM roundinfo, course, tees
		WHERE username = '$username'
		AND roundinfo.course = course.name
		AND roundinfo.tees = tees.tee
		AND course.name = tees.course
		$dateSql
		ORDER BY date DESC
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	function returnRoundDetails($args)
	{
		$roundid = $args['roundid'];
		
		$sql=<<<EOSQL
		SELECT *, holes.par as holepar
		FROM roundinfo, scoreinfo, holes, tees, course
		WHERE roundinfo.roundid = scoreinfo.roundid
		AND roundinfo.roundid = '$roundid'
        AND roundinfo.tees = holes.tees
        AND roundinfo.course = holes.course
        AND scoreinfo.hole = holes.hole
        AND roundinfo.course = course.name
        AND roundinfo.tees = tees.tee
        AND roundinfo.course = tees.course
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function returnSimpleRoundDetails($args)
	{
		$roundid = $args['roundid'];
        $username = $args['username'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM roundinfo
		WHERE roundinfo.roundid = '$roundid'
        AND username = '$username'
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function returnTempRoundDetails($args)
	{
		$roundid = $args['roundid'];
		
		$sql=<<<EOSQL
		SELECT *, holes.par as holepar
		FROM temproundinfo, tempscoreinfo, holes, tees, course
		WHERE temproundinfo.roundid = tempscoreinfo.roundid
		AND temproundinfo.roundid = '$roundid'
        AND temproundinfo.tees = holes.tees
        AND temproundinfo.course = holes.course
        AND tempscoreinfo.hole = holes.hole
        AND temproundinfo.course = course.name
        AND temproundinfo.tees = tees.tee
        AND temproundinfo.course = tees.course
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function insertTempRound($args)
    {
        $sql = " INSERT into temproundinfo set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '".mysql_real_escape_string($vals, $this->db_connect())."' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
        
    }
    
    function insertTempHole($args)
    {
        $sql = " INSERT into tempscoreinfo set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '".mysql_real_escape_string($vals, $this->db_connect())."' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
        
    }
    
    function checkForTempRound($args)
    {
        $username = $args['username'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM temproundinfo
        WHERE username = '$username'
EOSQL;
        return $this->prepareQuery($sql);   
    }
    
    function checkForTempRoundByRoundId($args)
    {
        $roundid = $args['roundid'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM temproundinfo
        WHERE roundid = '$roundid'
EOSQL;
        return $this->prepareQuery($sql);   
    }
    
    function getTempHoleForRound($args)
    {
        $roundid = $args['roundid'];
        $hole = $args['hole'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM tempscoreinfo
        WHERE roundid = '$roundid'
        AND hole = '$hole'
EOSQL;
        return $this->prepareQuery($sql);   
    }
    
    function getHoleForRound($args)
    {
        $roundid = $args['roundid'];
        $hole = $args['hole'];
        
        $sql=<<<EOSQL
        SELECT *
        FROM scoreinfo
        WHERE roundid = '$roundid'
        AND hole = '$hole'
EOSQL;
        return $this->prepareQuery($sql);   
    }
    
    function deleteRound($args)
    {
        $sql = "DELETE FROM roundinfo, scoreinfo USING roundinfo, scoreinfo WHERE roundinfo.roundid = scoreinfo.roundid AND roundinfo.roundid = '".$args['roundid']."' AND username = '".$args['username']."' ";

        return $this->deleteQuery($sql);
    }
    
    function deleteTempRoundInfo($args)
    {
        $sql = "DELETE FROM temproundinfo WHERE roundid = '".$args['roundid']."' AND username = '".$args['username']."' ";

        return $this->deleteQuery($sql);
    }
    
    function deleteTempScoreInfo($args)
    {
        $sql = "DELETE FROM tempscoreinfo WHERE roundid = '".$args['roundid']."' ";
        
        return $this->deleteQuery($sql);
    }
    
    function updateTempScoreInfo($args)
    {
        $sql = "UPDATE tempscoreinfo SET 
                score = '".$args['score']."', 
                fairway = '".$args['fairway']."', 
                fmissed = '".$args['fmissed']."', 
                green = '".$args['green']."', 
                gmissed = '".$args['gmissed']."', 
                upndown = '".$args['upndown']."', 
                sandsave = '".$args['sandsave']."', 
                putts = '".$args['putts']."',
                puttholedlength = '".$args['puttholedlength']."' 
                WHERE roundid = '".$args['roundid']."' AND hole = '".$args['hole']."' ";

        return $this->updateQuery($sql);
    }
    
    function updateScoreInfo($args)
    {
        $sql = "UPDATE scoreinfo SET 
                score = '".$args['score']."', 
                fairway = '".$args['fairway']."', 
                fmissed = '".$args['fmissed']."', 
                green = '".$args['green']."', 
                gmissed = '".$args['gmissed']."', 
                upndown = '".$args['upndown']."', 
                sandsave = '".$args['sandsave']."', 
                putts = '".$args['putts']."',
                puttholedlength = '".$args['puttholedlength']."' 
                WHERE roundid = '".$args['roundid']."' AND hole = '".$args['hole']."' ";

        return $this->updateQuery($sql);
    }
    
    function completeTempRoundInfo($args)
    {
        $roundid = $args['roundid'];
        $username = $args['username'];
        
        $sql=<<<EOSQL
        INSERT INTO roundinfo (username, date, course, tees, css, conditions, time, comment, competition, handicap)
        SELECT username, date, course, tees, css, conditions, time, comment, competition, handicap
        FROM temproundinfo
        WHERE temproundinfo.roundid = '$roundid'
        AND username = '$username'
EOSQL;
        return $this->insertQuery($sql);
    }
    
    function completeTempScoreInfo($args)
    {
        $roundid = $args['roundid'];
        $newroundid = $args['newroundid'];
        
        $sql=<<<EOSQL
        INSERT INTO scoreinfo (roundid, hole, score, fairway, fmissed, green, gmissed, upndown, sandsave, putts, puttholedlength)
        SELECT $newroundid, hole, score, fairway, fmissed, green, gmissed, upndown, sandsave, putts, puttholedlength
        FROM tempscoreinfo
        WHERE tempscoreinfo.roundid = '$roundid'
EOSQL;
        return $this->insertQuery($sql);   
    }
}

?>