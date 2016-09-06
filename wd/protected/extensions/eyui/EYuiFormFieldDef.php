<?php
class EYuiFormFieldDef extends EYuiFormModel {
	public $id;
	public $label;
	public $uicomponent;
	public $pattern;
	public $patternMessage;
	public $options;
	public $prompt; // for combobox. example: 'prompt'=>array(0=>'-please select-'), 
	public $defaultOption; // for options, default value selected
	public $htmlOptions;
	public $help; // extra extended information about this field shown when validation fails
}