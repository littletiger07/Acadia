<?php
namespace Customer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UpdateFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	public function exchangeArray($data)
	{
	    $this->id = (isset($data['id']))? $data['id']:null;
	    $this->customer_name = (isset($data['customer_name']))? $data['customer_name']:null;
	    $this->address = (isset($data['address']))? $data['address']:null;
	    $this->term = (isset($data['term']))? $data['term']:null;
	    $this->phone = (isset($data['phone']))? $data['phone']:null;
	    $this->fax = (isset($data['fax']))? $data['fax']:null;
	    $this->email = (isset($data['email']))? $data['email']:null;
	    $this->customer_comment = (isset($data['customer_comment']))? $data['customer_comment']:null;

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
					'name' => 'customer_name',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					'validators'=>array(
							array(
									'name'=> 'StringLength',
									'options'=> array(
											'encoding'=> 'UTF-8',
											'min'=> 3,
											'max'=>32,
									),
							),
					  
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'address',
			        'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					'validators'=>array(
							array(
									'name'=> 'StringLength',
									'options'=> array(
											'encoding'=> 'UTF-8',
											'min'=> 8,
											'max'=>512,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'term',
			        'required'=>false,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
					'validators'=>array(
							
					    array(
					    		'name' => 'Regex',
					    		'options' => array(
					    				'pattern' => '/^[0-9]{1,2}$/',
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
					'name' => 'phone',
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
					    				'pattern' => '/^[0-9,\(,\),\+,\-,\s ]+$/',
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
					'name' => 'fax',
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
					    						\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid fax number!",
					    						\Zend\Validator\Regex::INVALID => "Regex is invalid!",
					    						//etc...
					    				),
					    		),
					    ),
					)
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'email',
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
					'name' => 'customer_comment',
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

