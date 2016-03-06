<?php

//mysql class
class mysqlExecutor
{
	//connect to the database
	function db_connect()
	{
		global $SERVERNAME;
		global $USERNAME;
		global $PASSWORD;
		
		return mysql_connect ($SERVERNAME, $USERNAME, $PASSWORD);
	}
		
		
	//get connection error
	function getConnectionError()
	{
		return 'I cannot connect to the database because: ' . mysql_error();
	}
		
	//select the database
	function select_db()
	{
		global $DATABASE;
		
		return mysql_select_db ($DATABASE);
	}
		
		
	//execute the query
	function executeQuery($sql)
	{
		$val = mysql_query($sql);	
		return $val;	
	}

		
	//format the results
	function formatResults($result)
	{
		$i=0;
		$ret = "";
	
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key => $value) {
				$ret[$i][$key] = $value;
			}
			$i++;
		}
		
		return array ( 
			'res' => $ret, 
			'rows' => $i ,
			'insertid' => mysql_insert_id() ,
		);			
	}
		
		
	//get the error
	function getError()
	{
		return mysql_error();
	}
		
		
	//prepare the query
	function prepareQuery($sql)
	{
		$connect = $this->db_connect();
		
		if ($connect) { 
			$this->select_db();
		} else { 
			return $this->getConnectionError(); 
		}
		
		$queryresult = $this->executeQuery($sql);
		
		if ($queryresult) {
			$info = $this->formatResults($queryresult); 
		} else { 
			$info['error'] = $this->getError();
		}
	 
		$info['sql'] = $sql;
			
		//return the array
		return $info;
	}
		
	//function to perform an update query	
	function updateQuery($sql)
	{
		$connect = $this->db_connect();
		
		if ($connect) { 
			$this->select_db();                 
		} else { 
			return $this->getConnectionError(); 
		}
		
		$queryresult = $this->executeQuery($sql);
		
		if (!$queryresult) { 
			$info['error'] = $this->getError(); 
		}
		
		$info['sql'] = $sql;
		
		return $info;	
	}
		
	//function to perform an insert query	
	function insertQuery($sql)
	{
		$connect = $this->db_connect();
			
		if ($connect) { 
			$this->select_db();                 
		} else { 
			return $this->getConnectionError(); 
		}
			
		$queryresult = $this->executeQuery($sql);
		
		if (!$queryresult) { 
			$info['error']    = $this->getError(); 
		}
		
		$info['sql'] = $sql;
		$info['insertid'] = mysql_insert_id();
		
		return $info;
	}
		
	//function to perform a delete query
	function deleteQuery ( $sql )
	{	
		$connect = $this->db_connect();
			
		if ($connect) { 
			$this->select_db();                 
		} else { 
			return $this->getConnectionError(); 
		}
			
		$queryresult = $this->executeQuery($sql);
        
        if (!$queryresult) { 
			$info['error']    = $this->getError(); 
		}
		
		$info['sql'] = $sql;
		
		return $info;
	}


}


?>