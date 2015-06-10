<?php
namespace Order\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class ShippingForm extends Form

{

	
    public function __construct()
	{
		parent::__construct('UpdateShipping');
		
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
		
		$shipping_date=new Element('shipping_date');
		$shipping_date->setLabel('Shipping Date ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
	
	
		
	 
		
		$description=new Element('description');
		$description->setLabel('Description ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));


		$cost=new Element('cost');
		$cost->setLabel('Cost ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
	
		
		$tracking=new Element('tracking');
		$tracking->setLabel('Tracking Information ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));
		$shipping_comment=new Element('shipping_comment');
		$shipping_comment->setLabel('Comment ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));

		$send=new Element('send');
		$send->setValue('Update')->setAttributes(array('type'=>'submit'));
		 
		$this->add($id)->add($order_id)->add($shipping_date)->add($description)->add($cost)->add($tracking)->add($shipping_comment)->add($send);
		 
		

	}
	
	
	
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}