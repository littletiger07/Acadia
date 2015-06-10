<?php
namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * updateController
 *
 * @author
 *
 * @version
 *
 */
class UpdateController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated updateController::indexAction() default action
        return new ViewModel();
    }
    
    public function addAction()
    {
    	$check=new \Order\Model\UpdateFormChecker(3);
        $request=$this->getRequest();
        $form=new \Order\Form\UpdateForm($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),3);
        $form->setInputFilter($check->getInputFilter());
    	$form->get('send')->setValue('Add this order');
    	if ($request->isPost())
    	{
    	    $form->setData($request->getPost());
    	    if ($form->isValid())
    	    {
    	        $orderTable=$this->getServiceLocator()->get('OrderTable');
    	        $PO_Number=$form->get('PO_Number')->getValue();
                $customer_id=$form->get('customer_id')->getValue();
                if ($orderTable->select(array('customer_id'=>$customer_id, 'PO_Number'=>$PO_Number))->count())
                {
                    $this->flashMessenger()->addMessage('Error! Duplicated Item!');
                    return $this->redirect()->toUrl('/order/');
                }
                else 
                {
                    $data=$form->getPureData();
              
        	        $orderTable=$this->getServiceLocator()->get('OrderTable');
        	        $res=$orderTable->insert($data);
        	        if ($res)
        	        {
        	           $id=$orderTable->select(array('customer_id'=>$customer_id, 'PO_Number'=>$PO_Number))->current()->id;
        	           $this->flashMessenger()->addMessage('You have Successfully added order, now please add items for this order!!');
        			   return $this->redirect()->toUrl('/order/search/show?id='.$id);
        	        }
        	        else 
        	        {
        	            $this->flashMessenger()->addMessage('Error!No record added!!');
        	            return $this->redirect()->toUrl('/order/search/show?id='.$id);
        	        }
                }

    	  }
    	    
        }
    	return array('form'=>$form);
    }
    
    public function updateAction()
    {
        $request=$this->getRequest();
        $form=new \Order\Form\UpdateForm($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter') );
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
        $orderTable=$this->getServiceLocator()->get('OrderTable');
        if(!isset($data['PO_Number']))
        {
            $order=$orderTable->getOrderById($id);
            if($order)
                $form->bind($order);
            return array('form'=>$form);
        }
        $form->setData($data);
        $checker=new \Order\Model\UpdateFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {
        $PO_Number=$form->get('PO_Number')->getValue();
        $customer_id=$form->get('customer_id')->getValue();
       
            $orderRow=$this->getServiceLocator()->get('OrderRow');
            $orderRow->populate($form->getPureData());
            $res=$orderRow->save();
            if($res)
            {
            	$this->flashMessenger()->addMessage('You have successfully updated the order!!');
            	return $this->redirect()->toUrl('/order/search/show?id='.$id);
            }
            else
            {
            	$this->flashMessenger()->addMessage('Error! No Item updated!');
            	return $this->redirect()->toUrl('/order/search/show?id='.$id);
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
            $this->flashMessenger()->addMessage('You need to find the order before you can delete !!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can delete !!');
            return $this->redirect()->toUrl('/order');
        }
        $id=$data['id'];
        $orderTable=$this->getServiceLocator()->get('OrderTable');
        $row=$orderTable->getOrderById($id);
        if(!isset($data['del']))
            return array('row'=>$row);
        if ($data['del']=='Yes')
        {
            $itemTable=$this->getServiceLocator()->get('ItemTable');
            $itemRes=$itemTable->delete(array('order_id'=>$id));
            $orderRes=$orderTable->delete(array('id'=>$id));
            $this->flashMessenger()->addMessage($orderRes.' order(s) deleted from order table and '.$itemRes. 'item(s) deleted from po_items table!');
            return $this->redirect()->toUrl('/order');
            
        }  
        else 
            return $this->redirect()->toUrl('/order');
    }
    
    
    
    public function additemAction()
    {
        $form=new \Order\Form\ItemForm();
        $view=new \Zend\View\Model\ViewModel();
        $request=$this->getRequest();
        if(!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can add item to!!');
            return $this->redirect()->toUrl('/order');
        }
        
        $data=$request->getPost();

        if (!isset($data['order_id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can add item to!!');
            return $this->redirect()->toUrl('/order');
        }
        $order_id=$data['order_id'];
        if(!isset($data['catalogNo']))
        {    
            $form->get('order_id')->setValue($order_id);

            $view->form=$form;
             return $view;
        }
        $form->setData($data);
        $dbAdapter=$this->getServiceLocator()->get('Adapter');
        $check=new \Order\Model\ItemFormChecker($dbAdapter);
        $form->setInputFilter($check->getInputFilter());
        if ($form->isValid())
        {
            $itemTable=$this->getServiceLocator()->get('ItemTable');
            $catalogNo=$form->get('catalogNo')->getValue();
            $order_id=$form->get('order_id')->getValue();
            $catalogTable=new \Catalog\Model\CatalogTable($itemTable->getAdapter());
            if (!($catalogTable->getItemByCatalogNo($catalogNo)))
            {
                $this->flashMessenger()->addMessage('This Catalog Number is not valid!!');
                return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            }
            if ($itemTable->select(array('order_id'=>$order_id,'catalogNo'=>$catalogNo))->count()==0)
            {
                $res=$itemTable->insert($form->getPureData());
                if ($res)
                {
                    $this->flashMessenger()->addMessage('You have Successfully added item!');
                    return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
                }
                else
                {
                    $this->flashMessenger()->addMessage('Error! No Item added!');
                    return $this->redirect()->toUrl('/order/search/show?id='.$form->get('order_id')->getValue());
                }
                
            }
            else 
            {
                $this->flashMessenger()->addMessage('Error! Duplicated Item!');
                return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            }
              
        }
        else 
            $view->form=$form;
        return $view;
    }
    
    public function deleteitemAction()
    {
        $request=$this->getRequest();
        if (!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can delete item from!!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can delete item from!!');
            return $this->redirect()->toUrl('/order');
        }else 
        {
            $id=$data['id'];
            $itemTable=$this->getServiceLocator()->get('ItemTable');
            $row=$itemTable->getItemById($id);
          
            $order_id=$row->order_id;
            if (!isset($data['del']))
            {
                
                return array('row'=>$row);
            }else
            {
                if ($data['del']=='Yes')
                {
                    $res=$itemTable->delete(array('id'=>$id));
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

        public function updateitemAction()
        {
        	$request=$this->getRequest();
        	$form=new \Order\Form\ItemForm();
        	$form->get('send')->setValue('Update this item');
        	if (!$request->isPost())
        	{
        		$this->flashMessenger()->addMessage('You need to find the order before you can modify item from!!');
        		return $this->redirect()->toUrl('/order');
        	}
        	$data=$request->getPost();
        	if(!isset($data['id']))
        	{
        		$this->flashMessenger()->addMessage('You need to find the order before you can modify item from!!');
        		return $this->redirect()->toUrl('/order');
        	}
        	if (!isset($data['catalogNo']))
        	{
        		$id=$data['id'];
        		$itemTable=$this->getServiceLocator()->get('ItemTable');
        		$row=$itemTable->getItemById($id);
        	
        		if($row)
        			$form->bind($row);
        		return array('form'=>$form);
        		
        	} 
    	
    	    $form->setData($data);
    	    $dbAdapter=$this->getServiceLocator()->get('Adapter');
    	    $checker=new \Order\Model\ItemFormChecker($dbAdapter);
    	    $form->setInputFilter($checker->getInputFilter());
    	    if($form->isValid())
    	    {
    	        $itemTable=$this->getServiceLocator()->get('ItemTable');
    	        $catalogNo=$form->get('catalogNo')->getValue();
    	        $id=$form->get('id')->getValue();
    	        $order_id=$form->get('order_id')->getValue();
    	        $catalogTable=new \Catalog\Model\CatalogTable($itemTable->getAdapter());
    	        if (!($catalogTable->getItemByCatalogNo($catalogNo)))
    	        {
    	        	$this->flashMessenger()->addMessage('This Catalog Number is not valid!!');
    	        	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
    	        }
    	      
	            $itemRow=$this->getServiceLocator()->get('ItemRow');
	            $itemRow->populate($form->getPureData());
	            $res=$itemRow->save();
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
  
