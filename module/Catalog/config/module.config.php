<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Catalog\Controller\Search' => 'Catalog\Controller\SearchController',
            'Catalog\Controller\Update' => 'Catalog\Controller\UpdateController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'catalog' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/catalog',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Catalog\Controller',
                        'controller'    => 'Search',
                        'action'        => 'search',
                                        ),
                                   ),
                'may_terminate' => true,
                'child_routes' => array(
//                     'search' => array(
//                     		'type'    => 'segment',
//                     		'options' => array(
//                     				'route'       => '/search[/:action][/:searchText]',
//                     				'constraints' => array(
//                     						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                     				),
//                     				'defaults' => array(
//                     						'controller' => 'search',
//                     						'action'     => 'index',
//                     				),
//                     		),
//                         ),
                    
                    
                    
                    
                    
                    
                    
                                    // This route is a sane default when developing a module;
                                    // as you solidify the routes for your module, however,
                                    // you may want to remove it and replace it with more
                                    // specific routes.
                                    'default' => array(
                                        'type'    => 'Segment',
                                        'options' => array(
                                            'route'    => '/[:controller[/:action]]',
                                            'constraints' => array(
                                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                                   ),
                                            'defaults' => array(
                
                                                                ),
                                                           ),
                                                        ),
                                         ),
                                ),
                          ),
                     
    
    
    
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Catalog' => __DIR__ . '/../view',
                                       ),
                            ),
);
