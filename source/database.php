<?php

class Database
{

	private $db = null; /**< Private PDO object  */

	/**
     * Destructor of database, disconnect.
     * 
     */
	public function __destruct()
	{
		$this->db = null;
	}

	/**
     * Connect to database.
     * 
     * @param string $host - Hostname
     * @param string $port - Port number of host.
     * @param string $database - Database name.
     * @param string $username - Username.
     * @param string $password - Password.
     * @throws Exception - Failed to connect.
     */
	public function Connect($host, $port, $database, $username, $password)
	{

		// Disconnect
		$this->db = null;

		// Try to connect.
		try
		{
			$connect_string = 'mysql:host='.$host.';port='.$port.';dbname='.$database;
			$this->db = new \PDO($connect_string, $username, $password);
			// set the PDO error mode to exception
			$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
		}
		catch(PDOException $e)
		{
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}

	/**
     * Connect to database, by loading all required parameters and
     * then calling Connect with parameters.
     *
     * @param string $filename - Path of ini file.
     * @throws Exception - Failed to connect/ini file error.
     */
	public function ConnectFile($filename)
	{
		// Open ini file.
		if (($file = @parse_ini_file($filename, true)) == false)
		{
			throw new \Exception("Failed to open ini file: '" . $filename ."'.");
		}

		// Validate file content.
		$connect_args = ["host", "port", "database", "username", "password"];
		$arguments = [];
		$conf_errors = "";
		foreach($connect_args as $key => $arg)
		{
			if(!isset($file[$arg]))
			{
				$conf_errors = $conf_errors . "," . $key;
				continue;
			}

			array_push($arguments, $file[$arg]);
		}

		if(strlen($conf_errors) != 0)
		{
			throw \Exception("Missing parameters in ini file: '" . $filename ."': " . $conf_errors);
		}
		
		// Call Connect function.
		call_user_func_array([$this, 'Connect'], array_values($arguments));
	}

	/**
     * Disconnect from the database.
     * 
     */
	public function Disconnect()
	{
		$this->db = null;
	}

	/**
     * Execute query. 
     * The query gets prepared with the parameters.
     * Example:
     *	- $query = "SELECT * from pets WHERE name=:pet_name"
     *	- $parameters = ["pet_name" => "Bosse"];
     *
     * @param string $query - Query string to execute.
     * @param string $parameters - Parameters of preparing variables.
     * @throws Exception - Failed to connect/ini file error.
     * 
     */
	public function Query($query, $parameters)
	{
		if(is_null($this->db))
		{
			throw new \Exception("Not connected to database.", 0);
		}


		// Try to execute query
		try
		{
			// Prepare statement.
			$stmt = $this->db->prepare($query);
			
			// Execute query
			$stmt->execute($parameters);
			
			// Set fetch mode.
			$result = $stmt->setFetchMode(\PDO::FETCH_NAMED/*\PDO::FETCH_ASSOC*/);
			if($result == false)
			{
				throw new \Exception("Failed to set fetch mode.", 0);
			}

		}
		catch(PDOException $e)
		{
			throw new \Exception($e->getMessage(), $e->getCode());
		}

		// Pass statement to result.
		return new Result($stmt);
	}	

	/**
     * Check if connected to database.
     * 
     */
	public function IsConnected()
	{
		return !is_null($this->db);
	}
}


class Result
{

	private $statement = null; /**< Statement from Session class. */

	/**
	 * Constructor, set statement.
	 * 
     * @param PDOStatement $statement - statement to get results from
     */
	public function __construct($statement)
	{
		$this->statement = $statement;
	}

	/**
     * Fetch results, all results.
     *
     * @return Array of all results.
     * 
     */
	public function Fetch()
	{
		if($this->statement->rowCount() == 0 )
		{
			return array();
		}


		if($data = $this->statement->fetch())
		{
			return $data;
		}

		return array();
	}

	/**
     * Fetch results, next row result.
     *
     * @return Array of row data.
     * 
     */
	public function FetchAll()
	{
		if($this->statement->rowCount() == 0 )
		{
			return array();
		}


		if($data = $this->statement->fetchAll())
		{
			return $data;
		}

		return array();
	}

}

?>

