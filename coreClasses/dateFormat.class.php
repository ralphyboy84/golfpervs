<?php
class dateFormat
{
	
	private $date;
		
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
	
	
	function formatDateFromDatabase ()
	{
		$args = explode ( "-" , $this->date );
		return $args[2]."/".$args[1]."/".$args[0];
	}	
	
	
	
	function formatDateToDatabase ()
	{
		$args = explode ( "/" , $this->date );
		return $args[2]."-".$args[1]."-".$args[0];
	}
    
    
    function formatDateFromDatabaseOther ($date)
	{
		$args = explode ( "-" , $date );
		return $args[2]."/".$args[1]."/".$args[0];
	}	
	
	
	
	function formatDateToDatabaseOther ($date)
	{
		$args = explode ( "/" , $date );
		return $args[2]."-".$args[1]."-".$args[0];
	}


}

?>
