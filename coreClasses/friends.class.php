<?php

class friends
{
    private $_username;
    private $_dbObj;
    private $_dateObj;
    private $_info;
    private $_searchcriteria;
    private $_selectdefault;
    
    public function setUserName($val)
    {
        $this->_username = $val;   
    }
    
    public function getUserName()
    {
        return $this->_username;
    }
    
    public function setDBObj($val)
    {
        $this->_dbObj = $val;   
    }
    
    public function getDBObj()
    {
        return $this->_dbObj;
    }
    
    public function setDateObj($val)
    {
        $this->_dateObj = $val;   
    }
    
    public function getDateObj()
    {
        return $this->_dateObj;
    }
    
    public function setSearchCriteria($val)
    {
        $this->_searchcriteria = $val;   
    }
    
    public function getSearchCriteria()
    {
        return $this->_searchcriteria;
    }
    
    public function setSelectDefault($val)
    {
        $this->_selectdefault = $val;   
    }
    
    public function getSelectDefault()
    {
        return $this->_selectdefault;   
    }
    
    private function getInfo()
    {
        if (!$this->_info) {        
            $dbobj = $this->getDBObj();
            $args = array (
                'username' => $this->getUserName()  
            );
            $info = $dbobj->getFriendsFromDB($args);
            $this->setInfo($info['res']);
        }
        
        return $this->_info;
    }
    
    public function setInfo($vals)
    {
        $this->_info = $vals;
    }
    
    public function getNumberOfFriends()
    {
        $friends = $this->getInfo();
        if ($friends) {
            return sizeof($friends);
        } else {
            return "0";   
        }
    }
    
    public function getRecentlyActiveFriends()
    {
        $friends = $this->getInfo();
        $dateObj = $this->getDateObj();
        
        if ($friends) {
            for ($x=0; $x<=7; $x++) {
                $name = $friends[$x]['forename']." ".$friends[$x]['surname'];
                $date = explode(" ", $friends[$x]['lastlogin']);
                $datedisplay = $dateObj->formatDateFromDatabaseOther($date[0]);
                
                if ($datedisplay == date("d/m/Y")) {
                    $unixtime = strtotime($friends[$x]['lastlogin']);
                    $unixtime = strtotime("2015-10-22 15:30:01");
                    $currenttime = time();
                    $diff = $currenttime - $unixtime;
                    $days = $diff / 86400;
                }
                
                
                $row[]=<<<EOHTML
<li title='$name'>
    <img src="template/dist/img/avatar5.png" alt="User Image" />
    <a class="users-list-name" href="#">$name</a>
    <span class="users-list-date">$datedisplay</span>
</li>             
EOHTML;
            }
            
            return "<ui class='users-list clearfix'>".implode($row)."</ui>";
        } else {
            return "You have no friends :(";   
        }
    }
    
    public function searchForPerv()
    {
        $dbobj = $this->getDbObj();
        $dateobj = $this->getDateObj();
        $array = array(
            'criteria' => $this->getSearchCriteria()  
        );
        $info = $dbobj->searchPervsDB($array);
        
        if ($info['res']) {
            foreach ($info['res'] as $pervs) {
                if ($pervs['username'] != $_SESSION['username']) {
                    
                    //check to see if people are already friends
                    $dbargs = array (
                        'username' => $_SESSION['username'],
                        'friendid' => $pervs['username']
                    );
                    $alreadyPals = $dbobj->checkFriends($dbargs);
                    
                    if (!$alreadyPals['res']) {
                        $option = "<button data-id='".$pervs['username']."' type='button' class='btn btn-primary addfriendbutton'>Add</button>";
                    } else {
                        if ($alreadyPals['res'][0]['since'] != "0000-00-00 00:00:00") {
                            $date = explode(" ", $alreadyPals['res'][0]['since']);
                            $date = $dateobj->formatDateFromDatabaseOther($date[0]);
                            $option = "Friends since ".$date;
                        } else {
                            $option = "Already friends";   
                        }
                    }
                    
                    $row[] = "<tr><td>".$pervs['username']."</td><td>".$pervs['forename']." ".$pervs['surname']."</td><td id='cell_".$pervs['username']."'>$option</td></tr>";
                }
            }
            
            return "<table class='table table-bordered table-striped dataTable' id='searchresulttable'><thead><tr><th>Username</th><th>Name</th><th>&nbsp;</th></tr><tbody>".implode($row)."</tbody></table>";
        }
    }
    
    public function getPervsInTable()
    {
        $friends = $this->getInfo();
        
        if ($friends) {
            foreach ($friends as $vals) {
                $row[] = "<tr><td>".$vals['username']."</td><td>".$vals['forename']." ".$vals['surname']."</td></tr>";   
            }
            
            return "<table class='table table-bordered table-striped dataTable' id='allyourpervs'><thead><tr><th>Username</th><th>Name</th></tr><tbody>".implode($row)."</tbody></table>";
        }
    }
    
    public function getPervsInSelect()
    {
        $friends = $this->getInfo();
        
        if ($friends) {
            
            foreach ($friends as $vals) {
                
                if ($vals['username'] == $this->getSelectDefault()) {
                    $selected = "selected='selected'";   
                } else {
                    $selected = "";   
                }
                
                $row[] = "<option value='".$vals['username']."' $selected>".$vals['forename']." ".$vals['surname']."</option>";   
            }
            
            return "<select id='allyourpervs_select' name='allyourpervs_select' class='form-control'><option value=''>Select Perv....</option>".implode($row)."</select>";
        }
    }
}

?>