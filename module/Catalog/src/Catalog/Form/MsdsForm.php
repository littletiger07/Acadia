<?php
namespace Catalog\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class MsdsForm extends Form

{
	public function __construct($name = null)
	{
		parent::__construct('MSDS');
		$this->setAttribute('method', 'post');
		 

		$catalogNo=new Element('catalogNo');
		$catalogNo->setLabel('catalogNo ')
		->setAttributes(array(
				'type'=>'text',
			
				'size'=> '16',
		));
		
		$name=new Element('name');
		$name->setLabel('Chemical Name ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		$cas=new Element('cas');
		$cas->setLabel('CASRN')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
	
		             
		$formula=new Element('formula');
		$formula->setLabel('Formula ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		
		$mw=new Element('mw');
		$mw->setLabel('MW ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '8',
		));
		$smile=new Element('smile');
		$smile->setLabel('Smile ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '32',
		));
		
	
		$appearance=new Element('appearance');
		$appearance->setLabel('Appearance ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '8',
		));
		
		$color=new Element('color');
		$color->setLabel('Color ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
		$send=new Element('send');
		$send->setValue('Msds')->setAttributes(array('type'=>'submit'));
		 
		$this->add($catalogNo)->add($name)->add($cas)
		->add($formula)->add($mw)
		->add($appearance)->add($color)->add($send);
		 
		

	}
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}