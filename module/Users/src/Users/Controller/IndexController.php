<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Users for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController,Zend\View\Model\ViewModel;
use Users\Form\RegisterForm;
use Users\Model\Account;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }


    public function registerAction()
    {

        
        $form=new RegisterForm();
        $request=$this->getRequest();
        if ($request->isPost())
        {
            $account = new Account();
            $form->setInputFilter($account->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                echo "form is valid";
                exit();
            }
        }
     return array('form'=>$form);
    }
 
    public function loginAction()
    {
    

        
        $view = new ViewModel();
        $view->setTemplate('users\index\login');
        return $view;
    }
}
        