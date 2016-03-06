<?php

require_once ("roundsController.class.php");
require_once ("dateFormat.class.php");
require_once ("roundInfo.class.php");

class rounds
{
	private $_year;
	private $_user;
    private $_rounds;
	
	public function setYear($val)
	{
		$this->_year = $val;
	}
	
	public function getYear()
	{
		return $this->_year;
	}
	
	public function setUser($val)
	{
		$this->_user = $val;
	}
	
	public function getUser()
	{
		return $this->_user;
	}
    
    private function setRounds($val)
    {
        $this->_rounds = $val;   
    }
	
	private function _getAllRoundsFromDB()
	{
		$dbArgs = array (
			'username' => $this->getUser(),
			'year' => $this->getYear(),
		);
	
		$db = new roundsController();
		return $db->returnUserRounds($dbArgs);
	}
	
	public function getRoundsForSelectBox()
	{
		$rounds = $this->getRounds();
		
		if ($rounds['res']) {
			foreach ($rounds['res'] as $vals) {
				$dateObj = new dateFormat($vals);
				$date = $dateObj->formatDateFromDatabase();
				$optionArgs[] = "<option value='".$vals['roundid']."'>".$date." - ".$vals['label'].", ".$vals['location']." - ".$vals['teelabel']." tees</option>";
			}
			return "<select id='selectRound' class='form-control'>".implode($optionArgs)."</select>";
		}
	}
	
	public function getRoundsForTable()
	{
		$rounds = $this->getRounds();
		
		if ($rounds['res']) {
			foreach ($rounds['res'] as $vals) {
				$dateObj = new dateFormat($vals);
				$date = $dateObj->formatDateFromDatabase();
				$course = $vals['label'].", ".$vals['location'];
				$competition = $vals['competition'];
				$ss = $vals['css'];
				
				$roundObj = new roundInfo();
				$roundObj->setRoundId($vals['roundid']);
				$scoreArgs = $roundObj->getScoreForRound();
				$score = $scoreArgs['score'];
                
                $roundid = $vals['roundid'];
                $olddate = $vals['date'];
				
				$row[] =<<<EOROW
				<tr id='$roundid'>
					<td><span class='hiddendatecol'>$olddate</span>$date</td>
					<td>$course</td>
					<td>$competition</td>
					<td>$ss</td>
					<td>$score</td>
				</tr>
EOROW;
			}
			return "<table id='example2' class='table table-bordered table-striped dataTable'><thead><tr><th>Date</th><th>Course</th><th>Competition</th><th>SS</th><th>Score</th></tr><tbody>".implode($row)."</tbody></table>";
		} else {
            return "No rounds found.";   
        }
	}
    
    
    public function getRoundsForEditRoundTable()
	{
		$rounds = $this->getRounds();
		
		if ($rounds['res']) {
			foreach ($rounds['res'] as $vals) {
				$dateObj = new dateFormat($vals);
				$date = $dateObj->formatDateFromDatabase();
				$course = $vals['label'].", ".$vals['location'];
				$competition = $vals['competition'];
				$ss = $vals['css'];
				
				$roundObj = new roundInfo();
				$roundObj->setRoundId($vals['roundid']);
				$scoreArgs = $roundObj->getScoreForRound();
				$score = $scoreArgs['score'];
                
                $roundid = $vals['roundid'];
                $olddate = $vals['date'];
				
				$row[] =<<<EOROW
				<tr>
					<td><span class='hiddendatecol'>$olddate</span>$date</td>
					<td>$course</td>
					<td>$competition</td>
					<td>$score</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary">Actions</button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li id="editRoundButtonTable" data-id="$roundid"><a href="#">Edit Round</a></li>
                                <li id="deleteRoundButtonTable" data-id="$roundid"><a href="#">Delete Round</a></li>
                            </ul>
                        </div>
                    </td>
				</tr>
EOROW;
			}
			return "<table id='editroundtable' class='table table-bordered table-striped dataTable'><thead><tr><th>Date</th><th>Course</th><th>Competition</th><th>Score</th><th>&nbsp;</th></tr><tbody>".implode($row)."</tbody></table>";
		} else {
            return "No rounds found.";   
        }
	}
    
    public function getRounds()
    {
        if (!$this->_rounds) {
            $rounds = $this->_getAllRoundsFromDB();
            $this->setRounds($rounds);
        }
        
        return $this->_rounds;
    }
    
    public function getNumOfRounds()
    {
        $rds = $this->getRounds();
        
        if ($rds['res']) {
            return sizeof($rds['res']); 
        } else {
            return "0";   
        }
    }
    
    public function getAverageScore()
    {
        $rounds = $this->getRounds();
        
        if ($rounds['res']) {
            foreach ($rounds['res'] as $round) {
                $totalscore = $round['score'] + $totalscore;
                $x++;
            }
            return round($totalscore/$x,2);
        }
        
        return "No rounds";
    }
}

?>