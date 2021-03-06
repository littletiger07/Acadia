<?php
namespace Customer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class SearchFormChecker implements InputFilterAwareInterface
{
    public $name;
    protected $inputFilter;
    public function exchangeArray($data)
    {
        $this->searchText = (isset($data['searchText']))? $data['searchText'] : null;
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
            	'name' => 'searchText',
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
                	       'max'=>25,
                )
                )
                )
            )));

            $this->inputFilter=$inputFilter;
        }
        return $this->inputFilter;
    }
}
