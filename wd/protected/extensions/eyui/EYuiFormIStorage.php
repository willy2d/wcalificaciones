<?php
interface EYuiFormIStorage {

	/**
		a transaction event called when ever is needed.
		
		called when:
			a massive eyuiform_saveFieldValue is performed
			
		@transStatus:
			every time eyuiform_transaction is called it will be one of:
			"begin","commit" or "rollback"
			so, in conjunction with transId you can handle transactions in your database.
		@transaction_handler:
			is a handler returned when calling this method with transStatus 'begin' value
			it will be passed when transStatus is: 'commit' or 'rollback'
		
		returns:
			when $transStatus is 'begin' you must return a new transaction_handler
			
		you can implement this code:
		
		public function eyuiform_transaction($transStatus,$transaction_handler=null){
			if($transStatus == 'begin'){
				return $this->getDb()->beginTransaction();
			}else{
				if($transStatus == 'commit')
					$transaction_handler->commit();
				if($transStatus == 'rollback')
					$transaction_handler->rollback();
			}
		}
	*/
	public function eyuiform_transaction($transStatus,$transaction_handler=null);

	/**
		query the current field value
		
		must return: scalar.
	*/
	public function eyuiform_getFieldValue($formId,$fieldId,$fullFieldName);
	
	/**
		persist the field value
		
		must return: boolean.
	*/
	public function eyuiform_saveFieldValue($formId,$fieldId,$fullFieldName,$fieldValue);
}