<?php
 /**
 * EYuiAction class file.
 *
 *	must be installed in any controller, by default in site/controller as EYuiWidget defaults to.
 *
 *	@example:
 *		public function actions() { return array('eyui'=>array('class'=>'EYuiAction')); }
 *
 *  By default, EYuiWidget defaults an action to: siteController/eyui. Whenever a widget 
 *  requires access to data models it performs a call to this action via jquery ajax routines
 *  passing to it at least two parameters: action and data.
 *
 *  As an example using EYuiSearch: 
 *		EYuiSearch invokes an action via ajax: 
 *			url='site/eyui&classname=EYuiSearch&action=search&data=xxxx&text=hello'
 *
 *		in first place this action will dispatch its responsability to a 'classname' (see url argument)
 *			$inst = new $_className();
 *
 *		in second place this action will pass 'action' and 'data' arguments to the referenced class.
 *
 *		finally EYuiSearch will be invoked and its returns value will be converted to a JSON 
 * 		representation. (EYuiSearch implements EYuiActionRunnable interface)
 *
 *	@see
 *		EYuiActionRunnable
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiAction extends CAction {
	/**
	* this action invokes the appropiated handler referenced by a 'classname' url attribute.
	* the specified classname must implements: EYuiActionRunnable.php
	*/
	public function run(){
		$_className = $_GET['classname'];
		$inst = new $_className();
		header("Content-type: application/json");
		//header("charset: UTF-8");
		$data = isset($_GET['data']) ? $_GET['data'] : "";
		echo CJSON::encode($inst->runAction($_GET['action'],$data));
	}
 }	
