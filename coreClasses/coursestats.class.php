<?php

require_once("stats.class.php");

class coursestats extends stats
{
    public function _getInfoFromDB()
    {
        $dbObj = $this->getDbObj();
        return $dbObj->returnRounds($this->getUserName(), $this->getStartDate(), $this->getEndDate(), $this->getCompetition(), $this->getCourse());
    }
    
    public function setStats()
    {
        $info = $this->_getInfoFromDB();
        $newArray = $this->formatStats($info);
        $this->stats = $newArray;        
    }
    
    private function formatStats($args)
    {
        if ($args['res']) {
            foreach ($args['res'] as $vals) {
                $hole = $vals['hole'];
                $holes[$vals['hole']]['score'][] = $vals['score'];
                $holes[$vals['hole']]['putts'][] = $vals['putts']; 
                
                $minscore = $holes[$vals['hole']]['minscore'];
                
                if (!$minscore) {
                    $holes[$vals['hole']]['minscore'] = $vals['score'];
                }
                
                if ($vals['score'] < $minscore) {
                    $holes[$vals['hole']]['minscore'] = $vals['score'];
                }
                
                $maxscore = $holes[$vals['hole']]['maxscore'];
                
                if (!$maxscore) {
                    $holes[$vals['hole']]['maxscore'] = $vals['score'];
                }
                
                if ($vals['score'] > $maxscore) {
                    $holes[$vals['hole']]['maxscore'] = $vals['score'];
                }
                
                $avscore = round(array_sum($holes[$vals['hole']]['score'])/count($holes[$vals['hole']]['score']),2);
                $holes[$vals['hole']]['avscore'] = $avscore;
                $avputts = round(array_sum($holes[$vals['hole']]['putts'])/count($holes[$vals['hole']]['putts']),2);
                $holes[$vals['hole']]['avputts'] = $avputts;
                
                $holes[$vals['hole']]['par'] = $vals['par'];
                
                if ($vals['putts'] == 1) {
                    $fcount = $holes[$vals['hole']]['oneputts'];
                    $fcount++;
                    $holes[$vals['hole']]['oneputts'] = $fcount++;   
                }
                
                if ($vals['putts'] == 3) {
                    $fcount = $holes[$vals['hole']]['threeputts'];
                    $fcount++;
                    $holes[$vals['hole']]['threeputts'] = $fcount++;   
                }
                
                if ($vals['par'] != 3) {
                    if ($vals['fairway'] == 1) {
                        $fcount = $holes[$vals['hole']]['fairwaym'];
                        $fcount++;
                        $holes[$vals['hole']]['fairwaym'] = $fcount;   
                    }

                    if ($vals['fairway'] == 2) {

                        $fcount = $holes[$vals['hole']]['fairwayh'];
                        $fcount++;
                        $holes[$vals['hole']]['fairwayh'] = $fcount;
                    }

                    if ($holes[$vals['hole']]['fairwayh'] || $holes[$vals['hole']]['fairwaym']) {
                        $avfairways = 100*round($holes[$vals['hole']]['fairwayh']/($holes[$vals['hole']]['fairwayh']+$holes[$vals['hole']]['fairwaym']),2);
                        $holes[$vals['hole']]['avfairways'] = $avfairways."%";
                    }
                }

                if ($vals['green'] == 1) {
                    $fcount = $holes[$vals['hole']]['greenm'];
                    $fcount++;
                    $holes[$vals['hole']]['greenm'] = $fcount;   
                }
                           
                if ($vals['green'] == 2) {
                    
                    $fcount = $holes[$vals['hole']]['greenh'];
                    $fcount++;
                    $holes[$vals['hole']]['greenh'] = $fcount;
                }

                if ($holes[$vals['hole']]['greenh'] || $holes[$vals['hole']]['greenm']) {
                    $avgreens = 100*round($holes[$vals['hole']]['greenh']/($holes[$vals['hole']]['greenh']+$holes[$vals['hole']]['greenm']),2);
                    $holes[$vals['hole']]['avgreens'] = $avgreens."%";
                }
                
                if ($vals['upndown'] == 1) {
                    $fcount = $holes[$vals['hole']]['upndownn'];
                    $fcount++;
                    $holes[$vals['hole']]['upndownn'] = $fcount;
                }
                           
                if ($vals['upndown'] == 2) {
                    
                    $fcount = $holes[$vals['hole']]['upndowny'];
                    $fcount++;
                    $holes[$vals['hole']]['upndowny'] = $fcount;
                }

                if ($holes[$vals['hole']]['upndowny'] || $holes[$vals['hole']]['upndownn']) {
                    $avupndown = 100*round($holes[$vals['hole']]['upndowny']/($holes[$vals['hole']]['upndowny']+$holes[$vals['hole']]['upndownn']),2);
                    $holes[$vals['hole']]['avupndown'] = $avupndown."%";
                }
                
                if ($vals['sandsave'] == 1) {
                    $fcount = $holes[$vals['hole']]['sandsaven'];
                    $fcount++;
                    $holes[$vals['hole']]['sandsaven'] = $fcount;
                }
                           
                if ($vals['sandsave'] == 2) {
                    
                    $fcount = $holes[$vals['hole']]['sandsavey'];
                    $fcount++;
                    $holes[$vals['hole']]['sandsavey'] = $fcount;
                }

                if ($holes[$vals['hole']]['sandsavey'] || $holes[$vals['hole']]['sandsaven']) {
                    $avsandsave = 100*round($holes[$vals['hole']]['sandsavey']/($holes[$vals['hole']]['sandsavey']+$holes[$vals['hole']]['sandsaven']),2);
                    $holes[$vals['hole']]['avsandsave'] = $avsandsave."%";
                }
                
                /*$temp = $this->_formatData($vals, $holes, "sandsave");
                $holes[$vals['hole']]['sandsavey'] = $temp['sandsavey'];
                $holes[$vals['hole']]['sandsaven'] = $temp['sandsaven'];
                $holes[$vals['hole']]['avsandsave'] = $temp['avsandsave'];*/
                
                $holes[$vals['hole']]['par'] = $vals['par'];
            }
        }
        return $holes;
    }
    
    private function _formatData($vals, $holes, $type)
    {
        if ($vals[$type] == 1) {
            $fcount = $holes[$vals['hole']][$type.'n'];
            $fcount++;
            $holes[$vals['hole']][$type.'n'] = $fcount++;   
        }

        if ($vals[$type] == 2) {

            $fcount = $holes[$vals['hole']][$type.'y'];
            $fcount++;
            $holes[$vals['hole']][$type.'y'] = $fcount;
        }

        if ($holes[$vals['hole']][$type.'y'] || $holes[$vals['hole']][$type.'n']) {
            $av = 100*round($holes[$vals['hole']][$type.'y']/($holes[$vals['hole']][$type.'y']+$holes[$vals['hole']][$type.'n']),2);
            $holes[$vals['hole']]['av'.$type] = $av."%";
        }

        return array (
            $type.'n' => $holes[$vals['hole']][$type.'n'],
            $type.'y' => $holes[$vals['hole']][$type.'y'],
            'av'.$type => $av
        );
    }
    
    public function getStatsOverall()
    {
        $info = $this->getStats();

        if ($info) {
            foreach ($info as $hole => $vals) {
                $avscore = $vals['avscore'];
                $avputts = $vals['avputts'];
                $maxscore = $vals['maxscore'];
                $minscore = $vals['minscore'];
                $avfairways = $vals['avfairways'];
                $avgreens = $vals['avgreens'];
                $oneputts = $vals['oneputts'];
                $threeputts = $vals['threeputts'];
                $avupndown = $vals['avupndown'];
                $avsandsave = $vals['avsandsave'];
                
                if (!$oneputts){
                    $oneputts = 0;
                }
                
                if (!$threeputts){
                    $threeputts = 0;
                }
                
                if (!$avfairways) {
                    $avfairways = "-";
                }
                
                if (!$avsandsave) {
                    $avsandsave = "-";
                }
                
                if (!$avupndown) {
                    $avupndown = "-";
                }
                
                if ($avscore > $vals['par']) {
                    $class = "class='bg-purple-active color-palette'";
                } else if ($avscore < $vals['par']) {
                    $class = "class='bg-red-active color-palette'";
                } else {
                    $class = "";
                }
                
                if ($minscore < $vals['par']) {
                    $minclass = "class='bg-red-active color-palette'";
                } else {
                    $minclass = "";
                }
                
                if ($maxscore > $vals['par']) {
                    $maxclass = "class='bg-purple-active color-palette'";
                } else {
                    $maxclass = "";
                }
                
                $row[]=<<<EOROW
                <tr>
                    <td>$hole</td>
                    <td $class>$avscore</td>
                    <td>$avputts</td>
                    <td>$avfairways</td>
                    <td>$avgreens</td>
                    <td>$avupndown</td>
                    <td>$avsandsave</td>
                    <td>$oneputts</td>
                    <td>$threeputts</td>
                    <td $maxclass>$maxscore</td>
                    <td $minclass>$minscore</td>
                </tr>
EOROW;
            }
            
            if ($row) {
                return "<table class='table table-bordered table-striped dataTable'><thead><tr><th>Hole</th><th>Average Score</th>><th>Av Putts</th><th>Av Fairways</th><th>Av Greens</th><th>Av Up'n'Downs</th><th>Av Sand Saves</th><th>One Putts</th><th>Three Putts</th><th>Max Score</th><th>Min Score</th</tr></thead>".implode($row)."</table>";   
            }
        } else {
            return "No Stats for this course for this period.";   
        }
    }
}

?>