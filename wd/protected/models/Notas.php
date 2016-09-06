<?php

/**
 * This is the model class for table "d8d1f_notas".
 *
 * The followings are the available columns in table 'd8d1f_notas':
 * @property integer $id
 * @property string $Idvhd
 * @property integer $nro
 * @property string $grado
 * @property string $orden
 * @property integer $codalumno
 * @property string $apellidosnombres
 * @property string $genero
 * @property string $observacion
 * @property integer $arte
 * @property integer $cta
 * @property integer $comu
 * @property integer $efis
 * @property integer $etra
 * @property integer $erel
 * @property integer $fcc
 * @property integer $hge
 * @property integer $ingl
 * @property integer $mate
 * @property integer $pfrrhh
 * @property string $areasdesaprobadas
 * @property string $recomendacion
 *
 * The followings are the available model relations:
 * @property Alumnos $alumnos
 * @property Alumnos $codalumno0
 */
class Notas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Notas the static model class
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
		return 'd8d1f_notas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Idvhd, nro, grado, orden, codalumno, apellidosnombres, genero', 'required'),
			array('nro, codalumno, arte, cta, comu, efis, etra, erel, fcc, hge, ingl, mate, pfrrhh', 'numerical', 'integerOnly'=>true),
			array('Idvhd', 'length', 'max'=>11),
			array('grado, orden, areasdesaprobadas', 'length', 'max'=>2),
			array('apellidosnombres, observacion, recomendacion', 'length', 'max'=>255),
			array('genero', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, Idvhd, nro, grado, orden, codalumno, apellidosnombres, genero, observacion, arte, cta, comu, efis, etra, erel, fcc, hge, ingl, mate, pfrrhh, areasdesaprobadas, recomendacion', 'safe', 'on'=>'search'),
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
			'alumnos' => array(self::HAS_ONE, 'Alumnos', 'codalumno'),
			'codalumno0' => array(self::BELONGS_TO, 'Alumnos', 'codalumno'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'Idvhd' => 'Idvhd',
			'nro' => 'Nro',
			'grado' => 'Grado',
			'orden' => 'Orden',
			'codalumno' => 'Codalumno',
			'apellidosnombres' => 'Alumno',
			'genero' => 'Genero',
			'observacion' => 'Observacion',
			'arte' => 'Arte',
			'cta' => 'Cta',
			'comu' => 'Comu',
			'efis' => 'Efis',
			'etra' => 'Etra',
			'erel' => 'Erel',
			'fcc' => 'Fcc',
			'hge' => 'Hge',
			'ingl' => 'Ingl',
			'mate' => 'Mate',
			'pfrrhh' => 'Pfrrhh',
			'areasdesaprobadas' => 'Areas desaprobadas',
			'recomendacion' => 'Recomendacion',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('Idvhd',$this->Idvhd,true);
		$criteria->compare('nro',$this->nro);
		$criteria->compare('grado',$this->grado,true);
		$criteria->compare('orden',$this->orden,true);
		$criteria->compare('codalumno',$this->codalumno);
		$criteria->compare('apellidosnombres',$this->apellidosnombres,true);
		$criteria->compare('genero',$this->genero,true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('arte',$this->arte);
		$criteria->compare('cta',$this->cta);
		$criteria->compare('comu',$this->comu);
		$criteria->compare('efis',$this->efis);
		$criteria->compare('etra',$this->etra);
		$criteria->compare('erel',$this->erel);
		$criteria->compare('fcc',$this->fcc);
		$criteria->compare('hge',$this->hge);
		$criteria->compare('ingl',$this->ingl);
		$criteria->compare('mate',$this->mate);
		$criteria->compare('pfrrhh',$this->pfrrhh);
		$criteria->compare('areasdesaprobadas',$this->areasdesaprobadas,true);
		$criteria->compare('recomendacion',$this->recomendacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}