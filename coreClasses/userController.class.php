<?php

require_once 'mysqlExecutor.class.php';

class userController extends mysqlExecutor
{
	function returnUserInfo($username)
	{		
		$sql=<<<EOSQL
		SELECT *
		FROM user
		WHERE username = '$username'
EOSQL;
		return $this->prepareQuery($sql);
	}
    
    function insertNewUser ( $args )
	{
		$sql = " INSERT into user set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
	}
    
    function updateUser($args)
    {
        $sql = "UPDATE user SET forename = '".$args['forename']."', surname = '".$args['surname']."', email = '".$args['email']."', receivenotifs = '".$args['receivenotifs']."' WHERE username = '".$args['username']."' ";  
        
        return $this->updateQuery($sql);
    }
    
    function updateUserSignIn($args)
    {
        $logins = $args['logins'];
        $user	= $args['user'];

        $sql.=" UPDATE user SET lastlogin = now() , logins = '$logins' where username = '$user' ";
        return $this->updateQuery($sql);
    }
}

?>