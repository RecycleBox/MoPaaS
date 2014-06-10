<?php
	function add_data()
	{
		$json = json_decode(file_get_contents("php://input"));
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $GLOBALS['db']);

		$sql = 'INSERT INTO ' . $json->{'table'};
		$keys = '';
		$values = '';
		foreach ($json as $key => $value)
		{
			if ($key == 'table')
			{
				continue;
			}
			
			if ($keys == '')
			{
				$keys = $key;
			}
			else
			{
				$keys = $keys . ',' . $key;
			}

			if ($values == '')
			{
				$values = $value;
			}
			else
			{
				$values = $values . ',' . $value;
			}
		}
		$sql = $sql . ' (' . $keys . ') VALUES (' . $values . ')';

		if (!mysql_query($sql,$GLOBALS['db']))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建对象';
			echo(json_encode($return));
			return;
		}

		$return['Code'] = 1;
		$return['Sql'] = $sql;
		echo(json_encode($return));
	}

	function search_data()
	{
		$json = json_decode(file_get_contents("php://input"));

		$sql = 'SELECT * FROM ' . $json->{'table'};

		$result = mysql_query($sql,$GLOBALS['db']);
		if (!$result)
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建对象';
			echo(json_encode($return));
			return;
		}

		for($i = 0; $row = mysql_fetch_array($result); $i++)
		{
			$return['Result'][$i] = $row;
		}

		$return['Code'] = 1;
		echo(json_encode($return));
	}

	function init_data()
	{
		// Clean data
		$sql = "DROP DATABASE {$GLOBALS['DB_NAME']}";

		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法删除数据库: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		// Create database and connection
		create_database();
		$GLOBALS['database'] = new_medoo();

		$sql = "CREATE TABLE {$GLOBALS['DB_TABLE_NAME']}
		(
		ID int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(ID),
		Date date,
		SN int,
		Name1 varchar(32),
		Name2 varchar(32),
		Name3 varchar(32),
		Mobile varchar(32),
		PersonalID varchar(32),
		Status int,
		Bank int,
		CardNumber varchar(32),
		Receipt int,
		Agent int,
		Salesperson1 int,
		Salesperson2 int,
		Salesperson3 int,
		RoomNumber varchar(32),
		Location varchar(32),
		Comment varchar(128)
		)
		CHARACTER SET utf8
		COLLATE utf8_general_ci";
		
		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建主表: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		// Status table
		$sql = 'CREATE TABLE Status
		(
		ID int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(ID),
		Value varchar(32)
		)
		CHARACTER SET utf8
		COLLATE utf8_general_ci';
		
		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建Status表: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		// Bank table
		$sql = 'CREATE TABLE Bank
		(
		ID int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(ID),
		Value varchar(32)
		)
		CHARACTER SET utf8
		COLLATE utf8_general_ci';
		
		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建Bank表: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		// Agent table
		$sql = 'CREATE TABLE Agent
		(
		ID int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(ID),
		Value varchar(32)
		)
		CHARACTER SET utf8
		COLLATE utf8_general_ci';
		
		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建Agent表: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		// Salesperson table
		$sql = 'CREATE TABLE Salesperson
		(
		ID int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(ID),
		Value varchar(32)
		)
		CHARACTER SET utf8
		COLLATE utf8_general_ci';
		
		if (!$GLOBALS['database']->query($sql))
		{
			$return['Code'] = 0;
			$return['Reason'] = '无法创建Salesperson表: ' . $GLOBALS['database']->error()[2];
			echo(json_encode($return));
			return;
		}

		$return['Code'] = 1;
		echo(json_encode($return));
	}
?>