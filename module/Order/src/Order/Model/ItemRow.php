<?php
namespace Order\Model;
use Zend\Db\RowGateway\RowGateway;

class ItemRow extends RowGateway
{
	protected $_myTable='po_item';
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
        $this->catalogNo=(isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->shipping_address = (isset($data['shipping_address']))? $data['shipping_address']:null;
        $this->package_size = (isset($data['package_size']))? $data['package_size']:null;
        $this->quantities = (isset($data['quantities']))? $data['quantities']:null;
        $this->unit_price = (isset($data['unit_price']))? $data['unit_price']:null;
       
        $this->po_item_comment = (isset($data['po_item_comment']))? $data['po_item_comment']:null;

    }
   public function save()
   {
      
      $itemTable=new \Order\Model\ItemTable($this->adapter);
       if($itemTable->getItemById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('po_item');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$itemTable->updateWith($update);

  
       }
       else 
       {    $res=$itemTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $itemTable=new \Order\Model\ItemTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('po_item');
       $delete->where(array('id'=>$this->id));
       return $itemTable->deleteWith($delete);
   }
   
   public function isCompleted()
   {

       $query='select sum(amount*(shipping_item.shipping_quantities))as amount from shipping_item join shipping on shipping.id=shipping_item.shipping_id join po on shipping.order_id=po.id
           where po.id='.$this->order_id.' and shipping_item.catalogNo="'.$this->catalogNo.'"';
        
      
       $result = $this->adapter->query($query)->execute()->current();
        
       $res= (double)$result['amount'];
       
       return ($res>=($this->package_size*$this->quantities));
       
   }
   
   public function getItem()
   {
       $catalogTable=new \Catalog\Model\CatalogTable($this->adapter);
       return $catalogTable->getItemByCatalogNo($this->catalogNo);
   }
}