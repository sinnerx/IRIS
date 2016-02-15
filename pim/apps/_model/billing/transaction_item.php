<?php
namespace model\billing;

class Transaction_item extends \Origami
{
	protected $table = 'billing_transaction_item';
	protected $primary = 'billingTransactionItemID';
}

