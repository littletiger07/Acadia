<?php
namespace Order\Model;
use Zend\Db\RowGateway\RowGateway;

class CostRow extends RowGateway
{
	protected $_myTable='cost';
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
        $this->cost_name=(isset($data['cost_name']))? $data['cost_name']:null;
        $this->cost_description = (isset($data['cost_description']))? $data['cost_description']:null;
        $this->cost_amount = (isset($data['cost_amount']))? $data['cost_amount']:null;
     
       
        $this->cost_comment = (isset($data['cost_comment']))? $data['cost_comment']:null;

    }
   public function save()
   {
      
      $costTable=new \Order\Model\CostTable($this->adapter);
       if($costTable->getItemById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('cost');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$costTable->updateWith($update);

  
       }
       else 
       {    $res=$costTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $costTable=new \Order\Model\CostTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('cost');
       $delete->where(array('id'=>$this->id));
       return $costTable->deleteWith($delete);
   }
   
 
}