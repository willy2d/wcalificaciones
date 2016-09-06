<?php 
interface EYuiFormEditorIStorage {

	public function eyuiformeditordb_listitems($item_type,$parent_id);
	/**
		must return an indexed array with this key pair values:
			array('id','item_id','label','descr','parent_id','data')
	*/
	public function eyuiformeditordb_newitem($item_type,$label,$parent_id);
	/**
		delete an item by its primary id
	*/
	public function eyuiformeditordb_deleteitem($id);
	/**
		$obj: 
			array item keys:
				id
				item_id
				label
				descr
				position
				data
				parent_id
		must return:
			true or false
	*/
	public function eyuiformeditordb_updateitem($obj);
	/**
		returns an array for EYuiForm.pages config
	*/
	public function eyuiformeditordb_getpages();
}