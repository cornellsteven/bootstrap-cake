<?php

App::uses('AppModel', 'Model');

class Group extends AppModel {
		
	public $name = 'Group';
	
	public $hasMany = array(
		'User'
	);
	
	public function listGroups() {
		return $this->find('list', array(
			'conditions' => array(
				'Group.id >=' => AuthComponent::user('group_id'),
			),
		));
	}
	
}