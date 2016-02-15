<?php
namespace model\billing;

class Transaction extends \Origami
{
	protected $table = 'billing_transaction';
	protected $primary = 'billingTransactionID';
}