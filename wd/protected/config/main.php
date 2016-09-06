<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//Yii::setPathOfAlias('gus',dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'gustavo');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'',
		'language'=>'es',
	'sourceLanguage'=>'en',
	'charset'=>'UTF-8',
'theme'=>'starter',
	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.esearch.*',
		'application.modules.cruge.components.*',
		'application.modules.cruge.extensions.crugemailer.*',
	),

	'modules'=>array(
			'cruge'=>array(
				'tableprefix'=>'cruge_',
				'availableAuthMethods'=>array('default'),
				'availableAuthModes'=>array('username','email'),
				'baseUrl'=>'http://localhost/wcalificaciones/wd',
				 'debug'=>false,
				 'rbacSetupEnabled'=>false,
				 'allowUserAlways'=>false,
				'useEncryptedPassword' => true,
				'hash' => 'md5',
				// a donde enviar al usuario tras iniciar sesion, cerrar sesion o al expirar la sesion.
				'afterLoginUrl'=>null,
				'afterLogoutUrl'=>null,
				//'afterSessionExpiredUrl'=>null,
				'afterSessionExpiredUrl'=>array('/wcalificaciones/'),
				// manejo del layout con cruge.
				'loginLayout'=>'//layouts/column2',
				'registrationLayout'=>'//layouts/column2',
				'activateAccountLayout'=>'//layouts/column2',
				'editProfileLayout'=>'//layouts/column2',
				'generalUserManagementLayout'=>'ui',
			),
			
			),
	// application components
	'components'=>array(
	//bootstrap
	'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'ePdf' => array(
        'class'         => 'ext.yii-pdf.EYiiPdf',
        'params'        => array(
            'mpdf'     => array(
                'librarySourcePath' => 'application.vendors.mpdf.*',
                'constants'         => array(
                    '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                ),
                'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
                'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                    'mode'              => '', //  This parameter specifies the mode of the new document.
                    'format'            => 'A4', // format A4, A5, ...
                    'default_font_size' => 0, // Sets the default document font size in points (pt)
                    'default_font'      => '', // Sets the default font-family for the new document.
                    'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                    'mgr'               => 15, // margin_right
                    'mgt'               => 16, // margin_top
                    'mgb'               => 16, // margin_bottom
                    'mgh'               => 9, // margin_header
                    'mgf'               => 9, // margin_footer
                    'orientation'       => 'P', // landscape or portrait orientation
                )
            ),
               ),
    ),
//  IMPORTANTE:  asegurate de que la entrada 'user' (y format) que por defecto trae Yii
			//               sea sustituida por estas a continuación:
				'user'=>array(
				'allowAutoLogin'=>true,
				'class' => 'application.modules.cruge.components.CrugeWebUser',
				'loginUrl' => array('/cruge/ui/login'),
			),
			'authManager' => array(
				'class' => 'application.modules.cruge.components.CrugeAuthManager',
			),
			'crugemailer'=>array(
				'class' => 'application.modules.cruge.components.CrugeMailer',
				'mailfrom' => 'webmaster2d@gmail.com',
				'subjectprefix' => 'Bienvenido a wcalificaciones ',
				'debug' => true,
			),
			'format' => array(
				'datetimeFormat'=>"d M, Y h:m:s a",
			),
		
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix'=>'.do',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// ==================================================================================================================================================
	// Configura tus datos de conexion a tu base de datos ejemplo: 'connectionString' => 'mysql:host=localhost;dbname=wcalificaciones',
			'db'=>array(
			'connectionString' => 'mysql:host=localhost;port=33060;dbname=wcalificaciones',
			'emulatePrepare' => true,
			'username' => 'wcalificaciones',
			'password' => 'wcalificaciones',
			'charset' => 'utf8',
		),
// ==================================================================================================================================================
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
							),
		),
		// cache
		'cache'=>array(
        'class'=>'system.caching.CFileCache',
    ),
		
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// Coloca aqui tu email de contacto: ejemplo: 'adminEmail'=>'webmaster2d@gmail.com',
		'adminEmail'=>'webmaster2d@gmail.com',
	),
);