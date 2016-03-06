<?php

require_once("stats.class.php");

class putts extends stats
{
    public function _getInfoFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRoundPuttsTotal($this->getUserName(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    public function _getInfoForFriendFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRoundPuttsTotal($this->getCompareWith(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    private function _getAllInfoFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRounds($this->getUserName(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    private function _getAllInfoForFriendFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRounds($this->getCompareWith(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    public function setStats()
    { 
        $info = $this->_getInfoFromDB();
        $newArray = $this->formatStats($info);
        $this->stats = $newArray; 
    }
    
    public function setStatsForFriend()
    {
        $info = $this->_getInfoForFriendFromDB();
        $newArray = $this->formatStats($info);
        $this->statsforfriend = $newArray; 
    }
    
    private function formatStats($info)
    {    
        if ($info['res']) {
            foreach ($info['res'] as $vals) {
                $dateArgs = explode("-", $vals['date']);
                $year = $dateArgs[0];
                
                $array['overall'][] = $vals['putts'];
                $array['years'][$year][] = $vals['putts'];
                $array['courses'][$vals['course']][] = $vals['putts'];
                $array['competitions'][$vals['competition']][] = $vals['putts'];
            }
        }
        
        $keys = array (
            'overall',
            'years',
            'courses',
            'competitions'
        );
        
        foreach ($keys as $key) {
            $temp = $array[$key];
            
            if ($temp && $key != "overall") {
                foreach ($temp as $skey => $scores) {
                    foreach ($scores as $score) {
                        $total[$skey] = $score+$total[$skey];
                        ${"count_$skey"}++;
                    }
                    $newArray[$key][$skey] = round($total[$skey]/${"count_$skey"}, 2);
                }
            } else if ($temp && $key == "overall") {
                foreach ($temp as $putts) {
                    $totalx = $putts+$totalx;
                    $count++;
                }
                $newArray[$key]['putts'] = round($totalx/$count, 2);
                $newArray[$key]['rounds'] =$count;
            }
        }
        
        return $newArray;
    }
    
    public function getStatsByCourse()
    {        
        $info = $this->_getStatsBy("courses");
        
        if ($info) {
            return "<table id='puttcoursetable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Course")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByCompetition()
    {
        $info = $this->_getStatsBy("competitions");
        
        if ($info) {
            return "<table id='puttcompetitiontable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Competition")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByYear()
    {
        $info = $this->_getStatsBy("years");
        
        if ($info) {
            return "<table id='puttyeartable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Year")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsOverall()
    {
        $info = $this->getStats();
        
        $allholes = $this->_getAllInfoFromDB();
        $addinfo = $this->_formatOverallStats($allholes);
        
        $threeputtsround = $addinfo['threeputtsround'];
        $oneputtsround = $addinfo['oneputtsround'];
        $max1 = $addinfo['max1'];
        $max3 = $addinfo['max3'];
        $min3 = $addinfo['min3'];
        $girputtsround = $addinfo['girputtsround'];
        
        if ($this->getCompareWith()) {
            $friendinfo = $this->getStatsForFriend();
            $allholes = $this->_getAllInfoForFriendFromDB();
            $addinfofriend = $this->_formatOverallStats($allholes);
            
            $numroundscol = "<td>".$friendinfo['overall']['rounds']."</td>";
            $avputtscol = "<td>".$friendinfo['overall']['putts']."</td>";
            $threeputtscol = "<td>".$addinfofriend['threeputtsround']."</td>";
            $oneputtscol = "<td>".$addinfofriend['oneputtsround']."</td>";
            $max1col = "<td>".$addinfofriend['max1']."</td>";
            $max3col = "<td>".$addinfofriend['max3']."</td>";
            $min3col = "<td>".$addinfofriend['min3']."</td>";    
            $girputtscol = "<td>".$addinfofriend['girputtsround']."</td>";
            
            $thead = "<thead><tr><th>&nbsp;</th><th>You</th><th>".$this->getFullFriendName()."</th></tr>";
        }
        
        if ($info) {
            return "
            <table id='scoreoveralltable' class='table table-bordered table-striped standardTable'>
                $thead
                <tbody>
                    <tr><td>Number of Rounds</td><td>".$info['overall']['rounds']."</td>$numroundscol</tr>
                    <tr><td>Average Putts</td><td>".$info['overall']['putts']."</td>$avputtscol</tr>
                    <tr><td>Average 3 Putts Per Round</td><td>".$threeputtsround."</td>$threeputtscol</tr>
                    <tr><td>Average 1 Putts Per Round</td><td>".$oneputtsround."</td>$oneputtscol</tr>
                    <tr><td>Longest Streak of 1 Putt Greens</td><td>".$max1."</td>$max1col</tr>
                    <tr><td>Longest Streak of 3 Putt Greens</td><td>".$max3."</td>$max3col</tr>
                    <tr><td>Longest Streak of Greens Without a 3 Putt</td><td>".$min3."</td>$min3col</tr>
                    <tr><td>Putts Per GIR</td><td>".$girputtsround."</td>$girputtscol</tr>
                </tbody>
            </table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    private function _formatOverallStats($allholes)
    {
        $x=0;
        $prev1=0;
        $max3=0;
        
        if ($allholes['res']) {
            foreach ($allholes['res'] as $hole) {
                if ($hole['putts'] == 3) {
                    $threeputts++;   
                    $prev3++;
                    
                    if ($minprev3 > $min3) {
                        $min3 = $minprev3;
                        $minids = array_unique($ids);
                        unset($ids);
                    }
                    $minprev3 = 0;
                } else if ($hole['putts'] != 3) {
                    if ($prev3 > $max3) {
                        $max3 = $prev3;
                    }
                    $prev3 = 0;
                    
                    $minprev3++;
                    
                    $ids[] = $hole['date']." ".$hole['course'];
                }
                
                if ($hole['putts'] == 1) {
                    $oneputts++; 
                    $prev1++;
                } else if ($hole['putts'] != 1) {
                    if ($prev1 > $max1) {
                        $max1 = $prev1;
                    }
                    $prev1 = 0;
                }
                
                if ($hole['green'] == 2) {
                    $gir++;
                    $puttsgir = $puttsgir+$hole['putts'];
                }
                
                $x++;
            }
        }
        
        if ($minprev3 > $min3) {
            $min3 = $minprev3;
        }
        
        $threeputtsround = 0;
        
        if ($threeputts) {
            $divisor = $x/18;
            $threeputtsround = round($threeputts/$divisor,2);   
        }
        
        $oneputtsround = 0;
        
        if ($oneputts) {
            $divisor = $x/18;
            $oneputtsround = round($oneputts/$divisor,2);   
        }
        
        $girputtsround = 0;
        
        if ($puttsgir) {
            $girputtsround = round($puttsgir/$gir,2);   
        }
        
        return array (
            'girputtsround' => $girputtsround,
            'oneputtsround' => $oneputtsround,
            'threeputtsround' => $threeputtsround,
            'max1' => $max1,
            'min3' => $min3,
            'max3' => $max3
        );
    }
    
    private function _getStatsBy($stattoget)
    {
        $info = $this->getStats();
        
        if ($info[$stattoget]) { 
            foreach ($info[$stattoget] as $key => $vals) {           
                $row[] = "<tr><td>$key</td><td>$vals</td></tr>";
            }
        }
        
        return $row;
    }
    
    private function _getTableHeadersForByTables($type)
    {
        return "<thead><tr><th>$type</th><th>Average Putts</th></tr>";   
    }
}

?>