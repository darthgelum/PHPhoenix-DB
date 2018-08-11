<?php

namespace PHPhoenix;

/**
 * Database Module for PHPhoenix
 *
 * This module allows you to access the database. Currently
 * PDO and Mysqli drivers are supported. PDO drivers can access Mysql, 
 * SQLite and PostgreSQL databases.
 *
 * @see \PHPhoenix\DB\Query
 * @package    DB
 */
class DB {
	
	/**
	 * Phoenix Dependancy Container
	 * @var \PHPhoenix\Phoenix
	 */
	public $phoenix;
	
	/**
	 * Database connection instances
	 * @var \PHPhoenix\DB\Connection
	 */
	protected $db_instances = array();
	
	/**
	 * Initializes the database module
	 * 
	 * @param \PHPhoenix\Phoenix $phoenix Phoenix dependency container
	 */
	public function __construct($phoenix) {
		$this->phoenix = $phoenix;
	}
	
	/**
	 * Gets an instance of a connection to the database
	 *
	 * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
	 * @return \PHPhoenix\DB\Connection  Driver implementation of the Connection class.
	 */
	public function get($config = 'default'){
		if (!isset($this->db_instances[$config])) {
			$driver = $this->phoenix->config->get("db.{$config}.driver");
			$driver = "\\PHPhoenix\\DB\\".$driver."\\Connection";
			$this->db_instances[$config] = new $driver($this->phoenix, $config);
		}
		return $this->db_instances[$config];
	}
	
	/**
	 * Builds a query for specified connection.
	 *
	 * @param string $type   Query type. Available types: select,update,insert,delete,count
	 * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
	 * @return \PHPhoenix\DB\Query  Driver implementation of the Query class.
	 */
	public function query($type, $config = 'default')
	{
		return $this->get($config)->query($type);
	}

	/**
	 * Gets the id of the last inserted row
	 *
	 * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
	 * @return mixed Id of the last inserted row
	 */
	public function insert_id($config = 'default')
	{
		return $this->get($config)->insert_id();
	}
	
	/**
	 * Gets column names for the specified table
	 *
	 * @param string $table Name of the table to get columns from
	 * @param string  $config Configuration name of the connection.
	 *                        Defaults to  'default'.
	 * @return array Array of column names
	 */
	public function list_columns($table, $config = 'default') {
		return $this->get($config)->list_columns($table);
	}
	
	/**
	 * Returns an Expression representation of the value.
	 * Values wrapped inside Expression are not escaped in queries
	 *
	 * @param mixed $value  Value to be wrapped
     * @param array $params Escaped parameters
	 * @return \PHPhoenix\Db\Expression  Raw value that will not be escaped during query building
	 */
	public function expr($value, $params = array()) {
		return new \PHPhoenix\DB\Expression($value, $params);
	}
	
	/*
	 * Creates a new query
	 *
	 * @param string $driver Database driver name
	 * @param \PHPhoenix\DB\Connection $db   Database connection
	 * @param string $type Query type. Available types: select, update, insert, delete, count
	 * @return \PHPhoenix\DB\Query
	 */
	public function query_driver($driver, $db, $type) {
		$driver = "\\PHPhoenix\\DB\\".$driver."\\Query";
		return new $driver($db, $type);
	}
	
	/*
	 * Creates a new result
	 *
	 * @param string $driver Database driver name
	 * @param object $cursor Datbase result cursor
	 * @return \PHPhoenix\DB\Result
	 */
	public function result_driver($driver, $cursor) {
		$driver = "\\PHPhoenix\\DB\\".$driver."\\Result";
		return new $driver($cursor);
	}
}
