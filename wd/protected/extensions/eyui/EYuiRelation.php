<?php
/**
 * EYuiRelation class file.
 *
 * this widget handle associations between models in a visual way using ajax and jquery, your model is readed using interfaces.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiRelation extends EYuiWidget
	 implements EYuiActionRunnable 
{ 
	public $id='eyuirelation0';
	public $title;
	public $model;	// must implement EYuiRelationIMaster
	public $optionsClassName;
	public $relationClassName;
	
	public $onError;	// function(e){ alert(e.responseText); }
	public $htmlOptions;
	
	public function init(){
		//$this->setDebug();
		parent::init();
	}
	
	public function run(){
		
		$id=$this->id;
		$deleteImgUrl = $this->getResource('delete.png');
		$loadingImgTag = CHtml::image($this->getResource('loading.gif'),null
			,array('class'=>'loading','style'=>'display:none;'));

		$s = new CHttpSession();
		$s->open();
		$s[$id] = array(
			'optionsClassName'=>$this->optionsClassName,
			'relationClassName'=>$this->relationClassName,
			'model'=>$this->model,
		);
		$s->close();
		
		$action = $this->defaultAction;
		// this parameter is needed in EYuiAction to provide a reference for this class
		// who is listening for an action request redirected to this class via runAction method.
		$action .= "&classname=".__CLASS__;
		
		if($this->onError == null)
			$this->onError = 'function(e){ }';
		if(!($this->onError instanceof CJavaScriptExpression))
				$this->onError = new CJavaScriptExpression($this->onError);
		
		
		
		$options = CJavaScript::encode(
			array(
				'id'=>$id,
				'action'=>$action,
				'onError'=>$this->onError,
				'errorMsgPleaseSelectOption'=>self::t("must select an option"),
				'queryRemoveMessage'=>self::t("are you sure to remove this item ?"),
				'deleteImgUrl'=>$deleteImgUrl,
			)
		);
		
		$ioptions = new $this->optionsClassName;
		$irelation = new $this->relationClassName;
		
		$this->implementsInterface($this->model,'EYuiRelationIMaster');
		$this->implementsInterface($ioptions,'EYuiRelationIOptions');
		$this->implementsInterface($irelation,'EYuiRelationIRelation');
		
		// read options from a class referenced by optionsClassName using the required interface.
		//
		$availableOptions = $ioptions->eyuirelation_listData($this->id,$this->model->eyuirelation_getPrimaryId());
		$availableOptions = (count($availableOptions) > 0) ?
			(array(''=>self::t("--Please select--")) + $availableOptions) : array(''=>self::t("--empty--"));
		$select = CHtml::dropDownList($id.'_select','',$availableOptions);

		$selectLabel = isset($mapping['select']) ? ($mapping['select']) : self::t("Select");
		$_title = empty($this->title) ? "" : ("<span class='title'>".$this->title."</span>");
		
		// read current relation items from EYuiRelationIRelation
		$active_list = "";
		$n=0;
		foreach($irelation->eyuirelation_listData(
			$this->id,$this->model->eyuirelation_getPrimaryId()) as $key=>$text){
			$n++; 
			$oddeven = (($n % 2)==0) ? 'odd' : 'even';  
			$active_list .= "<li value='{$key}' class='$oddeven'><span>{$text}</span><img class='accion' src='{$deleteImgUrl}'></li>";
		}
		
		// process htmlOptions for main div in layout
		$htopts = '';
		if(empty($this->htmlOptions)){
			$htopts = "class='eyuirelation'";
		}else{
			if(!isset($this->htmlOptions['class']))
				$this->htmlOptions['class'] = 'eyuirelation';
			foreach($this->htmlOptions as $key=>$val)
				$htopts .= " {$key}='$val'";
		}
			
		
echo 
"
	<!-- EYuiRelation begins -->
	<div id='{$id}' {$htopts}'>
		{$_title}
		<ul>{$active_list}</ul>
		{$select}
		<input type='button' value='{$selectLabel}'>{$loadingImgTag}
		<div class='logger'></div>
	</div>
	<!-- EYuiRelation ends -->
";		
		
		Yii::app()->getClientScript()->registerScript(
			$id,
			"new EYuiRelation({$options});", 
			CClientScript::POS_LOAD
		);
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
	
		$this->id = $_GET['id'];
	
		$s = new CHttpSession();
		$s->open();
		$storedData = $s[$this->id];
		$s->close();
		$this->model = $storedData['model'];
		$this->optionsClassName = $storedData['optionsClassName'];
		$this->relationClassName = $storedData['relationClassName'];
	
		if($action == 'addnewoption'){
			// $data is a valid ID on EYuiRelationIOptions
			$ioptions = new $this->optionsClassName;
			$availableOptions = $ioptions->eyuirelation_listData($this->id,
				$this->model->eyuirelation_getPrimaryId());
			foreach($availableOptions as $key=>$text)
				if($key == $data){
					// add new relation
					$irelation = new $this->relationClassName;
					$id = $irelation->eyuirelation_insert($this->id,
						$this->model->eyuirelation_getPrimaryId(),$data);
					return array('text'=>$text,'id'=>$id);
				}
		}
		elseif($action == 'removeoption'){
			// data is the relation id
			$irelation = new $this->relationClassName;
			if($irelation->eyuirelation_remove($this->id,$data)){
				return "OK";
			}
			else
			throw new Exception("cant remove");
		}
		
		return array();
	}
}