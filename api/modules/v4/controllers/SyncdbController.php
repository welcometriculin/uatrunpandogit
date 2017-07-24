<?php
namespace app\api\modules\v4\controllers;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
//use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\db\Query;
use yii;
use yii\web\UnauthorizedHttpException;
use app\api\modules\v4\models\PlanCards;
use app\api\modules\v4\models\WebService;
use app\api\modules\v4\models\Crops;
use app\api\modules\v4\models\Products;
use app\api\modules\v4\models\Villages;
use app\api\modules\v4\models\CampaignCardActivities;
use app\api\modules\v4\models\MasterProducts;
use app\api\modules\v4\models\ChannelPartners;
use app\api\modules\v4\models\Activity;
use app\api\modules\v4\models\SubActivity;
use app\api\modules\v4\models\FavCrops;
use app\api\modules\v4\models\FavProducts;
use yii\db\Expression;
use app\api\modules\v4\models\UserTravellog;
use app\api\modules\v4\models\ChannelCardActivities;
use app\api\modules\v4\models\FavVillages;
use app\api\modules\v4\models\FavChannelpartners;
use app\api\modules\v4\models\ChannelCardTrackingInfo;

class SyncdbController extends ActiveController
{
	public $modelClass = 'app\api\modules\v4\models\PlanCards';
	public function behaviors()
	{

		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
		'class' => CompositeAuth::className(),
		'authMethods' => [
		//HttpBasicAuth::className(),
		//HttpBearerAuth::className(),
		QueryParamAuth::className(),
		],
		];
		$behaviors['contentNegotiator'] = [
		'class' => ContentNegotiator::className(),
		'formats' => [
		'application/json' => Response::FORMAT_JSON,
		//'application/xml' => Response::FORMAT_XML,
		],
		];

		return $behaviors;
	}
	public function actionOrigin()
	{
		$params = Yii::$app->getRequest()->getBodyParams();
		/* $web_model = new WebService();
	 	$web_model->params = json_encode($params);
		$web_model->user_id = Yii::$app->user->identity->id;
		$web_model->save(); */
		
		if(empty($params)) {
			return ['message' => 'You do not have data to Sync','sync_status' => true];
		}
		$user_id = Yii::$app->user->identity->id;
		$last_db_id = Plancards::find()->select('id')->orderBy('id DESC')->one();
		if (array_key_exists('user_travellog', $params)) {
			$user_travellog =  $params['user_travellog'];
			foreach ($user_travellog as $travellog ) {
				$res = UserTravellog::travellogstart($travellog);
			}
			if ($res == 'Not saved') {
				$check = 0;
			}else {
				$check = 1;
			}
		}
		if (array_key_exists('plancards', $params)) {
			$cards =  $params['plancards'];
			$check = PlanCards::synCards($cards);

			$j = 1;
			for($i = 0; $i < count($cards); $i++)
			{
				$count_ids = $last_db_id['id'] + $j;
				$map['mapping_id']	 = $cards[$i]['mapping_id'];
				$map['primary_id'] = $count_ids;
				$maping_array[] = $map;
				$j++;
			}
		}
		if (array_key_exists('campaian_activity', $params)) {
			if (!empty($params['campaian_activity'])) {

				$campaign_activity = $params['campaian_activity'];
				$flag = 0;
				foreach($campaign_activity as $camp_activity)
				{
					if ($camp_activity['data']['plan_card_id'] == '') {
						$flag = 1;
					}
				}
				if ($flag == 1) {
					for($i = 0; $i < count($cards); $i++)
					{
						for($j = 0;$j < count($campaign_activity);$j++)
						{
							if (in_array($maping_array[$i]['mapping_id'],$campaign_activity[$j]['data'])) {
								$campaign_activity[$j]['data']['plan_card_id'] = $maping_array[$i]['primary_id'];
							}
						}
					}
				}

				foreach ($campaign_activity as $activity)
				{
					$res = PlanCards::dataSubmit($activity,$user_id);
				}
				if ($res == 'plan cards submitted successfully') {
					$check = 1;
				} else {
					$check = 0;
				}
			}
		}
			
		if (array_key_exists('channel_activity', $params)) {
			if (!empty($params['channel_activity'])) {
				$channel_activity = $params['channel_activity']['data'];
				$flag = 0;
				foreach($channel_activity as $ch_card_activity){
					if ($ch_card_activity['plan_card_id'] == '') {
						$flag = 1;
					}
				}
				if ($flag == 1) {
					for($i = 0; $i < count($cards); $i++)
					{
						for($j = 0;$j < count($channel_activity);$j++)
						{
							if (in_array($maping_array[$i]['mapping_id'],$channel_activity[$j])) {
								$channel_activity[$j]['plan_card_id'] = $maping_array[$i]['primary_id'];
							}
						}
					}
				}
				foreach($channel_activity as $ch_activity)
				{
					//$data = $ch_activity['data'];
					$lat = $ch_activity['lat_position'];
					$long = $ch_activity['long_position'];
					$location = $ch_activity['location_name'];
					$card_id = $ch_activity['plan_card_id'];
					$distance = $ch_activity['distance_travelled'];
					$start_time = UserTravellog::travellogstart_time(); //usertravellog start_time
					$command = yii::$app->db->createCommand('UPDATE plan_cards SET status = "submitted", lat_position = '.$lat.', long_position = '.$long.', location_name = "'.$location.'", distance_travelled = "'.$distance.'", updated_date = NOW(), start_time = "'.$start_time.'" WHERE id = "'.$card_id.'"');
					$command->execute();
					$chct_info = $ch_activity['products'];
					$check = ChannelCardTrackingInfo::trackinginfo_sync($chct_info, $ch_activity);
					$check = ChannelCardActivities::channelcardinfo_sync($ch_activity);
				}
			}
		}
		if ($check == 1) {
			return ['message' =>'success','sync_status' =>true];
		} else {
			return ['message' =>'fail','sync_status' =>false];
		}
			
	}

	public function actionOffline()
	{
		$params = Yii::$app->getRequest()->getBodyParams();
		$user_id = Yii::$app->user->identity->id;
		$webmodel = new WebService();
		$webmodel->user_id = $user_id;
		$webmodel->params = json_encode($params);
		$webmodel->service_name = 'dashboard';
		$webmodel->save(false);	 
		$offline_response = (object) [];
		$date_time = $params['date_time'];
		if(array_key_exists('offline',$params)) {
			$offline_data = $params['offline'];
			$offline_response = $this->actionOriginoffline($offline_data);
		}
		if ($date_time == '') {
			$date = "";
			$plan_card_date = "";
		} else {
			$date= "and created_date > '".$date_time."'";
			$plan_card_date= "and p.created_date > '".$date_time."'";
		}
		//dynamic form service
		$dynamicFormService = $this->actionDynamicform();
		//$cards = PlanCards::pendingCards($date,$user_id);
		$list = CampaignCardActivities::locationList();
		//$villages = Villages::villageData($date,$user_id);
		$activites = Activity::activitiesList();
		$sub_actvities = SubActivity::subActivitieslist();
		//$partners = ChannelPartners::partnersdata($date,$user_id);
		//$master_products = MasterProducts::products($params['date_time']);
		$master_products = Products::products($params['date_time']);
		$sum_pendingcards = PlanCards::countCards($user_id);
		$week_pendingcards = PlanCards::wcountCards($user_id);
		$plan_cards_data = PlanCards::getAllCards($plan_card_date);
		$orders = UserTravellog::orderValue();
		return [
		'cards'=> $plan_cards_data,
		'locations'=> $list,
		//'villages'=> $villages,
		'activites' => $activites,
		'sub_actvities' => $sub_actvities,
		//'partners' => $partners,
		'master_products' => $master_products,
		'p_count'=> $sum_pendingcards[0]['cards_count'],
		'w_count' => $week_pendingcards[0]['count'],
		'total_distance' => $sum_pendingcards['distance'],
		'time_stamp' => date('Y-m-d H:i:s'),
		'current_date' => date('Y-m-d H:i:s'),
		'offline_response' => $offline_response,
		'order' => intval($orders),
		'status' => true,
		'dynamic_form' => $dynamicFormService
		];
	}

	public function actionOriginoffline ($a = NULL)
	{
		if($a == NULL) {
			$params = Yii::$app->getRequest()->getBodyParams();
		} else {
			$params = $a;
		}
		$user_id = Yii::$app->user->identity->id;
		$comp_id = Yii::$app->user->identity->input_company_id;
		
		$webmodel = new WebService();
		$webmodel->user_id = $user_id;
		$webmodel->params = json_encode($params);
		$webmodel->service_name = 'offlinesync';
		$webmodel->save(false);
		//echo '<pre>';print_r($params);exit;
		$user_travellog = array();
		$mapping_cards = array();
		$params = (isset($params['offline'])) ? $params['offline'] : $params;
		if(empty($params)) {
			return ['message' => 'You do not have data to Sync','sync_status' => true];
		}
		$check1 = 0;
		$check2 = 0;
		$check3 = 0;
		$check4 = 0;
		$check5 = 1;
		//user travellog data insertion
		if (array_key_exists('user_travellog', $params)) {
			$timestamp_array = array();
			$user_travellog =  $params['user_travellog'];
			foreach($user_travellog as $log) {
				$timestamp_array[] = $log['mobile_timestamp'];
			}
			$travel_timestamp_check_array = UserTravellog::find()->select('mobile_timestamp')->where(['user_id' => $user_id])->andWhere(['mobile_timestamp' => $timestamp_array])->column();
			//echo '<pre>';
			//print_r($travel_timestamp_check_array);
			//exit;
			if(!empty($travel_timestamp_check_array)) {
				if(count($user_travellog) == count($travel_timestamp_check_array))
				{
					$user_travellog = array();
				} else {
					foreach($user_travellog as $travellog_details ) {
						if (in_array($travellog_details['mobile_timestamp'],$travel_timestamp_check_array)) {
							unset($travellog_details);
						} else {
							$travellog_data[] = $travellog_details;
						}
					}
					$user_travellog = $travellog_data;
				}
			}
			if(!empty($user_travellog)) {
				foreach ($user_travellog as $travellog ) {
					$tr_timestamp = $travellog['mobile_timestamp'];
					if ($tr_timestamp == 'NA') {
						$tr_timestamp = time()*1000;
					}
					$timeStampV = date("Y-m-d H:i:s", $tr_timestamp/1000);
					if ($travellog['type'] == 'start') {
						$distance = 0;
						$location_name = PlanCards::getAddress($travellog['latitude_position'], $travellog['longitude_position']);
						//$location_name = 'raybiz';
						$startTime = date("Y-m-d H:i:s", $tr_timestamp/1000);
					} else {
						$distance_location = UserTravellog::getDistance($travellog['latitude_position'],$travellog['longitude_position'],$travellog['prev_latitude_position'],$travellog['prev_longitude_position']);
						$distance = $distance_location['distnace'];
						$location_name = $distance_location['location'];
						$startTime = date("Y-m-d H:i:s", $travellog['start_time']/1000);
						/* $distance = 1;
						$location_name = 'raybiz'; */
					}
					$user_travel_log[] = array($user_id, $travellog['latitude_position'],$travellog['longitude_position'], $location_name, $distance, $startTime, $timeStampV, $travellog['type'],$tr_timestamp,'offline',$travellog['order_number']);
				}
				if(count($user_travel_log) > 0){
					$excute_query = Yii::$app->db->createCommand()->batchInsert('user_travellog', ['user_id', 'latitude_position', 'longitude_position', 'location_name', 'distance_travelled', 'start_time','date_time', 'type', 'mobile_timestamp','mode','order_number'],
							$user_travel_log)->execute();
				}
				else{
					$excute_query = false;
				}
				if ($excute_query) {
					$check1 = 1;
				} else {
					$check1 = 0;
				}
			}/*  else {
			return 'duplicates';
			} */
		}

		//user travellog data insertion
		// plancards insertion
		$mapping_plan_cards = array();
		if((array_key_exists('plancards', $params)) && count($params['plancards']) > 0) {
			$cards =  $params['plancards'];
			//echo count($cards).'<pre>';
			//print_r($cards);
			foreach($cards as $card) {
				$card_array[] = $card['mobile_timestamp'];
			}
			$mapping_cards = $cards;
			$new_mapping_ids = array();
			$plans_timestamp_check_array = Plancards::find()->select('id, mobile_timestamp')->where(['assign_to' => $user_id])->andWhere(['mobile_timestamp' => $card_array])->asArray()->all();
			//echo count($plans_timestamp_check_array).'<pre>';print_r($plans_timestamp_check_array);exit;
			$plans_timestamp_check_arr = array();
			$plans_timestamp_check_arr1 = array();
			foreach($plans_timestamp_check_array as $plans_timestamp_check_array1){
				$plans_timestamp_check_arr[] = $plans_timestamp_check_array1['mobile_timestamp'];
				$plans_timestamp_check_arr1[$plans_timestamp_check_array1['mobile_timestamp']] = $plans_timestamp_check_array1['id'];
			}

			$plans_timestamp_check_array_cnt = count($plans_timestamp_check_array);
			if(!empty($plans_timestamp_check_array)) {
				$cards_cnt = count($cards);
				if($cards_cnt == $plans_timestamp_check_array_cnt)
				{
					foreach($cards as $key => $card_details ) {
						if (in_array($card_details['mobile_timestamp'],$plans_timestamp_check_arr)) {
							$mapping_plan_cards[] = array('plan_card_id' => $plans_timestamp_check_arr1[$card_details['mobile_timestamp']], 'mapping_id' =>$card_details['mapping_id']);
							//unset($card_details);
						}
					}
					$cards = array();

				} else {
					$mappingIds = $finalCardsData = $new_mapping_ids = array();
					foreach($cards as $key1 => $card_details) {
						foreach($plans_timestamp_check_array as $plans_timestamp_check_array1){
							if (in_array($card_details['mobile_timestamp'],$plans_timestamp_check_array1)) {
								$mappingIds[] = $card_details['mapping_id'];
								$mapping_plan_cards[] = array('plan_card_id' => strval($plans_timestamp_check_array1['id']), 'mapping_id' =>$card_details['mapping_id']);
								//unset($card_details);
							}
							else {
								$new_mapping_ids[] = $card_details['mapping_id'];
								//$cards_data[] = $card_details;
							}
						}
						/*if (array_key_exists($key1, $plans_timestamp_check_array) && in_array($card_details['mobile_timestamp'],$plans_timestamp_check_array[$key1])) {
							//echo 'sai';echo 'else';exit;
						$mapping_plan_cards[] = array('plan_card_id' => strval($plans_timestamp_check_array[$key1]['id']), 'mapping_id' =>$card_details['mapping_id']);
						unset($card_details);
						}
						else {
						echo 'hhh';
						$new_mapping_ids[] = $card_details['mapping_id'];
						$cards_data[] = $card_details;
						}*/
					}
					if(!empty($mappingIds)) {
						if(!empty($cards)) {
							foreach($cards as $planCard) {
								if(!in_array($planCard['mapping_id'],$mappingIds)) {
									$finalCardsData[] = $planCard;
								}
							}
						}
						$cards = $finalCardsData;
					}
					//$cards = $cards_data;
					$new_mapping_ids = array_unique($new_mapping_ids);
				}
			}
// 			echo '<pre>';print_r($new_mapping_ids);
// 			echo '<pre>';print_r($cards);exit;
			/* echo '<pre>sdsdfd';
			print_r($new_mapping_ids);
			echo '<pre>';print_r($mapping_plan_cards);

			exit;*/
			$plan_query = "insert into plan_cards (guid, planned_date, plan_type, assign_to, card_type, village_id, channel_partner, crop_name, product_name, activity, created_date, updated_date, created_by, updated_by, mobile_timestamp) values";
			$plan_query2 = '';
			// 			$crop_query = "insert into crops (guid, crop_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			// 			$crop_query2 = '';
			/* 	$village_query = "insert into villages (guid, village_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$village_query2 = ''; */
			// 			$product_query = "insert into products (guid, product_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			// 			$product_query2 = '';
			/* $chp_query = "insert into channel_partners (guid, channel_partner_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$chp_query2 = ''; */
			//echo '<pre>';
			//print_r($cards);exit;
			$plan_timestamp_arr = array();
			if (!empty($cards)) {
				foreach ($cards as $card) {
					$new_mapping_ids[] = $card['mapping_id'];
					$plan_date = date("Y-m-d", strtotime($card['planned_date']));
					$plan_type = PlanCards::planType($plan_date,date('Y-m-d H:i:s'));
					if ($card['activity'] == "Partner Visit") {
						$card['crop_id'] = 0;
						$card['product_id'] = 0;
						$card['activity'] = 'channel card';
						$card_type = 'channel card';
					} else {
						$card_type = 'campaign card';
						$card['channel_partner'] = NULL;
					}
					$guid = Yii::$app->guid->generate();
					$plan_timestamp = $card['mobile_timestamp'];
					if ($plan_timestamp == 'NA') {
						$plan_timestamp = time()*1000;
					}
					$planTimestamp = date("Y-m-d H:i:s", $plan_timestamp/1000);
					$plan_timestamp_arr[] = $plan_timestamp;
					$plans_data[] = array($guid,$plan_date,$plan_type,$user_id,$card_type,$card['village_id'],$card['channel_partner'],$card['crop_id'],$card['product_id'],$card['activity'],$planTimestamp,$planTimestamp,$user_id,$user_id,$plan_timestamp,'offline');
					// 					$plan_query2 .= "('".$guid."', '".$plan_date."', '".$plan_type."', '".$user_id."', '".$card_type."', '".$card['village_name']."', '".$card['channel_partner']."', '".$card['crop_name']."', '".$card['product_name']."', '".$card['activity']."', NOW(), NOW(), '".$user_id."', '".$user_id."', $plan_timestamp),";

					/*$res = PlanCards::cardCreate($card);
					 $count1 = Yii::$app->db->createCommand("select count(*) from crops where crop_name = '".$card['crop_name']."' and user_id='".$user_id."'")->queryScalar();
					$count2 = Yii::$app->db->createCommand("select count(*) from villages where village_name = '".$card['village_name']."' and user_id='".$user_id."'")->queryScalar();
					$count3 = Yii::$app->db->createCommand("select count(*) from products where product_name = '".$card['product_name']."' and user_id='".$user_id."'")->queryScalar();
					$count4 = Yii::$app->db->createCommand("select count(*) from channel_partners where channel_partner_name = '".$card['channel_partner']."' and user_id='".$user_id."'")->queryScalar();
					if ($count1 < 1 && $card['crop_name'] != '') {
					$guid_crop = Yii::$app->guid->generate();
					$crop_query2 .= "('".$guid_crop."', '".$card['crop_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
					}
					if ($count2 < 1 && $card['village_name']  != '') {
					$guid_village = Yii::$app->guid->generate();
					$village_query2 .= "('".$guid_village."', '".$card['village_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
					}
					if ($count3 < 1 && $card['product_name'] != '') {
					$guid_product = Yii::$app->guid->generate();
					$product_query2 .= "('".$guid_product."', '".$card['product_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
					}
					if ($count4 < 1 && $card['channel_partner'] != '') {
					$guid_chpartner = Yii::$app->guid->generate();
					$chp_query2 .= "('".$guid_chpartner."', '".$card['channel_partner']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
					} */
				}


				/* if ($crop_query2 != '') {
					$crop_query3 = trim($crop_query2, ',');
				$crop_query4 = $crop_query.$crop_query3;
				$crop_insert = Yii::$app->db->createCommand($crop_query4)->execute();
				} else {
				$crop_insert = false;
				}

				if ($village_query2 != '') {
				$village_query3 = trim($village_query2, ',');
				$village_query4 = $village_query.$village_query3;
				$village_insert = Yii::$app->db->createCommand($village_query4)->execute();
				} else {
				$village_insert = false;
				}

				if ($product_query2 != '') {
				$product_query3 = trim($product_query2, ',');
				$product_query4 = $product_query.$product_query3;
				$product_insert = Yii::$app->db->createCommand($product_query4)->execute();
				} else {
				$product_insert = false;
				}
				if ($chp_query2 != '') {
				$chp_query3 = trim($chp_query2, ',');
				$chp_query4 = $chp_query.$chp_query3;
				$chp_insert = Yii::$app->db->createCommand($chp_query4)->execute();
				} else {
				$chp_insert = false;
				} */
				if (count($plans_data)>0) {
					$plan_query3 = trim($plan_query2, ',');
					$plan_query4 = $plan_query . $plan_query3;
					$plan = Yii::$app->db->createCommand()->batchInsert('plan_cards', ['guid', 'planned_date', 'plan_type', 'assign_to', 'card_type', 'village_id', 'channel_partner', 'crop_id', 'product_id', 'activity', 'created_date', 'updated_date', 'created_by', 'updated_by', 'mobile_timestamp','creation_mode'],
							$plans_data)->execute();
					// 					$plan = Yii::$app->db->createCommand($plan_query4)->execute();
					$last_plan_id = Yii::$app->db->getLastInsertID();
					$mapping_plan_cards[] = array('plan_card_id' => strval($last_plan_id), 'mapping_id' => $new_mapping_ids[0]);
					for ($i = 1; $i< count($cards); $i++) {
						$last_plan_id++;
						$mapping_plan_cards[] = array('plan_card_id' => strval($last_plan_id), 'mapping_id' => $new_mapping_ids[$i]);

					}
				} else {
					$plan = false;
				}
				if ($plan) {
					$check2 = 1;
				} else {
					$check2 = 0;
				}
			}
		}
		// plancards insertion ending
		//echo '<pre>';
		//print_r($mapping_plan_cards);exit;
		$mapping_ids_arr = array();
		if (!empty($mapping_plan_cards)) {
			foreach ($mapping_plan_cards as $map_ids) {
				$mapping_ids_arr[] = $map_ids['mapping_id'];
			}
		}
		//print_r($mapping_ids_arr);exit;
		//$assign_plan_cards = array();
		if (array_key_exists('campaian_activity', $params) && count($params['campaian_activity']) > 0) {
			$campaign_activity  = $params['campaian_activity'];
			$planCardDist  = array();
			//echo '<pre>';print_r($campaign_activity);exit;
			foreach($campaign_activity as $key1 => $campaign_activity_details){
				if($campaign_activity_details['data']['plan_card_id'] != ''){
					//break;
					//$assign_plan_cards[] = $campaign_activity_details['data']['plan_card_id'];
					if (!in_array( $campaign_activity_details['data']['plan_card_id'] , $mapping_ids_arr)) {
						$mapping_plan_cards[] = array('plan_card_id' => strval($campaign_activity_details['data']['plan_card_id']), 'mapping_id'=> $campaign_activity_details['data']['plan_card_id']);
					}
				}
				//echo '<pre>';print_r($mapping_plan_cards);
				foreach($mapping_plan_cards as $key => $mapping_plan_card){

					if(in_array($mapping_plan_card['mapping_id'], $campaign_activity_details['data'])){
						$campaign_activity[$key1]['data']['plan_card_id'] = $mapping_plan_card['plan_card_id'];
					}
				}
			}
			//echo '<pre>';print_r($campaign_activity);exit;
			foreach($campaign_activity as $camp) {
				$camp_time_array[] = $camp['data']['mobile_timestamp'];
			}
			$camp_arrays_values = array();
			$camp_timestamp_check_array = CampaignCardActivities::find()->select('plan_card_id, mobile_timestamp')->where(['userid' => $user_id])->andWhere(['mobile_timestamp' => $camp_time_array])->asArray()->all();
			$camp_timestamp_check_arr = array();
			foreach($camp_timestamp_check_array as $camp_timestamp_check_array1){
				$camp_timestamp_check_arr[] = $camp_timestamp_check_array1['mobile_timestamp'];
			}
			$camp_timestamp_check_array_cnt = count($camp_timestamp_check_arr);
			if(!empty($camp_timestamp_check_arr)) {
				$campaign_activity_cnt = count($campaign_activity);
				if($campaign_activity_cnt == $camp_timestamp_check_array_cnt)
				{
					$campaign_activity = array();
				}
				else {
					foreach($campaign_activity as $key1 => $camp_details) {
						if (in_array($camp_details['data']['mobile_timestamp'],$camp_timestamp_check_arr)) {
							unset($camp_details);
						}
						else {
							$camp_arrays_values[] = $camp_details;
						}
					}
					$campaign_activity = $camp_arrays_values;
				}
			}
			/* echo '<pre>';print_r($assign_plan_cards);
			 echo '<pre>';print_r($mapping_plan_cards);*/
			//echo '<pre>';print_r($campaign_activity);exit;
			if(count($campaign_activity) >0) {
				foreach($campaign_activity as $activity) {
					$data = $activity['data'];
					$card_id = $data['plan_card_id'];
					$guid2 = Yii::$app->guid->generate();
					$sub_activity_id = $data['sub_activity_id'];
					$camp_timestamp = $data['mobile_timestamp'];
					$lat = $data['lat_position'];
					$long = $data['long_position'];
					//$start_time = UserTravellog::travellogstart_time();
					$start_time = date("Y-m-d H:i:s", $data['start_time']/1000);
					$prev_lat = $data['prev_lat_position'];
					$prev_long = $data['prev_long_position'];
					if ($camp_timestamp == 'NA') {
						$camp_timestamp = time()*1000;
					}
					$MobileStamp = date("Y-m-d H:i:s", $camp_timestamp/1000);
					$camp_array[] = array($guid2,$card_id,$data['contacted_person_name'],$data['contacted_person_phone'], $sub_activity_id, $data['no_of_farmers'],$data['no_of_female_farmers'],$data['no_of_retailers'],$data['no_of_villages'],$data['no_of_dealers'],$data['feedback'],$data['purpose'],$user_id,$MobileStamp,$user_id,$MobileStamp,$user_id,$camp_timestamp,'offline');
					$distance_location = UserTravellog::getDistance($lat,$long,$prev_lat,$prev_long);
					$distance = $distance_location['distnace'];
					$location = $distance_location['location'];
					$start_timestamp = date("Y-m-d H:i:s", $data['start_time']/1000);
					/* $distance = 1;
					$location = 'raybiz'; */
					$ord_value = $data['order_number'];
					$planCardDist[$activity['data']['plan_card_id']] = ['lat' => $lat, 'long' => $long, 'distance' => $distance, 'location' => $location, 'start_time' => $start_time,'updated_date' => $MobileStamp,'order_number' => $ord_value];

				}
				//echo '<pre>';print_r($camp_array);exit;
				$sql_insert = Yii::$app->db->createCommand()->batchInsert('campaign_card_activities', ['guid', 'plan_card_id', 'contacted_person_name', 'contacted_person_phone', 'sub_activity_id', 'no_of_farmers', 'no_of_female_farmers', 'no_of_retailers', 'no_of_villages', 'no_of_dealers', 'feedback', 'purpose', 'userid','created_date', 'created_by', 'updated_date', 'updated_by', 'mobile_timestamp','mode'],
						$camp_array)->execute();
				if(!$sql_insert) {
					$check3 = 0;
					$res = 'plan card inserting failure';
				} else {
					$check3 = 1;
					$res = 'plan cards submitted successfully';
					foreach($planCardDist as $key => $card_id)	{
						$sql = Yii::$app->db->createCommand()->update("plan_cards", ['status' => 'submitted', 'lat_position' => $card_id['lat'], 'long_position' => $card_id['long'],'location_name' => $card_id['location'], 'distance_travelled' => $card_id['distance'], 'start_time' => $card_id['start_time'], 'updated_date' => $card_id['updated_date'], 'submission_mode' => 'offline','order_number' => $card_id['order_number']],"id = $key")->execute();
					}
				}
			}

			/*if(count($mapping_plan_cards) > 0){
				return
			}*/

		}

		if (array_key_exists('channel_activity', $params) && count($params['channel_activity']) > 0) {
			if(!empty($params['channel_activity']['data'])) {
				$channel_activity = $params['channel_activity']['data'];
				foreach($channel_activity as $key1 => $channel_activity_details){
					if($channel_activity_details['plan_card_id'] != ''){
						//break;
						//$assign_plan_cards[] = $campaign_activity_details['data']['plan_card_id'];
						if (!in_array( $channel_activity_details['plan_card_id'] , $mapping_ids_arr)) {
							$mapping_plan_cards[] = array('plan_card_id' => strval($channel_activity_details['plan_card_id']), 'mapping_id'=> $channel_activity_details['plan_card_id']);
						}
					}
					foreach($mapping_plan_cards as $key => $mapping_channel_plan_card){

						if(in_array($mapping_channel_plan_card['mapping_id'], $channel_activity_details)){
							$channel_activity[$key1]['plan_card_id'] = $mapping_channel_plan_card['plan_card_id'];
							//$distance_location = UserTravellog::getDistance($lat,$long,$prev_lat,$prev_long);
						}
					}
				}
				//echo '<pre>';print_r($campaign_activity);exit;
				foreach($channel_activity as $camp) {
					$channel_time_array[] = $camp['mobile_timestamp'];
				}
				$channel_arrays_values = array();
				$channel_timestamp_check_array = ChannelCardActivities::find()->select('plan_card_id, mobile_timestamp')->where(['userid' => $user_id])->andWhere(['mobile_timestamp' => $channel_time_array])->asArray()->all();
				$channel_timestamp_check_arr = array();
				foreach($channel_timestamp_check_array as $channel_timestamp_check_array1){
					$channel_timestamp_check_arr[] = $channel_timestamp_check_array1['mobile_timestamp'];
				}
				$channel_timestamp_check_array_cnt = count($channel_timestamp_check_arr);
				if(!empty($channel_timestamp_check_arr)) {
					$channel_activity_cnt = count($channel_activity);
					if($channel_activity_cnt == $channel_timestamp_check_array_cnt)
					{
						$channel_activity = array();
					}
					else {
						foreach($channel_activity as $key1 => $channel_details) {
							if (in_array($channel_details['mobile_timestamp'],$channel_timestamp_check_arr)) {
								unset($channel_details);
							}
							else {
								$channel_arrays_values[] = $channel_details;
							}
						}
						$channel_activity = $channel_arrays_values;
					}
				}
				if(count($channel_activity) >0 ){
					$ch_products = array();
					foreach($channel_activity as $ch_activity) {
						$lat2 = $ch_activity['lat_position'];
						$long2 = $ch_activity['long_position'];
						$prev_lat2 = $ch_activity['prev_lat_position'];
						$prev_long2 = $ch_activity['prev_long_position'];
						$card_id3 = $ch_activity['plan_card_id'];
						$chact_timestamp = $ch_activity['mobile_timestamp'];
						$guid3 = Yii::$app->guid->generate();
						$guid4 = Yii::$app->guid->generate();
						//$start_time2 = UserTravellog::travellogstart_time();
						$prev_lat2 = $ch_activity['prev_lat_position'];
						$prev_long2 = $ch_activity['prev_long_position'];
						if ($chact_timestamp == 'NA') {
							$chact_timestamp = time() * 1000;
						}
						$MobileTimeStampV = date("Y-m-d H:i:s", $chact_timestamp/1000);
						$channel_distance_location = UserTravellog::getDistance($lat2,$long2,$prev_lat2,$prev_long2);
						$channel_distance = $channel_distance_location['distnace'];
						$channel_location = $channel_distance_location['location'];
						/* $channel_distance = 1;
						$channel_location = 'raybiz'; */
						$OrderValue = $ch_activity['order_number'];
						$start_time2 = date("Y-m-d H:i:s", $ch_activity['start_time']/1000);
						$channelplanCardDist[$ch_activity['plan_card_id']] = ['lat' => $lat2, 'long' => $long2, 'distance' => $channel_distance, 'location' => $channel_location, 'start_time' => $start_time2,'updated_date' => $MobileTimeStampV, 'order_number' => $OrderValue];
						$channel_array[] = array($guid3,$card_id3, $ch_activity['feedback'], $user_id, $MobileTimeStampV, $user_id, $MobileTimeStampV, $user_id, $chact_timestamp,'offline');
						$products = $ch_activity['products'];
						if (!empty($products)) {
							foreach($products as $product) {
								$ch_products[] = array($guid4,$card_id3,$product['product_id'],$product['product_unit'],$product['liquidation_status'],$product['demand_volume'],$product['season_progress'],$product['collection_value_four'],$product['collection_value_five'],$user_id,$MobileTimeStampV,$user_id,$MobileTimeStampV);
							}
						}
					}
					$channel_insert = Yii::$app->db->createCommand()->batchInsert('channel_card_activities', ['guid', 'plan_card_id','feedback', 'userid', 'created_date', 'created_by', 'updated_date', 'updated_by', 'mobile_timestamp','mode'],
							$channel_array)->execute();
					if (!empty($ch_products)) {
						$channel_track_insert = Yii::$app->db->createCommand()->batchInsert('channel_card_tracking_info', ['guid', 'plan_card_id','product_id', 'product_unit', 'liquidation_status', 'demand_volume','season_progress','collection_value_four','collection_value_five', 'created_by', 'created_date', 'updated_by','updated_date'],
								$ch_products)->execute();
					}
					if($channel_insert || $channel_track_insert) {
						$check4 = 1;
						$res = 'plan cards submitted successfully';
						foreach($channelplanCardDist as $key => $card_id)	{
							$sql = Yii::$app->db->createCommand()->update("plan_cards", ['status' => 'submitted', 'lat_position' => $card_id['lat'], 'long_position' => $card_id['long'],'location_name' => $card_id['location'], 'distance_travelled' => $card_id['distance'], 'start_time' => $card_id['start_time'], 'updated_date' => $card_id['updated_date'], 'submission_mode' => 'offline','order_number' => $card_id['order_number']],"id = $key")->execute();
						}
					} else {
						$check4 = 0;
						$res = 'plan card inserting failure';
					}
				}
		 }
		}
		//return $check1.$check2.$check2.$check2;
//echo '<pre>';print_r($params['offline']['update_fev']);;
         $params = (isset($params['offline'])) ? $params['offline'] : $params;
		if ( $params['update_fev'] == 1 || $params['update_fev'] == 1 ) {
			   //echo 'dfdsfdg'; exit;
			if (array_key_exists('crops_favourites', $params)) {
				$crops_favourites = FavCrops::favCropsInsert($params, $user_id);
			}
			if (array_key_exists('products_favourites', $params)) {
				$products_fav = $params['products_favourites'];
				if (array_key_exists('campaign_favourites', $products_fav)) {
					$products_favourites = FavProducts::favProductsInsert($products_fav, $user_id);
				}
				if (array_key_exists('channel_favourites', $products_fav)) {
					$products_favourites = FavProducts::favProductsInsert($products_fav, $user_id);
				}

			}
			if (array_key_exists('villages_favourites', $params)) {
				$village_favourites = FavVillages::favVillagesInsert($params, $user_id);
			}
			if (array_key_exists('channel_partners_favourites', $params)) {
				$channel_partners_favourites = FavChannelpartners::favPartnersInsert($params, $user_id);
			}

			if ($crops_favourites || $products_favourites || $channel_partners_favourites || $village_favourites) {
				$check5 = 1;
			} else {
				$check5 = 0;
			}
		}
		$crops = Crops::masteCrops();
		$products = Products::masteProducts();
		$master_villages = Villages::masterVillages();
		$master_retailers = ChannelPartners::masertRetailers();

		 $webmodel1 = new WebService();
		 $webmodel1->user_id = $user_id;
		$webmodel1->params = json_encode($mapping_plan_cards);
		$webmodel1->service_name = 'offline respnse';
		$webmodel1->save(false); 
		if ($check1 == 1 || $check2 == 1 || $check3 == 1 || $check4 == 1 || $check5 == 1) {
			return ['plan_cards' => $mapping_plan_cards, 'crops' => $crops, 'products' => $products, 'villages' => $master_villages, 'channel_partners' => $master_retailers,  'sync_status' => true];
		} else {
			return ['plan_cards' => $mapping_plan_cards,'sync_status' => true];
			//return ['message' => 'syncing failure','status' => false];
		}

	}

	public function actionImagesync()
	{

		$params = Yii::$app->request->bodyParams;
		$user_id = Yii::$app->user->identity->id;
		 $web_model = new WebService();
		 $web_model->user_id = $user_id;
		$web_model->params = json_encode($params);
		$web_model->service_name = 'image service';
		$web_model->save(false);  
		$images = PlanCards::imagesSyncing($params,$user_id);
		return $images;
	}

	public function actionOriginofflineold()
	{
		$params = Yii::$app->getRequest()->getBodyParams();
		/* $web_model = new WebService();
		 $web_model->params = json_encode($params);
		$web_model->user_id = Yii::$app->user->identity->id;
		$web_model->save(); */
		if(empty($params)) {
			return ['message' => 'You do not have data to Sync','sync_status' => true];
		}
		$user_id = Yii::$app->user->identity->id;
		$comp_id = Yii::$app->user->identity->input_company_id;
		//$last_db_id = Plancards::find()->select('id')->orderBy('id DESC')->one();
		$last_db_id['id'] = 0;
		$check1 = 0;
		$check2 = 0;
		$check3 = 0;
		$check4 = 0;
		$check5 = 1;
		$cht_result = 0;
		$ch_act_result = 0;
		if (array_key_exists('user_travellog', $params)) {
			$user_travellog =  $params['user_travellog'];
			$sql2 = '';
			$sql = "insert ignore into user_travellog (user_id, latitude_position, longitude_position, location_name, distance_travelled, start_time, type, mobile_timestamp) values";
			foreach ($user_travellog as $travellog ) {
				$tr_timestamp = $travellog['mobile_timestamp'];
				if ($tr_timestamp == 'NA') {
					$tr_timestamp = 'RAND()';
				}
				$lattitude = $travellog['latitude_position'];
				$longitude = $travellog['longitude_position'];
				if ($travellog['location_name'] == 'Location not available') {
					$travellog['location_name'] = PlanCards::getAddress($lattitude, $longitude);
				}
				$sql2 .= "('".$user_id."', '".$travellog['latitude_position']."', '".$travellog['longitude_position']."', '".$travellog['location_name']."', '".$travellog['distance_traveled']."', NOW(), '".$travellog['type']."', $tr_timestamp),";
			}
			if($sql2 != ''){
				$sql3 = trim($sql2, ',');
				$s = $sql.$sql3;
				//echo $s;
				$z = Yii::$app->db->createCommand($s)->execute();
			}
			else{
				$z = false;
			}
			if ($z) {
				$check1 = 1;
			} else {
				$check1 = 0;
			}
		}
		if (array_key_exists('plancards', $params)) {
			$cards =  $params['plancards'];
			$plan_query2 = '';
			$plan_query = "insert ignore into plan_cards (guid, planned_date, plan_type, assign_to, card_type, village_name, channel_partner, crop_name, product_name, activity, created_date, updated_date, created_by, updated_by, mobile_timestamp) values";
			$crop_query = "insert into crops (guid, crop_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$crop_query2 = '';
			$village_query = "insert into villages (guid, village_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$village_query2 = '';
			$product_query = "insert into products (guid, product_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$product_query2 = '';
			$chp_query = "insert into channel_partners (guid, channel_partner_name, comp_id, user_id, created_by, created_date, updated_by, updated_date) values";
			$chp_query2 = '';
			//echo '<pre>';
			//print_r($cards);exit;
			$plan_timestamp_arr = array();
			foreach($cards as $card)
			{
					
				$plan_date = date("Y-m-d", strtotime($card['planned_date']));
				$plan_type = PlanCards::planType($plan_date,date('Y-m-d H:i:s'));
				if($card['activity'] == "Partner Visit")
				{
					$card['activity'] = 'channel card';
					$card_type = 'channel card';
				} else {
					$card_type = 'campaign card';
				}
				$guid = Yii::$app->guid->generate();
				$plan_timestamp = $card['mobile_timestamp'];
				if ($plan_timestamp == 'NA') {
					$plan_timestamp = 'RAND()';
				}
				$plan_timestamp_arr[] = $plan_timestamp;
				$plan_query2 .= "('".$guid."', '".$plan_date."', '".$plan_type."', '".$user_id."', '".$card_type."', '".$card['village_name']."', '".$card['channel_partner']."', '".$card['crop_name']."', '".$card['product_name']."', '".$card['activity']."', NOW(), NOW(), '".$user_id."', '".$user_id."', $plan_timestamp),";
					
				//$res = PlanCards::cardCreate($card);
				$count1 = Yii::$app->db->createCommand("select count(*) from crops where crop_name = '".$card['crop_name']."' and user_id='".$user_id."'")->queryScalar();
				$count2 = Yii::$app->db->createCommand("select count(*) from villages where village_name = '".$card['village_name']."' and user_id='".$user_id."'")->queryScalar();
				$count3 = Yii::$app->db->createCommand("select count(*) from products where product_name = '".$card['product_name']."' and user_id='".$user_id."'")->queryScalar();
				$count4 = Yii::$app->db->createCommand("select count(*) from channel_partners where channel_partner_name = '".$card['channel_partner']."' and user_id='".$user_id."'")->queryScalar();
				if ($count1 < 1 && $card['crop_name'] != '') {
					$guid_crop = Yii::$app->guid->generate();
					$crop_query2 .= "('".$guid_crop."', '".$card['crop_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
				}
				if ($count2 < 1 && $card['village_name']  != '') {
					$guid_village = Yii::$app->guid->generate();
					$village_query2 .= "('".$guid_village."', '".$card['village_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
				}
				if ($count3 < 1 && $card['product_name'] != '') {
					$guid_product = Yii::$app->guid->generate();
					$product_query2 .= "('".$guid_product."', '".$card['product_name']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
				}
				if ($count4 < 1 && $card['channel_partner'] != '') {
					$guid_chpartner = Yii::$app->guid->generate();
					$chp_query2 .= "('".$guid_chpartner."', '".$card['channel_partner']."', '".$comp_id."', '".$user_id."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
				}
			}
			if($crop_query2 != ''){
				$crop_query3 = trim($crop_query2, ',');
				$crop_query4 = $crop_query.$crop_query3;
				$crop_insert = Yii::$app->db->createCommand($crop_query4)->execute();
			}
			else{
				$crop_insert = false;
			}

			if($village_query2 != ''){
				$village_query3 = trim($village_query2, ',');
				$village_query4 = $village_query.$village_query3;
				$village_insert = Yii::$app->db->createCommand($village_query4)->execute();
			}
			else{
				$village_insert = false;
			}

			if($product_query2 != ''){
				$product_query3 = trim($product_query2, ',');
				$product_query4 = $product_query.$product_query3;
				$product_insert = Yii::$app->db->createCommand($product_query4)->execute();
			}
			else{
				$product_insert = false;
			}
			if($chp_query2 != ''){
				$chp_query3 = trim($chp_query2, ',');
				$chp_query4 = $chp_query.$chp_query3;
				$chp_insert = Yii::$app->db->createCommand($chp_query4)->execute();
			}
			else{
				$chp_insert = false;
			}
			//echo $plan_query2;exit;
			if($plan_query2 != ''){
				$plan_query3 = trim($plan_query2, ',');
				$plan_query4 = $plan_query . $plan_query3;
				//echo $plan_query4;
				$plan = Yii::$app->db->createCommand($plan_query4)->execute();
					
				//$plan_times = implode(',',$plan_timestamp_arr);
				if($plan == false){
					$plan_rec = Plancards::find()->select('id')->where(['mobile_timestamp' => $plan_timestamp_arr])->orderBy('id asc')->asArray()->one();
					//echo '<pre>';
					//print_r($plan_rec);
					$last_db_id['id'] = $plan_rec['id']-1;
					$plan = true;
				}
				else{
					$last_plan_id = Yii::$app->db->getLastInsertID();
					$last_db_id['id'] = $last_plan_id-1;
				}
					
			}
			else{
				//echo implode(',',$plan_timestamp_arr);
				$plan = false;
			}
			//echo 'fdf'.$plan;exit;
			if ($plan || $crop_insert || $village_insert  || $product_insert  || $chp_insert) {
				$check2 = 1;
			} else {
				$check2 = 0;
			}

			$j = 1;
			$maping_array = array();
			$map = array();
			for($i = 0; $i < count($cards); $i++)
			{
				$count_ids = $last_db_id['id'] + $j;
				$map['mapping_id']	 = $cards[$i]['mapping_id'];
				$map['primary_id'] = $count_ids;
				$maping_array[] = $map;
				$j++;
			}
		}
		if (array_key_exists('campaian_activity', $params)) {
			if (!empty($params['campaian_activity'])) {

				$campaign_activity = $params['campaian_activity'];
					
				$flag = 0;
				foreach($campaign_activity as $camp_activity)
				{
					if ($camp_activity['data']['plan_card_id'] == '') {
						$flag = 1;
					}
				}
				if ($flag == 1) {
					for($i = 0; $i < count($cards); $i++)
					{
						for($j = 0;$j < count($campaign_activity);$j++)
						{
							if (in_array($maping_array[$i]['mapping_id'],$campaign_activity[$j]['data'])) {
								$campaign_activity[$j]['data']['plan_card_id'] = $maping_array[$i]['primary_id'];
							}
						}
					}
				}

				$camp_query = "insert ignore into campaign_card_activities (guid, plan_card_id, contacted_person_name, contacted_person_phone, no_of_farmers, no_of_female_farmers, no_of_retailers, no_of_villages, no_of_dealers, feedback, purpose, sub_activity_id, picture1, picture2, picture3, picture1_uri, picture2_uri, picture3_uri, userid, created_date, created_by, updated_date, updated_by, mobile_timestamp) values";
				$camp_query2 = '';
				//echo '<pre>';
				//print_r($campaign_activity);exit;
				foreach ($campaign_activity as $activity)
				{

					//$res = PlanCards::dataSubmit($activity,$user_id);

					$model1 = new CampaignCardActivities();
					$model2 = new PlanCards();
					$data = $activity['data'];
					$card_id = $activity['data']['plan_card_id'];
					$images = $activity['images'];
					$guid2 = Yii::$app->guid->generate();
					$card_status_before_submit = PlanCards::find()->select('status')->where(['id' => $card_id])->one();
					if ($card_status_before_submit['status'] != 'rejected') {
							
						$de_images = PlanCards::decodeImage($images,$user_id,$model1);
						if($data['activity'] != 'Partner Visit')
						{
							$userid = $user_id;
							if (array_key_exists("activity",$data))
							{
								unset($data['activity'],$data['lat_position'],$data['long_position'],$data['location_name']);
							}

							$pic = 'picture';
							$j = 1;
							$pics = array();
							$uris = array();
							$count = (count($de_images) < 3)?3:count($de_images);
							for($i = 0; $i < $count; $i++)
							{
								$pictures = $pic.$j;
								$uri = $pictures.'_uri';
								if (isset($de_images[$i])) {
									$pics[$pictures] = end((explode("/",$de_images[$i])));
									$uris[$uri] = str_replace(end((explode("/",$de_images[$i]))),'',str_replace('../web/','',$de_images[$i]));
								} else {
									$pics[$pictures] = '';
									$uris[$uri] = '';
								}
								$j++;
							}
							$camp_timestamp = $data['mobile_timestamp'];
							if ($camp_timestamp == 'NA') {
								$camp_timestamp = 'RAND()';
							}
							$camp_query2 .= "('".$guid2."', '".$card_id."', '".$data['contacted_person_name']."', '".$data['contacted_person_phone']."', '".$data['no_of_farmers']."', '".$data['no_of_female_farmers']."', '".$data['no_of_retailers']."', '".$data['no_of_villages']."', '".$data['no_of_dealers']."',
									'".$data['feedback']."', '".$data['purpose']."', '".$data['sub_activity_id']."', '".$pics['picture1']."','".$pics['picture2']."', '".$pics['picture3']."',
											'".$uris['picture1_uri']."', '".$uris['picture2_uri']."', '".$uris['picture3_uri']."', '".$user_id."', NOW(), '".$user_id."', NOW(), '".$user_id."', $camp_timestamp),";

						} else {
							$res = 'channel card activity';
						}
					} else {
						$res = 'Card is rejected';
					}
				}
				if($camp_query2 != ''){
					$camp_query3 = trim($camp_query2, ',');
					$camp_query4 = $camp_query.$camp_query3;
					//echo $camp_query4;exit;
					$campaigns = Yii::$app->db->createCommand($camp_query4)->execute();
				}
				else{
					$campaigns = false;
				}
					
				if(!$campaigns) {
					$res = 'plan card inserting failure';
				} else {
					$res = 'plan cards submitted successfully';
					foreach ($campaign_activity as $activity2)
					{
						$data2 = $activity2['data'];
						$card_id2 = $activity2['data']['plan_card_id'];
						$lat = $data2['lat_position'];
						$long = $data2['long_position'];
						$location = $data2['location_name'];
						if ($location == 'Location not available') {
							$location = PlanCards::getAddress($lat, $long);
						}
						$distance = $data2['distance_travelled'];
						$start_time = UserTravellog::travellogofflinestart_time();            //usertravellog start_time
						$card_status_before_submit = PlanCards::find()->select('status')->where(['id' => $card_id2])->one();
						if ($card_status_before_submit['status'] != 'rejected') {
							if($data2['activity'] != 'Partner Visit') {
								$command = yii::$app->db->createCommand('UPDATE plan_cards SET status="submitted",lat_position ='.$lat.',long_position ='.$long.',location_name = "'.$location.'",updated_date=NOW(),distance_travelled = "'.$distance.'", start_time = "'.$start_time.'" WHERE id="'.$card_id2.'"');
								$command->execute();
							} else {
								$res = 'channel card activity';
							}
						} else {
							$res = 'Card is rejected';
						}
					}
				}
				if ($res == 'plan cards submitted successfully') {
					$check3 = 1;
				} else {
					$check3 = 0;
				}
			}
		}

		if (array_key_exists('channel_activity', $params)) {
			if (!empty($params['channel_activity'])) {
	 		$channel_activity = $params['channel_activity']['data'];
	 		$flag = 0;
	 		foreach($channel_activity as $ch_card_activity){
	 			if ($ch_card_activity['plan_card_id'] == '') {
	 				$flag = 1;
	 			}
	 		}
	 		if ($flag == 1) {
	 			for($i = 0; $i < count($cards); $i++)
	 			{
	 				for($j = 0;$j < count($channel_activity);$j++)
	 				{
	 					if (in_array($maping_array[$i]['mapping_id'],$channel_activity[$j])) {
	 						$channel_activity[$j]['plan_card_id'] = $maping_array[$i]['primary_id'];
	 					}
	 				}
	 			}
	 		}
	 		$cht_info = "insert into channel_card_tracking_info (guid, plan_card_id, product_id, product_unit, liquidation_status, demand_volume, created_by, created_date, updated_by, updated_date) values";
	 		$cht_info2 = '';
	 		$ch_act = "insert ignore into channel_card_activities (guid, plan_card_id, target_value, actual_value, feedback, userid, created_date, created_by, updated_date, updated_by, mobile_timestamp) values";
	 		$ch_act2 = '';
	 		foreach($channel_activity as $ch_activity)
	 		{
	 			//$data = $ch_activity['data'];
	 			$lat2 = $ch_activity['lat_position'];
	 			$long2 = $ch_activity['long_position'];
	 			$location2 = $ch_activity['location_name'];
	 			if ($location2 == 'Location not available') {
	 				$location2 = PlanCards::getAddress($lat2, $long2);
	 			}
	 			$card_id3 = $ch_activity['plan_card_id'];
	 			$distance2 = $ch_activity['distance_travelled'];
	 			$guid3 = Yii::$app->guid->generate();
	 			$guid4 = Yii::$app->guid->generate();
	 			$start_time2 = UserTravellog::travellogofflinestart_time(); //usertravellog start_time
	 			$command = yii::$app->db->createCommand('UPDATE plan_cards SET status = "submitted", lat_position = '.$lat2.', long_position = '.$long2.', location_name = "'.$location2.'", distance_travelled = "'.$distance2.'", updated_date = NOW(), start_time = "'.$start_time2.'" WHERE id = "'.$card_id3.'"');
	 			$command->execute();
	 			$chct_info = $ch_activity['products'];
	 			foreach ($chct_info as $value) {
	 				$cht_info2 .= "('".$guid3."', '".$ch_activity['plan_card_id']."', '".$value['product_id']."', '".$value['product_unit']."', '".$value['liquidation_status']."', '".$value['demand_volume']."', '".$user_id."', NOW(), '".$user_id."', NOW()),";
	 			}
	 			$chact_timestamp = $ch_activity['mobile_timestamp'];
	 			if ($chact_timestamp == 'NA') {
	 				$chact_timestamp = 'RAND()';
	 			}
	 			$ch_act2 .= "('".$guid4."', '".$ch_activity['plan_card_id']."', '".$ch_activity['target_value']."', '".$ch_activity['actual_value']."', '".$ch_activity['feedback']."', '".$user_id."', NOW(), '".$user_id."', NOW(), '".$user_id."', $chact_timestamp),";
	 		}
	 		if($cht_info2 != ''){
	 			$cht_info3 = trim($cht_info2, ',');
	 			$cht_info4 = $cht_info.$cht_info3;
	 			$cht_result = Yii::$app->db->createCommand($cht_info4)->execute();
	 		}
	 		else{
	 			$cht_result = false;
	 		}

	 		if($ch_act2 != ''){
	 			$ch_act3 = trim($ch_act2, ',');
	 			$ch_act4 = $ch_act.$ch_act3;
	 			$ch_act_result = Yii::$app->db->createCommand($ch_act4)->execute();
	 		}
	 		else{
	 			$ch_act_result = false;
	 		}

			}
		}
		if ($cht_result || $ch_act_result) {
			$check4 = 1;
		} else {
			$check4 = 0;
		}
		if ($params['update_fev'] == 1) {
			if (array_key_exists('crops_favourites', $params)) {
				$crops_favourites = FavCrops::favCropsInsert($params, $user_id);
			}
			if (array_key_exists('products_favourites', $params)) {
				$products_favourites = FavProducts::favProductsInsert($params, $user_id);
			}

			if ($crops_favourites || $products_favourites) {
				$check5 = 1;
			} else {
				$check5 = 0;
			}
		}
		$crops = Crops::masteCrops();
		$products = Products::masteProducts();

		if ($check1 == 1 || $check2 == 1 || $check3 == 1 || $check4 == 1 || $check5 == 1) {
			return ['message' =>'success', 'crops' => $crops, 'products' => $products, 'sync_status' =>true];
		} else {
			return ['message' =>'fail','sync_status' =>false];
		}

	}
	public function actionDynamicform()
	{
		$company_id = Yii::$app->user->identity->input_company_id;
		$result_set = array();
		$sql_query = 'SELECT fba.*,fb.form_builder_activity_id, fb.step, fb.require, fb.mandatory, fb.label, fb.data_type,fb.product_id, fb.product_unit, fb.validation_type, fb.no_of_chars , fb.no_of_images , GROUP_CONCAT( fv.value
				SEPARATOR  "," ) AS value,CASE ac.activity_name
				WHEN "Farm and Home Visit" THEN "fhv"
				WHEN "Farmer Group Meeting" THEN "fgm"
				WHEN "Mass Campaign" THEN "mc"
				WHEN "Demonstration" THEN "demo"
				WHEN "Channel Card" THEN "partner"
				END as activity_name FROM form_builder_activities fba
				LEFT JOIN form_builder fb ON fb.form_builder_activity_id = fba.form_builder_activity_id
				LEFT JOIN form_value fv ON fb.form_builder_id = fv.form_builder_id
				LEFT JOIN activity ac ON ac.activity_id = fba.activity_id
				WHERE fba.company_id = "'.$company_id.'"
						GROUP BY fb.form_builder_id';
		$form_bulder_array = array(array('name' =>'Farm and Home Visit','key'=>'fhv'),array('name'=>'Farmer Group Meeting','key' =>'fgm'),array('name'=>'Mass Campaign','key' =>'mc'),array('name'=>'Demonstration','key' =>'demo'),array('name' => 'Partner Visit','key' =>'partner'));
		$query_result = yii::$app->db->createCommand($sql_query)->queryAll();
		
		$label_names_query = "SELECT crop_label, product_label, village_label, partner_label FROM label_names WHERE company_id =  $company_id";
		$label_names_query_result = yii::$app->db->createCommand($label_names_query)->queryOne();
		if (!empty($label_names_query_result)) {
			$result_set['label_names'] = $label_names_query_result;
		} else {
			$result_set['label_names'] = array('crop_label' => 'Crop', 'product_label' => 'Product', 'village_label' => 'Village', 'partner_label' => 'Partners');
		}
		$stepsArray1 = $stepsArray2 = array();
		if(!empty($query_result)) {
			foreach($query_result as $result) {
				if($result['activity_name'] == 'partner') {
					$stpVals = $result['step']- 1;
					$stepsArray1[] =  $stpVals;
				} else {
					$stpVals2 = $result['step']- 1;
					$stepsArray2[$result['activity_name']][] =  $stpVals2;
				}
			}
			$unique_steps = array_unique($stepsArray1);
			//echo '<pre>';print_r($stepsArray1);exit;
			foreach($query_result as $result) {
				if($result['activity_name'] == 'partner') {
					$stpVal = $result['step']- 1;
						
					if($stpVal == 0) {
				 	if($result['data_type'] == 'radio' || $result['data_type'] == 'checkbox' || $result['data_type'] == 'selectbox') {
				 		$result_set[$result['activity_name']]['steps'][$stpVal][$result['product_id']]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images'],'values'=> explode(",",$result['value']));
				 	} else {
				 		$result_set[$result['activity_name']]['steps'][$stpVal][$result['product_id']]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'validation_type' => $result['validation_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images']);
				 	}
					} else {
						if($result['data_type'] == 'radio' || $result['data_type'] == 'checkbox' || $result['data_type'] == 'selectbox') {

							$result_set[$result['activity_name']]['steps'][$stpVal]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images'],'values'=> explode(",",$result['value']));
						} else {
							$result_set[$result['activity_name']]['steps'][$stpVal]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'validation_type' => $result['validation_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images']);
						}
					}

					if(!in_array(0,$unique_steps)) {
						$result_set['partner']['steps'][0] = (object) null;
					} if(!in_array(1,$unique_steps)) {
						$result_set['partner']['steps'][1] = (object) null;
					}  if(!in_array(2,$unique_steps)) {
						$result_set['partner']['steps'][2]= (object) null;
					}
					
					ksort($result_set['partner']['steps'], SORT_REGULAR);
					
				} else {
					$stpVal2 = $result['step']- 1;
						
					if($result['data_type'] == 'radio' || $result['data_type'] == 'checkbox' || $result['data_type'] == 'selectbox') {
						$result_set[$result['activity_name']]['steps'][$stpVal2]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images'],'values'=> explode(",",$result['value']));
					} else {
						$result_set[$result['activity_name']]['steps'][$stpVal2]['fields'][] = array('label' => $result['label'],'required' => $result['require'], 'mandatory' => $result['mandatory'],'data_type' => $result['data_type'],'validation_type' => $result['validation_type'],'no_of_chars' => $result['no_of_chars'],'no_of_images' => $result['no_of_images']);
					}
					if(!in_array(0,$stepsArray2[$result['activity_name']])) {
						$result_set[$result['activity_name']]['steps'][0] = (object) null;
					}  if(!in_array(1,$stepsArray2[$result['activity_name']])) {
						$result_set[$result['activity_name']]['steps'][1] = (object) null;
					}  if(!in_array(2,$stepsArray2[$result['activity_name']])) {
						$result_set[$result['activity_name']]['steps'][2]= (object) null;
					}  if(!in_array(3,$stepsArray2[$result['activity_name']])) {
						$result_set[$result['activity_name']]['steps'][3]= (object) null;
					}  if(!in_array(4,$stepsArray2[$result['activity_name']])) {
						$result_set[$result['activity_name']]['steps'][4]= (object) null;
					}
					ksort($result_set[$result['activity_name']]['steps'], SORT_REGULAR);
				}
				
			}
		}

		$result_set['activites'] = $form_bulder_array;

		return $result_set;
		/* ksort($result_set['partner']['steps'], SORT_REGULAR);
		ksort($result_set['demo']['steps'], SORT_REGULAR);
		ksort($result_set['mc']['steps'], SORT_REGULAR);
		ksort($result_set['fhv']['steps'], SORT_REGULAR);
		ksort($result_set['fgm']['steps'], SORT_REGULAR);*/
				/* $sql_query2 = 'SELECT fb.form_builder_activity_id,fb.form_builder_id,fv.value FROM form_value fv
				       JOIN form_builder fb ON fb.form_builder_id = fv.form_builder_id';
		$query_result2 = yii::$app->db->createCommand($sql_query2)->queryAll();
		if (!empty($query_result2)) {
			foreach ($query_result2 as $key=>$value){
				$formValues[$value['form_builder_id']][] = $value['value'];
			}
		}
		if (!empty($query_result)) {
			foreach($query_result as $key=>$data){
				$id = $data['form_builder_id'];
				if(array_key_exists ($id,$formValues) ){
					$query_result[$key]['values'] = $formValues[$id];
				}
			}
		} */
		
		//return $query_result;
	}
}