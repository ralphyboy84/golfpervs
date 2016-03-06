<?php
class eventsCalendarMonth extends eventsCalendar
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
	
	function buildMonthView ()
	{
		$datetouse = $this->getDateToUse ();
		
		$month = $this->returnDateParam ( $datetouse , "month" );
		$year = $this->returnDateParam ( $datetouse , "year" );
		
		$day = $this->findFirstDayOfMonth ( $year.$month."01" );
		
		$dontdisplay = true;
		$dontrepeat = false;
		
		$y=1;
		
		for ( $i=1; $i<=41; $i++ )
		{
			if ( $i == $this->days[$day] && !$dontrepeat )
			{
				$dontdisplay = false;
			}
			
			if ( $y == $this->monthmaxdays[$month] )
			{
				$wehavefinished = true;
			}
			
			if ( $dontdisplay )
			{
				$tablecontent.= "<td>&nbsp;</td>";
			}
			else
			{
				if ( $y <= $this->monthmaxdays[$month] )
				{   
                    if ($y<10) {
                        $y = "0".$y;
                    }
					$dayinfo = $this->buildDayInfo ( "monthview" , $year.$month.$y );
					$tablecontent.= "<td>$dayinfo</td>";
                    $divContent.= "<div class='col-xs-12'>$dayinfo</div>";
					$y++;
				}
				else
				{
					$tablecontent.= "<td>&nbsp;</td>";
					$dontdisplay = true;
					
				}
			}

			if ( $i % 7 == 0 && !$wehavefinished )
			{
				$tablecontent.= "</tr><tr>";
                $divContent.= "</div><div class='row'>";
			}
			else if ( $i % 7 == 0 && $wehavefinished )
			{
				$tablecontent.= "</tr>";
                $divContent.= "</div>";
				break;
			}
		}
		
		$monthselect = $this->buildMonthSelector ($year,$month);
		$prevmonth = $this->buildPrevMonth ( $datetouse );
		$nextmonth = $this->buildNextMonth ( $datetouse );
		
		$monthTextual = $this->buildMonthLookUp ( $month );
		
		return <<<EOTABLE
		<div class='row'>
            <div class="col-lg-12 col-xs-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-calendar"></i> Events for $monthTextual $year</h3>
                </div>
                <div class="box-body">
					<div id="monthview">
					
						<table class='eventCalendarMonthSelectContainer'>
							<tr>
								<td>
									<button type="button" class="btn btn-primary" data-date="$prevmonth" name="prev_button" id="prev_button">Previous</button>
								</td>
                                <td>&nbsp;</td>
								<td>
									$monthselect
								</td>
                                <td>&nbsp;</td>
								<td>
									<button type="button" class="btn btn-primary" data-date="$nextmonth" name="next_button" id="next_button">Next Month</button>
								</td>
							</tr>
						</table>
						
						<br />
                        <div class='hidden-xs'>	
                            <table class='eventsCalendarMonth'>
                                <tr>
                                    <th>Mon</th>
                                    <th>Tues</th>
                                    <th>Wed</th>
                                    <th>Thurs</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                    <th>Sun</th>
                                </tr>

                                <tr>
                                    $tablecontent
                                </tr>
                            </table>
                        </div>
                    
                        <div class='hidden-sm hidden-md hidden-lg'>
                            <div class='row'>
                            $divContent
                            </div>
                        </div>
					<br />
					</div>
				</div>
			</div>
        </div>
EOTABLE;
	}



	
	
	function buildDayInfo ( $view , $date )
	{
		$day = $this->returnDateParam ( $date , "day" );
		$res = $this->returnDayContent ( $date );
		
		if ( $res )
		{
			$numofres = sizeof($res);
			$x=1;
			
			foreach ( $res as $event )
			{
				$content.= $event['title']."<br />";
				if ( $numofres-1 >= $x ) { $content.= "<hr />"; }
				$x++;
			}
		}
		
		return<<<EODIV
		<div class = "monthviewcellwrapper" id="monthcell${date}" data-date="$date" title='Click here to view more info for this date'>
			<div class="topleftmonthview">
			$day
			</div>
			
			$content
		</div>
EODIV;
	}
	
	
	function buildNextMonth ( $date )
	{
		$year = $this->returnDateParam ( $date , "year" );
		$month = $this->returnDateParam ( $date , "month" );
		
		if ( $month != 12 ) { return $year.$month+1; }
		else				
		{ 
			$newyear = $year+1;
			$month = "01";
			return $newyear.$month;
		}
	}	
	
	function buildPrevMonth ( $date )
	{
		$year = $this->returnDateParam ( $date , "year" );
		$month = $this->returnDateParam ( $date , "month" );
		
		if ( $month != 1 ) { return $year.$month-1; }
		else				
		{ 
			$newyear = $year-1;
			$month = "12";
			return $newyear.$month;
		}
	}
	
	
	
	function buildMonthSelector ($year,$month)
	{
		$args['Name'] = "month";
		$args['Type'] = "select";
		$args['options'] = $this->monthlookup;
		$args['defaultVal'] = $month;
		$args['data'] = array ('year' => $year);
		$qf = new quickForm ( $args );
		return $qf->buildSelectBox ();
	}
	

}

?>
