<?php
namespace Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalog\Form\UpdateForm;
use Catalog\Model\UpdateFormChecker;
use Zend\Db\TableGateway\TableGateway;
use Catalog\Model\Catalog,Catalog\Model\CatalogRow;
use Catalog\Model\CatalogTable;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;

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
    	$form= new UpdateForm;
    	$view=new ViewModel();
    	$request=$this->getRequest();
    	if(!($request->isPost()))
    	{
    	    if ($this->params()->fromQuery('catalogNo'))
    	    {
    	        $catalogNo=$this->params()->fromQuery('catalogNo');
        	    $catalogTable=$this->getServiceLocator()->get('CatalogTable');
    
        	    $row=$catalogTable->getItemByCatalogNo($catalogNo);

        	    $form->bind($row);
        	    $view->form=$form;
    	     }
    	     else 
    	     {
    	         $form=new \Catalog\Form\SearchForm();
    	         $form->get('searchText')->setName('catalogNo')->setAttributes(array('Lable'=>'Enter the catalog Number',));
    	         $form->get('send')->setValue('Update');
    	         $view->form=$form;
    	         return $view;
    	     }
    	}
    
    	if($request->isPost())
    	{
    	    $check=new UpdateFormChecker();
    	    $form->setInputFilter($check->getInputFilter());
    	    $form->setData($request->getPost());
    	    if ($form->isValid())
    	    {
    	        $data=$form->getPureData();
    	        $catalogRow=$this->getServiceLocator()->get('CatalogRow');
    	        $catalogRow->populate($data);
    	        $res=$catalogRow->save();
    	  
    	        if ($res==1)
    	        {
    	        	$this->flashMessenger()->addMessage('You have Successfully updated record!!');
    	        	return $this->redirect()->toUrl('/catalog/search');
    	        }
    	        else
    	        {
    	        	$this->flashMessenger()->addMessage('Database Error!, No record updated!');
    	        	return $this->redirect()->toUrl('/catalog/update/update');
    	        }

    	    }
    	    else $view->form=$form;
    	}
    	return $view;
    }
    public function addAction()
    {
        $form= new UpdateForm;
        $view=new ViewModel();
        $request=$this->getRequest();
        if(!($request->isPost()))
        {
            $form->get('send')->setValue('Add this item');
            $view->form=$form;
          
        }
        else
        {
            $check=new UpdateFormChecker();
            $form->setInputFilter($check->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
            	$data=$form->getPureData();
            	$catalogRow=$this->getServiceLocator()->get('CatalogRow');
            	$catalogRow->populate($data);
            	$res=$catalogRow->save();
            	if ($res==1)
            	{
            	    $this->flashMessenger()->addMessage('You have Successfully added record!!');
            	    return $this->redirect()->toUrl('/catalog/search');
            	}
            	else
            	{
            	    $this->flashMessenger()->addMessage('Database Error!, No record updated!');
            	    return $this->redirect()->toUrl('/catalog/search/search');
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
            if ($this->params()->fromQuery('catalogNo'))
            {
                $view->catalogNo=$this->params()->fromQuery('catalogNo');
                return $view;
            }    
            
            else
            {
                $this->flashMessenger()->addMessage('You should find the record before you can delete it!');
                return $this->redirect()->toUrl('/catalog/search');
            }
        }
        else 
        {
            $data=$request->getPost();

            if($data['del']=='Yes')
            {
                $catalogTable=$this->getServiceLocator()->get('CatalogTable');
                $row=$catalogTable->getItemByCatalogNo($data['catalogNo']);

                $res=$row->delete();

                if ($res==1)
                {
                    $this->flashMessenger()->addMessage('You have Successfully deleted record!!');
                    return $this->redirect()->toUrl('/catalog/search');
                }
                else
                {
                	$this->flashMessenger()->addMessage('Database Error!, No record deleted!');
                	return $this->redirect()->toUrl('/catalog/search');
                }
            }
            else 
            {
                return $this->redirect()->toUrl('/catalog/search');
            }
        }
    }
    protected function readCSV($csvFile)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle) ) {
        	$line_of_text[] = fgetcsv($file_handle, 1024);
        }
        fclose($file_handle);
        return $line_of_text;
        
        
    }
    
    protected function writeCSV($csvFile, $data)
    {
    	$file_handle = fopen($csvFile, 'a');
        return fputcsv($file_handle, $data);
    
    
    }
    
    public function uploadAction()
    {

     
        $form     = new \Catalog\Form\UploadForm('upload-form');
        $tempFile = null;
        
        $prg = $this->fileprg($form);
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
        	return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
        	if ($form->isValid()) {
        	    $updatedCount=0;
        	    $errorCount=0;
        	    
        	    $catalogForm=new \Catalog\Form\UpdateForm();
        	    $catalogTable=$this->getServiceLocator()->get('CatalogTable');
        	    $check=new \Catalog\Model\UpdateFormChecker();
        	    $catalogForm->setInputFilter($check->getInputFilter()); 
        	    $data = $form->getData();
        		// Form is valid, save the form!
        		$filename=$data['csv_file']['tmp_name'];
        		$csvData=$this->readCSV($filename);
        		
                foreach($csvData as $rowData)
                {
                    $row=array();
                    $row['catalogNo']=$rowData[0];
                    $row['name']=$rowData[1];
                    $row['cas']=$rowData[2];
                    $row['purity']=$rowData[3];
                    $row['package_size1']=$rowData[4];
                    $row['price1']=$rowData[5];
                    $row['package_size2']=$rowData[6];
                    $row['price2']=$rowData[7];

                    $row['formula']=$rowData[9];
                    $row['mw']=$rowData[10];
                    $row['smile']=$rowData[8];
                    $row['comment']= $rowData[11];

 //                   $catalogForm->setData($row);

//                     if ($catalogForm->isValid())
//                     {                     
                        
                        $catalogRow=$catalogTable->getItemByCatalogNo($row['catalogNo']);
                        if ($catalogRow)
                        {
                           $update=new \Zend\Db\Sql\Update('catalog');
                           $update->set($row);
                           $update->where(array('catalogNo'=>$row['catalogNo']));
                           if ($catalogTable->updateWith($update))
                           {
                               $updatedCount++;
                           }
                           else 
                               $errorCount++;
                        }
                        else
                        {
                            if ($catalogTable->insert($row))
                            {
                                $updatedCount++;
                            }
                            else 
                                $errorCount++;
                        }   
                    }
//                     else 
//                         $errorCount++;
                    
 //               }
                $this->flashMessenger()->addMessage('You have Successfully upload record!! with'.$updatedCount.'records updated and '.$errorCount.'records with error');
                return $this->redirect()->toUrl('/catalog/search');
                
        		//return $this->redirect()->toRoute('upload-form/success');
        	} else {
        		// Form not valid, but file uploads might be valid...
        		// Get the temporary file information to show the user in the view
        		$fileErrors = $form->get('csv_file')->getMessages();
        		if (empty($fileErrors)) {
        			$tempFile = $form->get('csv_file')->getValue();
        		}
        	}
        }
//         echo var_dump($_SESSION);
//         exit();
        
        return array(
        		'form'     => $form,
        		'tempFile' => $tempFile,
        );
        
    }
}