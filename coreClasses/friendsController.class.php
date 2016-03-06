<?php

require_once 'mysqlExecutor.class.php';

class friendsController extends mysqlExecutor
{
	function getFriendsFromDB($args)
	{
		$username = $args['username'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM friends, user
		WHERE friends.username = '$username'
        AND user.username = friends.friendid
        ORDER BY lastlogin DESC
EOSQL;
		return $this->prepareQuery($sql);
	}	
    
    function searchPervsDB($args)
    {
        $criteria = $args['criteria'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM user
		WHERE ( user.username like '%$criteria%'
            OR forename like '%$criteria%'
            OR surname like '%$criteria%'
            OR concat(forename, ' ', surname) like '%$criteria%' )
EOSQL;
		return $this->prepareQuery($sql);
    }
    
    function insertFriendRequest($args)
    {
        $sql = " INSERT into friends set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
                if ($key == "since") {
                    $params[] = " $key = $vals ";
                } else {
				    $params[] = " $key = '$vals' ";
                }
			}
		}
		
		$sql.= implode ( "," , $params  );
		
		return $this->insertQuery($sql);
    }
    
    function updateFriendRequest($args)
    {
        $sql = "UPDATE friends SET confirmed = 1, since = now() WHERE username = '".$args['username']."' AND friendid = '".$args['friendid']."'";   
        return $this->updateQuery($sql);
    }
    
    function checkFriends($args)
    {
        $username = $args['username'];
        $friendid = $args['friendid'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM friends
		WHERE username = '$username'
        AND friendid = '$friendid'
EOSQL;
		return $this->prepareQuery($sql);
    }
}
?>