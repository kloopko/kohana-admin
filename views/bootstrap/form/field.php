<?php
/*{{#ft-checkbox}}
	<input type="checkbox" name="{{name}}" id="{{id}}" value="{{value}}" />
{{/ft-checkbox}}

{{#ft-radio}}
	{{#options}}
	<input type="radio" name="{{name}}" value="{{value}}" />
	{{/options}}
{{/ft-radio}}

{{#ft-select}}
<select id="{{id}}" name="{{name}}">
	{{#options}}
	<option value="{{value}}">{{text}}</option>
	{{/options}}
</select>
{{/ft-select}}

{{#ft-text}}
<input type="text" name="{{name}}" id="{{id}}" value="{{value}}" />
{{/ft-text}}

{{#ft-textarea}}
<textarea id="{{id}}" name="{{name}}">{{value}}</textarea>
{{/ft-textarea}}
*/

		$attrs = $field->attrs();
		
		switch ($field->type())
		{
			case 'button' :
				
				echo Form::button($field->name(), $field->label(), $attrs);
				
			break;
			case 'file' :
			
				echo Form::file($field->name(), $field->attrs());
			
			break;
			case 'text' : 
			case 'email' :
			 
				echo Form::input($field->name(), $field->value(), $attrs + array(
					'type' => $field->type()
				));
				
			break;
			case 'select' : 
			
				echo Form::select($field->name(), $field->options(), $field->value(), $attrs);
			
			break;
			case 'radio' :
				
				$options = $field->options();
				
				foreach ($options as $option)
				{
					echo Form::radio($option);
				}
				
			break;
			case 'textarea' :
				
				echo Form::textarea($field->name(), $field->value(), $attrs);
				
			break;
		}
		
		