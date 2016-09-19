<?php
use SGW_Landing\View;
use SGW_Landing\DebtorTransModel;
use SGW_Landing\MySQLConnection;

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/Config.php');
require_once(ROOT_PATH . '/config_db.php');

MySQLConnection::connection('default', $db_connections[0]);

// var_dump($_SERVER);
$reference = '';
if (array_key_exists('PATH_INFO', $_SERVER)) {
	$reference = trim($_SERVER['PATH_INFO'], '/');
}

$invoice = new DebtorTransModel();
$invoice->read($reference);

// var_dump($invoice);

$view = new View('page');

$button = '<button class="btn btn-primary">Pay Now</button>';
	
$cart = new View('cart');
$cart->set('invoice', $invoice);
$cart->set('paynow', $button);
$view->set('content', $cart->renderString());
$view->set('moduleRoot', '/modules/sgw_paypal');

$view->render();

