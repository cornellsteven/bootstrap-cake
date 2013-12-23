<?php

$ajax = $this->request->is('ajax');
$edit = isset($this->request->data['User']['id']);

if ($edit) {
	$this->set('page_header_for_layout', 'Editing User: ' . $this->request->data['User']['name']);
} else {
	$this->set('page_header_for_layout', 'New User');
}

?>
<div class="form-fixed">
	<?php echo $this->Form->create('User'); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Create a New User</h3>
			</div><!-- /panel-heading -->
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						
						<?php if ($edit): ?>
							<?php echo $this->Form->hidden('id'); ?>
						<?php endif ?>
						<?php echo $this->element('admin/users-add'); ?>
						
					</div>
				</div><!-- /row -->
			</div><!-- /panel-body -->
			<div class="panel-footer">
				<?php echo $this->Form->submit('Save Changes', array('class' => 'btn btn-primary', 'div' => false)) ?>
				&nbsp;
				<?php echo $this->Form->button('Cancel', array('name' => 'cancel', 'class' => 'btn btn-default', 'div' => false)) ?>
			</div><!-- /panel-footer -->
		</div><!-- /panel -->
	<?php echo $this->Form->end(); ?>
</div><!-- /form-fixed -->