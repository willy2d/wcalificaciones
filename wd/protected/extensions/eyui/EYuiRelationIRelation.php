<?php
interface EYuiRelationIRelation {
	/*
		must return the id created for this new relation or null
	*/
	public function eyuirelation_insert($widgetid,$masterPrimaryId, $optionPrimaryId);
	
	/**
		must return true or false
	*/
	public function eyuirelation_remove($widgetid,$primaryId);
	
	/**
		must return a CHtml::listData
	*/
	public function eyuirelation_listData($widgetid,$masterPrimaryId);
}