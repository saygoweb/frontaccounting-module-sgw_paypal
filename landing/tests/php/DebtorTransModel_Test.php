<?php
namespace SGW_Landing_Tests;

use SGW_Landing\MySQLConnection;
use SGW_Landing\DebtorTransModel;

require_once(__DIR__ . '/../../vendor/autoload.php');

require_once(__DIR__ . '/TestConfig.php');

require_once(ROOT_PATH . '/config_db.php');

class DebtorTransModel_Test extends \PHPUnit_Framework_TestCase
{
	static function setupBeforeClass() {
		global $db_connections;
		
		$sql = <<<EOD
		DELETE FROM `0_debtor_trans` WHERE trans_no=999;
		DELETE FROM `0_debtor_trans_details` WHERE debtor_trans_no=999;
		REPLACE INTO `0_debtors_master` (`debtor_no`, `name`, `debtor_ref`, `address`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`) VALUES(1, 'Donald Easter LLC', 'Donald Easter', 'N/A', '123456789', 'USD', 1, 0, 0, 1, 4, 0, 0, 1000, '', 0);
		REPLACE INTO `0_debtors_master` (`debtor_no`, `name`, `debtor_ref`, `address`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`) VALUES(2, 'MoneyMaker Ltd.', 'MoneyMaker', 'N/A', '54354333', 'EUR', 1, 1, 0, 1, 1, 0, 0, 1000, '', 0);
		INSERT INTO `0_debtor_trans` (`trans_no`, `type`, `version`, `debtor_no`, `branch_code`, `tran_date`, `due_date`, `reference`, `tpe`, `order_`, `ov_amount`, `ov_gst`, `ov_freight`, `ov_freight_tax`, `ov_discount`, `alloc`, `prep_amount`, `rate`, `ship_via`, `dimension_id`, `dimension2_id`, `payment_terms`, `tax_included`) VALUES(999, 10, 0, 1, 1, '2015-05-10', '2015-05-05', '159999', 1, 1, 6240, 0, 0, 0, 0, 6240, 0, 1, 1, 0, 0, 4, 1);
		INSERT INTO `0_debtor_trans_details` (`debtor_trans_no`, `debtor_trans_type`, `stock_id`, `description`, `unit_price`, `unit_tax`, `quantity`, `discount_percent`, `standard_cost`, `qty_done`, `src_id`) VALUES(999, 10, '101', 'iPad Air 2 16GB', 300, 14.2855, 20, 0, 200, 0, 1);
		INSERT INTO `0_debtor_trans_details` (`debtor_trans_no`, `debtor_trans_type`, `stock_id`, `description`, `unit_price`, `unit_tax`, `quantity`, `discount_percent`, `standard_cost`, `qty_done`, `src_id`) VALUES(999, 10, '301', 'Support', 80, 3.81, 3, 0, 0, 0, 2);
EOD;
		$db = MySQLConnection::connection('default', $db_connections[0]);
		$result = $db->multi_query($sql);
		if (!$result) {
			throw new \Exception($db->error);
		}
		// Swallow the multiple results from multi_query
		do {} while ($db->next_result());
	}
	
	static function tearDownAfterClass() {
		$sql = <<<EOD
		DELETE FROM `0_debtor_trans` WHERE trans_no=999;
		DELETE FROM `0_debtor_trans_details` WHERE debtor_trans_no=999;
EOD;
		$db = MySQLConnection::connection('default');
		$result = $db->multi_query($sql);
		if (!$result) {
			throw new \Exception($db->error);
		}
		// Swallow the multiple results from multi_query
		do {} while ($db->next_result());
	}
	
	function testRead_2Details_OK() {
		$m = new DebtorTransModel();
		$m->read('159999');
// 		var_dump($m);
		$this->assertEquals(2, count($m->_items));
		$this->assertEquals('159999', $m->reference);
		$this->assertEquals(300, $m->_items[0]->unitPrice);
		$this->assertEquals(80, $m->_items[1]->unitPrice);
		
	}
}