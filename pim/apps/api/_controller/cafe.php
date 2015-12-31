<?php
/**
 * Cafe related web services
 */
class Controller_Cafe
{
	public function __construct()
	{
		$this->site = model::orm('site/site')->where('siteID', request::named('siteID'))->execute()->getFirst();

		if(!$this->site)
			exit(json_encode(array('status' => 'failed', 'message' => 'Site not found')));

		if(in_array(controller::getCurrentMethod(), array('auth', 'ping')))
			return;

		if(!request::param('access_token'))
			exit(json_encode(array('status' => 'failed', 'message' => 'access_token required')));

		if($this->site->getCafeToken() != request::param('access_token'))
			exit(json_encode(array('status' => 'failed', 'message' => 'Bad access_token')));
	}

	/**
	 * @return json
	 * - status : string
	 * - token : string
	 */
	public function auth()
	{
			$email = request::get('email');
			$password = request::get('password');

			$user = model::load('access/auth')->backendLoginCheck($email, $password);

			if($user)
			{
				$site = model::orm('site/site')
				->where('siteID IN (SELECT siteID FROM site_manager WHERE userID = ?)', array($user->userID))
				->where('siteID', $this->site->siteID)
				->execute()
				->getFirst();

				if($site)
					$response = array(
						'status' => 'success',
						'token' => $site->getCafeToken()
						);
			}
			else
			{
				$response = array(
					'status' => 'failed',
					'message' => 'Failed to authenticate.'
					);
			}

			return json_encode($response);
	}

	/**
	 * Get list of members
	 * @return json
	 * - status
	 * - members : array
	 *   - userID
	 *   - email
	 *   - fullName
	 *   - userIC
	 *   - occupation
	 *   - gender
	 *	 - updatedDate
	 */
	public function members()
	{
		$date = request::get('date', null);

		$members = db::where('user.userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($this->site->siteID))
		->select(array(
			'user.userID',
			'userEmail as email',
			'userPassword as password',
			'userProfileFullName as fullName',
			'userIC',
			'userProfileOccupationGroup as occupation',
			'userProfileGender as gender',
			'userUpdatedDate as updatedDate'
			))
		->join('user_profile', 'user_profile.userID = user.userID')
		->join('user_profile_additional', 'user_profile_additional.userID = user.userID')
		->order_by('userUpdatedDate ASC');

		if($date)
			db::where('userUpdatedDate > ?', $date);

		$members = db::get('user')->result('userID');

		$response = array(
			'status' => 'success',
			'data' => $members
			);

		return json_encode($response);
	}

	public function managers()
	{
		$managers = db::from('user')
		->select(array(
			'user.userID',
			'userEmail as email',
			'userPassword as password',
			'userProfileFullName as fullName',
			'userIC',
			'userProfileOccupationGroup as occupation'
			))
		->where('userLevel', 2)
		->where('user.userID IN (SELECT userID FROM site_manager WHERE siteManagerStatus = 1 AND siteID = ?)', array($this->site->siteID))
		->join('user_profile', 'user_profile.userID = user.userID')
		->join('user_profile_additional', 'user_profile_additional.userID = user.userID')
		->get()->result();


		$response = array(
			'status' => 'success',
			'data' => $managers
			);

			return json_encode($response);
	}

	/**
	 * Get list of billing items.
	 * @return json
	 * status
	 * data :
	 * - billingItemID
	 * - type
	 * - code
	 * - hotkey
	 * - name
	 * - description
	 * - priceType
	 * - price
	 * - priceNonmember
	 * - quantity
	 * - quantityDisabled
	 * - priceDisabled
	 * - status
	 * - createdDate
	 * - updatedDate
	 */
	public function billingItems()
	{
		$date = request::get('date');

		db::from('billing_item')
		->select(array(
			'billingItemID',
			'billingItemType as type',
			'billingItemCode as code',
			'billingItemHotkey as hotkey',
			'billingItemName as name',
			'billingItemDescription as description',
			'billingItemPriceType as priceType',
			'billingItemPrice as price',
			'billingItemPriceNonmember as priceNonmember',
			'billingItemQuantity as quantity',
			'billingItemQuantityDisabled as quantityDisabled',
			'billingItemPriceDisabled as priceDisabled',
			'billingItemStatus as status',
			'billingItemCreatedDate as createdDate',
			'billingItemUpdatedDate as updatedDate'
			));

		if($date)
			db::where('billingItemUpdatedDate > ?', array($date));

		$items = db::get()->result('billingItemID');

		$response = array(
			'status' => 'success',
			'data' => $items
			);

		return json_encode($response);
	}

	public function siteInfo()
	{
		$site = $this->site;
		$info = $site->info();

		return json_encode(array(
			'status' => 'success',
			'data' => array(
				'name' => $site->siteName,
				'address' => $info->siteInfoAddress,
				'phone' => $info->siteInfoPhone,
				'email' => $info->siteInfoEmail
				)
			));
	}

	/**
	 * Get last transaction localID
	 */
	public function lastTransactionDate()
	{
		$row = db::from('billing_transaction')
		->where('siteID', $this->site->siteID)
		->limit(1)
		->order_by('billingTransactionUpdatedDate DESC')
		->get()
		->row();
		
		return json_encode(array(
			'status' => 'success',
			'data' => $row['billingTransactionUpdatedDate']
			));
	}

	/*public function lastMemberRegisteredDate()
	{
		$row = db::from('user')
		->where('userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($this->site->siteID))
		->limit(1)
		->order_by('userID DESC')
		->get()
		->row();

		return json_encode(array(
			'status' => 'success',
			'data' => $row['userCreatedDate']
			));
	}*/

	/**
	 * Upload transactions.
	 * @param $_POST[transactions]
	 * array of :
	 * - transaction_id
	 * - total decimal
	 * - quantity decimal
	 * - datetime [datetime]
	 * - transaction_items array of
	 *   - transaction_item_id
	 *   - billing_item_id
	 *   - price
	 *   - quantity
	 * - user
	 *   - userID
	 *   - age
	 *   - occupation
	 */
	public function uploadTransactions()
	{
		$transactions = request::post('transactions');

		if(!$transactions)
			return json_encode(array(
				'status' => 'success',
				'total_transactions' => 0
				));

		$allIds = array_keys($transactions);

		// existing.
		$existing = db::from('billing_transaction')
		->where('siteID', $this->site->siteID)
		->where('billingTransactionLocalID', $allIds)->get()->result('billingTransactionLocalID');
		$localIds = array_keys($existing);

		$totalTransactions = 0;

		foreach($transactions as $row_transaction)
		{
			$localId = $row_transaction['transaction_id'];

			// update
			if(in_array($localId, $localIds))
			{
				$transaction = model::orm('billing/transaction')
									->where('siteID', $this->site->siteID)
									->where('billingTransactionLocalID', $localId)
									->execute()->getFirst();

				/*$totalTransactions++;

				$transaction->billingTransactionTotalQuantity = $row_transaction['quantity'];
				$transaction->billingTransactionTotal = $row_transaction['total'];
				$transaction->billingTransactionDate = $row_transaction['datetime'];
				$transaction->billingTransactionCreatedDate = $row_transaction['datetime'];
				$transaction->billingTransactionUpdatedDate = $row_transaction['datetime'];
				$transaction->save();*/
			}
			else
			{
				$totalTransactions++;

				$transaction = model::orm('billing/transaction')->create();
				$transaction->siteID = $this->site->siteID;
				$transaction->billingTransactionLocalID = $localId;
				$transaction->billingTransactionTotalQuantity = $row_transaction['quantity'];
				$transaction->billingTransactionStatus = 1;
				$transaction->billingTransactionTotal = $row_transaction['total'];
				$transaction->billingTransactionDate = $row_transaction['datetime'];
				// $transaction->billingTransactionCreatedDate = $row_transaction['datetime'];
				// $transaction->billingTransactionUpdatedDate = $row_transaction['datetime'];
				$transaction->billingTransactionCreatedDate = $row_transaction['createdDate'] ? : $row_transaction['datetime'];
				$transaction->billingTransactionUpdatedDate = $row_transaction['updatedDate'] ? : $row_transaction['datetime'];
				$transaction->billingTransactionUploaded = 1;
				$transaction->save();

				foreach($row_transaction['transaction_items'] as $localItemId => $row_transactionItem)
				{
					$transactionItem = model::orm('billing/transaction_item')->create();
					$transactionItem->billingTransactionID = $transaction->billingTransactionID;
					$transactionItem->billingItemID = $row_transactionItem['billing_item_id'];
					$transactionItem->billingTransactionItemPrice = $row_transactionItem['price'];
					$transactionItem->billingTransactionItemQuantity = $row_transactionItem['quantity'];
					$transactionItem->save();

					if(isset($row_transactionItem['pc_usage']))
					{
						/*
						billingPcUsageLocalID [int]
						billingTransactionItemID [int]
						billingPcUsageAsset [varchar]
						billingPcUsagePcNo [varchar]
						billingPcUsageStart [datetime]
						billingPcUsageEnd [datetime]*/
						$localPcUsage = $row_transactionItem['pc_usage'];

						$pcUsage = model::orm('billing/pc_usage')->create();
						$pcUsage->billingTransactionItemID = $transactionItem->billingTransactionItemID;
						$pcUsage->billingPcUsageLocalID = $localPcUsage['pc_usage_id'];
						$pcUsage->billingPcUsageAsset = $localPcUsage['asset'];
						$pcUsage->billingPcUsagePcNo = $localPcUsage['pc_no'];
						$pcUsage->billingPcUsageStart = $localPcUsage['start'];
						$pcUsage->billingPcUsageEnd = $localPcUsage['end'];
						$pcUsage->save();
					}
				}

				$transactionUser = model::orm('billing/transaction_user')->create();
				$transactionUser->billingTransactionID = $transaction->billingTransactionID;
				$transactionUser->billingTransactionUser = $row_transaction['user']['userID'];
				$transactionUser->billingTransactionUserAge = $row_transaction['user']['age'];
				$transactionUser->billingTransactionUserOccupationGroup = $row_transaction['user']['occupation'];
				$transactionUser->save();
			}
		}

		return json_encode(array(
			'status' => 'success',
			'total_transactions' => $totalTransactions
			));
	}

	public function ping()
	{
		$siteCafe = db::where('siteID', $this->site->siteID)->get('site_cafe')->row();

		if(!$siteCafe)
		{
			db::insert('site_cafe', array(
				'siteID' => $this->site->siteID,
				'siteCafeIpAddress' => $_REQUEST['REMOTE_ADDR'],
				'siteCafeLastAccess' => date('Y-m-d H:i:s')
				));
		}
		else
		{
			db::where('siteCafeID', $siteCafe['siteCafeID'])
			->update('site_cafe', array(
				'siteCafeIpAddress' => $_REQUEST['REMOTE_ADDR'],
				'siteCafeLastAccess' => date('Y-m-d H:i:s')
				));
		}

		return json_encode(array(
			'status' => 'success'
			));
	}

	protected function getCafeVersion()
	{
		$path = apps::$root.'../repo/cafe/.git/refs/heads/master';
		// $cafeRoot = apps::$root.'../repo/cafe';

		$currentVersion = file_get_contents($path);

		return trim($currentVersion);
	}

	public function getVersion()
	{
		$currentVersion = $this->getCafeVersion();

		return json_encode(array(
			'status' => 'success',
			'data' => $currentVersion));
	}

	public function getPatches()
	{
		$version = request::get('version');

		// get version from cafe's repo.
		// $path = apps::$root.'../repo/cafe/.git/refs/heads/master';
		$cafeRoot = apps::$root.'../repo/cafe';

		$currentVersion = $this->getCafeVersion();

		/*if($version == $currentVersion)
		{
			return json_encode(array(
				'status' => 'failed',
				'message' => 'Already up to latest version'
				));
		}*/

		chdir($cafeRoot);
		$command = 'git diff --name-status '.$version.' '.$currentVersion;
		$content = trim(trim($ctn = shell_exec($command)), "\n");

		if($content == "")
		{
			return json_encode(array(
				'status' => 'updated',
				'version' => $currentVersion
				));
		}

		$files = explode("\n", $content);

		$response = array(
			'status' => 'success',
			'version' => $currentVersion,
			'updates' => ''
			);

		$updates = array();

		$changeTypes = array(
			'A' => 'new',
			'M' => 'modified',
			'D' => 'deleted'
			);
		foreach($files as $path)
		{
			list($changeType, $path) = explode("\t", $path, 2);

			$changeType = $changeTypes[$changeType];

			$fileContent = null;
			if(in_array($changeType, array('new', 'modified')))
				$fileContent = file_get_contents($path);

			$updates[] = array(
				'name' => $path,
				'md5' => md5($fileContent),
				'content' => $fileContent,
				'change_type' => $changeType
				);
		}

		$response['updates'] = base64_encode(serialize($updates));

		return json_encode($response);
	}
}


?>