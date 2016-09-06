<?php

require_once(__DIR__ . '/../../../vendor/autoload.php');

use SGW_Paypal\common\Checksum;

class ChecksumTest extends PHPUnit_Framework_TestCase
{
	function testChecksum26_Ok() {
		$s = 'SOME_TEST_STRING';
		
		$sEncode = Checksum::encode($s);
		$checksum = Checksum::checksum26($s);
		$this->assertEquals(415, $checksum);
		
		$sDecode = Checksum::decode($sEncode);
		
		$this->assertEquals($s, $sDecode[0]);
		
		$actual = Checksum::isValid26($sDecode[0], $sDecode[1]);
		$this->assertTrue($actual);
		
	}
}