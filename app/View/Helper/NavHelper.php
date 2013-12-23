<?php

App::uses('AppHelper', 'View/Helper');

class NavHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	public function addItem($controller, $icon = NULL, $display = NULL, $title = NULL, $action = 'index') {
		$icon = isset($icon) ? '<i class="icon-' . $icon . '"></i>' : '';
		$humanized = Inflector::humanize($controller);
		$class = "$controller-$action";
		$display = isset($display) ? $display : $humanized;
		$title = isset($title) ? $title : "Manage $humanized";
		
		$item  = '<li class="' . $class . '">' . "\n\t";
		$item .= $this->Html->link(sprintf('%s<span>%s</span>', $icon, $display), array('controller' => $controller, 'action' => $action), array('escape' => false, 'title' => $title)) . "\n";
		$item .= "</li>\n";
	
		return $item;
	}
	
}

?>