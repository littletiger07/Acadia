<?php
namespace Order\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class ItemForm extends Form
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
        
        $catalogNo=new Element('catalogNo');
        $catalogNo->setLabel('Catalog Number ');
        $catalogNo->setAttributes(array(
        	'type'=>'text',
            'size'=>'12',
        ));
        
        $package_size=new Element('package_size');
        $package_size->setLabel('Package Size ');
        $package_size->setAttributes(array(
        	'type'=>'text',
            'size'=>'4',
        ));
        
        $quantities=new Element('quantities');
        $quantities->setLabel('Quantities ');
        $quantities->setAttributes(array(
        	'type'=>'text',
            'size'=>'4',
            'value'=>'1',
            
        ));
        
        $unit_price=new Element('unit_price');
        $unit_price->setLabel('unit_price');
        $unit_price->setAttributes(array(
        	'type'=>'text',
            'size'=>8,
            
        ));
        
        $po_item_comment=new Element('po_item_comment');
        $po_item_comment->setLabel('po_item_comment ');
        $po_item_comment->setAttributes(array(
        	'type'=>'text',
        'size'=>'32',
        ));
        
        $send=new Element('send');
        $send->setValue('Add this item')->setAttributes(array('type'=>'submit'));
        $this->add($id)->add($order_id)->add($catalogNo)->add($package_size)->add($quantities)->add($unit_price)->add($po_item_comment)->add($send);
   
     
    }
    public function getPureData()
    {
    	$data=$this->getData();
    	unset($data['send']);
    	return $data;
    }
}