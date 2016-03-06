<?php

require_once("eventsController.class.php");

class adminEditEvent
{
	
	private $startdate;
	private $enddate;
	private $title;
	private $description;
	private $eventid;
	private $courseid;
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
		$q = new eventsController ();
		$dbres = $q->returnAllCalendarEvents ();
		
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
		//$args['attributes'] = array ( 'onchange' => 'loadEditEventDiv (this.value)' );
		$qf = new quickForm ( $args );
		$eventlist = $qf->buildSelectBox ();

		if ($this->updated) {
			$eventUpdated = "Your event has been updated.";
		}
		
		return<<<EOHTML
        <div class='row'>
            <div class="col-lg-12 col-xs-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-calendar"></i> Edit Event</h3>
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
							<button id="editEventButton" type="button" class="btn btn-default">Edit Event</button>
						</div>
					</div>
				</form>
				<div id="eventlistdiv">

				</div>
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </div>
EOHTML;
	}
	
	
	function buildEditEvent ()
	{
		$startArgs = explode ( "-" , $this->startdate );
		$newStartDate = $startArgs[2]."/".$startArgs[1]."/".$startArgs[0];
		
		if ($this->enddate && $this->enddate != $this->startdate) {
			$endArgs = explode ( "-" , $this->enddate );
			$newEndDate = $endArgs[2]."/".$endArgs[1]."/".$endArgs[0];
			
			$style = "style='display:block'";
			$checkBoxChecked = "checked='checked'";
		} else {
			$style = "style='display:none'";
		}
		
		$memargs['Name'] = "eventid";
		$memargs['Type'] = "hidden";
		$memargs['attributes'] = array ( "value" => $this->eventid );
		$qf = new quickForm ($memargs);
		$eventid = $qf->buildInput ();
		
		$q = new eventsController ();
		$courseRes = $q->returnCourses (false);
		
		if ( $courseRes['res'] )
		{
			$optionArgs[] = "<option value=''>None</option>";
			foreach ( $courseRes['res'] as $courses )
			{
				if ( $this->courseid == $courses['name'] ) { $selected = "selected"; }
				$optionArgs[] = "<option value='".$courses['name']."' $selected>".$courses['name']."</option>";
				$selected = "";
			}
		}
		
		$course = "<select id='course_select' name='course_select' class='form-control'>".implode ( $optionArgs )."</select>";
		
		return<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-calendar"></i> Edit Event</h3>
        </div>
        <div class="box-body">
		<form class="form-horizontal" role="form" id="updateEventForm">
			<div id="updateStartDateForm" class="form-group">
				<label for="updateStartDate" class="col-sm-3 control-label">Start Date:</label>
				<div class="col-sm-5">
					<input type='input' class='form-control' id='startDate' name='startDate' value='$newStartDate' placeholder='Enter Start Date' data-date-format="dd/mm/yyyy" />
				</div>
				<div id="hiddenStartDateDiv" class="col-sm-4">
					You must enter a start date.
				</div>
			</div>
			
			<div id="updateShowEndDateForm" class="form-group">
				<label for="updateShowEndDate" class="col-sm-3 control-label">Event over multiple days?:</label>
				<div class="col-sm-5">
					<input type='checkbox' id='updateShowEndDate' name='updateShowEndDate' $checkBoxChecked />
				</div>
			</div>
				
			<div id="endEventDateHiddenRow" class="form-group" $style>
				<label for="startDate" class="col-sm-3 control-label">End Date:</label>
				<div class="col-sm-5">
					<input type='input' class='form-control' id='endDate' name='endDate' value='$newEndDate' placeholder='Enter End Date' data-date-format="dd/mm/yyyy" />
				</div>
				<div id="hiddenEndDateDiv" class="col-sm-4">
					You must enter an end date.
				</div>
			</div>
				
			<div id="updateEventNameForm" class="form-group">
				<label for="eventName" class="col-sm-3 control-label">Event Name:</label>
				<div class="col-sm-5">
					<input type='input' class='form-control' id='eventName' name='eventName' value='$this->title' placeholder='Enter Event Name' />
				</div>
				<div id="hiddenEventNameDiv" class="col-sm-4">
					You must enter an event name.
				</div>
			</div>	
				
			<div id="eventDescriptionForm" class="form-group">
				<label for="eventName" class="col-sm-3 control-label">Event Description:</label>
				<div class="col-sm-5">
					<textarea class='form-control' id='eventDescription' name='eventDescription' rows='4' cols='20' placeholder='Enter Event Description'>$this->description</textarea>
				</div>
			</div>
			
			<div id="courseNameForm" class="form-group">
				<label for="courseName" class="col-sm-3 control-label">Course:</label>
				<div class="col-sm-5">
					$course
				</div>
			</div> 

			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-10">
					<button id="updateEventButton" type="button" class="btn btn-primary">Update Event</button>
				</div>
			</div>
			$eventid
		</form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>
EOHTML;
	}
	
	
	function editEventInDB ()
	{
	
		$db = new eventsController ();
		$dbargs['startdate'] = $this->startdate;
		$dbargs['enddate'] = $this->enddate;
		$dbargs['title'] = $this->title;
		$dbargs['description'] = $this->description;
		$dbargs['eventid'] = $this->eventid;
		$dbargs['courseid'] = $this->courseid;
		$db->updateEventByEventID ( $dbargs );
	}

}

?>
