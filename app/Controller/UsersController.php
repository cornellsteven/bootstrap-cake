<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {
	
	public $uses = array(
		'User',
		'Group',
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		// $this->Auth->allow('admin_add');
	}
	
	
	
	
	
	
	
	
	
	###################################################################
	## Public (front-facing) methods
	###################################################################
	
	public function login() {
		if ($this->request->is('ajax')) {
			if ($this->Auth->login()) {
				$this->redirect(array('action' => 'loggedin'));
			} else {
				echo 'error';
			}
			exit();
		}
		
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				
				// Log
				// $this->logger();
				
				// Redirect
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->error('Your username or password was incorrect.');
			}
		} else {
			if ($this->Session->read('Auth.User')) {
				$this->redirect($this->Auth->redirect());
			}
		}
		
		$this->setTitle('Log In');
	}
	
	public function logout() {
		// Log
		// $this->logger();
	    
		// Redirect
		$this->redirect($this->Auth->logout());
	}
	
	
	
	
	
	
	
	
	
	###################################################################
	## Admin methods
	###################################################################
	
	public function admin_index($group = 'all') {
		$groups = $this->Group->listGroups(array(
			'fields' => array('slug', 'id'),
			'order' => array('level' => 'desc'),
		));
		
		if ($group !== 'all' && ! array_key_exists($group, $groups)) {
			$group = key($groups);
		}
		
		$conditions = array();
		if ($group !== 'all') {
			$conditions['User.group_id'] = $groups[$group];
		}
		
		if ($this->request->is('get') && isset($this->request->query['query'])) {
			$this->request->query['query'] = trim($this->request->query['query']);
			if ( ! empty($this->request->query['query'])) {
				$this->redirect(array('action' => 'index', 'query' => $this->request->query['query']));
			} else {
				$this->redirect(array('action' => 'index'));
			}
		}
		
		if (isset($this->request->params['named']['query'])) {
			
			// Search ANY group
			unset($conditions['User.group_id']);
			
			$query_phone = preg_replace('/\D/', '', $this->request->params['named']['query']);
			$query = '%' . str_replace('*', '%', $this->request->params['named']['query']) . '%';
			$conditions['OR'] = array(
				'User.name LIKE' => $query,
				'User.username LIKE' => $query,
			);
			
			// Phone number search?
			if (strlen($query_phone)) {
				$conditions['OR']['User.phone LIKE'] = $query_phone;
			}
			
			$this->request->data['User']['query'] = $this->request->params['named']['query'];
		}
		
		$params = $this->User->getPublicUsers($conditions, true);
		$params['limit'] = 50;
		$params['contain'] = array('Group');
		$this->paginate = $params;
		
		$users = $this->paginate();
		
		if ( ! $users) {
			$users = array();
		} else {
			foreach ($users as $key => $value) {
				if (empty($value['User']['name']) || strlen($value['User']['name']) < 2) {
					$users[$key]['User']['name'] = $value['User']['username'];
				}
			}
		}
		
		$groups = $this->Group->listGroups();
		$group_tab_names = $this->Group->listGroups(array('fields' => array('slug', 'name'), 'order' => array('level' => 'desc')));
		
		$this->set(compact('users', 'groups', 'group', 'group_tab_names'));
		$this->setTitle('Manage Users');
	}
	
	public function admin_view($id = NULL) {
		$this->User->id = $id;
		if ( ! $this->User->exists()) {
			$this->error('That user does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		$user = $this->User->fetch(array(
			'Listing',
			'WatchedListing' => array(
				'Listing',
			),
		));
		
		$logs = $this->User->Log->recent($id);
		
		$this->set(compact('user', 'logs'));
		$this->setTitle('User Overview: ' . $user['User']['name']);
	}
	
	public function admin_add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data;
			
			$data['User']['email'] = $data['User']['username'];
			$data['User']['group_id'] = ( ! isset($data['User']['group_id']) || $data['User']['group_id'] < 1 ) ? 3 : $data['User']['group_id'];
			
			$this->User->create();
			if ($this->User->save($data)) {
				$this->success('User successfully created.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->error('The User could not be created. Please try again.');
			}
		}
		
		$groups = $this->Group->listGroups();
		$this->set('groups', $groups);
		
		$this->setTitle('New User');
	}
	
	public function admin_edit($id = NULL) {
		$this->User->id = $id;
		if ( ! $this->User->exists()) {
			$this->error('That user does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->_removeValidation(array('phone', 'street_address1', 'city', 'zipcode', 'company'));
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ( empty($this->request->data['User']['password']) ) {
				unset($this->request->data['User']['password'], $this->request->data['User']['password_confirm']);
			}
			
			if ($this->User->save($this->request->data)) {
				$this->success('User successfully saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->error('The user could not be saved. Please try again.');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
		
		$groups = $this->Group->listGroups();
		$this->set('groups', $groups);
		$this->set('states_list', $this->states_list);
		$this->set('countries_list', $this->countries_list);
		
		$this->setTitle('Edit User');
		$this->view = 'admin_add';
	}
	
	public function admin_activate($id = NULL) {
		$this->User->id = $id;
		if ( ! $this->User->exists()) {
			$this->error('That user does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->User->saveField('active', 1)) {
			$this->success('User successfully activated');
		} else {
			$this->error('Unable to activate the User');
		}

		$this->redirect($this->referer());
	}
	
	public function admin_deactivate($id = NULL) {
		$this->User->id = $id;
		if ( ! $this->User->exists()) {
			$this->error('That user does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->User->saveField('active', 0)) {
			$this->success('User successfully deactivated');
		} else {
			$this->error('Unable to deactivate the User');
		}

		$this->redirect($this->referer());
	}
	
	public function admin_delete($id = NULL) {
		if ( ! $this->request->is('post')) {
			// throw new MethodNotAllowedException();
		}
		
		$this->User->id = $id;
		if ( ! $this->User->exists()) {
			$this->error('That user does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->User->delete()) {
			$status = array(
				'error' => 0,
				'message' => 'User deleted successfully.',
			);
		} else {
			$status = array(
				'error' => 1,
				'message' => 'User could not be deleted. Please try again.',
			);
		}
		
		if ($this->request->is('ajax')) {
			$this->json($status);
		}
		
		if ( ! $status['error']) {
			$this->success($status['message']);
		} else {
			$this->error($status['message']);
		}
		
		$this->redirect(array('action' => 'index'));
	}
	
	
	
	
	
	
	
	
	
	###################################################################
	## Utility methods
	###################################################################
	
	public function checker() {
		$foo = $this->Auth->user('id') !== NULL;
		echo $foo ? '1' : '0';
		exit();
	}
	
	private function _removeValidation($fields = NULL) {
		if ($fields === NULL || empty($fields)) {
			return;
		}
		
		if ( ! is_array($fields)) {
			$fields = array($fields);
		}
		
		foreach ($fields as $field) {
			$this->User->validator()->remove($field);
		}
	}
	
}