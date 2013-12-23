<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

class AppModel extends Model {
	
	/**
	 * Set all models to be Containable by default
	 *
	 * @var string
	 */
	public $actsAs = array(
		'Containable',
	);
	
	/**
	 * List all phone number field names (E.g., phone, cell_phone, work_phone)
	 *
	 * @var string
	 */
	public $phone_fields = array(
		'phone',
	);
	
	/**
	 * Add some universal pre-save formatting/parsing
	 *
	 * @param string $options 
	 * @return void
	 * @author cornellcampbell
	 */
	public function beforeSave($options = array()) {
		// Remove non-number characters from phone numbers
		foreach ($this->phone_fields as $field) {
			if (isset($this->data[$this->alias][$field])) {
				$this->data[$this->alias][$field] = preg_replace('/\D/', '', $this->data[$this->alias][$field]);
			}
			if (isset($this->data[$field])) {
				$this->data[$field] = preg_replace('/\D/', '', $this->data[$field]);
			}
		}
		
		// Convert ip address to INT for proper storage in db
		if (isset($this->data[$this->alias]['ip'])) {
			$this->data[$this->alias]['ip'] = ip2long($this->data[$this->alias]['ip']);
		}
		
		return true;
		
	}
	
	/**
	 * Format / parse a few fields universally
	 *
	 * @param string $results 
	 * @param string $primary 
	 * @return void
	 * @author Cornell Campbell
	 */
	public function afterFind($results = array(), $primary = false) {
		foreach ($results as $key => $val) {
			foreach ($this->phone_fields as $field) {
				if (isset($val[$this->alias][$field])) {
					$results[$key][$this->alias][$field] = format_phone($val[$this->alias][$field], false, false);
				} else if ( ! $primary && isset($val[$field])) {
					$results[$key][$field] = format_phone($val[$field], false, false);
				}
			}
		}
		
		return $results;
	}
	
	/**
	 * read() replacement method which uses contain() if available
	 *
	 * @param string $contain list or array of models to be contained
	 * @return mixed results of Model::find() method
	 * @author cornellcampbell
	 */
	public function fetch($contain = NULL) {
		if ($contain === NULL || ! is_array($contain)) {
			$contain = array();
			for ($i=0; $i < func_num_args(); $i++) { 
				$contain[] = func_get_arg($i);
			}
		}
				
		return $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $this->{$this->primaryKey},
			),
			'contain' => $contain,
			'recursive' => -1,
		));
	}
	
	/**
	 * Save a json object of the deleted item (better safe than sorry)
	 *
	 * @param string $cascade 
	 * @return void
	 * @author cornellcampbell
	 */
	public function beforeDelete($cascade = true) {
		// Save the initial recursive level
		$_recursive = $this->recursive;
		
		// Read data and related data from model
		$this->recursive = 1;
		if ($cascade !== TRUE) {
			$this->recursive = 0;
		}
		$data = $this->read();
		
		// Set recursive back to what it was
		$this->recursive = $_recursive;
		
		// Import String utility and DeletedItem model
		App::uses('String', 'Utility');
		
		// Save to DeletedItem model
	    $this->DeletedItem = ClassRegistry::init( 'DeletedItem' );
		$this->DeletedItem->create();
		$this->DeletedItem->save(array(
			'id' => String::uuid(),
			'user_id' => AuthComponent::user('group_id'),
			'model' => $this->name,
			'object' => json_encode($data),
			'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL,
		));
		
		// Always return true, so delete does not fail
		return true;
	}
	
}
