<?php
/**
 * EYuiForm class file. please view readme for details on input arguments.
 *
 
	HTML LAYOUT:
 
	<div id='DIVID' class='eyuiform'>
		<h1>form title</h1>
		<div class='pages'>
		
			<div class='page'>
				<div class='groups'>
				
					<div class='group'>
						<h1>{groupTitle}</h1>
						<p class='info'>{groupDescr}</p>
							<ul class='fields'>
							
								<li class='{fieldClassName}'>
									<label>fieldLabel</label><br/>
									<span class='fieldspan'>{input component}</span>
										<span class='loader'></span>
									<div class='info'>
										{fieldDescription}
										<div class='error'></div>
									</div>
								</li>
								
								<!-- next field -->
								<li></li>
								
							</ul>
						<hr/>
					</div>
					
					<!-- next group -->
					<div class='group'>
						..fields..
					</div>
					
				</div>
			</div>
			
			<!-- next page.. -->
			<div class='page'>
				...etc...
			</div>
			
		</div>
		<br/>
		<hr/>
		<span id='DIVID_saving'></span>
		<div id='DIVID_logger'></div>
	</div> 
 
 
 * Present a form filled by users.
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiForm extends EYuiWidget implements EYuiActionRunnable {
	
	public $id;
	public $label;
	public $model;
	public $fielddefs;
	public $pages;
	public $submitButtonLabel='Submit Form';
	public $showErrorResume;
	public $submitErrorText='Cannot submit. Please fix errors.';
	public $showErrorsLabel='Show Errors';
	public $formSubmittedOkLabel='The form has been submitted.';
	public $jQueryUiEnabled=true;
	public $jQueryControl='tabs'; // tabs or accordion
	public $jQueryGroupControl=''; // use 'accordion' or leave blank
	public $descriptionLocation; // valid values: 'title'(default) or 'visible'
	
	public $cssClassname='eyuiform';
	public $debug;
	public $action=null;
	
	public function init(){
		//$this->setDebug();
		parent::init();
	}
	
	public function run(){
	
		$id=($this->id != null) ? $this->id : "ejuiform0";
		$tabId = $id."_tabs";
		$cssClassName = ($this->cssClassname != null) ? "class='".$this->cssClassname."'" : '';
		$loadingImgTag = CHtml::image($this->getResource('loading.gif'));
		
		// this->pages can be one of:
		//	a) an array of page entries
		//	b) an instance who implements EYuiFormEditorIStorage,
		//		if so, it will get it page entries from EYuiFormEditor database
		if(isset($this->pages) && (!is_array($this->pages)))
			if($impl = class_implements($this->pages))
				if(isset($impl['EYuiFormEditorIStorage']))
					$this->pages = $this->pages->eyuiformeditordb_getpages();
		
		// will convert each fielddefs into instances of EYuiFormFieldDef
		// and each pages into instances of: EYuiFormPage
		$this->parseModel(array('EYuiFormFieldDef'=>$this->fielddefs,'EYuiFormPage'=>$this->pages));
		
		// build page navigator
		$pageHeader="";
		if($this->jQueryControl == 'tabs'){
		$pageHeader = "<ul>";
		if(isset($this->pages))
		foreach($this->pages as $page)
			{
				$tit = $page->descr;
				$pageHeader .= 
"
	<li alt='{$page->id}' title='$tit'>
		<a href='#{$page->id}'>$page->label</a>
	</li>
";
			}
		$pageHeader .= "</ul>";
		}
		
		$groups_divs = array(); // used when jQueryGroupControl is accordion.
		
		// build each page layout
		$pageDiv="\n";
		if(isset($this->pages))
		foreach($this->pages as $page){
			if($this->jQueryControl == 'accordion')
				$pageDiv .= "\n\n<h3><a href='#'>{$page->label}</a></h3>\n";
			$pageDiv .= "<div class='page' id='{$page->id}'>\n";
				$h1PageTitle="";
				if($this->jQueryUiEnabled == false)
					$h1PageTitle = "<h1>{$page->label}</h1>";
				$pageDiv .= "<div class='info'>{$h1PageTitle}<p>{$page->descr}</p></div>";
				
				$groupsDivId = "{$page->id}_groups";
				$groups_divs[] = $groupsDivId;
				$pageDiv .= "<div class='groups' id='{$groupsDivId}'>";
				if(isset($page->groups))
				foreach($page->groups as $group){
					$fieldsDiv = "";
					foreach($group->fields as $f){
						if(!is_string($f)){
							// each input field here must have 'ALT' signature equal to: 'input'
							//
							$f->setValue($this->model->eyuiform_getFieldValue(
								$this->id,$f->id,$f->getName())
							);
							$fieldsDiv .= $this->preProcessField($f);
						}else{
							$fieldsDiv .= $f;
						}
					}
					$groupTitle = ($group->label != '') ? "<h1>{$group->label}</h1>" : "";
					$groupDescr = ($group->descr != '') ? "<p class='info'>{$group->descr}</p>" : "";
				
					$groupTitleForAccordionControl = "";
					$fix="";
					if($this->jQueryGroupControl == 'accordion'){
						$groupTitleForAccordionControl = "<h6>{$group->label}</h6>";
						$groupTitle='';
						$fix="style='min-height: 150px;'";
					}
				
					
				
					$pageDiv .= 
"
{$groupTitleForAccordionControl}
<div class='group' $fix>
	{$groupTitle}
	{$groupDescr}
	<ul class='fields'>
		{$fieldsDiv}
	</ul>
	<hr/>
</div>
";
				}
				$pageDiv .= "</div>\n";
			$pageDiv .= "</div>\n\n";
		}
		
		// prepares the action to be passed into js component
		$action = $this->action != null ? $this->action : $this->defaultAction;
		$action .= "&classname=".__CLASS__;
		$action .= "&formid=".$this->id;
		
		// creates a submit button
		$form_submit_button_id = $this->id."_submit";
		$submitButton = CHtml::button(self::t($this->submitButtonLabel)
			,array('id'=>$form_submit_button_id));
				
		// creates the layout
		$layout = 
"\n<!-- {$id} begins-->\n<div id='{$id}' {$cssClassName}>\n
		<h1>{$this->label}</h1>
		<div id='{$tabId}' class='pages'>
			{$pageHeader}
			{$pageDiv}
		</div>
		<br/>
		<hr/>
		{$submitButton}<span id='{$id}_saving'></span>
		<div id='{$id}_logger'></div>
</div>\n<!-- {$id} ends-->\n\n"
		;
		echo $layout;
		
		// save valuable information in session to be used later in future action calls:
		$s = new CHttpSession();
		$s->open();
		$s['eyuiform_'.$id] = array(
			'model'=>$this->model,'pages'=>$this->pages);	
		$s->close();
		// prepares options to be passed to js component
		$options = CJavaScript::encode(array(
				'formid'=>$id,
				'action'=>$action,
				'formsubmitbuttonid'=>$form_submit_button_id,
				'loadingUrl'=>$this->getResource('loading.gif'),
				'showErrorResume'=>$this->showErrorResume,
				'submitErrorText'=>self::t($this->submitErrorText),
				'showErrorsLabel'=>self::t($this->showErrorsLabel),
				'formSubmittedOkLabel'=>self::t($this->formSubmittedOkLabel),
		));
		// jquery-ui activation
		if($this->jQueryUiEnabled==true){
			
			$scriptForGroup="";
			if($this->jQueryGroupControl == 'accordion')
				foreach($groups_divs as $groupId)
					$scriptForGroup .= "$(function(){ $('#{$groupId}').{$this->jQueryGroupControl}(); });\n";
			
			Yii::app()->getClientScript()->registerScript(
				$tabId."_corescript",
				 "$(function(){ $('#{$tabId}').{$this->jQueryControl}(); });\n"
				."$(function() { $( '#{$form_submit_button_id}' ).button(); });\n"
				.$scriptForGroup."\n\n"
				, 
				CClientScript::POS_BEGIN
			);
		}
		// invokes the js component who will perform future action calls
		Yii::app()->getClientScript()->registerScript(
			$id,
			"new EYuiForm({$options});", 
			CClientScript::POS_LOAD
		);
	}
	
	// called before a field is renderized
	// 
	private function preProcessField($field){
		$field->descriptionLocation = $this->descriptionLocation;
		
		return $field;
	}
	
	private function findFieldByName($fieldName){
		foreach($this->pages as $page)
			foreach($page->groups as $group)
				foreach($group->fields as $field){
					if(!is_string($field))
						if($field->getName() === $fieldName)
							return $field;
				}
		return null;
	}
	
	private function validateField(EYuiFormField $field,$newFieldValue){
		// validate using fielddefs (fielddef.pattern and field.required)
		if(($newFieldValue == '') && ($field->required==true))
			return array(
				'result'=>false,
				'message'=>($field->requiredMessage != '' ? $field->requiredMessage 
					: self::t('this field is required').': '.$field->label),
				'help'=>$field->getHelpMessage(),
				'fieldname'=>$field->getName(),
			);
		// regexp validation
		if($field->field->pattern != ''){
			Yii::log(__METHOD__."\n field: {$field->id}\noriginal provided pattern:\n".$field->field->pattern,"info");
			$securePattern = "/".trim(trim($field->field->pattern),'/')."/";
			Yii::log(__METHOD__."\n field: {$field->id}\nsecure pattern:\n".$securePattern,"info");
			if(!preg_match($securePattern,$newFieldValue))
				if($newFieldValue != '')
					return array(
						'result'=>false,
						'message'=>($field->field->patternMessage != '' ? $field->field->patternMessage 
							: self::t('invalid supplied value for field:').': '.$field->label),
						'fieldname'=>$field->getName(),
						'help'=>$field->getHelpMessage(),
					);
		}
		// validates using model
		$validationResult = $this->model->eyuiform_validateField(
				$this->id,$field->id,$field->getName(),$newFieldValue,$field->getValue());
		if(is_array($validationResult)){
			if($validationResult['result'] == false){
				return array(
					'result'=>false,
					'message'=>$validationResult['message'].". [".$field->label."]",
					'fieldname'=>$field->getName(),
					'help'=>$field->getHelpMessage(),
				);
			}else{
				// validation is OK
				return array(
					'result'=>true,
					'message'=>'',
					'fieldname'=>$field->getName(),
					'help'=>'',
				);
			}
		}else
		throw new Exception("eyuiform_validateField must return an array");
	}
	
	/**
	* Method called whenever an EYui widget is invoked from an action int order to start a query.
	* invoked via jquery-ajax, by: eyuisearch.js
	* @see
	*	EYuiActionRunnable
	*	EYuiAction
	* @returns an array or any object. it will be converted in EYuiAction to a JSON representation.
	*/
	public function runAction($action,$data){
		$this->id = $_GET['formid'];
		// recovery session information saved before when widget was built
		$s = new CHttpSession();
		$s->open();
		$data = $s['eyuiform_'.$this->id]; //array('model'=>$this->model,'pages'=>$this->pages);	
		$s->close();
		$this->model = $data['model'];
		$this->pages = $data['pages'];

		if($action == 'submit'){
			// get the post. it will be an array formed in js component as: 
			// { id: input.id , val: input.value }
			$rawPost = trim(file_get_contents('php://input'));
			$post = CJSON::decode($rawPost);
			Yii::log(__METHOD__.".submit:\n".$rawPost,"info");
			// validates each provided field in post
			$errorFields = array();
			foreach($post as $fieldentry){
				if(($field = $this->findFieldByName(trim($fieldentry['id']))) != null){
					// query the model for current field value
					$field->setValue($this->model->eyuiform_getFieldValue(
						$this->id,$field->id,$field->getName()));
					// the new field value readed from post
					$result = $this->validateField($field,$fieldentry['val']);
					// array entry definition for each error passed to jquery:
					if($result['result']==false)
						$errorFields[] = array(
							'fieldname'=>$field->getName(),
							'message'=>$result['message'],
							'help'=>$result['help'],
							'id'=>$field->id,
							'label'=>$field->label,
							'page'=>$field->getPage()->label,
							'group'=>$field->getGroup()->label,
							);
				}
				else
					throw new Exception("the field provided in your form is not recognized by EYuiForm config.");
			}
			if(count($errorFields) > 0){
				return array(
					'result'=>false,
					'message'=>'', // it will show options.submitErrorText
					'errors'=>$errorFields,
				);
			}
			
			// all fields pass validation, now massive saving for each field:
			// notify begintrans
			$transaction_handler = $this->model->eyuiform_transaction('begin');
			
			foreach($post as $fieldentry){
				Yii::log(__METHOD__."\nsaving field:\n".$fieldentry['id'],"info");
				if(($field = $this->findFieldByName(trim($fieldentry['id']))) != null){
				$field->setValue(trim($fieldentry['val']));
				if(!$this->model->eyuiform_saveFieldValue(
					$this->id,$field->id,$field->getName(),$field->getValue())){
						// abort saving
						Yii::log(__METHOD__."\nsaving field ABORT.\n","info");
						$this->model->eyuiform_transaction('rollback',$transaction_handler);
						return array(
							'result'=>false,
							'message'=>'Error saving field',
							'fieldname'=>$field->getName(),
						);
						break;
					}
			}}
			Yii::log(__METHOD__."\nsaving field end.\n","info");
			// notify commit
			$this->model->eyuiform_transaction('commit',$transaction_handler);
			return array(
				'result'=>true,
				'message'=>'FORM SUBMITTED',
			);
		}
		else
		if($action == 'fieldchange'){
			// a specific field has a change and need to be validated in model
			//
			$post = CJSON::decode(trim(file_get_contents('php://input')));
			if(($field = $this->findFieldByName(trim($post['id']))) != null){
				// query the model for current field value
				$field->setValue($this->model->eyuiform_getFieldValue(
					$this->id,$field->id,$field->getName()));
				// the new field value readed from post
				return $this->validateField($field,trim($post['val']));
			}
			else
			throw new Exception("the field provided in your form is not recognized by EYuiForm config.");
		}
	}
	
	/**
		it will convert every fielddef into instances of EYuiFormFieldDef
		and every page into instances of EYuiFormPage
		
		as result:  
			$this->fielddefs will be an array of EYuiFormFieldDef instances.
			$this->pages will be an array of EYuiFormPage instances.
	*/
	private function parseModel($entryDefs) {
		foreach($entryDefs as $className=>$_array){
			if(isset($_array)){
				$models = array();
				foreach($_array as $_id=>$attrs){
					$_model = new $className;
					$_model->id = $_id;
					$_model->setAttributes($attrs);
					$models[] = $_model;
				}
				if($className == 'EYuiFormFieldDef')
					$this->fielddefs = $models;
				if($className == 'EYuiFormPage')
					$this->pages = $models;
			}
		}
		if(isset($this->pages)){
		foreach($this->pages as $page){
			$models = array();
			if(isset($page->groups))
			foreach($page->groups as $_id=>$attrs){
				$_model = new EYuiFormGroup;
				$_model->id = $_id;
				$_model->parent = $page;
				$_model->setAttributes($attrs);
				$models[] = $_model;
			}
			$page->groups = $models;
		}
		foreach($this->pages as $page)
			if(isset($page->groups))
			foreach($page->groups as $group)
				{
					$models = array();
					foreach($group->fields as $_id=>$attrs){
						if(!strstr($_id,'separator')){
							$_model = new EYuiFormField;
							$_model->id = $_id;
							$_model->parent = $group;
							$_model->setAttributes($attrs);
							$models[] = $_model;
						}else{	
							$models[] = $attrs;
						}
					}
					$group->fields = $models;
				}
		foreach($this->pages as $page)
			if(isset($page->groups))
			foreach($page->groups as $group)
				foreach($group->fields as $field) {
						if(isset($this->fielddefs)){
							foreach($this->fielddefs as $fd){
								if(!is_string($field) && ($fd->id == $field->field)){
									// link every declared field with a fielddef
									$field->field = $fd;
									// now every field must be in passed model
									$field->modelName = get_class($this->model);
									break;
								}
							}
						}else{
							// no provided fieldset, maybe an array containing object definition:
							// {"uicomponent":"textarea","pattern":"4","patternMessage":"5","options":"2"}
							if(is_array($field->field)){
								$fd = new EYuiFormFieldDef();
								$fd->setAttributes($field->field);
								$field->field = $fd;
							}
						}
					}
		}
	}
	
}