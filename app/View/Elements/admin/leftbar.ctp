<?php
	
	// See /View/Helper/NavHelper for more details of addItem() method
	
	if ( ! $_USER) return;
	$controller = $this->name;
	$action = $this->action;
	$this->Nav->setController($controller);
	$this->Nav->setAction($action);
	
?>
<aside id="leftbar">
	<nav>
		<ul id="leftnav">
			<?php echo $this->Nav->addLink('/', 'arrow-thin-left', 'Return Home'); ?>
			<?php // echo $this->Nav->brand('Admin'); // Pass it the html for the "brand" li ?>
			<?php echo $this->Nav->addItem('users', 'menu', 'Dashboard', 'View your Dashboard', 'dashboard'); ?>
			<?php echo $this->Nav->addItem('users', 'user-group', 'User Accounts'); ?>
			<?php echo $this->Nav->addItem('settings', 'gear'); ?>
			<?php echo $this->Nav->addItem('users', 'lock-open', 'Log Out', NULL, 'logout') ?>
		</ul>
	</nav>
</aside>