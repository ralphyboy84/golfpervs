<?php

require_once("eventsController.class.php");

class eventsCalendar
{
	public $todaysdate;
	public $view;
	public $viewdate;
	public $days = array ( "Mon" => 1 , "Tue" => 2 , "Wed" => 3, "Thu" => 4 , "Fri" => 5 , "Sat" => 6 , "Sun" => 7 );
	public $monthmaxdays = array ( "01" => 31 , "03" => 31 , "04" => 30 , "05" => 31 , "06" => 30 , "07" => 31 ,
									"08" => 31 , "09" => 30 , "10" => 31 , "11" => 30 , "12" => 31 ); 
	public $monthlookup = array ( "01" => January , "02" => February , "03" => March , "04" => April , "05" => May , "06" => June , 
								   "07" => July , "08" => August , "09" => September , "10" => October , "11" => November , "12" => December ); 
    private $_futureEvents;

	function __construct ( $args = null )
	{
		if ( $args )
		{
			foreach ( $args as $key => $vals )
			{
				$this->$key = $vals;
			}
		}
	}
	
	function setFutureEvents($val)
    {
        $this->_futureEvents = $val;   
    }
	
	function findFirstDayOfMonth ( $date )
	{
		return date ( "D" , strtotime ( $date ) );
	}
	
	
	function buildDayInfo ( $view , $date )
	{
		$day = $this->returnDateParam ( $date , "day" );
		
		return<<<EODIV
		<div class = "monthviewcellwrapper" id="monthcell${date}">
			<div class="topleftmonthview">
			$day
			</div>
		</div>
EODIV;
	}
	
	
	
	function buildMonthLookUp ( $month )
	{
		return $this->monthlookup[$month];
	}
	
	
	function returnDateParam ( $date , $param )
	{
		if ( $param == "day" )
		{
			return substr ( $date , 6 , 2 );
		}
		else if ( $param == "month" )
		{
			return substr ( $date , 4 , 2 );
		}
		else if ( $param == "year" )
		{
			return substr ( $date , 0 , 4 );
		}
	}
	
	
	function getDateToUse ( )
	{
		if ( $this->viewdate )
		{
			return $this->viewdate;
		}
		else
		{
			return $this->todaysdate;
		}
	}
	
	
	function returnDayContent ( $date )
	{
		$year = $this->returnDateParam ( $date , "year" );
		$month = $this->returnDateParam ( $date , "month" );
		$day = $this->returnDateParam ( $date , "day" );
		
		$date = $year."-".$month."-".$day;
		
		$dbargs['date'] = $date;
		$db = new eventsController ();
		$dbres = $db->returnCalendarEventDay ( $dbargs );
		
		return $dbres['res'];
	}
	
	
	function buildToggleSelector ()
	{
		$args['Name'] = "toggle";
		$args['Type'] = "select";
		$args['options'] = array ( 'month' => 'Month View' , 'day' => 'Day View' );
		$args['attributes'] = array ( 'onchange' => 'loadDefaultCalendarView (this.value)' );
		$qf = new quickForm ( $args );
		$select = $qf->buildSelectBox ();
		
		return<<<EOSELECT
		$select
		
		<div id='eventscalendarcontent'>
EOSELECT;
	}
    
    public function getFutureEvents()
    {
        if (!$this->_futureEvents) {
            $ec = new eventsController();
            $info = $ec->getFutureEventsFromDB();
            $this->setFutureEvents($info);   
        }
        
        return $this->_futureEvents;
    }
    
    public function getNumOfFutureEvents()
    {
        $events = $this->getFutureEvents();
        
        if ($events['res']) {
            return sizeof($events['res']);
        } else {
            return "0";   
        }
    }
	
}

?>
