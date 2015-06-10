<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Invoice for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Invoice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Where;

class CreateController extends AbstractActionController
{
    public function searchAction()
    {
        $form=new \Invoice\Form\searchForm();
        if($this->params()->fromQuery('searchText'))
        {
            $checker=new \Invoice\Model\SearchFormChecker();
            $form->setInputFilter($checker->getInputFilter());
            $form->setData($this->params()->fromQuery());
            if ($form->isValid())
            {
                $data=$form->getData();
                $searchText=$data['searchText'];
            }
            else 
                return array('form'=>$form);
            
            
            $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
            $where=new Where();
            $where->like('invoice_number', '%'.$searchText.'%');
            $rowset=$invoiceTable->select($where);

            return array('rowset'=>$rowset);
            
        }
        else 
            return array('form'=>$form);
    }

    public function createAction()
    {
        $request=$this->getRequest();
        $form=new \Invoice\Form\InvoiceForm();
        if (!$request->isPost())
        {
        	$this->flashMessenger()->addMessage('You need to find the order first !!');
        	return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['order_id']))
        {
        	$this->flashMessenger()->addMessage('You need to find the order first !!');
        	return $this->redirect()->toUrl('/order');
        }
        
        $order_id=$data['order_id'];
        if(!isset($data['invoice_number']))
        {
            $form->get('order_id')->setValue($order_id);
            return array('form'=>$form);
        }
        $InvoiceTable=$this->getServiceLocator()->get('InvoiceTable');
 
        $form->setData($data);

        $checker=new \Invoice\Model\InvoiceFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {

            $InvoiceTable=$this->getServiceLocator()->get('InvoiceTable');
        	$res=$InvoiceTable->insert($form->getPureData());
        	$message= $res? 'Successfully create an invoice!!':'Error, No Invoice created';
        	 
        	$this->flashMessenger()->addMessage($message);
        	
        	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        
        }else
        	return array('form'=>$form);
    }
    
    public function updateAction()
    {
        $request=$this->getRequest();
        $form=new \Invoice\Form\InvoiceForm();
        if (!$request->isPost())
        {
        	$this->flashMessenger()->addMessage('You need to find the order first !!');
        	return $this->redirect()->toUrl('/order');
        }
        $data=$request->getPost();
        if(!isset($data['id']))
        {
        	$this->flashMessenger()->addMessage('You need to find the order first !!');
        	return $this->redirect()->toUrl('/order');
        }
        
        $id=$data['id'];

        $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
        $invoice=$invoiceTable->getInvoiceById($id);

        $order_id=$invoice->order_id;
        if(!isset($data['invoice_number']))
        {
            $form->setData($invoice->toArray());
            
       
            return array('form'=>$form);
        }
         
        $form->setData($data);
        $checker=new \Invoice\Model\InvoiceFormChecker();
        $form->setInputFilter($checker->getInputFilter());
        if ($form->isValid())
        {
        
        	$invoiceRow=$this->getServiceLocator()->get('InvoiceRow');
        	$invoiceRow->populate($form->getPureData());
        	$res=$invoiceRow->save();
        	$message= $res? 'Successfully update an invoice!!':'Error, No Invoice updated';
        
        	$this->flashMessenger()->addMessage($message);
        	 
        	return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
        
        }else
        	return array('form'=>$form);
        }
        
        public function deleteAction()
        {
            $request=$this->getRequest();
            if (!$request->isPost())
            {
            	$this->flashMessenger()->addMessage('You need to find the order before you can delete invoice!!');
            	return $this->redirect()->toUrl('/order');
            }
            $data=$request->getPost();
            if(!isset($data['id']))
            {
            	$this->flashMessenger()->addMessage('You need to find the order before you can delete invoice!!');
            	return $this->redirect()->toUrl('/order');
            }else
            {
            	$id=$data['id'];
            	$invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
            	$row=$invoiceTable->getInvoiceById($id);
            
            	$order_id=$row->order_id;
            	if (!isset($data['del']))
            	{
            
            		return array('row'=>$row);
            	}else
            	{
            		if ($data['del']=='Yes')
            		{
            			$res=$invoiceTable->delete(array('id'=>$id));
            			if ($res)
            			{
            				$this->flashMessenger()->addMessage('You have successfully deleted an Invoice!!');
            				return $this->redirect()->toUrl('/order/search/show?id='.$order_id);
            			}
            			else
            			{
            				$this->flashMessenger()->addMessage('Error! No Invoice deleted!');
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
        
    public function viewAction()
    {
        $request=$this->getRequest();
        $view=new \Zend\View\Model\ViewModel();
        if (!$request->isPost())
        {
            $this->flashmessenger()->addMessage('You need to find the order before you can view the invoice!');
            $this->redirect()->toUrl('/Order');
            
        }
        $data=$request->getPost();
        if ((!isset($data['order_id'])) && (!(isset($data['invoice_number']))))
        {
            $this->flashmessenger()->addMessage('You need to find the order before you can view the invoice!');
            $this->redirect()->toUrl('/Order');
        }
        $orderTable=$this->getServiceLocator()->get('OrderTable');
        if (isset($data['order_id']))
        {
            $order_id=$data['order_id'];
           
            $order=$orderTable->getOrderById($order_id);
           
          
        }
        if (isset($data['invoice_id']))
        {
            $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
            $invoice=$invoiceTable->getInvoiceById($data['invoice_id']);
            $order=$orderTable->getOrderById($invoice->order_id);
            
        }
        $view->order=$order;
        $view->setTerminal(true);
        return $view;
        
    }
    
    public function showoverdueAction()
    {
        $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
        $rowset=$invoiceTable->getOverdue();
        $view=new \Zend\View\Model\ViewModel();
        $view->rowset=$rowset;
        $view->setTemplate('\invoice\create\search.phtml');
        return $view;
    }
    
    public function showoutstandingAction()
    {
        $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
        $rowset=$invoiceTable->getOutstanding();
        $view=new \Zend\View\Model\ViewModel();
        $view->rowset=$rowset;
        $view->setTemplate('\invoice\create\search.phtml');
        return $view;
    }
    public function setpaidAction()
    {
        $request=$this->getRequest();
        if (!$request->isPost())
        {
            $this->flashmessenger()->addMessage('You need to find the order before you can update the invoice!');
            $this->redirect()->toUrl('/Order');
        }
        $data=$request->getPost();
        if (!isset($data['id']))
        {
            $this->flashmessenger()->addMessage('You need to find the order before you can update the invoice!');
            $this->redirect()->toUrl('/Order');
        }
        $id=$data['id'];
        $invoiceTable=$this->getServiceLocator()->get('InvoiceTable');
        $invoice=$invoiceTable->getInvoiceById($id);
        if (!isset($data['setpaid']))
        {
           
            return array('invoice'=>$invoice);
        }
        else 
        {
            if ($data['setpaid']=='Yes')
            {
                $invoice->setPaid();
                $this->flashMessenger()->addMessage('You have successfully update an Invoice!!');
                return $this->redirect()->toUrl('/order/search/show?id='.$invoice->order_id);
            }
            else
                $this->redirect()->toUrl('/order/show?id='.$invoice->order_id);    
                
        }
        
    }
}
