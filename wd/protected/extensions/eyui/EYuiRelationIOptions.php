<?php
interface EYuiRelationIOptions {
	/**
		must return a CHtml::listData
	*/
	public function eyuirelation_listData($widgetid,$primaryid);
}