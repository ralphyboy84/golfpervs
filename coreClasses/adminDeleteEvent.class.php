<?php

require_once("eventsController.class.php");

class adminDeleteEvent
{
	
	private $startdate;
	private $enddate;
	private $title;
	private $description;
	private $eventid;
	private $updated;
	
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
	
	
	function viewEventList ()
	{
		$db = new eventsController ();
		$dbres = $db->returnAllCalendarEvents ();
		
		if ( $dbres['res'] )
		{
			foreach ( $dbres['res'] as $vals )
			{
				$df = new dateFormat ( array ( "date" => $vals['startdate'] ) );
				$date = $df->formatDateFromDatabase ();
				$selectargs[$vals['eventid']] = $vals['title']." - (".$date.")";
			}
		}
		
		$args['Name'] = "eventlist";
		$args['Type'] = "select";
		$args['options'] = $selectargs;
		$qf = new quickForm ( $args );
		$eventlist = $qf->buildSelectBox ();
		
		if ($this->updated) {
			$eventUpdated = "Your event has been deleted.";
		}
		
		return<<<EOHTML
		<div class='row'>
            <div class="col-lg-12 col-xs-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-calendar"></i> Delete Event</h3>
                </div>
                <div class="box-body">
				$eventUpdated
				<form class="form-horizontal" role="form" id="editEventForm">
					<div id="startDateForm" class="form-group">
						<label for="selectEvent" class="col-sm-3 control-label">Select Event:</label>
						<div class="col-sm-6">
							$eventlist
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button id="deleteEventButton" type="button" class="btn btn-primary">Delete Event</button>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
EOHTML;
	}
		
	function deleteEventInDB ()
	{
		$dbargs['eventid'] = $this->eventid;
		$db = new eventsController ();
		$db->deleteEventByEventID ( $dbargs );
	}

}

?>
