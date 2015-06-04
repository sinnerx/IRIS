<?php 

namespace model\billing;
use db, session;

class finance extends \Origami
{ 
	protected $table = 'billing_finance_transaction';
	protected $primary = 'billingFinanceTransactionID';

}	