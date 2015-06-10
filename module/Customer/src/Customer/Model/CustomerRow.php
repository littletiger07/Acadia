<?php
namespace Customer\Model;
use Zend\Db\RowGateway\RowGateway;
use Customer\Model\CustomergTable;
class CustomerRow extends RowGateway
{
	protected $_myTable='customer';
	protected $_primary='id';
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
        $this->customer_name = (isset($data['customer_name']))? $data['customer_name']:null;
        $this->address = (isset($data['address']))? $data['address']:null;
        $this->term = (isset($data['term']))? $data['term']:null;
        $this->phone = (isset($data['phone']))? $data['phone']:null;
        $this->fax = (isset($data['fax']))? $data['fax']:null;
        $this->email = (isset($data['email']))? $data['email']:null;
          $this->customer_comment = (isset($data['customer_comment']))? $data['customer_comment']:null;

    }
   public function save()
   {
      
      $customerTable=new CustomerTable($this->adapter);
       if($customerTable->getCustomerById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('customer');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$customerTable->updateWith($update);

  
       }
       else 
       {    $res=$customerTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $customerTable=new CustomerTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('customer');
       $delete->where(array('id'=>$this->id));
       return $customerTable->deleteWith($delete);
   }
   
   public function getOrders()
   {
       $orderTable=new \Order\Model\OrderTable($this->adapter);
       $where=new \Zend\Db\Sql\Where();
       $where->equalTo('customer_id', $this->id);
       return $orderTable->select($where);
   }
}