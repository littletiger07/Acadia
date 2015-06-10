<?php
namespace Order\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ShippingItemFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
    
	public function exchangeArray($data)
	{ 
	    $this->id = (isset($data['id']))? $data['id']:null;
        $this->shipping_id = (isset($data['shipping_id']))? $data['shipping_id']:null;
        $this->catalogNo=(isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->amount = (isset($data['amount']))? $data['amount']:null;
        $this->shipping_quantites = (isset($data['shipping_quantities']))? $data['shipping_quantities']:null;
        $this->batch= (isset($data['batch']))? $data['batch']:null;
        $this->shipping_item_comment = (isset($data['shipping_item_comment']))? $data['shipping_item_comment']:null;
        
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
					'name' => 'shipping_id',
					'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
						
			)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name' => 'catalogNo',
    				'required'=>true,
    				'filters'=> array(
    						array('name'=>'StripTags'),
    						array('name'=>'StringTrim'),
    				),
    		
    		)));
    		$inputFilter->add($factory->createInput(array(
    				'name' => 'amount',
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
    				'name' => 'shipping_quantities',
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
    				'name' => 'batch',
    				'required'=>true,
    				'filters'=> array(
    						array('name'=>'StripTags'),
    						array('name'=>'StringTrim'),
    				),
    		
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name' => 'shipping_item_comment',
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
    	