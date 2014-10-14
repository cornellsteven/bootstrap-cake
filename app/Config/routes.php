<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
    
    Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
    Router::connect('/login', array('controller' => 'users', 'action' => 'login', ADMIN => false));
    Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', ADMIN => false));
    Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect('/' . ADMIN, array('controller' => 'users', 'action' => 'index', ADMIN => true));
    Router::connect('/' . ADMIN . '/clearcache', array('controller' => 'users', 'action' => 'clearcache', ADMIN => true));
    
    CakePlugin::routes();
    require CAKE . 'Config' . DS . 'routes.php';
