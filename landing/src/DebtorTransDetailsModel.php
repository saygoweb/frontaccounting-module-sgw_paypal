<?php
namespace SGW_Landing;

use SGW_Landing\MySQLDataMapper;

class DebtorTransDetailsModel {

	public function __construct() {
		$this->_mapper = MySQLDataMapper::createByClass(DB_PREFIX, $this);
	}

	/**
	 * @var MySQLDataMapper
	 */
	public $_mapper;

	public $id;
	public $debtorTransNo;
	public $debtorTransType;
	public $stockId;
	public $description;
	public $unitPrice;
	public $unitTax;
	public $quantity;
	public $discountPercent;
	

	// 	public function write() {
	// 		$this->mapper->write($this);
	// 	}

}

// $m = new RecurringModel();
// $d = DataMapper::createByClass('0_', $m);
// $d->write($m);