<?php

/**
 * This is the model class for table "d8d1f_alumnos".
 *
 * The followings are the available columns in table 'd8d1f_alumnos':
 * @property integer $codalumno
 * @property string $idvhd
 * @property integer $nro
 * @property string $grado
 * @property integer $orden
 * @property string $apellidosnombres
 * @property string $genero
 * @property string $observacion
 *
 * The followings are the available model relations:
 * @property Notas $codalumno0
 * @property Notas[] $notases
 */
class Alumnos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Alumnos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'd8d1f_alumnos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codalumno, idvhd, nro, grado, orden, apellidosnombres, genero', 'required'),
			array('codalumno, nro, orden', 'numerical', 'integerOnly'=>true),
			array('idvhd', 'length', 'max'=>11),
			array('grado', 'length', 'max'=>2),
			array('apellidosnombres, observacion', 'length', 'max'=>255),
			array('genero', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('codalumno, idvhd, nro, grado, orden, apellidosnombres, genero, observacion', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'codalumno0' => array(self::BELONGS_TO, 'Notas', 'codalumno'),
			'notases' => array(self::HAS_MANY, 'Notas', 'codalumno'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'codalumno' => 'Codalumno',
			'idvhd' => 'Idvhd',
			'nro' => 'Nro',
			'grado' => 'Grado',
			'orden' => 'Orden',
			'apellidosnombres' => 'Apellidosnombres',
			'genero' => 'Genero',
			'observacion' => 'Observacion',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('codalumno',$this->codalumno);
		$criteria->compare('idvhd',$this->idvhd,true);
		$criteria->compare('nro',$this->nro);
		$criteria->compare('grado',$this->grado,true);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('apellidosnombres',$this->apellidosnombres,true);
		$criteria->compare('genero',$this->genero,true);
		$criteria->compare('observacion',$this->observacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}