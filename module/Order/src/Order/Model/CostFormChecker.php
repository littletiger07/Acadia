<?php
namespace Order\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CostFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;

	
	
	public function exchangeArray($data)
	{
      $this->id = (isset($data['id']))? $data['id']:null;
        $this->order_id = (isset($data['order_id']))? $data['order_id']:null;
        $this->cost_name=(isset($data['cost_name']))? $data['cost_name']:null;
        $this->cost_description = (isset($data['cost_description']))? $data['cost_description']:null;
        $this->cost_amount = (isset($data['cost_amount']))? $data['cost_amount']:null;
     
       
        $this->cost_comment = (isset($data['cost_comment']))? $data['cost_comment']:null;
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
					'name' => 'id',
					'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'cost_name',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
						
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'cost_amount',
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
			    								//etc...
			    						),
			    				),
			    		),
			    )
						
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'cost_description',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					
			
			)));
			
		
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'cost_comment',
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