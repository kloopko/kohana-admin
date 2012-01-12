<?php echo Form::open($form->action(), $form->attrs()) ?>
	
	<?php 
		foreach($form->fields() as $field) : 
		
			if ($field->type() !== 'hidden') : 		
	?>	
			<div class="clearfix">
				<?php echo Form::label($field->name(), $field->label()) ?>
				<div class="input">
					<?php echo $field; ?>
				</div>
			</div>	
	<?php 
			else :
			
				echo Form::hidden($field->name(), $field->value(), $field->attrs());
		
			endif;
			
		endforeach; 
	?>
	
	<div class="actions">
		<button class="btn large primary" value="1" type="submit">Submit</button>
	</div>
	
<?php echo Form::close() ?>