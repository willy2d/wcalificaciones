<?php

class EYuiAjaxAction extends EYuiWidget{
	public $id;
	public $action;
	public $label;
	public $labelOn;
	public $onBeforeAjaxCall;
	public $onSuccess;
	public $onError;
	public $ajaxoptions;
	public $htmlOptions;
	
	public function run(){
		
		$this->debug = true;
	
		if($this->id == null)	
			$this->id = 'eyuiajaxaction0';
			
		$defaultCssStyle = 'cursor: pointer; color: blue; text-decoration: underline;';
		$loading = $this->getResource("loading.gif");
		$htoptions="";
		if(isset($this->htmlOptions) && is_array($this->htmlOptions)){
			if(!isset($this->htmlOptions['style']))
				$this->htmlOptions['style'] = $defaultCssStyle;
		}
		else{
			$this->htmlOptions['style'] = $defaultCssStyle;
		}
		foreach($this->htmlOptions as $key=>$val)
			$htoptions .= " ".$key."='".$val."' ";
		
		if(is_array($this->action)){
			$action = CHtml::normalizeUrl($this->action);
		}else
			$action = $this->action;
		
		if($this->onSuccess == null)
			$this->onSuccess = 'function(val,text){ }';
		if($this->onError == null)
			$this->onError = 'function(e){ }';
		if($this->onBeforeAjaxCall == null)
			$this->onBeforeAjaxCall = 'function(){ }';
		
		if(!($this->onSuccess instanceof CJavaScriptExpression))
				$this->onSuccess = new CJavaScriptExpression($this->onSuccess);
		if(!($this->onError instanceof CJavaScriptExpression))
				$this->onError = new CJavaScriptExpression($this->onError);
		if(!($this->onBeforeAjaxCall instanceof CJavaScriptExpression))
				$this->onBeforeAjaxCall = new CJavaScriptExpression($this->onBeforeAjaxCall);
				
		if(!isset($this->ajaxoptions['url']))
			$this->ajaxoptions['url']=CHtml::normalizeUrl($this->action);
		if(!isset($this->ajaxoptions['success']))
			$this->ajaxoptions['success']=new CJavaScriptExpression("function(data){ ({$this->onSuccess})(data); clearBusy(); }");
		if(!isset($this->ajaxoptions['error']))
			$this->ajaxoptions['error']=new CJavaScriptExpression("function(e){ ({$this->onError})(e); clearBusy(); }");
		
		$ajxOptions = CJavaScript::encode($this->ajaxoptions);
		
		$options = CJavaScript::encode(array('onBeforeAjaxCall'=>$this->onBeforeAjaxCall));
		
		$layout = 
"
	<div id='{$this->id}' {$htoptions}><span>{$this->label}</span><img src='{$loading}' style='display: none;'></div>
	
";

		echo $layout;
	
		Yii::app()->getClientScript()->registerScript(
			"script_{$this->id}",
"
$('#{$this->id}').click(function(){
	var options = {$options};
	var img = $(this).find('img');
	var label = $(this).find('span');
	label.html('{$this->labelOn}');
	var obj = $(this);
	if(obj.data('busy')==true)
		return;
	// mark the components in its busy state
	img.show();
	obj.data('busy',true);
	obj.addClass('eyuiajaxaction-busy');
	var clearBusy = function(){
		label.html('{$this->label}');
		obj.removeClass('eyuiajaxaction-busy');
		obj.data('busy',false);
		img.hide();
	}
	options.onBeforeAjaxCall();
	$.ajax({$ajxOptions});
});
", 
			CClientScript::POS_END
		);
	}
}