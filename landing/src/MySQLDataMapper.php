<?php
namespace SGW_Landing;

class MySQLDataMapper {
	
	/**
	 * @var array
	 */
	public $map;
	
	/**
	 * @var string
	 */
	public $tablePrefix;
	
	/**
	 * @var string
	 */
	public $table;
	
	/**
	 * @var string
	 */
	public $connectionName;
	
	public static function create($tablePrefix, $table, $map, $connectionName) {
		$mapper = new MySQLDataMapper();
		$mapper->tablePrefix = $tablePrefix;
		$mapper->table = $tablePrefix . $table;
		$mapper->map = $map;
		$mapper->connectionName = $connectionName;
		return $mapper;
	}
	
	public static function createByClass($tablePrefix, $c, $connectionName = 'default') {
		return self::create(
			$tablePrefix,
			self::autoTable($c),
			self::autoMap($c),
			$connectionName
		);
	}
	
	public static function autoTable($c) {
		$className = get_class($c);
		$parts = explode('\\', $className);
		$partCount = count($parts);
		if ($partCount > 0) {
			$className = $parts[$partCount - 1];
		}
		$parts = self::splitUpper($className);
		$tableName = '';
		if ($parts) {
			$tableName .= strtolower($parts[0]);
			$partCount = count($parts);
			for ($i = 1; $i < $partCount; $i++) {
				if ($parts[$i] == 'Model') {
					continue;
				}
				$tableName .= '_' . strtolower($parts[$i]);
			}
		}
		return $tableName;
	}

	public static function splitUpper($s) {
		$matches = array();
		$matchCount = preg_match_all('/[A-Z][a-z]*/', $s, $matches);
		if ($matchCount > 0) {
			return $matches[0];
		}
		return array();
	}
	
	public static function propertyName($s) {
		$matches = array();
		$matchCount = preg_match_all('/^([a-z]+)((?:[A-Z][a-z]*)*)/', $s, $matches);
		$propertyName = '';
		if ($matchCount == 1 && count($matches) == 3) {
			$propertyName .= strtolower($matches[1][0]);
			$parts = self::splitUpper($matches[2][0]);
			foreach ($parts as $part) {
				$propertyName .= '_' . strtolower($part);
			}
		}
		return $propertyName;
	}
	
	public static function autoMap($c) {
		$properties = get_object_vars($c);
		foreach ($properties as $key => $value) {
			$properties[$key] = self::propertyName($key);
		}
		return $properties;
	}
	
	public function writeDB(&$c, $key = 'id') {
		$set = '';
		foreach ($this->map as $property => $field) {
			if ($property == $key || $property[0] == '_') {
				continue;
			}
			if ($set) {
				$set .= ', ';
			}
			$value = $c->$property;
			$set .= "$field='$value'";
		}
		if ($c->$key === null) {
			$sql = 'INSERT INTO `' . $this->table . '` SET ' . $set;
			$result = db_query($sql, _('Could not create recurring sale'));
			if ($result) {
				$c->$key = db_insert_id();
			}
		} else {
			$keyField = $this->map[$key];
			$id = $c->$key;
			$sql = 'UPDATE `' . $this->table . '` SET ' . $set . ' WHERE ' . $keyField . "='" . $id . "'";
			db_query($sql, _('Could not update recurring sale'));
		}
	}
	
	public function writeArray(&$c, &$data, $exclude = array()) {
		if ($data) {
			foreach ($this->map as $property => $field) {
				if ($property[0] == '_') {
					continue;
				}
				if (!in_array($property, $exclude)) {
					$data[$field] = $c->$property;
				}
			}
			return true;
		}
		return false;
	}
	
	public function readDBOneRow(&$c, $id, $key = 'id', $where = '') {
		$sql = $this->sql($id, $key, $where);
		$result = $this->query($sql, _('Could not read from database'));
		return $this->readRow($c, $result);
	}

	public function readDBMany($id, $key = 'id', $where = '') {
		$sql = $this->select($id, $key, $where);
		return $this->query($sql, _('Could not read from database'));
	}
	
	/**
	 * Returns a SQL SELECT statement
	 * @param string $id
	 * @param string $key
	 * @param string $where
	 * @return string
	 */
	public function select($id, $key = 'id', $where = '') {
		$keyField = $this->map[$key];
		// TODO Could make the '*' explicit from the map
		$sql = 'SELECT * FROM `' . $this->table . '` WHERE ' . $keyField . "='" . $id . "'";
		if ($where) {
			$sql .= ' AND ' . $where;
		}
		if ($where) {
			$sql .= ' AND ' . $where;
		}
		return $sql;
	}
	
	/**
	 * Escapes the given string $s
	 * @param string $s
	 * @return string
	 */
	public function escapeString($s) {
		$connection = MySQLConnection::connection($this->connectionName);
		return $connection->escape_string($s);
	}
	
	public function query($sql, $errorMessage = '') {
		$sql = str_replace('0_', $this->tablePrefix, $sql);
		$connection = MySQLConnection::connection($this->connectionName);
		$result = $connection->query($sql);
		if ($result === false) {
			if ($connection->errno != 0) {
				throw new \Exception("SQL Error: " . $connection->error);
			}
		}
		return $result;
	}
	
	public function readDBRow(&$c, $result) {
		$connection = MySQLConnection::connection($this->connectionName);
		$data = $result->fetch_assoc();
		return $this->readArray($c, $data);
	}
	
	public function readArray(&$c, $data, $exclude = array()) {
		if ($data) {
			foreach ($this->map as $property => $field) {
				if ($property[0] == '_') {
					continue;
				}
				if (!in_array($property, $exclude) && array_key_exists($field, $data)) {
					$c->$property = $data[$field];
				}
			}
			return true;
		}
		return false;
	}
	
	public function delete($id, $key = 'id') {
		$keyField = $this->map[$key];
		$sql = 'DELETE FROM `' . $this->table . '` WHERE ' . $keyField . "='" . $id . "'";
		$result = db_query($sql, _('Could not delete recurring sale'));
		return $result ? db_num_affected_rows() === 1 : false;
	}
	
}