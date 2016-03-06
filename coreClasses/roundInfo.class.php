<?php

require_once ("roundsController.class.php");

class roundInfo
{
	private $_roundid;
	private $_roundInfo;
    private $_tempRoundInfo;
    private $_dateobj;
    
    public function setDateObj($val)
    {
        $this->_dateobj = $val;
    }   
    
    public function getDateObj()
    {
        return $this->_dateobj;   
    }
	
	public function setRoundId($val)
	{
		$this->_roundid = $val;
	}
	
	public function getRoundId()
	{
		return $this->_roundid;
	}
	
	public function setRoundInfo($array)
	{
		$this->_roundInfo = $array;
	}
	
	public function getRoundInfo()
	{
		if (!$this->_roundInfo){
			$info = $this->_getRoundInfoFromDB();
			$this->setRoundInfo($info['res']);
		}
		
		return $this->_roundInfo;
	}
    
    public function setTempRoundInfo($array)
	{
		$this->_tempRoundInfo = $array;
	}
	
	public function getTempRoundInfo()
	{
		if (!$this->_tempRoundInfo){
			$info = $this->_getTempRoundInfoFromDB();
			$this->setTempRoundInfo($info['res']);
		}
		
		return $this->_tempRoundInfo;
	}
	
	public function getScoreForRound()
	{
		return array (
			'score' => $this->_getValueForRound("score"),
		);
	}
    
    public function getPuttsForRound()
	{	
		return array (
			'putts' => $this->_getValueForRound("putts"),
		);
	}
    
    public function getPuttsHoledForRound()
	{	
		return array (
			'puttholedlength' => $this->_getValueForRound("puttholedlength"),
		);
	}
    
    public function getFairwaysForRound()
    {
        return $this->_getConditionalValue("fairway");   
    }
    
    public function getGreensForRound()
    {
        return $this->_getConditionalValue("green");   
    }
    
    public function getUpNDownsForRound()
    {
        return $this->_getConditionalValue("upndown");   
    }
    
    public function getSandSavesForRound()
    {
        return $this->_getConditionalValue("sandsave");   
    }
    
    private function _getValueForRound($value)
    {
        $roundInfo = $this->getRoundInfo();
		
		if ($roundInfo) {
			foreach ($roundInfo as $vals) {
				$val = $vals[$value] + $val;
			}
		}
		
		return $val;
    }
    
    private function _getConditionalValue($value)
    {
        $hit = 0;
        $c = 0;
        $le = 0;
        $r = 0;
        $s = 0;
        $lo = 0;
        $miss = 0;
        $na = 0;
        
        $roundInfo = $this->getRoundInfo();
		
		if ($roundInfo) {
			foreach ($roundInfo as $vals) {
                if ($vals[$value] == 2) {
                    $hit++;   
                } else if ($vals[$value] == 1) {
                    if ($value == "fairway") {
                        if ($vals['fmissed'] == 1) {
                            $le++;   
                        } else if ($vals['fmissed'] == 2) {
                            $r++;   
                        } else if ($vals['fmissed'] == 3) {
                            $c++;   
                        }
                    } else if ($value == "green") {
                        if ($vals['gmissed'] == 1) {
                            $le++;   
                        } else if ($vals['gmissed'] == 2) {
                            $r++;   
                        } else if ($vals['gmissed'] == 3) {
                            $lo++;   
                        } else if ($vals['gmissed'] == 4) {
                            $s++;   
                        } else if ($vals['gmissed'] == 5) {
                            $na++;   
                        }
                    }  
                    $miss++;
                }
			}
		}
		
		return array (
            'hit' => $hit,
            'center' => $c,
            'left' => $le,
            'right' => $r,
            'long' => $lo,
            'short' => $s,
            'miss' => $miss,
            'na' => $na
        ); 
    }
	
	private function _getRoundInfoFromDB()
	{
		$dbArgs = array (
			'roundid' => $this->getRoundId(),
		);
	
		$db = new roundsController();
		return $db->returnRoundDetails($dbArgs);
	}
    
    private function _getTempRoundInfoFromDB()
	{
		$dbArgs = array (
			'roundid' => $this->getRoundId(),
		);
	
		$db = new roundsController();
		return $db->returnTempRoundDetails($dbArgs);
	}
    
    public function getPuttBreakDown()
    {
        $chipin = 0;
        $oneputt = 0;
        $twoputt = 0;
        $threeputt = 0;
        $fourputt = 0;
        $fiveputt = 0;
        $otherputt = 0;
        
        $roundInfo = $this->getRoundInfo();
		
		if ($roundInfo) {
			foreach ($roundInfo as $vals) {
                if ($vals['putts'] == 0) {
                    $chipin++;   
                } else if ($vals['putts'] == 1) {
                    $oneputt++;   
                } else if ($vals['putts'] == 2) {
                    $twoputt++;   
                } else if ($vals['putts'] == 3) {
                    $threeputt++;   
                } else if ($vals['putts'] >= 4) {
                    $fourputt++;   
                }
            }
        }
        
        return array (
            0 => $chipin,
            1 => $oneputt,
            2 => $twoputt,
            3 => $threeputt,
            4 => $fourputt
        );
    }
    
    public function getPuttLengthHoled()
    {
        $info = $this->getRoundInfo();
        
        if ($info) {
            foreach ($info as $vals) {
                $array[] =(int)$vals['puttholedlength']; 
            }
        }

        return $array;
    }
    
    public function returnInfoForRound()
    {
        $scoreInfo = $this->getScoreForRound();
        $puttsInfo = $this->getPuttsForRound();
        $puttsHoledInfo = $this->getPuttsHoledForRound();
        $fairwayInfo = $this->getFairwaysForRound();
        $greenInfo = $this->getGreensForRound();
        $upNDownInfo = $this->getUpNDownsForRound();
        $sandSaveInfo = $this->getSandSavesForRound();
        $puttBreakDown = $this->getPuttBreakDown();
        $roundInfo = $this->getRoundInfo();
        
        $upndownpercentage = 0;
        $sandsavepercentage = 0;
        
        if ($upNDownInfo['hit'] || $upNDownInfo['miss']) {
            $upndownpercentage = round($upNDownInfo['hit']/($upNDownInfo['hit'] + $upNDownInfo['miss'])*100,2);   
        }
        
        if ($sandSaveInfo['hit'] || $sandSaveInfo['miss']) {
            $sandsavepercentage = round($sandSaveInfo['hit']/($sandSaveInfo['hit'] + $sandSaveInfo['miss'])*100,2);   
        }
        
        if ($roundInfo) {
            return array (
                'course' => $roundInfo[0]['course'],
                'tee' => $roundInfo[0]['tee'],
                'date' => $roundInfo[0]['date'],
                'time' => $roundInfo[0]['time'],
                'score' => $scoreInfo['score'],
                'putts' => $puttsInfo['putts'],
                'puttholedlength' => $puttsHoledInfo['puttholedlength'],
                'fairwayshit' => $fairwayInfo['hit'],
                'fairwaysmiss' => $fairwayInfo['miss'],
                'fairwayspercentage' => round(($fairwayInfo['hit']/($fairwayInfo['hit'] + $fairwayInfo['miss'])*100),2),
                'greenshit' => $greenInfo['hit'],
                'greensmiss' => $greenInfo['miss'],
                'greenspercentage' => round($greenInfo['hit']/($greenInfo['hit'] + $greenInfo['miss'])*100,2),
                'upndownmake' => $upNDownInfo['hit'],
                'upndownmiss' => $upNDownInfo['miss'],
                'sandsavemake' => $sandSaveInfo['hit'],
                'sandsavemiss' => $sandSaveInfo['miss'],
                'puttbreakdown' => $puttBreakDown,
                'upndownpercentage' => $upndownpercentage,
                'sandsavepercentage' => $sandsavepercentage
            );
        }
    }
    
    public function getTempScoreCard()
    {
        $info = $this->getTempRoundInfo();
        return $this->_formatScoreCardArray($info);
    }
    
    public function getScoreCard()
    {
        $info = $this->getRoundInfo();
        return $this->_formatScoreCardArray($info);
    }
    
    private function _formatScoreCardArray($info)
    {
        if ($info) {
            for($x=0; $x<18; $x++) {
                $row['holes'][$x] = array (
                    'hole' => $info[$x]['hole'],
                    'holepar' => $info[$x]['holepar'],
                    'yards' => $info[$x]['yards'],
                    'si' => $info[$x]['si'],
                    'score' => $info[$x]['score'],
                );
            }

            $row['info'] = array (
                'Course' => $info[0]['label'],
                'Location' => $info[0]['location'],
                'Tee' => $info[0]['tees'],
                'Time' => $info[0]['time'],
                'Date' => $info[0]['date']
            );

            return $row;
        }
    }
    
    public function buildTempScoreCard()
    {
        $info = $this->getTempScoreCard();
        return $this->_FormatScoreCard($info);
    }
    
    public function buildScoreCard()
    {
        $info = $this->getScoreCard();
        return $this->_FormatScoreCard($info);
    }
    
    private function _FormatScoreCard($info)
    {
        if ($info) {
            if ($info['holes']) {
                foreach ($info['holes'] as $hole) {

                    if ($hole['score'] < $hole['holepar']) {
                        $class='bg-red-active color-palette';   
                    } else if ($hole['score'] > $hole['holepar']) {
                        $class='bg-purple-active color-palette';
                    } else {
                        $class='';   
                    }

                    $row[] = "<tr><td>".$hole['hole']."</td><td>".$hole['holepar']."</td><td>".$hole['yards']."</td><td>".$hole['si']."</td><td class='$class'>".$hole['score']."</td></tr>";  
                    $total = $hole['score'] + $total;
                    $par = $hole['holepar'] + $par;
                }
            }
        
            $dateobj = $this->getDateObj();            
            
            if ($info['info']) {
                foreach ($info['info'] as $key => $vals) {
                    if ($key == "Date") {
                        $string[] = "$key: ".$dateobj->formatDateFromDatabaseOther($vals);
                    } else {
                        $string[] = "$key: $vals";
                    }
                }
            }
        }
        
        if ($string && $row) {
            return implode ("<br />", $string)."<br /><br />"
                ."<table class='table table-bordered table-striped dataTable scoreCardTable'>"
                ."<tr><th>Hole</th><th>Par</th><th>Length</th><th>SI</th><th>Score</th></tr>"
                .implode($row)
                ."<tr><td>&nbsp;</td><td>$par</td><td></td><td></td><td>$total</td></tr>"
                ."</table>";
        }
    }
}

?>