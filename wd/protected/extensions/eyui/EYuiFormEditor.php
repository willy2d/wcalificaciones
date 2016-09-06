<?php
/**
 * EYuiFormEditor class file. please view readme for details on input arguments.
 *
 * Present a form editor
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiFormEditor extends EYuiWidget implements EYuiActionRunnable {
	
	public $id;
	public $htmlOptions;
	public $action;
	public $model; // an implementation of EYuiFormEditorIStorage

	public function init(){
		parent::init();
	}
	
	public function run(){
	
		if($this->id == null)
			$this->id='eyuiformeditor0';
	
		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = 'eyuiformeditor';
	
		$action = $this->action != null ? $this->action : $this->defaultAction;
		$action .= "&classname=".__CLASS__;
	
		$loggerId = $this->id."_logger";
	
		$s = new CHttpSession();
		$s->open();
		$s[$this->id] = array('model'=>$this->model);
		$s->close();
	
		$htOpts = "";
		foreach($this->htmlOptions as $key=>$val)
			$htOpts .= " {$key}='{$val}'";
	
		$loadingImg = "<img src='{$this->getResource('loading.gif')}' class='loading'>";
	
		$editPageAndGroupDialogId = $this->id."_dialog_editpageandgroup";
		$editFieldDialogId = $this->id."_dialog_editfield";
	
		echo "
		<!-- EYuiFormEditor begins -->
		<div id='{$this->id}' {$htOpts}>
			<div class='tab'>
				<h1>".self::t('Pages')."{$loadingImg}</h1>
				<a title='".self::t('Create a New Page')."'>".self::t('New Page')."</a>
				<div class='items'></div>
			</div>
			<div class='tab'>
				<h1>".self::t('Groups')."{$loadingImg}</h1>
				<a title='".self::t('Create a New Group')."'>".self::t('New Group')."</a>
				<div class='items'></div>
			</div>
			<div class='tab'>
				<h1>".self::t('Fields')."{$loadingImg}</h1>
				<a title='".self::t('Create a New Field')."'>".self::t('New Field')."</a>
				<div class='items'></div>
			</div>
		</div>
		<div id='{$loggerId}'></div>
		{$this->dialogBoxEditPageAndGroup($editPageAndGroupDialogId)}
		{$this->dialogBoxEditField($editFieldDialogId)}
		<!-- EYuiFormEditor ends -->
";
	
		$options = CJavaScript::encode(array(
			'id'=>$this->id,
			'loggerId'=>$loggerId,
			'action'=>$action,
			'editPageAndGroupDialogId'=>$editPageAndGroupDialogId,
			'editFieldDialogId'=>$editFieldDialogId,
			
			'iconedit'=>$this->getResource('update.png'),
			'iconup'=>$this->getResource('up.png'),
			'icondw'=>$this->getResource('dw.png'),
			'icondelete'=>$this->getResource('delete.png'),
			'loading'=>$this->getResource('loading.gif'),
			
			'OkButtonText'=>self::t("Accept"),
			'CancelButtonText'=>self::t("Cancel"),
			'pleaseConfirmMessage'=>self::t('Are you sure to remove this item ?'),
			'PageRequiredMessage'=>self::t(
				"a page unique name for your form is required. only letters and digits."),
			'LabelRequiredMessage'=>self::t(
				"a label for this page is required."
			),
			'PositionRequiredMessage'=>self::t(
				"field position is required, must be a number."
			),
		));
	
		Yii::app()->getClientScript()->registerScript(
			"eyuiformeditor_script",
			"new EYuiFormEditor({$options});", 
			CClientScript::POS_READY
		);		
	}
	
	public function runAction($action,$data){
		$this->id = $data;
		$s = new CHttpSession();
		$s->open();
		$storedData = $s[$this->id];
		$s->close();
		$this->model = $storedData['model'];
		
		if($action == 'listitems'){
			$parent_id = null;
			if(isset($_GET['id']))
				$parent_id = $_GET['id'];
			return $this->model->eyuiformeditordb_listitems($_GET['item_type'],$parent_id);
		}
		if($action == 'newitem'){
			$parent_id = null;
			if(isset($_GET['id']))
				$parent_id = $_GET['id'];
			return $this->model->eyuiformeditordb_newitem($_GET['item_type'],self::t("New Item"),$parent_id);
		}
		if($action == 'deleteitem'){
			if($this->model->eyuiformeditordb_deleteitem($_GET['id']))
				return "OK";
		}
		if($action == 'updateitem'){
			$obj = CJSON::decode(trim(file_get_contents('php://input')));
			if($this->model->eyuiformeditordb_updateitem($obj)==true)
				return "OK";
		}
	}
	
	public function dialogBoxEditPageAndGroup($dialogId){
	
	$dialogTitle = self::t("Edit Properties");
	$position = self::t("Position");
	$internalName = self::t("Internal Name");
	$publicName = self::t("Public Name");
	$description = self::t("Description");
	
	return 
"
<div id='{$dialogId}' title='{$dialogTitle}' class='eyuiformeditor-dialog'>
	<form id='{$dialogId}_form'>
		<div class='row'>
			<div class='col'>
				<label>{$position}:</label>
				<input name='position' type='text' value='' maxlength=3 size=5>
			</div>
			<div class='col'>	
				<label>{$internalName}:</label>
				<input name='page' type='text' value='' maxlength=10 size=10>
			</div>
			<div class='col'>	
				<label>{$publicName}:</label>
				<input name='label' type='text' value='' maxlength=250 size=20>
			</div>
		</div>
		<div class='row'>
			<div class='col'>		
				<label>{$description}:</label>
				<input name='descr' type='text' value='' maxlength=250 size=30>
			</div>
		</div>
	</form>
</div>
";
	}

	public function dialogBoxEditField($dialogId){
	
	$dialogTitle = self::t("Edit Field Properties");
	$position = self::t("Position");
	$internalName = self::t("Internal Name");
	$publicName = self::t("Public Name");
	$description = self::t("Description");
	
	return 
"
<div id='{$dialogId}' title='{$dialogTitle}' class='eyuiformeditor-dialog'>
	<form id='{$dialogId}_form'>
	
	<div class='row'>
		<div class='col'>
			<label>{$position}:</label>
			<input name='position' type='text' value='' maxlength=3 size=5>
		</div>
		<div class='col'>
			<label>{$internalName}:</label>
			<input name='page' type='text' value='' maxlength=45 size=10>
		</div>
		<div class='col'>
			<label>{$publicName}:</label>
			<input name='label' type='text' value='' maxlength=250 size=20>
		</div>
		<div class='col'>
			<label>{$description}:</label>
			<input name='descr' type='text' value='' maxlength=250 size=45>
		</div>
	</div>
	
	
	<div class='row'>
		<div class='col'>
			<label>".self::t("Component").":</label>
			<select name='uicomponent'>
				<option value='textbox'>Text</option>
				<option value='checkbox'>Yes/No</option>
				<option value='textarea'>Text Area</option>
				<option value='combobox'>Combo</option>
				<option value='datebox'>Date</option>
				<!-<option value='listbox'>List</option>-->
			</select>
		</div>
		<div class='col'>
			<label>".self::t("Default Value").":</label>
			<input name='default' type='text' value='' maxlength=1024 size=45>
		</div>
	</div>
	
	
	<div class='row'>
		<div class='col'>
			<label>".self::t("Date Format").":</label>
			<input name='dateformat' type='text' value='' maxlength=15 size=15><br/>
			<select id='_dateformats'>
				<option value=''>-".self::t("select")."-</option>
				<option value='dd/mm/yy'>1/12/2012</option>
				<option value='yy/mm/dd'>2012/12/01</option>
				<option value='d/M/yy'>1/Dic/2012</option>
			</select>
			<script>
				$('#_dateformats').change(function(){ 
				$(this).parent().find('input').val($(this).val()); });
			</script>
		</div>
		<div class='col'>	
			<label>".self::t("Combo Items").":</label>
			<textarea name='options' rows=3 cols=15></textarea>
		</div>
		<div class='col'>	
			<kbd>".self::t("example").":
				<ul class='examplecombo help'>
					<li>1, Red</li>
					<li>2, Green</li>
					<li>3, Blue</li>
				</ul>
			</kbd>
		</div>
	</div>
	

	<div class='row'>
		<div class='col'>	
			<label>".self::t("Is Required").":</label>
			<input name='required' type='checkbox' value=''>
		</div>
		<div class='col'>	
			<label>".self::t("Is Required Message").":</label>
			<input name='requiredmessage' type='text' value='' maxlength=100 size=45>
		</div>
	</div>
	
	
	<div class='row'>
		<div class='col'>	
			<label>".self::t("Regular Expression").":</label>
			<textarea name='pattern' cols=30 rows=3></textarea>
			<br/>
			<label>".self::t("Regular Expression Message").":</label>
			<input name='patternmessage' type='text' value='' maxlength=100 size=45>
		</div>
		<div class='col'>	
			<label>".self::t("Available Expressions").":</label>
			<select id='_regexps'>
				<option value=''>-".self::t("select one")."-</option>
				<option value='/^([a-zA-Z0-9]+)$/'>".self::t("Alphanumeric")."</option>
				<option value='/^([a-zA-Z0-9 ]+)$/'>".self::t("Alphanumeric+space")."</option>
				<option value='".CHtml::encode("/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/")."'>Email</option>
				<option value='/^([0-9]+)$/'>".self::t("Digits Only")."</option>
			</select>
			<script>
				$('#_regexps').change(function(){ $(this).parent().parent().find('textarea').val($(this).val()); });
			</script>
		</div>
	</div>


	<div class='row'>
		<div class='col'>	
			<label>".self::t("Max Length").":</label>
			<input name='maxlength' type='text' value='' maxlength=3 size=5>
		</div>
		<div class='col'>	
			<label>".self::t("Field Size").":</label>
			<input name='size' type='text' value='' maxlength=3 size=5>
		</div>
		<div class='col'>	
			<label>".self::t("Css Class Name").":</label>
			<input name='class' type='text' value='' maxlength=45 size=20>
		</div>
		<div class='col'>	
			<label>".self::t("Css Styles").":</label>
			<textarea name='style' cols=30 rows=3></textarea>
		</div>
	</div>
	
	</form>
</div>
";
	}
	
}