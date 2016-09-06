<?php
class EYuiFormModel extends CModel {
	private static $_names;
	
	public function setAttributes($values,$safeOnly=false) 
	{ 
		foreach($values as $k=>$v)
			if(!in_array($k,$this->attributeNames())){
				// this exception is thrown when an invalid attribute is specified
				// in the EYuiForm widget array fielddefs or pages.
				throw new Exception("invalid attribute name: ".$k.", value=".$v);
			}
	
		parent::setAttributes($values,false);
	}	
	public function attributeNames() 
	{ 
		$className=get_class($this); 
		if(!isset(self::$_names[$className])) 
		{ 
			$class=new ReflectionClass(get_class($this)); 
			$names=array(); 
			foreach($class->getProperties() as $property) 
			{ 
				$name=$property->getName(); 
				if($property->isPublic() && !$property->isStatic()) 
					$names[]=$name; 
			} 
			return self::$_names[$className]=$names; 
		} 
		else 
			return self::$_names[$className]; 
	}
}