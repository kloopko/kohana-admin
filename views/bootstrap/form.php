<?php echo Form::open($form->action(), $form->attrs()) ?>
	
	<?php 
		foreach($form->fields() as $field) : 
		
			if ($field->type() !== 'hidden') : 		
	?>	
			<div class="clearfix <?php echo $field->error() ? 'error' : ''; ?>">
				<?php echo Form::label($field->name(), $field->label()) ?>
				<div class="input">
					<?php echo $field; ?>
					
					<?php if ($field->error()) : ?>
					<span class="help-inline"><?php echo HTML::chars($field->error()) ?></span>
					<?php endif; ?>
				</div>
			</div>	
	<?php 
			else :
			
				echo Form::hidden($field->name(), $field->value(), $field->attrs());
		
			endif;
			
		endforeach; 
	?>
	
	<div class="actions">
		<?php echo $form->submit() ?>
	</div>
	
<?php echo Form::close() ?>