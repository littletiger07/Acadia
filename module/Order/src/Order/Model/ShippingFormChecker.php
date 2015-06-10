<?php
namespace Order\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ShippingFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id = (isset($data['id']))? $data['id']:null;
		$this->order_id = (isset($data['order_id']))? $data['order_id']:null;
		$this->shipping_date=(isset($data['shipping_date']))? $data['shipping_date']:null;
		$this->description = (isset($data['description']))? $data['description']:null;
		$this->cost = (isset($data['cost']))? $data['cost']:null;
		$this->tracking = (isset($data['tracking']))? $data['tracking']:null;
		$this->shipping_comment = (isset($data['shipping_comment']))? $data['shipping_comment']:null;
		 
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("not used");
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory=new InputFactory();
			$inputFilter->add($factory->createInput(array(
					'name' => 'shipping_date',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			    'validators'=>array(
			    
			    		array(
			    				'name' => '\Zend\Validator\Date',
			    					
			    		),
			    			
			    )
			    )));
			$inputFilter->add($factory->createInput(array(
					'name' => 'description',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			    )));
			$inputFilter->add($factory->createInput(array(
					'name' => 'cost',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			    'validators'=>array(
			    		 
			    		array(
			    				'name' => 'Regex',
			    				'options' => array(
			    						'pattern' => '/^[0-9,.]+$/',
			    						'messages' => array(
			    								\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid number!",
			    								\Zend\Validator\Regex::INVALID => "Regex is invalid!",
			    							
			    						),
			    				),
			    		),
			    )
			   )));
			$inputFilter->add($factory->createInput(array(
					'name' => 'tracking',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'shipping_comment',
					'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			)));
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}
