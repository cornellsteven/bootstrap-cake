<?php

$modalId = isset($modalId) ? $modalId : 'Modal';
$button = isset($button) ? $button : 'Submit';

?>
<div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-cross"></i></button>
                <h4 class="modal-title"><?php echo $title; ?></h4>
            </div>
            <div class="modal-body">
                <?php if (isset($content)): ?>
                    <?php echo $content; ?>
                <?php endif ?>
                <?php if (isset($element)): ?>
                    <?php echo $this->element($element); ?>
                <?php endif ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"><?php echo $button; ?></button>
            </div>
        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->