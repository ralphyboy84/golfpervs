<?php

class errorHandler 
{
    public $errorFlag;
	public $warningFlag;
	
	function showErrors ()
	{
		if ( $this->errorFlag )
		{
			foreach ( $this->errorFlag as $errs )
			{
				$errors.= $errs."<br />";
			}
			
			return $errors;
		}
		
		return false;
	}
}

?>