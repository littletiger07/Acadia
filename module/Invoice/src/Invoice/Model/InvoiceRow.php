<?php
namespace Invoice\Model;
use Zend\Db\RowGateway\RowGateway;
use \Invoice\Model\InvoiceTable;
class InvoiceRow extends RowGateway
{
	protected $_myTable='invoice';
	protected $_primary='id';
	protected $adapter;
	//public $id,$order_id,$invoice_number,$invoice_date,$invoice_status,$invoice_comment;
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
        $this->invoice_number=(isset($data['invoice_number']))? $data['invoice_number']:null;
        $this->invoice_date = (isset($data['invoice_date']))? $data['invoice_date']:null;
        $this->invoice_status = (isset($data['invoice_status']))? $data['invoice_status']:null;
      
        $this->invoice_comment = (isset($data['invoice_comment']))? $data['invoice_comment']:null;

    }
   public function save()
   {
      
      $invoiceTable=new InvoiceTable($this->adapter);
       if($invoiceTable->getInvoiceById($this->id))
       {
           $update=new \Zend\Db\Sql\Update('invoice');
           $update->set($this->toArray());
           $update->where(array('id'=>$this->id));
           $res=$invoiceTable->updateWith($update);

  
       }
       else 
       {    $res=$invoiceTable->insert($this->toArray());
                 
       }
       return $res;
   }
   public function delete()
   {
       $invoiceTable=new InvoiceTable($this->adapter);
       $delete=new \Zend\Db\Sql\Delete('invoice');
       $delete->where(array('id'=>$this->id));
       return $invoiceTable->deleteWith($delete);
   }
   public function getOrder()
   {
       $orderTable= new \Order\Model\OrderTable($this->adapter);
       $order= $orderTable->getOrderById($this->order_id);

       return $order;
   } 
   public function getDueDate()
   {
       $duedate=date('Y-m-d',strtotime($this->invoice_date. ' + '.$this->getOrder()->getCustomer()->term.' days'));
       return $duedate;
   }
   public function setPaid()
   {
       $this->invoice_status='paid';
       $this->save();
   }
}