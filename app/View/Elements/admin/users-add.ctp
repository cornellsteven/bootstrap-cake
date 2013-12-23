<div class="row">
	<div class="col-md-12">
		<?php echo $this->Form->input('group_id', array('default' => 1)); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?php echo $this->Form->input('first_name'); ?>
	</div>
	<div class="col-md-6">
		<?php echo $this->Form->input('last_name'); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php echo $this->Form->input('username'); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?php if ($this->request->params['action'] == 'admin_edit'): ?>
			<?php echo $this->Form->input('password', array('placeholder' => 'Leave blank for old password')); ?>
		<?php else: ?>
			<?php echo $this->Form->input('password'); ?>
		<?php endif ?>
	</div>
	<div class="col-md-6">
		<?php echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Confirm Password')); ?>
	</div>
</div>