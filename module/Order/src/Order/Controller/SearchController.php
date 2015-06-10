<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Order for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Order\Controller;

use Zend\Db\Sql\Select, Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    public function indexAction()
    {
        
        return array();
    }
    
    public function searchAction()
    {
    	$form=new \Order\Form\SearchForm();
    
    	if ($this->params()->fromQuery('searchText'))
    	{
    		$formChecker= new \Order\Model\SearchFormChecker();
    		$form->setInputFilter($formChecker->getInputFilter());
    		$form->setData($this->params()->fromQuery());
    		if ($form->isValid())
    		{
    			$data=($form->getData());
    			$searchText=$data['searchText'];
    
    
    
    			$orderTable=$this->getServiceLocator()->get('orderTable');
    			$where=new Where();
    			$where->like('PO_Number','%'.$searchText.'%');
    			$rowset=$orderTable->select($where);
    
    			if (!($rowset->count()))
    			{
    				$this->flashMessenger()->addMessage('NO Record Found!!!!');
    				return $this->redirect()->toUrl('/order/search/search');
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
    	$showBlock=($this->params()->fromQuery('showblock'))? $this->params()->fromQuery('showblock'):null;

    	$orderTable=$this->getServiceLocator()->get('OrderTable');
    	$itemTable=$this->getServiceLocator()->get('ItemTable');
    	$shippingTable=$this->getServiceLocator()->get('ShippingTable');
    	$where=new Where();
    	$where->equalTo('id',$id);
    	$rowset=$orderTable->select($where);
    	$row=$rowset->current();
    	if (!$row)
    	{
    	    $view->error="No Such Record!";
    	    return $view;
    	}
//     	$rowset=$itemTable->getItemsByOrderId($row->id);
//     	$shippings=$shippingTable->getShippingsByOrderId($row->id);

//     	$view->shippings=$shippings;
//     	$view->po_items=$rowset;
        $view->showBlock=$showBlock;
    	$view->item=$row;
    	return $view;
    }
    
 
  
   
}
