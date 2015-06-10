<?php
namespace Order\Model;
use Zend\Db\RowGateway\RowGateway;
use Order\Model\OrderTable;
class ShippingItemRow extends RowGateway
{
	protected $_myTable='shipping_item';
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
        $this->shipping_id = (isset($data['shipping_id']))? $data['shipping_id']:null;
        $this->catalogNo=(isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->amount = (isset($data['amount']))? $data['amount']:null;
        $this->shipping_quantities = (isset($data['shipping_quantities']))? $data['shipping_quantities']:null;
        $this->batch= (isset($data['batch']))? $data['batch']:null;
        
         
        $this->shipping_item_comment = (isset($data['shipping_item_comment']))? $data['shipping_item_comment']:null;
        
    }
    public function save()
    {
    
    	$shippingItemTable=new \Order\Model\ShippingItemTable($this->adapter);
    	if($shippingItemTable->getItemById($this->id))
    	{
    		$update=new \Zend\Db\Sql\Update('po_item');
    		$update->set($this->toArray());
    		$update->where(array('id'=>$this->id));
    		$res=$shippingItemTable->updateWith($update);
    
    
    	}
    	else
    	{    $res=$shippingItemTable->insert($this->toArray());
    	 
    	}
    	return $res;
    }
    public function delete()
    {
    	$shippingItemTable=new \Order\Model\ShippingItemTable($this->adapter);
    	$delete=new \Zend\Db\Sql\Delete('po_item');
    	$delete->where(array('id'=>$this->id));
    	return $shippingItemTable->deleteWith($delete);
    }
}