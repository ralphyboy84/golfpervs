<?php
require_once ( "errorHandler.class.php" );

class quickForm extends errorHandler
{
	private $attributes;
	private $Name;
	private $Type;
	private $ButtonLabel;
	private $textDefault;
	private $defaultVal;
	private $className;
	private $data;
	
	function __construct ( $args = null )
	{
		if ( $args )
		{
			foreach ( $args as $key => $vals )
			{
				$this->$key = $vals;
			}
		}
		else
		{
			$this->errorFlag[] = "Error instantiating the class";
		}
		
		//these are the 2 properties that every form item must have
		if ( !$this->Type || !$this->Name )
		{
			die;
		}
	}
	
	function buildInput ()
	{
		$name = $this->Name;
		$type = $this->Type;
		
		if ( $type == "input" ) { $type = "text"; $append = "input"; } else { $append = $type; }
		
		$attribs = $this->returnAttributes ();
				
		return<<<EOHTML
<input type='$type' $attribs name='${name}_${append}' id='${name}_${append}' />
EOHTML;
	}
	
	
	function buildButton ()
	{
		$name        = $this->Name;
		$type 		 = $this->Type;
		$buttonLabel = $this->ButtonLabel;
		
		$attribs = $this->returnAttributes ();
		
		return<<<EOHTML
<button type='$type' $attribs name='${name}_${type}' id='${name}_${type}'>$buttonLabel</button>
EOHTML;
	}
	
	
	function buildTextArea ()
	{
		$name        = $this->Name;
		$type 		 = $this->Type;
		$default	 = $this->textDefault;
		$class		 = $this->className;
		
		$attribs = $this->returnAttributes ();
		
		return<<<EOHTML
<textarea name='${name}_${type}' $attribs id='${name}_${type}' class='$class'>$default</textarea>
EOHTML;
	}
	
	
	function buildSelectBox ()
	{
		$name        = $this->Name;
		$type 		 = $this->Type;
		$optionparam = $this->options;
		$default	 = $this->defaultVal;
		$attribs = $this->returnAttributes ();
		
		if ( !$optionparam )
		{
			return;
		}
		else
		{
			foreach ( $optionparam as $key => $vals )
			{
				if ( $key == $default ) { $options.= "<option value='$key' selected='selected'>$vals</option>"; }
				else 					{ $options.= "<option value='$key'>$vals</option>"; }
			}
		}
		
		if ($this->data) {
			foreach ($this->data as $key => $vals) {
				$dataVals[] = "data-$key = '$vals'";
			}
			$dataString = implode($dataVals);
		}
		
		return<<<EOHTML
<select name="${name}_${type}" $attribs id="${name}_${type}" $dataString class='monthEventSelect form-control'>
$options
</select>
EOHTML;
	}
	
	
	
	function returnAttributes ()
	{
		if ( $this->attributes )
		{
			foreach ( $this->attributes as $key => $vals )
			{
				$attribs.= " $key = '$vals' ";
			}
		}
		
		return $attribs;
	}

}

?>