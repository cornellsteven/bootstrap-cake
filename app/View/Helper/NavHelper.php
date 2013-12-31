<?php

App::uses('AppHelper', 'View/Helper');

class NavHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	private $_controller;
	private $_action;
	
	public function addItem($controller, $icon = NULL, $display = NULL, $title = NULL, $action = 'index') {
		$icon = isset($icon) ? '<i class="icon-' . $icon . '"></i>' : '';
		$humanized = Inflector::humanize($controller);
		$class = "$controller-$action";
		$display = isset($display) ? $display : $humanized;
		$title = isset($title) ? $title : "Manage $humanized";
		
		// Check for active
		if ("{$this->_controller}-{$this->_action}" == $class) {
			$class = "$class active";
		}
		
		$item  = '<li class="' . $class . '">' . "\n\t";
		$item .= $this->Html->link(sprintf('%s<span>%s</span>', $icon, $display), array('controller' => $controller, 'action' => $action), array('escape' => false, 'title' => $title)) . "\n";
		$item .= "</li>\n";
	
		return $item;
	}
	
	public function brand($html) {
		return '<li class="brand">' . $html . '</li>' . "\n";
	}
	
	public function setController($controller) {
		$this->_controller = strtolower(Inflector::humanize($controller));
	}
	
	public function setAction($action) {
		$this->_action = str_replace('admin_', '', $action);
	}
	
}

?>