<div class="clearfix">
	<label><?php echo $label; ?></label>
	<div class="input">
		<ul class="inputs-list">
		<?php foreach ($this->get('options') as $key => $option): ?>
			<li class="checkbox">
				<label>
					<input<?php echo HTML::attributes($this->get_option_attr('checkbox', $option, $key)); ?> />
					
					<span><?php echo $this->option_label($option, $key); ?></span>
				</label>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php echo $message; ?>
	</div>
</div>