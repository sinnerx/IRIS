<?php
namespace model\developer;
use db;

/**
 * list of tasks to be routed to.
 */

class Tasks
{
	/**
	 * List of task registered.
	 * Please register first, before your function.
	 */
	public function registry($manager)
	{
		/* example
		$manager->addTask('addUser', array(
			'description' => 'heheheh'
			));*/
		$manager->addTask('billingItem', array(
			'description' => 'Migrate list of predefined billing items'
			));

		$manager->addTask('billingReset', array(
			'repeatable' => true,
			'description' => 'Reset billing'
			));

		$manager->addTask('updateBillingItemUpdatedDate', array(
			'description' => 'Update billing item updated date'
			));

		$manager->addTask('addRootCoordinator', array(
			'description' => 'add root (coordinator)'
			));
		
		$manager->addTask('addOperationManager', array(
			'description' => 'add Operation Manager'
			));

		/*$manager->addTask('purchaseRequisitionCategory', array(
			'description' => 'P1IM expense category'
			));

		$manager->addTask('purchaseRequisitionItem', array(
			'description' => 'P1IM expense Item'
			));*/

		$manager->addTask('prCategoryAndItem', array(
			'description' => 'P1IM expense category and item'
			));

		$manager->addTask('prCategoryAndItemNew', array(
			'description' => 'P1IM expense category and item'
			));

		/*$manager->addTask('purchaseRequisitionExpenditure', array(
			'description' => 'P1IM expenditure'
			));*/

		$manager->addTask('prExpenditureNew', array(
			'description' => 'P1IM expenditure'
			));

		$manager->addTask('prExpenditure', array(
			'description' => 'P1IM expenditure'
			));

		$manager->addTask('updateUserUpdatedDate', array(
			'description' => 'Update User Updated Date With User Created Date'
			));

		$manager->addTask('pageAddDefault', array(
			'repeatable' => true,
			'description' => 'Add another 6 page_default for skmm'
			));

		$manager->addTask('pageLocalDataMigrate', array(
			'description' => 'Migrate new pages',
			'repeatable' => true
			));
	}

	public function pageAddDefault()
	{
		$defaults = array(
			array(1, 'Mengenai Kami', 'mengenai-kami'),
			array(2, 'Pengurusan', 'pengurusan'),
			array(3, 'AJK Pi1M', 'ajk'),
			array(4, 'Maklumat Tempatan', 'maklumat-tempatan'),
			array(5, 'Perlancongan & Rekreasi', 'perlancongan-rekreasi'),
			array(6, 'Kemudahan Awam', 'kemudahan-awam'),
			array(7, 'Ekonomi dan Keusahawanan', 'ekonomi-keusahawanan'),
			array(8, 'Komuniti', 'komuniti'),
			array(9, 'Pendidikan', 'pendidikan'));

		foreach($defaults as $default)
		{
			if(db::where('pageDefaultType', $default[0])->get('page_default')->row())
				continue;

			db::insert('page_default', array(
				'pageDefaultType' => $default[0],
				'pageDefaultName' => $default[1],
				'pageDefaultSlug' => $default[2]
				));
		}
	}

	public function pageLocalDataMigrate()
	{
		$defaults = db::get('page_default')->result('pageDefaultType');

		foreach(orm('site/site')->execute() as $site)
			$site->initiateDefaultPages(orm('page/page_default')->execute());
	}

	public function billingItem()
	{
		/*db::query("INSERT INTO `billing_item` VALUES (1,'A','
			Membership Student',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:00:36',NULL,NULL,1),(2,'B','
			Membership Adult',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:03:09',NULL,NULL,1),(3,'C','
			Monthly Wifi Fee, Student',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:04:32',NULL,NULL,1),(4,'D','
			Monthly Wifi Fee, Adult',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:05:04',NULL,NULL,1),(5,'E','
			PC, Member Student',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:05:55',NULL,NULL,1),(6,'F','
			PC, NonMem Student',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:06:19',NULL,NULL,1),(7,'G','
			PC, Member Adult',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:06:40',NULL,NULL,1),(8,'H','
			PC, NonMem Adult',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:07:03',NULL,NULL,1),(9,'I','
			Printing & Photostat B&W',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:07:28',NULL,NULL,1),(10,'J','
			Other Service',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:08:01',NULL,NULL,1),(11,'K','
			Printing & Photostat Color',NULL,1,1,1,NULL,NULL,1,NULL,'2015-05-29 11:09:21',NULL,NULL,1),(12,'L','
			Scanning',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:10:13',NULL,NULL,1),(13,'M','
			Laminating (A4)',NULL,1.5,1,1,NULL,1,1,NULL,'2015-05-29 11:10:41',NULL,NULL,1),(14,'Y','
			Utilities',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:04',NULL,NULL,1),(15,'Z','
			Transfer to NuSuara',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:32',NULL,NULL,1),(16,'X','
			Sewaan Macbook Daily',NULL,5,1,1,NULL,1,1,1,'2015-06-05 18:14:51',NULL,NULL,1),(19,'N','
			Transfer Out ',NULL,0,1,1,NULL,1,1,1,'2015-06-18 11:33:22',NULL,2,1)");*/

			/*db::query("INSERT INTO `billing_item` (`billingItemID`, `billingItemHotkey`, `billingItemName`, `billingItemDescription`, `billingItemPrice`, `billingItemUnit`, `billingItemQuantity`, `billingItemTaxDisabled`, `billingItemDescriptionDisabled`, `billingItemPriceDisabled`, `billingItemUnitDisabled`, `billingItemQuantityDisabled`, `billingItemCreatedDate`, `billingItemType`, `billingItemStatus`, `billingItemUpdatedDate`, `billingItemPriceType`, `billingItemPriceNonmember`) VALUES
(1, 'A', 'Membership', NULL, 5, NULL, 1, NULL, NULL, 1, NULL, 1, '2015-10-22 11:10:10', 1, 1, '2015-10-22 11:10:10', 1, NULL),
(2, 'B', 'Monthly Wifi', NULL, 0, NULL, 1, NULL, NULL, 1, NULL, 1, '2015-10-22 08:47:25', 1, 1, '2015-10-22 08:47:25', 1, NULL),
(3, 'C', 'PC Usage', NULL, 2, NULL, 1, NULL, NULL, 1, NULL, 1, NULL, 1, 1, '2015-10-22 08:47:42', 2, 3),
(4, 'D', 'Printing & Photostat B&W', NULL, 0.2, NULL, 1, NULL, NULL, 1, NULL, 1, '2015-10-22 09:17:52', 1, 1, '2015-10-22 09:17:52', 1, NULL),
(5, 'E', 'Other Service', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, 0, '2015-10-22 09:04:02', 1, 1, '2015-10-22 09:04:02', 1, NULL),
(6, 'F', 'Printing & Photostat Color', NULL, 0.5, NULL, 1, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2015-10-22 08:49:29', 1, NULL),
(7, 'G', 'Scanning', NULL, 1, NULL, 1, NULL, NULL, 1, NULL, 0, '2015-10-22 11:17:31', 1, 1, '2015-10-22 11:17:31', 2, 2),
(8, 'H', 'Laminating', NULL, 2, NULL, 1, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, '2015-10-22 08:49:58', 1, NULL),
(9, 'I', 'Utilities', NULL, 2, NULL, 1, NULL, NULL, 0, NULL, 0, '2015-10-22 09:22:12', 1, 1, '2015-10-22 09:22:12', 1, NULL),
(10, 'J', 'Transfer To Nusuara', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, 1, '2015-10-22 09:56:17', 2, 1, '2015-10-22 09:56:17', 1, NULL);");*/

		$data['Membership'] = array(
			'billingItemHotkey' => 'A',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 5,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 1,
			'billingItemQuantityDisabled' => 1,
			'billingItemType' => 1,
			);

		$data['Monthly Wifi'] = array(
			'billingItemHotkey' => 'B',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 1,
			'billingItemQuantityDisabled' => 1,
			'billingItemType' => 1,
			);

		$data['PC Usage'] = array(
			'billingItemHotkey' => 'C',
			'billingItemPriceType' => 2,
			'billingItemPrice' => 1,
			'billingItemPriceNonmember' => 2,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			'billingItemCode' => 'pc_usage'
			);

		$data['Printing & Photostat B&W'] = array(
			'billingItemHotkey' => 'D',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0.2,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 1,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Other Service'] = array(
			'billingItemHotkey' => 'E',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Printing & Photostat Color'] = array(
			'billingItemHotkey' => 'F',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 1,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 1,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Scanning'] = array(
			'billingItemHotkey' => 'G',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0.2,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 1,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Laminating'] = array(
			'billingItemHotkey' => 'H',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 2,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Utilities'] = array(
			'billingItemHotkey' => 'I',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 0,
			'billingItemType' => 1,
			);

		$data['Transfer to Nusuara (Collection)'] = array(
			'billingItemHotkey' => 'J',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 1,
			'billingItemType' => 2, // credit
			'billingItemCode' => 'transfer_collection'
			);

		$data['Transfer to Nusuara (RL)'] = array(
			'billingItemHotkey' => 'K',
			'billingItemPriceType' => 1,
			'billingItemPrice' => 0,
			'billingItemPriceNonmember' => null,
			'billingItemQuantity' => 1,
			'billingItemPriceDisabled' => 0,
			'billingItemQuantityDisabled' => 1,
			'billingItemType' => 2, // credit
			'billingItemCode' => 'transfer_rl'
			);

		foreach($data as $itemName => $row)
		{
			$row['billingItemCreatedDate'] = now();
			$row['billingItemStatus'] = 1;
			$row['billingItemName'] = $itemName;

			db::insert('billing_item', $row);
		}
		// db::where('billingItemName', 'PC Usage')->update('billing_item', array('billingItemCode' => 'pc_usage'));
	}

	public function billingReset()
	{
				db::query("TRUNCATE `billing_approval`;
		TRUNCATE `billing_approval_level`;
		TRUNCATE `billing_finance_transaction`;
		TRUNCATE `billing_item`;
		TRUNCATE `billing_item_code`;
		TRUNCATE `billing_log`;
		TRUNCATE `billing_transaction`;
		TRUNCATE `billing_transaction_item`;
		TRUNCATE `billing_pc_usage`;
		TRUNCATE `billing_transaction_upload`;
		TRUNCATE `billing_transaction_user`;
		TRUNCATE `billing_verification`;");

		// remove log for billing item.
		db::delete('task_log', array(
			'taskCode' => 'billingItem'
			));
	}

	/*public function purchaseRequisitionCategory()
	{
		db::query("INSERT INTO `purchase_requisition_category` VALUES (1,'Utilities','2015-06-16 00:00:00'),(2,'Cleaning Service','2015-06-16 00:00:00'),(3,'Admin & Other Expenses','2015-06-16 00:00:00'),(4,'Training & Awareness','2015-06-16 00:00:00'),(5,'Office Equipment & Consumable','2015-06-16 00:00:00')");

	}*/

	public function prCategoryAndItemNew()
	{
		$categories = array(
			'Utilities' => array(
				'Water', 'Electricity', 'Astro'
				),
			'Cleaning Service' => array(
				'Sundries', 'Cleaning & Landscape'
				),
			'Admin & Other Expenses' => array(
				'Stationary'
				),
			'Training & Awareness' => array(
				'Gift - ICT Training & Program', 'Monthly Event', 'PI1M Launching', 'Open Day'
				),
			'Office Equipment & Consumable' => array(
				'ICT Equipment', 'Ink & Toner'
				)
			);

		foreach($categories as $category => $items)
		{
			db::insert('expense_category', array(
				'expenseCategoryName' => $category,
				'expenseCategoryCreatedDate' => date('Y-m-d H:i:s')
				));

			$id = db::getLastID('expense_category', 'expenseCategoryID');

			// category items
			foreach($items as $itemName)
			{
				db::insert('expense_item', array(
					'expenseCategoryID' => $id,
					'expenseItemName' => $itemName,
					'expenseItemCreatedDate' => date('Y-m-d H:i:s'),
					'expenseItemStatus' => 1
					));
			}
		}
	}

	public function prExpenditureNew()
	{
		$expenditures['data'] = array(
			array(1, 'PI1M Expenses'),
			array(1, 'PI1M Equipment'),
			array(2, 'Scheduled Event'),
			array(2, 'Ad hoc Event'),
			array(3, 'Other'),
			array(3, '1Citizen')
		);

		foreach($expenditures['data'] as $row)
			db::insert('expense_expenditure', array(
				'expenseExpenditureSet' => $row[0],
				'expenseExpenditureName' => $row[1],
				'expenseExpenditureStatus' => 1,
				'expenseExpenditureCreatedDate' => date('Y-m-d H:i:s')
				));
	}

	public function prCategoryAndItem()
	{
		$categories = array(
			'Utilities' => array(
				'Water', 'Electricity', 'Astro'
				),
			'Cleaning Service' => array(
				'Sundries', 'Cleaning & Landscape'
				),
			'Admin & Other Expenses' => array(
				'Stationary'
				),
			'Training & Awareness' => array(
				'Gift - ICT Training & Program', 'Monthly Event', 'PI1M Launching', 'Open Day'
				),
			'Office Equipment & Consumable' => array(
				'ICT Equipment', 'Ink & Toner'
				)
			);

		foreach($categories as $category => $items)
		{
			db::insert('purchase_requisition_category', array(
				'purchaseRequisitionCategoryName' => $category,
				'purchaseRequisitionCategoryCreatedDate' => date('Y-m-d H:i:s')
				));

			$id = db::getLastID('purchase_requisition_category', 'purchaseRequisitionCategoryID');

			// category items
			foreach($items as $itemName)
			{
				db::insert('purchase_requisition_item', array(
					'purchaseRequisitionCategoryID' => $id,
					'purchaseRequisitionItemName' => $itemName,
					'purchaseRequisitionItemCreatedDate' => date('Y-m-d H:i:s'),
					'purchaseRequisitionItemStatus' => 1
					));
			}
		}
	}

	/*public function purchaseRequisitionItem()
	{


		db::query("INSERT INTO `purchase_requisition_item` VALUES (1,1,1,0,'Water','2015-06-17 00:00:00',1),(2,1,1,0,'Electricity','2015-06-17 00:00:00',1),(3,1,1,0,'Astro','2015-06-17 00:00:00',1),(4,2,1,0,'Sundries','2015-06-17 00:00:00',1),(5,2,1,0,'Cleaning & Landscape','2015-06-17 00:00:00',1),(6,4,1,0,'Gift - ICT Training & Program','2015-06-19 00:00:00',1),(7,4,1,0,'Monthly Event','2015-06-19 00:00:00',1),(8,4,1,0,'PI1M Launching','2015-06-19 00:00:00',1),(9,4,1,0,'Open Day','2015-06-19 00:00:00',1),(10,3,1,0,'Stationary','2015-06-19 00:00:00',1),(11,5,1,0,'ICT Equipment','2015-06-19 00:00:00',1),(12,5,1,0,'Ink & Toner','2015-06-19 00:00:00',1)");
	}*/

	public function prExpenditure()
	{
		$expenditures['data'] = array(
			array(1, 'PI1M Expenses'),
			array(1, 'PI1M Equipment'),
			array(2, 'Scheduled Event'),
			array(2, 'Ad hoc Event'),
			array(3, 'Other'),
			array(3, '1Citizen')
		);

		foreach($expenditures['data'] as $row)
			db::insert('purchase_requisition_expenditure', array(
				'purchaseRequisitionExpenditureSet' => $row[0],
				'purchaseRequisitionExpenditureName' => $row[1],
				'purchaseRequisitionExpenditureStatus' => 1,
				'purchaseRequisitionExpenditureCreatedDate' => date('Y-m-d H:i:s')
				));
	}
	/*public function purchaseRequisitionExpenditure()
	{
		db::query("INSERT INTO `purchase_requisition_expenditure` VALUES (1,1,'PI1M Expenses','2015-09-25 10:18:10',1),(2,1,'PI1M Equipment','2015-09-23 00:00:00',1),(3,2,'Scheduled Event','2015-09-25 10:25:15',1),(4,2,'Ad hoc Event','2015-09-23 00:00:00',1),(5,3,'Other','2015-09-23 00:00:00',1),(6,3,'1Citizen','2015-09-23 00:00:00',1)");
	}*/

	public function addRootCoordinator()
	{
		$data = array(
			'userIC' => 'COORDINATOR',
			'userPassword' => 12345,
			'userEmail' => 'coordinator@nusuara.com',
			'userProfileFullName' => 'coordinator'
			);

		$row = \model::load('user/user')->add($data, 99);
	}

	public function addOperationManager()
	{
		$data = array(
			'userIC' => 'OPSMANAGER',
			'userPassword' => 12345,
			'userEmail' => 'opsmanager@gmail.com',
			'userProfileFullName' => 'Operation Manager'
			);

		$row = \model::load('user/user')->add($data, 4);
	}

	public function updateUserUpdatedDate()
	{
		db::query("UPDATE user SET userUpdatedDate = userCreatedDate");
	}

	public function updateBillingItemUpdatedDate()
	{
		db::query('UPDATE billing_item SET billingItemUpdatedDate = billingItemCreatedDate');
	}
}


?>