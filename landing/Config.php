<?php

$rootPath = realpath(__DIR__ . '/../../../');

if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', $rootPath);
}

define('DB_PREFIX', '0_');