<?php
namespace SGW_Landing;

use SGW_Landing\MySQLDataMapper;

require_once(__DIR__ . '/../Config.php');

class DebtorTransModel {

	public function __construct() {
		$this->_mapper = MySQLDataMapper::createByClass(DB_PREFIX, $this);
	}

	/**
	 * @var MySQLDataMapper
	 */
	public $_mapper;

	public $transNo;
	public $type;
	public $tranDate;
	public $dueDate;
	public $reference;
	public $name;
	
	public $_items;
	
	public function read($reference) {
		$sql = "SELECT
			dt.trans_no,
			dt.type,
			dt.debtor_no,
			dt.tran_date,
			dt.due_date,
			dt.reference,
			dm.name
			FROM 0_debtor_trans AS dt
			JOIN 0_debtors_master AS dm ON dt.debtor_no=dm.debtor_no
			WHERE dt.reference='%s'";
		$sql = sprintf($sql, $this->_mapper->escapeString($reference));
		$result = $this->_mapper->query($sql);
		$this->_items = array();
		if ($result && $this->_mapper->readDBRow($this, $result)) {
			$detailModel = new DebtorTransDetailsModel();
			$result = $detailModel->_mapper->readDBMany($this->transNo, 'debtorTransNo', "debtor_trans_type='10'");
			while ($detailModel->_mapper->readDBRow($detailModel, $result)) {
				$this->_items[] = clone $detailModel;
			}
		}
	}
	
}
