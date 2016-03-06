<?php

require_once ("roundsController.class.php");

class roundsForm
{
	private $_roundid;
	
	public function setRoundId($val)
	{
		$this->_roundid = $val;
	}
	
	public function getRoundId()
	{
		return $this->_roundid;
	}
}

?>