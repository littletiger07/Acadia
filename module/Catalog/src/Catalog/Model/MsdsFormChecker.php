<?php
namespace Catalog\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class MsdsFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	public function exchangeArray($data)
	{

       
        $this->catalogNo = (isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->name = (isset($data['name']))? $data['name']:null;
        $this->cas = (isset($data['cas']))? $data['cas']:null;
       
        $this->formula = (isset($data['formula']))? $data['formula']:null;
        $this->mw = (isset($data['mw']))? $data['mw']:null;
   
        $this->appearance = (isset($data['appearance']))? $data['appearance']:null;
        $this->color = (isset($data['color']))? $data['color']:null;
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
					'name' => 'catalogNo',
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
											'min'=> 6,
											'max'=>10,
									),
							),
					    array(
					    		'name' => 'Regex',
					    		'options' => array(
					    				'pattern' => '/^[0-9,a-z,A-Z,-]+$/',
					    				'messages' => array(
					    						\Zend\Validator\Regex::NOT_MATCH => "Cagalog Number should contain only numbers and letters!",
					    						\Zend\Validator\Regex::INVALID => "Regex is invalid!",
					    						//etc...
					    				),
					    		),
					    ),
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'name',
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
											'min'=> 5,
											'max'=>512,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'cas',
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
											'min'=> 7,
											'max'=>13,
									)
							),
					    array(
					    		'name' => 'Regex',
					    		'options' => array(
					    				'pattern' => '/^[0-9]{2,8}-[0-9]{2}-[0-9]{1}$/',
					    		    'messages' => array(
					    		    		\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid cas number!",
					    		             \Zend\Validator\Regex::INVALID => "Regex is invalid!",
					    		    		//etc...
					    		    ),
					    		),
					    ),
					    	
					)
			)));
			
	
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'formula',
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
											'min'=> 5,
											'max'=>32,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'mw',
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
											'min'=> 2,
											'max'=>8,
									)
							)
					)
			)));
			
		
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'appearance',
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
											'max'=>15,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'color',
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
											'min'=> 0,
											'max'=>15,
									)
							)
					)
			)));
			
	
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}