<?php

require_once 'mysqlExecutor.class.php';

class loginController extends mysqlExecutor
{
	function validateLogin($args)
	{
		$username = $args['username'];
		$password = $args['password'];
		
		$sql=<<<EOSQL
		SELECT username, forename, surname, lastlogin, logins
		FROM user
		WHERE username = '$username'
		AND password = '$password'
EOSQL;
		return $this->prepareQuery($sql);
	}
}

?>