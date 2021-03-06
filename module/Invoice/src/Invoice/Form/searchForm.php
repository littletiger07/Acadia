<?php
namespace Invoice\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class SearchForm extends Form      

{
    public function __construct($name = null)
    {
    	parent::__construct('SearchInvoice');
    	$this->setAttribute('method', 'get');
    	
    	$name=new Element('searchText');
    	$name->setLabel('Enter Search Text')
    	     ->setAttributes(array(
    	     	 'type'=>'text',    
    	         'class'=>'searchText',
    	     'size'=> '64',
    	     ));
    	
    	         
    	$send=new Element('send');
    	$send->setValue('Search')->setAttributes(array('type'=>'submit'));
    	
    	$this->add($name)->add($send);
    	
    	
    	         
    }	
}
