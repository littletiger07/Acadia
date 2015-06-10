<?php
namespace Users\Form;
use Zend\Form\Form;
use Zend\Form\Element;


class RegisterForm extends Form      

{
    public function __construct($name = null)
    {
    	parent::__construct('Register');
    	$this->setAttribute('method', 'post');
    	
    	$name=new Element('name');
    	$name->setLabel('Full Name')
    	     ->setAttributes(array(
    	     	 'type'=>'text',    
    	         'class'=>'username',
    	     'size'=> '30',
    	     ));
    	$password = new Element('password');
    	$password->setLabel('Password')
    	         ->setAttributes(array(
    	         	 'type'=>'password',
    	             'size'=>'30',
    	         ));
    	         
    	$send=new Element('send');
    	$send->setValue('Submit')->setAttributes(array('type'=>'submit'));
    	
    	$this->add($name)->add($password)->add($send);
    	
    	
    	         
    }	
}