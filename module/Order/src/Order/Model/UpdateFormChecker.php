<?php
namespace Order\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UpdateFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	protected $item_count;
	
	public function __construct($item_count=1)
	{
	   
	    $this->item_count=$item_count;
	}
	
	public function exchangeArray($data)
	{
	    $this->id = (isset($data['id']))? $data['id']:null;
	    $this->PO_Number = (isset($data['PO_Number']))? $data['PO_Number']:null;
	    $this->customer_id = (isset($data['customer_id']))? $data['customer_id']:null;
	    $this->shipping_address = (isset($data['shipping_address']))? $data['shipping_address']:null;
	    $this->order_phone = (isset($data['order_phone']))? $data['order_phone']:null;
	    $this->order_email = (isset($data['order_email']))? $data['order_email']:null;
	    $this->order_date = (isset($data['order_date']))? $data['order_date']:null;
	    $this->status = (isset($data['status']))? $data['status']:null;
	    $this->order_comment = (isset($data['order_comment']))? $data['order_comment']:null;

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
					'name' => 'PO_Number',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),

			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'customer_id',
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
					'name' => 'shipping_address',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
				
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'order_phone',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					'validators'=>array(
							array(
									'name'=> 'StringLength',
									'options'=> array(
											'encoding'=> 'UTF-8',
											'min'=> 6,
											'max'=>16,
									)
							),
					    array(
					    		'name' => 'Regex',
					    		'options' => array(
					    				'pattern' => '/^[0-9,\(,\),\+,\-,\s]+$/',
					    				'messages' => array(
					    						\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid phone number!",
					    						\Zend\Validator\Regex::INVALID => "Regex is invalid!",
					    						//etc...
					    				),
					    		),
					    ),
					)
			             
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'status',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
				
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'order_email',
			        'required'=>false,
					'filters'=> array(
							
							array('name'=>'StringTrim'),
					),
					'validators'=>array(
							array(
									'name'=> 'EmailAddress',
									'options'=> array(
									    'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
									    'useMxCheck'    => true,
									    'useDeepMxCheck'  => true,
									    	
									    \Zend\Validator\EmailAddress::INVALID_FORMAT =>'Please Enter a Valid Email Address!'
									    	
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'order_comment',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					
					
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'order_date',
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

