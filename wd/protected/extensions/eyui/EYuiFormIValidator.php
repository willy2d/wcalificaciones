<?php
interface EYuiFormIValidator {

	/*
		reads the primary id from model
	*/
	public function eyuiform_getPrimaryId();

	/**
		must validate the passed field.
		
		@returns:
			array
			array fields required: 'result'=>boolean, 'message'=>description
	*/
	public function eyuiform_validateField($formId,$fieldId,$fullFieldName,$fieldValue,$oldValue);
	
}