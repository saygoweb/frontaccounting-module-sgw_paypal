<?php
namespace SGW_Paypal\common;

use SGW_Base\BaseConverter;

class Checksum {
	
	const DIGITS_26 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	public static function checksum26($s) {
		return crc32($s) % 676; // 676 = 26*26 being 2 digits base 26
	}
	
	public static function encode($s) {
		$cs = self::checksum26($s);
		$base = new BaseConverter(self::DIGITS_26);
		$csDigits = $base->toDigits($cs);
		if ($cs < 26) {
			$csDigits = 'A' . $csDigits;
		}
		return $s . $csDigits;
	}

	public static function decode($s) {
		$c = strlen($s);
		if ($c <= 2) {
			return false;
		}
		$csDigits = substr($s, $c - 2, 2);
		$base = new BaseConverter(self::DIGITS_26);
		$csActual = $base->fromDigits($csDigits);
		$s = substr($s, 0, $c - 2);
		return array($s, $csActual);
	}
	
	/**
	 * Returns true if the given string $s is valid
	 * @param string $s
	 * @param integer $csActual
	 * @return boolean
	 */
	public static function isValid26($s, $csActual) {
		$csExpected = self::checksum26($s);
		return $csActual == $csExpected;
	}
	
}