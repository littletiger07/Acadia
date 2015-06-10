<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Customer for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Customer\Controller;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class SearchController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /search/search/foo
        return array();
    }
    
    public function searchAction()
    {
        $form=new \Customer\Form\SearchForm();
        
         if ($this->params()->fromQuery('searchText'))
         {
            $formChecker= new \Customer\Model\SearchFormChecker();
            $form->setInputFilter($formChecker->getInputFilter());
            $form->setData($this->params()->fromQuery());
            if ($form->isValid())
            {   
                $data=($form->getData());
                $searchText=$data['searchText'];
              


                $customerTable=$this->getServiceLocator()->get('CustomerTable');
                $where=new Where();
                $where->like('customer_name','%'.$searchText.'%');
                $rowset=$customerTable->select($where);

                if (!($rowset->count()))
                {
                    $this->flashMessenger()->addMessage('NO Record Found!!!!');
                    return $this->redirect()->toUrl('/customer/search/search');
                }
                $view=new ViewModel();
                $view->rowset=$rowset;
                return $view;
                
            }
         }
      return array('form'=>$form);
    }
    
    public function showAction()
    {
    	$view=new ViewModel();
    	if (!($this->params()->fromQuery('id')))
    	{
    		$view->error="No Such Record!";
    		return $view;
    
    	}
    	$id=$this->params()->fromQuery('id');
    	$customerTable=$this->getServiceLocator()->get('CustomerTable');
    	$where=new Where();
    	$where->equalTo('id',$id);
    	$rowset=$customerTable->select($where);
    	$row=$rowset->current();

    	$view->item=$row;
    	return $view;
    } 

    public function showallAction()
    {
        $view=new ViewModel();
        $customerTable=$this->getServiceLocator()->get('CustomerTable');
        $view->rowset=$customerTable->getAllCustomer();
        $view->setTemplate('\customer\search\search.phtml');
        return $view;
    }
    
    public function getordersAction()
    {
        $view=new ViewModel();
        if (!($this->params()->fromQuery('id')))
        {
        	$view->error="No Such Record!";
        	return $view;
        
        }
        $id=$this->params()->fromQuery('id');
        $customerTable=$this->getServiceLocator()->get('CustomerTable');
        $customer=$customerTable->getCustomerById($id);
        $orders=$customer->getOrders();
        $view->rowset=$orders;
        $view->setTemplate('\order\search\search.phtml');
        return $view;
    }
    
    public function getaddressAction()
    {
    	$id=$this->params()->fromQuery('id');
    	if (!$id)
    		return '';
    	$customerTable=$this->getServiceLocator()->get('CustomerTable');
    	$customer=$customerTable->getCustomerById($id);
    	$address= $customer->address;
    	$view=new ViewModel(array('address'=>$address));
    	$view->setTerminal(true);
    	return $view;
    }
    
    public function getshippingaddresscountAction()
    {
    	$id=$this->params()->fromQuery('id');
    	$adapter=$this->getServiceLocator()->get('Adapter');
    	$sql='select count(distinct shipping_address) from po where customer_id='.$id;
    	$statement=$adapter->query($sql);
    	$res=$statement->execute()->current();
    	$count=$res['count(distinct shipping_address)'];
    	$view=new ViewModel(array('count'=>$count));
    	$view->setTerminal(true);
    	return $view;
    	
    }
    
    public function getshippingaddressAction()
    {
    	$id=$this->params()->fromQuery('id');
    	$index=$this->params()->fromQuery('index');
    	$adapter=$this->getServiceLocator()->get('Adapter');
    	$sql='select distinct shipping_address from po where customer_id='.$id.' order by order_date desc limit '.($index-1).', 1';
    	
    	$statement=$adapter->query($sql);
    	$res=$statement->execute()->current();
    	$address=$res['shipping_address'];
		$view=new ViewModel(array('address'=>$address));
		$view->setTerminal(true);
		return $view;
    }
   
}
