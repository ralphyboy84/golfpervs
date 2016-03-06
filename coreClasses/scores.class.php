<?php

require_once("stats.class.php");

class scores extends stats
{
    public function _getInfoFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRoundScoreTotal($this->getUserName(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    public function _getInfoForFriendFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRoundScoreTotal($this->getCompareWith(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
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
                
                $array['overall']['score'][] = $vals['score'];
                $array['years'][$year]['score'][] = $vals['score'];
                $array['courses'][$vals['course']]['score'][] = $vals['score'];
                $array['competitions'][$vals['competition']]['score'][] = $vals['score'];
                
                $array['overall']['par'][] = $vals['par'];
                $array['years'][$year]['par'][] = $vals['par'];
                $array['courses'][$vals['course']]['par'][] = $vals['par'];
                $array['competitions'][$vals['competition']]['par'][] = $vals['par'];
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
                    foreach ($scores['score'] as $score) {
                        $total[$skey] = $score+$total[$skey];
                        ${"count_$skey"}++;
                    }
                    $newArray[$key][$skey]['score'] = round($total[$skey]/${"count_$skey"}, 2);
                    
                    foreach ($scores['par'] as $par) {
                        $partotal[$skey] = $par+$partotal[$skey];
                        ${"parcount_$skey"}++;
                    }
                    $newArray[$key][$skey]['par'] = round($partotal[$skey]/${"parcount_$skey"}, 2);
                    $newArray[$key][$skey]['rounds'] =${"parcount_$skey"};
                }
            } else if ($temp && $key == "overall") {
                foreach ($temp['score'] as $score) {
                    $totalx = $score+$totalx;
                    $count++;
                }
                $newArray[$key]['score'] = round($totalx/$count, 2);

                foreach ($temp['par'] as $par) {
                    $partotalx = $par+$partotalx;
                    $parcount++;
                }
                $newArray[$key]['par'] = round($partotalx/$parcount, 2);
                $newArray[$key]['rounds'] =$parcount;
            }
        }
        
        return $newArray;
    }
    
    public function getStatsByCourse()
    {        
        $info = $this->_getStatsBy("courses");
        
        if ($info) {
            return "<table id='scorecoursetable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Course")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByCompetition()
    {
        $info = $this->_getStatsBy("competitions");
        
        if ($info) {
            return "<table id='scorecompetitiontable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Competition")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByYear()
    {
        $info = $this->_getStatsBy("years");
        
        if ($info) {
            return "<table id='scoreyeartable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Year")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsOverall()
    {
        $info = $this->getStats();
        
        if ($this->getCompareWith()){
            $friendInfo = $this->getStatsForFriend();
            
            $numofroundscol = "<td>".$friendInfo['overall']['rounds']."</td>";
            $scorecol = "<td>".$friendInfo['overall']['score']."</td>";
            $parcol = "<td>".$friendInfo['overall']['par']."</td>";
            $topar = $friendInfo['overall']['score'] - $friendInfo['overall']['par'];
            $toparcol = "<td>".$topar."</td>";
            $thead = "<thead><tr><th>&nbsp</th><th>You</th><th>".$this->getFullFriendName()."</th></tr></thead>";
        }
        
        if ($info) {
            $topar = $info['overall']['score'] - $info['overall']['par'];
            
            return "
            <table id='scoreoveralltable' class='table table-bordered table-striped standardTable'>
                $thead
                <tbody>
                    <tr><td>Number of Rounds</td><td>".$info['overall']['rounds']."</td>$numofroundscol</tr>
                    <tr><td>Score</td><td>".$info['overall']['score']."</td>$scorecol</tr>
                    <tr><td>Par</td><td>".$info['overall']['par']."</td>$parcol</tr>
                    <tr><td>To Par</td><td>$topar</td>$toparcol</tr>
                </tbody>
            </table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    private function _getStatsBy($stattoget)
    {
        $info = $this->getStats();
        
        if ($info[$stattoget]) { 
            foreach ($info[$stattoget] as $key => $vals) {  
                
                $topar = $vals['score']-$vals['par'];
                
                $row[] = "<tr><td>$key</td><td>".$vals['rounds']."</td><td>".$vals['score']."</td><td>".$vals['par']."</td><td>".$topar."</td></tr>";
            }
        }
        
        return $row;
    }
    
    private function _getTableHeadersForByTables($type)
    {
        return "<thead><tr><th>$type</th><th>Number of Rounds</th><th>Average Score</th><th>Average Par</th><th>Average Par</th></tr>";   
    }
}

?>