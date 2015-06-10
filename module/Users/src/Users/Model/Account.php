<?php
namespace Users\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Account implements InputFilterAwareInterface
{
    public $name;
    protected $inputFilter;
    public function exchangeArray($data)
    {
        $this->name = (isset($data['name']))? $data['name'] : null;
        $this->password =(isset($data['password']))? $data['papssword']:null;
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
                	       'max'=>25,
                )
                )
                )
            )));
            $inputFilter->add($factory->createInput(array(
            		'name' => 'password',
            		'required'=>true,
            		'filters'=> array(

            		),
            		'validators'=>array(
            				array(
                					'name'    => 'StringLength',
                                    'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min'      => 6,
                                    'max'      => 12,
                            ),
            				    
            						
            				),
            		),
            )));
            $this->inputFilter=$inputFilter;
        }
        return $this->inputFilter;
    }
}
