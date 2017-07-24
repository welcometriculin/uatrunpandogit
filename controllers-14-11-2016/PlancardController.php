<?php

namespace app\controllers;

use Yii;
use app\models\PlanCards;
use app\models\PlanCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Villages;
use app\models\Crops;
use app\models\Products;
use app\models\ChannelPartners;
use app\models\LabelNames;
use app\models\FormBuilder;
use yii\helpers\Url;
/**
 * PlancardController implements the CRUD actions for PlanCards model.
 */
class PlancardController extends KgController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PlanCards models.
     * @return mixed
     */
    public function actionIndex()
    {
    	//test
        $searchModel = new PlanCardSearch();
        $model=new PlanCards();
        $label_names_display = LabelNames::labelNamesDisplay();
      //  $dataProvider->pagination->pageSize=3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $page = Yii::$app->request->get('page');
        $per_page = Yii::$app->request->get('per-page');
        if(!$page && !$per_page) {
        	$geturl = Url::remember(['plancard/index','page'=>Yii::$app->request->get('page'),'per-page'=>Yii::$app->request->get('per-page') ],'previous');
        }
        $geturl = Url::remember('',null);
    //    $dataProvider->pagination->pageSize=2;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'model'		=>$model,
        	'label_names_display' => $label_names_display,
        ]);
    }

    /**
     * Displays a single PlanCards model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$data=\app\models\PlanCards::Campcard($id);
    	$label_names_display = LabelNames::labelNamesDisplay();
    	
        return $this->render('view', [
            'model' => $this->findModel($id),'data'=>$data, 'label_names_display' => $label_names_display,
        ]);
    }

    /**
     * Creates a new PlanCards model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    
        $model = new PlanCards();
        $model->scenario = 'create';
        $crops_list = Crops::cropList();
        $products_list = Products::productList();
        $label_names_display = LabelNames::labelNamesDisplay();
        
        if ($model->load(Yii::$app->request->post())) {
        	if ($_POST['save'] == 'saved') {
        	
        	} else {
        		$model->plan_approval_status = 'Approved';
        	}
        	$model->planned_date=date("Y-m-d", strtotime($_POST['PlanCards']['planned_date']));
        	$t=time();
			$time=date("Y-m-d h:m:s",$t);
        	$planned_date=$model->planned_date;
        	$plan_type =Plancards::planType($planned_date,$time);
        	//echo $plan_type;exit;
        	$model->plan_type=$plan_type;
        	if ($model->activity == 'Channel Card') {
        		$model->crop_id = 0;
        		$model->product_id = 0;
        		$model->card_type="channel card";
//         		$new_village_save = Villages::addNewVillageSave($model->village_name, $model->assign_to);
//         		$new_channel_partner_save = ChannelPartners::addNewChannelPartnerSave($model->channel_partner, $model->assign_to);
        	} else {
        		$model->channel_partner = '';
        		//$new_village_save = Villages::addNewVillageSave($model->village_name, $model->assign_to);
        		//$new_crop_save = Crops::addNewCropSave($model->crop_name, $model->assign_to);
        		//$new_product_save = Products::addNewProductSave($model->product_name, $model->assign_to);
        	}
        	$last_insert_id = PlanCards::find()->select('id')->orderBy('id DESC')->one();
        	$model->mobile_timestamp = $last_insert_id['id'] + 1;
        	if($model->save(false)){
        	Yii::$app->session->setFlash('plan-create');
            return $this->redirect(['index']);
        	}
        } else {
            return $this->render('create', [
                'model' => $model,
            	'crops_list' => $crops_list,
            	'products_list' => $products_list,
            	'label_names_display' => $label_names_display,
            ]);
        	}
        
    }

    /**
     * Updates an existing PlanCards model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       $model = $this->findModel($id);
       $updated = PlanCards::updateUser($id);
       $model->scenario = 'update';
       $crops_list = Crops::cropList();
       $products_list = Products::productList();
       $label_names_display = LabelNames::labelNamesDisplay();
		
    if ($model->load(Yii::$app->request->post())) {
    	//echo '<pre>';print_r(Yii::$app->request->post());exit;
  	  		if ($_POST['edit'] == 'approve') {
    			$model->plan_approval_status = 'Approved';
        		$model->status = 'not_submitted';
        		Yii::$app->session->setFlash('plan-approve');
        	} elseif ($_POST['edit'] == 'reject') {
        		$model->plan_approval_status = 'Rejected';
        		$model->status = 'rejected';
        		Yii::$app->session->setFlash('plan-reject');
        	} elseif ($_POST['edit'] == 'updated') {
        		$model->plan_approval_status = $model->plan_approval_status;
        		Yii::$app->session->setFlash('plan-update');
        	}
   				$model->planned_date=date("Y-m-d", strtotime($_POST['PlanCards']['planned_date']));
   				$t=time();
   				$time=date("Y-m-d h:m:s",$t);
   		 		$planned_date=$model->planned_date;
   		 		$plan_type =Plancards::planType($planned_date,$time);
   				$model->plan_type=$plan_type;
   				if($model->activity == 'Channel Card') {	
   					$model->crop_id = 0;
   					$model->product_id = 0;
   					$model->channel_partner = $_POST['PlanCards']['channel_partner'];
   				} else {
   		 			$model->channel_partner = '';
   				}
    			$model->save(false);
          	return $this->redirect(Url::previous());
      } else {
       		return $this->render('update', [
                				'model' => $model,
       		    		   		'updated'=> $updated,
       		    		   		'crops_list' => $crops_list,
       		    		   		'products_list' => $products_list,
       							'label_names_display' => $label_names_display,
            					]);
       }
   	}

    /**
     * Deletes an existing PlanCards model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       	$sql2 = Yii::$app->db->createCommand()
   				->update('plan_cards', ['is_deleted' => 1], ['id' => $id])
   				->execute();
       	Yii::$app->session->setFlash('plan-delete');
        return $this->redirect(Url::previous());
    }
    public function actionActivity($id)
    {
    	$label_names_display = LabelNames::labelNamesDisplay();
    	$activityName = PlanCards::find()->select('activity')->where(['guid' => $id])->column();
    	$activityLabels = FormBuilder::activityLabels($activityName[0]);
    	if ($id == '') {
    		
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    	return $this->render('activity', ['response' => $this->activityData($id), 'label_names_display' => $label_names_display, 'actvityLabels' => $activityLabels]);
    		
    }
    /**
     * Finds the PlanCards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanCards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
    	$model = PlanCards::find()->where(['guid' => $id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findDetails($id)
    {
    	$query = new \yii\db\Query();
		$query->select('*')
				->from('plan_cards pc')
				->innerJoin('campaign_card_activities cc', 'pc.id = cc.plan_card_id')
				->where(['pc.id' => $id]);
		$query = $query->createCommand();
		return $queryresp = $query->queryOne();
    }
   protected function activityData($guid)
   {
   	
   	$model=new PlanCards();
   	$user_id=Yii::$app->user->identity->id;
   	$plancardProducts = array();
   //$da=$model::find()->select('id,activity')->where(['guid'=>$guid])->one();
   /* $sql = "select  p.id as plan_card_id_d,IF( p.created_by =$user_id,  'self',  'manager' ) AS plan_created_by,p.planned_date, mv.village_name, pr.product_name, cr.crop_name ,  
    			p.activity,  p.plan_type, p.card_type ,p.channel_partner,p.status ,p.updated_date,p.updated_date,
    			IF(c.contacted_person_name is null or c.contacted_person_name = '', '--', c.contacted_person_name) as contacted_person_name,
				IF(c.contacted_person_phone is null or c.contacted_person_phone = '', '--', c.contacted_person_phone) as contacted_person_phone,
				IF(c.no_of_farmers is null or c.no_of_farmers = '', '--', c.no_of_farmers) as no_of_farmers,
				IF(c.no_of_female_farmers is null or c.no_of_female_farmers = '', '--', c.no_of_female_farmers) as no_of_female_farmers,
				IF(c.no_of_villages is null or c.no_of_villages = '', '--', c.no_of_villages) as no_of_villages,
				IF(c.no_of_retailers is null or c.no_of_retailers = '', '--', c.no_of_retailers) as no_of_retailers,
				IF(c.no_of_dealers is null or c.no_of_dealers = '', '--', c.no_of_dealers) as no_of_dealers,
				IF(c.feedback is null or c.feedback = '', '--', c.feedback) as feedback,
				IF(c.purpose is null or c.purpose = '', '--', c.purpose) as purpose,
    			c.picture1,c.picture2,c.picture3,c.picture1_uri,c.picture2_uri,c.picture3_uri,
    			IF(ch.target_value is null or ch.target_value = '', '--', ch.target_value) as target_value,
				IF(ch.actual_value is null or ch.actual_value = '', '--', ch.actual_value) as actual_value,
				IF(ch.feedback is null or ch.feedback = '', '--', ch.feedback) as cfeedback,
    			u.first_name as created, uu.first_name as assignee ,IF(sa.sub_activity_name is null or sa.sub_activity_name = '', '--', sa.sub_activity_name) as sub_activity_name
    			from plan_cards p
    			inner join users uu on p.assign_to = uu.id
    			inner join users u on p.created_by = u.id
    			left join crops cr on cr.id = p.crop_id
    			left join products pr on pr.id = p.product_id
    			left join villages_master mv on mv.village_id = p.village_id			
    			inner join activity a on a.activity_name = p.activity
    			left join campaign_card_activities c
				on c.plan_card_id=p.id
				left join channel_card_activities ch
				on ch.plan_card_id = p.id
				left join sub_activity sa on sa.sub_activity_id = c.sub_activity_id and sa.activity_id = a.activity_id
				where p.guid = '".$guid."'"; */
   	$sql = "select  p.id as plan_card_id_d,IF( p.created_by =$user_id,  'self',  'manager' ) AS plan_created_by,p.planned_date, mv.village_name, pr.product_name, cr.crop_name ,
			   	p.activity,  p.plan_type, p.card_type ,p.channel_partner,p.status ,p.updated_date,p.updated_date,
			   	IF(c.contacted_person_name is null or c.contacted_person_name = '', '--', c.contacted_person_name) as contacted_person_name,
			   	IF(c.contacted_person_phone is null or c.contacted_person_phone = '', '--', c.contacted_person_phone) as contacted_person_phone,
			   	IF(c.no_of_farmers is null or c.no_of_farmers = '', '--', c.no_of_farmers) as no_of_farmers,
			   	IF(c.no_of_female_farmers is null or c.no_of_female_farmers = '', '--', c.no_of_female_farmers) as no_of_female_farmers,
			   	IF(c.no_of_villages is null or c.no_of_villages = '', '--', c.no_of_villages) as no_of_villages,
			   	IF(c.no_of_retailers is null or c.no_of_retailers = '', '--', c.no_of_retailers) as no_of_retailers,
			   	IF(c.no_of_dealers is null or c.no_of_dealers = '', '--', c.no_of_dealers) as no_of_dealers,
			   	IF(c.feedback is null or c.feedback = '', '--', c.feedback) as feedback,
			   	IF(c.purpose is null or c.purpose = '', '--', c.purpose) as purpose,
			   	c.picture1,c.picture2,c.picture3,c.picture1_uri,c.picture2_uri,c.picture3_uri,
			   	IF(ch.feedback is null or ch.feedback = '', '--', ch.feedback) as cfeedback,ch.target_value,
    			ch.actual_value,u.first_name as created, uu.first_name as assignee ,IF(sa.sub_activity_name is null or sa.sub_activity_name = '', '--', sa.sub_activity_name) as sub_activity_name
			   	,GROUP_CONCAT(CONCAT(ci.image_path,ci.image_name)) as imagepath from plan_cards p
			   	inner join users uu on p.assign_to = uu.id
			   	inner join users u on p.created_by = u.id
			   	left join crops cr on cr.id = p.crop_id
			   	left join products pr on pr.id = p.product_id
			   	left join villages_master mv on mv.village_id = p.village_id
			   	inner join activity a on a.activity_name = p.activity
			   	left join campaign_card_activities c
			   	on c.plan_card_id=p.id
			   	left join channel_card_activities ch
			   	on ch.plan_card_id = p.id
			   	left join channel_card_images ci on ci.plan_card_id = p.id
			   	left join sub_activity sa on sa.sub_activity_id = c.sub_activity_id and sa.activity_id = a.activity_id
			   	where p.guid = '".$guid."'";
 
   $q = Yii::$app->db->createCommand($sql)->queryAll();
   /* $sql2 = "select ct.plan_card_id,cth.product_id,cth.product_unit,cth.liquidation_status,cth.demand_volume,
    			p.product_name,cth.season_progress from channel_card_activities ct
				JOIN channel_card_tracking_info cth ON cth.plan_card_id = ct.plan_card_id
				JOIN products p ON  p.id = cth.product_id
   				ORDER BY p.product_name"; */
   $sql2 = "select ct.plan_card_id,cth.product_id,cth.product_unit,cth.liquidation_status,cth.demand_volume,
    			p.product_name,cth.season_progress, cth.collection_value_four, cth.collection_value_five from channel_card_activities ct
				JOIN channel_card_tracking_info cth ON cth.plan_card_id = ct.plan_card_id
				JOIN products p ON  p.id = cth.product_id
   				ORDER BY p.product_name";
   $query = Yii::$app->db->createCommand($sql2)->queryAll();
   if (!empty($query)) {
	   foreach ($query as $key=>$value){
	   	$plancardProducts[$value['plan_card_id']][] = array('product_id'=>$value['product_id'], 'product_name'=>$value['product_name'],
	   			'product_unit'=>$value['product_unit'],
	   			'liquidation_status'=>$value['liquidation_status'],
	   			'demand_volume'=>$value['demand_volume'],
	   			'season_progress'=>$value['season_progress'],
	   			'collection_value_four' => $value['collection_value_four'],
	   			'collection_value_five' => $value['collection_value_five']
	   	);
	   }
   }
  /*  if (!empty($q)) {
	   foreach($q as $key=>$data){
	   	$id = $data['plan_card_id_d'];
		   	if(array_key_exists ($id,$plancardProducts) ){
		   		$q[$key]['Products'] = $plancardProducts[$id];
		   	}
   		}
   } */
//    echo '<pre>';print_r($q);exit;
     if (!empty($q)) {
     	$productIds  = array();
		if (!empty($query)) {
			foreach ($query as $key=>$value){
				$productIds[] = $value['product_id']; // get all products ids
			}
			$product_Ids = implode(',', $productIds);
				
		/* 	$partnerLabelQuery = "select temp.form_builder_id, temp.form_builder_activity_id, temp.product_id,group_concat(replace(temp.label, ' ','_') separator '~') as label, unit from (SELECT fb.form_builder_id, fb.form_builder_activity_id,  pi.product_id,fb.label, GROUP_CONCAT( pi.product_unit ) as unit
					FROM form_builder fb
					JOIN product_info pi ON pi.form_builder_activity_id = fb.form_builder_activity_id
					WHERE fb.step =1 and pi.product_id in(".$product_Ids.")
							GROUP BY fb.form_builder_id) as temp
							group by temp.form_builder_activity_id, temp.product_id"; */
			$partnerLabelQuery = "SELECT fb.form_builder_id, fb.form_builder_activity_id, fb.product_id, group_concat( replace( fb.label, ' ', '_' ) order by fb.form_builder_id
					SEPARATOR '~' ) AS label
					FROM form_builder fb
					WHERE fb.step =1
					AND fb.product_id IN (".$product_Ids.")
					GROUP BY fb.form_builder_activity_id, fb.product_id";
			$partnerLabelQueryResult = Yii::$app->db->createCommand($partnerLabelQuery)->queryAll();
				
			$productLabelArray = array();
			if(!empty($partnerLabelQueryResult)) {
				//$i = 0;
				foreach($partnerLabelQueryResult as $productResult) {
					$labelProducts[] = $productResult['product_id'];
					$productLabelArray[$productResult['label']][] = $productResult['product_id'];
				}
				$uniqProductIds = array_unique($productIds);
				$uniqProductIds = array_values($uniqProductIds);
				$result_array_diff = array_diff($uniqProductIds,$labelProducts);
				$productLabelArray['liquidation_status~demand_volume~season_progress'] = $result_array_diff;
			}
				
			foreach ($query as $value) {
				$channelLabels = array('product_id' => $value['product_id'],'product_name'=>$value['product_name'],
						'product_unit'=>$value['product_unit'],
						'liquidation_status'=>$value['liquidation_status'],
						'demand_volume'=>$value['demand_volume'],
						'season_progress'=>$value['season_progress'],
						'collection_value_four' => $value['collection_value_four'],
						'collection_value_five' => $value['collection_value_five']);
					
				$plancardProducts[$value['plan_card_id']][] = $channelLabels;
			}
		}
// 		echo '<pre>';print_r($plancardProducts);exit('111111');
		
		if (!empty($q)) {
			$product_label = array();
			foreach($q as $key=>$data){
				$id = $data['plan_card_id_d'];
				if(array_key_exists ($id,$plancardProducts) ){
					$productMap = array();
					foreach($plancardProducts[$id] as $productDetails) {
						foreach($productLabelArray as $key1 => $productfinal) {
							if(in_array($productDetails['product_id'],$productfinal)){								
								$productMap[$key1][] = $productDetails;
							}
						}

					}
					$q[$key]['Products'] = $productMap;
				}
			}
		}
		
		if(!empty($q)) {
		foreach( $q as $key => $infoArr){
			$pArr = array();
			$pArr1 = array();
			if($infoArr['activity'] == 'Channel Card'){
				if (array_key_exists('Products', $infoArr)) {
					foreach($infoArr['Products'] as $k => $pDetail){
						$labelarray = explode('~',str_replace("_"," ",$k));
						$pArr['liquidation_status'] = (isset($labelarray[0])  && $labelarray[0] != '') ? $labelarray[0] : '';
						$pArr['demand_volume'] = (isset($labelarray[1])  && $labelarray[1] != '') ? $labelarray[1] : '';
						$pArr['season_progress'] = (isset($labelarray[2])  && $labelarray[2] != '') ? $labelarray[2] : '';
						$pArr['collection_value_four'] = (isset($labelarray[3])  && $labelarray[3] != '') ? $labelarray[3] : '';
						$pArr['collection_value_five'] = (isset($labelarray[4])  && $labelarray[4] != '') ? $labelarray[4] : '';
						$pArr['product_details'] = $pDetail;
						$pArr1[] = $pArr;
					}
				} else {
					$infoArr['Products'] = array();
				}
				$infoArr['Products'] = $pArr1;
				$q[$key]['Products'] = $infoArr['Products'];
			}
		}
		}
		if(!empty($q)) {
			foreach( $q as $key => $planDetails){
				$pArr2 = array();
				if($planDetails['activity'] == 'Channel Card'){
					if (array_key_exists('Products', $planDetails)) {
						foreach($planDetails['Products'] as $productDe) {
							foreach($productDe['product_details'] as $productLa) {
								//echo '<pre>';print_r($productLa);exit;
								$productLa['liquidation_status_label'] = $productDe['liquidation_status'];
								$productLa['demand_volume_label'] = $productDe['demand_volume'];
								$productLa['season_progress_label'] = $productDe['season_progress'];
								$productLa['collection_value_four_label'] = $productDe['collection_value_four'];
								$productLa['collection_value_five_label'] = $productDe['collection_value_five'];
								$pArr2[] = $productLa;
												
							} 
						}
					} else {
						$planDetails['Products'] = array();
					}
					$planDetails['Productsk'] = $pArr2;
					$q[$key]['Products'] = array_unique($planDetails['Productsk'],SORT_REGULAR);
				}
			}
		}
		//echo '<pre>';print_r($q);exit;
		$images = $channelImages = array();
		if(!empty($q)) {
			foreach($q as $Card) {
				$images = array();
				if($Card['card_type'] != 'channel card') {
					if($Card['picture1'] != '') {
						$images[] = $Card['picture1_uri']. $Card['picture1'];
					}
					if($Card['picture2'] != '') {
						$images[] = $Card['picture2_uri']. $Card['picture2'];
					}
					if($Card['picture3'] != '') {
						$images[] = $Card['picture3_uri']. $Card['picture3'];
					}
					unset($Card['picture1_uri'],$Card['picture1'],$Card['picture2_uri'],$Card['picture2'],$Card['picture3'],$Card['picture3_uri'],$Card['imagepath']);
					$Card['images'] = $images;
					$finalData[] = $Card;
				} else {
					if($Card['imagepath'] != '' ) {
						$channelImages = explode(",",$Card['imagepath']);
					}
					unset($Card['picture1_uri'],$Card['picture1'],$Card['picture2_uri'],$Card['picture2'],$Card['picture3'],$Card['picture3_uri'],$Card['imagepath']);
					$Card['images'] = $channelImages;
					$finalData[] = $Card;
				}
			}
		}
		return $finalData;
   } else {
   	throw new NotFoundHttpException('The requested page does not exist.');
   }
 
//    	if($da->activity!='Channel Card')
//    	{
//    		$query = new \yii\db\Query;
//    		$query->select('p.id,p.planned_date,p.village_name,p.crop_name,p.product_name,p.assign_to,p.activity,p.created_by,p.plan_type,p.card_type,p.status,c.*,u.first_name as created, uu.first_name as assignee')
//    		->from('plan_cards p')
//    		->innerJoin('users uu','p.assign_to = uu.id')
//    		->innerJoin('users u','p.created_by = u.id')
//    		->leftJoin('campaign_card_activities c', 'p.id =c.plan_card_id')
//    		->where(['p.guid'=>$guid]);
//    		$command = $query->createCommand();
//    		return $response = $command->queryAll();
   		
//    	}else{
//    			$query = new \yii\db\Query;
//    		$query->select('p.id,p.planned_date,p.village_name,p.crop_name,p.product_name,p.assign_to,p.activity,p.created_by,p.plan_type,p.card_type,p.status,c.*,u.first_name as created, uu.first_name as assignee')
//    		->from('plan_cards p')
//    		->innerJoin('users uu','p.assign_to = uu.id')
//    		->innerJoin('users u','p.created_by = u.id')
//    		->leftJoin('campaign_card_activities c', 'p.id =c.plan_card_id')
//    		->where(['p.guid'=>$guid]);
//    		$command = $query->createCommand();
//    		return $response = $command->queryAll();
//    	}
   	
   }
   public function actionActivitaion($status,$id)
   {
   	if($status == 'rejected'){
   		$apr_status = 'Rejected';
   		$status='rejected';
   	}
   	else{
   		$apr_status='Approved';
   		$status='not_submitted';
   	}
   	$sql2 = Yii::$app->db->createCommand()
   	->update('plan_cards', ['status' => $status,'plan_approval_status' => $apr_status], ['id' => $id])
   	->execute();
   	Yii::$app->session->setFlash('Card has been'.$status);
   return $this->redirect(['update', 'id' =>$id]);
   	
   }
   public  function actionPlantype()
   {
   
   	$planned_date = date("Y-m-d", strtotime($_GET['planned_date']));
   	$time= date('Y-m-d H:i:s');
	$resp = Plancards::planType($planned_date,$time);
	return "<option value='".$resp."' selected>".ucfirst($resp)."</option>"; 
	
   }
   
   //plan history 
   public function actionHistory() 
   {
	$searchModel = new PlanCardSearch();
   	$model=new PlanCards();
   	$label_names_display = LabelNames::labelNamesDisplay();
   	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   	return $this->render('history', [
   			'searchModel' => $searchModel,
   			'dataProvider' => $dataProvider,
   			'model'		=>$model,
   			'label_names_display' => $label_names_display,
   	]);
   }
   
   public function actionDropdownlist() 
   {
   	if(isset($_REQUEST)) {
   		$flag = 'create';
   		$assign_to = $_REQUEST['assign_to'];
   		if ($assign_to != '') {
	   		$villages_list = Villages::villageList($assign_to, $flag);
	   		//$products_list = Products::productList($assign_to, $flag);
	   		//$crops_list = Crops::cropList($assign_to, $flag);
	   		$partners = ChannelPartners::partnersList($assign_to, $flag);
	   		$response = array($villages_list,$partners);
   		} else {
   			$response = 0;
   		}
   		return json_encode($response);
   	}
   }
   
}
