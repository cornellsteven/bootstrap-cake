<div class="row">
	<div class="col-md-12">
		
		<?php if ($this->Paginator->hasPage(null, 2)): ?>
			<div class="pagination-holder clearfix">
				<ul class="pagination">
					<?php
						echo $this->Paginator->prev('Prev', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'prev disabled'));
						echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'first' => '1', 'currentTag' => 'a'));
						echo $this->Paginator->next('Next', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'next disabled'));
					?>
				</ul>
				<?php if ( ! isset($text) || $text == true): ?>
					<span class="text"><?php echo $this->Paginator->counter(array('format' => 'Showing items <strong>{:start}</strong>-<strong>{:end}</strong> of <strong>{:count}</strong>, Page {:page} of {:pages}')) ?></span>
				<?php endif ?>
			</div>
		<?php endif; ?>
		
	</div>
</div>