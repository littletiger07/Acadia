<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Quote for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Quote;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Catalog\Model\CatalogTable;
use Catalog\Model\CatalogRow;
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
    			// DB
    					'QuoteTable' => function($sm) 
    					{
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
                            $quoteTable=new \Quote\Model\QuoteTable($dbAdapter);
    						
    						return $quoteTable;
    					}  ,
    					'QuoteRow'=>function($sm)
    					{
    					    $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    					    $quoteRow=new \Quote\Model\QuoteRow($dbAdapter);
    					    return $quoteRow;
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
