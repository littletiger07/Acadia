<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Catalog for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Catalog\Form\SearchForm;
use Catalog\Model\FormChecker;
use Zend\Db\TableGateway\TableGateway;
use Catalog\Model\Catalog,Catalog\Model\CatalogRow;
use Catalog\Model\CatalogTable;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function searchAction()
    {
        $form=new SearchForm();
        
         if ($this->params()->fromQuery('searchText'))
         {
            $formChecker= new FormChecker;
            $form->setInputFilter($formChecker->getInputFilter());
            $form->setData($this->params()->fromQuery());
            if ($form->isValid())
            {   
                $data=($form->getData());
                $searchText=$data['searchText'];
              


                $catalogTable=$this->getServiceLocator()->get('CatalogTable');
                $where=new Where();
                $where->like('catalogNo','%'.$searchText.'%')->OR->like('cas','%'.$searchText.'%')->OR->like('name','%'.$searchText.'%');
                $rowset=$catalogTable->select($where);

                if (!($rowset->count()))
                {
                    $this->flashMessenger()->addMessage('NO Record Found!!');
                    return $this->redirect()->toUrl('/catalog/search');
                }
            
                
                if ($rowset->count()==1)
                {
                    return $this->redirect()->toUrl('/catalog/search/show?catalogNo='.$rowset->current()->catalogNo);
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
        if (!($this->params()->fromQuery('catalogNo')))
        {
            $view->error="No Such Record!";
            return $view;
                
        }
        $catalogNo=$this->params()->fromQuery('catalogNo');
        $catalogTable=$this->getServiceLocator()->get('CatalogTable');
        $where=new Where();
        $where->equalTo('catalogNo',$catalogNo);
        $rowset=$catalogTable->select($where);
        $row=$rowset->current();
        if (!$row)
        {
            $this->flashMessenger()->addMessage('NO Record Found!!');
            return $this->redirect()->toUrl('/catalog/search');
        }
       
        
        if ($row->cas)
        {
        	$dbAdapter=$this->getServiceLocator()->get('Adapter');
        	$quoteTable= new \Quote\Model\QuoteTable($dbAdapter);
        	$quotes=$quoteTable->getQuotesByCas($row->cas);
        	if($quotes->count())
        		$view->quotes=$quotes;
        }
        
        $view->item=$row;
        return $view;

    }
    
    
    public function msdsAction()
    {
    	$request=$this->getRequest();
    	$form= new \Catalog\Form\MsdsForm();
    	if (!$request->isPost())
    	{
    		if (!($this->params()->fromQuery('catalogNo')))
    		{
    			$this->flashMessenger()->addMessage('Find the record first!!!');
    			return $this->redirect()->toUrl('/catalog/search/search');
    		}
    		$catalogNo=$this->params()->fromQuery('catalogNo');
    	
    		$catalogTable=$this->getServiceLocator()->get('CatalogTable');
    		$row=$catalogTable->getItemByCatalogNo($catalogNo);
    		if($row)
    		  $form->setData($row->toArray());
    		else 
    		{
    		    $this->flashMessenger()->addMessage('No record found!!!');
    		    return $this->redirect()->toUrl('/catalog/search/search');
    		}
    		return array('form'=>$form);
    	}
    	$data=$request->getPost();
    	$checker=new \Catalog\Model\MsdsFormChecker();
    	$form->setInputFilter($checker->getInputFilter());
    	$form->setData($data);
    	if ($form->isValid())
    	{
    	    //$adapter=$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    	    $catalogTable=$this->getServiceLocator()->get('CatalogTable');
    	    $data=$form->getPureData();
//     	    $query='update catalog set appearance="'.$data["appearance"].'", color="'.$data["color"].'" where catalogNo="'.$data["catalogNo"].'"';
//     	    $res=$adapter->query($query)->execute();
            $update=new \Zend\Db\Sql\Update('catalog');
            $update->set(array('appearance'=>$data['appearance'],'color'=>$data['color']));
            $update->where(array('catalogNo'=>$data['catalogNo']));
            $res=$catalogTable->updateWith($update);
    	    $view=new ViewModel();
    	    $view->data=$data;
     	    $view->setTerminal(true);
    	    return $view;
    	}
    }
    
    public function getordersAction()
    {
        $view=new ViewModel();
        if (!($this->params()->fromQuery('catalogNo')))
        {
        	$this->flashMessenger()->addMessage('Find the item first!!!');
    		return $this->redirect()->toUrl('/catalog/search/search');
        
        }
        $catalogNo=$this->params()->fromQuery('catalogNo');
        $catalogTable=$this->getServiceLocator()->get('CatalogTable');
        $row=$catalogTable->getItemByCatalogNo($catalogNo);
        $rowset=$row->getOrders();

        $view->rowset=$rowset;
        $view->setTemplate('\Order\search\search.phtml');
        return $view;
        
    }
}
