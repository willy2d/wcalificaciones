EYUI
----

This extension provides a set of Reusable Software Components for usage in your Yii Web Application. 

[Main Repository](https://bitbucket.org/christiansalazarh/eyui "EYui Widgets - jQuery Based Widgets")

[Demo Repository](https://bitbucket.org/christiansalazarh/eyuidemo "Bitbucket repo - demo")

[Issue Report](https://bitbucket.org/christiansalazarh/eyui/issues "Please reporte issues here")

Available Widgets:

* EYuiSearch
* EYuiAjaxAction
* EYuiRelation
* EYuiForm
* EYuiFormEditor
* EYuiFormDataExport

##Main Installation

All EYui widgets shares the same configuration, it must be set up in two stages:

1) setup in config main:

~~~
'import'=>array(
	'ext.eyui.*',	// please check case sensitive directory naming in linux environments.
),
~~~

2) Setup a common action in your **siteController** (or something else)
(this action serves for ajax communication between your widgets and your application, it is common for every EYui widget in this package.)
~~~
public function actions()
{
	return array(
		'eyui'=>array(
			'class'=>'EYuiAction',
		),
	);
}
~~~

##i18N (International)

EYui supports internationalization using standard yii mechanism.  You'll find a 'messages' folder
containing traduction for spanish language for now (default english). 
Please refer to Yii documentation to know how translation is performed.

in order to translate any message using EYui please use: EYuiWidget::t('my text')
it will search for an entry in this file:
		protected/extensions/eyui/messages/<YOUR LANGUAGE>/eyui.php
		please note: 
		<YOUR LANGUAGE> is provided in your config file using the 'language' attribute.



Available Components (Widgets)
------------------------------

##EYuiSearch

Performs an ajax search on your data model, based on a user provided text.

![EYuiSearch screenshot](https://bitbucket.org/christiansalazarh/eyui/downloads/eyuisearch.gif "EYuiSearch Screenshot")

inserting the widget:
~~~
// 'model' & 'attribute': is your receptor, who will receive the selection.
// 'searchModel': is a model who implements a EYuiSearchable interface,
// 'attributes': is a field list in your 'searchModel', it will be shown in the ui listbox.
// 'context': is an extra data to be passed to eyuisearchable_findModels
$this->widget('ext.eyui.EYuiSearch'
	,array(
		'model'=>$model,
		'attribute'=>'idperson',
		'searchModel'=>Person::model(),				
		'attributes'=>array('idperson','names'),	
		'onSuccess'=>'function(val,text){  }',
		'onError'=>'function(e){ alert(e.responseText); }',
		'onEmptyResult'=>'function(searchText){ }',
		'noResultsMessage'=>'Sorry, no results found.',
		'context'=>'',
	)
);
~~~

Implements an interface in your searchable data model (the model referenced in the 'searchModel' widget attribute)
~~~
class Person extends CActiveRecord 
	implements EYuiSearchable
{
	/**
		$text: is the user provided text.
		@returns:
			you must return your results matching the input argument ($text).
	*/
	public function eyuisearchable_findModels($text,$context){
		$criteria=new CDbCriteria;
		$criteria->params = array(':text'=>"%".$text."%");
		$criteria->addCondition("names like :text");
		
		// as an example:
		// if($context=='dogs') ..find 'dogs'..
		// if($context=='persons') ..find 'persons'..
		
		return $this->findAll($criteria);
	}
	...
}
~~~

How it works ?

Your user puts a text in your widget, press 'find' button, next the widget will perform an ajax
search in your 'searchModel' (your model who implements a EYuiSearchable), calling the
method: eyuisearchable_findModels. It expects to receive a result based in the text argument,
all results will be shown in the dropDownList (a comboBox). When a user picks a result
in your dropDownList then your model->attribute will be setted using this selection.


##EYuiAjaxAction

Creates an ajax action launcher.  

Is similar to CHtml::ajaxLink but it builds a more advanced widget to perform ajax calls to your Yii actions showing a loading icon, handling for success and error events and support for customized messages, all in one single widget.

![EYuiAjaxAction screenshot](https://bitbucket.org/christiansalazarh/eyui/downloads/eyuiajaxaction.gif "EYuiAjaxAction Screenshot")

~~~
	<div id='mylogger'></div>
	<?php 
		$this->widget('ext.eyui.EYuiAjaxAction'
			,array(
				'id'=>'myId',
				'action'=>array('example/send'),
				'label'=>'Send Data',
				'labelOn'=>'Sending...',
				'onBeforeAjaxCall'=>'function(){ $("#mylogger").html("..."); }',
				'onSuccess'=>'function(data){ $("#mylogger").html(data); }',
				'onError'=>'function(e){ $("#mylogger").html(e.responseText); }',
				'htmlOptions'=>array('class'=>'yourclass'),
				// ajax options, please read ajax documentation for option names.
				// 'ajaxOptions'=>array('optionName'=>'value'),
			)
		);
	?>
~~~


##EYuiRelation

![EYuiForm screenshot](https://bitbucket.org/christiansalazarh/eyui/downloads/eyuirelation.gif "EYuiRelation Screenshot")

###Documentación en español

EYuiRelation maneja relaciones entre modelos de una forma visual. El tipo de relacion que maneja es una muy sencilla
en donde el objeto A se relaciona con el objeto B y esta relacion se almacena en un modelo AB. 

Explico con un ejemplo basado en el actual demo publicado en EYuiDemo:

Tenemos tres tablas:
	TblCompany	{id,name}
		almacena una lista de compañias
	TblDepartment {id, name}
		almacena una lista de departamentos
	TblCompanyDepartment {id, company_id, department_id}
		asocia varios departamentos a una empresa

Lo que queremos hacer es que mediante un widget podamos visualmente asignar departamentos a una compañia seleccionada,
pues bien con EYuiRelation se puede insertando un widget asi: (siguiendo el ejemplo)

~~~
	<?php 
		$this->widget('ext.eyui.EYuiRelation'
			,array(
				'id'=>'departmentrelationship', // important !
				'model'=>$model,
				'title'=>'Departments:<hr/>',
				'optionsClassName'=>'TblDepartment',
				'relationClassName'=>'TblCompanyDepartment',
			)
		);
	?>
~~~

En donde:

	'model': 
		es la compañia, el maestro, es una clase que implementa a: EYuiRelationIMaster
	'optionsClassName':
		es la clase que tiene las opciones para agregar, los departamentos disponibles,
		esta clase debe implementar a: EYuiRelationIOptions
	'relationClassName':
		es la clase que guardara la relacion AB, debe implementar: EYuiRelationIRelation


EYuiRelation permite que puedas asociar cualquier cosa, no solo objetos
de activeRecord de Yii, por esa razón esta basada en interfaces para que seas tu quien
diga de donde salen los datos, la interfaz solo asegura que EYuiRelation reciba lo que necesita.
	
Implementando EYuiRelation.

Es mas sencillo que te refieras al demo y veas como se manejan las clases que se pretende
que sean manejadas con EYuiRelation, es bastante simple.

Por favor ten claro que cuando hablo de Company-Department es solo para proveer un ejemplo,
con EYuiRelation puedes manejar cualquier relacion AB.
	
Mas abajo verás un ejemplo de como se implementan las interfaces para que el widget funcione.	
	
###English Documentation
	
EYuiRelation handle relations between models in a visual way. The relationship handled is very simple:
the object A has a relation with object B, and this relationship is stored in a new model named AB.

Please let me explain using an example:

Consider three tables:

	TblCompany	{id,name}
		stores a company list.
	TblDepartment {id, name}
		stores a department list.
	TblCompanyDepartment {id, company_id, department_id}
		is the AB relationship between company and department, in other words it says:
		"the company A has many (or none) departments"

Using this widget we can visualize and handle this departments related to a selected company, please
follow this example:

~~~
	<?php 
		$this->widget('ext.eyui.EYuiRelation'
			,array(
				'id'=>'departmentrelationship', // important !
				'model'=>$model,
				'title'=>'Departments:<hr/>',
				'optionsClassName'=>'TblDepartment',
				'relationClassName'=>'TblCompanyDepartment',
			)
		);
	?>
~~~

In where:

	'model': 
		is the company, is a class who implements EYuiRelationIMaster.
	'optionsClassName':
		this class has the available options, has the departments to be selected for add each
		one to a selected company. This must implements: EYuiRelationIOptions
	'relationClassName':
		this is the AB relationship between companies and departments
		this class must implements: EYuiRelationIRelation

Using EYuiRelation you can associate any objects, not only CActiveRecord instances. because this EYuiRelation is
based on a set of interfaces. Using this way you can provide data and EYuiRelation is responsible to handle this data.
	
###Implementing EYuiRelation.

Please refer to EYuiDemo, in that project you will find clear examples, please pay attention to the implemented interfaces on TblDepartments, TblCompany and TblCompanyDepartment.

Please be notified that Department-Company are examples, you can associate anything you want.

~~~
	<?php 
		// In this case TblCompany acts as X part on a XY relationship.				
		//
		class TblCompany extends CActiveRecord
			implements EYuiRelationIMaster
		{
			public function eyuirelation_getPrimaryId() {
				return $this->primarykey;
			}
			...
		}
	?>
~~~

~~~
	<?php 
		// In this case TblJob acts as Y part on a XY relationship.				
		//
		class TblJob extends CActiveRecord
			implements EYuiRelationIOptions
		{
			public function eyuirelation_listData($widgetid,$primaryid) {
				// primaryId:
				//	when a widget is implemented in a form, you pass a 'model' attribute,
				//	so, this primaryId is the model->primaryKey value obtained using
				//  the EYuiRelationIMaster interface.
				return CHtml::listData(self::model()->findAll(),'id','name');
			}
			...
		}
	?>
~~~


~~~
	<?php 
		class TblCompanyJob extends CActiveRecord
			implements EYuiRelationIRelation
		{
			public function eyuirelation_insert($widgetid,$masterPrimaryId, $optionPrimaryId){
				// $masterPrimaryId is: company_id, the X part
				// $optionPrimaryId is: job_id, the Y part
				// and TblCompanyJob is : the XY relationship.
				//
				$inst = new TblCompanyJob;
				$inst->company_id = $masterPrimaryId;
				$inst->job_id = $optionPrimaryId;
				if($inst->insert()){
					return $inst->id;
				}
				else
				return null;
			}
			public function eyuirelation_remove($widgetid,$primaryId){
				$inst = self::findByPk($primaryId);
				if($inst != null)
					if($inst->delete())
						return true;
				return false;
			}
			// must return a CHtml::listData
			public function eyuirelation_listData($widgetid,$masterPrimaryId){
				$models = self::model()->findAllByAttributes(array('company_id'=>$masterPrimaryId));
				$items = array();
				foreach($models as $model)
					$items[$model->id] = $model->job->name;
				return $items;
			}
			...
		 }
	?>
~~~

~~~
	// put this widget in a form:
	<?php 
		$this->widget('ext.eyui.EYuiRelation'
			,array(
				'id'=>'jobrelationship', // important !
				'model'=>$model,	// model is an instance of a selected TblCompany
				'title'=>'Jobs:<hr/>',
				'optionsClassName'=>'TblJob',
				'relationClassName'=>'TblCompanyJob',
			)
		);
	?>
~~~


	
##EYuiForm

![EYuiForm screenshot](https://bitbucket.org/christiansalazarh/eyui/downloads/eyuiform.gif "EYuiForm Screenshot")

EYuiForm allows you to handle large forms, those having houndred of fields, a "hard time consuming task" if you decide to design it by hand.

With EYuiForm you only pass a field structure organized by: pages, groups, fields and validation rules, next the widget cover the rest: it automatically builds the form in tabs or accordion and validates the user input (and storage, using EYuiFormDb as model). EYuiForm will handle field validation using regexp patterns, required flag and extra validation provided by you in your own model.

One of the most important things in EYuiForm is the storage: It will storage your user field entries automatically in one single table. For this specific task i build: EYuiFormDb component, it will handle the storage for you using -vertical storage- (in vertical storage each -field- is stored in a database as a -single row entry-, please no more 500 fields in one single row anymore...).  Don't worry if you doesnt like vertical storage, you can avoid EYuiFormDb and you can store all the fields as you like in your own model as traditional way.

The next code fragment show you the table required to store a very big form in your own model, please note the usage of BLOB fields, you can optimize it to feet your needs, but basically by default it works fine for very large forms. (EYuiFormDb will handle this data model automatically for you when you pass an instance of EYuiFormDb in widget configuration, is very simple, dont worry about this).


###EYuiForm sql script required

~~~
CREATE TABLE `eyuiformdb` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `model_id` INTEGER NOT NULL,
  `form_id` VARCHAR(45) NOT NULL,
  `field_name` VARCHAR(45) NOT NULL,
  `field_value` BLOB,
  INDEX `eyuiformdb_index` (`model_id` ASC,`form_id` ASC,`field_name` ASC) ,
  PRIMARY KEY (`id`)
 );
~~~ 

###EYuiForm Implementation

First of all a full example is provided in the demo section of this EYui package, please look it at
[Demo](https://bitbucket.org/christiansalazarh/eyuidemo "demo").


1) Using the widget in your own view:

~~~
<?php 
	$this->widget('ext.eyui.EYuiForm',array(
		'id'=>'form1',
		'label'=>'A Big Form',
		'model'=>$validationModel,
		'showErrorResume'=>false,
		//'themeUrl'=>'themes',
		//'theme'=>'redmond',
		'jQueryUiEnabled'=>true,
		'jQueryControl'=>'tabs', // tabs or accordion
		'jQueryGroupControl'=>'', // use 'accordion' or leave blank
		'descriptionLocation'=>'title', // field description location: 'title' or 'visible'
		
		// fielddes are field definitions, used later in each form field when needed.
		'fielddefs'=>array(
			'f1'=>array(
				// a specific pattern number
				'uicomponent'=>'textbox',
				'pattern'=>'/^([0-9]{5,8})$/',
				'patternMessage'=>'please review this field',
				'htmlOptions'=>array('maxlength'=>'5'),
				'help'=>'only 5 to 8 digits only',
			),
			'f2'=>array(
				// a simple name or lastname text box
				'uicomponent'=>'textbox',
				'pattern'=>'/^([A-Za-z]{3,10})$/',
				'help'=>'escriba solo letras',
				'htmlOptions'=>array('size'=>10,'maxlength'=>10),
			),
			'f3'=>array(
				// a text area field
				'uicomponent'=>'textarea',
				'pattern'=>'',
				'htmlOptions'=>array('rows'=>5,'cols'=>30),
			),
			'f4'=>array(
				// a combo box
				'uicomponent'=>'combobox',
				'prompt'=>array(0=>'-please select-'),
				'options'=>array(1=>'red',2=>'yellow',3=>'black'),
			),
			'f5'=>array(
				// simple boolean checkbox
				'uicomponent'=>'checkbox',
			),
		),// fielddefs
		
		
		// now, create pages and groups inside each page, finally field must be inserted into groups
		//
		'pages'=>array(
		
			
			'page1'=>array(	
				'label'=>'About You',
				'descr'=>'description for page 1',
				'groups'=>array(
					'group1'=>array(
						'label'=>'Your Basic Information',
						'descr'=>'descripcion aqui',
						'fields'=>array(
							'anynumber'=>array(
								'field'=>'f1','label'=>'Any Number',
								'required'=>true,'descr'=>'please write any number here',
								'default'=>'',
								'htmlOptions'=>array('style'=>'width: 100px; color: blue;'),
								//'help'=>'override field help using this field',
								),
							
							// using separators, must be named as separator1..2 etc
							'separator1'=>'<hr/>', 
							
							'firstname'=>array(
								'field'=>'f2','label'=>'First Name'	,
								'required'=>true,'descr'=>'Your First Name','default'=>''),
							'lastname'=>array(
								'field'=>'f2','label'=>'Last Name'	,
								'required'=>true,'descr'=>'Your Last Name','default'=>''),
							
							// using separators, must be named as separator1..2 etc
							'separator2'=>'<hr/>', // using separators, must be named as separator1..2 etc
							
							'lastname2'=>array(
								'field'=>'f2','label'=>'Last Name 2'	,
								'required'=>false,'descr'=>'Your Last Name','default'=>''),
							
						),
					),
					'group2'=>array(
						'label'=>'More About You',
						'descr'=>'please write more about you',
						'fields'=>array(
							'animals1'=>array(
								'field'=>'f5','label'=>'Do you like dogs ?',
								'required'=>false,'descr'=>'please specify'),
							'animals2'=>array(
								'field'=>'f5','label'=>'Do you like cats ?',
								'required'=>false,'descr'=>'please specify'),
							'colors'=>array(
								'field'=>'f4','label'=>'What is your preferred color ?',
								'required'=>false,'descr'=>'please specify'),
						),
					),
				),
			),
			

			
			'page2'=>array(	
				'label'=>'About Your Parents',
				'descr'=>'description for page 2',
				'groups'=>array(
					'group1'=>array(
						'label'=>'Your Mother',
						'descr'=>'descripcion aqui',
						'fields'=>array(
							// please note: 'firstname' and 'lastname' are currently in use
							// many time in this form, EYuiForm will threat it as different
							// fields, dont worry about specify different field names.
							'firstname'=>array(
								'field'=>'f2','label'=>'First Name'	,
								'required'=>true,'descr'=>'Your Mother First Name','default'=>''),
							'lastname'=>array(
								'field'=>'f2','label'=>'Last Name'	,
								'required'=>true,'descr'=>'Your Mother Last Name','default'=>''),
						),
					),
					'group2'=>array(
						'label'=>'Your Father',
						'descr'=>'descripcion aqui',
						'fields'=>array(
							// please note: 'firstname' and 'lastname' are currently in use
							// many time in this form, EYuiForm will threat it as different
							// fields, dont worry about specify different field names.
							'firstname'=>array(
								'field'=>'f2','label'=>'First Name'	,
								'required'=>true,'descr'=>'Your Father First Name','default'=>''),
							'lastname'=>array(
								'field'=>'f2','label'=>'Last Name'	,
								'required'=>true,'descr'=>'Your Father Last Name','default'=>''),
						),
					),
				),
			),
			
			
		),// end pages
	));
?>
~~~


2) Launching the action from any controller:

		please note here:
		
		If you decide not use EYuiFormDb then use:
			$this->render('eyuiform',array('model'=>YourClass::model()));
			
		but in YourClass.php you must implement:
			EYuiFormIStorage.php and EYuiFormIValidator.php
		this two interfaces ensure YourClass has the appropiated required methods
		to work togheter with EYuiForm.
~~~
public function actionEYuiForm(){

	// TblUser will provide extra validation for your form
	// in order to do that, it must implements: EYuiFormIValidator
	//
	$user = TblUser::model()->findByPk(1);
	$this->render('eyuiform'
		,array('user'=>$user,'validationModel'=>EyuiFormDb::newModel($user)));
}
~~~


3) In your model, for specific model validation.
~~~
class TblUser extends CActiveRecord
	implements EYuiFormIValidator
{
	public function eyuiform_getPrimaryId(){ 
		return $this->getPrimaryKey();
	}
	public function eyuiform_validateField($formId,$fieldId,$fullFieldName,$fieldValue,$oldValue){
		if(($formId=='form1') && ($fullFieldName=='page1_group1_anynumber') && ($fieldValue=='99999'))
			return array('result'=>false,'message'=>'please dont use 99999 (sample validation test rule)');
		return array('result'=>true,'message'=>'all is fine by now..!');
	}
	
	// this method (demostrative only) shows you the usage when you need access to stored fields:
	//
	public function getNames(){
		$firstname = EYuiFormDb::getFieldValue($this,'form1','page1_group1_firstname');
		$lastname = EYuiFormDb::getFieldValue($this,'form1','page1_group1_lastname');
		$names = ucwords(trim($firstname." ".$lastname));
		if($names == '')
			return "Unnamed Contact";
		return $names;
	}
	
	...
}	
~~~

###How to access the field values from your application ?
~~~
class TblUser extends CActiveRecord
	implements EYuiFormIValidator
{
	...another required method for interface EYuiFormIValidator...
	
	public function getNames(){
		$firstname = EYuiFormDb::getFieldValue($this,'form1','page1_group1_firstname');
		$lastname = EYuiFormDb::getFieldValue($this,'form1','page1_group1_lastname');
		$names = ucwords(trim($firstname." ".$lastname));
		if($names == '')
			return "Unnamed Contact";
		return $names;
	}
	
	...
}	
~~~

##EYuiFormEditor

Is an ajax-jquery user interface field editor for EYuiForm, it helps you to create pages, groups and fields in a visual way.

![EYuiFormEditor screenshot](https://bitbucket.org/christiansalazarh/eyui/downloads/eyuiformeditor.gif "EYuiFormEditor Screenshot")

###Launching the Form Editor:

1. create a table in your database using: 
	eyuiformeditordb-mysql-schema.sql

2. insert the widget in your view.
~~~
$this->widget('ext.eyui.EYuiFormEditor',array(
	'model'=>EYuiFormEditorDb::model('any_model_id',"form1"),
));
~~~

###EYuiFormEditor Details:

The widget is created based on a data model. This data model by is a class who implements a EYuiFormEditorIStorage, the class EYuiFormEditorDb is provided for this task.  This class receive two arguments:
	a) model_id. this argument identifies your form from others stored in the same data source.
	b) form_id.	In conjuntion with model_id, this argument can be: 'form1' or 'form2' etc..

Suppose you have an Organization, this organization use forms handled by EYuiForm, but the same organizacion will have one, two or more forms..(the form_id argument), but, the form1 for organization1 is a different one than form1 for organization2, So organization 1 and 2 will have its own model_id.
	
The data management is very simple, is managed by EYuiFormEditorDb, it uses a single table (see eyuiformeditordb-mysql-schema.sql) for form structure storage.  You can use your own data source implementing a EYuiFormEditorIStorage interface.

##EYuiFormEditor sql script

Please look at:  

  eyuiformeditordb-mysql-schema.sql
	
###Using EYuiFormEditor togheter with an existing EYuiForm widget:
	
In previous EYuiForm documentation i describe a setup based config for EYuiForm widget using an array of pages and fielddefs to render it.  Now using EYuiFormEditor you dont need the 'fielddef' widget argument, only 'pages', as this example does:

~~~
<?php 
	$this->widget('ext.eyui.EYuiForm',array(
		'id'=>'form1',
		'label'=>'A Big Form',
		'model'=>$validationModel,
		'showErrorResume'=>false,
		'jQueryUiEnabled'=>true,
		'jQueryControl'=>'tabs', // tabs or accordion
		'jQueryGroupControl'=>'', // use 'accordion' or leave blank
		'descriptionLocation'=>'title', // field description location: 'title' or 'visible'
		//
		// Using EYuiFormEditorDb stored fields: 
		//
		//	it will load all fields, groups and pages for model: 'any_model_id' and 'form1'
		//	
		'pages'=>EYuiFormEditorDb::model('any_model_id',"form1"),
	));
?>
~~~

##EYuiFormDataExport

EYuiFormDataExport exports data in CSV format, this data is obtained using EYuiForm. it connects to EYuiFormDb and EYuiFormEditorDb (or any class created by you wich implements the required interfaces).

The usage is very simple, as this action demostrate: (please refer to demo)

~~~
<?php 
	public function actionDataExport($userid=null) {
		
		// 'mymodelid' is referenced in: eyuidemo\protected\views\example\eyuiform2.php
		
		// a writeable directory for tmp files
		$tmp_folder = 'assets/';
		
		// 1. load the form structure (pages, groups and fields)
		$exporter = EYuiFormDataExport::newModel(
			EYuiFormEditorDb::model('mymodelid','form1'));

		// 2. build the required CSV files (empty files, only column definition)
		$exporter->prepareFiles($tmp_folder);
		
		if($userid == null){
			//	3.a) export records for each user
			foreach(TblUser::model()->findAll() as $user)
				$exporter->insertModel(EYuiFormDb::newModel($user),'form1');
		}
		else{
			//	3.b) export records for selected user
			$user = TblUser::model()->findByPk($userid);
			$exporter->insertModel(EYuiFormDb::newModel($user),'form1');
		}
		
		// AT THIS POINT YOU MUST HAVE CSV FILES STORED IN YOUR $tmp_folder
		// EACH FILE NAME IS THE FORM PAGE NAME.
		
		
		// 4. outputs all generated csv files into a single zip.
		
		$zipName = "exported-data.zip";
		$destZip = $tmp_folder.$zipName;
		$zip = new ZipArchive;
		if ($zip->open($destZip,ZIPARCHIVE::OVERWRITE) === TRUE){
			// exporter will return the generated filenames 
			foreach($exporter->getFiles() as $fname){
				$fname = utf8_decode($fname);
				$addfile = $tmp_folder.$fname;
				if(!$zip->addFile($addfile,$fname))
					throw new Exception("[error addZip: ".$addfile."]");
			}
			$zip->close();
			
			// 5. at this point you must have a zip file. now output it to browser for direct download
			//
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Transfer-Encoding: binary");
			header("Content-Disposition: attachment; filename=".$zipName);
			header("Content-Type: application/octet-stream");
			echo readfile($destZip);
		}
	}
?>
~~~
	

##Understanding The EYui Architecture

This diagrams will help you in EYui understanding, but it is not a requirement to use it, it is only needed if you decide to colaborate on it.

![EYui Class Diagrams](https://bitbucket.org/christiansalazarh/eyui/downloads/eyui-design-1.gif "EYui Class Diagrams")

![EYui Sequence Diagrams](https://bitbucket.org/christiansalazarh/eyui/downloads/eyui-design-2.gif "EYui Sequence Diagrams")