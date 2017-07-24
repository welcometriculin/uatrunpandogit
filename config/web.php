<?php

$params = require(__DIR__ . '/params.php');

$config = [
	'timezone' => 'Asia/Kolkata',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
		'aliases' => [
				//'@foo' => '/path/to/foo',
				'@weburl' => 'http://uat.runpando.com',    // for local landing page images
				//'@weburl' => 'http://kisangatesqa.raybiztech.net/web', // for qa landing page images
				//'@weburl' =>'http://kisangates.raybiztech.net/web' ,   // for uat landing page images
				//'@weburl' =>'http://107.167.176.221/kisangates/web' ,   // for uat landing page images qa
				//'@weburl' =>'http://107.167.176.221/kisangatesproduction/web' , // for uat landing page images production
				'@imageurl' => 'http://uat.runpando.com' ,      //for local plancard activity images
				//'@imageurl'  => 'http://kisangatesqa.raybiztech.net/', //for  qa plancard activity images
				//'@imageurl' =>  'http://kisangates.raybiztech.net/', //for uat plancard activity images
				//'@imageurl' =>  'http://107.167.176.221/kisangates/', //for uat plancard activity images qa
				//'@imageurl' =>  'http://107.167.176.221/kisangatesproduction/', //for uat plancard activity images production
		],
	
    'components' => [
    	'assetManager' => [
    'bundles' => [
        'yii\bootstrap\BootstrapAsset' => [
            'css' => [],
        ],

    ],
],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'kisangates',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
    	'session' => [
    		'class' => 'yii\web\Session',
    		//'name' => 'PHPFRONTSESSID',
    		'savePath' => sys_get_temp_dir(),
    		//'timeout'	=> 10,
    	],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'guid'=> ['class'=>'app\components\GUID'],
        'sms'=> ['class'=>'app\components\SMS'],
    	'commonmethods' => ['class'=>'app\components\CommonMethods'],	
        /* for pretty url settings*/
        'urlManager' => [
	        'enablePrettyUrl' => true,
	        'showScriptName' => true,
	        'enableStrictParsing' => false,
		        'rules' => [
		        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
		        '<controller:\w+>/<action:\w+>/<id:[\w-]+>' => '<controller>/<action>',
		        '<controller:\w+>/<action:\w+>/<id:[\w-]+>/<date:[\w-]+>' => '<controller>/<action>',//for travellog/view
		        //'<controller:\w+>/<action:\w+>/<changeid:\d+>/<id:\d+>' => '<controller>/<action>',//userscontroller
		        '<controller:\w+>/<action:\w+>/<changeid:\d+>/<id:\d+>/<employee_role:\d+>' => '<controller>/<action>',//userscontroller
		        '<controller:\w+>/<action:\w+>/<roleid:\d+>/<controllername:\w+>' => '<controller>/<action>',//roleprivilegemapcontroller
		        '<controller:\w+>/<action:\w+>/<controllername:\w+>/<id:\d+>' => '<controller>/<action>',//roleprivilegemapcontroller
		        '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
		        		],
        ],
        'metadata'=> ['class'=>'app\components\Metadata'],
        /* for pretty url settings*/
	/*swift mailer*/
      'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
          	'transport' => [
	            'class' => 'Swift_SmtpTransport',
	            'host' => 'smtp.gmail.com',  
	            'username' => 'pandohelpdesk@gmail.com',
	            'password' => 'pando@123',
	            'port' => '587', 
	            'encryption' => 'tls', 
        	],
        ],
	/*swift mailer*/
	/*zyxPHPmailer*/
	/*'mail' => [
		    'class'            => 'zyx\phpmailer\Mailer',
		    //'viewPath'         => '@common/mail',
		    'useFileTransport' => false,
		    'config'           => [
		        'mailer'     => 'smtp',
		        'host'       => 'smtp.gmail.com',
		        'port'       => '465',
		        'smtpsecure' => 'ssl',
		        'smtpauth'   => true,
		        'username'   => 'raheem415415@gmail.com',
		        'password'   => 'RaheemRaheem',
		    ],
		],*/
	/*zyxPHPmailer*/
	/*PHPmailer*/
		/*'smtpmail'=> [
    				'class'=> 'PHPMailer',
				    'Mailer'=>'smtp',
				    'SMTPAuth'=>true,  
    				'Host'=>'smtp.gmail.com',
				    'Port'=> '587',
    				'Username'=>'pandohelpdesk@gmail.com',
    				'Password'=>'pando@123',    				
					//'From' => 'pradeepraj.y@kisangates.com',
    				'SMTPSecure' => 'tls',    				
    	],*/
    	'smtpmail'=> [
    	'class'=> 'PHPMailer',
    	'Mailer'=>'smtp',
    	'SMTPAuth'=>true,
    	'Host'=>'email-smtp.us-east-1.amazonaws.com',
    	'Port'=> '25',
    	'Username'=>'AKIAINQSJTFKGMNUO72A',
    	'Password'=>'AlnAGgfDkFKcSRliaofWUEwZagGxIQFKs6XCk4LQoVmv',
    	//'From' => 'contact@greenscoutsofamerica.com',
    	'SMTPSecure' => 'tls',
    	],
	/*PHPmailer*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            /*  'email' => [
                'class' => 'yii\log\EmailTarget',
                'levels' => ['error'],
                'message' => [
                'from' => ['pandohelpdesk@gmail.com'],
                'to' => [],
                'subject' => 'web-Rasi-RunPando: Database errors at Kisangates',
                ],
              ], */
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
