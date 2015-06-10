<?php 
namespace Order\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class ShippingItemForm extends Form
{
 
    public function __construct()
    {
        parent::__construct('Order Item Update');
        
        $id=new Element('id');
        
        $id->setAttributes(array(
        		'type'=>'hidden',
        
        		'size'=> '16',
        ));
        
        $shipping_id=new Element('shipping_id');
        $shipping_id->setAttributes(array(
        	'type'=>'hidden',
            'size'=>'4',
        ));
        
        $catalogNo=new Element\Select('catalogNo');
        $catalogNo->setLabel('Catalog Number ');
        
       
        
        $amount=new Element('amount');
        $amount->setLabel('Package Size ');
        $amount->setAttributes(array(
        	'type'=>'text',
            'size'=>'4',
        ));
        
        $shipping_quantities=new Element('shipping_quantities');
        $shipping_quantities->setLabel('Quantities ');
        $shipping_quantities->setAttributes(array(
        	'type'=>'text',
            'size'=>'4',
            'value'=>'1',
            
        ));
        
        $batch=new Element('batch');
        $batch->setLabel('Batch Number');
        $batch->setAttributes(array(
        	'type'=>'text',
            'size'=>16,
            
        ));
        
        $shipping_item_comment=new Element('shipping_item_comment');
        $shipping_item_comment->setLabel('comment ');
        $shipping_item_comment->setAttributes(array(
        	'type'=>'text',
        'size'=>'256',
        ));
        
        $send=new Element('send');
        $send->setValue('Add this item')->setAttributes(array('type'=>'submit'));
        $this->add($id)->add($shipping_id)->add($catalogNo)->add($amount)->add($shipping_quantities)->add($batch)->add($shipping_item_comment)->add($send);
   
     
    }
    
 
    public function getPureData()
    {
    	$data=$this->getData();
    	unset($data['send']);
    	return $data;
    }
}