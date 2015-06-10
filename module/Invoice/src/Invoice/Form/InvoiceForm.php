<?php
namespace Invoice\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class InvoiceForm extends Form

{

	
    public function __construct()
	{
		parent::__construct('Create Invoice');
		
		$this->setAttribute('method', 'post');
	
		$id=new Element('id');
		
		$id->setAttributes(array(
				'type'=>'hidden',
				
				'size'=> '16',
		));
		$order_id=new Element('order_id');
		$order_id->setAttributes(array(
				'type'=>'hidden',
			
				'size'=> '32',
		));
		
		$invoice_date=new Element('invoice_date');
		$invoice_date->setLabel('Invoice Date ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
	
	
		
	 
		
		$invoice_number=new Element('invoice_number');
		$invoice_number->setLabel('Invoice Number ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));


		$invoice_status=new Element('invoice_status');
		$invoice_status->setLabel('Status ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
	
		
	
		$invoice_comment=new Element('invoice_comment');
		$invoice_comment->setLabel('Comment ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));

		$send=new Element('send');
		$send->setValue('Update')->setAttributes(array('type'=>'submit'));
		 
		$this->add($id)->add($invoice_number)->add($order_id)->add($invoice_date)->add($invoice_status)->add($invoice_comment)->add($send);
		 
		

	}
	
	
	
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}