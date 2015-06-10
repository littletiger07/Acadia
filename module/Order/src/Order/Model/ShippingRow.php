<?php
namespace Order\Model;
use Zend\Db\RowGateway\RowGateway;
use Order\Model\ShippingTable;
class ShippingRow extends RowGateway
{
	protected $_myTable='shipping';
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
        $this->order_id = (isset($data['order_id']))? $data['order_id']:null;
        $this->shipping_date=(isset($data['shipping_date']))? $data['shipping_date']:null;
        $this->description = (isset($data['description']))? $data['description']:null;
        $this->cost = (isset($data['cost']))? $data['cost']:null;
        $this->tracking = (isset($data['tracking']))? $data['tracking']:null;
        $this->shipping_comment = (isset($data['shipping_comment']))? $data['shipping_comment']:null;
       
    }
   public function save()
   {
      
      $shippingTable=new ShippingTable($this->adapter);
       if($shippingTable->getShippingById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('shipping');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$shippingTable->updateWith($update);

  
       }
       else 
       {    $res=$shippingTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $shippingTable=new ShippingTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('shipping');
       $delete->where(array('id'=>$this->id));
       return $shippingTable->deleteWith($delete);
   }
   
   public function getItems()
   {
       $shippingItemTable=new \Order\Model\ShippingItemTable($this->adapter);
       $res=$shippingItemTable->select(array('shipping_id'=>$this->id));
       return $res;
   }

   
}