<?php
if(!defined('INITIALIZED'))
	exit;

class Database_MySQL extends Database
{
	public function __construct()
	{
		$this->setDatabaseDriver(self::DB_MYSQL);
	}

	public function connect()
	{
		try
		{
			parent::__construct('mysql:host=' . $this->getDatabaseHost() . ';port=' . $this->getDatabasePort() . ';dbname=' . $this->getDatabaseName(), $this->getDatabaseUsername(), $this->getDatabasePassword());
			$this->setConnected(true);
			return true;
		}
		catch(PDOException $error)
		{
			new Error_Critic('', 'CANNOT CONNECT TO DATABASE: ' . $error->getMessage());
			return false;
		}
	}

	public function fieldName($name)
	{
		if(preg_match("/[^a-zA-Z0-9_]+/", $name) > 0)
			new Error_Critic('', 'Invalid field name format.');

		return '`' . $name . '`';
	}

	public function tableName($name)
	{
		if(preg_match("/[^a-zA-Z0-9_]+/", $name) > 0)
			new Error_Critic('', 'Invalid table name format.');

		return '`' . $name . '`';
	}
}