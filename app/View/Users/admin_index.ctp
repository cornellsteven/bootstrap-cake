<?php if (isset($this->request->named['query'])): ?>
    <div class="alert alert-info">
        You are viewing results for your search of "<?php echo $this->request->params['named']['query']; ?>". 
        <?php echo $this->Html->link('Click here to see all your Users', array('action' => 'index')) ?>.
    </div>
<?php endif ?>

<?php echo $this->element('admin/tabs-users', array('groups' => $groups, 'group' => $group)); ?>
<table class="table table-index">
    <tr>
        <th><?php echo $this->Paginator->sort('name', 'User\'s Name'); ?></th>
        <th style="width: 30%;"><?php echo $this->Paginator->sort('group_id', 'Group'); ?></th>
        <th class="actions" colspan="2">Actions</th>
    </tr>
    <?php if ( ! $users): ?>
        <tr>
            <td colspan="3">
                <?php if (isset($this->request->data['User']['query'])): ?>
                    <p style="margin-bottom: 0;">Your search did not return any results.</p>
                <?php else: ?>
                    <p style="margin-bottom: 0;">There are no users in this system.</p>
                <?php endif ?>
            </td>
        </tr>
    <?php endif ?>
    <?php foreach ($users as $user) { ?>
        <tr class="removable">
            <td><?php echo $this->Html->link($user['User']['name'], array('action' => 'edit', $user['User']['id'])); ?></td>
            <td><?php echo $user['Group']['name']; ?></td>
            <td class="action">
                <?php echo $this->Html->link('<i class="icon-document-edit"></i>', array('action' => 'edit', $user['User']['id']), array('escape' => false)) ?>
            </td>
            <td class="action">
                <?php echo $this->Form->postLink('<i class="icon-trash"></i>', array('action' => 'delete', $user['User']['id']), array('escape' => false, 'class' => 'delete'), 'Are you sure you want to delete &ldquo;' . $this->Text->truncate($user['User']['name'], 50) . '&rdquo;'); ?>
            </td>
        </tr>
    <?php } ?>
</table>

<!-- Modals -->
<?php echo $this->element('admin/modal-form'); ?>