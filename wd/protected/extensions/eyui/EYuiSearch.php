<?php
/**
 * EYuiSearch class file.
 *
 * Present a search widget jquery based and perform a search over selected data model.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiSearch extends EYuiWidget implements EYuiActionRunnable {
	
	public $model;
	public $attribute;
	public $searchModel;
	public $attributes;
	public $searchLabel='Search:';
	public $searchTextButton='Search';
	public $okTextButton='Ok';
	public $noResultsMessage='No results found.';
	public $findTextBoxId; // if setted it sets the ID for search input box.
	public $onSuccess;	// function(val,text){}
	public $onError;	// function(e){ alert(e.responseText); }
	public $onEmptyResult;	// function(txt){ }
	public $action=null;
	public $context;	// any context name it will be passed to eyuisearchable_findModels
	
	public $id;
	public $cssClassname='eyuisearch';
	
	
	public function init(){
		//$this->setDebug();
		parent::init();
	}
	
	
	public function run(){
		$id=($this->id != null) ? $this->id : "ejuisearch0";
		$cssClassName = ($this->cssClassname != null) ? "class='".$this->cssClassname."'" : '';
		$loadingImgTag = CHtml::image($this->getResource('loading.gif'));

		if(count($this->attributes) != 2){
			/*
				example:
				
				'attributes'=>array('idproduct','productname'),
			*/
			throw new Exception('\'attributes\' must be an array and must have two entries in order to acomplish CHtml::listData.',500);
		}
		
		$data = serialize(array(
			'classname'=>get_class($this->searchModel),
			'attributes'=>$this->attributes,
			'context'=>$this->context,
		));
		
		$action = $this->action != null ? $this->action : $this->defaultAction;
		// this parameter is needed in EYuiAction to provide a reference for this class
		// who is listening for an action request redirected to this class via runAction method.
		$action .= "&classname=".__CLASS__;
		
		// model.attribute id to be setted via jquery whenever a user clics ok on a search 
		// result item.
		$modelAttributeId='';
		if(($this->model != null) && ($this->attribute != null))
			$modelAttributeId = get_class($this->model).'_'.$this->attribute;
		
		$findInputId = ($this->findTextBoxId != null) ? "id='".$this->findTextBoxId."'" : "";
		
		$layout = 
		"\n<div id='{$id}' {$cssClassName}>\n
			<div class='searchbar'>
				<span>{$this->searchLabel}</span>
				<input class='textinput' type='text' {$findInputId}>
				<input class='button' type='button' value='{$this->searchTextButton}'>
				{$loadingImgTag}
			</div>\n
			<div class='resultsbar'>
				<select></select>
				<input class='button' type='button' value='{$this->okTextButton}'>
			</div>\n
			<span class='error'></span>\n
		</div>\n\n"
		;
		
		echo $layout;
		
		if($this->onSuccess == null)
			$this->onSuccess = 'function(val,text){ }';
		if($this->onError == null)
			$this->onError = 'function(e){ }';
		if($this->onEmptyResult == null)
			$this->onEmptyResult = 'function(txt){ }';
		if(!($this->onSuccess instanceof CJavaScriptExpression))
				$this->onSuccess = new CJavaScriptExpression($this->onSuccess);
		if(!($this->onError instanceof CJavaScriptExpression))
				$this->onError = new CJavaScriptExpression($this->onError);
		if(!($this->onEmptyResult instanceof CJavaScriptExpression))
				$this->onEmptyResult = new CJavaScriptExpression($this->onEmptyResult);
		
		$options = CJavaScript::encode(
			array(
				'id'=>$id,
				'action'=>$action,
				'onSuccess'=>$this->onSuccess,
				'onError'=>$this->onError,
				'onEmptyResult'=>$this->onEmptyResult,
				'modelAttributeId'=>$modelAttributeId,
				'noResultsMessage'=>$this->noResultsMessage,
				'data'=>$data,
			)
		);
		
		Yii::app()->getClientScript()->registerScript(
			$id,
			"new EYuiSearch({$options});", 
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
		$udata = unserialize($data);
		$this->searchModel = new $udata['classname'];
		$this->attributes = $udata['attributes'];
		$this->context = $udata['context'];
		
		$primaryKey = $this->attributes[0];
		$label = $this->attributes[1];
		
		Yii::log("runAction called action is:".$action,"info");
		if($action == 'search'){
			$searchText = $_GET['searchtext'];
			// perform a query to specified class name referenced by $this->searchModel
			// this class must implements:
			//	EYuiSearchable
			return CHtml::listData(
				$this->searchModel->eyuisearchable_findModels($searchText,$this->context),
				$primaryKey,
				$label
			);
		}
		return array();
	}
	
}