<?php

$action = isset($action) ? $action : 'add';
$model = isset($model) ? $model : Inflector::singularize($this->name);

if ( ! isset($title)) {
    $humanized = Inflector::humanize(Inflector::underscore($model));
    
    $title = $action == 'add' ? "Create $humanized" : ucwords($action) . ' ' . $humanized;
    if ( ! isset($button)) {
        $button = $title;
    }
}

$remote  = isset($remote)     ? $remote     : false;
$modalId = isset($modalId)     ? $modalId     : ucwords("{$action}{$model}Modal");
$element = isset($element)     ? $element     : 'admin/' . Inflector::tableize($model) . '-' . $action;
$button  = isset($button)     ? $button     : 'Submit';

if (isset($inline) && $inline === true) {
    $remote = true;
} else {
    $inline = false;
}

if ( ! isset($url)) {
    $url = array('controller' => Inflector::tableize($model), 'action' => $action);
}

?>

<?php if ($inline): ?>
    <div class="modal modal-form fade js-inline-add" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" data-js-inline-select="<?php echo "{$model}Select" ?>">
<?php else: ?>
    <div class="modal modal-form fade" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog">
<?php endif ?>
    <?php echo $this->Form->create($model, array('url' => $url, 'style' => 'margin-bottom: 0;')); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-cross"></i></button>
                    <h4 class="modal-title"><?php echo $title; ?></h4>
                </div>
                <div class="modal-body">
                    <?php if ($remote): ?>
                        &nbsp;
                    <?php else: ?>
                        <?php if (isset($content)): ?>
                            <?php echo $content; ?>
                        <?php endif ?>
                        <?php echo $this->element($element); ?>
                    <?php endif ?>
                </div>
                <div class="modal-footer">
                    <?php if ( ! $remote): ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <?php echo $this->Form->submit($button, array('class' => 'btn btn-primary', 'div' => false)) ?>
                    <?php endif ?>
                </div>
            </div><!-- /modal-content -->
        </div><!-- /modal-dialog -->
    <?php echo $this->Form->end(); ?>
</div><!-- /modal -->