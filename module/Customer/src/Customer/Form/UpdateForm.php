<?php
namespace Customer\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class UpdateForm extends Form

{
	public function __construct($name = null)
	{
		parent::__construct('UpdateCustomer');
		$this->setAttribute('method', 'post');
		 
		$id=new Element('id');
		
		$id->setAttributes(array(
				'type'=>'hidden',
				
				'size'=> '16',
		));
		$customer_name=new Element('customer_name');
		$customer_name->setLabel('Name ')
		->setAttributes(array(
				'type'=>'text',
			
				'size'=> '32',
		));
		
		$address=new Element\Textarea('address');
		$address->setLabel('Enter Billing Address ')
		->setAttributes(array(
			
					
				'rows'=>'10',
		        'cols'=>'30',
		));
		
		$term=new Element('term');
		$term->setLabel('Term')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '8',
		));
		
		$phone=new Element('phone');
		$phone->setLabel('Phone ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));

		$fax=new Element('fax');
		$fax->setLabel('Fax ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		$email=new Element('email');
		$email->setLabel('Email Address ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '64',
		));
		
	
		$customer_comment=new Element('customer_comment');
		$customer_comment->setLabel('Comment ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		
	
		
		$send=new Element('send');
		$send->setValue('Update')->setAttributes(array('type'=>'submit'));
		 
		$this->add($id)->add($customer_name)->add($address)->add($term)->add($phone)->add($fax)
		->add($email)->add($customer_comment)->add($send);
		 
		

	}
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}
