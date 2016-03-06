<?php

require_once 'mysqlExecutor.class.php';

class eventsController extends mysqlExecutor
{		
	function returnCourses($paramarray)
	{
		$sql = "select * from course ";
		
		if ($paramarray['orderby'])	{
			$sql.= "order by = '".$paramarray['orderby']."' ";
		}
		
		if ($paramarray['limit']) {
			$sql.= "limit ".$paramarray['limit']." ";
		}
		
		return $this->prepareQuery($sql);
	}
		
	
	function returnAllCalendarEvents (  )
	{
		$date = $args['date'];
		
		if ( $_SESSION['friendid'] ) { 
			$username = $_SESSION['friendid']; 
			$friendval = true; 
		}
		else { 
			$username = $_SESSION['username']; 
		}

		$sql = " SELECT * FROM calendar where username = '$username'";

		return $this->prepareQuery($sql);
	}
	
	
	function returnCalendarEventByEventID ( $args = null )
	{
		$eventid = $args['eventid'];
		
		if ( $_SESSION['friendid'] ) { 
			$username = $_SESSION['friendid']; 
			$friendval = true; 
		}
		else { 
			$username = $_SESSION['username']; 
		}

		$sql = " SELECT * FROM calendar where eventid = $eventid and username = '$username' ";

		return $this->prepareQuery($sql);
	}
	
	
	function returnCalendarEventDay ( $args )
	{
		$date = $args['date'];
		
		if ( $_SESSION['friendid'] ) { 
			$username = $_SESSION['friendid']; 
			$friendval = true; 
		}
		else { 
			$username = $_SESSION['username']; 
		}

		$sql = " SELECT * FROM calendar where startdate <= '$date' and enddate >= '$date'  and username = '$username' ";

		return $this->prepareQuery($sql);
	}
	
	
	function insertEvent ( $args )
	{
		$sql = " INSERT into calendar set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
	}

	
	function updateEventByEventID ( $args )
	{
		$old = $this -> returnCalendarEventByEventID($args);
					
		if ($args['startdate'])   {$newarray['startdate']   = $args['startdate'];}   else {$newarray['startdate']   = $old['res'][0]['startdate'];}
		if ($args['enddate'])     {$newarray['enddate']     = $args['enddate'];}     else {$newarray['enddate']     = $old['res'][0]['enddate'];}
		if ($args['title'])       {$newarray['title']       = $args['title'];}       else {$newarray['title']       = $old['res'][0]['title'];}
		if ($args['description']) {$newarray['description'] = $args['description'];} else {$newarray['description'] = $old['res'][0]['description'];}
		if ($args['courseid']) 	  {$newarray['courseid'] 	= $args['courseid'];} 	 else {$newarray['courseid'] 	= $old['res'][0]['courseid'];}
	
		$sql = "update calendar set ";
		$sql.= "startdate = '".$newarray['startdate']. "', ";
		$sql.= "enddate = '".$newarray['enddate']. "', ";
		$sql.= "title = '".$args['title']. "', ";
		$sql.= "courseid = '".$args['courseid']. "', ";
		$sql.= "description = '".$args['description']. "' ";
		$sql.= "where eventid = '".$args['eventid']. "' ";

		return $this->updateQuery($sql);
	}
	
	function deleteEventByEventID($args)
	{
		$sql = " DELETE FROM calendar WHERE eventid = '".$args['eventid']."' ";
		return $this->deleteQuery($sql);
		
	}
    
    function getFutureEventsFromDB()
    {
        $date = date("Y-m-d");
        
        if ( $_SESSION['friendid'] ) { 
			$username = $_SESSION['friendid']; 
			$friendval = true; 
		}
		else { 
			$username = $_SESSION['username']; 
		}

		$sql = " SELECT * FROM calendar where startdate >= '$date' and username = '$username' ";

		return $this->prepareQuery($sql);
    }
}
?>