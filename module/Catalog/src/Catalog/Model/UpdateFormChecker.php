<?php
namespace Catalog\Model;

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
        $this->catalogNo = (isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->name = (isset($data['name']))? $data['name']:null;
        $this->cas = (isset($data['cas']))? $data['cas']:null;
        $this->purity = (isset($data['purity']))? $data['purity']:null;
        $this->package_size1 = (isset($data['package_size1']))? $data['package_size1']:null;
        $this->price1 = (isset($data['price1']))? $data['price1']:null;
        $this->package_size2 = (isset($data['packagesize2']))? $data['package_size2']:null;
        $this->price2 = (isset($data['price2']))? $data['price2']:null;
        $this->stock = (isset($data['stock']))? $data['stock']:null;
        $this->location = (isset($data['location']))? $data['location']:null;
        $this->formula = (isset($data['formula']))? $data['formula']:null;
        $this->mw = (isset($data['mw']))? $data['mw']:null;
        $this->smile = (isset($data['smile']))? $data['smile']:null;
        $this->comment = (isset($data['comment']))? $data['comment']:null;
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
					'name' => 'purity',
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
											'max'=>6,
									)
							)
					)
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'package_size1',
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
											'max'=>10,
									)
							)
					)
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'price1',
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
											'max'=>20,
									)
							)
					)
			)));
			$inputFilter->add($factory->createInput(array(
					'name' => 'package_size2',
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
											'max'=>6,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'price2',
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
											'max'=>20,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'stock',
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
											'min'=> 0,
											'max'=>10,
									),
							),
							array(
									'name' => 'Regex',
									'options' => array(
											'pattern' => '/^[0-9,.]+$/',
											'messages' => array(
													\Zend\Validator\Regex::NOT_MATCH => "stock information should only contain numbers!",
													\Zend\Validator\Regex::INVALID => "Regex is invalid!",
													//etc...
											),
									),
							),
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'location',
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
											'min'=> 0,
											'max'=>10,
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
					'name' => 'smile',
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
											'min'=> 4,
											'max'=>256,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'comment',
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
											'min'=> 3,
											'max'=>128,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'appearance',
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
											'min'=> 3,
											'max'=>15,
									)
							)
					)
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'color',
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
