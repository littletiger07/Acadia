<?php
namespace Order\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ItemFormChecker implements InputFilterAwareInterface
{
	public $name;
	protected $inputFilter;
	protected $dbAdapter;
	
	public function __construct($dbAdapter)
	{
	    $this->dbAdapter=$dbAdapter;
	}
	

	
	
	public function exchangeArray($data)
	{
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->catalogNo=(isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->package_size=(isset($data['package_size']))? $data['package_size']:null;
        $this->quantities=(isset($data['quantities']))? $data['quantities']:null;
        $this->unit_price=(isset($data['unit_price']))? $data['unit_price']:null;
        $this->po_item_comment=(isset($data['po_item_comment']))? $data['po_item_comment']:null;
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
					'name' => 'catalogNo',
					'required'=>true,
					'filters'=> array(
							array('name'=>'StripTags'),
							array('name'=>'StringTrim'),
					),
			        'validators'=> array(
						      array(
			        	            'name' => 'Db\RecordExists',
						            'options'=> array(
			        	                                'table'=> 'catalog',
						                                'field'=> 'catalogNo',
						                                'adapter'=>$this->dbAdapter,
			        )
			        )
					)
						
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'package_size',
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
					'name' => 'quantities',
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
													\Zend\Validator\Regex::NOT_MATCH => "Please enter a valid number!",
													\Zend\Validator\Regex::INVALID => "Regex is invalid!",
													//etc...
											),
									),
							),
					)
			
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name' => 'unit_price',
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
					'name' => 'po_item_comment',
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