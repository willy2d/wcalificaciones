<?php
/**
 * EYuiFormDataExport helper class to perform data export from stored form values.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiFormDataExport {

	private $_formStructure;
	private $_formPages;
	private $_fileNames;
	private $_basePath;
	private $_separator;
	
	private static function instanceErrorMessage(){
		return self::t("Sorry, the provided instance does not implements the required interface: ");
	}
	
	/**
		@params $formStructure	instance who implements an EYuiFormEditorIStorage interface
			example:
				EYuiFormEditorDb::model('any_model_id','form1')
				
		returns:
			a new instance of EYuiFormDataExport 
			or it throws an exception if formStructure do not implements EYuiFormEditorIStorage
	*/
	public static function newModel($formStructure){
	
		// $formStructure must be an instance who implements: EYuiFormEditorIStorage
		//
		$_interfaceName='EYuiFormEditorIStorage';
		if($_impl = class_implements($formStructure))
			if(!isset($_impl[$_interfaceName]))
				throw new Exception(self::instanceErrorMessage().$_interfaceName);
				
		$inst = new EYuiFormDataExport();
		$inst->_fileNames = array();
		$inst->_formStructure = $formStructure;
		$inst->_formPages = $formStructure->eyuiformeditordb_getpages();
		return $inst;
	}

	public function prepareFiles($basePath,$prefix="",$boolUseLabel=true,$separator=';'){
		$this->_basePath = rtrim(rtrim($basePath,"/"),"\\")."/";
		$this->_separator = $separator;
		foreach($this->_formPages as $pageKey=>$page){
			// page fields: 'label','descr','groups'
			$label = ($boolUseLabel==true) ? $page['label'] : $pageKey;
			$fname = $prefix.$label.".csv";
			$this->_fileNames[$pageKey] = $fname;
			$path = $this->decoder($this->_basePath.$fname);
			@unlink($path);
			$f = fopen($path,"a");
			if($f != null){
				//write CSV column headers - fieldid
				$sep='';
				fprintf($f,"ID");$sep=$separator;
				foreach($page['groups'] as $group){
					foreach($group['fields'] as $item_id=>$field){
						fprintf($f,$sep.$item_id);
						$sep=$separator;
					}
				}
				fprintf($f,"\n");
				//write CSV column headers - labels
				$sep='';
				fprintf($f,"ID");$sep=$separator;
				foreach($page['groups'] as $group){
					foreach($group['fields'] as $item_id=>$field){
						fprintf($f,$sep.$this->processvalue($field['label']));
						$sep=$separator;
					}
				}
				fprintf($f,"\n");
				fclose($f);
			}
		}
	}
	
	/**
		insert a new object into this data export
		
		@params:
			$object an instance who implements: EYuiFormIStorage and EYuiFormIValidator
			example:
				EYuiFormDb::newModel($employee)
			
		returns:
			void or thowns an exception if $object is not an implementation of EYuiFormIStorage or EYuiFormIValidator
	*/
	public function insertModel($object,$formid) {
		
		if($_impl = class_implements($object)){
			if(!isset($_impl['EYuiFormIStorage']))
				throw new Exception(self::instanceErrorMessage().$_interfaceName);
			if(!isset($_impl['EYuiFormIValidator']))
				throw new Exception(self::instanceErrorMessage().$_interfaceName);
			
		}
		
		//readed using: EYuiFormIValidator
		$primaryId = $object->eyuiform_getPrimaryId();
		
		foreach($this->_formPages as $pageKey=>$page){
			$fname = $this->_fileNames[$pageKey];
			$path = $this->decoder($this->_basePath.$fname);
			$f = fopen($path,"a");
			// page fields: 'label','descr','groups'
			
			$sep="";
			fprintf($f,$primaryId);$sep = $this->_separator;
			
			foreach($page['groups'] as $groupKey=>$group){
				foreach($group['fields'] as $item_id=>$field){
					$full_field_name = $pageKey."_".$groupKey."_".$item_id;
					$field_value = $this->processvalue($object->eyuiform_getFieldValue($formid,$item_id,$full_field_name));
					fprintf($f,$sep.$field_value);
					$sep = $this->_separator;
				}
			}
			fprintf($f,"\n");
			fclose($f);
		}
	}
	
	
	public function getFiles(){
		return $this->_fileNames;
	}

	private function processvalue($v){
		$t='';
		for($i=0;$i<strlen($v);$i++)
			if($v[$i]!=$this->_separator){
				$t .= $v[$i];
			}else{
				$t .= '_';
			}
		return $this->decoder($t);
	}

	private function decoder($v)
	{
		return utf8_decode($v);
	}
}