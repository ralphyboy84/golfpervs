<?php

require_once("stats.class.php");

class greens extends stats
{
    public function _getInfoFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRounds($this->getUserName(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    public function setStats()
    {
        $info = $this->_getInfoFromDB();
        
        if ($info['res']) {
            foreach ($info['res'] as $vals) {
                $dateArgs = explode("-", $vals['date']);
                $year = $dateArgs[0];
                
                if ($vals['green'] == "2") {
                    $array = $this->_formatHitsMisses($array, $vals, $year, "hit");
                } else if ($vals['green'] == "1") {
                    $array = $this->_formatHitsMisses($array, $vals, $year, "miss");
                    
                    if ($vals['gmissed'] == "1") {
                        $array = $this->_formatHitsMisses($array, $vals, $year, "left");
                    } else if ($vals['gmissed'] == "2") {
                        $array = $this->_formatHitsMisses($array, $vals, $year, "right");
                    } else if ($vals['gmissed'] == "3") {
                        $array = $this->_formatHitsMisses($array, $vals, $year, "long");
                    } else if ($vals['gmissed'] == "4") {                        
                        $array = $this->_formatHitsMisses($array, $vals, $year, "short");
                    } else if ($vals['gmissed'] == "5") {                        
                        $array = $this->_formatHitsMisses($array, $vals, $year, "na");
                    }
                }
            }
        }
        
        $this->stats = $array;
    }
    
    public function getStatsByCourse()
    {        
        $info = $this->_getStatsBy("courses");
        
        if ($info) {
            return "<table id='greencoursetable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Course")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByCompetition()
    {
        $info = $this->_getStatsBy("competitions");
        
        if ($info) {
            return "<table id='greencompetitiontable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Competition")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    public function getStatsByYear()
    {
        $info = $this->_getStatsBy("years");
        
        if ($info) {
            return "<table id='greenyeartable' class='table table-bordered table-striped dataTable'>".$this->_getTableHeadersForByTables("Year")."<tbody>".implode($info)."</tbody></table>";  
        } else {
            return "No Stats for this period.";   
        }
    }
    
    private function _getStatsBy($stattoget)
    {
        $info = $this->getStats();
        
        if ($info[$stattoget]) { 
            foreach ($info[$stattoget] as $key => $vals) {
                $total = $vals['hit'] + $vals['left'] + $vals['right'] + $vals['long'] + $vals['short']+$vals['na'];
                
                $hitptg = round(($vals['hit']/$total)*100,2);
                $missleftptg = round(($vals['left']/$total)*100,2);
                $missrightptg = round(($vals['right']/$total)*100,2);
                $misslongptg = round(($vals['long']/$total)*100,2);
                $missshortptg = round(($vals['short']/$total)*100,2);
                $missnaptg = round(($vals['na']/$total)*100,2);
                
                
                $row[] = "<tr><td>$key</td><td>$hitptg%</td><td>$missleftptg%</td><td>$missrightptg%</td><td>$misslongptg%</td><td>$missshortptg%</td><td>$missnaptg%</td></tr>";
            }
        }
        
        return $row;
    }
    
    private function _getTableHeadersForByTables($type)
    {
        return "<thead><tr><th>$type</th><th>Hit</th><th>Miss Left</th><th>Miss Right</th><th>Miss Long</th><th>Miss Short</th><th>NA</th></tr>";   
    }
}

?>