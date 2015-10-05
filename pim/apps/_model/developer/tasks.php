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
			'description' => 'Migrate list of task'
			));

		$manager->addTask('addRootCoordinator', array(
			'description' => 'add root (coordinator)'
			));
		
		$manager->addTask('addOperationManager', array(
			'description' => 'add Operation Manager'
			));

		$manager->addTask('purchaseRequisitionCategory', array(
			'description' => 'P1IM expense category'
			));

		$manager->addTask('purchaseRequisitionItem', array(
			'description' => 'P1IM expense Item'
			));

		$manager->addTask('purchaseRequisitionExpenditure', array(
			'description' => 'P1IM expenditure'
			));
	}

	public function billingItem()
	{
		db::query("INSERT INTO `billing_item` VALUES (1,'A','Membership Student',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:00:36',NULL,NULL,1),(2,'B','Membership Adult',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:03:09',NULL,NULL,1),(3,'C','Monthly Wifi Fee, Student',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:04:32',NULL,NULL,1),(4,'D','Monthly Wifi Fee, Adult',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:05:04',NULL,NULL,1),(5,'E','PC, Member Student',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:05:55',NULL,NULL,1),(6,'F','PC, NonMem Student',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:06:19',NULL,NULL,1),(7,'G','PC, Member Adult',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:06:40',NULL,NULL,1),(8,'H','PC, NonMem Adult',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:07:03',NULL,NULL,1),(9,'I','Printing & Photostat B&W',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:07:28',NULL,NULL,1),(10,'J','Other Service',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:08:01',NULL,NULL,1),(11,'K','Printing & Photostat Color',NULL,1,1,1,NULL,NULL,1,NULL,'2015-05-29 11:09:21',NULL,NULL,1),(12,'L','Scanning',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:10:13',NULL,NULL,1),(13,'M','Laminating (A4)',NULL,1.5,1,1,NULL,1,1,NULL,'2015-05-29 11:10:41',NULL,NULL,1),(14,'Y','Utilities',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:04',NULL,NULL,1),(15,'Z','Transfer to NuSuara',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:32',NULL,NULL,1),(16,'X','Sewaan Macbook Daily',NULL,5,1,1,NULL,1,1,1,'2015-06-05 18:14:51',NULL,NULL,1),(19,'N','Transfer Out ',NULL,0,1,1,NULL,1,1,1,'2015-06-18 11:33:22',NULL,2,1)");

	}

	public function purchaseRequisitionCategory()
	{
		db::query("INSERT INTO `purchase_requisition_category` VALUES (1,'Utilities','2015-06-16 00:00:00'),(2,'Cleaning Service','2015-06-16 00:00:00'),(3,'Admin & Other Expenses','2015-06-16 00:00:00'),(4,'Training & Awareness','2015-06-16 00:00:00'),(5,'Office Equipment & Consumable','2015-06-16 00:00:00')");

	}

	public function purchaseRequisitionItem()
	{
		db::query("INSERT INTO `purchase_requisition_item` VALUES (1,1,1,0,'Water','2015-06-17 00:00:00',1),(2,1,1,0,'Electricity','2015-06-17 00:00:00',1),(3,1,1,0,'Astro','2015-06-17 00:00:00',1),(4,2,1,0,'Sundries','2015-06-17 00:00:00',1),(5,2,1,0,'Cleaning & Landscape','2015-06-17 00:00:00',1),(6,4,1,0,'Gift - ICT Training & Program','2015-06-19 00:00:00',1),(7,4,1,0,'Monthly Event','2015-06-19 00:00:00',1),(8,4,1,0,'PI1M Launching','2015-06-19 00:00:00',1),(9,4,1,0,'Open Day','2015-06-19 00:00:00',1),(10,3,1,0,'Stationary','2015-06-19 00:00:00',1),(11,5,1,0,'ICT Equipment','2015-06-19 00:00:00',1),(12,5,1,0,'Ink & Toner','2015-06-19 00:00:00',1)");
	}

	public function purchaseRequisitionExpenditure()
	{
		db::query("INSERT INTO `purchase_requisition_expenditure` VALUES (1,1,'PI1M Expenses','2015-09-25 10:18:10',1),(2,1,'PI1M Equipment','2015-09-23 00:00:00',1),(3,2,'Scheduled Event','2015-09-25 10:25:15',1),(4,2,'Ad hoc Event','2015-09-23 00:00:00',1),(5,3,'Other','2015-09-23 00:00:00',1),(6,3,'1Citizen','2015-09-23 00:00:00',1)");
	}

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
}


?>