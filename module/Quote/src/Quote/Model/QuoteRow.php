<?php
namespace Quote\Model;
use Zend\Db\RowGateway\RowGateway;
use Quote\Model\QuoteTable;
class QuoteRow extends RowGateway
{
	protected $_myTable='quote';
	protected $_primary='quote_id';
	protected $adapter;
	function __construct($adapter)
	{
		parent::__construct($this->_primary,$this->_myTable, $adapter);
		$this->adapter=$adapter;
	}

	public function getArrayCopy()
	{
		return $this->toArray();
	}

	public function exchangeArray($data)
	{
		$this->id = (isset($data['id']))? $data['id']:null;
		$this->quote_date = (isset($data['quote_date']))? $data['quote_date']:null;
		$this->quote_email = (isset($data['quote_email']))? $data['quote_email']:null;
		$this->quote_cas = (isset($data['quote_cas']))? $data['quote_cas']:null;
		$this->quote_content = (isset($data['quote_content']))? $data['quote_content']:null;
		$this->quote_comment = (isset($data['quote_comment']))? $data['quote_comment']:null;
		
	}
	
	public function delete()
	{
		$quoteTable=new QuoteTable($this->adapter);
		$delete=new \Zend\Db\Sql\Delete('quote');
		$delete->where(array('quote_id'=>$this->quote_id));
		return $quoteTable->deleteWith($delete);
	}
}
	 