<div class="pagination">
	<ul>

	<?php if ($previous_page !== FALSE): ?>
		<li class="prev">
			<a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev">&larr; <?php echo __('Previous') ?></a>
		</li>
	<?php else: ?>
		<li class="prev disabled">
			<a href="#">&larr; <?php echo __('Previous') ?></a>
		</li>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<li class="disabled">
				<a href="#"><?php echo $i ?></a>
			</li>
		<?php else: ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a>
			</li>
		<?php endif ?>

	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<li class="last">
			<a href="<?php echo HTML::chars($page->url($next_page)) ?>" rel="next"><?php echo __('Next') ?> &raquo;</a>
		</li>
	<?php else: ?>
		<li class="last disabled">
			<a href="#"><?php echo __('Next') ?> &raquo;</a>
		</li>
	<?php endif ?>
	
	</ul>
</div><!-- .pagination -->