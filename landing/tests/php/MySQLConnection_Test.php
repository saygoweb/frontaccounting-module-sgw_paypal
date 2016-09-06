<?php
namespace SGW_Landing_Tests;

use SGW_Landing\MySQLConnection;

require_once(__DIR__ . '/../../vendor/autoload.php');

require_once(__DIR__ . '/TestConfig.php');

require_once(ROOT_PATH . '/config_db.php');

define ('TB_PREF', '&TB_PREF&');

class MySQLConnection_Test extends \PHPUnit_Framework_TestCase
{
	function testConnect_OK() {
		global $db_connections;
		
		$actual1 = MySQLConnection::connection('default', $db_connections[0]);
		$this->assertInstanceOf('\mysqli', $actual1);
	
		$actual2 = MySQLConnection::connection('default');
		$this->assertInstanceOf('\mysqli', $actual2);
		$this->assertSame($actual1, $actual2);
	}
}