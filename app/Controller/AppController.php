<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

class AppController extends Controller {
    
    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
                ADMIN => false,
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login',
                ADMIN => false,
            ),
            'loginRedirect' => LOGIN_REDIRECT,
            'authenticate' => array(
                'Form' => array(
                    'scope' => array('User.active' => 1),
                ),
            ),
        ),
        'AutoLogin' => array(
            'cookieName'     => '_brksd_persistent',
            'expires'         => '+ 1 month',
        ),
        'Cookie',
        'DebugKit.Toolbar',
        'Sanitizer',
        // 'ProcessImage',
        // 'RequestHandler',
    );
    
    public $helpers = array(
        'Session',
        'Html',
        'Time',
        'Text',
        'Form',
        'Number',
    );
    
    public $states_list = array(
        'AK' => 'AK', 'AL' => 'AL', 'AR' => 'AR', 'AZ' => 'AZ', 'CA' => 'CA', 'CO' => 'CO', 'CT' => 'CT',
        'DC' => 'DC', 'DE' => 'DE', 'FL' => 'FL', 'GA' => 'GA', 'HI' => 'HI', 'IA' => 'IA', 'ID' => 'ID', 
        'IL' => 'IL', 'IN' => 'IN', 'KS' => 'KS', 'KY' => 'KY', 'LA' => 'LA', 'MA' => 'MA', 'MD' => 'MD', 
        'ME' => 'ME', 'MI' => 'MI', 'MN' => 'MN', 'MO' => 'MO', 'MS' => 'MS', 'MT' => 'MT', 'NC' => 'NC', 
        'ND' => 'ND', 'NE' => 'NE', 'NH' => 'NH', 'NJ' => 'NJ', 'NM' => 'NM', 'NV' => 'NV', 'NY' => 'NY',
        'OH' => 'OH', 'OK' => 'OK', 'OR' => 'OR', 'PA' => 'PA', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 
        'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VA' => 'VA', 'VT' => 'VT', 'WA' => 'WA', 'WI' => 'WI', 
        'WV' => 'WV', 'WY' => 'WY',
    );
    
    public $states_long_list = array(
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
        'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District Of Columbia',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois',
        'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana',
        'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota',
        'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon',
        'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota',
        'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia',
        'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
    );
    
    public function beforeFilter() {
        // If "Cancel" button is pushed
        if (isset($this->request->data['cancel'])) {
            $this->redirect(array('action' => 'index', ADMIN => true));
        }
        
        $this->Auth->allow();
        
        // If this is an admin page, Deny all non-logged-in users and change layout to 'Admin'
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == ADMIN) {
            if (ADMIN !== 'admin') {
                $this->request->params['action'] = str_replace(ADMIN . '_', 'admin_', $this->request->params['action']);
                $this->view = str_replace(ADMIN . '_', 'admin_', $this->request->params['action']);
            }
            
            $this->Auth->deny();
            $this->layout = 'admin';
        }
        
        // Populate a variable with User details and make it available to views
        // Setup $_USER
        if (NULL === ($_USER = $this->Auth->user())) {
            $_USER = false;
        }
        $this->_user = $_USER;
        
        // Set a session ID (for the duration of the day)
        $this->_sessid = $_sessid = md5(sha1(Configure::read('Session.cookie') . date('Y-m-d')));
        
        $this->set('_USER', $this->_user);
        $this->set('_sessid', $_sessid);
        $this->set('states_list', $this->states_list);
        $this->set('states_long_list', $this->states_long_list);
    }
    
    /**
     * Runs if the AutoLoginComponent is invoked successfully
     *
     * @return void
     * @author cornellcampbell
     */
    public function _autoLogin() {
        if ($this->request->is('ajax')) {
            echo '1'; exit();
        } else {
            if (FALSE !== strstr($_SERVER['REQUEST_URI'], Router::url('/'))) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }
        }
    }
    
    /**
     * Runs if the AutoLoginComponent fails
     *
     * @return void
     * @author cornellcampbell
     */
    public function _autoLoginError() {
        if ($this->request->is('ajax')) {
            echo '0'; exit();
        }
    }
    
    public function denyAccess() {
        if ($this->Auth->user('group_id') != 1) {
            $this->redirect('/' . ADMIN);
        }
    }
    
    /**
     * Convenience method for setting title_for_layout
     *
     * @param string $title 
     * @return void
     * @author Cornell Campbell
     */
    public function setTitle($title) {
        $this->set('title_for_layout', $title);
    }
    
    /**
     * Sets a warning message at the top of the current layout
     *
     * @param string $message 
     * @return void
     * @author cornellcampbell
     */
    public function warn($message) {
        $this->set('warning_for_layout', $message);
    }
    
    /**
     * Covenience method for setting a "Success" flash
     *
     * @param string $message 
     * @param string $view 
     * @return void
     * @author Cornell Campbell
     */
    public function success($message, $view = 'success') {
        $this->_setFlash($message, $view);
    }
    
    /**
     * Convenience method for setting an "Error" flash
     *
     * @param string $message 
     * @param string $view 
     * @return void
     * @author Cornell Campbell
     */
    public function error($message, $view = 'error') {
        $this->_setFlash($message, $view);
    }
    
    /**
     * Convenience method for setting a "Warning" flash
     *
     * @param string $message 
     * @param string $view 
     * @return void
     * @author Cornell Campbell
     */
    public function warning($message, $view = 'warning') {
        $this->_setFlash($message, $view);
    }
    
    /**
     * Convenience method for setting an "Info" flash
     *
     * @param string $message 
     * @param string $view 
     * @return void
     * @author Cornell Campbell
     */
    public function info($message, $view = 'info') {
        $this->_setFlash($message, $view);
    }
    
    /**
     * Worker method to display various alerts (see success(), warn(), etc. above)
     *
     * @param string $message 
     * @param string $view 
     * @return void
     * @author cornellcampbell
     */
    public function _setFlash($message, $view = 'info') {
        $this->Session->setFlash($message, "alert-$view");
    }
    
    public function json($array = array()) {
        header('Content-type: application/json');
        print json_encode($array);
        exit;
    }
    
}
