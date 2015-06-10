<?php
namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \DateTime;

class ShippingController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated ShippingController::indexAction() default action
        return new ViewModel();
    }
    
    public function addAction()
    {
        $request=$this->getRequest();
        $form=new \Order\Form\ShippingForm();
        if (!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can ship !!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['order_id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can ship !!');
            return $this->redirect()->toUrl('/order');
        }
        $order_id=$data['order_id'];
        if(!isset($data['shipping_date']))
        {    
            $form->get('order_id')->setValue($order_id);
            $form->get('send')->setValue('Add this shipment');
            return array('form'=>$form);
        }
        $form->setData($data);
        $checker=new \Order\Model\ShippingFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {
            $shippingTable=$this->getServiceLocator()->get('ShippingTable');
            $res=$shippingTable->insert($form->getPureData());
            $message= $res? 'Successfully added one record to shipping table!!':'Error, No shipping record added';
         
            $this->flashMessenger()->addMessage($message);
            return $this->redirect()->toUrl('/order/search/show?id='.$order_id.'&showblock=1');
            
        }else 
            return array('form'=>$form);
    }
    
    public function updateAction()
    {
   
        $request=$this->getRequest();
        $form=new \Order\Form\ShippingForm($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter') );
        if(!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can update !!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can update!!');
            return $this->redirect()->toUrl('/order');
        }
        $id=$data['id'];
        $shippingTable=$this->getServiceLocator()->get('ShippingTable');
        if(!isset($data['shipping_date']))
        {
            $shipping=$shippingTable->getShippingById($id);
            if($shipping)
                $form->bind($shipping);
            return array('form'=>$form);
        }
        $form->setData($data);
        $checker=new \Order\Model\ShippingFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {
            $order_id=$form->get('order_id')->getValue();
           
            $update=new \Zend\Db\Sql\Update('shipping');
            $update->set($form->getPureData());
            $update->where(array('id'=>$id));
            $res=$shippingTable->updateWith($update);
  
            if($res)
            {
            	$this->flashMessenger()->addMessage('You have successfully updated the shipment!!');
            	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            }
            else
            {
            	$this->flashMessenger()->addMessage('Error! No Item updated!');
            	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            }
       
              
        }
        else
            return array('form->$form');
    }
 
    
    public function deleteAction()
    {
         $request=$this->getRequest();
        if (!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the shipment before you can delete !!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
            $this->flashMessenger()->addMessage('You need to find the shipment before you can delete !!');
            return $this->redirect()->toUrl('/order');
        }
        $id=$data['id'];
        $shippingTable=$this->getServiceLocator()->get('ShippingTable');
        $row=$shippingTable->getShippingById($id);
        if(!isset($data['del']))
            return array('row'=>$row);
        if ($data['del']=='Yes')
        {
            $shippingItemTable=$this->getServiceLocator()->get('ShippingItemTable');
            $itemRes=$shippingItemTable->delete(array('shipping_id'=>$id));
            $shippingRes=$shippingTable->delete(array('id'=>$id));
            $this->flashMessenger()->addMessage($shippingRes.' shipment(s) deleted from order table and '.$itemRes. 'item(s) deleted from shipping_items table!');
            return $this->redirect()->toUrl('/order/search/show?id='.$row->order_id);
            
        }  
        else 
              return $this->redirect()->toUrl('/order/search/show?id='.$row->order_id);
    }
    
    public function additemAction()
    {
        $request=$this->getRequest();
        $form=new \Order\Form\ShippingItemForm();
        if (!$request->isPost())
        {
        	$this->flashMessenger()->addMessage('You need to find the order before you can ship !!');
        	return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['shipping_id']))
        {
        	$this->flashMessenger()->addMessage('You need to find the order before you can ship !!');
        	return $this->redirect()->toUrl('/order');
        }
        
        $shipping_id=$data['shipping_id'];
        $shippingTable=$this->getServiceLocator()->get('ShippingTable');
        $order_id=$shippingTable->getShippingById($shipping_id)->order_id;
        $orderTable=$this->getServiceLocator()->get('OrderTable');
        $po_items=$orderTable->getOrderById($order_id)->getItems();
        $options=array();
        foreach($po_items as $item)
        	$options[$item->catalogNo]=$item->catalogNo;
        $form->get('shipping_id')->setValue($shipping_id);
        $form->get('catalogNo')->setValueOptions($options);
        $form->get('send')->setValue('Add this item');
        if(!isset($data['catalogNo']))
        {
           
        	return array('form'=>$form);
        }
        $form->setData($data);
        $checker=new \Order\Model\ShippingItemFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {

            $shippingItemTable=$this->getServiceLocator()->get('ShippingItemTable');
        	$res=$shippingItemTable->insert($form->getPureData());
        	$message= $res? 'Successfully added one record to shipping table!!':'Error, No shipping record added';
        	if ($res)
        	{
        		$orderTable=$this->getServiceLocator()->get('OrderTable');
        		$order=$orderTable->getOrderById($order_id);
        		if ($order->isCompleted())
        		{
        			$order->status='complete';
        			$order->save();
        	
        		}
        		else
        		{
        			$order->status='partial';
        			$order->save();
        		}
        	} 
        	$this->flashMessenger()->addMessage($message);
        	
        	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        
        }else
        	return array('form'=>$form);
        }
        
   
    
    public function updateitemAction()
    {
       {
        	$request=$this->getRequest();
        	$form=new \Order\Form\ShippingItemForm();
        	$form->get('send')->setValue('Update this item');
        	if (!$request->isPost())
        	{
        		$this->flashMessenger()->addMessage('You need to find the shipment before you can modify item from!!');
        		return $this->redirect()->toUrl('/order');
        	}
        	$data=$request->getPost();
        	if(!isset($data['id']))
        	{
        		$this->flashMessenger()->addMessage('You need to find the order before you can modify item from!!');
        		return $this->redirect()->toUrl('/order');
        	}
        	$id=$data['id'];
        	$shippingItemTable=$this->getServiceLocator()->get('ShippingItemTable');
        	$shippingTable=$this->getServiceLocator()->get('ShippingTable');
        	$shippingItem=$shippingItemTable->getItemById($id);
        	$shipping_id=$shippingItem->shipping_id;
        	$order_id=$shippingTable->getShippingById($shipping_id)->order_id;
        	$shippingTable=$this->getServiceLocator()->get('ShippingTable');
        	
        	$orderTable=$this->getServiceLocator()->get('OrderTable');
        	$po_items=$orderTable->getOrderById($order_id)->getItems();
        	$options=array();
        	foreach($po_items as $item)
        		$options[$item->catalogNo]=$item->catalogNo;
        	
        	$form->get('catalogNo')->setValueOptions($options);
        	$form->get('send')->setValue('Update this item');
        	if (!isset($data['catalogNo']))
        	{
        	
        		
        		$row=$shippingItemTable->getItemById($id);
        	
        		if($row)
        			$form->bind($row);
        		return array('form'=>$form);
        		
        	} 
        	
    	    $form->setData($data);
    	    $checker=new \Order\Model\ShippingItemFormChecker();
    	    $form->setInputFilter($checker->getInputFilter());
    	    if($form->isValid())
    	    {
                $shippingTable=$this->getServiceLocator()->get('ShippingTable');
                $shipping=$shippingTable->getShippingById($form->get('shipping_id')->getValue());
                $order_id=$shipping->order_id;
    	        $update=new \Zend\Db\Sql\Update('shipping_item');
                $update->set($form->getPureData());
                $update->where(array('id'=>$id));
                $res=$shippingItemTable->updateWith($update);
	            if($res)
	            {
	                $this->flashMessenger()->addMessage('You have successfully updated an item!!');
	                return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
	            }
	            else 
	            {
	                $this->flashMessenger()->addMessage('Error! No Item updated!');
	                return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
    	            }
    	        }
    	        else
    	        	return array('form'=>$form);
    	         
    	    }
        	   
        
    }
    
    public function deleteitemAction()
    {
        $request=$this->getRequest();
        if (!$request->isPost())
        {
        	$this->flashMessenger()->addMessage('You need to find the shipment before you can delete item from!!');
        	return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
        	$this->flashMessenger()->addMessage('You need to find the shipment before you can delete item from!!');
        	return $this->redirect()->toUrl('/order');
        }else
        {
        	$id=$data['id'];
        	$shippingItemTable=$this->getServiceLocator()->get('ShippingItemTable');
        	$row=$shippingItemTable->getItemById($id);       
        	$shipping_id=$row->shipping_id;
        	$shippingTable=$this->getServiceLocator()->get('ShippingTable');
        	$ship=$shippingTable->getShippingById($shipping_id);
        	$order_id=$ship->order_id;
        	if (!isset($data['del']))
        	{
        
        		return array('row'=>$row);
        	}else
        	{
        		if ($data['del']=='Yes')
        		{
        			$res=$shippingItemTable->delete(array('id'=>$id));
        			if ($res)
        			{
        				$this->flashMessenger()->addMessage('You have successfully deleted an item!!');
        				return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        			}
        			else
        			{
        				$this->flashMessenger()->addMessage('Error! No Item deleted!');
        				return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        			}
        		}
        		else
        		{
        			return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        		}
        	}
        
        }
    }
    
    public function packingslipAction()
        {
            $request=$this->getRequest();
            if (!$request->isPost())
            {
                $this->flashMessenger()->addMessage('You have find the order to generate the packing slip !!');
                return $this->redirect()->toUrl('/order/');
            }
            $data=$request->getPost();
            if(!isset($data['id']))
            {
                $this->flashMessenger()->addMessage('You have find the order to generate the packing slip !!');
                return $this->redirect()->toUrl('/order/');
            }
            $id=$data['id'];
            $adapter=$this->getServiceLocator()->get('\Zend\Db\Adapter\Adapter');
            $query='select * from shipping join po on shipping.order_id=po.id join customer on po.customer_id=customer.id where shipping.id='.$id;
            $res=$adapter->query($query)->execute();
        
            $data=$res->current();
            $query='select * from shipping_item join catalog on shipping_item.catalogNo=catalog.catalogNo where shipping_id='.$id;
            
            $res=$adapter->query($query)->execute();
        
            $view=new \Zend\View\Model\ViewModel();
            $view->data=$data;
            $view->items=$res;
            $view->setTerminal(true);
            return $view;     
                    
                   
                           
        }
     public function invoiceAction()
     {
         $request=$this->getRequest();
         if (!$request->isPost())
         {
         	$this->flashMessenger()->addMessage('You have find the order to generate the packing slip !!');
         	return $this->redirect()->toUrl('/order/');
         }
         $data=$request->getPost();
         if(!isset($data['id']))
         {
         	if(!isset($data['invoice_number']))
         	{ 
         	    $this->flashMessenger()->addMessage('You have find the order to generate the packing slip !!');
         	    return $this->redirect()->toUrl('/order/');
         	}
         	else
         	{
//          	      echo var_dump($data);
//          	      exit();
         	      $view=new \Zend\View\Model\ViewModel();
                  $view->data=$data;
                  $view->setTemplate('\order\shipping\custominvoice.phtml');
                  $view->setTerminal(true);
                  return $view;
         	}
         }
         $id=$data['id'];
         $invoiceData=array();
         $shippingTable=$this->getServiceLocator()->get('ShippingTable');
         $orderTable=$this->getServiceLocator()->get('OrderTable');
         $shipping=$shippingTable->getShippingById($id);
         $invoiceData['tracking']=$shipping->tracking;
         $order=$orderTable->getOrderById($shipping->order_id);
         $invoiceData['billing_address']=$order->getcustomer()->address;
         $invoiceData['shipping_address']=$order->shipping_address;
         $invoiceData['PO_Number']=$order->PO_Number;
         $invoiceData['term']=$order->getCustomer()->term;
         
         if ($invoice=$order->getInvoice())
         {
             $invoiceData['invoice_date']=$invoice->invoice_date;
             $invoiceData['invoice_number']=$invoice->invoice_date;
             $invoiceData['due_date']=$invoice->getDueDate();
         }
         else 
         {
             $invoiceData['invoice_date']=$shipping->shipping_date;
             $invoiceData['invoice_number']='';
         }
         $items=$shipping->getItems()->toArray();
         $itemTable=$this->getServiceLocator()->get('ItemTable');
         $adapter=$this->getServiceLocator()->get('\Zend\Db\Adapter\Adapter');
         $catalogTable=new \Catalog\Model\CatalogTable($adapter);
         foreach($items as &$item)
         {
             $orderItem=$itemTable->getItemByOrderIdAndCatalogNo($shipping->order_id, $item['catalogNo']);
             $item['price']=$item['shipping_quantities']*$item['amount']/$orderItem->package_size /$orderItem->quantities * $orderItem->unit_price;
             $item['name']=$catalogTable->getItemByCatalogNo($item['catalogNo'])->name;
         }
         
         $view=new \Zend\View\Model\ViewModel();
         $view->data=$invoiceData;
         $view->items=$items;
         $view->shipping=$shipping;
         $view->setTerminal(true);
         
         return $view;
         
     }

}