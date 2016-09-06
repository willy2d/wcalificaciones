<?php
/**
 * EYuiFormEditorDb class file. please view readme for details.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
class EYuiFormEditorDb implements
	EYuiFormEditorIStorage {

	const ITEM_PAGE=1;
	const ITEM_GROUP=2;
	const ITEM_FIELD=3;

	public $model_id;
	public $form_id;
	public $tablename;

	public static function model($model_id,$form_id) {
		$inst = new EYuiFormEditorDb();
		$inst->model_id = $model_id;
		$inst->form_id = $form_id;
		return $inst;
	}

	public function getDb(){
		return Yii::app()->db;
	}
	public function getTablename(){
		return $this->tablename == null ? "eyuiformeditordb" : $this->tablename;
	}

	/**
		must return an array of indexed arrays with this key pair values:
			array(
				array('id','item_id','label','descr','parent_id','data'),
				array('id','item_id','label','descr','parent_id','data'),
				...
				array('id','item_id','label','descr','parent_id','data'),
			)
	*/
	public function eyuiformeditordb_listitems($item_type,$parent_id){
		if($item_type == 'page')
			$item_type = self::ITEM_PAGE;
		if($item_type == 'group')
			$item_type = self::ITEM_GROUP;
		if($item_type == 'field')
			$item_type = self::ITEM_FIELD;
		$params = array(
			':modelid'=>$this->model_id,
			':formid'=>$this->form_id,
			':item'=>$item_type
		);
		$whereCond = "model_id = :modelid AND form_id = :formid and item = :item";
		$rows=$this->getDb()->createCommand()
			->select('id, item_id, label, descr, position, data, parent_id')
			->from($this->getTablename())
			->where($whereCond,$params)
			->order(array('position'))
			->queryAll();
		$values = array();
		foreach($rows as $row){
			if(($item_type == self::ITEM_PAGE) || ($row['parent_id'] == ((int)$parent_id)))
			$values[] = array(
				'id'=>(int)$row['id'],
				'item_id'=>$row['item_id'],
				'label'=>$row['label'],
				'descr'=>CHtml::decode($row['descr']),
				'position'=>(int)$row['position'],
				'data'=>$row['data'],
				'parent_id'=>(int)$row['parent_id'],
				);
		}
		return $values;
	}


	/**
		must return an indexed array with this key pair values:
			array('id','item_id','label','descr','parent_id','data')
	*/
	public function eyuiformeditordb_newitem($item_type,$label,$parent_id){
		$prefix=$item_type;
		if($item_type == 'page')
			$item_type = self::ITEM_PAGE;
		if($item_type == 'group')
			$item_type = self::ITEM_GROUP;
		if($item_type == 'field')
			$item_type = self::ITEM_FIELD;
		$params = array(
			':modelid'=>$this->model_id,
			':formid'=>$this->form_id,
			':item'=>$item_type
		);
		$whereCond = "model_id = :modelid AND form_id = :formid and item = :item";

		$this->getDb()->createCommand()
			->insert($this->getTablename(), array(
				'item'=>$item_type,
				'model_id'=>$this->model_id,
				'form_id'=>$this->form_id,
				'item_id'=>'new',
				'parent_id'=>$parent_id,
				'label'=>$label,
				'descr'=>'',
				'data'=>"[{'name': 'uicomponent', 'value': 'textbox'}]",
			));

		$rows=$this->getDb()->createCommand()
			->select('id, item_id, label, descr, position, data, parent_id')
			->from($this->getTablename())
			->where($whereCond,$params)
			->queryAll();

		$nextPos=0;
		foreach($rows as $row){
			$p = 1*($row['position']);
			if($p > $nextPos)
				$nextPos = $p;
		}
		$nextPos++;

		foreach($rows as $row){
			if($row['item_id']=='new')
				{
					$id = $row['id'];
					$newId = $prefix.$id;
					$this->getDb()->createCommand()->update(
						$this->getTablename(), array(
								'label'=>$label." ".$nextPos,
								'item_id'=>$newId,
								'position'=>$nextPos
							),
							"id = :id",array(':id'=>$id));

					return array(
						'id'=>$id,'item_id'=>$newId,'label'=>$row['label'],
						'descr'=>$row['descr'],'position'=>$nextPos
						,'data'=>$row['data'],'parent_id'=>$row['parent_id']);
				}
		}
		return null;
	}


	/**
		delete an item by its primary id
	*/
	public function eyuiformeditordb_deleteitem($id){
		return $this->getDb()->createCommand()->delete($this->getTablename()
			,"id = :id",array(':id'=>$id));
	}

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
	public function eyuiformeditordb_updateitem($obj) {
		return $this->getDb()->createCommand()->update(
				$this->getTablename(),
				array(
					'item_id'=>$obj['item_id'],
					'label'=>$obj['label'],
					'descr'=>CHtml::encode($obj['descr']),
					'position'=>$obj['position'],
					'data'=>$obj['data'],
				),
				"id = :id",array(':id'=>$obj['id'])
			);
	}

	// data is an array formed in javascript as: {name: "fieldname", value: "fieldvalue"}
	private function _dataFinder($data,$name){
		foreach($data as $item)
			if($item['name']==$name)
				return $item['value'];
		return "";
	}

	public function eyuiformeditordb_getpages(){
		$result = array();
		$pages = $this->eyuiformeditordb_listitems('page',null);
		if(isset($pages))
		foreach($pages as $page){
			$groups = $this->eyuiformeditordb_listitems('group',$page['id']);
			$arGroups = array();
			if(isset($groups))
			foreach($groups as $group){
				$arFields = array();
				$fields = $this->eyuiformeditordb_listitems('field',$group['id']);
				if(isset($fields))
					foreach($fields as $field){
						$data = CJSON::decode($field['data']);
						// data example:
						/* [{"name":"uicomponent","value":"textarea"},{"name":"default","value":"1"},{"name":"options","value":"2"},{"name":"required","value":0},{"name":"requiredmessage","value":"3"},{"name":"pattern","value":"4"},{"name":"patternmessage","value":"5"},{"name":"maxlength","value":"6"},{"name":"size","value":"7"},{"name":"class","value":"8"},{"name":"style","value":"9"}]
						*/
						if($data != null)
						$arFields[$field['item_id']] = array(
							'label'=>$field['label'],
							'descr'=>CHtml::decode($field['descr']),
							'default'=>$this->_dataFinder($data,'default'),
							'help'=>$field['descr'],
							'required'=>$this->_dataFinder($data,'required'),
							'requiredMessage'=>$this->_dataFinder($data,'requiredmessage'),
							'dateformat'=>$this->_dataFinder($data,'dateformat'),
							'htmlOptions'=>array(
									'size'=>$this->_dataFinder($data,'size'),
									'maxlength'=>$this->_dataFinder($data,'maxlength'),
									'class'=>$this->_dataFinder($data,'class'),
									'style'=>$this->_dataFinder($data,'style'),
								),
							'field'=>array(
								'id'=>$field['item_id'],
								'uicomponent'=>$this->_dataFinder($data,'uicomponent'),
								'pattern'=>$this->_dataFinder($data,'pattern'),
								'patternMessage'=>$this->_dataFinder($data,'patternmessage'),
								'options'=>$this->_dataFinder($data,'options'),
							),
						);
					}
				$arGroups[$group['item_id']] = array('label'=>$group['label'],'descr'=>$group['descr']
					,'fields'=>$arFields);
			}
			$result[$page['item_id']] = array(
				'label'=>$page['label'],
				'descr'=>$page['descr'],
				'groups'=>$arGroups
			);
		}
		//die("leido: ".CJSON::encode($result));
		return $result;
	}
}