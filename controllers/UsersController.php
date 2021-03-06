<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use app\models\Roles;
use app\models\Designations;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends KgController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$actioncolumns = $this->accessindexactioncolumns();
    	$linkactions = $this->accesslinkactions();
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //for bulk upload users start
        $model = new Users();
        $kgadmin_role_id = Roles::KGADMIN;
		$icadmin_role_id = Roles::ICADMIN;
		$manager_role_id = Roles::MANAGER;
		$ffofficer_role_id = Roles::FIELDFORCE;
        $message = '';
        $finalData = '';
        if (Yii::$app->request->isPost) {
			$model->bulkfile = UploadedFile::getInstance($model, 'bulkfile');
        	$session = Yii::$app->session;
        	\Yii::$app->session->set('csvfile',$model->bulkfile);
        	$model->bulkfile->saveAs(Yii::getAlias('@webroot').'/import/'.$model->bulkfile);
        	$csv = \Yii::$app->session->get('csvfile');
        	$inputFile = '../web/import/'.$csv;
        	try {
        		$inputFileType = \PHPExcel_IOFactory::identify($inputFile);
        		$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        		$objPHPExcel = $objReader->load($inputFile);
        	} catch (Exception $e) {
        		die('error');
        	}
        	$sheet = $objPHPExcel->getSheet(0);
        	$highestRow = $sheet->getHighestRow();
        	$highestColumn = $sheet->getHighestColumn();
        	$ffofficer_empids = array();
        	for ($row = 2; $row <= $highestRow; $row++) {
        		$rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
        		$model = new Users();
        		$finalData[$row]['first_name'] = $rowData[0][1];
        		$finalData[$row]['employee_number'] = $rowData[0][2];
        		$finalData[$row]['phone_number'] = $rowData[0][3];
        		$finalData[$row]['email_address'] = strtolower($rowData[0][4]);
        		$finalData[$row]['roleid'] = $rowData[0][5];
        		$finalData[$row]['reporting_user_id'] = $rowData[0][6];
        		$finalData[$row]['input_company_id'] = Yii::$app->user->identity->input_company_id;
        		$password = rand(1000, 10000);
        		$finalData[$row]['password'] = md5($password);
        		$key = rand(1000,10000);
        		$access_token = md5($model->email_address.$key);
        		$finalData[$row]['access_token'] = $access_token;
        		$bulk_mails[$rowData[0][4]] = $password;
        		if ($rowData[0][5] == $manager_role_id) {
        			$reporting_manager_empids[] = $rowData[0][6];
        		}
        		if ($rowData[0][5] == $ffofficer_role_id) {
        			$ffofficer_empids[] = $rowData[0][2];
        		}
        	}
        	$arraay_emails = $model->bulkemailunique();
        	$array_phone_numbers = $model->bulkphoneunique();
        	$array_employee_numbers = $model->bulk_employeeid_unique();
        	$array_reporting_user_employee_ids = $model->bulk_reporting_user_employee_ids();
//         	if (!empty($array_reporting_user_employee_ids[3])) {
//         		$array_manager_empids = explode(',', $array_reporting_user_employee_ids[3]);
//         	} else {
//         		$array_manager_empids = array();
//         	}
        	if (!empty($array_reporting_user_employee_ids[4])) {
        		$array_ffofficer_empids = explode(',', $array_reporting_user_employee_ids[4]);
        	} else {
        		$array_ffofficer_empids = array();
        	}
        	$check = 1;
        	$length = count($finalData)+2;
        	for($i = 2;$i < $length;$i++){
        		$first_name = trim($finalData[$i]['first_name']);
        		$employee_id = trim($finalData[$i]['employee_number']);
        		$phone_number = trim($finalData[$i]['phone_number']);
        		$email_id = trim($finalData[$i]['email_address']);
        		$role_id = trim($finalData[$i]['roleid']);
        		$reporting_user_id = trim($finalData[$i]['reporting_user_id']);
        		if ($role_id == $kgadmin_role_id) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-kgadmin-roleid');
        			break;
        		}
        		if ($role_id == $icadmin_role_id) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-icadmin-roleid');
        			break;
        		}
        		if ($role_id > $ffofficer_role_id) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-roleid-notmatch');
        			break;
        		}
        		if (!$first_name || !$employee_id || !$phone_number || !$email_id || !$role_id || !$reporting_user_id) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-spaces-occured');
        			break;
        		}
        		if (!preg_match("/^[0-9]+$/",$finalData[$i]['employee_number'])) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('employee-number-format');
        			break;
        		}
        		if (!preg_match("/^[0-9]{10}$/",$finalData[$i]['phone_number'])) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('phone-number-format');
        			break;
        		}
        		if (!filter_var($finalData[$i]['email_address'], FILTER_VALIDATE_EMAIL)) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('not valid email address');
        			break;
        		}
        		if ($finalData[$i]['first_name'] == "" || $finalData[$i]['employee_number'] == "" || $finalData[$i]['email_address'] == "" || $finalData[$i]['roleid'] == "" || $finalData[$i]['reporting_user_id'] == "") {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-empty');
        			break;
        		}
        		if (in_array($finalData[$i]['email_address'], $arraay_emails)) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-email-exist');
        			break;
        		}
        		if (in_array($finalData[$i]['phone_number'], $array_phone_numbers)) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-phone-number-exist');
        			break;
        		}
        		if (in_array($finalData[$i]['employee_number'], $array_employee_numbers)) {
        			$check = 0;
        			$flash = Yii::$app->session->setFlash('bulkfields-employee_number-exist');
        			break;
        		}
//         		if ($finalData[$i]['roleid'] == $manager_role_id) {
//         			if (in_array($finalData[$i]['employee_number'], $reporting_manager_empids) || in_array($finalData[$i]['reporting_user_id'], $array_manager_empids)) {
//         				$check = 0;
//         				$flash = Yii::$app->session->setFlash('bulkfields-same-employeeids');
//         				break;
//         			}
//         		}
        		if ($finalData[$i]['roleid'] == $manager_role_id) {
        			if ($finalData[$i]['employee_number'] == $finalData[$i]['reporting_user_id']) {
        				$check = 0;
        				$flash = Yii::$app->session->setFlash('bulkfields-same-employeeids');
        				break;
        			}
        		}
        		if ($finalData[$i]['roleid'] == $manager_role_id) {
        			if (in_array($finalData[$i]['reporting_user_id'], $ffofficer_empids) || in_array($finalData[$i]['reporting_user_id'], $array_ffofficer_empids)) {
        				$check = 0;
        				$flash = Yii::$app->session->setFlash('bulkfields-manager-ff');
        				break;
        			}
        		}
        		if ($finalData[$i]['roleid'] == $ffofficer_role_id) {
        			if (in_array($finalData[$i]['reporting_user_id'], $ffofficer_empids) || in_array($finalData[$i]['reporting_user_id'], $array_ffofficer_empids)) {
        				$check = 0;
        				$flash = Yii::$app->session->setFlash('bulkfields-ff-ff');
        				break;
        			}
        		}
        		for($j = 2;$j < $length;$j++){
        			if ($i != $j) {
        				if (in_array($finalData[$i]['email_address'], $finalData[$j])) {
        					$check = 0;
        					$flash = Yii::$app->session->setFlash('bulkfields-duplicate-email');
        					break;
        				}
        				if (in_array($finalData[$i]['phone_number'], $finalData[$j])) {
        					$check = 0;
        					$flash = Yii::$app->session->setFlash('bulkfields-duplicate-phone-number');
        					break;
        				}
        				if ($finalData[$i]['employee_number'] == $finalData[$j]['employee_number']) {
        					$check = 0;
        					$flash = Yii::$app->session->setFlash('bulkfields-duplicate-employeeids');
        					break;
        				}
        			}
        		}
        	}
        	if ($check == 1) {
        		for($z = 2; $z < $length; $z++){
        			$model = new Users();
        			$model->attributes = $finalData[$z];
        			$model->save(false);
        		}
        		$last_inserted_row_id = Yii::$app->db->getLastInsertID();
        		$firstId = $last_inserted_row_id;
        		$rowCnt = count($finalData);
        		for($ids = 0; $ids < $rowCnt; $ids++){
        			$update = "UPDATE users u
    						JOIN users uu ON u.reporting_user_id = uu.employee_number
    						SET u.reporting_user_id = uu.id
    						WHERE u.id = '".$firstId."'
    						AND uu.input_company_id = '".Yii::$app->user->identity->input_company_id."'";
        			$sql_update = Yii::$app->db->createCommand($update)->execute();
        			$update_manager_status = "UPDATE users 
    										SET status = 'active'
    										WHERE id = '".$firstId."'
    										AND roleid = '".$manager_role_id."'
    										AND input_company_id = '".Yii::$app->user->identity->input_company_id."'";
        			$sql_update_status = Yii::$app->db->createCommand($update_manager_status)->execute();
        			$firstId--;
        		}
        		$mail = $model::bulkEmail($bulk_mails);
        		$flash = Yii::$app->session->setFlash('bulkfields-success');
        	}
        	$inputFile = '../web/import/'.$csv;
        	unlink('../web/import/'.$csv);
        	return $this->refresh();
        }
        //for bulk upload users end
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'actioncolumns'=> $actioncolumns,
        	'linkactions' => $linkactions,
        	'model' => $model,
        ]);
      
    }

    /**
     * Displays a single Users model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$linkactions = $this->accesslinkactions();
        return $this->render('view', [
            'model' => $this->findModel($id),
        	'linkactions' => $linkactions,
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        // && $model->employeenameUnique() for unique first name
        $ffofficer_role_id = Roles::FIELDFORCE;
        $manager_role_id = Roles::MANAGER;
        $ic_admin_role_id = Roles::ICADMIN;
        $designations = Designations::designationList('create');
        if ($model->load(Yii::$app->request->post()) && $model->emailunique() && $model->phone_number_unique() && $model->employeeid_unique()) 
        {
        	if ($_POST['save'] == 'saved') {
        		
        	}
        	else {
        		$model->status = 'active';
        	}
//         	$password = rand(1000, 10000);
//         	$model->password = md5($password);
        	$password = 'Goodluck@123';
        	$model->password = md5($password);
        	$model->email_address = strtolower($model->email_address);
        	$model->input_company_id = Yii::$app->user->identity->input_company_id;
        	$key = rand(1000,10000);
        	$access_token = md5($model->email_address.$key);
        	$model->access_token = $access_token;
        	/*upload photo*/
        	$model->photo = UploadedFile::getInstance($model, 'photo');
        	if ($model->photo != ''){
	        	$model->photo->name = $model->employee_number.'.'.$model->photo->extension;
	        	$model->photo->saveAs(Yii::getAlias('@webroot').'/user_images/'.$model->photo);
	        	$model->photo_path = 'user_images/';
        	}
        	else{
        		$model->photo = '';
        		$model->photo_path = '';
        	}
        	/*upload photo*/
        	if ($model->save(false)) {
        		if ($_POST['save'] == 'saved' || $_POST['save'] == 'saveactivate') {
        			$template = 'Greetings from Kisangates!<br /></br />';
        			if ($_POST['save'] == 'saved') {
        				$subject = 'User Creation';
        				$template .= '       Your account created successfully. <br> You can login into your account by using below credentials, once your status is activated. <br>Username: '.$model->email_address. '<br> Password: '.$password.'<br><br>Thank You, <br />Pando Support Team,<br /><a href ="http://www.runpando.com">www.runpando.com</a>';
        			} else {
        				$subject = 'Pando Activation';
        				if ($model->roleid == $manager_role_id || $model->roleid == $ic_admin_role_id) {
        				$template .= '       <br />Your account is activated successfully. <br><br> You can login into your account by using below credentials. <br /> <br />Username: '.$model->email_address. '<br> Password: '.$password.'<br><br>Thank You, <br />Pando Support Team,<br /><a href ="http://www.runpando.com">www.runpando.com</a>';
        				}
        				if ($model->roleid == $ffofficer_role_id) {
        					$template .= "Your Pando account has been activated successfully.<br><br>
        							Kindly download the app from Google Play Store or click the link: <a href = 'https://play.google.com/store/apps/details?id=com.kisangates.pando&hl=en' >https://play.google.com/store/apps/details?id=com.kisangates.pando&hl=en</a>
        							 <br /><br /><b>Sign-in with the below credentials.</b><br /><br /> \n Username: ".$model->email_address. "<br />Password: ".$password."<br /><br />Please feel free to contact <a href = 'mailto:pandosupport@kisangates.com'>pandosupport@kisangates.com</a>
										for any support.<br /><br />
        							 Thank you,  <br /> Pando Support Team, <br /> <a href ='http://www.runpando.com' >www.runpando.com</a>";
        					$message = "Your account is activated successfully. You can login to your account by using below credentials. \n Username: ".$model->email_address. "\n Password: ".$password."\n Regards, \n Kisan Gates Support Team.";
        					$smsSend = Yii::$app->sms->SmsSend($model->phone_number, $message);
        				}
        			}
	        		$mail = Yii::$app->smtpmail;
	        		$mail->setFrom(Yii::$app->params['fromEmail'],Yii::$app->params['fromName']);
	        		$mail->addAddress($model->email_address);
	        		$mail->addCc(Yii::$app->params['fromEmail']);
	        		$mail->CharSet = 'UTF-8';
	        		$mail->Subject = $subject;
	        		$mail->MsgHTML($template);
	        		if ($mail->Send()) {
	        			Yii::$app->session->setFlash('users-created');
	        		} else {
	        			Yii::$app->session->setFlash('users-created');
	        			echo "Mailer Error: " . $mail->ErrorInfo;
	        		}
        		}
            	return $this->redirect(['profile']);
        	} else {
        		print_r($model->getErrors());
        		throw new \yii\web\HttpException(500, Yii::t('yii', 'Some Internal server issue occured.'));
        	}
        } else {
            return $this->render('create', [
                'model' => $model,
            	'designations' => $designations
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$manager_role_id = Roles::MANAGER;
    	$designations = Designations::designationList('create');
    	if ($model->load(Yii::$app->request->post()) && $model->emailunique() && $model->phone_number_unique() && $model->employeeid_unique()) {
    		$model->email_address = strtolower($model->email_address);
    		if ($_POST['save'] == 'saved') {
    			$flash = Yii::$app->session->setFlash('users-updated');
			} else if ($_POST['save'] == 'saveactivate') {
    			$model->status = 'active';
    			$model->is_blocked = 0;
    			if ($model->roleid == $ffofficer_role_id || $manager_role_id) {
// 	    			$password = rand(1000, 10000);
// 	    			$model->password = md5($password);
    				$password = 'Goodluck@123';
    				$model->password = md5($password);
    			}
    			$flash = Yii::$app->session->setFlash('users-activated');
    		} else {
    			$model->status = 'inactive';
    			$model->is_blocked = 1;
    			$flash = Yii::$app->session->setFlash('users-deactivated');
    		}
    		/*upload photo*/
    		$model->photo = UploadedFile::getInstance($model, 'photo');
    		if ($model->photo != '') {
    			$model->photo->name = $model->employee_number.'.'.$model->photo->extension;
    			$model->photo->saveAs(Yii::getAlias('@webroot').'/user_images/'.$model->photo);
    			$model->photo_path = 'user_images/';
    		} else {
    			unset($model->photo);
    		}
    		/*upload photo*/
    		$sql2 = new \yii\db\Query();
    		$sql2->createCommand()->update('input_companies', ['person_name'=> $model->first_name, 'phone_number' => $model->phone_number,
    				'contact_email' => $model->email_address, 'status' => $model->status ], ['contact_email'=> $model->email_address])->execute();
    		
    		if ($model->save(false)) {
    			if ($_POST['save'] == 'saveactivate' || $_POST['save'] == 'saveinactivate') {
    				$template = 'Greetings from Kisangates! <br /><br />';
    				if ($_POST['save'] == 'saveactivate') {
    					$subject = 'Pando Activation';
    					if ($model->roleid == $manager_role_id) {
    						$template .= '       Your account is activated successfully. <br><br> You can login into your account by using below credentials. <br>Username: '.$model->email_address. '<br> Password: '.$password.'<br><br>Thank You,<br />Pando Support Team,<br /><a href ="http://www.runpando.com">www.runpando.com</a>';
    					}
//     					$template .= '     Your account is activated successfully. <br><br> You can login into your account by using your credentials.<br><br>Regards <br>Kisan Gates Support Team';
    					if ($model->roleid == $ffofficer_role_id) {
    						$template .= "Your Pando account has been activated successfully.<br><br>
        							Kindly download the app from Google Play Store or click the link: <a href = 'https://play.google.com/store/apps/details?id=com.kisangates.pando&hl=en' >https://play.google.com/store/apps/details?id=com.kisangates.pando&hl=en</a>
        							 <br /><br /><b>Sign-in with the below credentials.</b><br /><br /> \n Username: ".$model->email_address. "<br />Password: ".$password."<br /><br />Please feel free to contact <a href = 'mailto:pandosupport@kisangates.com'>pandosupport@kisangates.com</a>
									for any support.<br /><br />
        							 Thank you,  <br /> Pando Support Team, <br /> <a href ='http://www.runpando.com' >www.runpando.com</a>";
    						$message = "Your account is activated successfully. You can login to your account by using below credentials.\n Username: ".$model->email_address. "\n Password: ".$password."\n Regards,  \n Kisan Gates Support Team.";
    						$smsSend = Yii::$app->sms->SmsSend($model->phone_number, $message);
    					}
    				} else if ($_POST['save'] == 'saveinactivate') {
    					$subject = 'User Deactivation';
    					$template .= 'Your account is de-activated. <br><br> Please contact your admin.<br><br>Thank You,<br />Pando Support Team,<br /><a href ="http://www.runpando.com">www.runpando.com</a>';
    				}
    				$mail = Yii::$app->smtpmail;
    				$mail->setFrom(Yii::$app->params['fromEmail'],Yii::$app->params['fromName']);
    				$mail->addAddress($model->email_address);
    				$mail->addCc(Yii::$app->params['fromEmail']);
    				$mail->CharSet = 'UTF-8';
    				$mail->Subject = $subject;
    				$mail->MsgHTML($template);
    				if ($mail->Send()) {
    					$flash;
    				} else {
    					$flash;
    					echo "Mailer Error: " . $mail->ErrorInfo;
    				}
    			}
    			//return $this->redirect(['profile']);
    			$this->redirect(Url::previous());
    		} else {
    			print_r($model->getErrors());
    			throw new \yii\web\HttpException(500, Yii::t('yii', 'Some Internal server issue occured.'));
        	}
        } else {
            return $this->render('update', [
                'model' => $model,
            	'designations' =>$designations 	
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
    	$manager_role_id = Roles::MANAGER;
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$dependencyUserActions = Users::dependencyUserActions($id);
    	$dependencyUserCount = Users::dependencyUsers($id);
    	if ($dependencyUserActions['count'] > 0 && $dependencyUserActions['roleid'] == $ffofficer_role_id){ 
    		Yii::$app->session->setFlash('users-depend-plancard');
    	} elseif ($dependencyUserCount['count'] > 0 && $dependencyUserCount['roleid'] == $manager_role_id) {
    		Yii::$app->session->setFlash('users-depend');
    	} else {
	    	$sql = Yii::$app->db->createCommand()
			    	->update('users', ['is_deleted' => 1], ['guid' => $id])
			    	->execute();
	    	Yii::$app->session->setFlash('users-deleted');
    	}
        return $this->redirect(['profile']);
    }

    public function actionProfile()
    {
    	
    	//url remember
    	$page = Yii::$app->request->get('page');
    	$per_page = Yii::$app->request->get('per-page');
        
    	if(!$page && !$per_page) {
    		$geturl = Url::remember(['retailer/index','page'=>Yii::$app->request->get('page'),'per-page'=>Yii::$app->request->get('per-page') ],'previous');
    	}
    	$geturl = Url::remember('',null);
        
    	$designations = Designations::designationList('upload');
    	$userscount = Users::usersCount();
       
    	$actioncolumns = $this->accessindexactioncolumns();
         
    	$linkactions = $this->accesslinkactions();
        
    	$searchModel = new UsersSearch();
       
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	//for bulk upload users start
    	$user_model = new Users();
    	$model = $this->findModel(Yii::$app->user->identity->guid);
    	$kgadmin_role_id = Roles::KGADMIN;
    	$icadmin_role_id = Roles::ICADMIN;
    	$manager_role_id = Roles::MANAGER;
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$message = '';
    	$finalData = '';
    	if (isset($_POST['bulk'])) {
    		$user_model->bulkfile = UploadedFile::getInstance($user_model, 'bulkfile');
    		$user_model->bulkfile->saveAs(Yii::getAlias('@webroot').'/import/'.$user_model->bulkfile);
    		$csv = $user_model->bulkfile;
    		$inputFile = '../web/import/'.$csv;
    		try {
    			$inputFileType = \PHPExcel_IOFactory::identify($inputFile);
    			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    			$objPHPExcel = $objReader->load($inputFile);
    		} catch (Exception $e) {
    			die('error');
    		}
    		$sheet = $objPHPExcel->getSheet(0);
    		$highestRow = $sheet->getHighestRow();
    		$highestColumn = $sheet->getHighestColumn();
    		$ffofficer_empids = array();
    		$mngr_empids = array();
    		$all_empids = array();
    		$EmpId = array();
    		$check = 1;
    		$row2 = 1;
    		$rowDataheader = $sheet->rangeToArray('A'.$row2.':'.$highestColumn.$row2,NULL,TRUE,FALSE);
    		if(empty($rowDataheader[0][0])) {
    			$check = 0;
    			$flash = Yii::$app->session->setFlash('bulkusers-wrong-file');
    			return $this->redirect(['profile']);
    			
    		}
    		if (strip_tags($rowDataheader[0][1]) != 'Employee Name' || strip_tags($rowDataheader[0][2]) != 'Employee Id' || strip_tags($rowDataheader[0][3]) != 'Phone Number' || strip_tags($rowDataheader[0][4]) != 'Email Id' || $highestColumn != 'L') {
    			$check = 0;
    			$flash = Yii::$app->session->setFlash('bulkusers-wrong-file');
    		}
    		if ($check == 1) {
	    		for ($row = 2; $row <= $highestRow; $row++) {
	    			$rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
	    			$user_model = new Users();
	    			$finalData[$row]['first_name'] = trim($rowData[0][1]);
	    			$finalData[$row]['employee_number'] = trim($rowData[0][2]);
	    			$finalData[$row]['phone_number'] = trim($rowData[0][3]);
	    			$finalData[$row]['email_address'] = trim(strtolower($rowData[0][4]));
	    			$finalData[$row]['roleid'] = trim($rowData[0][5]);
	    			$finalData[$row]['designation_id'] = trim($rowData[0][6]);
	    			$finalData[$row]['reporting_user_id'] = trim($rowData[0][7]);
	    			$finalData[$row]['state'] = trim($rowData[0][8]);
	    			$finalData[$row]['district'] = trim($rowData[0][9]);
	    			$finalData[$row]['head_quarters'] = trim($rowData[0][10]);
	    			$finalData[$row]['area_of_operatoin'] = trim($rowData[0][11]);
	    			$finalData[$row]['input_company_id'] = Yii::$app->user->identity->input_company_id;
	//     			$password = rand(1000, 10000);
	//     			$finalData[$row]['password'] = md5($password);
					$deignation_id = array_search(strtolower($finalData[$row]['designation_id']),$designations);
					if($deignation_id == '') {
						$finalData[$row]['designation_id'] = 0;
					} else {
						$finalData[$row]['designation_id'] = $deignation_id;
					}
	    			$password = 'Goodluck@123';
	    			$finalData[$row]['password'] = md5($password);
	    			$key = rand(1000,10000);
	    			$access_token = md5($model->email_address.$key);
	    			$finalData[$row]['access_token'] = $access_token;
	    			$bulk_mails[$rowData[0][4]] = $password;
	    			$EmpId[] = trim($rowData[0][2]);
	    			if ($rowData[0][5] == $manager_role_id) {
	    				$reporting_manager_empids[] = $rowData[0][7];
	    			}
	    			if ($rowData[0][5] == $ffofficer_role_id) {
	    				$ffofficer_empids[] = $rowData[0][2];
	    			}
	    			if ($rowData[0][5] == $manager_role_id) {
	    				$mngr_empids[] = $rowData[0][2];
	    			}
	    			if ($rowData[0][6] != '') {
	    				$all_empids[] = $rowData[0][7];
	    			}
	    		}
	    		$arraay_emails = $model->bulkemailunique();
	    		$array_phone_numbers = $model->bulkphoneunique();
	    		$array_employee_numbers = $model->bulk_employeeid_unique();
	    		$array_reporting_user_employee_ids = $model->bulk_reporting_user_employee_ids();
	    		$rp_ids = $model->reportingEmpid();
	    		$remaining_reporting_empIds = array_unique(array_diff($all_empids, $mngr_empids));
	    		$remaining_reporting_empIds_count = count($remaining_reporting_empIds);
	    		$remaining_reporting_empIds = implode(',',$remaining_reporting_empIds);
	    		//$first_name_check = $model->firstnameCheck();
	    		//         	if (!empty($array_reporting_user_employee_ids[3])) {
	    		//         		$array_manager_empids = explode(',', $array_reporting_user_employee_ids[3]);
	    		//         	} else {
	    		//         		$array_manager_empids = array();
	    		//         	}
	    		if (!empty($array_reporting_user_employee_ids[4])) {
	    			$array_ffofficer_empids = explode(',', $array_reporting_user_employee_ids[4]);
	    		} else {
	    			$array_ffofficer_empids = array();
	    		}
// 	    		echo '<pre>';print_r($finalData);exit;
	    		if (!empty($finalData)) {
		    		$length = count($finalData)+2;
		    		for($i = 2;$i < $length;$i++){
		    			$first_name = trim($finalData[$i]['first_name']);
		    			$employee_id = trim($finalData[$i]['employee_number']);
		    			$phone_number = trim($finalData[$i]['phone_number']);
		    			$email_id = trim($finalData[$i]['email_address']);
		    			$role_id = trim($finalData[$i]['roleid']);
		    			$reporting_user_id = trim($finalData[$i]['reporting_user_id']);
		    			if ($role_id == $kgadmin_role_id) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-kgadmin-roleid');
		    				break;
		    			}
		    			if ($role_id == $icadmin_role_id) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-icadmin-roleid');
		    				break;
		    			}
		    			if ($role_id > $ffofficer_role_id) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-roleid-notmatch');
		    				break;
		    			}
		    			if (!$first_name || !$employee_id || !$phone_number || !$email_id || !$role_id || !$reporting_user_id) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-spaces-occured');
		    				break;
		    			}
		    			if (!preg_match("/^[a-zA-Z0-9\s]+$/", $finalData[$i]['first_name'])) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-first-name-format');
		    				break;
		    			}
		//     			if (!preg_match("/^[A-Za-z0-9!@#$%&?()*+:_\/\\\\ ]+$/", $finalData[$i]['employee_number'])) {
		//     				$check = 0;
		//     				$flash = Yii::$app->session->setFlash('employee-number-format');
		//     				break;
		//     			}
		    			/* if (!in_array($reporting_user_id, $rp_ids)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-reporting-user-not-exist','Please Check Reporting Employee ID: '.$reporting_user_id);
		    				break;
		    			} */
		    			//employee name checking
		    			/*if (in_array($first_name, $first_name_check)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-firstname-exist',"$first_name".' name alreday exist');
		    				break;
		    			}*/
		    			if (!preg_match("/^[0-9]{10,12}$/", $finalData[$i]['phone_number']) || preg_match("/^[0-9]{11}$/", $finalData[$i]['phone_number'])) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('phone-number-format');
		    				break;
		    			}
		    			if (!filter_var($finalData[$i]['email_address'], FILTER_VALIDATE_EMAIL)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('not valid email address');
		    				break;
		    			}
		    			if ($finalData[$i]['first_name'] == "" || $finalData[$i]['employee_number'] == "" || $finalData[$i]['email_address'] == "" || $finalData[$i]['roleid'] == "" || $finalData[$i]['reporting_user_id'] == "") {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-empty');
		    				break;
		    			}
		    			if (in_array($finalData[$i]['email_address'], $arraay_emails)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-email-exist');
		    				break;
		    			}
		    			if (in_array($finalData[$i]['phone_number'], $array_phone_numbers) || in_array('91'.$finalData[$i]['phone_number'], $array_phone_numbers) || in_array(substr($finalData[$i]['phone_number'], -10), $array_phone_numbers)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-phone-number-exist');
		    				break;
		    			}
		    			if (in_array($finalData[$i]['employee_number'], $array_employee_numbers)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-employee_number-exist');
		    				break;
		    			}
		    			if ($finalData[$i]['designation_id'] == 0) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('designation-empty');
		    				break;
		    			}
		    			
		    			//         		if ($finalData[$i]['roleid'] == $manager_role_id) {
		    			//         			if (in_array($finalData[$i]['employee_number'], $reporting_manager_empids) || in_array($finalData[$i]['reporting_user_id'], $array_manager_empids)) {
		    			//         				$check = 0;
		    			//         				$flash = Yii::$app->session->setFlash('bulkfields-same-employeeids');
		    			//         				break;
		    			//         			}
		    			//         		}
		    			if ($finalData[$i]['roleid'] == $manager_role_id) {
		    				if ($finalData[$i]['employee_number'] == $finalData[$i]['reporting_user_id']) {
		    					$check = 0;
		    					$flash = Yii::$app->session->setFlash('bulkfields-same-employeeids');
		    					break;
		    				}
		    			}
		    			if ($finalData[$i]['roleid'] == $manager_role_id) {
		    				if (in_array($finalData[$i]['reporting_user_id'], $ffofficer_empids) || in_array($finalData[$i]['reporting_user_id'], $array_ffofficer_empids)) {
		    					$check = 0;
		    					$flash = Yii::$app->session->setFlash('bulkfields-manager-ff');
		    					break;
		    				}
		    			}
		    			if ($finalData[$i]['roleid'] == $ffofficer_role_id) {
		    				if (in_array($finalData[$i]['reporting_user_id'], $ffofficer_empids) || in_array($finalData[$i]['reporting_user_id'], $array_ffofficer_empids)) {
		    					$check = 0;
		    					$flash = Yii::$app->session->setFlash('bulkfields-ff-ff');
		    					break;
		    				}
		    			}
		    			if(!in_array($finalData[$i]['reporting_user_id'],$array_employee_numbers)) {
		    				if(!in_array($finalData[$i]['reporting_user_id'],$EmpId)) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-not-db-employeeids', "Some reporting user employee id's are not in your company. Please try again.");
		    				break;
		    				}
		    			}
		    		
		    			for($j = 2;$j < $length;$j++){
		    				if ($i != $j) {
		    					if ($finalData[$i]['email_address'] == $finalData[$j]['email_address']) {
		    						$check = 0;
		    						$flash = Yii::$app->session->setFlash('bulkfields-duplicate-email',$finalData[$j]);
		    						break;
		    					}
		    					if ($finalData[$i]['phone_number'] == $finalData[$j]['phone_number']) {
		    						$check = 0;
		    						$flash = Yii::$app->session->setFlash('bulkfields-duplicate-phone-number');
		    						break;
		    					}
		    					if ($finalData[$i]['employee_number'] == $finalData[$j]['employee_number']) {
		    						$check = 0;
		    						$flash = Yii::$app->session->setFlash('bulkfields-duplicate-employeeids');
		    						break;
		    					}
		    					//employee name duplication checking
		    					/*if(trim($finalData[$i]['first_name']) == trim($finalData[$j]['first_name'])) {
		    						$check = 0;
		    						$flash = Yii::$app->session->setFlash('bulkfields-firstname-duplicates');
		    						break;
		    					}*/
		    				}
		    			}
		    		}
		    	
		    		/* if ($remaining_reporting_empIds != '') {
		    			$db_emp_ids = Yii::$app->db->createCommand('select count(id) from users where employee_number in ("'.$remaining_reporting_empIds.'") and input_company_id = '.Yii::$app->user->identity->input_company_id)->queryScalar();
		    			echo $remaining_reporting_empIds_count;exit; 
		    			if ($db_emp_ids != $remaining_reporting_empIds_count) {
		    				$check = 0;
		    				$flash = Yii::$app->session->setFlash('bulkfields-not-db-employeeids', "Some reporting user employee id's are not in our database. Please try again.");
		    			}
		    		} */
	    		}
    		}
    		if ($check == 1) {
    			if (!empty($finalData)) {
	    			for ($z = 2; $z < $length; $z++) {
	    				$user_model = new Users();
	    				$user_model->attributes = $finalData[$z];
	    				$user_model->save(false);
	    			}
	    			$last_inserted_row_id = Yii::$app->db->getLastInsertID();
	    			$firstId = $last_inserted_row_id;
	    			$rowCnt = count($finalData);
	    			for ($ids = 0; $ids < $rowCnt; $ids++) {
	    				$update = "UPDATE users u
	    						JOIN users uu ON u.reporting_user_id = uu.employee_number
	    						SET u.reporting_user_id = uu.id
	    						WHERE u.id = '".$firstId."'
	    						AND uu.input_company_id = '".Yii::$app->user->identity->input_company_id."'";
	    				$sql_update = Yii::$app->db->createCommand($update)->execute();
	    				$update_manager_status = "UPDATE users
	    										SET status = 'active'
	    										WHERE id = '".$firstId."'
	    										AND input_company_id = '".Yii::$app->user->identity->input_company_id."'";
	    				$sql_update_status = Yii::$app->db->createCommand($update_manager_status)->execute();
	    				$firstId--;
	    			}
	    			$mail = $model::bulkEmail($bulk_mails);
	    			$flash = Yii::$app->session->setFlash('bulkfields-success');
    			} else {
					$flash = Yii::$app->session->setFlash('bulkfields-file-empty');
				}
    		}
    		unlink($inputFile);
    		return $this->refresh();
    	}
    	//for bulk upload users end
    	$organization_model = new \app\models\InputCompanies();
    	if ($model->load(Yii::$app->request->post()) || $organization_model->load(Yii::$app->request->post()) && $model->emailunique()) {
			if ($_POST['save'] == 'userupdate') {
    			$model->scenario = 'userprofile';
    			unset($model->reporting_user_id);
    			$model->save();
    			$sql2 = new \yii\db\Query();
    			$sql2->createCommand()->update('input_companies', ['person_name'=> $model->first_name, 'phone_number' => $model->phone_number,
    					'contact_email' => $model->email_address], ['contact_email'=> $model->email_address])->execute();
    			
    		} else {
    			$sql = new \yii\db\Query();
    			$sql->createCommand()->update('input_companies', ['organization_name' => $organization_model->organization_name, 'phone_number' => $organization_model->phone_number,
    					'contact_email' => $organization_model->contact_email], ['contact_email'=> $organization_model->contact_email])->execute();
    			$sql2 = new \yii\db\Query();
    			$sql2->createCommand()->update('users', ['phone_number' => $organization_model->phone_number,
    					'email_address' => $organization_model->contact_email], ['email_address'=> $organization_model->contact_email])->execute();
    		}
    		if ($model->save(false)) {
    			if ($_POST['save'] == 'userupdate') {
    				Yii::$app->session->setFlash('users-updated');
    			} else {
    				Yii::$app->session->setFlash('organization-updated');
    			}
    			return $this->redirect(['profile']);
    		} else {
    			print_r($model->getErrors());
    			throw new \yii\web\HttpException(500, Yii::t('yii', 'Some Internal server issue occured.'));
    		}
    	}
    	return $this->render('profile', [
    			'model' => $this->findModel(Yii::$app->user->identity->guid),
    			'organization_model' => $organization_model,
    			 'searchModel' => $searchModel,
   				 'dataProvider' => $dataProvider,
    			 'actioncolumns'=> $actioncolumns,
   				 'linkactions' => $linkactions,
    			'user_model' => $user_model,
    			'userscount' => $userscount,
    			]);
    }
   
    
    public function actionChangepassword()
    {
    	$loginsession =\Yii::$app->session->get('loginid');
    	if(!isset($loginsession))
    	{
    		return $this->redirect(['site/login']);
    	}
    	$model = new Users();
    	$model->scenario = 'changepassword';
    	if ($model->load(Yii::$app->request->post()) && $model->changepasswordcheck()) {
    		return $this->redirect(['profile']);
    	}
    	
    	return $this->render('changepassword', [
    			'model' => $model,
    			]);
    
    }
    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBulkimport()
    {
    	$model = new Users();
    	$message = '';
    	$finalData = '';
    	if (Yii::$app->request->isPost) {
    		$model->bulkfile = UploadedFile::getInstance($model, 'bulkfile');
    		$session = Yii::$app->session;
    		\Yii::$app->session->set('csvfile',$model->bulkfile);
    		$model->bulkfile->saveAs(Yii::getAlias('@webroot').'/import/'.$model->bulkfile);
    		$csv = \Yii::$app->session->get('csvfile');
    		$inputFile = '../web/import/'.$csv;
    		try {
    			$inputFileType = \PHPExcel_IOFactory::identify($inputFile);
    			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    			$objPHPExcel = $objReader->load($inputFile);
    		} catch (Exception $e) {
    			die('error');
    		}
    		$sheet = $objPHPExcel->getSheet(0);
    		$highestRow = $sheet->getHighestRow();
    		$highestColumn = $sheet->getHighestColumn();
    		for($row=2; $row<=$highestRow; $row++){
    			$rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
    			$model = new Users();
    			$finalData[$row]['first_name'] = $rowData[0][1];
    			$finalData[$row]['employee_number'] = $rowData[0][2];
    			$finalData[$row]['phone_number'] = $rowData[0][3];
    			$finalData[$row]['email_address'] = $rowData[0][4];
    			$finalData[$row]['roleid'] = $rowData[0][5];
    			$finalData[$row]['reporting_user_id'] = $rowData[0][6];
    			$finalData[$row]['input_company_id'] = Yii::$app->user->identity->input_company_id;
    			$password = rand(1000, 10000);
    			$finalData[$row]['password'] =md5($password);
    			$key = rand(1000,10000);
    			$access_token = md5($model->email_address.$key);
    			$finalData[$row]['access_token'] = $access_token;
    		}
    		$arraay_emails = $model->bulkemailunique();
    		$array_phone_numbers = $model->bulkphoneunique();
    		$check = 1;
    		$length = count($finalData)+2;
    		for($i = 2;$i < $length;$i++){
    			if ($finalData[$i]['first_name'] == "" || $finalData[$i]['employee_number'] == "" || $finalData[$i]['email_address'] == "" || $finalData[$i]['roleid'] == "" || $finalData[$i]['reporting_user_id'] == "") {
    				$check = 0;
    				$flash = Yii::$app->session->setFlash('bulkfields-empty');
    				$message = 'Some fields are empty. Please fill.';
    			}
    			if (in_array($finalData[$i]['email_address'], $arraay_emails) || in_array($finalData[$i]['phone_number'], $array_phone_numbers)) {
    				$check = 0;
    				$flash = Yii::$app->session->setFlash('bulkfields-exist');
    				$message = 'Email already exist.';
    			}
    			for($j = 2;$j < $length;$j++){
    				if ($i != $j) {
    					if (in_array($finalData[$i]['email_address'], $finalData[$j]) || in_array($finalData[$i]['phone_number'], $finalData[$j]) || ($finalData[$i]['employee_number'] == $finalData[$j]['employee_number'])) {
    						$check = 0;
    						$flash = Yii::$app->session->setFlash('bulkfields-duplicate');
    						$message = 'Duplicate records are found.';
    					}
    				}
    			}
    		}
    		if ($check == 1) {
    			for($z = 2; $z < $length; $z++){
    				$model = new Users();
    				$model->attributes = $finalData[$z];
    				//$model->save(false);
    			}
    			$last_inserted_row_id = Yii::$app->db->getLastInsertID();
    			$firstId = $last_inserted_row_id;
    			$rowCnt = count($finalData);
    			for($ids = 0; $ids < $rowCnt; $ids++){
    				$update = "UPDATE users u
    						JOIN users uu ON u.reporting_user_id = uu.employee_number
    						SET u.reporting_user_id = uu.id
    						WHERE u.id = '".$firstId."'
    						AND uu.input_company_id = '".Yii::$app->user->identity->input_company_id."'";
    				$sql_update = Yii::$app->db->createCommand($update)->execute();
    				$firstId--;
    			}
    			//$update2 = "UPDATE users SET reporting_user_id = '".Yii::$app->user->identity->id."' WHERE reporting_user_id = 'admin' AND input_company_id = '".Yii::$app->user->identity->input_company_id."'";
    			//$sql_update2 = Yii::$app->db->createCommand($update2)->execute();
	 			$message = 'Successfully data Inserted';
			}
    	//	$mail = $model::bulkEmail($s);
    	}
	return $this->render('bulkupload', ['model'=> $model, 'message'=> $message]);
    }
    
    protected function findModel($id)
    {
    	$model = Users::find()->where(['guid' => $id])->one();
    	if ($model !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
        /* if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } */
    }
    
    public function actionReportinglists($id)
    { 
    	$user_status_active = Users::USER_STATUS_ACTIVE;
    	$httpurl = $_SERVER['HTTP_REFERER'];
    	$httpurl = explode('/',$httpurl);
    	$httpurl = end($httpurl);
    	if($httpurl == 'create'){
	    	$countUsers = Users::find()->where(['roleid'=> $id, 'status' => $user_status_active,'is_deleted' => 0])->count();
	    	$users = Users::find()->where(['roleid'=> $id, 'input_company_id' => Yii::$app->user->identity->input_company_id, 'status' => $user_status_active,'is_deleted' => 0])->orderBy(['first_name' => SORT_ASC])->all();
    	}
    	else{
    		$countUsers = Users::find()->where(['status' => $user_status_active, 'is_deleted' => 0])->count();
    		$users = Users::find()->where(['status' => $user_status_active,'is_deleted' => 0])->all();
    		$roles = Roles::find()->all();
    		//print_r($roles);exit;
    	
    		//$users2 = Users::find()->where(['id'=> $id])->one();
    		$users2 = (new \yii\db\Query())
    					->select('uu.id, uu.first_name, uu.roleid, r.role_name as role')
    					->from('users u')
			    		->innerJoin('users uu', 'uu.id = u.reporting_user_id')
			    		->innerJoin('roles r', 'uu.roleid = r.id')
    					->where(['u.id' => $id])
    					->andWhere(['uu.status' => $user_status_active, 'uu.is_deleted' => 0]);
    		$users2 = $users2->createCommand();
    		$users2 = $users2->queryOne();
//    		print_r($users2);exit;

    	}
    	$result = '';
    	$selected ='';
    	$result .= "<option value='' >Select Reporting Manager Name</option>";
    	if($countUsers > 0){
    		foreach($users as $key => $user){
    			//print_r($user['id']);exit;
    			if($httpurl != 'create'){
    				if(in_array($user['id'], $users2)){
    				$selected = 'selected';
    				}
    			}
    			else{
    				$selected = '';
    			}
    			$result .= "<option value='".$user['id']."' $selected>".ucwords($user['first_name'])."</option>";
    		}
    
    	}
    	/*else{
    		$result .= "<option value='' >Select</option>";
    	}*/
    	return $result;
    }
    
    public function actionReportinglistsupdate($changeid = 0, $id, $employee_role = 0)
    { 
    	$kgadmin_role_id = Roles::KGADMIN;
    	$icadmin_role_id = Roles::ICADMIN;
    	$manager_role_id = Roles::MANAGER;
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$user_status_active = Users::USER_STATUS_ACTIVE;
    	//for getting managerid
    	$httpurl = $_SERVER['HTTP_REFERER'];
    	$httpurl = explode('/',$httpurl);
    	$httpurl = end($httpurl);
    	if ($httpurl != 'create') {
    		$http_get_id = Users::find()->select('id')->where(['guid' => $httpurl])->asArray()->one(); //for rendering id based on guid
    		$httpurl = $http_get_id['id'];
    	}
    	//for getting managerid end
    	$user = (new \yii\db\Query())
		    	->select('uu.id, uu.first_name, uu.roleid, r.role_name')
		    	->from('users u')
		    	->innerJoin('users uu', 'uu.id = u.reporting_user_id')
		    	->innerJoin('roles r', 'uu.roleid = r.id')
		    	->where(['u.id' => $id])
		    	->andWhere(['uu.status' => $user_status_active, 'uu.is_deleted' => 0]);
    	$user = $user->createCommand();
    	$user = $user->queryOne();
    	$manager_id = Users::find()->where(['roleid'=> $manager_role_id, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->one();
    	
    	$dependent_users = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $id, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    	$depend_users = array();
    	$depend_users_string = '';
    	$all_dependent_user_ids = array();
    	if (!empty($dependent_users)) {
    		$managerReporteeIds = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $dependent_users[0]['reportees']])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    		$all_dependent_user_ids = array_merge($dependent_users, $managerReporteeIds);
    		if (!empty($all_dependent_user_ids)) {
    			foreach ($all_dependent_user_ids as $all_ids) {
    				$depend_users[] = $all_ids['reportees'];
    			}
    			$depend_users_string = implode(',', $depend_users);
    		}
    	}
    	if ($depend_users_string == '') {
    		$depend_users_string = $id;
    	}
    	//print_r(count($depend_users));exit;
    	
    	if (!empty($user)) {
	    	if($changeid == 0 && $employee_role != 0){
	    		$user = (new \yii\db\Query())
			    		->select('uu.id, uu.first_name, uu.roleid, r.role_name')
			    		->from('users u')
			    		->innerJoin('users uu', 'uu.id = u.reporting_user_id')
			    		->innerJoin('roles r', 'uu.roleid = r.id')
			    		->where(['u.id' => $id])
			    		->andWhere(['uu.status' => $user_status_active, 'uu.is_deleted' => 0]);
	    		$user = $user->createCommand();
	    		$user = $user->queryOne();
	//    		print_r($user);exit;
	    		if (count($depend_users) > 0  && $employee_role == $manager_role_id) {
	    			$users = Users::find()->where(['roleid'=> $user['roleid'], 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->andWhere("id NOT IN ($depend_users_string)")->orderBy(['first_name' => SORT_ASC])->all();
	    			$countUsers = Users::find()->where(['roleid'=> $user['roleid'], 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->andWhere("id NOT IN ($depend_users_string)")->count();
	    		} else {
		    		$users = Users::find()->where(['roleid'=> $user['roleid'], 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->orderBy(['first_name' => SORT_ASC])->all();
		    		$countUsers = Users::find()->where(['roleid'=> $user['roleid'], 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->count();
	    		}
	    		//$roles = Roles::find()->all();
	    		$query2 = '';
	    		$query3 = '';
	//     		if(Yii::$app->user->identity->roleid == '1')
	//     		{
	//     			$query2 .= "";
	//     		}
	//     		else if(Yii::$app->user->identity->roleid == '2')
	//     		{
	//     			$query2 .= "where id != 1";
	//     		}
	//     		else if(Yii::$app->user->identity->roleid == '3')
	//     		{
	//     			$query2 .= "where id != 1 and id != 2";
	//     		}
	    		if ($employee_role == $manager_role_id) {
					$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere("id NOT IN ($depend_users_string)")->count();
					if ($managercount > 0 && $managercount == 1 && $httpurl == $manager_id['id']) {
						$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
					} elseif ($managercount > 0) {
						$query3 .= "where id != $kgadmin_role_id and id != $ffofficer_role_id";
					} else {
						$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
					}
				} else if ($employee_role == $ffofficer_role_id) {
	    			$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
	    			if ($managercount > 0) {
	    				$query3 .= "where id != $kgadmin_role_id and id != $ffofficer_role_id";
	    			} else {
	    				$query3 .= "where id != $kgadmin_role_id and id = $icadmin_role_id and id != $ffofficer_role_id";
	    			}
	    		}
	    		$query = "select id, role_name from roles $query3";
	    		$roles = Yii::$app->db->createCommand($query)->queryAll(); 
	    		
	    		foreach ($user as $us) {
	    			$uss[][]=  $us;
	    		}
	 //   		print_r($uss[0]);exit;
	//     		print_r($users);exit;
	    	} else {
	    		$user = (new \yii\db\Query())
			    		->select('uu.id, uu.first_name, uu.roleid, r.role_name')
			    		->from('users u')
			    		->innerJoin('users uu', 'uu.id = u.reporting_user_id')
			    		->innerJoin('roles r', 'uu.roleid = r.id')
			    		->where(['u.id' => $id])
			    		->andWhere(['uu.status' => $user_status_active, 'uu.is_deleted' => 0]);
	    		$user = $user->createCommand();
	    		$user = $user->queryOne();
	    		
	    		foreach ($user as $us) {
	    			$uss[][] =  $us; 
	    		}
	    		
	    		if (count($depend_users) > 0  && ($employee_role == $manager_role_id || $employee_role == $ffofficer_role_id)) {
	    			$countUsers = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->andWhere("id NOT IN ($depend_users_string)")->count();
		    		$users = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->andWhere("id NOT IN ($depend_users_string)")->orderBy(['first_name' => SORT_ASC])->all();
	    		} else {
		    		$countUsers = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->count();
		    		$users = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->orderBy(['first_name' => SORT_ASC])->all();
	    		}
	    		//$roles = Roles::find()->all();
	    		$query2 = '';
	    		$query3 = '';
	//     		if(Yii::$app->user->identity->roleid == '1')
	//     		{
	//     			$query2 .= "";
	//     		}
	//     		else if(Yii::$app->user->identity->roleid == '2')
	//     		{
	//     			$query2 .= "where id != 1";
	//     		}
	//     		else if(Yii::$app->user->identity->roleid == '3')
	//     		{
	//     			$query2 .= "where id != 1 and id != 2";
	//     		}
	    		if ($employee_role == $manager_role_id) {
	    			$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere("id NOT IN ($depend_users_string)")->count();
	    			if ($managercount > 0 && $managercount == 1 && $httpurl == $manager_id['id']) {
						$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
					} elseif ($managercount > 0) {
						$query3 .= "where id != $kgadmin_role_id and id != $ffofficer_role_id";
					} else {
						$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
					}
	    		} else if ($employee_role == $ffofficer_role_id) {
	    			$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
	    			if ($managercount > 0 && $managercount == 1 && $httpurl == $manager_id['id']) {
						$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
					} elseif ($managercount > 0) {
	    				$query3 .= "where id != $kgadmin_role_id and id != $ffofficer_role_id";
	    			} else {
	    				$query3 .= "where id != $kgadmin_role_id and id = $icadmin_role_id and id != $ffofficer_role_id";
	    			}
	    		}
	    		$query = "select id, role_name from roles $query3";
	    		$roles = Yii::$app->db->createCommand($query)->queryAll();
	    	}
	    	$roleuserresult = '';
	    	$roleresult = '';
	    	$selected ='';
	    	$roleuserresult .= "<option value='' >Select Reporting Manager Name</option>";
	    	$roleresult .= "<option value='' >Select Reporting Manager Role</option>";
	    	$selected2 = ''; 
	    	
	    	foreach ($roles as $key => $data_roles) {
	    		if ($changeid == 0) {
		    		if ($uss[2][0] == $data_roles['id']) {
		    			$selected2 = 'selected';
		    		} else {
		    			$selected2 = '';
		    		}
	    		} else {
		    		if ($changeid == $data_roles['id']) {
		    			$selected2 = 'selected';
		    		} else {
		    			$selected2 = '';
		    		}
	    		}
	    		if ($data_roles['role_name'] != 'field force') {
	    			$roleresult .= "<option value='".$data_roles['id']."' $selected2>".ucwords($data_roles['role_name'])."</option>";
	    		}
	    	}
	    	    	    	
	    	if ($countUsers > 0) {
	    		foreach ($users as $key => $user) {
	    			//print_r($user['id']);exit;
	    			if ($changeid == 0) {
		    			if (in_array($user['id'], $uss[0])) {
		    					$selected = 'selected';
		    			} else {
		    				$selected = '';
		    			}
	    			} else {
	    				$selected = '';
	    			}
	    			$roleuserresult .= "<option value='".$user['id']."' $selected>".ucwords($user['first_name'])."</option>";
	    		}
	    	}
	    	/*else{
	    	 $result .= "<option value='' >Select</option>";
	    	}*/
	    	$result_ar = array();
	    	$result_ar[0] = $roleresult;
	    	$result_ar[1] = $roleuserresult;
	    	return json_encode($result_ar);
    	} else {
    		if ($changeid == 0) {
    			if ($employee_role == $icadmin_role_id) {
    				$countUsers = '';
    				$users = '';
    				$result = "<option value='0' >Select Reporting Manager Role</option>";
    				$result2 = "<option value='0' >Select Reporting Manager Name</option>";
    			} else {
    				if ($employee_role == $manager_role_id) {
    					$countUsers = Roles::find()->where(['id'=> $employee_role])->count();
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} elseif ($employee_role == $ffofficer_role_id) {
    					$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
    					$countUsers = Roles::find()->where(['id'=> $employee_role])->count();
    					if ($managercount > 0) {
    						$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    					} else {
    						$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    					}
    				}
    				$result = '';
    				$selected ='';
    				$result .= "<option value='' >Select Reporting Manager Role</option>";
    				$result2 = "<option value='' >Select Reporting Manager Name</option>";
    				if ($countUsers > 0) {
    					foreach ($users as $key => $user) {
    						$result .= "<option value='".$user['id']."' $selected>".ucwords($user['role_name'])."</option>";
    					}
    				}
    		}
    		
    		$result_ar2 = array();
    		$result_ar2[0] = $result;
    		$result_ar2[1] = $result2;
    		return json_encode($result_ar2);
    	 } else {
    	 	$countUsers = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->count();
    	 	$users = Users::find()->where(['roleid'=> $changeid, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere(['!=', 'id', $id])->orderBy(['first_name' => SORT_ASC])->all();
    	 	//$roles = Roles::find()->all();
    	 	$query2 = '';
    	 	$query3 = '';
    	 	if ($employee_role == $manager_role_id) {
    	 		$query3 .= "where id != $kgadmin_role_id and id != $manager_role_id and id != $ffofficer_role_id";
    	 	} elseif ($employee_role == $ffofficer_role_id) {
    	 		$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
    	 		if ($managercount > 0) {
    	 			$query3 .= "where id != $kgadmin_role_id and id != $ffofficer_role_id";
    	 		} else {
    	 			$query3 .= "where id != $kgadmin_role_id and id = $icadmin_role_id and id != $ffofficer_role_id";
    	 		}
    	 	}
    	 	$query = "select id,role_name from roles $query3";
    	 	$roles = Yii::$app->db->createCommand($query)->queryAll();
    	 	}
    	 	$roleuserresult = '';
    	 	$roleresult = '';
    	 	$selected ='';
    	 	$roleuserresult .= "<option value='' >Select Reporting Manager Name</option>";
    	 	$roleresult .= "<option value='' >Select Reporting Manager Role</option>";
    	 	$selected2 = '';
    	 	
    	 	foreach ($roles as $key => $data_roles) {
    	 		if ($changeid == $data_roles['id']) {
    	 			$selected2 = 'selected';
    	 		}
    	 		else {
    	 			$selected2 = '';
    	 		}
    	 		if ($data_roles['role_name'] != 'field force') {
    	 			$roleresult .= "<option value='".$data_roles['id']."' $selected2>".ucwords($data_roles['role_name'])."</option>";
    	 		}
    	 	}
    	 	
    	 	if ($countUsers > 0) {
    	 		foreach ($users as $key => $user) {
    	 			$roleuserresult .= "<option value='".$user['id']."' $selected>".ucwords($user['first_name'])."</option>";
    	 		} 
    	 	}
    	 	/*else{
    	 	 $result .= "<option value='' >Select</option>";
    	 	}*/
    	 	$result_ar = array();
    	 	$result_ar[0] = $roleresult;
    	 	$result_ar[1] = $roleuserresult;
    	 	return json_encode($result_ar);
    	 
    	}
    }
    public function actionReportinguserroles($id)
    { 
    	$kgadmin_role_id = Roles::KGADMIN;
    	$icadmin_role_id = Roles::ICADMIN;
    	$manager_role_id = Roles::MANAGER;
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$user_status_active = Users::USER_STATUS_ACTIVE;
    	
    	$httpurl = $_SERVER['HTTP_REFERER'];
    	$httpurl = explode('/',$httpurl);
    	$httpurl = end($httpurl);
    	if ($httpurl != 'create') {
    		$http_get_id = Users::find()->select('id')->where(['guid' => $httpurl])->asArray()->one(); //for rendering id based on guid
    		$httpurl = $http_get_id['id'];
    	}
    	$manager_id = Users::find()->where(['roleid'=> $manager_role_id, 'input_company_id'=> Yii::$app->user->identity->input_company_id, 'status' => $user_status_active, 'is_deleted' => 0])->one();
    	 
    	$dependent_users = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $httpurl, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    	$depend_users = array();
    	$depend_users_string = '';
    	$all_dependent_user_ids = array();
    	if (!empty($dependent_users)) {
    		$managerReporteeIds = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $dependent_users[0]['reportees']])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    		$all_dependent_user_ids = array_merge($dependent_users, $managerReporteeIds);
    		if (!empty($all_dependent_user_ids)) {
    			foreach ($all_dependent_user_ids as $all_ids) {
    				$depend_users[] = $all_ids['reportees'];
    			}
    			$depend_users_string = implode(',', $depend_users);
    		}
    	}
    	if ($depend_users_string == '') {
    		$depend_users_string = $httpurl;
    	}
    	//print_r(count($depend_users));exit;
    	
    	if ($httpurl == 'create') {
    		if ($id == $icadmin_role_id) {
    			$countUsers = '';
    			$users = '';
    			$result = "<option value='0' >Select Reporting Manager Role</option>";
    			$result2 = "<option value='0' >Select Reporting Manager Name</option>";
    		} else {
    			if ($id == $manager_role_id) {
    				$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
    				$countUsers = Roles::find()->where(['id'=> $id])->count();
    				if ($managercount > 0) {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} else {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				}
    			} elseif ($id == $ffofficer_role_id) {
	    			$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->count();
	    			$countUsers = Roles::find()->where(['id'=> $id])->count();
	    			if ($managercount > 0) {
	    				$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
	    			} else {
	    				$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
	    			}
	    		}
	    		$result = '';
	    		$selected ='';
	    		$result .= "<option value='' >Select Reporting Manager Role</option>";
	    		$result2 = "<option value='' >Select Reporting Manager Name</option>";
	    		if ($countUsers > 0) {
	    			foreach ($users as $key => $user) {
	    				//print_r($user['id']);exit;
// 	    				if($httpurl != 'create'){
// 	    					if(in_array($user['id'], $users2)){
// 	    						$selected = 'selected';
// 	    					}
// 	    				}
// 	    				else{
// 	    					$selected = '';
// 	    				}
	    				$result .= "<option value='".$user['id']."' $selected>".ucwords($user['role_name'])."</option>";
	    			}
	    		
	    		}
    		}
    	} else {
    		if ($id == $icadmin_role_id) {
    			$countUsers = '';
    			$users = '';
    			$result = "<option value='0' >Select Reporting Manager Role</option>";
    			$result2 = "<option value='0' >Select Reporting Manager Name</option>";
    		} else {
    			if ($id == $manager_role_id) {
    				$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere("id NOT IN ($depend_users_string)")->count();
    				$countUsers = Roles::find()->where(['id'=> $id])->count();
    				if ($managercount > 0 && $managercount == 1 && $httpurl == $manager_id['id']) {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} elseif ($managercount > 0) {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} else {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				}
    			} elseif ($id == $ffofficer_role_id) {
    				$managercount = Users::find()->where(['input_company_id' => Yii::$app->user->identity->input_company_id, 'roleid' => $manager_role_id, 'status' => $user_status_active, 'is_deleted' => 0])->andWhere("id NOT IN ($depend_users_string)")->count();
    				$countUsers = Roles::find()->where(['id'=> $id])->count();
    				if ($managercount > 0 && $managercount == 1 && $httpurl == $manager_id['id']) {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} elseif ($managercount > 0) {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				} else {
    					$users = Roles::find()->where(['!=','id', $kgadmin_role_id])->andWhere(['!=','id', $manager_role_id])->andWhere(['!=','id', $ffofficer_role_id])->all();
    				}
    			}
    			$result = '';
    			$selected ='';
    			$result .= "<option value='' >Select Reporting Manager Role</option>";
    			$result2 = "<option value='' >Select Reporting Manager Name</option>";
    			if ($countUsers > 0) {
    				foreach ($users as $key => $user) {
    					//print_r($user['id']);exit;
    					// 	    				if($httpurl != 'create'){
    					// 	    					if(in_array($user['id'], $users2)){
    					// 	    						$selected = 'selected';
    					// 	    					}
    					// 	    				}
    					// 	    				else{
    					// 	    					$selected = '';
    					// 	    				}
    					$result .= "<option value='".$user['id']."' $selected>".ucwords($user['role_name'])."</option>";
    				}
    			}
    		}
    	}
    	
    	$result_ar2 = array();
    	$result_ar2[0] = $result;
    	$result_ar2[1] = $result2;
    	return json_encode($result_ar2);
    }
    
    public function actionDependencyusers()
    {
    	$manager_role_id = Roles::MANAGER;
    	$ffofficer_role_id = Roles::FIELDFORCE;
    	$role_id = $_REQUEST['role_id'];
    	$manager_id = $_REQUEST['manager_id'];
    	$reporting_user_id = $_REQUEST['reporting_user_id'];
    	$emp_role = $_REQUEST['emp_role'];
    	
    	if ($role_id == $manager_role_id) {
    		//$managerIds = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $manager_id, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    		//$ffids = Users::find()->select('group_concat(id) as reportees')->where(['reporting_user_id' => $manager_id, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $ffofficer_role_id])->andWhere(['!=', 'reporting_user_id', 0])->groupBy('reporting_user_id')->asArray()->all();
    		$managerIds = Users::find()->select('id as reportees')->where(['reporting_user_id' => $manager_id, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $manager_role_id])->andWhere(['!=', 'reporting_user_id', 0])->asArray()->all();
    		$ffids = Users::find()->select('id as reportees')->where(['reporting_user_id' => $manager_id, 'input_company_id' => Yii::$app->user->identity->input_company_id])->andWhere(['roleid' => $ffofficer_role_id])->andWhere(['!=', 'reporting_user_id', 0])->asArray()->all();
    		if (count($managerIds) > 0 || count($ffids) > 0) {
    			//if ($emp_role == $ffofficer_role_id) {
    				return "users exist";
    			//}
    		}
    	}
    }
    
    public function actionAjaxphone()
    {  
    	$phone_number = $_REQUEST['phone_number'];
    	$phone_number2 = '91'.$phone_number;
    	$user_id = Yii::$app->user->identity->id;
    	$sql = Users::find()->select('phone_number')->where(["or", "phone_number = $phone_number", "phone_number = $phone_number2", "phone_number = '".substr($phone_number, -10)."'"])->andWhere(['!=', 'id', $user_id])->count();
    	if ($sql > 0) {
    		return 'exist';
    	} else {
    		return "doesn't exist";
    	}
    }
    public function actionDownload()
    {
    	return \Yii::$app->response->sendFile('../users_template.xls');
    
    }
    public function actionExport($type)
    {
    	$summaryparam = array();
    	if($type == 'web') {
    		$roleid = 3;
    		$filename = 'Web';
    	} else {
    		$roleid = 4;
    		$filename = 'App';
    	}
    	$LOGINID = Yii::$app->user->identity->id;
    	$usersListData = Users::userlistRecoursive($LOGINID,$roleid,true);
    	$userDetails = Users::usersDetailsdata($usersListData);
    	$objPHPExcel = \PHPExcel_IOFactory::createReader('Excel5');
    	$objPHPExcel = $objPHPExcel->load('../crops_template.xls');
    	$objPHPExcel->getActiveSheet()->fromArray($userDetails, null, 'A2');
    	$objPHPExcel->getActiveSheet()->setTitle('User Details');
    	$objPHPExcel->getActiveSheet()
    	->setCellValue('A1', 'User Name')
    	->setCellValue('B1', 'Employee Id')
    	->setCellValue('C1', 'Reports To')
    	->setCellValue('D1', 'Email Address')
    	->setCellValue('E1', 'Phone Number')
    	->setCellValue('F1', 'Role Name')
    	->setCellValue('G1', 'Head Quarters')
    	->setCellValue('H1', 'Active')
    	->setCellValue('I1', 'is_login');
    
    	// Set AutoSize for name and email fields
    	/*  $sheet = $objPHPExcel->getSheet(0);
    	$highestRow = $sheet->getHighestRow();
    	$highestColumn = $sheet->getHighestColumn();
    
    	$sheetData = $sheet->rangeToArray(
    	'A2:' . $highestColumn . $highestRow,
    	NULL,TRUE,FALSE
    	); */
    	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$objWriter->save(\Yii::getAlias('@webroot').'/'.$filename.' User Details.xls');
   		return \Yii::$app->response->sendFile(\Yii::getAlias('@webroot').'/'.$filename.' User Details.xls');
   }
}
