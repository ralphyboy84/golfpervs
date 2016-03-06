<?php

require_once 'mysqlExecutor.class.php';

class menuController extends mysqlExecutor
{
	function returnMainMenuItems()
	{		
		$sql=<<<EOSQL
		SELECT menuid, name, link, id, image
		FROM menu
		WHERE inuse = 1
		ORDER BY position ASC
EOSQL;
		return $this->prepareQuery($sql);
	}
	
	
	function returnSubMenuItems($menuid)
	{		
		$sql=<<<EOSQL
		SELECT name, link, id, directory
		FROM submenu
		WHERE inuse = 1
		AND menuid = '$menuid'
		ORDER BY position ASC
EOSQL;
		return $this->prepareQuery($sql);
	}
}

?>