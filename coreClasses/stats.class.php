<?php



abstract class stats
{
    private $startdate;
    private $enddate;
    private $username;
    private $course;
    private $competition;
    private $label;
    private $dbObj;
    protected $stats;
    private $page;
    private $dateObj;
    private $compareWith;
    private $compareWithSelect;
    private $statsForFriend;
    private $friendName;
    
    public function getStartDate()
    {
        return $this->startdate;
    }
    
    public function setStartDate($val)
    {
        $this->startdate = $val;
    }
    
    public function getEndDate()
    {
        return $this->enddate;
    }
    
    public function setEndDate($val)
    {
        $this->enddate = $val;
    }
    
    public function getUserName()
    {
        return $this->username;
    }
    
    public function setUserName($val)
    {
        $this->username = $val;
    }
    
    public function getCourse()
    {
        return $this->course;
    }
    
    public function setCourse($val)
    {
        $this->course = $val;
    }
    
    public function getCompetition()
    {
        return $this->competition;
    }
    
    public function setCompetition($val)
    {
        $this->competition = $val;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setLabel($val)
    {
        $this->label = $val;
    }
    
    public function getDbObj()
    {
        return $this->dbObj;
    }
    
    public function setDbObj($val)
    {
        $this->dbObj = $val;
    }
    
    public function getDateObj()
    {
        return $this->dateObj;
    }
    
    public function setDateObj($val)
    {
        $this->dateObj = $val;
    }
    
    public function getStats()
    {
        if (!$this->stats) {
            $this->setStats();
        }
        
        return $this->stats;   
    }
    
    public function getStatsForFriend()
    {
        if (!$this->statsforfriend) {
            $this->setStatsForFriend();
        }
        
        return $this->statsforfriend;   
    }
    
    public function getPage()
    {
        return $this->page;   
    }
    
    public function setPage($val)
    {
        $this->page = $val;
    }
    
    public function setCompareWithSelect($val)
    {
        $this->compareWithSelect = $val;  
    }
    
    public function getCompareWithSelect()
    {
        return $this->compareWithSelect;   
    }
    
    public function setCompareWith($val)
    {
        $this->compareWith = $val;  
    }
    
    public function getCompareWith()
    {
        return $this->compareWith;   
    }
    
    public function setFullFriendName($val)
    {
        $this->friendName = $val;   
    }
    
    public function getFullFriendName()
    {
        return $this->friendName;   
    }
    
    abstract function _getInfoFromDB();
    //abstract function setStats();
    
    public function getForm()
    {
        $label = $this->getLabel();
        $page = $this->getPage();
        $dateObj = $this->getDateObj();
        
        if ($this->getStartDate()) {
            $defaultStartDate = $dateObj->formatDateFromDatabaseOther($this->getStartDate());   
        }
        
        if ($this->getEndDate()) {
            $defaultEndDate = $dateObj->formatDateFromDatabaseOther($this->getEndDate());   
        }
        
        $defaultcourse = $this->getCourse();
        $defaultcompetition = $this->getCompetition();
        
        $comparewith = $this->getCompareWithSelect();
        
        return<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> $label Stats - Filter</h3>
        </div>
        <div class="box-body">
            <form class="form-horizontal" id='stats' method='POST'>					
                <div class="form-group">
                    <label for="startDate" class="col-sm-2 control-label">Start Date:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='startDate' name='startDate' value='$defaultStartDate' placeholder='Enter Start Date' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="endDate" class="col-sm-2 control-label">End Date:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='endDate' name='endDate' value='$defaultEndDate' placeholder='Enter End Date' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="course" class="col-sm-2 control-label">Course:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='course' name='course' value='$defaultcourse' placeholder='Enter Course' />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="competition" class="col-sm-2 control-label">Competition:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='competition' name='competition' value='$defaultcompetition' placeholder='Enter Competition' />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="competition" class="col-sm-2 control-label">Compare With:</label>
                    <div class="col-sm-5">
                        $comparewith
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="viewStatsForAreaButton" type="button" class="btn btn-primary" data-page="$page">Filter Stats</button>
                    </div>
                </div>
            </form>
      </div><!-- /.box -->
    </div>
  </div>
</div>
EOHTML;
    }
    
    public function getStatsContainer($html)
    {
        $label = $this->getLabel();
        
        return<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> $label Stats</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body" id="${label}stats">
            $html
      </div><!-- /.box -->
    </div>
  </div>
</div> 
EOHTML;
    }
    
    protected function _formatHitsMisses($array, $vals, $year, $key)
    {
        $hit = $array['overall'][$key];
        $hit++;
        $array['overall'][$key] = $hit; 

        $coursecount = $array['courses'][$vals['course']][$key];
        $coursecount++;
        $array['courses'][$vals['course']][$key] = $coursecount;

        $compcount = $array['competitions'][$vals['competition']][$key];
        $compcount++;
        $array['competitions'][$vals['competition']][$key] = $compcount;

        $yearcount = $array['years'][$year][$key];
        $yearcount++;
        $array['years'][$year][$key] = $yearcount;

        $courseyearcount = $array['courseyear'][$vals['course']][$year][$key];
        $courseyearcount++;
        $array['courseyear'][$vals['course']][$year][$key] = $coursecount;

        $compyearcount = $array['competitionyear'][$vals['competition']][$year][$key];
        $compyearcount++;
        $array['competitionyear'][$vals['competition']][$year][$key] = $compyearcount;
        
        return $array;
    }
    
    
}

?>