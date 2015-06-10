<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Customer\Controller\Search' => 'Customer\Controller\SearchController',
            'Customer\Controller\Update' => 'Customer\Controller\UpdateController',
        ),
    ),
    'router' => array(
        'routes' => array(      //routes array
            'customer' => array(        //customer array
                'type'    => 'Literal',
                'options' => array(     //option array
                    // Change this to something specific to your module
                    'route'    => '/customer',
                    'defaults' => array(    //default array
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Customer\Controller',
                        'controller'    => 'Search',
                        'action'        => 'search',
                    ),                      //end of default array
                ),                      //end of option array
                'may_terminate' => true,
                'child_routes' => array(    //child_routes array

                     'default' => array(    //default array
                        'type'    => 'Segment',
                        'options' => array(     //options array
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(     //constraints array
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),                          //end of contraints array
                            'defaults' => array(
                            ),
                        ),                      //end of options array
                    ),                      //end of default array
                ),                          //end of child_route array
            ),                      //end of customer array
        ),                  //end of routes array
    ),          //end of router array
    'view_manager' => array(
        'template_path_stack' => array(
            'Customer' => __DIR__ . '/../view',
        ),
    ),
);
