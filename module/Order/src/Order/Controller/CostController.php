<?php
namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * CostController
 *
 * @author
 *
 * @version
 *
 */
class CostController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated CostController::indexAction() default action
        return new ViewModel();
    }
    
    public function addAction()
    {
       $request=$this->getRequest();
        $form=new \Order\Form\CostForm();
        if (!$request->isPost())
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can add cost !!');
            return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['order_id']))
        {
            $this->flashMessenger()->addMessage('You need to find the order before you can add cost !!');
            return $this->redirect()->toUrl('/order');
        }
        $order_id=$data['order_id'];
        if(!isset($data['cost_name']))
        {    
            $form->get('order_id')->setValue($order_id);
            $form->get('send')->setValue('Add this cost');
            return array('form'=>$form);
        }
        $form->setData($data);
        $checker=new \Order\Model\CostFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {
            $costTable=$this->getServiceLocator()->get('CostTable');
            $res=$costTable->insert($form->getPureData());
            $message= $res? 'Successfully added one record to Cost table!!':'Error, No Cost record added';
           
            $this->flashMessenger()->addMessage($message);
            return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            
        }else 
            return array('form'=>$form);
    }
    
    public function updateAction()
    {
        $request=$this->getRequest();
        $form=new \Order\Form\CostForm();
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
        if (!isset($data['cost_name']))
        {
        	$id=$data['id'];
        	$costTable=$this->getServiceLocator()->get('CostTable');
        	$row=$costTable->getItemById($id);
        	 
        	if($row)
        		$form->bind($row);
        	return array('form'=>$form);
        
        }
         
        $form->setData($data);
        $checker=new \Order\Model\CostFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if($form->isValid())
        {
        	$costTable=$this->getServiceLocator()->get('CostTable');
        
        	 
        	$costRow=$this->getServiceLocator()->get('CostRow');
        	
        	$costRow->populate($form->getPureData());
        	$order_id=$costRow->order_id;
        	$res=$costRow->save();
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
    
    public function deleteAction()
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
        	$costTable=$this->getServiceLocator()->get('CostTable');
        	$row=$costTable->getItemById($id);
        
        	$order_id=$row->order_id;
        	if (!isset($data['del']))
        	{
        
        		return array('row'=>$row);
        	}else
        	{
        		if ($data['del']=='Yes')
        		{
        			$res=$row->delete();
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
}