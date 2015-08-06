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
	}

	public function billingItem()
	{
		db::query("INSERT INTO `billing_item` VALUES (1,'A','Membership Student',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:00:36',NULL,NULL,1),(2,'B','Membership Adult',NULL,5,1,1,NULL,NULL,NULL,1,'2015-05-29 11:03:09',NULL,NULL,1),(3,'C','Monthly Wifi Fee, Student',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:04:32',NULL,NULL,1),(4,'D','Monthly Wifi Fee, Adult',NULL,0,1,1,NULL,1,1,1,'2015-05-29 11:05:04',NULL,NULL,1),(5,'E','PC, Member Student',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:05:55',NULL,NULL,1),(6,'F','PC, NonMem Student',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:06:19',NULL,NULL,1),(7,'G','PC, Member Adult',NULL,1,1,1,NULL,1,1,1,'2015-05-29 11:06:40',NULL,NULL,1),(8,'H','PC, NonMem Adult',NULL,2,1,1,NULL,1,1,1,'2015-05-29 11:07:03',NULL,NULL,1),(9,'I','Printing & Photostat B&W',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:07:28',NULL,NULL,1),(10,'J','Other Service',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:08:01',NULL,NULL,1),(11,'K','Printing & Photostat Color',NULL,1,1,1,NULL,NULL,1,NULL,'2015-05-29 11:09:21',NULL,NULL,1),(12,'L','Scanning',NULL,0.2,1,1,NULL,1,1,NULL,'2015-05-29 11:10:13',NULL,NULL,1),(13,'M','Laminating (A4)',NULL,1.5,1,1,NULL,1,1,NULL,'2015-05-29 11:10:41',NULL,NULL,1),(14,'Y','Utilities',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:04',NULL,NULL,1),(15,'Z','Transfer to NuSuara',NULL,0,1,1,NULL,1,NULL,NULL,'2015-05-29 11:11:32',NULL,NULL,1),(16,'X','Sewaan Macbook Daily',NULL,5,1,1,NULL,1,1,1,'2015-06-05 18:14:51',NULL,NULL,1),(17,'N','N item',NULL,5,1,1,NULL,1,1,1,'2015-06-18 09:16:18',NULL,1,0),(18,'N','Transfer out',NULL,50,1,1,NULL,1,1,1,'2015-06-18 11:31:24',NULL,2,0),(19,'N','Transfer Out ',NULL,0,1,1,NULL,1,1,1,'2015-06-18 11:33:22',NULL,2,1)");

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
}


?>