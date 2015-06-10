<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Order for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Order;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Order\Model\OrderTable;
use Order\Model\OrderRow;
use Order\Model\ItemTable;
use Order\Model\ItemRow;

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
        			    	
        			    
        			    	return $dpAdapter;
        			    }  ,
    					'OrderTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$orderTable=new OrderTable($dbAdapter);
    
    						return $orderTable;
    					}  ,
    					'OrderRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$orderRow=new OrderRow($dbAdapter);
    						return $orderRow;
    					} ,
    					
    					'ItemTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$itemTable=new ItemTable($dbAdapter);
    					
    						return $itemTable;
    					}  ,
    					'ItemRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$itemRow=new ItemRow($dbAdapter);
    						return $itemRow;
    					} ,
    					
    					'ShippingTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$shippingTable=new \Order\Model\ShippingTable($dbAdapter);
    							
    						return $shippingTable;
    					}  ,
    					'ShippingRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$shippingRow=new \Order\Model\ShippingRow($dbAdapter);
    						return $shippingRow;
    					} ,
    					
    					'ShippingItemTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$shippingItemTable=new \Order\Model\ShippingItemTable($dbAdapter);
    							
    						return $shippingItemTable;
    					}  ,
    					'ShippingItemRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$shippingItemRow=new \Order\Model\ShippingItemRow($dbAdapter);
    						return $shippingItemRow;
    					} ,
    					'CostTable' => function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$costTable=new \Order\Model\CostTable($dbAdapter);
    							
    						return $costTable;
    					}  ,
    					'CostRow'=>function($sm)
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$costRow=new \Order\Model\CostRow($dbAdapter);
    						return $costRow;
    					} ,
    					
    			),
    	);
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $em = $e->getApplication()->getEventManager();
        
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($e) {
        	$flashMessenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
        	if ($flashMessenger->hasMessages()) {
        		$e->getViewModel()->setVariable('flashMessages', $flashMessenger->getMessages());
        	}
        });
        
        }
        }
        