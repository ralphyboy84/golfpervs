<?php
class eventsCalendarDay extends eventsCalendar
{

	function __construct ( $args = null )
	{
		if ( $args )
		{
			foreach ( $args as $key => $vals )
			{
				$this->$key = $vals;
			}
		}
		
				
		$date = $this->getDateToUse ();

		if ( $this->returnDateParam ( $date , "year" ) % 4 == 0 )
		{
			$this->monthmaxdays["02"] = 29 ;
		}
		else
		{
			$this->monthmaxdays["02"] = 28 ;
		}	
	}
	
	function buildDayView ()
	{
		$datetouse = $this->getDateToUse ();
		$res = $this->returnDayContent ( $datetouse );
		
		$month = $this->returnDateParam ( $datetouse , "month" );
		$year = $this->returnDateParam ( $datetouse , "year" );
		$day = $this->returnDateParam ( $datetouse , "day" );
		
		if ( $res )
		{
			$numofres = sizeof($res);
			$x=1;
			
			foreach ( $res as $vals )
			{
				$content.= "Event Name: ".$vals['title']."<br /><br />Event Description: ".$vals['description'];
				if ( $numofres-1 >= $x ) { $content.= "<hr />"; }
				$x++;
			}
		}
		else
		{
			$content = "No events scheduled for this day";
		}
		
		
		$qfargs['Type'] = "button";
		$qfargs['Name'] = "prev";
		$qfargs['ButtonLabel'] = "Prev";
		$prevday = $this->buildPrevDay ( $datetouse );
		$qfargs['attributes'] = array ( "onclick" => "loadPrevDay ( $prevday )" );
		$qf = new quickForm ( $qfargs );
		$prev = $qf->buildButton ();
		
		
		$qfargs['Type'] = "button";
		$qfargs['Name'] = "next";
		$qfargs['ButtonLabel'] = "Next";
		$nextday = $this->buildNextDay ( $datetouse );
		$qfargs['attributes'] = array ( "onclick" => "loadNextDay ( $nextday )" );
		$qf = new quickForm ( $qfargs );
		$next = $qf->buildButton ();
		
		
		$qfargs['Type'] = "button";
		$qfargs['Name'] = "return";
		$qfargs['ButtonLabel'] = "Return to Month";
		$qfargs['attributes'] = array ( "onclick" => "returnToMonth ( $datetouse )" );
		$qf = new quickForm ( $qfargs );
		$return = $qf->buildButton ();
		
		return<<<EODIV
		<div id="dayview">
			<table class='eventsCalendarDayDayScrollerTable'>
				<tr>
					<td><button type="button" class="btn btn-primary" data-date='$prevday' name="prev_day_button" id="prev_day_button">Previous Day</button></td>
                    <td>&nbsp;</td>
					<td><input type='input' class='form-control' id='selectCalendarDayFromDayView' value='$day/$month/$year'  placeholder='Choose a date to go to' data-date-format="dd/mm/yyyy" /></td>
                    <td>&nbsp;</td>
					<td><button type="button" class="btn btn-primary" data-date='$nextday' name="next_button" id="next_day_button">Next Day</button></td>
				</tr>
			</table>
			<br />
			
            <div class='row'>
                <div class="col-lg-12 col-xs-12"  id="monthcell${datetouse}">
                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-calendar"></i> $day/$month/$year</h3>
                    </div>
                    <div class="box-body">
					$content
				</div>
			</div>

			<table class='eventsCalendarDayBackToMonthTable'>
				<tr>
					<td><button type="button" class="btn btn-primary" data-date="$datetouse" name="return_button" id="return_button">Return to Month</button></td>
				</tr>
			</table>
		</div>
    </div>
EODIV;
	
	}

	function buildNextDay ( $date )
	{
		$year = $this->returnDateParam ( $date , "year" );
		$month = $this->returnDateParam ( $date , "month" );
		$day = $this->returnDateParam ( $date , "day" );
		
		if ( $month != 12 )
		{
			if ( $day < $this->monthmaxdays[$month] )
			{
				return $year.$month.$day+1;
			}
			else
			{
				$newmonth = $month+1;
				
				if ( strlen ( $newmonth ) == 1 ) { $newmonth = "0$newmonth"; }
				
				return $year.$newmonth."01";
			}
		}
		else
		{
			if ( $day < $this->monthmaxdays[$month] )
			{
				return $year.$month.$day+1;
			}
			else
			{
				$newyear = $year+1;
				
				return $newyear."0101";
			}
		}
		
		
	}	
	
	function buildPrevDay ( $date )
	{
		$year = $this->returnDateParam ( $date , "year" );
		$month = $this->returnDateParam ( $date , "month" );
		$day = $this->returnDateParam ( $date , "day" );
		
		if ( $month != 1)
		{
			if ( $day > 1 )
			{
				return $year.$month.$day-1;
			}
			else
			{
				$newmonth = $month-1;
				
				if ( strlen ( $newmonth ) == 1 ) { $newmonth = "0$newmonth"; }
				
				$day = $this->monthmaxdays[$newmonth];

				return $year.$newmonth.$day;
			}
		}
		else
		{
			if ( $day == 1 )
			{
				$newyear = $year-1;
				return $newyear."1231";
			}
			else
			{			
				return $year.$month.$day-1;
			}
		}
	}
	

}

?>
