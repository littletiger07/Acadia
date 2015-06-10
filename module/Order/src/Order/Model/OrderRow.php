<?php
namespace Order\Model;
use Zend\Db\RowGateway\RowGateway;
use Order\Model\OrderTable;
class OrderRow extends RowGateway
{
	protected $_myTable='po';
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
        $this->PO_Number = (isset($data['PO_Number']))? $data['PO_Number']:null;
        $this->customer_id=(isset($data['customer_id']))? $data['customer_id']:null;
        $this->shipping_address = (isset($data['shipping_address']))? $data['shipping_address']:null;
        $this->status = (isset($data['status']))? $data['status']:null;
        $this->order_phone = (isset($data['order_phone']))? $data['order_phone']:null;
        $this->order_date = (isset($data['order_date']))? $data['order_date']:null;
        $this->order_email = (isset($data['order_email']))? $data['order_email']:null;
        $this->order_comment = (isset($data['order_comment']))? $data['order_comment']:null;

    }
   public function save()
   {
      
      $orderTable=new OrderTable($this->adapter);
       if($orderTable->getOrderById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('po');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$orderTable->updateWith($update);

  
       }
       else 
       {    $res=$orderTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $orderTable=new OrderTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('po');
       $delete->where(array('id'=>$this->id));
       return $orderTable->deleteWith($delete);
   }
   
   public function getItems()
   {
        $itemTable=new \Order\Model\ItemTable($this->adapter);
        $res=$itemTable->select(array('order_id'=>$this->id));
        return $res;
   }
   
   public function getCustomer()
   {
       $customerTable=new \Customer\Model\CustomerTable($this->adapter);
       return ($customerTable->getCustomerById($this->customer_id));
   }
   public function getShippings()
   {
       $shippingTable=new  \Order\Model\ShippingTable($this->adapter);
       $res=$shippingTable->select(array('order_id'=>$this->id));
       return $res;
   }
   
   public function getCosts()
   {
       $costTable=new \Order\Model\CostTable($this->adapter);
       $res=$costTable->select(array('order_id'=>$this->id));
       return $res;
   }
   public function isCompleted()
   {
       $items=$this->getItems();
       foreach($items as $item)
       {
           if (!$item->isCompleted())
               return false;
       }
       return true;
       
   }
   
   public function getOrderTotal()
   {
       $items=$this->getItems();
       $amount=0;
       foreach($items as $item)
       {
           $amount=$amount+$item->quantities*$item->unit_price;
       }
       return $amount;
       
   }
   
   public function getInvoiceTotal()
   {
       $amount=$this->getOrderTotal();
       $shippings=$this->getShippings();
       foreach($shippings as $shipping)
           $amount=$amount+$shipping->cost;
       $costs=$this->getCosts();
       foreach($costs as $cost)
           $amount=$amount+$cost->cost_amount;
       return $amount;
   }
   public function getInvoice()
   {
       $invoiceTable=new \Invoice\Model\InvoiceTable($this->adapter);
       $res=$invoiceTable->select(array('order_id'=>$this->id));
       return ($res->current());
   }
}