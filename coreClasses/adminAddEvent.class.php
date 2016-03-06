<?php

require_once("eventsController.class.php");

class adminAddEvent
{
	
	private $startdate;
	private $enddate;
	private $title;
	private $description;
	private $courseid;
	
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
	
	
	function buildAddEvent ()
	{		
		$memargs['Name'] = "add";
		$memargs['Type'] = "button";
		$memargs['ButtonLabel'] = "Add";
		$memargs['attributes'] = array ( "onclick" => "addEvent ()" );
		$qf = new quickForm ($memargs);
		$addbut = $qf->buildButton ();
		
		$q = new eventsController ();
		$courseRes = $q->returnCourses (false);
		
		if ( $courseRes['res'] ) 		{
			$optionArgs[] = "<option value=''>None</option>";
			foreach ( $courseRes['res'] as $courses ) {
				$optionArgs[] = "<option value='".$courses['name']."'>".$courses['name']."</option>";
			}
		}
		
		if ($optionArgs) {
			$course = "<select id='course_select' name='course_select' class='form-control'>".implode ( $optionArgs )."</select>";
		}
		
		return<<<EOHTML
    <div class='row'>
            <div class="col-lg-12 col-xs-12">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-calendar"></i> Add Event</h3>
                </div>
                <div class="box-body">
				<form class="form-horizontal" role="form" id="addEventForm">
					<div id="startDateForm" class="form-group">
						<label for="startDate" class="col-sm-3 control-label">Start Date:</label>
						<div class="col-sm-5">
							<input type='input' class='form-control' id='startDate' name='startDate' value='' placeholder='Enter Start Date' data-date-format="dd/mm/yyyy" />
						</div>
						<div id="hiddenStartDateDiv" class="col-sm-4">
							You must enter a start date.
						</div>
					</div>
					
					<div id="showEndDateForm" class="form-group">
						<label for="showEndDate" class="col-sm-3 control-label">Event over multiple days?:</label>
						<div class="col-sm-5">
							<input type='checkbox' id='showEndDate' name='showEndDate' />
						</div>
					</div>
						
					<div id="endEventDateHiddenRow" class="form-group">
						<label for="startDate" class="col-sm-3 control-label">End Date:</label>
						<div class="col-sm-5">
							<input type='input' class='form-control' id='endDate' name='endDate' value='' placeholder='Enter End Date' data-date-format="dd/mm/yyyy" />
						</div>
						<div id="hiddenEndDateDiv" class="col-sm-4">
							You must enter an end date.
						</div>
					</div> 
						
					<div id="eventNameForm" class="form-group">
						<label for="eventName" class="col-sm-3 control-label">Event Name:</label>
						<div class="col-sm-5">
							<input type='input' class='form-control' id='eventName' name='eventName' value='' placeholder='Enter Event Name' />
						</div>
						<div id="hiddenEventNameDiv" class="col-sm-4">
							You must enter an event name.
						</div>
					</div>	
						
					<div id="eventDescriptionForm" class="form-group">
						<label for="eventName" class="col-sm-3 control-label">Event Description:</label>
						<div class="col-sm-5">
							<textarea class='form-control' id='eventDescription' name='eventDescription' rows='4' cols='20' placeholder='Enter Event Description'></textarea>
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
							<button id="addEventButton" type="button" class="btn btn-primary">Add Event</button>
						</div>
					</div>
				</form>
				
				<div id='addEventSuccessDiv'>
				
				</div>
			</div>
		</div>
    </div>
EOHTML;
	}
	
	
	function addEventToDB ()
	{		
		$db = new eventsController ();
		$dbargs['startdate'] = $this->startdate;
		$dbargs['enddate'] = $this->enddate;
		$dbargs['title'] = $this->title;
		$dbargs['description'] = $this->description;
		$dbargs['courseid'] = $this->courseid;
		$dbargs['username'] = $_SESSION['username'];
		$res = $db->insertEvent ( $dbargs );
	}

}

?>
