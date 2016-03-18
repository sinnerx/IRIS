<?php
/**
 * Cafe related web services
 */
class Controller_Cafe
{
	public function __construct()
	{
		header("Access-Control-Allow-Origin: *");

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
		$reuploadRequest = db::where('billingReuploadRequestStatus', 0)
			->where('siteID', $this->site->siteID)
			->get('billing_reupload_request')->row();

		if($reuploadRequest)
		{
			$date = $reuploadRequest['billingReuploadRequestBeginDate'];

			return json_encode(array(
				'status' => 'success',
				'data' => $date
				));
		}


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

	protected function getPendingUpload()
	{
		return db::where('billingTransactionUploadStatus', 0)->get('billing_transaction_upload')->num_rows();
	}

	protected function getUploadableDay()
	{
		$day = floor($this->site->siteID / 26) + 1;
		
		return $day;
	}

	protected function transactionLock()
	{
		return json_encode(array(
			'status' => 'success',
			'message' => 'Transaction upload is being locked for the time being, be try again after 30 minutes.',
			'total_transactions' => 0
			));
	}

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
		// to handle both case from not-updated yet cafe..
		if($uploadData = request::post('uploadData'))
			$transactions = unserialize(base64_decode($uploadData));
		else
			$transactions = request::post('transactions');

		// lock to do billing reset
		// return $this->transactionLock();

		/*if(($uploadableDay = $this->getUploadableDay()) != date('N'))
		{
			$days = array(
				1 => 'Monday',
				2 => 'Tuesday',
				3 => 'Wednesday',
				4 => 'Thursday',
				5 => 'Friday'
				);

			$interval = (date('N') > $uploadableDay ? 7 + $uploadableDay : $uploadableDay) - 1;

			$date = date('d/m/Y', strtotime("+$interval days", time()));

			return json_encode(array(
				'status' => 'success',
				'message' => "We are currently doing a transaction reset on pi1m server. However, in order to cater the load of microsites upload on the server, we have rescheduled your transaction uploading day to $days[$uploadableDay] $date.\n\nYou may however continue uploading your transaction normally after this period. (this week)",
				'total_transactions' => 0
				));
		}*/

		$pendingUpload = $this->getPendingUpload();

		// if there's currently 10 pending upload. lessen the server load.
		if($pendingUpload > 10)
		{
			return json_encode(array(
				'status' => 'success',
				'message' => $pendingUpload.' site(s) are currently uploading their transactions. Please try again after '.($pendingUpload*2).' minutes.',
				'total_transactions' => 0
				));
		}

		// begin upload record
		db::insert('billing_transaction_upload', array(
			'siteID' => $this->site->siteID,
			'billingTransactionUploadStatus' => 0,
			'billingTransactionUploadStartDate' => now(),
			'billingTransactionUploadCreatedDate' => now()
			));

		// $transactionUploadId = db::getLastID('billing_transaction_upload', 'billingTransactionUploadID');
		$transactionUploadId = db::getLastInsertId();

		if(!$transactions)
		{
			db::where('billingTransactionUploadID', $transactionUploadId)->update('billing_transaction_upload', array(
				'billingTransactionUploadStatus' => 2,
				'billingTransactionUploadTotal' => 0,
				'billingTransactionUploadCompletedDate' => now()
			));

			return json_encode(array(
				'status' => 'success',
				'total_transactions' => 0
				));
		}

		// $allIds = array_keys($transactions);
		$allIds = array();

		// get all unique ids
		foreach($transactions as $row)
			$allIds[] = $row['unique'];

		// existing.
		$existing = db::from('billing_transaction')
		->where('siteID', $this->site->siteID)
		->where('billingTransactionUnique', $allIds)->get()->result('billingTransactionUnique');

		$uniqueIds = array_keys($existing);

		$totalTransactions = 0;

		foreach($transactions as $row_transaction)
		{
			$localId = $row_transaction['transaction_id'];
			$uniqueId = $row_transaction['unique'];

			// update
			if(in_array($uniqueId, $uniqueIds) && $uniqueId != null)
			{
				$totalTransactions++;

				$transaction = model::orm('billing/transaction')
									->where('siteID', $this->site->siteID)
									->where('billingTransactionLocalID', $localId)
									->execute()->getFirst();

				// only update date if there's changes. since currently only date can be updated. and status
				$transaction->billingTransactionDate = $row_transaction['datetime'];
				$transaction->billingTransactionUpdatedDate = $row_transaction['updatedDate'];

				if(isset($row_transaction['status']))
					$transaction->billingTransactionStatus = $row_transaction['status'];
				
				$transaction->save();
			}
			else
			{
				$totalTransactions++;

				$transaction = model::orm('billing/transaction')->create();
				$transaction->siteID = $this->site->siteID;
				$transaction->billingTransactionLocalID = $localId;
				$transaction->billingTransactionTotalQuantity = $row_transaction['quantity'];
				$transaction->billingTransactionUnique = $uniqueId ? $uniqueId : strtotime($row_transaction['createdDate'])*1000;
				$transaction->billingTransactionStatus = $row_transaction['status'] ? : 1;
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
					$transactionItem->billingTransactionItemDescription = $row_transactionItem['description'];
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

		// if site has reupload request, update to success.
		db::where('siteID', $this->site->siteID)->where('billingReuploadRequestStatus', 0)->update('billing_reupload_request', array('billingReuploadRequestStatus' => 1));

		// log the upload date.
		db::where('billingTransactionUploadID', $transactionUploadId)->update('billing_transaction_upload', array(
			'billingTransactionUploadStatus' => 1,
			'billingTransactionUploadTotal' => $totalTransactions,
			'billingTransactionUploadCompletedDate' => now()
			));
		/*db::insert('billing_transaction_upload', array(
			'siteID' => $this->site->siteID,
			'billingTransactionUploadCreatedDate' => now()
			));*/

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
				'siteCafeIpAddress' => $_SERVER['REMOTE_ADDR'],
				'siteCafeLastAccess' => date('Y-m-d H:i:s')
				));
		}
		else
		{
			// only insert at least per minute
			if(time() > (strtotime($siteCafe['siteCafeLastAccess']) + 60))
			{
				db::where('siteCafeID', $siteCafe['siteCafeID'])
				->update('site_cafe', array(
					'siteCafeIpAddress' => $_SERVER['REMOTE_ADDR'],
					'siteCafeLastAccess' => date('Y-m-d H:i:s')
					));
			}
		}

		$lastUpdatedDate = db::select('userUpdatedDate')
		->where('userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($this->site->siteID))
		->limit(1)
		->order_by('userUpdatedDate DESC')
		->get('user')->row('userUpdatedDate');

		return json_encode(array(
			'status' => 'success',
			'cafe_version' => $this->getCafeVersion(),
			'mlu' => $lastUpdatedDate ? : 0 // member last update in a timestamp
			));
	}

	protected function getAbsoluteCafeVersion()
	{
		$path = apps::$root.'../repo/cafe/.git/refs/heads/master';
		// $cafeRoot = apps::$root.'../repo/cafe';

		$currentVersion = file_get_contents($path);

		return trim($currentVersion);
	}

	protected function isFullUpdateNeeded()
	{
		return db::where('siteID', $this->site->siteID)->where('billingUpdateRequestStatus', 0)->get('billing_update_request')->row() ? true : false;
	}

	protected function getCafeVersion()
	{
		if($this->isFullUpdateNeeded())
			return 1;

		return $this->getAbsoluteCafeVersion();
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

		// check if there's a forced update request
		if($this->isFullUpdateNeeded())
		{
			$version = '76a59b79e7a6a4c580784e9d7f2c87ac5f37d1fb';

			// update billing_update_request if any.
			db::where('siteID', $this->site->siteID)->where('billingUpdateRequestStatus', 0)->update('billing_update_request', array(
				'billingUpdateRequestStatus' => 1,
				'billingUpdateRequestUpdatedDate' => now()
				));
		}

		$currentVersion = $this->getAbsoluteCafeVersion();

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