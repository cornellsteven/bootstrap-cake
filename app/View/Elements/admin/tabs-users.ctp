<ul class="nav nav-tabs nav-tabs-panel">
	<li<?php echo $group == 'all' ? ' class="active"' : ''; ?>><?php echo $this->Html->link('All Users', array('controller' => 'users', 'action' => 'index', 'all'), array('escape' => false)); ?></li>
<?php foreach ($group_tab_names as $key => $value): ?>
	<li<?php echo $group == $key ? ' class="active"' : ''; ?>><?php echo $this->Html->link($value, array('controller' => 'users', 'action' => 'index', $key), array('escape' => false)); ?></li>
<?php endforeach ?>
	<li class="action"><a data-toggle="modal" href="#AddUserModal"><i class="icon-plus"></i> New User</a></li>
</ul>