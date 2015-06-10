<?php
namespace Invoice\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class InvoiceFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	

	
	public function exchangeArray($data)
	{
	    $this->id = (isset($data['id']))? $data['id']:null;
	    $this->order_id = (isset($data['order_id']))? $data['order_id']:null;
	    $this->invoice_number=(isset($data['invoice_number']))? $data['invoice_number']:null;
	    $this->invoice_date = (isset($data['invoice_date']))? $data['invoice_date']:null;
	    $this->invoice_status = (isset($data['invoice_status']))? $data['invoice_status']:null;
	    
	    $this->invoice_comment = (isset($data['invoice_comment']))? $data['invoice_comment']:null;
	     

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
					'name' => 'invoice_number',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),

			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'order_id',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
				'validators'=>array(
							
					    array(
					    		'name' => 'Regex',
					    		'options' => array(
					    				'pattern' => '/^[0-9]+$/',
					    		    'messages' => array(
					    		    		\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid  number!",
					    		             \Zend\Validator\Regex::INVALID => "Regex is invalid!",
					    		    		//etc...
					    		    ),
					    		),
					    ),
					    	
					)
			)));
			
	
			
		
			$inputFilter->add($factory->createInput(array(
					'name' => 'invoice_status',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
				
			)));
			
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'invoice_comment',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					
					
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'invoice_date',
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


	    
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}

