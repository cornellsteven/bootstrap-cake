<?php

$edit = isset($this->request->data['User']['id']);

?>
<div class="page-header">
	<?php if ($this->request->params['action'] == 'admin_add'): ?>
		<h2>New User</h2>
	<?php else: ?>
		<h2>Editing User: <?php echo $this->request->data['User']['name']; ?></h2>
	<?php endif ?>
</div>
<?php echo $this->Form->create('User'); ?>
	<div class="form-fixed">
		<?php if ($edit): ?>
			<?php echo $this->Form->hidden('id'); ?>
		<?php endif ?>
		<?php echo $this->element('admin/users-add'); ?>
	</div><!-- /form-fixed -->
	<div class="form-actions">
		<?php echo $this->Form->submit('Save Changes', array('class' => 'btn btn-primary', 'div' => false)) ?>
		&nbsp;
		<?php echo $this->Form->button('Cancel', array('name' => 'cancel', 'class' => 'btn btn-default', 'div' => false)) ?>
	</div><!-- /form-actions -->
<?php echo $this->Form->end(); ?>