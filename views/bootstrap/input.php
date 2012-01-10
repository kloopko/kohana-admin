<?php echo $open; ?>
	<label <?php if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>>
		<?php echo $label; ?>
	</label>
	<div class="input">
		<?php if ($this->editable() === TRUE): ?>
			<?php echo $this->add_class('xlarge')->html(); ?>
		<?php else: ?>
			<span><?php echo $this->val(); ?></span>
		<?php endif; ?>
		<?php echo $message; ?>
	</div>
<?php echo $close; ?>