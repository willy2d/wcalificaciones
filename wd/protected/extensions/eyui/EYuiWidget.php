<?php 
/**
 * EYuiWidget class file.
 *
 *	Base class for all EYui Widgets.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
abstract class EYuiWidget extends CWidget {

	public $themeUrl;
	public $theme='base';
	public $jqueryUiCssFile='jquery-ui.css';

	private $_baseUrl;
	private $_debug;
	private $_defaultAction='index.php?r=site/eyui';
	
	public function init(){	
		$this->registerCoreScripts();
		parent::init();
	}
	
	/**
		this will translate messages using this source:
			protected/extensions/eyui/messages/language/eyui.php
	*/
	public static function t($text){
		return Yii::t("EYuiWidget.eyui",$text);
	}
	
	/**
	* Informs about _debug state. 
	* it produces registerCoreScripts to read assets directly from sources.
	*/
	public function getIsDebug(){
		return ($this->_debug==true);
	}
	/**
	* set debug mode.
	*/
	public function setDebug($flag=true){
		$this->_debug = $flag;
	}
	/**
	* returns the default action used for communication pruposes
	*/
	public function getDefaultAction(){
		return $this->_defaultAction;
	}

	/**
		detects if a given instance implements the required interface name
	*/
	protected function implementsInterface($modelInstance,$requiredInterface){
		if($_impl = class_implements($modelInstance))
			if(!isset($_impl[$requiredInterface]))
				throw new Exception(
				self::t("Sorry, the provided instance does not implements the required interface: ")
					.$requiredInterface);
	}
	
	/** 
	* Register the core scripts common for every extended widget from this class.
	* Registered: jQuery and assets directory.
	*/
	public function registerCoreScripts(){
		
		$localAssetsDir = dirname(__FILE__) . "/" . 'assets';
		$this->_baseUrl = Yii::app()->getAssetManager()->publish($localAssetsDir);
		
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
		
		if($this->isDebug){
			//$this->_baseUrl = $localAssetsDir;
			$this->_baseUrl = 'protected/extensions/eyui/assets';
		}
		
		foreach(scandir($localAssetsDir) as $f){
			$_f = strtolower($f);
			if(strstr($_f,".js"))
				$cs->registerScriptFile($this->_baseUrl."/".$_f);
			if(strstr($_f,".css"))
				$cs->registerCssFile($this->_baseUrl."/".$_f);
		}
		
		// jquery-ui
		if($this->themeUrl == null)
			$this->themeUrl = $this->_baseUrl;
		$cs->registerCssFile($this->themeUrl."/".$this->theme.
			"/".$this->jqueryUiCssFile);
	}
	
	/**
		Helper method to provide a stored resource
	*/
	public function getResource($fileName){
		return $this->_baseUrl."/".$fileName;
	}
}