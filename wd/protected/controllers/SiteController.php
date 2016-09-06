<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		'search'=>array('class'=>'ext.esearch.SearchAction','model'=>'Notas','attributes'=>array('codalumno'),),

		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	/*mostrar*/
		//public function loadModel($id)
		public function loadModel($id)
	{
		$model=Notas::model()->find('codalumno=:id', array(':id'=>$id));
		//$model=Alumnos::model()->findByPk($id);
		//$model=Notas::model()->findByPk($id);
		//$model=Notas::model()->find($id);
			
		if($model===null)
			throw new CHttpException(404,'La web solicitada no ha sido encontrada');
		return $model;
	}
	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	//Imprimir
	
		public function actionImprimir($id)
	{
		$this->layout="//layouts/imprimir";
		$this->render('imprimir',array(
			'model'=>$this->loadModel($id),
		));
	}
	/*
//pasamos parámetros opcionales que llegan por la URL-imprimir con parametros
    public function actionImprimir($id=null,$id2=null,$id3=null){
          $bruce  = "Parámetro 1: ".$id."<br/>";
          $bruce .= "Parámetro 2: ".$id2."<br/>";
          $bruce .= "Parámetro 3: ".$id3."<br/>";
                 
         //Renderizamos la vista
         $this->layout="//layouts/imprimir";
		$this->render('imprimir',array(
			'model'=>$this->loadModel($id),
		));
    }
*/
	
	//vistaprevia
		public function actionVistaprevia($id)
	{
			$this->render('imprimir',array(
			'model'=>$this->loadModel($id),
		));
	}
	
		public function actionReporte($id)
	{
		$this->render('reporte',array(
			'model'=>$this->loadModel($id),
			//'model'=>with('alumnos','notas')->findAll();
		));
	//$a_criteria = new CDbCriteria...
//$b_criteria = new CDbCriteria...
//$a = MyModel::model()->find($a_criteria);
//$b = MyOtherModel::model()->find($b_criteria);
/*$a = Alumnos::model()->findByPk($id);
//$b = Notas::model()->findByPk($id);
$this->render('reporte', array(
        'model'=>$a,
       // 'modelnotas'=>$b,
    ));/*
	//$a = Alumnos::model()->findByPk($id)->with('notases')->findAll();
	$a =Alumnos::model()->findByPk($id);
	$this->render('reporte', array(
        'model'=>$a,
    ));*/
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
/*--------acciones pdf------------*/
	public function actionViewpdf()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('viewpdf');
	}
	public function actionPdf($id)
{
$this->render('pdf',array(
'model'=>$this->loadModel($id),
));
}

public function actionExportar()
{
$dataProvider=new CActiveDataProvider('Notas');
				# mPDF
	$this->layout="//layouts/pdf";
        $mPDF1 = Yii::app()->ePdf->mpdf();
$mPDF1->WriteHTML($this->render('view',array(
			'dataProvider'=>$dataProvider,
		),true)
		);
$mPDF1->Output('reporte_alumnos.pdf',EYiiPdf::OUTPUT_TO_DOWNLOAD);

}
}