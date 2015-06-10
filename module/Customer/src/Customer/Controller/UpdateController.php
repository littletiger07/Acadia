<?php
namespace Customer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * UpdateController
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
        // TODO Auto-generated UpdateController::indexAction() default action
        return new ViewModel();
    }
    
    public function updateAction()
    {
        $form= new \Customer\Form\UpdateForm();
        $view=new \Zend\View\Model\ViewModel();
        $request=$this->getRequest();
    	if(!($request->isPost()))
    	{
    	    if ($this->params()->fromQuery('id'))
    	    {
    	        $id=$this->params()->fromQuery('id');
        	    $customerTable=$this->getServiceLocator()->get('CustomerTable');
    
        	    $row=$customerTable->getCustomerById($id);

        	    $form->bind($row);
        	    $view->form=$form;
        	    return $view;
    	     }
    	     else 
    	     {
    	         $this->flashMessenger()->addMessage('Please search for the record first!!');
    	        return $this->redirect()->toUrl('/customer/search/search');
    	         
    	     }
    	}
    	else 
    	{
    	    $check=new \Customer\Model\UpdateFormChecker();
    	    $form->setInputFilter($check->getInputFilter());
    	    $form->setData($request->getPost());
    	    if ($form->isValid())
    	    {
    	        $data=$form->getPureData();
    	        $customerRow=$this->getServiceLocator()->get('CustomerRow');
    	        $customerRow->populate($data);

    	        $res=$customerRow->save();
    	  
    	        if ($res==1)
    	        {
    	        	$this->flashMessenger()->addMessage('You have Successfully updated record!!');
    	        	return $this->redirect()->toUrl('/customer/search');
    	        }
    	        else
    	        {
    	        	$this->flashMessenger()->addMessage('Database Error!, No record updated!');
    	        	return $this->redirect()->toUrl('/customer/search');
    	        }

    	    }
    	    else $view->form=$form;
    	}
    	return $view;
    }
    
    public function addAction()
    {
    	$form= new \Customer\Form\UpdateForm;
    	$view=new ViewModel();
    	$request=$this->getRequest();
    	if(!($request->isPost()))
    	{
    		$form->get('send')->setValue('Add this customer');
    	    $view->form=$form;
    
    	}
    	else
    	{
    		$check=new \Customer\Model\UpdateFormChecker();
    		$form->setInputFilter($check->getInputFilter());
    		$form->setData($request->getPost());
    		if ($form->isValid())
    		{
    			$data=$form->getPureData();
    			$customerRow=$this->getServiceLocator()->get('CustomerRow');
    			$customerRow->populate($data);
    			$res=$customerRow->save();
    			if ($res==1)
    			{
    				$this->flashMessenger()->addMessage('You have Successfully added record!!');
    				return $this->redirect()->toUrl('/customer/search/search');
    			}
    			else
    			{
    				$this->flashMessenger()->addMessage('Database Error!, No record updated!');
    				return $this->redirect()->toUrl('/customer/search/search');
    			}
    
    		}
    		else $view->form=$form;
    	}
    	return $view;
    }
    
    public function deleteAction()
    {
    	$request=$this->getRequest();
    	$view=new ViewModel();
    	if (!($request->isPost()))
    	{
    		if ($this->params()->fromQuery('id'))
    		{
    			
    			$customerTable=$this->getServiceLocator()->get('CustomerTable');
    			$row=$customerTable->getCustomerById($this->params()->fromQuery('id'));
    			
    			$view->customer=$row;
    			return $view;
    		}
    
    		else
    		{
    			$this->flashMessenger()->addMessage('You should find the record before you can delete it!');
    			return $this->redirect()->toUrl('/customer/search/search');
    		}
    	}
    	else
    	{
    		$data=$request->getPost();
    
    		if($data['del']=='Yes')
    		{
    			$customerTable=$this->getServiceLocator()->get('CustomerTable');
    			$row=$customerTable->getCustomerById($data['id']);
    
    			$res=$row->delete();
    
    			if ($res==1)
    			{
    				$this->flashMessenger()->addMessage('You have Successfully deleted record!!');
    				return $this->redirect()->toUrl('/customer/search');
    			}
    			else
    			{
    				$this->flashMessenger()->addMessage('Database Error!, No record deleted!');
    				return $this->redirect()->toUrl('/customer/search');
    			}
    		}
    		else
    		{
    			return $this->redirect()->toUrl('/customer/search/search');
    		}
    	}
    }
}