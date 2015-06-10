<?php 
namespace Order\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class CostForm extends Form
{
 
    public function __construct()
    {
        parent::__construct('Order Item Update');
        
        $id=new Element('id');
        
        $id->setAttributes(array(
        		'type'=>'hidden',
        
        		'size'=> '16',
        ));
        
        $order_id=new Element('order_id');
        $order_id->setAttributes(array(
        	'type'=>'hidden',
            'size'=>'4',
        ));
        
        $cost_name=new Element('cost_name');
        $cost_name->setLabel('Name ');
        $cost_name->setAttributes(array(
        		'type'=>'text',
        		'size'=>'16',
                'value'=>'Banking Fee'
        ));
       
        
        $cost_amount=new Element('cost_amount');
        $cost_amount->setLabel('Amount ');
        $cost_amount->setAttributes(array(
        	'type'=>'text',
            'size'=>'8',
            'value'=>'20'
        ));
        
        $cost_description=new Element('cost_description');
        $cost_description->setLabel('Description ');
        $cost_description->setAttributes(array(
        	'type'=>'text',
            'size'=>'64',
            'value'=>'Fee for process payment made via international wire transfer'
           
            
        ));
    
        
        $cost_comment=new Element('cost_comment');
        $cost_comment->setLabel('Comment ');
        $cost_comment->setAttributes(array(
        	'type'=>'text',
        'size'=>'256',
        ));
        
        $send=new Element('send');
        $send->setValue('Add this item')->setAttributes(array('type'=>'submit'));
        $this->add($id)->add($order_id)->add($cost_name)->add($cost_description)->add($cost_amount)->add($cost_comment)->add($send);
   
     
    }
    
 
    public function getPureData()
    {
    	$data=$this->getData();
    	unset($data['send']);
    	return $data;
    }
}