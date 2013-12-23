<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
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
	
	date_default_timezone_set('America/Chicago');
	
	// Check for localhost
	if (FALSE === strstr($_SERVER['HTTP_HOST'], 'localhost')) {
		Configure::write('localhost', false);
	} else {
		Configure::write('localhost', true);
	}
	
	// Import config
	require 'config.php';
	
	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED & ~E_STRICT,
		'trace' => false
	));
	
	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => false
	));
	
	Configure::write('App.encoding', 'UTF-8');
	
	//Configure::write('App.baseUrl', env('SCRIPT_NAME'));
	//Configure::write('App.fullBaseUrl', 'http://example.com');
	
	Configure::write('App.imageBaseUrl', 'images/');
	Configure::write('App.cssBaseUrl', 'stylesheets/css/');
	Configure::write('App.jsBaseUrl', 'js/');
	
	Configure::write('Routing.prefixes', array(ADMIN));
	
	if (Configure::read('localhost') || Configure::read('debug') > 0) {
		Configure::write('Cache.disable', true);
	}
	
	Configure::write('Cache.check', true);
	
	Configure::write('Session', array(
		'defaults' => 'php',
		'cookie' => '_brksd',
		'timeout' => 1440,
	));
	
	// Tip: use the random password generator located at: 
	// http://www.sethcardoza.com/tools/random-password-generator/
	Configure::write('Security.salt', 'dbi%A6-HQW50TWoQ)CKspXD^J7zK^Ew^4FEB2KoQ');
	Configure::write('Security.cipherSeed', '46848179433343234196646981938561');

	Configure::write('Asset.timestamp', 'force');
	
	Configure::write('Acl.classname', 'DbAcl');
	Configure::write('Acl.database', 'default');

	Configure::write('Config.timezone', 'America/Chicago');
	
	
	
	
	
	$engine = 'File';

	// In development mode, caches should expire quickly.
	$duration = '+999 days';
	if (Configure::read('debug') > 0) {
		$duration = '+10 seconds';
	}

	// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
	$prefix = 'myapp_';

	/**
	 * Configure the cache used for general framework caching. Path information,
	 * object listings, and translation cache files are stored with this configuration.
	 */
	Cache::config('_cake_core_', array(
		'engine' => $engine,
		'prefix' => $prefix . 'cake_core_',
		'path' => CACHE . 'persistent' . DS,
		'serialize' => ($engine === 'File'),
		'duration' => $duration
	));

	/**
	 * Configure the cache for model and datasource caches. This cache configuration
	 * is used to store schema descriptions, and table listings in connections.
	 */
	Cache::config('_cake_model_', array(
		'engine' => $engine,
		'prefix' => $prefix . 'cake_model_',
		'path' => CACHE . 'models' . DS,
		'serialize' => ($engine === 'File'),
		'duration' => $duration
	));
	
	
	
	
	
	
	// Change some settings if we're IN debug mode
	if (Configure::read('debug') > 0) {
		
		// Clear the cache
		Cache::clear();
		clearCache();
		
		// Turn off caching
		Configure::write('Cache.disable', true);
		
		// Turn certain error reporting on
		Configure::write('Error.trace', true);
		Configure::write('Exception.log', true);
	}
	