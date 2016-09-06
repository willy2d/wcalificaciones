<?php
class EYuiFormField  extends EYuiFormModel {
	public $id;
	public $field; // when parsed is a fielddef instance, when config: is the fielddef key id
	public $label;
	public $descr;
	public $default;
	public $required; //boolean
	public $requiredMessage;
	public $help; // extra extended information about this field shown when validation fails
	public $htmlOptions;
	public $parent;
	public $modelName;	// used to produce: 'name'=>'modelname[fieldname]'
	public $dateformat;
	public $descriptionLocation; // 'visible' or 'title'
	private $_value;
	
	public function getGroup(){
		return $this->parent;
	}
	public function getPage(){
		return $this->parent->parent;
	}
	
	public function getName(){
		$group = $this->parent;
		$page  = $group->parent;
		return $page->id."_".$group->id."_".$this->id;
	}
	
	public function getValue(){
		return $this->_value;
	}
	public function setValue($newValue){
		$this->_value = $newValue;
	}
	public function getHelpMessage(){
		$a="";
		$b="";
		if($this->help != null)
			$a = $this->help;
		if($this->field->help != null)
			$b = $this->field->help;
		$sep="";
		if(strlen($a)>0)
			$sep=" ";
		return $a.$sep.$b;
	}
	
	public function __toString(){
		
		$input = "";
		if(is_string($this->field))
			return "[{$this->field}]";
			
		if(!isset($this->dateformat) || ($this->dateformat == ''))
			$this->dateformat = 'dd/mm/yy';
			
		if(!in_array($this->field->uicomponent,
			array("textbox","textarea","listbox","combobox","checkbox","datebox")))
			return "[invalid: ".$this->field->uicomponent."]";
			
		if(!in_array($this->descriptionLocation,array('visible','title')))
			$this->descriptionLocation = 'title';
		$descrVisibleTag = '';
		if($this->descriptionLocation == 'visible')
			$descrVisibleTag = "<div class='descr'>{$this->descr}</div>";
		if($this->descriptionLocation == 'title')
			if(!isset($this->htmlOptions['title']))
				$this->htmlOptions['title'] = CHtml::encode($this->descr);
			
	
		$name = $this->getName();
	
		if($this->getValue() == '')
			$this->setValue(CHtml::encode($this->default));
		
		$value = $this->getValue();
		
		$htmlOptions = array();
		if(isset($this->htmlOptions))
			$htmlOptions += $this->htmlOptions;
		if(isset($this->field->htmlOptions))
			$htmlOptions += $this->field->htmlOptions;
			
		// clear empty attributes
		//
		if(isset($htmlOptions))
			foreach($htmlOptions as $opt=>$val)	
				if(empty($val))
					unset($htmlOptions[$opt]);
			
		//
		// IMPORTANT:
		//	every input field must have 'alt' signature stablished to 'input' or it wont be recognized
		//	by eyuiform.js when post.
		if(!isset($htmlOptions['alt']))
			$htmlOptions['alt'] = 'input';
			
		$inputName = $this->modelName."[".$name."]";
		$htmlOptions['id'] = $this->getName();
		
		
		$req = $this->required == true ? '*' : '';
		$reqClass = $this->required == true ? 'required' : '';
	
		if($this->field->uicomponent == 'textbox')
			$input = CHtml::textField($inputName,$value,$htmlOptions);
	
		if($this->field->uicomponent == 'textarea')
			$input = CHtml::textArea($inputName,$value,$htmlOptions);
	
		if($this->field->uicomponent == 'listbox'){
			$input = CHtml::listBox($inputName,$value,$this->checkOptions($this->field->options),$htmlOptions);
		}
	
		if($this->field->uicomponent == 'combobox'){
			$prompt = array();
			if($this->field->prompt != null)
				$prompt = $this->field->prompt;
			$input = CHtml::dropDownList($inputName,$value,$prompt+$this->checkOptions($this->field->options),$htmlOptions);
		}
		
		if($this->field->uicomponent == 'checkbox'){
			if(!isset($htmlOptions['class']))
				$htmlOptions['class']='checkbox';
			$input = CHtml::checkBox($inputName,($this->getValue() > 0),$htmlOptions);
		}
		
		if($this->field->uicomponent == 'datebox'){
			$id = $this->getName();
			$language = Yii::app()->language;
			$dp_options = CJavaScript::encode(array(
				'constrainInput'=>true,
				'dateFormat'=>$this->dateformat,
				'showOn'=>"both",
				'showButtonPanel'=>true,
				'changeMonth'=>true,
				'changeYear'=>true,
			));
	
			if((($language == 'en') || ($language=="") || ($language=='en-US') || ($language=='en_us')))
				$language='en-GB';

			$js = "jQuery('#{$id}').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$language}'], {$dp_options}));";
			
			$input = CHtml::textField($inputName,$value,$htmlOptions);
			Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id, $js);
		}
		
		if($this->field->uicomponent == 'checkboxlist'){
			/*
			$input = "<div class='checkboxes'>";
			foreach($this->field->options as $_key=>$_val){
				$_id = $this->id."_".$_key;
				$input .= "<span class='checkbox'><label for='{$_id}'>{$_val}</label>".CHtml::checkBox(
					$name,false,array('id'=>$_id))."</span>";
			}
			$input .= "</div>";
			*/
		}
		
		return "
<li class='{$reqClass}'>
	<label>{$req}{$this->label}:</label><br/>
	<span class='fieldspan'>{$input}</span><span class='loader'></span>
	<div class='info'>
		{$descrVisibleTag}
		<div class='error'></div>
	</div>
</li>
";
	}
	
	/**
		it will review if passed options are an array (as expected) or and string, so it will convert it to an array.
		
		if it is a string, expected format:
			"value1, text1\n
			 value2, text2\n
			"
		example:
		"1, red\n2, green\n3, yellow"
	*/
	private function checkOptions($options){
		if(is_array($options)){
			return $options;
		}else
		return $this->explodeOptions($options);
	}
	
	public function explodeOptions($listValues){
		$lista = explode("\n",$listValues);
		$ar=array();
		foreach($lista as $item){
			$k = explode(",",$item);
			$val = "";
			$text = "";
			if(count($k)==2){
				$val = trim($k[0]);
				$text = trim($k[1]);
			}
			else{
				$val = "0";
				$text = trim($k[0]);
			}
			$ar[$val] = $text;
		}
		return $ar;
	}	
}