<?php

namespace Quote\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


/**
 * SearchController
 *
 * @author
 *
 * @version
 *
 */
class SearchController extends AbstractActionController {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated SearchController::indexAction() default action
		
		return new ViewModel ();
	}
	
	
	public function searchAction()
	{
		$form=new \Quote\Form\SearchForm();
	
		if ($this->params()->fromQuery('searchText'))
		{
			$formChecker= new \Quote\Model\FormChecker;
			$form->setInputFilter($formChecker->getInputFilter());
			$form->setData($this->params()->fromQuery());
			if ($form->isValid())
			{
				$data=($form->getData());
				$searchText=$data['searchText'];
	
	
	
				$quoteTable=$this->getServiceLocator()->get('QuoteTable');
				$where=new Where();
				$where->like('quote_email','%'.$searchText.'%')->OR->like('quote_cas','%'.$searchText.'%');
				$rowset=$quoteTable->select($where);
	
				if (!($rowset->count()))
				{
					$this->flashMessenger()->addMessage('NO Record Found!!');
					return $this->redirect()->toUrl('/quote/search/search');
				}
	
	
				
				$view=new ViewModel();
				$view->rowset=$rowset;
				return $view;
			}
		}
		return array('form'=>$form);
	}
		
	
}