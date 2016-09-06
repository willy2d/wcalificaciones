<?php
class EYuiFormGroup  extends EYuiFormModel {
	const GROUPTYPE_ITEM = 'item';
	const GROUPTYPE_ROW = 'row';
	

	public $id;
	public $label;
	public $descr;
	public $fields;
	
	public $groupType;
	public $minItems;
	public $maxItems;
	
	public $parent;
}