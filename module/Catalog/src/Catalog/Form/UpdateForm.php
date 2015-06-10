<?php
namespace Catalog\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class UpdateForm extends Form

{
	public function __construct($name = null)
	{
		parent::__construct('UpdateCatalog');
		$this->setAttribute('method', 'post');
		 
		$id=new Element('id');
		
		$id->setAttributes(array(
				'type'=>'hidden',
				
				'size'=> '64',
		));
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
		
		$purity=new Element('purity');
		$purity->setLabel('Purity ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '4',
		));

		$package_size1=new Element('package_size1');
		$package_size1->setLabel('Package Size ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		$price1=new Element('price1');
		$price1->setLabel('Price ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
		$package_size2=new Element('package_size2');
		$package_size2->setLabel('Package Size ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		$price2=new Element('price2');
		$price2->setLabel('Price ')
		->setAttributes(array(
				'type'=>'text',
					
				'size'=> '16',
		));
		
		$stock=new Element('stock');
		$stock->setLabel('Stock ')
		      ->setAttributes(array(
		      	'type'=>'text',
		        'size'=>4,
		      ));
		
		$location=new Element('location');
		$location->setLabel('Location ')  
		         ->setAttributes(array(
		         	'type'=>'text',
		            'size'=>'8',
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
		
		$comment=new Element('comment');
		$comment->setLabel('Comment ')
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
		$send->setValue('Update')->setAttributes(array('type'=>'submit'));
		 
		$this->add($id)->add($catalogNo)->add($name)->add($cas)->add($purity)->add($package_size1)
		->add($price1)->add($package_size2)->add($price2)->add($stock)->add($location)->add($formula)->add($mw)->add($smile)->add($comment)
		->add($appearance)->add($color)->add($send);
		 
		

	}
	public function getPureData()
	{
		$data=$this->getData();
		unset($data['send']);
		return $data;
	}
}
