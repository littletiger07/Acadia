<?php
namespace Catalog\Model;
use Zend\Db\RowGateway\RowGateway;
use Catalog\Model\CatalogTable;
class CatalogRow extends RowGateway
{
	protected $_myTable='catalog';
	protected $_primary='catalogNo';
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
        $this->catalogNo = (isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->name = (isset($data['name']))? $data['name']:null;
        $this->cas = (isset($data['cas']))? $data['cas']:null;
        $this->purity = (isset($data['purity']))? $data['purity']:null;
        $this->package_size1 = (isset($data['package_size1']))? $data['package_size1']:null;
        $this->price1 = (isset($data['price1']))? $data['price1']:null;
        $this->package_size2 = (isset($data['package_size2']))? $data['package_size2']:null;
        $this->price2 = (isset($data['price2']))? $data['price2']:null;
        $this->stock = (isset($data['stock']))? $data['stock']:null;
        $this->location = (isset($data['location']))? $data['location']:null;
        $this->formula = (isset($data['formula']))? $data['formula']:null;
        $this->mw = (isset($data['mw']))? $data['mw']:null;
        $this->smile = (isset($data['smile']))? $data['smile']:null;
        $this->comment = (isset($data['comment']))? $data['comment']:null;
        $this->appearance = (isset($data['appearance']))? $data['appearance']:null;
        $this->color = (isset($data['color']))? $data['color']:null;
    }
   public function save()
   {
      
      $catalogTable=new CatalogTable($this->adapter);
       if($catalogTable->getItemByCatalogNo($this->catalogNo))
       {
           $update=new \Zend\Db\Sql\Update('catalog');
           $update->set($this->toArray());
           $update->where(array('catalogNo'=>$this->catalogNo));
           $res=$catalogTable->updateWith($update);

  
       }
       else 
       {    $res=$catalogTable->insert($this->toArray());
                 
       }
       return $res;
   }
   
   public function upload()
   {
       //echo var_dump($this->catalogNo);
       $catalogTable=new CatalogTable($this->adapter);
       $row=$catalogTable->getItemByCatalogNo($this->catalogNo);
       if($row)
       {
       	$this->id=$row->id;
        $this->comment=$row->comment;
       	$this->stock=$row->stock;
       	$this->location=$row->location;
       	$this->appearance=$row->appearance;
       	$this->color=$row->color;
  
       	if (($this->toArray())==$row)
       	    return 0;
 
        $update=new \Zend\Db\Sql\Update('catalog');
       	$update->set($this->toArray());
       	$update->where(array('catalogNo'=>$this->catalogNo));
       	$res=$catalogTable->updateWith($update);
        

       }
       else
       {    

           $res=$catalogTable->insert($this->toArray());
        
       }
       return $res;
       }
   
   public function delete()
   {
       $catalogTable=new CatalogTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('catalog');
       $delete->where(array('catalogNo'=>$this->catalogNo));
       return $catalogTable->deleteWith($delete);
   }
   
   public function getIdenticals()
   {
       if (!$this->cas)
           return null;
       $catalogTable=new \Catalog\Model\CatalogTable($this->adapter);
       
       $where= new \Zend\Db\Sql\Where();
       $where->equalTo('cas',$this->cas);
       $where->notEqualTo('catalogNo',$this->catalogNo);
       $res=$catalogTable->select($where);

       
       return $res;
   }
   public function getStock()
   {
        $sum=$this->stock;
        $items=$this->getIdenticals();
        if ($items)
		{
		foreach($items as $item)
        {
            $sum=$sum+$item->stock;
        }
		}
        return $sum;
   }
   public function getLocation()
   {
       $location=($this->location=='')? '':$this->location.'<br/>';
       $items=$this->getIdenticals();
       if ($items)
	   {
	   foreach($items as $item)
       {
           if ($item->stock>0)
           {
               $location.=$item->location. ' '.$item->stock.'g as <a href="/catalog/search/show?catalogNo='.$item->catalogNo.'">'.$item->catalogNo.'</a>';
           }
       }
	   }
       return $location;
   }

   
   
   public function getOrders()
   {
       $items=$this->getIdenticals();
//        $catalogNo=array();
//        $catalogNo[]=$this->catalogNo;
//        foreach($items as $item)
//            $catalogNo[]=$item->catalogNo;
       $orderTable=new \Order\Model\OrderTable($this->adapter);
       $select=new \Zend\Db\Sql\Select('po');
       $select->join('po_item','po.id=po_item.order_id',array('po_item_id'=>'id'));
       $select->where(array('po_item.catalogNo'=>$this->catalogNo),\Zend\Db\Sql\Predicate\PredicateSet::OP_OR);
       if ($items)
       {
           
           
           foreach($items as $item)
           {    $select->where(array('po_item.catalogNo'=>$item->catalogNo),\Zend\Db\Sql\Predicate\PredicateSet::OP_OR
           );}
           
       }
  
            
           return $orderTable->selectWith($select);
        
          
   }
}