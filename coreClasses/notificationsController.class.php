<?php

require_once 'mysqlExecutor.class.php';

class notificationsController extends mysqlExecutor
{
    public function insertNotification($args)
    {
        $sql = " INSERT into notification set ";
		
		if ( $args ) {
			foreach ( $args as $key => $vals ) {
				$params[] = " $key = '$vals' ";
			}
		}
		
		$sql.= implode ( "," , $params  ).", dateentered = now()";
		
		return $this->insertQuery($sql);
    }
    
    public function getUnconfirmedNotifsDB($args)
    {
        $username = $args['username'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM notification
		WHERE username = '$username'
        AND confirmed = 0
        ORDER BY dateentered DESC
EOSQL;
		return $this->prepareQuery($sql);
    }
    
    public function getAllNotifsDB($args)
    {
        $username = $args['username'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM notification
		WHERE username = '$username'
        ORDER BY dateentered DESC
EOSQL;
		return $this->prepareQuery($sql);
    }
    
    public function getLast5NotifsDB($args)
    {
        $username = $args['username'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM notification
		WHERE username = '$username'
        ORDER BY dateentered DESC
        LIMIT 0,5
EOSQL;
		return $this->prepareQuery($sql);
    }
    
    public function getNotifFromDB($args)
    {
        $id = $args['id'];
		
		$sql=<<<EOSQL
		SELECT *
		FROM notification
		WHERE id = '$id'
EOSQL;
		return $this->prepareQuery($sql); 
    }
    
    public function confirmNotification($args)
    {
        $sql = "UPDATE notification SET confirmed = 1, dateconfirmed = now() WHERE id = '".$args['id']."' ";   
        return $this->updateQuery($sql);   
    }
}

?>