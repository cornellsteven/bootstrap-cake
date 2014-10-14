<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

CakePlugin::loadAll(); // Loads all plugins at once

Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));

App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
    'engine' => 'File',
    'types' => array('notice', 'info', 'debug'),
    'file' => 'debug',
));
CakeLog::config('error', array(
    'engine' => 'File',
    'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
    'file' => 'error',
));

// Set up some cache configurations
Cache::config('default', array(
    'engine' => 'File', 
    'duration'=> 3600, 
    'probability'=> 100, 
    'path' => CACHE, 
    'prefix' => 'cake_', 
    'serialize' => true, 
    'mask' => 0666,
));

// 1 hour
Cache::config('short', array(
    'engine' => 'File',
    'duration' => '+1 hours',
    'path' => CACHE,
    'prefix' => 'cake_short_',
    'path' => CACHE . 'short' . DS,
));

// 1 day
Cache::config('medium', array(
    'engine' => 'File',
    'duration' => '+12 hours',
    'path' => CACHE,
    'prefix' => 'cake_medium_',
    'path' => CACHE . 'medium' . DS,
));

// long (1 week)
Cache::config('long', array(
    'engine' => 'File',
    'duration' => '+1 week',
    'probability' => 100,
    'path' => CACHE . 'long' . DS,
));

