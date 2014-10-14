<?php

App::uses('AppModel', 'Model');

class User extends AppModel {
    
    public $name = 'User';
    
    public $displayField = 'name';
    
    public $order = array(
        'name' => 'asc',
    );
    
    public $belongsTo = array(
        'Group'
    );
    
    public $validate = array(
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'You must enter your email address',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'You must enter a valid email address'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'The email address you entered is already in use'
            )
        ),
        'password' => array(
            'minlen' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters'
            ),
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'You must enter a password'
            ),
            'matches' => array(
                'rule' => array('matchesValidation', 'password_confirm'),
                'message' => 'Did not match'
            )
        ),
        'password_confirm' => array(
            'matches' => array(
                'rule' => array('matchesValidation', 'password'),
                'message' => 'Did not match'
            )
        ),
    );
    
    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['name'] = sprintf('CONCAT(`%s`.`first_name`, " ", `%s`.`last_name`)', $this->alias, $this->alias);
    }
    
    public function beforeSave($options = array()) {
        parent::beforeSave();
        
        // Encrypt the password
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        
        return true;
        
    }
    
    public function matchesValidation($fields, $matchDataKey) {
        
        // Sanity check this should be an array and a string
        if ( ! is_array($fields) || ! is_string($matchDataKey)) {
            return false;
        }
        
        // Verify the matchDataKey exists
        if ( ! isset($this->data[$this->name][$matchDataKey])) {
            return true;
        }
        
        // Get the value to compare
        $comparativeString = $this->data[$this->name][$matchDataKey];
        
        // Get the field to compare against
        $value = array_shift($fields);
        
        // Check for match
        if ($value != $comparativeString) {
            return false;
        }
        
        // If we get here then it passed
        return true;
        
    }
    
    /**
     * Returns all users excluding those in the group Super Admin
     * 
     * @return admin 
     */
    public function getPublicUsers($conditions = array(), $paginate = false) {
        $params['conditions'] = $conditions + array('User.group_id >' => 1);
        $params['contain'] = array('Group');
        
        if ($paginate) {
            return $params;
        } else {
            return $this->find('all', $params);
        }
        
    }
    
}