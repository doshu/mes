<div class="text-center">
	<ul class="pagination pagination-small pagination-xs">
		<li>
			<?php echo $this->Paginator->prev('< ' . __('precedente'), array(), null, array('class' => 'prev disabled')); ?>
		</li>
		<?php
			echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'tag' => 'li'));
		?>
		<li>
			<?php echo $this->Paginator->next(__('successivo') . ' >', array(), null, array('class' => 'next disabled')); ?>
		</li>
	</ul>
</div>
