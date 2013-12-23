<?php
	
	// See /View/Helper/NavHelper for more details of addItem() method
	
	if ( ! $_USER) return;
	$controller = $this->name;	
	$action = $this->action;
	
?>
<nav>
	<ul id="leftnav">
		<?php echo $this->Nav->addItem('users', 'menu', 'Dashboard', 'View your Dashboard', 'dashboard'); ?>
		<?php echo $this->Nav->addItem('venues', 'map'); ?>
		<?php echo $this->Nav->addItem('events', 'calendar'); ?>
		<?php echo $this->Nav->addItem('users', 'user-group', 'User Accounts'); ?>
		<?php echo $this->Nav->addItem('settings', 'gear'); ?>
	</ul>
	
	<div class="logout">
		<?php echo $this->Html->link('<i class="icon-lock-open"></i> Log Out', array('controller' => 'users', 'action' => 'logout', ADMIN => false), array('escape' => false, 'title' => 'Log Out of Your Account')); ?>
	</div>
</nav>