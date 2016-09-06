<?php
/**
	EYuiFormDb
	
	implements a default functionality for value storage on long forms.
	
	please use with: 	
		eyuiformdb-mysql-schema.sql
		eyuiformdb-postgre-schema.sql
	
	and instance for this class could be passed directly to EYuiForm.model,
	
	as an example: (without a validator)
	
		$this->widget('ext.EYui.EYuiForm',array(
			'id'=>'form1',
			'label'=>'Form Title',
			'model'=>EYuiFormDb::newModel(Yii::app()->user->id),
			..
			..
		);
		
	using an external value validator:

		$this->widget('ext.EYui.EYuiForm',array(
			'id'=>'form1',
			'label'=>'Form Title',
			'model'=>EYuiFormDb::newModel(Yii::app()->user->id,"Customer"),
			..
			..
		);
		your validator class, in this case: "Customer.php" must implements:
			EYuiFormIValidator in order to validate each field
*/
class EYuiFormDb
	implements EYuiFormIStorage, EYuiFormIValidator
{
	private $_instanceid;
	private $_validatorInstance;
	public $tablename;

	/*
		way1:
			standard init
	*/
	public static function newModel($validatorModel){
		$model = new EYuiFormDb();
		
		// assume is a class instance ready for use
		$model->_validatorInstance = $validatorModel;
		if(is_string($validatorModel))
			$model->_validatorInstance = new $validatorModel;
		$model->_instanceid = $model->_validatorInstance->eyuiform_getPrimaryId();
		return $model;
	}
	
	public function getDb(){
		return Yii::app()->db;
	}
	
	public function getTablename(){
		return $this->tablename == null ? "eyuiformdb" : $this->tablename;
	}
	
	
	public function eyuiform_getPrimaryId(){
		return $this->_instanceid;
	}
	
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

	/**
		query the current field value
		
		must return: scalar.
	*/
	public function eyuiform_getFieldValue($formId,$fieldId,$fullFieldName){
		$row=$this->getDb()->createCommand()
			->select('field_value')
			->from($this->getTablename())
			->where('model_id=:modelid AND form_id=:formid AND field_name=:fieldname', array(
				':modelid'=>$this->_instanceid,
				':formid'=>$formId,
				':fieldname'=>$fullFieldName,
			))->queryRow();
		if($row!==false){
			return trim(CHtml::decode($row['field_value']));
		}
		else
		return "";
	}
	
	/**
		persist the field value
		
		must return: boolean.
	*/
	public function eyuiform_saveFieldValue($formId,$fieldId,$fullFieldName,$fieldValue){
		Yii::log(__METHOD__."\n{$formId},{$fieldId},{$fullFieldName},{$fieldValue}","info");
		$whereCondition = 'model_id=:modelid AND form_id=:formid AND field_name=:fieldname';
		$params = array(
				':modelid'=>$this->_instanceid,
				':formid'=>$formId,
				':fieldname'=>$fullFieldName
			);
		// try find field:
		$row=$this->getDb()->createCommand()
			->select('field_value')
			->from($this->getTablename())
			->where($whereCondition,$params)->queryRow();
		if($row!==false){
			// it exists, so update it.
			Yii::log(__METHOD__."\nupdating field: {$fullFieldName}","info");
			$ok = $this->getDb()->createCommand()
				->update($this->getTablename(), array('field_value'=>CHtml::encode($fieldValue)), 
				$whereCondition, $params);
			Yii::log(__METHOD__."\nupdate field result: {$ok}","info");
			return true;
		}
		else{
			// it doesnt exists, so insert it.
			Yii::log(__METHOD__."\ninsert field: {$fullFieldName}","info");
			return $this->getDb()->createCommand()
				->insert($this->getTablename(), array(
					'model_id'=>$this->_instanceid,
					'form_id'=>$formId,
					'field_name'=>$fullFieldName,
					'field_value'=>CHtml::encode($fieldValue)
				));
		}
		return false;
	}

	/**
		must validate the passed field.
		
		@returns:
			array
			array fields required: 'result'=>boolean, 'message'=>description
	*/
	public function eyuiform_validateField($formId,$fieldId,$fullFieldName,$fieldValue,$oldValue){
		if($this->_validatorInstance != null)
			return $this->_validatorInstance->eyuiform_validateField(
				$formId,$fieldId,$fullFieldName,$fieldValue,$oldValue);
		return array('result'=>true,'message'=>'');
	}
	
	/**
		helper function.
		
		query the current field value for a given instance in a specific form.
	*/
	public static function getFieldValue($modelInstance, $formId, $fullFieldName){
		$inst = self::newModel($modelInstance);
		return $inst->eyuiform_getFieldValue($formId,'',$fullFieldName);
	}
}