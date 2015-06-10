<?php
namespace Order\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class UpdateForm extends Form

{
	protected $adapter;
	public $item_count;
    public function __construct($adapter,$item_count=1)
	{
		parent::__construct('UpdateOrder');
		$this->adapter=$adapter;
		$this->setAttribute('method', 'post');
		$this->item_count=$item_count; 
		$id=new Element('id');
		
		$id->setAttributes(array(
				'type'=>'hidden',
				
				'size'=> '16',
		));
		$PO_Number=new Element('PO_Number');
		$PO_Number->setLabel('PO Number ')
		->setAttributes(array(
				'type'=>'text',
			
				'size'=> '32',
		));
		
		$customer_id=new Element\Select('customer_id');
		$customer_id->setLabel('customer_id')
		->setValueOptions($this->getCustomerOptions())
		->setAttributes(array(
				'id'=>'customer_id',
		));
		
		$shipping_address=new Element\Textarea('shipping_address');
		$shipping_address->setLabel('Enter Shipping Address ')
		->setAttributes(array(
				'id'=>'shipping_address',
					
				'rows'=>'10',
		        'cols'=>'40',
		));
		
	 
		
		$order_phone=new Element('order_phone');
		$order_phone->setLabel('Phone ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));


		$order_email=new Element('order_email');
		$order_email->setLabel('Email Address ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));
		
		$order_date=new Element('order_date');
		$order_date->setLabel('Order Date ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		
		$status=new Element('status');
		$status->setLabel('Order Status ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		$order_comment=new Element('order_comment');
		$order_comment->setLabel('Comment ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
// 		$item_count=new Element('item_count');
// 		$item_count->setLabel('Item Count')
// 		           ->setAttributes(array(
// 		           	'type'=>'text',
// 		            'size'=>'4',
		               
// 		           ));
		$this->add($id)->add($PO_Number)->add($shipping_address)->add($customer_id)->add($order_phone)->add($order_email)->add($order_date)->add($status)->add($order_comment);
		
// 		for ($i=1;$i<=$this->item_count;$i++)
// 		{
// 		    $id=new Element('id'.$i);
		    
// 		    $id->setAttributes(array(
// 		    		'type'=>'hidden',
		    
// 		    		'size'=> '16',
// 		    ));
		    

		    
// 		    $catalogNo=new Element('catalogNo'.$i);
// 		    $catalogNo->setlabel('Item '.$i)
// 		    ->setAttributes(array(
// 		    		'type'=>'text',
// 		    		'size'=>'8',
// 		    ));
		    
// 		    $package_size=new Element('package_size'.$i);
// 		    $package_size->setLabel('Item '.$i.' packate size')
// 		    ->setAttributes(array(
// 		    		'type'=>'size',
// 		    		'size'=>'4',
// 		    ));
		    
// 		    $quantities=new Element('quantities'.$i);
// 		    $quantities->setLabel('Item '.$i.' quantities')
// 		    ->setAttributes(array(
// 		    		'type'=>'text',
// 		    		'size'=>'4',
// 		    		'value'=>'1',
		    
// 		    ));
		    
// 		    $unit_price=new Element('unit_price'.$i);
// 		    $unit_price->setLabel('Item '.$i.' unite price')
// 		    ->setAttributes(array(
// 		    		'type'=>'text',
// 		    		'size'=>8,
		    
// 		    ));
		    
// 		    $po_item_comment=new Element('po_item_comment'.$i);
// 		    $po_item_comment->setLabel('Item '.$i.' comment')
// 		    ->setAttributes(array(
// 		    		'type'=>'text',
// 		    		'size'=>'256',
// 		    ));
		    
// 		    $this->add($id)->add($catalogNo)->add($quantities)->add($unit_price)->add($po_item_comment);
// 		}
		$send=new Element('send');
		$send->setValue('Update')->setAttributes(array('type'=>'submit'));
		 
		$this->add($send);
		 
		

	}
	
	public function getCustomerOptions()
	{
	   
	    $customerTable=new \Customer\Model\CustomerTable($this->adapter);
	    return $customerTable->getCustomerOptions();
	}
	
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}
