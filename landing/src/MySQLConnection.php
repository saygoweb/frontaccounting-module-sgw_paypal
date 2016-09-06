<?php
namespace SGW_Landing;

class MySQLConnection {

	/**
	 * Returns a named connection, or creates a named connection to the given $uri
	 * The $connectionInfo array is expected to have the following keys:
	 * - host
	 * - dbuser
	 * - dbpassword
	 * - dbname
	 * @param string $name
	 * @param array $connectionInfo
	 * @return \mysqli
	 * @throws \Exception
	 */
	public static function connection($name = 'default', $connectionInfo = null) {
		static $instance = array();
		if (array_key_exists($name, $instance)) {
			return $instance[$name]->db;
		}
		if (!$connectionInfo) {
			throw new \Exception('No uri given to create connection');
		}
		$instance[$name] = new MySQLConnection($connectionInfo);
		return $instance[$name]->db;
	}
	
	public $db;
	
	private function __construct($connectionInfo) {
// 		$info = self::parseURI($uri);
		$this->db = new \mysqli($connectionInfo['host'], $connectionInfo['dbuser'], $connectionInfo['dbpassword'], $connectionInfo['dbname'], 3306);
		if ($this->db->connect_errno) {
			throw new \Exception($this->db->connect_error);
		}
	}

	public static function parseURI($uri) {
		$retval = FALSE;
		$matches = array();
		$matchResult = preg_match(
			'/\b((?#protocol)mysql):\/\/((?#user)[^:@\/]+):((?#pass)[^:@\/]+)@((?#host)[^:@\/]+)\/((?#database)[^:@\/]+)/',
			$uri, $matches
			);
		if ($matchResult !== 1) {
			throw new \Exception("Could not parse uri '$uri'");
		}
		$retval = array();
		$retval['protocol'] = $matches[1];
		$retval['user'] = $matches[2];
		$retval['pass'] = $matches[3];
		$retval['host'] = $matches[4];
		$retval['database'] = $matches[5];
		return $retval;
	}
}