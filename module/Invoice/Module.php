<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Invoice for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Invoice;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
    	return array(
    			'abstract_factories' => array(),
    			'aliases' => array(),
    			'factories' => array(
    						
    					'Adapter' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    
    						 
    						return $dbAdapter;
    					}  ,
    					'InvoiceTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$invoiceTable=new \Invoice\Model\InvoiceTable($dbAdapter);
    
    						return $invoiceTable;
    					}  ,
    					'InvoiceRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$invoiceRow=new \Invoice\Model\InvoiceRow($dbAdapter);
    						return $invoiceRow;
    					} ,
    				)
    			);
    }
    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
